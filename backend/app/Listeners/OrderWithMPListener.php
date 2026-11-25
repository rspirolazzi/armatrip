<?php

namespace App\Listeners;

use App\Events\OrderEventWithMP;
use App\Mail\OrderAdminMail;
use App\Mail\OrderWithMPMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderWithMPListener
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
     * @param OrderEventWithMP $event
     * @return void
     */
    public function handle(OrderEventWithMP $event)
    {
        try {
            $order = $event->getOrder();
            Log::info('Enviando email al usuario', $order->user->toArray());
            Mail::to($order->user->email)->send(new OrderWithMPMail($order));
            Log::debug('Se envia el email');
            Log::info('Enviando email al encargado de la sucursal', $order->store->toArray());
            Mail::to($order->store->admin->email)->send(new OrderAdminMail($order));
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
