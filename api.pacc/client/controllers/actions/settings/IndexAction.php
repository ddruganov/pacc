<?php

namespace client\controllers\actions\settings;

use client\components\validators\ClientSettingsSaver;
use core\actions\ClientSecureAction;
use Yii;

class IndexAction extends ClientSecureAction
{
    public function run()
    {
        $clientSettingsSaver = new ClientSettingsSaver();
        if (Yii::$app->request->isPost) {
            $clientSettingsSaver->setAttributes(Yii::$app->request->post());
            if ($clientSettingsSaver->save()) {
                return $this->controller->redirect('/settings'); // resets POST
            }
        }

        return $this->controller->render('index', [
            'clientSettingsSaver' => $clientSettingsSaver
        ]);
    }
}
