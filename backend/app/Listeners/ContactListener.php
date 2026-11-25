<?php

namespace App\Listeners;

use App\Admin;
use App\Events\ContactEvent;
use App\Mail\ContactAdminMail;
use App\Mail\ContactMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(ContactEvent $event)
    {
        try {
            $contact = $event->getContact();
            Log::info('Enviando email al usuario', $contact->toArray());
            Mail::to($contact->email)->send(new ContactMail($contact));
            Log::debug('Se envia el email', ['email'=>$contact->email]);

            Log::info('Enviando email al encargado de la sucursal', $contact->store->toArrayAdmin());
            Mail::to($contact->store->admin->email)->send(new ContactAdminMail($contact));
            Log::debug('Se envia el email', ['email'=>$contact->store->admin->email]);

            $admins = Admin::where('role', Admin::$ROLE_ADMIN)->get();
            foreach($admins as $admin){
                Log::info('Enviando email al admin de armatrip', $admin->toArray());
                Mail::to($admin->email)->send(new ContactAdminMail($contact));
                Log::debug('Se envia el email', ['email'=>$admin->email]);
            }
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar enviar el email', [
                'contact' => $contact,
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
            ]);
        }
    }
}
