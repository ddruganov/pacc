<?php

namespace client\controllers;

use client\controllers\actions\auth\LoginAction;
use client\controllers\actions\auth\LogoutAction;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'login' => LoginAction::class,
            'logout' => LogoutAction::class,
        ];
    }
}
