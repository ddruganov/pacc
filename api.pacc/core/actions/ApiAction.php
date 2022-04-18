<?php

namespace core\actions;

use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use core\models\security\UserVerification;
use Yii;
use yii\base\Action;

class ApiAction extends Action
{
    private const SAFE_ACTIONS = [
        'register',
        'verifyEmail',
        'login',
        'logout',
        'restore',
        'resetPassword'
    ];

    public function beforeRun()
    {
        if (in_array($this->id, self::SAFE_ACTIONS)) {
            return parent::beforeRun();
        }

        $userId = UserHelper::id();

        if (!$userId) {
            echo json_encode([
                'success' => false,
                'code' => 401 // unauthorized
            ]);
            Yii::$app->end();
        }

        if (!UserVerification::findOne($userId)->isEmailVerified) {
            echo json_encode([
                'success' => false,
                'code' => 402 // not verified
            ]);
            Yii::$app->end();
        }

        return parent::beforeRun();
    }

    protected function apiResponse(ExecutionResult $result)
    {
        return $this->controller->asJson($result->asApiResponse());
    }
}
