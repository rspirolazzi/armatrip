<?php

namespace App\Listeners;

use App\Events\OrderAfterEvent;
use Illuminate\Support\Facades\Log;
use Rspirolazzi\Coupons\Coupons;
use Rspirolazzi\Coupons\Exceptions\InvalidCouponException;

class CouponAfterOrderCreateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderAfterEvent $event
     * @return void
     */
    public function handle(OrderAfterEvent $event)
    {
        try {
            $request = $event->getRequest();
            $order = $event->getOrder();

            $coupon = null;
            $couponRequested = $request->get('coupon', null);
            if ($couponRequested) {
                $coupon = Coupons::check($couponRequested['code']);
                if ($coupon) {
                    $order->discount = $order->sub_total * ($coupon->reward / 100);
                    $order->total = $order->total - $order->discount;
                    $order->user->redeemCode($coupon->code, $order->id);
                }
            } else {
                Log::debug('No hay cupones para procesar');
            }
        } catch (InvalidCouponException $e) {
            Log::error('Ocurrio un error al intentar obtener el cupon.', ['error' => $e->getMessage(), 'file' => $e->getFile()]);
        } catch (\Exception $e) {
            Log::error('Ocurrio un error al intentar obtener el cupon.', ['error' => $e->getMessage(), 'file' => $e->getFile()]);
        }

    }
}
