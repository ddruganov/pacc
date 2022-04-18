<?php

namespace core\collectors\client;

use core\collectors\AbstractDataCollector;
use core\models\client\Client;
use core\models\organization\OrganizationClient;
use yii\db\Query;

class ClientOneCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return (new Query())
            ->select([
                'client.id',
                'organizationClientId' => 'oc.id',
                'oc.name',
                'client.email',
                'oc.note'
            ])
            ->from(['client' => Client::tableName()])
            ->innerJoin(['oc' => OrganizationClient::tableName()], 'oc.client_id = client.id')
            ->where(['oc.id' => $this->getParam('organizationClientId')])
            ->one() ?: [];
    }
}
