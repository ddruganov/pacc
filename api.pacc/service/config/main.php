<?php

$main_local = require __DIR__ . '/main-local.php';
$main = [
    'id' => 'service.pacc',
    'name' => 'ServicePacc',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'service\controllers',
    'components' => [
        'request' => [
            'enableCsrfValidation' => false
        ]
    ],
    'params' => require __DIR__ . '/params.php'
];

return array_merge($main, $main_local);
