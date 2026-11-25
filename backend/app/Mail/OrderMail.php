<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    /**
     * Create a new message instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $products = collect($this->order->products->map(function ($row_product) {
            $product = $row_product->product()->withTrashed()->first();
            $category = $product->category()->withTrashed()->first();

            return [
                'name' => $category->name,
                'id' => $category->id,
                'order' => $category->order,
                'product' => collect($product)->sortBy('order')->all(),
            ];
        }))->sortBy('order')->groupBy('id')->all();

        return $this->view('email.order')->with([
            'order'=>$this->order,
            'products'=>$products,
        ])->subject('Confirmación de tu pedido');
    }
}
