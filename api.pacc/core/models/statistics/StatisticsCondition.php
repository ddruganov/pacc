<?php

namespace core\models\statistics;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * This is the model class for table "statistics.statistics_condition".
 *
 * @property int $id
 * @property int $typeId
 * @property int $fieldTypeId
 */
class StatisticsCondition extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'statistics.statistics_condition';
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'type_id', 'field_type_id'], 'integer'],
        ];
    }

    public function delete()
    {
        foreach (StatisticsConditionValue::findAll(['condition_id' => $this->id]) as $value) {
            if ($value->delete() === false) {
                $this->addErrors($value->getErrors());
                return false;
            }
        }

        return parent::delete();
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if (isset($attributes['id'])) {
            $model = self::findOne($attributes['id']);
        }
        $model ??= new self();

        $values = isset($attributes['values']) ? $attributes['values'] : [];
        unset($attributes['values']);

        $model->setAttributes($attributes);

        if (!$model->save()) {
            return new ExecutionResult(false, $model->getFirstErrors());
        }

        foreach (StatisticsConditionValue::findAll(['condition_id' => $model->id]) as $value) {
            if ($value->delete() === false) {
                return new ExecutionResult(false, $value->getFirstErrors());
            }
        }

        foreach ($values as $value) {
            $res = StatisticsConditionValue::saveWithAttributes([
                'conditionId' => $model->id,
                'value' => $value
            ]);
            if (!$res->isSuccessful()) {
                return $res;
            }
        }

        return new ExecutionResult(true);
    }
    #endregion
}
