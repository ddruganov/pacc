<?php

namespace core\models\statistics;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;
use core\models\common\ModelType;
use Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "statistics.statistics_component".
 *
 * @property int $id
 * @property int $modelTypeId
 * @property int $statisticsId
 */
class StatisticsComponent extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'statistics.statistics_component';
    }

    public function rules()
    {
        return [
            [['model_type_id', 'statistics_id'], 'required', 'message' => 'Это поле обязательно для заполнения'],
            [['model_type_id', 'statistics_id'], 'integer'],
        ];
    }

    public function delete()
    {
        $boundModel = $this->getBoundModel();
        if ($boundModel->delete() === false) {
            $this->addErrors($boundModel->getErrors());
            return false;
        }
        return parent::delete();
    }

    public function getBoundModel(): ActiveRecord
    {
        $modelClass = $this->getBoundModelClass();
        return $modelClass::findOne($this->id) ?: new $modelClass(['id' => $this->id]);
    }

    private function getBoundModelClass(): string
    {
        switch ($this->modelTypeId) {
            case ModelType::STATISTICS_FIELD:
                return StatisticsField::class;
            case ModelType::STATISTICS_CONDITION;
                return StatisticsCondition::class;
            case ModelType::STATISTICS_ORDER;
                return StatisticsOrder::class;
            default:
                throw new Exception('Unknown bound model type id');
        }
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = isset($attributes['id']) ? self::findOne($attributes['id']) : new self();
        $model->setAttributes($attributes);
        if (!$model->save()) {
            return new ExecutionResult(false, $model->getFirstErrors());
        }

        $boundModelClass = $model->getBoundModelClass();
        $boundModelAttributes = array_diff_key($attributes, array_flip(['modelTypeId', 'statisticsId']));
        $boundModelAttributes['id'] = $model->id;
        return $boundModelClass::saveWithAttributes($boundModelAttributes);
    }
    #endregion
}
