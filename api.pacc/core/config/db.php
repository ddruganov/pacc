<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=pacc',
    'username' => 'ddruganov',
    'password' => 'admin',
    'charset' => 'utf8'
];
// return [
//     'class' => Connection::class,
//     'dsn' => 'pgsql:host=hattie.db.elephantsql.com;port=5432;dbname=loectylz',
//     'username' => 'loectylz',
//     'password' => 'jGUFXPRGLjx6i5nwbPoznHQKtiyxrWIC',
//     'charset' => 'utf8'
// ];
