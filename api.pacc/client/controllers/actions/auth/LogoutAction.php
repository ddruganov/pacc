<?php

namespace client\controllers\actions\auth;

use core\models\client\Client;
use core\models\token\TokenHelper;
use yii\base\Action;

class LogoutAction extends Action
{
    public function run()
    {
        if ($accessToken = (new TokenHelper(new Client()))->getAccessTokenFromCookies()) {
            $accessToken->onLogout();
        }

        return $this->controller->redirect('/');
    }
}
