<?php

$main_local = require __DIR__ . '/main-local.php';
$main = [
    'id' => 'client.pass_accounting',
    'name' => 'ClientPacc',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'client\controllers',
    'components' => [
        'request' => [
            'enableCsrfValidation' => false
        ]
    ],
    'params' => require __DIR__ . '/params.php'
];

return array_merge($main, $main_local);
