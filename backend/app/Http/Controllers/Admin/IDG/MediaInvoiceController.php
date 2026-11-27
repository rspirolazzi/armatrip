<?php

namespace App\Http\Controllers\Admin\IDG;

use App\Http\Controllers\AppBaseController;
use App\Models\Admin\MediaInvoice;
use App\Models\Admin\MediaInvoiceItem;
use Setting;
use App\Models\Admin\IDG\Category;
use App\Models\MercadoPago;
use App\Models\Admin\Product;
use App\Repositories\Admin\MediaInvoiceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Auth, Flash, PDF, Response, MP;

class MediaInvoiceController extends AppBaseController
{

    /** @var  MediaInvoiceRepository */
    private $mediaInvoiceRepository;

    public function __construct(MediaInvoiceRepository $mediaInvoiceRepository)
    {
        $this->mediaInvoiceRepository = $mediaInvoiceRepository;
    }

    /**
     * Display a listing of the MediaInvoice.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->mediaInvoiceRepository->pushCriteria(new RequestCriteria($request));
        //$this->mediaInvoiceRepository->pushCriteria(new StoreCriteria($request));
        $this->mediaInvoiceRepository->scopeQuery(function ($query) use($request){
            $query->where('model_type', MediaInvoice::class);

            $orderBy = $request->get(config('repository.criteria.params.orderBy', 'orderBy'), null);
            if(is_null($orderBy)){
                $sortedBy = $request->get(config('repository.criteria.params.sortedBy', 'sortedBy'), 'desc');
                $query->orderBy('created_at', $sortedBy);
            }
            return $query;
        });
        $media_invoices = $this->mediaInvoiceRepository->paginate(25);

        return view('admin.media_invoices.index')
            ->with('media_invoices', $media_invoices);
    }

    /**
     * Show the form for creating a new MediaInvoice.
     *
     * @return Response
     */
    public function create()
    {
        $category = Category::allowedToSellParents()->first();
        return view('admin.media_invoices.create')
            ->with('category', $category)
            ;
    }

