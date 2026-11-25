<?php
return [
    'images_app_url'=>env('APP_URL').env('APP_URL_STORAGE_IMAGES'),
    'app_url'=>env('APP_URL'),
    'captchaIsActive' => env('CAPTCHA_IS_ACTIVE', true),
    'mercadopago' => [
        'public_key' => env('MP_PUBLIC_KEY'),
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
