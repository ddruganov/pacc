<?php

namespace core\actions;

use core\components\helpers\ClientHelper;
use Yii;
use yii\base\Action;

class ClientSecureAction extends Action
{
    public function beforeRun()
    {
        if (!ClientHelper::get()) {
            header('Location: /');
            Yii::$app->end();
        }
        return parent::beforeRun();
    }
}
