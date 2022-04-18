<?php

namespace api\controllers\actions\auth;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\models\common\ModelType;
use core\models\token\TokenGroupGenerator;
use core\validators\LoginValidator;

class LoginAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $data['modelTypeId'] = ModelType::USER;

        $loginValidator = new LoginValidator($data);
        if ($loginValidator->validate()) {
            $accessToken = (new TokenGroupGenerator())->issueTokenGroup($loginValidator->getTokenConsumer());
            return $this->apiResponse(new ExecutionResult((bool)$accessToken));
        }

        return $this->apiResponse(new ExecutionResult(false, ['common' => 'Поля формы заполнены неверно'], ['errors' => $loginValidator->getFirstErrors()]));
    }
}
