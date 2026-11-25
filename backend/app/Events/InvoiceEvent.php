<?php

namespace App\Events;

use App\Models\Admin\Invoice;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InvoiceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $invoice;
    protected $pdf_file;
    protected $excel_file;

    /**
     * Create a new event instance.
     *
     * @param Invoice $invoice
     * @param object $pdf_file
     * @param $excel_file
     */
    public function __construct($invoice, $pdf_file, $excel_file=null)
    {
        $this->invoice = $invoice;
        $this->pdf_file = $pdf_file;
        $this->excel_file = $excel_file;
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

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param mixed $invoice
     */
    public function setInvoice($invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return mixed
     */
    public function getPdfFile()
    {
        return $this->pdf_file;
    }

    /**
     * @param mixed $pdf_file
     */
    public function setPdfFile($pdf_file): void
    {
        $this->pdf_file = $pdf_file;
    }

    /**
     * @return mixed
     */
    public function getExcelFile()
    {
        return $this->excel_file;
    }

    /**
     * @param mixed $excel_file
     */
    public function setExcelFile($excel_file): void
    {
        $this->excel_file = $excel_file;
    }
}
