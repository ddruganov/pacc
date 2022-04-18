<?php

namespace core\models\pass;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\models\pass\PassInstance;

/**
 * This is the model class for table "pass.visit".
 *
 * @property int $id
 * @property int $passInstanceId
 * @property string $datetime
 * @property int $hoursSpent
 */
class Visit extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'pass.visit';
    }

    public function rules()
    {
        return [
            [['pass_instance_id', 'datetime', 'hours_spent'], 'required'],
            [['pass_instance_id', 'hours_spent'], 'integer'],
            [['datetime'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'passInstanceId' => 'ID экземпляра абонемента',
            'hoursSpent' => 'Количество списываемых часов',
            'datetime' => 'Дата и время'
        ];
    }

    public function getPassInstance(): PassInstance
    {
        return PassInstance::findOne($this->passInstanceId);
    }

    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = new self([
            'passInstanceId' => $attributes['passInstanceId'],
            'hoursSpent' => $attributes['hoursSpent'],
            'datetime' => DateHelper::now()
        ]);
        return $model->getPassInstance()->canVisit()
            ? new ExecutionResult($model->save(), $model->getFirstErrors())
            : new ExecutionResult(false, ['common' => 'Абонемент просрочен или закончились часы']);
    }
}
