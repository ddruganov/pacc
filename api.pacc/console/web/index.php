<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../core/config/main.php',
    require __DIR__ . '/../config/main.php'
);

$app = new yii\console\Application($config);
require __DIR__ . '/../../core/config/bootstrap.php';
$app->run();
