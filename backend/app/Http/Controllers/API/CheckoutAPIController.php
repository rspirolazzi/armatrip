<?php

namespace App\Http\Controllers\API;

use App\Events\OrderEvent;
use App\Events\OrderEventWithMP;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCheckoutAPIRequest as Request;
use App\Models\Address;
use App\Models\Item;
use App\Models\MercadoPago;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Admin\Product;
use App\Events\OrdersStatus;
use App\Models\Store;
use App\Repositories\API\CheckoutRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JWTAuth, Response, MP;
use Coupons;


/**
 * Class CheckoutController
 * @package App\Http\Controllers\API
 */
class CheckoutAPIController extends AppBaseController
{
    /** @var  CheckoutRepository */
    private $checkoutRepository;

    public function __construct(CheckoutRepository $checkoutRepo)
    {
        $this->checkoutRepository = $checkoutRepo;
    }

    protected function getUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
    }

    public function checkout(Request $request)
    {
        $store = Store::find($request->get('store_id'));
        Log::info('==================================================');
        Log::info('Comienza transaccion para la tienda ' . $store->name);

        DB::beginTransaction();
        $success = false;
        $message = '';
        $order = new Order();
        try {
            $user = $this->getUser();
            $requestOrderItems = $request->get('orderItems');
            $orderItems = [];
            $subTotal = $total = $delivery = 0;
            Log::debug('Productos a agregar', $requestOrderItems);
            foreach ($requestOrderItems as $index => $value) {
                $product = Product::find($value['id']);
                if ($product == null) {
                    Log::error('Ocurrio un error inesperado. Uno de los productos ingresado, no es valido.', $value);
                    return $this->sendError('Ocurrio un error inesperado. Uno de los productos ingresado, no es valido.');
                }
                $orderItem = new OrderItem();
                $orderItem->product_id = $value['id'];
                $orderItem->qty = (int)$value['qty'];
                $orderItem->price = $product->price;
                $orderItem->total = $orderItem->qty * $product->price;
                $subTotal += $orderItem->qty * $product->price;
                Log::debug('Subtotal del producto', [
                    'qty' => (int)$value['qty'],
                    'price' => $product->price,
                    'total' => $orderItem->qty * $product->price,
                ]);
                if (isset($value['items'])) {
                    $itemName = function ($arr_value) {
                        return $arr_value['name'];
                    };
                    $orderItem->items = join(',', array_map($itemName, $value['items']));
                }
                $orderItems [] = $orderItem;
            }
            $order->store_id = $request->get('store_id');

            $order->favourite = $request->get('favourite') ? 1 : 0;
            $order->address_id = $request->get('address_id');
            $address = Address::find($request->get('address_id'));

            $order->address_flat = $this->getFlatAddress($address);
            $order->payment = $request->get('payment');
            $order->delivery_time = $request->get('delivery_time');
            $order->comments = $request->get('comments');
            $order->cash = $request->get('cash');
            $order->sub_total = $subTotal;
            $order->delivery = $order->store->delivery;
            $order->total = $order->sub_total + $order->delivery;

            if ($order->store->min_amount > 0 && $order->store->min_amount > $order->total) {
                Log::error('Monto minimo para el pedido', ['order_total' => $order->total, 'order->store->min_amount' => $order->store->min_amount]);
                return $this->sendError('Monto mínimo $' . $order->store->min_amount);
            }

            $coupon = null;
            $couponRequested = $request->get('coupon', null);
            if ($couponRequested) {
                $coupon = Coupons::check($couponRequested['code']);
                if ($coupon) {
                    $order->discount = $order->sub_total * ($coupon->reward / 100);
                    $order->total = $order->total - $order->discount;
                }
            }

            $order->comments = $request->get('comments', "");
            $order->user_id = $user->id;
            $order->status = Order::$STATUS_PENDING;
            $order->items = count($orderItems);
            if ($order->payment == Order::$PAYMENT_CASH && $order->total > $order->cash) {
                Log::error('"total_insuficiente" esta vacio en la orden', ['orden' => $order->toArray()]);
                return $this->sendError('El pago ingresado es menor al total de la orden');
            }
            $order->reserved_at = Carbon::now();
            if ($order->save()) {

                if ($coupon) {
                    $order->user->redeemCode($coupon->code, $order->id);
                }


                foreach ($orderItems as $index => $orderItem) {
                    $orderItem->order_id = $order->id;
                    $orderItem->save();

                    $items_arr = explode(',', $orderItem->items);

                    $items = Item::whereIn('name', $items_arr)->pluck('id');

                    $orderItem->items()->attach($items);
                }
                $success = true;
            }
        } catch (\Exception $e) {
            Log::error('Ocurrio un error inesperado.', ['error' => $e->getMessage(), 'file' => $e->getFile()]);
            $success = false;
            $message = 'Ocurrio un error inesperado.';
        } catch (InvalidCouponException $e) {
            Log::error('Ocurrio un error al intentar obtener el cupon.', ['error' => $e->getMessage(), 'file' => $e->getFile()]);
            $success = false;
            $message = 'El cupon ingresado no es valido.';
        }

        if ($success) {
            DB::commit();
            Log::debug('Se realizo el commit');
            if ($order->payment == Order::$PAYMENT_CARD) {
                //MercadoPago
                if (empty($request->get('mp_notification_id'))) {
                    Log::error('"mp_notification_id" esta vacio en la orden', ['orden' => $order->toArray()]);
                    return $this->sendError('Ocurrio un error con el metodo de pago seleccionado. No se proceso ninguna tarjeta de credito');
                }
                $MPAccessToken = env('MP_ACCESS_TOKEN');
                if ($order->store->getMPKey()) {
                    $MPAccessToken = $order->store->getMPAccessToken();
                    Log::debug('MP_ACCESS_TOKEN de la tienda', ['tienda' => $order->store->name]);
                } else {
                    Log::debug('usando MP_ACCESS_TOKEN global');
                }

                $mp = new MP($MPAccessToken);
                Log::debug('Generando payment_data');
                $mercadoPago = MercadoPago::create([
                    "order_id" => $order->id,
                    "transaction_amount" => $order->total,
                    "token" => $request->get('mp_notification_id'),
                    "description" => "Compra generada por {$order->user->email} en la sucursal {$order->store->name}",
                    "payment_method_id" => $request->get('payment_method_id'),
                    "status" => 'process',
                ]);
                Log::debug('payment_data generado', ['payment_data' => $mercadoPago->toPaymentData()]);
                $payment = $mp->post("/v1/payments", $mercadoPago->toPaymentData());
                Log::debug('Resultado de payment_data', ['payment' => $payment]);

                $mercadoPago->body = serialize($payment);
                $mercadoPago->status = $payment['response']['status'];
                $mercadoPago->save();
                if ($mercadoPago->status == MercadoPago::$STATUS_APPROVED) {
                    event(new OrderEvent($order));
                    event(new OrdersStatus(''));
                    return $this->sendResponse($order->toArray(), 'La orden se genero con exito');
                } else if ($mercadoPago->status == MercadoPago::$STATUS_REJECTED) {
                    $order->status = Order::$STATUS_CANCELLED;
                    $order->save();
                    switch ($payment['response']['status_detail']) {
                        case 'cc_rejected_bad_filled_card_number':
                            $message = "Revisa el número de tarjeta.";
                            break;
                        case 'cc_rejected_bad_filled_date':
                            $message = "Revisa la fecha de vencimiento.";
                            break;
                        case 'cc_rejected_bad_filled_other':
                            $message = "Revisa los datos.";
                            break;
                        case 'cc_rejected_bad_filled_security_code':
                            $message = "Revisa el código de seguridad.";
                            break;
                        case 'cc_rejected_blacklist':
                            $message = "No pudimos procesar tu pago.";
                            break;
                        case 'cc_rejected_call_for_authorize':
                            $message = "Debes autorizar ante {$payment['response']['payment_method_id']} el pago de \${$order->total} a MercadoPago";
                            break;
                        case 'cc_rejected_card_disabled':
                            $message = "Llama a {$payment['response']['payment_method_id']} para que active tu tarjeta. El teléfono está al dorso de tu tarjeta.";
                            break;
                        case 'cc_rejected_card_error':
                            $message = "No pudimos procesar tu pago.";
                            break;
                        case 'cc_rejected_duplicated_payment':
                            $message = "Ya hiciste un pago por ese valor. Si necesitas volver a pagar usa otra tarjeta u otro medio de pago.";
                            break;
                        case 'cc_rejected_high_risk':
                            $message = "Tu pago fue rechazado. Elige otro de los medios de pago, te recomendamos con medios en efectivo.";
                            break;
                        case 'cc_rejected_insufficient_amount':
                            $message = "Tu {$payment['response']['payment_method_id']} no tiene fondos suficientes.";
                            break;
                        case 'cc_rejected_invalid_installments':
                            $message = "{$payment['response']['payment_method_id']} no procesa pagos en installments cuotas.";
                            break;
                        case 'cc_rejected_max_attempts':
                            $message = "Llegaste al límite de intentos permitidos. Elige otra tarjeta u otro medio de pago.";
                            break;
                        case 'cc_rejected_other_reason':
                            $message = "{$payment['response']['payment_method_id']} no procesó el pago.";
                            break;
                        default:
                            $message = 'Ocurrio un error al intentar realizar el pago con tu tarjeta';
                            break;
                    }
                    return $this->sendError($message);
                } else if ($mercadoPago->status == MercadoPago::$STATUS_IN_PROCESS) {
                    event(new OrderEventWithMP($order, $mercadoPago));
                    event(new OrdersStatus(''));
                    return $this->sendError($message);
                }
            }
            if ($order->status != Order::$STATUS_CANCELLED) {
                event(new OrderEvent($order));
                event(new OrdersStatus(''));
                return $this->sendResponse($order->toArray(), 'La orden se genero con exito');
            } else {
                return $this->sendError('Ocurrio un error. La orden fue cancelada');
            }
        } else {
            DB::rollback();
            return $this->sendError($message);
        }
    }
}
