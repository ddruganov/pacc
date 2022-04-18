<?php

namespace core\collectors\statistics;

use core\collectors\AbstractDataCollector;
use core\components\statistics\StatisticsViewQueryModifierFactory;
use core\models\client\Client;
use core\models\organization\OrganizationClient;
use core\models\organization\OrganizationUser;
use core\models\pass\PassInstance;
use core\models\pass\Visit;
use core\models\statistics\Statistics;
use core\models\user\Coach;
use yii\db\Query;

class StatisticsViewCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $stat = Statistics::findOne($this->getParam('id'));
        $components = (new StatisticsComponentAllCollector())->setParams([
            'statisticsId' => $stat->id,
            'discardBlankFields' => true,
            'discardBlankConditions' => true,
            'discardBlankOrder' => true
        ])->get();

        $query = (new Query())
            ->from(['org_client' => OrganizationClient::tableName()])
            ->innerJoin(['client' => Client::tableName()], 'client.id = org_client.client_id')
            ->leftJoin(['pass_inst' => PassInstance::tableName()], 'pass_inst.organization_client_id = org_client.id')
            ->leftJoin(['visit' => Visit::tableName()], 'visit.pass_instance_id = pass_inst.id')
            ->leftJoin(['coach' => Coach::tableName()], 'coach.id = pass_inst.coach_id')
            ->leftJoin(['org_user' => OrganizationUser::tableName()], 'org_user.id = coach.organization_user_id')
            ->where(['org_client.organization_id' => $this->getParam('organizationId')]);

        foreach ($components['fields'] as $field) {
            $query->addSelect([$field['alias'] => $field['tableAlias'] . '.' . $field['fieldName']]);
        }

        foreach ($components['conditions'] as $condition) {
            StatisticsViewQueryModifierFactory::get($condition['typeId'])->apply($query, $condition);
        }

        if ($components['order']) {
            $tableField = $components['order']['tableAlias'] . '.' . $components['order']['fieldName'];
            $direction = $components['order']['direction'] ? SORT_DESC : SORT_ASC;
            $query->orderBy([$tableField  => $direction]);
        }

        return [
            'id' => $stat->id,
            'name' => $stat->name,
            'fields' => $components['fields'],
            'data' => $query->all()
        ];
    }
}
