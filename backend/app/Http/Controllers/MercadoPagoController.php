<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Flash;
use Illuminate\Http\Request;
use MP;
use Response;

class MercadoPagoController extends AppBaseController
{
    public function index(Request $request){
        // return view('mercados.index');

        $order_id = $request->input('order_id');

        // Check if order not pay

        $order = Order::where(['id'=>$order_id])->first();

        if($order){
            $order_items = OrderItem::where('order_id', $order_id)->get();
            // dd($order_items);

            $preferenceData = [
                'external_reference' => $order_id,
                // also you can do this
                'notification_url'   => route('mercado.pago.notifications')
            ];

            foreach($order_items as $item){
                $product = Product::find($item->id);
                $family = Family::find($product->family_id);
                // dd($product);
                $preferenceData['items'][] = [
                    'id'          => $product->id,
                    'category_id' => $family->name,
                    'title'       => $product->name,
                    'description' => $product->description,
                    'quantity'    => $item->qty,
                    'currency_id' => config('armatrip.mercadopago.currency_id'),
                    'unit_price'  => intval($item->total),
                ];
            }

            // dd($preferenceData);



            $mp = new MP (config('armatrip.mercadopago.client_id'), config('armatrip.mercadopago.client_secret'));
            $preference = $mp->create_preference($preferenceData);
            // dd($preference);
            // dd($preference['response']['init_point']);
            return redirect($preference['response']['init_point']);
        }else{
            return redirect()->back();
        }






    }

    public function notifications(Request $request){
        $mp = new MP (config('armatrip.mercadopago.client_id'), config('armatrip.mercadopago.client_secret'));

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

            //here you must clear unnecessary data in external reference

            // get order
            // check this section
            $order = Order::findOrFail($external_reference_id);

            // link notification id
            $order->mp_notification_id = $_GET["id"];

            if ($merchant_order_info["status"] == 200) {

                // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items

                $paid_amount = 0;

                foreach ($merchant_order_info["response"]["payments"] as $payment) {
                    $order->status = $payment['status'];
                    if ($payment['status'] == 'approved') {
                        $paid_amount += $payment['transaction_amount'];
                    }
                }

                if ($paid_amount >= $merchant_order_info["response"]["total_amount"]) {

                    if (count($merchant_order_info["response"]["shipments"]) > 0) {

                        // The merchant_order has shipments
                        if ($merchant_order_info["response"]["shipments"][0]["status"] == "ready_to_ship") {

                            print_r("Totally paid. Print the label and release your item.");
                        }

                    } else {
                        // The merchant_order don't has any shipments
                        print_r("Totally paid. Release your item.");
                    }

                } else {
                    print_r("Not paid yet. Do not release your item.");
                }
            }

            $order->save();

        }

        return response('OK', 201);
    }
}
