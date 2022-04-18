<?php

namespace core\components\statistics\modifiers;

use yii\db\Query;

abstract class AbstractStatisticsViewQueryModifier
{
    public abstract function apply(Query $query, array $condition): Query;

    protected function tryConvertCondition(string $value): string
    {
        if (in_array($value, ['-1 day', '-3 days', '-1 week', '-2 weeks', '-1 month', '-3 months', '-6 months', '-1 year'])) {
            $value = date('Y-m-d H:i:s', strtotime($value));
        }

        return $value;
    }
}
