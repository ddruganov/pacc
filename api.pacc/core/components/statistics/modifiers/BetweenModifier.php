<?php

namespace core\components\statistics\modifiers;

use yii\db\Query;

class BetweenModifier extends AbstractStatisticsViewQueryModifier
{
    public function apply(Query $query, array $condition): Query
    {
        $left = $this->tryConvertCondition($condition['values'][0]);
        $right = $this->tryConvertCondition($condition['values'][1]);
        $query->andWhere([
            'between',
            $condition['tableAlias'] . '.' . $condition['fieldName'],
            $left,
            $right,
        ]);

        return $query;
    }
}
