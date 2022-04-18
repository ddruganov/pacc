<?php

namespace api\controllers\actions\auth;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\DateHelper;
use core\components\PasswordValidator;
use core\models\security\UserRestore;
use Exception;
use Throwable;
use Yii;

class ResetPasswordAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['hash'])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Неверная подпись запроса']));
        }
        $hash = $data['hash'];

        /** @var UserRestore */
        $userRestore = UserRestore::find()
            ->where(['hash' => $hash, 'restoration_date' => null])
            ->andWhere(['>', 'expiration_date', DateHelper::now()])
            ->one();
        if (!$userRestore) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Запрос на смену пароля недействителен']));
        }

        $password = isset($data['password']) ? $data['password'] : null;
        $repeatPassword = isset($data['repeatPassword']) ? $data['repeatPassword'] : null;

        $passwordValidator = new PasswordValidator([
            'password' => $password,
            'repeatPassword' => $repeatPassword
        ]);

        if (!$passwordValidator->validate()) {
            return $this->apiResponse(new ExecutionResult(false, [], ['errors' => $passwordValidator->getFirstErrors()]));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userRestore->restorationDate = DateHelper::now();
            if (!$userRestore->save()) {
                throw new Exception(@reset($userRestore->getFirstErrors()));
            }

            $user = $userRestore->getUser();
            $user->password = Yii::$app->security->generatePasswordHash($passwordValidator->password);

            $success = $user->save();
            $success ? $transaction->commit() : $transaction->rollBack();

            return $this->apiResponse(new ExecutionResult($success, $user->getFirstErrors()));
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
