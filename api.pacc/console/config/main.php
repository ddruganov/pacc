<?php

$main_local = require __DIR__ . '/main-local.php';
$main = [
    'id' => 'console.pass_accounting',
    'name' => 'PassAccounting',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false
        ],
    ],
    'params' => require __DIR__ . '/params.php'
];

return array_merge($main, $main_local);
