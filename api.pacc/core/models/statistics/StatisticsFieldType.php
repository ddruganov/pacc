<?php

namespace core\models\statistics;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "statistics.statistics_field_type".
 *
 * @property int $id
 * @property int $tableFieldId
 * @property string $alias
 * @property string $function
 * @property string $inputType
 */
class StatisticsFieldType extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'statistics.statistics_field_type';
    }

    public function rules()
    {
        return [
            [['id', 'table_field_id', 'alias', 'function', 'input_type'], 'required'],
            [['id', 'table_field_id'], 'integer'],
            [['alias', 'function', 'input_type'], 'string'],
        ];
    }
}
