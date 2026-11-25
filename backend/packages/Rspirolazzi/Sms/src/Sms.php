<?php

namespace Rspirolazzi\Sms;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;
use Rspirolazzi\Sms\Exceptions\SMSException;
class Sms
{
    public function createMessage($phone, $code)
    {
        try{
            $prefix = config('sms.prefix');
            $body = $code . ' es tú codigo de activacion ' . config('app.name');

            if (config('sms.driver') === 'mail') {
                return $this->sendByMail("{$prefix}{$phone}", $body);
            }

            $suffix = config('sms.test') ? '_test':'';
            $sid = config('sms.sid'.$suffix);
            $token = config('sms.token'.$suffix);
            $client = new Client($sid, $token);
            $client->messages->create(
                "{$prefix}{$phone}",
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => config('sms.phoneNumber'.$suffix),
                    'body' => $body
                )
            );
            Log::info('El SMS se envió con éxito', ['phone'=>"{$prefix}{$phone}"]);
            return true;
        }catch(RestException $ex){
            Log::error('Ocurrio un error al intentar enviar un SMS', [
                'phone'=>"{$prefix}{$phone}",
                'message'=>$ex->getMessage(),
                'file'=>$ex->getFile(),
                'line'=>$ex->getLine(),
            ]);
        }catch(\Exception $ex){
            throw new SMSException($ex->getMessage(),$ex->getCode(), $ex);
        }
        return false;
    }

    /**
     * SMS simulado para desarrollo: nunca se usa fuera del entorno local.
     *
     * @throws SMSException
     */
    private function sendByMail($phone, $body)
    {
        if (!app()->environment('local')) {
            throw new SMSException('El SMS simulado solo se permite en el entorno local.');
        }
        Mail::raw($body, function ($mail) use ($phone) {
            $mail->to(config('sms.mail_to'))->subject('SMS simulado para ' . $phone);
        });
        Log::info('SMS simulado enviado por email', ['phone' => $phone]);
        return true;
    }
}
