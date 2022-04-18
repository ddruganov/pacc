<?php

namespace client\controllers\actions\settings;

use core\actions\ClientSecureAction;
use core\components\helpers\ClientHelper;
use core\models\organization\OrganizationClient;
use Yii;

class SetDefaultOrganizationIdAction extends ClientSecureAction
{
    public function run()
    {
        $newOrganizationId = Yii::$app->request->post('organizationId');
        $backurl = Yii::$app->request->post('backurl');
        if (!$newOrganizationId) {
            return $this->controller->redirect($backurl);
        }

        OrganizationClient::updateAll([
            'is_default' => false
        ], [
            'client_id' => ClientHelper::id()
        ]);

        $org = OrganizationClient::findOne(['id' => $newOrganizationId]);
        $org->isDefault = true;
        $org->save();

        return $this->controller->redirect($backurl);
    }
}
