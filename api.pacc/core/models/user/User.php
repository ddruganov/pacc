<?php

namespace core\models\user;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\components\UserRegisterData;
use core\email\EmailReceiverInterface;
use core\models\common\ModelType;
use core\models\organization\Organization;
use core\models\organization\OrganizationUser;
use core\models\security\UserVerification;
use core\models\token\TokenConsumerInterface;
use Yii;

/**
 * This is the model class for table "user.user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $creationDate
 */
class User extends ExtendedActiveRecord implements TokenConsumerInterface, SaveableInterface, EmailReceiverInterface
{
    public static function tableName()
    {
        return 'user.user';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'password', 'creation_date'], 'required'],
            [['name', 'email', 'password', 'creation_date'], 'string'],
            [['password'], 'string', 'max' => 64],
        ];
    }

    #region TokenConsumerInterface
    public function getId(): int
    {
        return $this->id;
    }

    public function getModelTypeId(): int
    {
        return ModelType::USER;
    }

    public function getAudience(): string
    {
        return Yii::$app->params['hosts']['admin'];
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    #endregion

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = isset($attributes['id']) ? self::findOne($attributes['id']) : new self();
        $model->setAttributes([
            'name' => $attributes['name'],
            'email' => $attributes['email']
        ]);
        $model->isNewRecord && $model->setAttributes([
            'creationDate' => DateHelper::now()
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion

    public static function register(UserRegisterData $data): ExecutionResult
    {
        $user = new self();
        $user->setAttributes([
            'name' => $data->getName(),
            'email' => $data->getEmail(),
            'password' => Yii::$app->security->generatePasswordHash($data->getPassword()),
            'creationDate' => DateHelper::now()
        ]);
        if (!$user->save()) {
            return new ExecutionResult(false, $user->getFirstErrors());
        }

        $userVerification = new UserVerification();
        $userVerification->setAttributes([
            'id' => $user->id,
            'isEmailVerified' => false,
            'verificationHash' => md5(join('USRVERIFY', [$user->id, $user->creationDate, $user->email, md5(rand())]))
        ]);
        if (!$userVerification->save()) {
            return new ExecutionResult(false, $userVerification->getFirstErrors());
        }

        $res = Organization::new($user);
        if (!$res->isSuccessful()) {
            return $res;
        }

        $organizationUser = new OrganizationUser();
        $organizationUser->setAttributes([
            'organizationId' => $res->getData('id'),
            'userId' => $user->id,
            'isDefault' => !$data->getInvitationHash(),
            'userRoleId' => 1,
            'active' => true,
            'name' => $user->name,
            'creationDate' => DateHelper::now()
        ]);

        if (!$organizationUser->save()) {
            return new ExecutionResult(false, $organizationUser->getFirstErrors());
        }

        if (!$data->getInvitationHash()) {
            return new ExecutionResult(true, [], ['id' => $user->id]);
        }

        $inviterOrganization = Organization::findOne(['hash' => $data->getInvitationHash()]);
        if (!$inviterOrganization) {
            return new ExecutionResult(false, ['common' => 'Такой организации не существует']);
        }

        $organizationUser = new OrganizationUser();
        $organizationUser->setAttributes([
            'organizationId' => $inviterOrganization->id,
            'userId' => $user->id,
            'isDefault' => true,
            'userRoleId' => 1,
            'active' => true,
            'name' => $user->name,
            'creationDate' => DateHelper::now()
        ]);

        return new ExecutionResult($organizationUser->save(), $organizationUser->getFirstErrors(), ['id' => $user->id]);
    }

    #region EmailReceiverInterface
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }
    #endregion
}
