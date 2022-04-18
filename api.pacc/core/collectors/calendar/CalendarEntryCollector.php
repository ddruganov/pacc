<?php

namespace core\collectors\calendar;

use core\collectors\AbstractDataCollector;
use core\components\helpers\UserHelper;
use core\models\calendar\CalendarEntry;
use yii\db\Query;

class CalendarEntryCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select([
                'id',
                'date',
                'timeStart' => 'time_start',
                'timeEnd' => 'time_end',
                'modelId' => 'model_id',
                'modelTypeId' => 'model_type_id',
                'backgroundColor' => 'background_color',
                'note'
            ])
            ->from(CalendarEntry::tableName())
            ->where(['organization_id' => UserHelper::getOrganizationId()]);

        // date
        if ($dateFrom = $this->getParam('filter.date.from')) {
            $query->andWhere(['>=', 'date', $dateFrom]);
        }
        if ($dateTo = $this->getParam('filter.date.to')) {
            $query->andWhere(['<=', 'date', $dateTo]);
        }

        $query->orderBy('id desc');

        return $query->all();
    }
}
