<?php
return [
    'images_app_url'=>env('APP_URL').env('APP_URL_STORAGE_IMAGES'),
    'app_url'=>env('APP_URL'),
    'captchaIsActive' => env('CAPTCHA_IS_ACTIVE', true),
    // Valores por defecto: los settings del panel (mp_*) tienen prioridad donde existen.
    'mercadopago' => [
        'public_key' => env('MP_PUBLIC_KEY'),
        'enabled' => env('MP_ENABLED', false),
        // Sin default: cada uso decide qué hacer si no está definido.
        'enabled_test' => env('MP_ENABLED_TEST'),
        'access_token' => env('MP_ACCESS_TOKEN'),
        'access_token_test' => env('MP_ACCESS_TOKEN_TEST'),
        'client_id' => env('MP_CLIENT_ID'),
        'client_secret' => env('MP_CLIENT_SECRET'),
        'currency_id' => env('MP_CURRENCY_ID'),
        'notification_url' => env('MP_NOTIFICATION_URL'),
    ],
    'seed_users_password' => env('SEED_USERS_PASSWORD'),
    'prefix_order' => 1,
    'administrator' => [
        'name' => 'Armatrip',
        'email' => env('MAIL_ADMIN_ADDRESS')
    ],
    'tax' => [
        'product_code' => 'bus',
    ],
    'show_disabled_categories'=>true,
    'category' => [
        'category_media' => 23,
    ],
    'armatrip_in' => [
        /*Nombre de las categorias separadas por coma (,) para mostrar en el PDF de la orden*/
        'categories_availables_pdf' => ['Helados', 'Baldes de helado', 'Extras'],
        'email_admin' => env('MAIL_INTERNAL_ORDERS_ADDRESS', ''),
        'multimedia' => [
            'directory' => DIRECTORY_SEPARATOR . 'multimedia' . DIRECTORY_SEPARATOR,
        ]
    ],
    'packages'=>[
        'multimedia' => [
            'directory' => DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR,
        ]
    ]
];
