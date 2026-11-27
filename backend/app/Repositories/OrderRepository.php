<?php

namespace App\Repositories;

use App\Events\OrderAfterEvent;
use App\Events\OrderBeforeEvent;
use App\Exceptions\MercadoPagoException;
use App\Exceptions\OrderValidationException;
use App\Exceptions\PackageNotFoundException;
use App\Exceptions\PackageWrongProductsCount;
use App\Exceptions\ProvinceNotFoundException;
use App\Exceptions\StoreNotFoundException;
use App\Helpers\Errors;
use App\Http\Requests\API\CreateOrderAPIRequest;
use App\Models\Admin\Package;
use App\Models\Admin\Province;
use App\Models\Admin\SettingManager;
use App\Models\Order;
use App\Models\Store;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Common\BaseRepository;
use Validator, MP, Coupons;

;

use Illuminate\Http\Request;
use App\Models\MercadoPago;
use Error;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version May 20, 2018, 2:07 pm UTC
 *
 * @method Order findWithoutFail($id, $columns = ['*'])
 * @method Order find($id, $columns = ['*'])
 * @method Order first($columns = ['*'])
 */
class OrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'address_id',
        'address_flat',
        'items',
        'payment',
        'comments',
        'cash',
        'sub_total',
        'delivery',
        'total',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }

    protected function getPackage($package_code)
    {
        $package = Package::where('code', $package_code)->first();
        if (!$package) {
            throw new PackageNotFoundException();
        }
        return $package;
    }

    protected function getStore($store_code)
    {
        $store = Store::where('code', $store_code)->first();
        if (!$store) {
            throw new StoreNotFoundException();
        }
        return $store;
    }

    protected function getProvince($province_code)
    {
        $province = Province::where('code', $province_code)->first();
        if (!$province) {
            throw new ProvinceNotFoundException();
        }
        return $province;
    }

    public function getNextOrderNumber()
    {
        $id = Order::max('id');
        $number = Order::getNumber(config('armatrip.prefix_order'), ($id ? ($id + 1) : 1));
        return $number;
    }

    /**
     * @param CreateOrderAPIRequest $request
     * @param User $user
     * @return Order|mixed
     * @throws \Exception
     */
    public function checkout(CreateOrderAPIRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $order = $user->active_order;

            $package = $order->package->package;
            $province = $order->province;

            $tender_installment = $order->package->installment;
            $tender_tax = $order->package->tax;

            $products_code = collect($request->get('products'))->map(function ($value) {
                return $value;
            })->all();

            $products = $package->products->whereIn('code', $products_code)->toArray();
            Log::debug('Productos a agregar', $products_code);

            if ($package->code !== $request->get('code', null)) {
                throw new PackageNotFoundException('El paquete seleccionado no existe');
            }
            if (collect($products_code)->count() !== collect($products)->count()) {
                throw new PackageWrongProductsCount('La cantidad de productos no coincide con la cantidad seleccionada');
            }
            $products = $this->calculatePriceByProducts($products, $province);
            $total = $this->calculateTotalPriceByProducts($package->finalPrice, $products);

            $total = \App\Helpers\Helper::applyTax($total, $tender_tax);
            $total = \App\Helpers\Helper::applyTax($total, SettingManager::get('increment_percent', 0));

            $dateRange = $order->package->findDateRange();

            if ($dateRange) {
                $total = \App\Helpers\Helper::applyTax($total, $dateRange->tax);
            }

            $coupon = $order->coupon;
            if ($coupon) {
                $coupon = Coupons::check($order->coupon->code);
                if ($coupon) {
                    $discount = ($coupon->reward / 100);
                    $order->discount = $total * $discount;
                    $total = $total - ($total * $discount);
                }
            }

            $order->total = $total * $order->package->qty;
            $order->sub_total = $total;
            $order->status = Order::$STATUS_FINISHED;
            $order->package->tax = $tender_tax;
            $order->package->installment = $tender_installment;
            $order->package->save();
            collect($products)->each(function ($product) use (&$order) {
                $qty = 1;
                $product['price'] = !is_null($product['price']) ? $product['price'] : 0;
                $order->products()->create([
                    'product_id' => $product['id'],
                    'price' => $product['price'],
                    'qty' => $qty,
                    'total' => $qty * $product['price'],
                ]);
            });
            event(new OrderBeforeEvent($order, $request));
            $order->save();
            $user->active_order_id = null;
            $user->save();
            DB::commit();
            event(new OrderAfterEvent($order, $request));
            return $order;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    protected function calculateTotalPriceByProducts($price, $products)
    {
        $total = collect($products)->reduce(function ($total, $product) {
            $price = (float)$product['price'];
            Log::debug('Precio producto', ["{$product['code']}" => $price]);
            return $total + $price;
        }, $price);

        return round($total, 0);
    }

    protected function calculatePriceByProducts($products, Province $province)
    {
        return collect($products)->map(function ($product) use ($province) {
            if ($province && $province->tax > 0 && $product['code'] == SettingManager::get('tax_product_code', config('armatrip.tax.product_code'))) {
                $tax = $product['price'] * ($province->tax / 100);
                $product['price'] = $tax + $product['price'];
            }
            return $product;
        });
    }


    public function findOrCreate(User $user, Request $request)
    {
        $order_values = $request->only('school_name', 'school_city', 'school_id', 'store_code', 'province_code', 'package_code', 'qty', 'qty_adults', 'qty_children', 'qty_babies');
        $order_date_values = $request->only('year', 'month', 'biweekly', 'date_range_id');

        $store = $this->getStore($order_values['store_code']);
        $province = $this->getProvince($order_values['province_code']);
        $package = $this->getPackage($order_values['package_code']);

        $result_dates = null;
        if (!empty($order_date_values['date_range_id'])) {
            $result_dates = $package->dateRanges->firstWhere('id', $order_date_values['date_range_id']);
        } else {
            $result_dates = $package->findDateRange(
                $order_date_values['year'],
                $order_date_values['month'],
                $order_date_values['biweekly']
            );
        }

        if ($result_dates === null) {
            throw new OrderValidationException('Paquete no disponible para las fechas seleccionadas');
        }
        $attributes = [
            'qty_adults' => !empty($order_values['qty_adults']) ? $order_values['qty_adults'] : 0,
            'qty_children' => !empty($order_values['qty_children']) ? $order_values['qty_children'] : 0,
            'qty_babies' => !empty($order_values['qty_babies']) ? $order_values['qty_babies'] : 0,
            'store_id' => $store->id,
            'province_id' => $province->id,
            'total' => 0,
            'sub_total' => 0,
            'school_id' => !empty($order_values['school_id']) ? $order_values['school_id'] : null,
            'school_name' => !empty($order_values['school_name']) ? $order_values['school_name'] : null,
            'school_city' => !empty($order_values['school_city']) ? $order_values['school_city'] : null,
        ];
        if (!empty($order_date_values['date_range_id'])) {
            $attributes['date_start'] = $result_dates['date_start'];
            $attributes['date_end'] = $result_dates['date_end'];
        } else {
            $attributes['year'] = $order_date_values['year'];
            $attributes['month'] = $order_date_values['month'];
            $attributes['biweekly'] = $order_date_values['biweekly'];
        }

        $order = $this
            ->findWhere(['user_id' => $user->id, 'status' => Order::$STATUS_PENDING])
            ->first();
        if ($order) {
            $order->update($attributes);
        } else {
            $order = $this->create(
                array_merge(
                    $attributes,
                    ['user_id' => $user->id],
                    ['number' => $this->getNextOrderNumber()]
                ));
        }

        $qty = $order_values['qty'];
        $order_package = $order->package;
        $price = $package->finalPrice;

        $tender_installment = $package->installment;

        if (!empty($order_date_values['date_range_id'])) {
            $orderDate = $result_dates->date_start;
        } else {
            $orderDate = Carbon::createFromDate($order->year, $order->month, 30);
        }

        $currentDate = Carbon::now();
        $diffInMonths = $orderDate->diffInMonths($currentDate);

        if ($diffInMonths < $tender_installment) {
            $tender_installment = ($diffInMonths > 0 ? $diffInMonths : 1);
        }
        $tender_tax = $package->tax * $tender_installment;

        if ($order_package) {
            $order_package->package_id = $package->id;
            $order_package->price = $price;
            $order_package->qty = $qty;
            $order_package->total = $price * $qty;
            $order_package->tax = $tender_tax;
            $order_package->installment = $tender_installment;
            $order_package->save();
        } else {
            $order->package()->create([
                'package_id' => $package->id,
                'price' => $price,
                'qty' => $qty,
                'total' => $price * $qty,
                'tax' => $tender_tax,
                'installment' => $tender_installment,
            ]);
        }
        $user->active_order_id = $order->id;
        $user->save();
        Log::info('La orden se guardo con éxito', $order->toArray());
        return $order;
    }

    /**
     * Metodo para procesar pago de orden por mercado pago
     *
     * @param Order $order
     * @param Request $request
     * @return void
     */
    public function processMp(Request $request, Order $order)
    {
        if ((bool)SettingManager::get('mp_enabled', config('armatrip.mercadopago.enabled'))) {
            $return_value = ['status' => false, 'message' => 'El medio de pago no esta habilitado', 'errorCode' => Errors::$E_MP_DISABLED];
            Log::info('Comenzado a procesar pago por MercadoPago');
            try {
                if ($request->get('mp_notification_id', null)) {

                    $MP_TEST = SettingManager::get('mp_enabled_test', (config('armatrip.mercadopago.enabled_test') ?? true));
                    $MP_ACCESS_TOKEN = '';

                    if ($MP_TEST) {
                        $MP_ACCESS_TOKEN = SettingManager::get('mp_access_token_test', config('armatrip.mercadopago.access_token_test'));
                    } else {
                        Log::warning('Se esta utilizando MP en modo productivo');
                        $MP_ACCESS_TOKEN = SettingManager::get('mp_access_token', config('armatrip.mercadopago.access_token'));
                    }

                    Log::debug('Variables de MP', [
                        'MP_TEST' => $MP_TEST,
                    ]);

                    $amount_mp = 0;

                    if ((bool)SettingManager::get('mp_enabled', false)) {
                        $percent = SettingManager::get('mp_advance_amount_percentage', 0);
                        if ($percent > 0) {
                            $amount_mp = round($order->package->price * ($percent / 100));
                        }
                    }

                    if ($amount_mp <= 0) {
                        throw new Exception('El amount no pude ser cero', $order->toArrayAdmin());
                    }

                    if ($amount_mp != $request->get('amount', 0)) {
                        Log::warning('El amount enviado no conincide con el calculado', [
                            'calculado' => $amount_mp,
                            'enviado' => $request->get('amount', 0),
                        ]);
                    }

                    if ($order->mercadoPago && $order->mercadoPago->status == MercadoPago::$STATUS_APPROVED) {
                        Log::info('Ya se realizó una reserva por este viaje', $order->toArrayAdmin());
                        throw new Exception('Ya se realizó una reserva por este viaje', $order->toArrayAdmin());
                    }

                    $mp = new MP($MP_ACCESS_TOKEN);
                    Log::debug('Generando payment_data');
                    $mercadoPago = MercadoPago::create([
                        "order_id" => $order->id,
                        "order_type" => Order::class,
                        "transaction_amount" => $amount_mp,
                        "token" => $request->get('mp_notification_id'),
                        "description" => "Compra generada por {$order->user->email} para la empresa {$order->store->name}",
                        "payment_method_id" => $request->get('payment_method_id'),
                        "status" => 'process',
                    ]);
                    Log::debug('payment_data generado', ['payment_data' => $mercadoPago->toPaymentData()]);
                    try {
                        $payment = $mp->post("/v1/payments", $mercadoPago->toPaymentData());
                        Log::debug('Resultado de payment_data', $payment);

                        $mercadoPago->body = serialize($payment);
                        $mercadoPago->status = $payment['response']['status'];
                        $mercadoPago->save();
                        if ($mercadoPago->status == MercadoPago::$STATUS_APPROVED) {
                            //event(new OrderEvent($order));
                            //event(new OrdersStatus(''));
                            //return $this->sendResponse($order->toArray(), 'La orden se genero con exito');
                            Log::info('El pago se aprobo con éxito', $payment);
                            $return_value = ['status' => true, 'message' => 'El pago se aprobo con éxito', 'errorCode' => Errors::$E_0];
                        } else if ($mercadoPago->status == MercadoPago::$STATUS_REJECTED) {
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
                            Log::error('El pago fue rechazado: ' . $message, $payment);
                            $return_value = ['status' => false, 'message' => $message, 'errorCode' => Errors::$E_MP_HANDLER];
                        } else if ($mercadoPago->status == MercadoPago::$STATUS_IN_PROCESS) {
                            Log::warning('El pago esta en proceso', $payment);
                            $return_value = ['status' => true, 'message' => 'El pago se registro con éxito y esta en proceso', 'errorCode' => Errors::$E_MP_UNKNOWN];
                        }
                    } catch (Exception $e) {
                        $mercadoPago->body = serialize([]);
                        $mercadoPago->status = MercadoPago::$STATUS_EXCEPTION;
                        $mercadoPago->save();
                        $return_value = ['status' => false, 'message' => 'Ocurrio un error inesperado, por favor reintentelo nuevamente', 'errorCode' => Errors::$E_UNKNOWN];
                        Log::error('Ocurrio un error en MP', $mercadoPago->toPaymentData());
                        Log::error($e->getMessage());
                        Log::error('Detalle:', ['error' => $e->getTraceAsString()]);
                    }

                } else {
                    Log::debug('No hay mp_notificatoin_id para procesar esta orden', [
                        'order_id' => $order->id,
                    ]);
                }
            } catch (Exception $e) {
                Log::error('Ocurrio un error inesperado al procesar el pago por MercadoPago', [
                    'error' => $e->getTraceAsString()
                ]);
                $return_value = ['status' => false, 'message' => 'Ocurrio un error inesperado, por favor reintentelo nuevamente', 'errorCode' => Errors::$E_UNKNOWN];
            }
            Log::info('Fin del proceso de pago por MercadoPago');
            return $return_value;
        }
    }
}