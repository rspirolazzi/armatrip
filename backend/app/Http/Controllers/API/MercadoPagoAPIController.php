<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMercadoPagoAPIRequest;
use App\Http\Requests\API\UpdateMercadoPagoAPIRequest;
use App\Models\MercadoPago;
use App\Repositories\Admin\MercadoPagoRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use MP;
/**
 * Class MercadoPagoController
 * @package App\Http\Controllers\API
 */

class MercadoPagoAPIController extends AppBaseController
{
    /** @var  MercadoPagoRepository */
    private $mercadoPagoRepository;
    private $orderRepository;

    public function __construct(MercadoPagoRepository $mercadoPagoRepo, OrderRepository $orderRepo)
    {
        $this->mercadoPagoRepository = $mercadoPagoRepo;
        $this->orderRepository = $orderRepo;
    }

    /**
     * Display a listing of the MercadoPago.
     * GET|HEAD /mercadoPagos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->mercadoPagoRepository->pushCriteria(new RequestCriteria($request));
        $this->mercadoPagoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $mercadoPagos = $this->mercadoPagoRepository->all();

        return $this->sendResponse($mercadoPagos->toArray(), 'Mercado Pagos retrieved successfully');
    }

    /**
     * Store a newly created MercadoPago in storage.
     * POST /mercadoPagos
     *
     * @param CreateMercadoPagoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMercadoPagoAPIRequest $request)
    {
        $input = $request->all();

        $mercadoPagos = $this->mercadoPagoRepository->create($input);

        return $this->sendResponse($mercadoPagos->toArray(), 'Mercado Pago saved successfully');
    }

    /**
     * Display the specified MercadoPago.
     * GET|HEAD /mercadoPagos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MercadoPago $mercadoPago */
        $mercadoPago = $this->mercadoPagoRepository->findWithoutFail($id);

        if (empty($mercadoPago)) {
            return $this->sendError('Mercado Pago not found');
        }

        return $this->sendResponse($mercadoPago->toArray(), 'Mercado Pago retrieved successfully');
    }

    /**
     * Update the specified MercadoPago in storage.
     * PUT/PATCH /mercadoPagos/{id}
     *
     * @param  int $id
     * @param UpdateMercadoPagoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMercadoPagoAPIRequest $request)
    {
        $input = $request->all();

        /** @var MercadoPago $mercadoPago */
        $mercadoPago = $this->mercadoPagoRepository->findWithoutFail($id);

        if (empty($mercadoPago)) {
            return $this->sendError('Mercado Pago not found');
        }

        $mercadoPago = $this->mercadoPagoRepository->update($input, $id);

        return $this->sendResponse($mercadoPago->toArray(), 'MercadoPago updated successfully');
    }

    /**
     * Remove the specified MercadoPago from storage.
     * DELETE /mercadoPagos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MercadoPago $mercadoPago */
        $mercadoPago = $this->mercadoPagoRepository->findWithoutFail($id);

        if (empty($mercadoPago)) {
            return $this->sendError('Mercado Pago not found');
        }

        $mercadoPago->delete();

        return $this->sendResponse($id, 'Mercado Pago deleted successfully');
    }

    /**
     * MAKING MERCADO PAGO URL
     * DELETE /CHECKOUT/ID
     *
     * @param  int $id
     *
     * @return Response
     */
    public function checkout($id)
    {

        $order = $this->orderRepository->findWithoutFail($id);
        $mercadoPagos = $this->mercadoPagoRepository->create(array('web_cart_id'=> 1 ,'data'=>"", 'status'=>"No pagado"));
        
        $mp = new MP (env('MP_CLIENT_ID'), env('MP_CLIENT_SECRET'));
        $preferenceData = [
            'external_reference' => $mercadoPagos->id,
            
            'payer'              => [
                'phone' => array(
                    "area_code" => "54",
                    "number" => $order->user->telephone
                ),
                'address' => array(
                    "street_name" => $order->address->street,
                    "street_number" => $order->address->number,
                ),
                'email' => $order->user->email,
                'name' => $order->user->name,
                'surname' => $order->user->last_name,
            ],
            'back_urls'          => [
                'success' => "https://www.tu-sitio/success",
                'failure' => "http://www.tu-sitio/failure",
                'pending' => "http://www.tu-sitio/pending"
            ],
            'notification_url'   => env('MP_NOTIFICATION_URL')
        ];

        // add items
        foreach ($order->orderItems as $item):
            $preferenceData['items'][] = [
                'id'          => $item->product_id,
                
                'title'       => $item->product->name,            
                'description' => $item->product->description,
                'picture_url' => $item->product->image,
                'quantity'    => $item->qty,
                'currency_id' => 'ARS',
                'unit_price'  => (int)$item->price,
            ];
        endforeach;

        $preference = $mp->create_preference($preferenceData);

        // return init point to be redirected
        
        return $this->sendResponse($preference['response']['init_point'], 'Checkout saved successfully');
        
    }
}
