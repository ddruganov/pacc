<?php

namespace client\controllers;

use core\components\helpers\ClientHelper;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(ClientHelper::id() ? '/pass-instance' : '/auth/login');
    }
}
