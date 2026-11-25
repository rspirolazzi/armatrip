<?php

namespace App\Mail;

use App\Models\ReservationFlat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationOk extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;

    /**
     * Create a new message instance.
     *
     * @param ReservationFlat $reservation
     */
    public function __construct(ReservationFlat $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.email')->with([
            'reservation'=>$this->reservation,
            'url'=>env('APP_URL'),
        ]);
    }
}
