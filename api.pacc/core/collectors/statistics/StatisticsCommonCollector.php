<?php

namespace core\collectors\statistics;

use core\collectors\AbstractDataCollector;
use core\models\common\TableField;
use core\models\statistics\StatisticsConditionType;
use core\models\statistics\StatisticsFieldType;
use yii\db\Query;

class StatisticsCommonCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $conditionTypes = (new Query())
            ->select([
                'id',
                'operator',
                'name',
                'inputCount' => 'input_count'
            ])
            ->from(['sct' => StatisticsConditionType::tableName()])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $fieldTypes = (new Query())
            ->select([
                'sft.id',
                'sft.alias',
                'inputType' => 'sft.input_type'
            ])
            ->from(['sft' => StatisticsFieldType::tableName()])
            ->innerJoin(['tf' => TableField::tableName()], 'tf.id = sft.table_field_id')
            ->orderBy(['sft.id' => SORT_ASC])
            ->all();

        return [
            'conditionTypes' => $conditionTypes,
            'fieldTypes' => $fieldTypes
        ];
    }
}
