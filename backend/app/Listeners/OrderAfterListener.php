<?php

namespace App\Listeners;

use App\Events\OrderAfterEvent;
use App\Mail\OrderAdminMail;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class OrderAfterListener
{
    
    /**
     * Handle the event.
     *
     * @param OrderAfterEvent $event
     * @return void
     */
    public function handle(OrderAfterEvent $event)
    {

        $order = $event->getOrder();
        try {
            Log::info('Enviando email al usuario', $order->user->toArray());
            Mail::to($order->user->email)->send(new OrderMail($order));
            Log::debug('Se envia el email');
            Log::info('Enviando email al admin');
            Mail::to(config('armatrip.administrator.email'))->send(new OrderAdminMail($order));
            Log::debug('Se envia el email');
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar enviar el email', [
                'order' => $order,
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
            ]);
        }
    }
}
