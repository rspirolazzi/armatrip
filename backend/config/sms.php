<?php

return [
    // twilio: envío real. mail: SMS simulado por email, solo con APP_ENV=local.
    'driver' => env('SMS_DRIVER', 'twilio'),
    'mail_to' => env('SMS_MAIL_TO', 'sms@armatrip.test'),
    'code' => [
        'expires_minutes' => 10,
        'max_attempts' => 5,
        'max_requests' => 5,
        'window_minutes' => 10,
    ],
    'sid'=>env('TWILIO_ACCOUNT_SID', ''),
    'token'=>env('TWILIO_AUTH_TOKEN', ''),
    'phoneNumber'=>env('TWILIO_NUMBER', ''),
    'prefix'=>'+549',
];