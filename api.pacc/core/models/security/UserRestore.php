<?php

namespace core\models\security;

use core\components\ExtendedActiveRecord;
use core\models\user\User;

/**
 * This is the model class for table "security.user_restore".
 *
 * @property int $id
 * @property int $userId
 * @property string $creationDate
 * @property string $expirationDate
 * @property string $restorationDate
 * @property string $hash
 */
class UserRestore extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'security.user_restore';
    }

    public function rules()
    {
        return [
            [['user_id', 'creation_date', 'expiration_date', 'hash'], 'required'],
            [['user_id'], 'integer'],
            [['creation_date', 'expiration_date', 'restoration_date', 'hash'], 'string'],
            [['hash'], 'string', 'max' => 32],
        ];
    }

    public function getUser(): User
    {
        return User::findOne($this->userId);
    }
}
