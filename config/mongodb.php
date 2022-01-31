<?php

return [
    'uri' => env('MONGODB_HOST'),
    'database' => env('MONGODB_NAME'),
    'uriOptions' => [
        'username' => env('MONGODB_USERNAME'),
        'password' => env('MONGODB_PASSWORD')
    ],

    'driverOptions' => [

    ]
];