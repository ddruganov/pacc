<?php

namespace core\collectors\activity;

use core\collectors\AbstractDataCollector;
use core\models\common\Activity;
use yii\db\Query;

class ActivityAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return (new Query())
            ->select(['id', 'name'])
            ->from(Activity::tableName())
            ->where(['organization_id' => $this->getParam('organizationId'), 'deleted' => false])
            ->all();
    }
}
