<?php

$params_local = require __DIR__ . '/params-local.php';
$params = [];

return array_merge($params, $params_local);
