<?php

namespace api\controllers;

use api\controllers\actions\auth\GetCurrentUserAction;
use api\controllers\actions\auth\LoginAction;
use api\controllers\actions\auth\LogoutAction;
use api\controllers\actions\auth\RegisterAction;
use api\controllers\actions\auth\ResetPasswordAction;
use api\controllers\actions\auth\RestoreAction;
use api\controllers\actions\auth\VerifyEmailAction;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'register' => RegisterAction::class,
            'verifyEmail' => VerifyEmailAction::class,

            'login' => LoginAction::class,
            'logout' => LogoutAction::class,

            'restore' => RestoreAction::class,
            'resetPassword' => ResetPasswordAction::class,

            'getCurrentUser' => GetCurrentUserAction::class
        ];
    }
}
