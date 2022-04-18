<?php

namespace core\models\pass;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\components\toggle\ToggleableInterface;
use core\models\common\Activity;
use core\models\organization\OrganizationClient;
use core\models\user\Coach;

/**
 * This is the model class for table "pass.pass".
 *
 * @property int $id
 * @property int $hours
 * @property int $price
 * @property bool $active
 * @property int $expiresIn
 * @property string $name
 * @property bool $deleted
 * @property int $activityId
 * @property int $organizationId
 */
class Pass extends ExtendedActiveRecord implements ToggleableInterface, SaveableInterface
{
    public static function tableName()
    {
        return 'pass.pass';
    }

    public function rules()
    {
        return [
            [['active', 'deleted', 'organization_id'], 'required'],
            [['hours', 'expires_in', 'activity_id', 'organization_id'], 'integer', 'min' => 1, 'message' => '{attribute} не может быть меньше 1'],
            [['price'], 'number', 'min' => 0, 'tooSmall' => '{attribute} не может быть меньше 0'],
            [['active', 'deleted'], 'boolean'],
            [['name'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'hours' => 'Количество часов',
            'expiresIn' => 'Срок действия',
            'price' => 'Цена'
        ];
    }

    public function getPricePerHour(): float
    {
        return $this->price / $this->hours;
    }

    public function delete(): bool
    {
        $this->deleted = true;
        return $this->save();
    }

    public function getActivity(): Activity
    {
        return Activity::findOne($this->activityId);
    }

    #region ToggleableInterface
    public function toggle(): self
    {
        $this->active = !$this->active;
        return $this;
    }
    #endregion

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = isset($attributes['id']) ? self::findOne($attributes['id']) : new self();
        $model->setAttributes($attributes);
        $model->isNewRecord && $model->setAttributes([
            'deleted' => false,
            'active' => true
        ]);

        return new ExecutionResult($model->save(), [], ['id' => $model->id, 'errors' => $model->getFirstErrors()]);
    }
    #endregion

    public function createInstance(OrganizationClient $organizationClient, ?Coach $coach = null): ExecutionResult
    {
        $model = new PassInstance([
            'passId' => $this->id,
            'hours' => $this->hours,
            'price' => $this->price,
            'expirationDate' => date('Y-m-d H:i:s', strtotime('+' . $this->expiresIn . ' days')),
            'creationDate' =>  DateHelper::now(),
            'name' => '[' . Activity::findOne($this->activityId)->name . '] ' . $organizationClient->name . '. ' . $this->name,
            'activityId' => $this->activityId,
            'organizationClientId' => $organizationClient->id,
            'coachId' => $coach ? $coach->id : null
        ]);
        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
}
