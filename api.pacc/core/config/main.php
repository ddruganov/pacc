<?php

use yii\caching\FileCache;
use yii\log\FileTarget;

$main_local = require __DIR__ . '/main-local.php';
$main = [
    'components' => [
        'db' => require(__DIR__ . '/db.php'),
        'cache' => [
            'class' => FileCache::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ],
        'assetManager' => [
            'forceCopy' => true,
            // 'linkAssets' => true
        ]
    ],
    'params' => require __DIR__ . '/params.php'
];

return array_merge($main, $main_local);
