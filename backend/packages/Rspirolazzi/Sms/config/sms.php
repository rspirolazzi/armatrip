<?php
return [
    // twilio: envío real. mail: SMS simulado por email, solo con APP_ENV=local.
    'driver' => env('SMS_DRIVER', 'twilio'),
    'mail_to' => env('SMS_MAIL_TO', 'sms@armatrip.test'),
    'test'=>env('TWILIO_TEST', true),
    'sid_test'=>env('TWILIO_ACCOUNT_SID_TEST', ''),
    'token_test'=>env('TWILIO_AUTH_TOKEN_TEST', ''),
    'sid'=>env('TWILIO_ACCOUNT_SID', ''),
    'token'=>env('TWILIO_AUTH_TOKEN', ''),
    'phoneNumber'=>env('TWILIO_NUMBER', ''),
    'phoneNumber_test'=>env('TWILIO_NUMBER_TEST', ''),
    'prefix'=>'+549',
];