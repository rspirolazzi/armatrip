<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateMercadoPagoRequest;
use App\Http\Requests\Admin\UpdateMercadoPagoRequest;
use App\Repositories\Admin\MercadoPagoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use MP;

class MercadoPagoController extends AppBaseController
{
    /** @var  MercadoPagoRepository */
    private $mercadoPagoRepository;

    public function __construct(MercadoPagoRepository $mercadoPagoRepo)
    {
        $this->mercadoPagoRepository = $mercadoPagoRepo;
    }

    /**
     * Display a listing of the MercadoPago for status payment.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->mercadoPagoRepository->pushCriteria(new RequestCriteria($request));
        $mercadoPagos = $this->mercadoPagoRepository->orderBy('id', 'desc')->paginate(25);

        return view('admin.mercado_pagos.index')
            ->with('mercadoPagos', $mercadoPagos);
    }

    /**
     * Display a listing of the MercadoPago.
     *
     * @param Request $request
     * @return Response
     */
    public function history(Request $request)
    {
        $this->mercadoPagoRepository->pushCriteria(new RequestCriteria($request));
        $mercadoPagos = $this->mercadoPagoRepository->orderBy('id', 'desc')->paginate(25);

        return view('admin.mercado_pagos.history')
            ->with('mercadoPagos', $mercadoPagos);
    }


    /**
     * Display the specified MercadoPago.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mercadoPago = $this->mercadoPagoRepository->findWithoutFail($id);

        if (empty($mercadoPago)) {
            Flash::error('Mercado Pago not found');

            return redirect(route('admin.mercadoPagos.index'));
        }

        return view('admin.mercado_pagos.show')->with('mercadoPago', $mercadoPago);
    }



    /**
     * Get route notifications IPN
     *
     * @param  request $Request
     *
     * @return Response
     */
    public function IPN(Request $request)
    {
        $mp = new MP (env('MP_CLIENT_ID'), env('MP_CLIENT_SECRET'));

        if ( ! isset($_GET["id"], $_GET["topic"]) || ! ctype_digit($_GET["id"])) {
            abort(404);
        }

        // Get the payment and the corresponding merchant_order reported by the IPN.
        if ($_GET["topic"] == 'payment') {
            $payment_info = $mp->get("/collections/notifications/" . $_GET["id"]);
            $merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
            
            // Get the merchant_order reported by the IPN.

            // get order and link the notification id
            $external_reference_id = $merchant_order_info["response"]["external_reference"];
           
           // get order
            $mercadoPago = $this->mercadoPagoRepository->findWithoutFail($external_reference_id);
            // link notification id
            $mercadoPago->web_cart_id = $_GET["id"];

            if ($merchant_order_info["status"] == 200) {
                // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
                
                $paid_amount = 0;

                foreach ($merchant_order_info["response"]["payments"] as $payment) {
                    $mercadoPago->status = $payment['status'];
                    if ($payment['status'] == 'approved') {
                        $paid_amount += $payment['transaction_amount'];
                    }
                }

                if ($paid_amount >= $merchant_order_info["response"]["total_amount"]) {
                    if (count($merchant_order_info["response"]["shipments"]) > 0) { 
                        
                        // The merchant_order has shipments
                        if ($merchant_order_info["response"]["shipments"][0]["status"] == "ready_to_ship") {
                            print_r("Totally paid. Print the label and release your item.");
                            $mercadoPago = $this->mercadoPagoRepository->update(array('web_cart_id' => $_GET["id"], 'data' => '', 'status' => 'Pagado'), $external_reference_id);

                        }
                    } else {
                        // The merchant_order don't has any shipments
                        print_r("Totally paid. Release your item.");
                        $mercadoPago = $this->mercadoPagoRepository->update(array('web_cart_id' => $_GET["id"], 'data' => '', 'status' => 'Pagado'), $external_reference_id);
                    }
                } else {
                    print_r("Not paid yet. Do not release your item.");
                    $mercadoPago = $this->mercadoPagoRepository->update(array('web_cart_id' => $_GET["id"], 'data' => '', 'status' => 'No pagado'), $external_reference_id);
                }
            }

            $mercadoPago->save();

        }

        return response('OK', 201);
    }


}
