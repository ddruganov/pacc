<?php

namespace core\collectors\client;

use core\collectors\AbstractDataCollector;
use core\models\organization\OrganizationClient;
use core\models\pass\PassInstance;
use core\models\pass\Visit;
use yii\db\Query;

class ClientVisitCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $data = (new Query())
            ->select([
                'v.id',
                'v.datetime',
                'hoursSpent' => 'v.hours_spent',
                'passInstanceId' => 'pi.id',
                'passInstanceName' => 'pi.name'
            ])
            ->from(['oc' => OrganizationClient::tableName()])
            ->innerJoin(['pi' => PassInstance::tableName()], 'pi.organization_client_id = oc.id')
            ->innerJoin(['v' => Visit::tableName()], 'v.pass_instance_id = pi.id')
            ->where(['oc.id' => $this->getParam('organizationClientId')])
            ->orderBy(['v.id' => SORT_DESC])
            ->all();

        foreach ($data as $key => $value) {
            $data[$key]['datetime'] = date('d.m.Y H:i', strtotime($value['datetime']));
        }

        return $data;
    }
}
