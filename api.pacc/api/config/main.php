<?php

$main_local = require __DIR__ . '/main-local.php';
$main = [
    'id' => 'api.pass_accounting',
    'name' => 'PassAccounting',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'Qe[:rg\mD{%7DgnM'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => require __DIR__ . '/params.php'
];

return array_merge($main, $main_local);
