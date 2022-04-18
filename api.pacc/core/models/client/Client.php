<?php

namespace core\models\client;

use core\components\ClientRegisterData;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\models\token\TokenConsumerInterface;
use core\models\common\ModelType;
use core\models\organization\OrganizationClient;
use Yii;

/**
 * This is the model class for table "client.client".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $creationDate
 */
class Client extends ExtendedActiveRecord implements TokenConsumerInterface, SaveableInterface
{
    public static function tableName()
    {
        return 'client.client';
    }

    public function rules()
    {
        return [
            [['creation_date'], 'required'],
            [['name', 'password', 'creation_date'], 'string'],
            [['email'], 'email']
        ];
    }

    #region TokenConsumerInterface
    public function getId(): int
    {
        return $this->id;
    }

    public function getModelTypeId(): int
    {
        return ModelType::CLIENT;
    }

    public function getAudience(): string
    {
        return Yii::$app->params['hosts']['client'];
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    #endregion

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if (isset($attributes['id'])) {
            $model = self::findOne($attributes['id']);
        }
        $model ??= new self();
        $model->setAttributes([
            'email' => isset($attributes['email']) ? $attributes['email'] : $model->email
        ]);
        $model->isNewRecord && $model->setAttributes([
            'creationDate' => DateHelper::now()
        ]);

        if (!$model->save()) {
            return new ExecutionResult(false, ['common' => 'Ошибка сохранения клиента'], ['errors' => $model->getFirstErrors()]);
        }

        $ocAttributes = [
            'clientId' => $model->id,
            'organizationId' => $attributes['organizationId'],
            'name' => isset($attributes['name']) ? $attributes['name'] : null,
            'note' => isset($attributes['note']) ? $attributes['note'] : null,
            'isDefault' => !OrganizationClient::findOne(['client_id' => $model->id, 'is_default' => true])
        ];

        return OrganizationClient::saveWithAttributes($ocAttributes);
    }
    #endregion

    public function register(ClientRegisterData $data): ExecutionResult
    {

        return new ExecutionResult(true);
    }
}
