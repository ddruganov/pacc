<?php

namespace core\models\statistics;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * This is the model class for table "statistics.statistics_condition_value".
 *
 * @property int $id
 * @property int $conditionId
 * @property string $value
 */
class StatisticsConditionValue extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'statistics.statistics_condition_value';
    }

    public function rules()
    {
        return [
            [['condition_id', 'value'], 'required'],
            [['condition_id'], 'integer'],
            [['value'], 'string'],
        ];
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if (isset($attributes['id'])) {
            $model = self::findOne($attributes['id']);
        }
        $model ??= new self();

        $model->setAttributes($attributes);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion
}
