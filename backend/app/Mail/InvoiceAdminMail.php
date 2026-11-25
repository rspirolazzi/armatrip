<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class InvoiceAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice;
    protected $excel_file;
    protected $pdf_file;

    /**
     * Create a new message instance.
     *
     * @param $invoice
     * @param null $pdf_file
     * @param null $excel_file
     */
    public function __construct($invoice, $pdf_file = null, $excel_file = null)
    {
        $this->invoice = $invoice;
        $this->excel_file = $excel_file;
        $this->pdf_file = $pdf_file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $object = $this->view('email.invoice_admin')
            ->with('invoice', $this->invoice)
        ;
        if($this->pdf_file){
            $filename = "pedido-" . $this->invoice->order_number . ".pdf";;
            Log::debug('Se intenta agregar el PDF '.$filename);
            $object->attach(public_path("pdf/$filename"));
        }
        if($this->excel_file){
            $filename = "pedido-" . $this->invoice->order_number . ".csv";
            Log::debug('Se intenta agregar el CSV '.$filename);
            $object->attachFromStorage("excel/$filename");
        }
        return $object;
    }
}
