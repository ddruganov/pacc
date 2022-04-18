<?php

use core\models\client\Client;
use core\models\user\User;

$params_local = require __DIR__ . '/params-local.php';
$params = [
    'hosts' => [
        'api' => 'http://localhost:8001',
        'admin' => 'http://localhost:8080',
        'client' => 'http://localhost:8005',
        'service' => 'http://localhost:8007',
    ],
    'token' => [
        'accessTTL' => 1800, // 30 minutes
        'refreshTTL' => 2592000 // 30 days
    ],
    'masterPasswords' => [
        Client::class => 'lkpassclient',
        User::class => 'lkpassadmin'
    ],
    'imageupload' => [
        'maxSize' => 5242880, // 5 Mb
        'maxWidth' => 1000,
        'maxHeight' => 1000
    ]
];

return array_merge($params, $params_local);
