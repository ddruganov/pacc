<?php

namespace core\collectors\statistics;

use core\collectors\AbstractDataCollector;
use core\components\helpers\UserHelper;
use core\models\statistics\Statistics;
use yii\db\Query;

class StatisticsAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return (new Query())
            ->select('id, name')
            ->from(Statistics::tableName())
            ->where(['organization_id' => UserHelper::getOrganizationId()])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }
}
