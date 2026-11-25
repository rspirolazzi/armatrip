<?php

namespace App\Http\Controllers\API;

use App\Exceptions\MercadoPagoException;
use App\Exceptions\PackageNotFoundException;
use App\Exceptions\PackageWrongProductsCount;
use App\Helpers\Errors;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateOrderAPIRequest as Request;
use App\Models\MercadoPago;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Log;
use Response;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class OrderController
 * @package App\Http\Controllers\API
 */
class OrderAPIController extends AppBaseController
{
    /** @var  OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    protected function getUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
    }

    /**
     * Display a listing of the Order.
     * GET|HEAD /orders
     *
     * @param Request $request
     * @return Response
     */
    public function checkout(Request $request)
    {
        try {
            $order = $this->orderRepository->checkout($request, $this->getUser());
            return $this->sendResponse($order, 'La reserva se generó con éxito');
        } catch (MercadoPagoException $e) {
            Log::error('La orden no fue procesada con anterioridad por MercadoPago', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            return $this->sendError($e->getMessage(), Errors::$E_MP_ORDER_NOT_PAYMENT);
        } catch (PackageNotFoundException $e) {
            Log::error('Ocurrio un error inesperado al realizar el checkout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            return $this->sendError($e->getMessage(), Errors::$E_PACKAGE_NOT_FOUND);
        } catch (PackageWrongProductsCount $e) {
            Log::error('Ocurrio un error inesperado al realizar el checkout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            return $this->sendError($e->getMessage(), Errors::$E_FORM_VALIDATE);
        } catch (\Exception $e) {
            Log::error('Ocurrio un error inesperado al realizar el checkout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            return $this->sendError('Ocurrio un error inesperado');
        }
    }
    /**
     * Display a listing of the Order.
     * GET|HEAD /orders
     *
     * @param Request $request
     * @return Response
     */
    public function payment(Request $request)
    {
        try {
            $order = $this->getUser()->active_order;
            if($order->mercadoPago && $order->mercadoPago->status == MercadoPago::$STATUS_APPROVED){
                return $this->sendResponse($order, 'Ya se realizó una reserva por este viaje');
            }else{
                $status = $this->orderRepository->processMp($request, $order);
                if($status['status']){
                    return $this->sendResponse($order, $status['message']);
                }else{
                    return $this->sendError($status['message'], $status['errorCode']);
                }
            }
            
        } catch (PackageNotFoundException $e) {
            Log::error('Ocurrio un error inesperado al realizar el checkout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            return $this->sendError($e->getMessage(), Errors::$E_PACKAGE_NOT_FOUND);
        } catch (PackageWrongProductsCount $e) {
            Log::error('Ocurrio un error inesperado al realizar el checkout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            return $this->sendError($e->getMessage(), Errors::$E_FORM_VALIDATE);
        } catch (\Exception $e) {
            Log::error('Ocurrio un error inesperado al realizar el checkout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'traceAsString' => $e->getTraceAsString(),
            ]);
            return $this->sendError('Ocurrio un error inesperado');
        }
    }

    public function show($code_shareable)
    {
        $order = $this->orderRepository->findByField('public_code_url', $code_shareable)->first();
        if (empty($order) || !$order->package->package->shareable) {
            return $this->sendError('No se pudo recuperar la orden');
        }

        return $this->sendResponse([
            'user' => [
                'name' => $order->user->name,
                'email' => $order->user->email,
            ],
            'store' => $order->store->toArray(),
            'province' => $order->province->toArray(),
            'comments' => $order->comments,
            'total' => (float)$order->total,
            'number' => $order->number,
            'date' => $order->dateToTravel()->format('Y-m-d'),
            'school_name' => $order->school_name,
            'public_code_url' => $order->public_code_url,
            'passagers' => $order->package->qty,
            'installment' => $order->package->installment,
            'tender_installment' => 0,
            'price_by_passagers' =>(float) $order->package->price,
            'price_by_passagers_tender' => $order->package->price/$order->package->installment,
            'package' => [
                'name' => $order->package->package->name,
                'description' => $order->package->package->description,
            ],
            'products' => $order->products->map(function ($product) {
                return [
                    'id' => $product->product->id,
                    'name' => $product->product->name,
                    'category' => [
                        'code' => $product->product->category->code,
                        'name' => $product->product->category->name,
                        'order' => $product->product->category->order,
                    ],
                ];
            }),
        ]);
    }

}
