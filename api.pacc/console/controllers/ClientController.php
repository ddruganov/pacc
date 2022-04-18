<?php

namespace console\controllers;

use core\components\ErrorLog;
use core\models\client\Client;
use core\models\organization\OrganizationClient;
use yii\console\Controller;
use yii\db\Query;

class ClientController extends Controller
{
    public function actionDeleteClientWithoutOrganization()
    {
        $ids = (new Query())
            ->select(['client.id'])
            ->from(['client' => Client::tableName()])
            ->innerJoin(['org_client' => OrganizationClient::tableName()], 'org_client.client_id = client.id')
            ->where(['org_client.id' => null]);

        ErrorLog::log($ids->createCommand()->getRawSql());
    }
}
