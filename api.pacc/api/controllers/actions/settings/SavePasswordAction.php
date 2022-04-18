<?php

namespace api\controllers\actions\settings;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use core\components\PasswordValidator;
use Yii;

class SavePasswordAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $password = $data['password'];
        $repeatPassword = $data['repeatPassword'];

        $passwordValidator = new PasswordValidator([
            'password' => $password,
            'repeatPassword' => $repeatPassword
        ]);

        if (!$passwordValidator->validate()) {
            return $this->apiResponse(new ExecutionResult(false, [], ['errors' => $passwordValidator->getFirstErrors()]));
        }

        $user = UserHelper::get();
        $user->password = Yii::$app->security->generatePasswordHash($password);
        return $this->apiResponse(new ExecutionResult($user->save(), $user->getFirstErrors()));
    }
}
