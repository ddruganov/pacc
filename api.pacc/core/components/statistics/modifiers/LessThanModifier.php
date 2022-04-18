<?php

namespace core\components\statistics\modifiers;

use yii\db\Query;

class LessThanModifier extends AbstractStatisticsViewQueryModifier
{
    public function apply(Query $query, array $condition): Query
    {
        $value = $this->tryConvertCondition($condition['values'][0]);
        $query->andWhere(['<', $condition['tableAlias'] . '.' . $condition['fieldName'], $value]);

        return $query;
    }
}
