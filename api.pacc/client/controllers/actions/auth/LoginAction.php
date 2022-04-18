<?php

namespace client\controllers\actions\auth;

use core\components\helpers\ClientHelper;
use core\models\common\ModelType;
use core\models\token\TokenGroupGenerator;
use core\validators\LoginValidator;
use Yii;
use yii\base\Action;

class LoginAction extends Action
{
    public function run(?string $backurl = null)
    {
        if (ClientHelper::id()) {
            return $this->controller->redirect($backurl ?: '/');
        }

        $loginValidator = new LoginValidator([
            'modelTypeId' => ModelType::CLIENT
        ]);

        if (Yii::$app->request->isPost) {
            $loginValidator->setAttributes([
                'email' => Yii::$app->request->post('email'),
                'password' => Yii::$app->request->post('password'),
            ]);
            if ($loginValidator->validate()) {
                $accessToken = (new TokenGroupGenerator())->issueTokenGroup($loginValidator->getTokenConsumer());
                if (!$accessToken) {
                    $loginValidator->addError('common', 'Неизвестная ошибка. Попробуйте позже.');
                } else {
                    return $this->controller->redirect($backurl ?: '/');
                }
            }
        }

        return $this->controller->render('login', [
            'loginValidator' => $loginValidator
        ]);
    }
}
