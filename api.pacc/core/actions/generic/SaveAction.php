<?php

namespace core\actions\generic;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use core\components\SaveableInterface;
use InvalidArgumentException;
use Throwable;
use Yii;

class SaveAction extends ApiAction
{
    public string $modelClass;

    public function beforeRun()
    {
        if (!is_a($this->modelClass, SaveableInterface::class, true)) {
            throw new InvalidArgumentException($this->modelClass . ' must implement SaveableInterface');
        }
        return true;
    }

    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $data['organizationId'] = isset($data['organizationId']) ? $data['organizationId']  : UserHelper::getOrganizationId();
        $data['organizationUserId'] = isset($data['organizationUserId']) ? $data['organizationUserId']  : UserHelper::getOrganizationUserId();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            /** @var \core\components\ExecutionResult */
            $result = $this->modelClass::saveWithAttributes($data);
            $result->isSuccessful() ? $transaction->commit() : $transaction->rollBack();
            return $this->apiResponse($result);
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
