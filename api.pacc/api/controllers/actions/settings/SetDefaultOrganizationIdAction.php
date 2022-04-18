<?php

namespace api\controllers\actions\settings;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use core\models\organization\OrganizationUser;

class SetDefaultOrganizationIdAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['organizationId'])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Отсутствуют входные данные']));
        }

        $newDefaultOrg = OrganizationUser::findOne(['organization_id' => $data['organizationId'], 'user_id' => UserHelper::id()]);
        if (!$newDefaultOrg) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Организация не найдена']));
        }

        $currentDefaultOrg = OrganizationUser::findOne(['user_id' => UserHelper::id(), 'is_default' => true]);
        if ($currentDefaultOrg && $currentDefaultOrg->id === $newDefaultOrg->id) {
            return $this->apiResponse(new ExecutionResult(true));
        }

        foreach (OrganizationUser::findAll(['user_id' => UserHelper::id()]) as $userOrg) {
            $userOrg->setAttribute('isDefault', true);
            if (!$userOrg->save()) {
                return $this->apiResponse(new ExecutionResult(false, $userOrg->getFirstErrors()));
            }
        }

        $newDefaultOrg->isDefault = true;

        return $this->apiResponse(new ExecutionResult($newDefaultOrg->save(), $newDefaultOrg->getFirstErrors()));
    }
}
