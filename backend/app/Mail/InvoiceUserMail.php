<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceUserMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice;

    /**
     * Create a new message instance.
     *
     * @param $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $object = $this->view('email.invoice_user')
            ->with('invoice', $this->invoice);
        return $object;
    }
}
