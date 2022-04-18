<?php

namespace core\models\statistics;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "statistics.statistics_condition_type".
 *
 * @property int $id
 * @property string $operator
 * @property string $name
 * @property int $inputCount
 */
class StatisticsConditionType extends ExtendedActiveRecord
{
    const EQUAL = 1;
    const LESS_THAN = 2;
    const LESS_THAN_OR_EQUAL = 3;
    const GREATER_THAN = 4;
    const GREATER_THAN_OR_EQUAL = 5;
    const ILIKE = 6;
    const BETWEEN = 7;

    public static function tableName()
    {
        return 'statistics.statistics_condition_type';
    }

    public function rules()
    {
        return [
            [['id', 'operator', 'name', 'input_count'], 'required'],
            [['id', 'input_count'], 'integer'],
            [['operator', 'name'], 'string'],
        ];
    }
}
