<?php

namespace core\models\statistics;

use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;
use core\components\ExecutionResult;
use core\components\helpers\DateHelper;

/**
 * This is the model class for table "statistics.statistics".
 *
 * @property int $id
 * @property string $creationDate
 * @property int $organizationId
 * @property int $creatorId
 * @property string $name
 */
class Statistics extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'statistics.statistics';
    }

    public function rules()
    {
        return [
            [['creation_date', 'organization_id', 'creator_id', 'name'], 'required'],
            [['creation_date', 'name'], 'string'],
            [['organization_id', 'creator_id'], 'integer'],
        ];
    }

    public function delete()
    {
        foreach (StatisticsComponent::findAll(['statistics_id' => $this->id]) as $component) {
            if ($component->delete() === false) {
                $this->addErrors($component->getErrors());
                return false;
            }
        }

        return parent::delete();
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = isset($attributes['id']) ? self::findOne($attributes['id']) : new self();
        $model->setAttributes($attributes);

        $model->isNewRecord && $model->setAttributes([
            'creationDate' => DateHelper::now(),
            'organizationId' => $attributes['organizationId'],
            'creatorId' => $attributes['organizationUserId'],
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors(), ['id' => $model->id]);
    }
    #endregion
}
