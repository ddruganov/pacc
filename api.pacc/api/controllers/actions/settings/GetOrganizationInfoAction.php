<?php

namespace api\controllers\actions\settings;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use core\models\organization\Organization;

class GetOrganizationInfoAction extends ApiAction
{
    public function run()
    {
        $organization = Organization::findOne(UserHelper::getOrganizationId());
        if (!$organization) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Организация не найдена']));
        }

        return $this->apiResponse(new ExecutionResult(true, [], [
            'name' => $organization->name,
            'description' => $organization->description,
            'logo' => $organization->getLogo()
        ]));
    }
}
