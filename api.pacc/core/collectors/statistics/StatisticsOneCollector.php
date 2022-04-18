<?php

namespace core\collectors\statistics;

use core\collectors\AbstractDataCollector;
use core\models\statistics\Statistics;

class StatisticsOneCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $stat = Statistics::findOne($this->getParam('id'));
        return [
            'id' => $stat->id,
            'name' => $stat->name,
            'components' => (new StatisticsComponentAllCollector())->setParams(['statisticsId' => $stat->id])->get(),
        ];
    }
}
