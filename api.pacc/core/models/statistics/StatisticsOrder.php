<?php

namespace core\models\statistics;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * This is the model class for table "statistics.statistics_order".
 *
 * @property int $id
 * @property int $fieldTypeId
 * @property bool $direction
 */
class StatisticsOrder extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'statistics.statistics_order';
    }

    public function rules()
    {
        return [
            [['id', 'direction'], 'required'],
            [['id', 'field_type_id'], 'integer'],
            [['direction'], 'boolean'],
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
        $model->isNewRecord && $model->setAttributes([
            'direction' => false
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion
}
