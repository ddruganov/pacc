<?php

namespace core\models\calendar;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;
use core\models\common\ModelType;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "calendar.entry".
 *
 * @property int $id
 * @property int $modelId
 * @property int $modelTypeId
 * @property string $date
 * @property int $timeStart
 * @property int $timeEnd
 * @property int $organizationId
 * @property string $backgroundColor
 * @property string|null $note
 */
class CalendarEntry extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'calendar.entry';
    }

    public function rules()
    {
        return [
            [['date', 'time_start', 'time_end', 'organization_id', 'background_color'], 'required'],
            [['date', 'background_color', 'note'], 'string'],
            [['model_id', 'model_type_id', 'time_start', 'time_end', 'organization_id'], 'integer']
        ];
    }

    public function getBoundModel(): ?ActiveRecord
    {
        $modelClass = ModelType::findOne($this->modelTypeId)->class;
        return $modelClass::findOne($this->modelId);
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
            'organizationId' => $attributes['organizationId']
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion
}
