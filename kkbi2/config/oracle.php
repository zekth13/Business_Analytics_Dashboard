<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', ''),
        'host'           => env('DB_HOST', '172.16.12.10'),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', 'WH'),
        'username'       => env('DB_USERNAME', 'DW'),
        'password'       => env('DB_PASSWORD', 'DW'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
    ],
];