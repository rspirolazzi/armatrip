<?php

namespace App\Mail;

use App\Models\MercadoPago;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderWithMPMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    protected $mercadopago;
    /**
     * Create a new message instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order, MercadoPago $mercadoPago)
    {
        $this->order=$order;
        $this->$mercadoPago=$mercadoPago;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mercadopago = $this->mercadopago;
        return $this->view('email.ordermp.'.$this->mercadopago->status)->with([
            'order'=>$this->order,
        ])->subject('Confirmación de tu pedido');
    }
}
