<?php

return [
    // Entorno Redsys
    'url' => env('REDSYS_URL', 'https://sis-t.redsys.es:25443/sis/realizarPago'),

    // Datos comercio
    'merchant_code' => env('REDSYS_MERCHANT_CODE', ''),
    'terminal' => env('REDSYS_TERMINAL', '1'),
    'currency' => env('REDSYS_CURRENCY', '978'), // EUR
    'transaction_type' => env('REDSYS_TRANSACTION_TYPE', '0'), // Autorización

    // Clave secreta (formato base64 que entrega Redsys)
    'key' => env('REDSYS_KEY', ''),

    // Firma
    'signature_version' => 'HMAC_SHA256_V1',

    // Textos opcionales
    'merchant_name' => env('REDSYS_MERCHANT_NAME', 'TFG'),
];

