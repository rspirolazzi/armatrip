<?php

namespace App\Listeners;

use App\Admin;
use App\Events\InvoiceEvent;
use App\Mail\InvoiceAdminMail;
use App\Mail\InvoiceUserMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceConfirmListener
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
     * @param InvoiceEvent $event
     * @return void
     */
    public function handle(InvoiceEvent $event)
    {
        $invoice = $event->getInvoice();
        $pdf_file = $event->getPdfFile();
        $excel_file = $event->getExcelFile();

        try {
            $email = config('armatrip.armatrip_in.email_admin', '');
            $userFaricciInAdmins = Admin::role(Admin::$GROUP_FARICCI_ADMIN)->get();
            if ($userFaricciInAdmins) {
                $userFaricciInAdmins->each(function ($user) use ($invoice, $excel_file, $pdf_file) {
                    Log::info('Enviando email al administrador Faricci IN', $user->toArray());
                    Mail::to($user->email)->send(new InvoiceAdminMail($invoice, $excel_file, $pdf_file));
                });
            }
            $currentUser = Auth::user();
            Log::info('Enviando email al encargado de la sucursal', $currentUser->toArray());
            Mail::to($currentUser->email)->send(new InvoiceUserMail($invoice));
            Log::debug('Se envia el email');

        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar enviar el email', [
                'invoice' => $invoice,
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
            ]);
        }
    }
}