    /**
     * Show the form for creating a new MediaInvoice.
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function category($id, Request $request)
    {
        $category = Category::find($id);
        $products = Product::where('category_id', $id)->get();

        return view('admin.media_invoices.category')
            ->with('category', $category)
            ->with('products', $products);
    }

    /**
     * Store a newly created MediaInvoice in storage.
     *
     * @param CreateMediaInvoiceRequest $request
     *
     * @return Response
     */
    public function store(CreateMediaInvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            Log::info('Se comienza a generar el borrador de el pedido interno');
            $order_number = MediaInvoice::generateOrderNumber(Auth::user()->store_id);
            $media_invoice = MediaInvoice::create([
                'status' => MediaInvoice::$STATUS_DRAFT,
                'user_id' => Auth::user()->id,
                'model_type' => MediaInvoice::class,
                'store_id' => Auth::user()->store_id,
                'order_number' => $order_number,
            ]);
            Log::debug('Se genero con éxito el pedido interno', $media_invoice->toArray());
            $items = $request->get('items');
            $itemsFiltered = collect($items)->map(function ($values, $model_type) {
                $temp = collect($values)->filter(function ($qty) {
                    return (int)$qty > 0;
                })->all();
                return $temp;
            })->filter(function ($values) {
                return sizeof($values) > 0;
            });
            if (sizeof($itemsFiltered) === 0) {
                throw new ValidatorException('No hay cantidades agregadas');
            }
            $itemsFiltered->each(function ($values, $model_type) use ($media_invoice) {
                collect($values)->each(function ($qty, $id) use ($media_invoice, $model_type) {
                    //dd($id, $qty);
                    $media_invoiceItem = new MediaInvoiceItem([
                        'model_type' => $model_type,
                        'model_id' => $id,
                        'qty' => $qty,
                    ]);
                    $media_invoice->items()->save($media_invoiceItem);
                });
            });
            DB::commit();
            Log::info('Se completo con éxito la creacion del pedido', $media_invoice->toArray());
            Flash::success('El pedido se genero con éxito');
            return redirect(route('admin.media_invoices.confirm', [$media_invoice->id]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ocurrio un error inesperado al intentar generar el pedido interno', ['message' => $e->getMessage()]);
            Flash::error('Ocurrio un error al generar el pedido');
            return redirect(route('admin.media_invoices.create'));
        }
        return redirect(route('admin.media_invoices.index'));
    }

    /**
     * Show the form for editing the specified MediaInvoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function print($id)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice)) {
            Flash::error('Pedido interno no encontrado');

            return redirect(route('admin.media_invoices.index'));
        }
        $products = Product::where('allowed_to_sell', 1)->orderBy('order', 'asc')->get();

        $pdf = PDF::loadView('admin.media_invoices.print', ['mediaInvoice' => $media_invoice, 'products' => $products]);
        return $pdf->download("pedido-{$media_invoice->order_number}.pdf");


        return view('admin.media_invoices.print')
            ->with('media_invoice', $media_invoice)
            ->with('products', $products);

    }

    /**
     * Show the form for editing the specified MediaInvoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function confirm($id)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
            Flash::error('Pedido interno no encontrado');

            return redirect(route('admin.media_invoices.index'));
        }

        return view('admin.media_invoices.confirm')->with('media_invoice', $media_invoice);
    }

    /**
     * Show the form for editing the specified MediaInvoice.
     *
     * @param  int $id
     *
     * @param UpdateMediaInvoiceRequest $request
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function confirm_invoice($id, UpdateMediaInvoiceRequest $request)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
            Flash::error('Pedido interno no encontrado');

            return redirect(route('admin.media_invoices.index'));
        }

        $media_invoice = $this->mediaInvoiceRepository->update([
            'comment' => $request->get('comment'),
            'status' => MediaInvoice::$STATUS_CONFIRMED,
            'sent_at' => Carbon::now(),
        ], $id);

        Flash::success('El pedido interno se actualizo con éxito.');

        return redirect(route('admin.media_invoices.index'));
    }

    /**
     * Update the specified MediaInvoice in storage.
     *
     * @param  int $id
     * @param UpdateMediaInvoiceRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateMediaInvoiceRequest $request)
    {
        Log::info('Se intentara actualizar la MediaInvoice ' . $id);
        DB::beginTransaction();
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);
        try {
            if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
                Flash::error('Pedido interno no encontrado');
                return redirect(route('admin.media_invoices.index'));
            }
            $itemsFiltered = $request->get('items');
            collect($itemsFiltered)->each(function ($values, $model_type) use ($media_invoice) {
                collect($values)->each(function ($qty, $id) use ($media_invoice, $model_type) {
                    $media_invoiceItemIndex = $media_invoice->items->search(function ($item) use ($id, $model_type) {
                        return ($item->model_id == $id && $item->model_type == $model_type);
                    });
                    $qty = (int)$qty;
                    if ($media_invoiceItemIndex !== false) {
                        if ($qty > 0) {
                            $media_invoice->items[$media_invoiceItemIndex]->qty = $qty;
                            $media_invoice->items[$media_invoiceItemIndex]->save();
                            Log::debug('Se actualiza la cantidad para el item', $media_invoice->items[$media_invoiceItemIndex]->toArray());
                        } else {
                            Log::debug('Se elmina el producto en el pedido', $media_invoice->items[$media_invoiceItemIndex]->toArray());
                            $media_invoice->items[$media_invoiceItemIndex]->delete();
                        }
                    } else if ($qty > 0) {
                        $media_invoiceItem = new MediaInvoiceItem([
                            'model_type' => $model_type,
                            'model_id' => $id,
                            'qty' => $qty,
                        ]);
                        $media_invoice->items()->save($media_invoiceItem);
                        Log::debug('Se agrega un nuevo item para el pedido', $media_invoiceItem->toArray());
                    }
                });
            });
            DB::commit();
            Log::info('Se completo con éxito la actualización del pedido', $media_invoice->toArray());
            Flash::success('El pedido interno se actualizo con éxito.');
            return redirect(route('admin.media_invoices.confirm', $media_invoice->id));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ocurrio un error al intentar actualizar el pedido interno', ['message' => $e->getMessage()]);
            Log::error('Ocurrio un error al intentar actualizar el pedido interno', $media_invoice->toArray());
            Flash::error('Ocurrio un error al intentar actualizar el pedido interno');
            return redirect(route('admin.media_invoices.edit', $media_invoice->id));
        }
    }

    /**
     * Display the specified MediaInvoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice)) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.media_invoices.index'));
        }

        return view('admin.media_invoices.show')->with('media_invoice', $media_invoice);
    }

    /**
     * Remove the specified MediaInvoice from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.media_invoices.index'));
        }

        $this->mediaInvoiceRepository->delete($id);

        Flash::success('El pedido interno borró con éxito.');

        return redirect(route('admin.media_invoices.index'));
    }

    public function update_item(Request $request)
    {
        DB::beginTransaction();
        try {
            $cart = $this->mediaInvoiceRepository->findOrCreateCart();
            $qty = $request->get('qty', 0);
            $product = Product::find($request->get('id', null));
            //dd($product, $request->toArray());
            $item = $cart->items()->where('model_id', $product->id)->first();
            if ($product) {
                if ($qty == 0) {
                    $item->delete();
                    Log::info('Se quitó con éxito el item ' . $product->id);
                    Flash::success('El artículo ' . $product->name . ' se eliminó con éxito.');
                } else if ($product->min <= $qty && $product->max >= $qty) {
                    if ($item) {
                        $item->qty = $qty;
                        $item->save();
                    } else {
                        $cart->items()->create([
                            'model_type' => Product::class,
                            'model_id' => $product->id,
                            'qty' => $qty,
                            'price' => $product->price,
                            'total' => $product->price * $qty,
                            'extra_1' => '',
                            'extra_2' => '',
                            'extra_3' => '',
                        ]);
                    }
                    Log::info('Se argegó con éxito el item ' . $product->id);
                    Flash::success('El artículo ' . $product->name . ' se agregó con éxito.');
                } else if ($product->min > $qty) {
                    Log::debug('El producto ' . $product->name . ' no admite una cantidad menor a ' . $product->min, ['qty' => $qty]);
                    Flash::warning('La cantidad mínima a comprar para ' . $product->name . ' es de ' . $product->min);
                } else if ($product->max < $qty) {
                    Log::debug('El producto ' . $product->name . ' no admite una cantidad mayor a ' . $product->max, ['qty' => $qty]);
                    Flash::warning('La cantidad máxima a comprar para ' . $product->name . ' es de ' . $product->max);
                }
            } else {
                Log::info('El producto con id ' . $request->get('id') . ' no existe');
            }
            $cart->total = collect($cart->items)->sum(function ($item) {
                return (float)$item->price * (float)$item->qty;
            });
            $cart->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Ocurrio un error inesperado', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            Flash::error('Ocurrio un error inesperado.');
        }
        return redirect($request->get('redirect_to', route('admin.media_invoices.create')));
    }

    public function update_mass_item(Request $request)
    {
        $redirect_to = $request->get('redirect_to', route('admin.media_invoices.create'));
        DB::beginTransaction();
        try {
            $action = $request->get('action', 'update');

            $items = collect($request->get('items', []))->map(function ($qty, $id) {
                return ["qty" => $qty, "id" => $id];
            });
            $cart = $this->mediaInvoiceRepository->findOrCreateCart();
            if ($action === 'empty') {
                $items = collect($items)->map(function ($value) {
                    $value["qty"] = 0;
                    return $value;
                    //dd($value);
                });
            }
            $items->each(function ($value) use ($cart) {
                $qty = $value['qty'];
                $item = $cart->items()->where('id', $value['id'])->first();
                $product = $item->model;
                if ($qty == 0) {
                    $item->delete();
                    Log::info('Se quitó con éxito el item ' . $value['id']);
                } else if ($product->min <= $qty && $product->max >= $qty) {
                    if ($item) {
                        $item->qty = $qty;
                        $item->price = $item->model->price;
                        $item->total = $item->model->price * $qty;
                        $item->save();
                        Log::info('Se actualizó con éxito el item ' . $value['id']);
                    }
                } else if ($product->min > $qty) {
                    Log::debug('El producto ' . $product->name . ' no admite una cantidad menor a ' . $product->min, ['qty' => $qty]);
                    Flash::warning('La cantidad mínima a comprar para ' . $product->name . ' es de ' . $product->min);
                } else if ($product->max < $qty) {
                    Log::debug('El producto ' . $product->name . ' no admite una cantidad mayor a ' . $product->max, ['qty' => $qty]);
                    Flash::warning('La cantidad máxima a comprar para ' . $product->name . ' es de ' . $product->max);
                }
            });

            $cart->total = collect($cart->items)->sum(function ($item) {
                return (float)$item->price * (float)$item->qty;
            });
            if ($action === 'update_continue') {
                Flash::success('El Pedido interno se actualizó con éxito.');
                $redirect_to = route('admin.media_invoices.payment', $cart->id);
            } else if ($action === 'update') {
                Flash::success('El Pedido interno se actualizó con éxito.');
            } else if ($action === 'empty') {
                Flash::success('El Pedido interno se vació con éxito.');
            }
            $cart->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Ocurrio un error inesperado', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            Flash::error('Ocurrio un error inesperado.');
        }
        return redirect($redirect_to);
    }

    public function selectPayment($id, Request $request)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        $this->validate($request, ['payment' => 'required']);

        if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.media_invoices.index'));
        }
        Log::info('Se selecciono el medio de pago ' . $request->get('payment') . ' para el pedido', $media_invoice->toArray());
        switch ($request->get('payment')) {
            case MediaInvoice::$PAYMENT_CASH:
            case MediaInvoice::$PAYMENT_TRANSFER:
                $media_invoice->status = MediaInvoice::$STATUS_CONFIRMED;
                $media_invoice->sent_at = Carbon::now();
                $media_invoice->save();
                Log::info('El pedido interno se guardo con éxito', array_merge($media_invoice->toArray(), ['paymentSelected' => $request->get('payment')]));
                Flash::success('El pedido interno se guardo con éxito.');
                return redirect(route('admin.media_invoices.index'));
                break;
            case MediaInvoice::$PAYMENT_CARD:
                return redirect(route('admin.media_invoices.creditCard', $media_invoice->id));
                break;
        }
        Log::error('No se pudo procesar la opción de pago', array_merge($media_invoice->toArray(), ['paymentSelected' => $request->get('payment')]));
        Flash::error('La opción de pago ingresada es incorrecta');

        return view('admin.media_invoices.payment')
            ->with('media_invoice', $media_invoice);
    }

    /**
     * Display the specified MediaInvoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function payment($id)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.media_invoices.index'));
        }

        return view('admin.media_invoices.payment')->with('media_invoice', $media_invoice);
    }

    /**
     * Display the specified MediaInvoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function creditCard($id)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.media_invoices.index'));
        }

        return view('admin.media_invoices.credit_card')->with('media_invoice', $media_invoice);
    }

    /**
     * Display the specified MediaInvoice.
     *
     * @param  int $id
     *
     * @param Request $request
     * @return Response
     */
    public function cardPayment($id, Request $request)
    {
        $media_invoice = $this->mediaInvoiceRepository->findWithoutFail($id);

        if (empty($media_invoice) || $media_invoice->status !== MediaInvoice::$STATUS_DRAFT) {
            Flash::error('El pedido interno no se encontró');

            return redirect(route('admin.media_invoices.index'));
        }
        Log::debug('Comienza la transacción');
        DB::beginTransaction();
        try {
            //MercadoPago
            if (empty($request->get('mp_notification_id'))) {
                Log::error('"mp_notification_id" esta vacio en la orden', ['orden' => $media_invoice->toArray()]);
                return $this->sendError('Ocurrio un error con el metodo de pago seleccionado. No se proceso ninguna tarjeta de credito');
            }
            $mp_enabled_test = Setting::get('mp_enabled_test', config('armatrip.mercadopago.enabled_test'));
            if ($mp_enabled_test) {
                $MPAccessToken = Setting::get('mp_access_token_test', config('armatrip.mercadopago.access_token'));
                Log::debug('MP_ACCESS_TOKEN de test');
            } else {
                $MPAccessToken = Setting::get('mp_access_token', config('armatrip.mercadopago.access_token'));
                Log::debug('MP_ACCESS_TOKEN de produccion');
            }

            $mp = new MP($MPAccessToken);
            Log::debug('Generando payment_data');
            $mercadoPago = MercadoPago::create([
                "order_id" => $media_invoice->id,
                "order_type" => MediaInvoice::class,
                "transaction_amount" => $media_invoice->total,
                "token" => $request->get('mp_notification_id'),
                "description" => "Pedido interno: Compra generada por {$media_invoice->user->email} en la sucursal {$media_invoice->store->name}",
                "payment_method_id" => $request->get('payment_method_id'),
                "status" => 'process',
                "payer" => array(
                    "email" => $media_invoice->user->email
                ),
            ]);
            //dd($mercadoPago, $mercadoPago->toPaymentData());
            Log::debug('payment_data generado', ['payment_data' => $mercadoPago->toPaymentData()]);
            $payment = $mp->post("/v1/payments", $mercadoPago->toPaymentData());
            Log::debug('Resultado de payment_data', ['payment' => $payment]);

            //dd($payment);

            $mercadoPago->body = serialize($payment);
            $mercadoPago->status = $payment['response']['status'];
            $mercadoPago->save();
            if ($mercadoPago->status == MercadoPago::$STATUS_APPROVED) {
                $media_invoice->status = MediaInvoice::$STATUS_CONFIRMED;
                $media_invoice->sent_at = Carbon::now();
                $media_invoice->save();
                DB::commit();

                Flash::warning('El pago se envió con éxito y quedará a la espera de la aprobación por la entidad');
                Flash::success('El pedido interno se confirmo con éxito.');
                return redirect(route('admin.media_invoices.index'));

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
                        $message = "Debes autorizar ante {$payment['response']['payment_method_id']} el pago de \${$media_invoice->total} a MercadoPago";
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
                Log::error('MP devolvio un error al procesar el pago', array_merge($media_invoice->toArray(), ['mp_message' => $message]));
                Flash::warning('Ocurrio un error al procesar el pago. La entidad de pago devolvio "' . $message . '"');
                DB::commit();
                return redirect(route('admin.media_invoices.index'));
            } else if ($mercadoPago->status == MercadoPago::$STATUS_IN_PROCESS) {
                Log::info('MP devolvio que esta pendiente de procesar el pago', array_merge($media_invoice->toArray()));
                $media_invoice->status = MediaInvoice::$STATUS_CONFIRMED;
                $media_invoice->sent_at = Carbon::now();
                $media_invoice->save();
                DB::commit();
                Flash::warning('El pago queda pendiente de proceso');
                Flash::success('El pedido interno se confirmo con éxito.');
                return redirect(route('admin.media_invoices.index'));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //dd($e);
            Flash::error('Ocurrio un error al procesar el pago.');
            Log::error('Ocurrio un error al procesar el pago.', array_merge(['error' => $e->__toString()], $media_invoice->toArray()));
        }
        return redirect(route('admin.media_invoices.payment', $media_invoice->id));

    }

}
