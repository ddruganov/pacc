<?php

use yii\helpers\ArrayHelper;
use yii\web\Application;

require __DIR__ . '/index-local.php';

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = ArrayHelper::merge(
    require __DIR__ . '/../../core/config/main.php',
    require __DIR__ . '/../config/main.php',
);

$app = new Application($config);
require __DIR__ . '/../../core/config/bootstrap.php';
$app->run();
