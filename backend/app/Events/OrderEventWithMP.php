<?php

namespace App\Events;

use App\Models\MercadoPago;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderEventWithMP
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $order;
    protected $mercadopago;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param MercadoPago $mercadoPago
     */
    public function __construct(Order $order, MercadoPago $mercadoPago)
    {
        $this->order = $order;
        $this->$mercadoPago = $mercadoPago;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @return Mercadopago
     */
    public function getMercadopago()
    {
        return $this->mercadopago;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
