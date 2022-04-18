<?php

namespace api\controllers\actions\auth;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\DateHelper;
use core\models\security\UserVerification;

class VerifyEmailAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['hash'])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Отсутствует хэш верификации']));
        }

        $userVerification = UserVerification::findOne(['verification_hash' => $data['hash']]);
        $userVerification->setAttributes([
            'verificationDate' => DateHelper::now(),
            'isEmailVerified' => true
        ]);

        return $this->apiResponse(new ExecutionResult($userVerification->save(), $userVerification->getFirstErrors()));
    }
}
