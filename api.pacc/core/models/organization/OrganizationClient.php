<?php

namespace core\models\organization;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\models\client\Client;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "organization.organization_client".
 *
 * @property int $id
 * @property int $organizationId
 * @property int $clientId
 * @property string|null $note
 * @property bool $isDefault
 * @property string $creationDate
 * @property string $name
 */
class OrganizationClient extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'organization.organization_client';
    }

    public function rules()
    {
        return [
            [['organization_id', 'client_id', 'is_default', 'creation_date'], 'required'],
            [['organization_id', 'client_id'], 'integer'],
            [['note', 'creation_date', 'name'], 'string'],
            [['note'], 'filter', 'filter' => fn ($value) => HtmlPurifier::process($value)],
            [['is_default'], 'boolean']
        ];
    }

    public function getOrganization(): Organization
    {
        return Organization::findOne($this->organizationId);
    }

    public function getClient(): Client
    {
        return Client::findOne($this->clientId);
    }

    #region SaveableInterface implementation
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if (isset($attributes['id'])) {
            $model = self::findOne($attributes['id']);
        } elseif (isset($attributes['clientId']) && isset($attributes['organizationId'])) {
            $model = self::findOne(['client_id' => $attributes['clientId'], 'organization_id' => $attributes['organizationId']]);
        }
        $model ??= new self();

        $model->setAttributes($attributes);
        $model->isNewRecord && $model->setAttributes([
            'creationDate' => DateHelper::now()
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors(), ['id' => $model->id]);
    }
    #endregion
}
