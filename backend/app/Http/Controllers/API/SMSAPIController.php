<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Response;
use Rspirolazzi\Sms\Exceptions\SMSException;
use Sms;

/**
 * Class SMSController
 * @package App\Http\Controllers\API
 */
class SMSAPIController extends AppBaseController
{
    public function activation(Request $request)
    {
        try {
            $phone = $request->only('phone');
            $code = Token::generateCodeSMS();
            if(Sms::createMessage($phone, $code )){
                Log::info('El sms se envio con éxito', ['phone' => $phone, 'code'=>$code]);
                return $this->sendResponse([], 'El sms se envio con éxito');
            }else{
                return $this->sendError('Ocurrio un error al intentar enviar el SMS');
            }
        } catch (SMSException $ex) {
            Log::error('Ocurrio un error al enviar un sms de activación', [
                'message' => $ex->getMessage(),
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ]);
            return $this->sendError('Ocurrio un error al intentar enviar el SMS');
        }
    }
    public function verify(Request $request)
    {
        try {
            $phone = $request->only('phone');
            Sms::createMessage($phone);
            Log::info('El sms se envio con éxito', ['phone' => $phone]);
            return $this->sendResponse(['success' => true], 'El sms se envio con éxito');
        } catch (SMSException $ex) {
            Log::error('Ocurrio un error al enviar un sms de activación', [
                'message' => $ex->getMessage(),
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ]);
            return $this->sendError(['success' => false]);
        }
    }
}
