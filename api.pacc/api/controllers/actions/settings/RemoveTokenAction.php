<?php

namespace api\controllers\actions\settings;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\models\token\RefreshToken;

class RemoveTokenAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['id'])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Отсутствуют входные данные']));
        }

        $token = RefreshToken::findOne($data['id']);
        if (!$token) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Токен не найден']));
        }

        return $this->apiResponse(new ExecutionResult($token->void(), $token->getFirstErrors()));
    }
}
