<?php

namespace core\collectors\calendar;

use core\collectors\AbstractDataCollector;
use core\components\helpers\UserHelper;
use core\models\client\Client;
use core\models\common\ModelType;
use core\models\organization\OrganizationClient;
use core\models\pass\PassInstance;
use yii\db\Query;

class BoundModelAutocompleteCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $clients = (new Query())
            ->select([
                'c.id',
                'c.name'
            ])
            ->from(['oc' => OrganizationClient::tableName()])
            ->innerJoin(['c' => Client::tableName()], 'c.id = oc.client_id')
            ->where(['ilike', 'c.name', $this->getParam('filter.name')])
            ->andWhere(['oc.organization_id' => UserHelper::getOrganizationId()])
            ->all();
        foreach ($clients as $key => $value) {
            $clients[$key]['modelTypeId'] = ModelType::CLIENT;
        }

        $passInstances = (new Query())
            ->select([
                'pi.id',
                'pi.name'
            ])
            ->from(['oc' => OrganizationClient::tableName()])
            ->innerJoin(['pi' => PassInstance::tableName()], 'pi.organization_client_id = oc.id')
            ->where(['ilike', 'pi.name', $this->getParam('filter.name')])
            ->andWhere(['oc.organization_id' => UserHelper::getOrganizationId()])
            ->all();
        foreach ($passInstances as $key => $value) {
            $passInstances[$key]['modelTypeId'] = ModelType::PASS_INSTANCE;
        }

        return [
            'models' => array_merge($clients, $passInstances)
        ];
    }
}
