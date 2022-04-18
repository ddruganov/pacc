<?php

namespace console\controllers;

use core\components\ErrorLog;
use core\email\EmailQueueHandler;
use core\email\types\user\RegisterEmail;
use core\models\user\User;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $user = User::findOne(39);

        $res = (new EmailQueueHandler())
            ->setReceivers([$user])
            ->addEmail(new RegisterEmail($user))
            ->run();

        ErrorLog::log($res);
    }
}
