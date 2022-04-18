<?php

namespace core\models\pass;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\components\toggle\ToggleableInterface;
use core\models\calendar\CalendarEntry;
use core\models\client\Client;
use core\models\common\Activity;
use core\models\common\ModelType;
use core\models\organization\OrganizationClient;
use core\models\pass\Visit;
use core\models\user\Coach;
use yii\db\Query;

/**
 * This is the model class for table "pass.pass_instance".
 *
 * @property int $id
 * @property int $passId
 * @property bool $hours
 * @property float $price
 * @property string $name
 * @property float $creationDate
 * @property float $expirationDate
 * @property int $activityId
 * @property int $organizationClientId
 */
class PassInstance extends ExtendedActiveRecord implements ToggleableInterface, SaveableInterface
{
    public static function tableName()
    {
        return 'pass.pass_instance';
    }

    public function rules()
    {
        return [
            [['pass_id', 'hours', 'price', 'name', 'creation_date', 'expiration_date', 'activity_id', 'organization_client_id'], 'required', 'message' => '{attribute} обязательно для заполнения'],
            [['hours', 'pass_id', 'organization_client_id', 'activity_id'], 'integer', 'min' => 1],
            [['price'], 'number', 'min' => 0],
            [['name', 'creation_date', 'expiration_date'], 'string']
        ];
    }

    public function getPricePerVisit(): float
    {
        return $this->price / $this->hours;
    }

    public function getClient(): Client
    {
        return OrganizationClient::findOne($this->organizationClientId)->getClient();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isExpired(): bool
    {
        return DateHelper::now()  > $this->expirationDate;
    }

    public function getActivity(): Activity
    {
        return Activity::findOne($this->activityId);
    }

    public function getLastVisitDate(): string
    {
        return (new Query())
            ->select('datetime')
            ->from(Visit::tableName())
            ->where(['pass_instance_id' => $this->id])
            ->orderBy('datetime desc')
            ->scalar();
    }

    public function getHoursLeft(): int
    {
        return $this->hours - (new Query())
            ->select('count(hours_spent)')
            ->from(Visit::tableName())
            ->where(['pass_instance_id' => $this->id])
            ->scalar();
    }

    public function canVisit(): bool
    {
        return !$this->isExpired() && ($this->getHoursLeft() > 0);
    }

    public function delete()
    {
        $entries = CalendarEntry::findAll(['model_id' => $this->id, 'model_type_id' => ModelType::PASS_INSTANCE]);
        foreach ($entries as $entry) {
            if ($entry->delete() === false) {
                $this->addErrors($entry->getErrors());
                return false;
            }
        }

        return parent::delete();
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
        $organizationClient = OrganizationClient::findOne($attributes['organizationClientId']);
        if (!$organizationClient) {
            return new ExecutionResult(false, ['common' => 'Такого клиента не существует']);
        }

        $coach = null;
        if (isset($attributes['coachId'])) {
            $coach = Coach::findOne($attributes['coachId']);
            if (!$coach) {
                return new ExecutionResult(false, ['common' => 'Такого тренера не существует']);
            }
        }

        $pass = Pass::findOne($attributes['passId']);
        if (!$pass) {
            return new ExecutionResult(false, ['common' => 'Абонемента #' . $attributes['passId'] . ' не существует']);
        }

        return $pass->createInstance($organizationClient, $coach);
    }
    #endregion
}
