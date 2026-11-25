<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class OrderBeforeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $order;
    protected $request;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param Request $request
     */
    public function __construct(Order $order, Request $request)
    {
        $this->order = $order;
        $this->request = $request;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('order-before-save');
    }

}
