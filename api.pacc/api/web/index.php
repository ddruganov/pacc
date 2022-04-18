<?php

// header('Access-Control-Allow-Origin: ' . getallheaders()['Origin']);
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Accept, Authorization, Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, OrganizationId');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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
date_default_timezone_set('Europe/Kirov');
$app->run();
