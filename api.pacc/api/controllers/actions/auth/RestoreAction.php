<?php

namespace api\controllers\actions\auth;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\DateHelper;
use core\models\security\UserRestore;
use core\models\user\User;
use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Throwable;
use Yii;

class RestoreAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['email'])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Отсутствуют входные данные']));
        }
        $email = $data['email'];

        $user = User::findOne(['email' => $email]);
        if (!$user) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Пользователь с таким email не найден']));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $previousAttempt = UserRestore::find()->where(['user_id' => $user->id])->andWhere(['>', 'expiration_date', DateHelper::now()])->one();
            if ($previousAttempt) {
                $previousAttempt->expirationDate = DateHelper::datetimeAsString('Y-m-d H:i:s', strtotime('-1 second'));
                if (!$previousAttempt->save()) {
                    throw new Exception('Ошибка завершения предыдущей попытки восстановления пароля');
                }
            }

            $model = new UserRestore();
            $model->setAttributes([
                'userId' => $user->id,
                'creationDate' => DateHelper::now(),
                'expirationDate' => date('Y-m-d H:i:s', strtotime('+1 day')),
                'hash' => md5(join('USR_RESTORE', [DateHelper::now(), $user->id, md5(rand())]))
            ]);
            $success = $model->save();

            $success ? $transaction->commit() : $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult($success, $model->getFirstErrors()));
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
