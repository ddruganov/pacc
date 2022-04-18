<?php

namespace core\models\security;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "security.user_verification".
 *
 * @property int $id
 * @property bool $isEmailVerified
 * @property string $verificationHash
 * @property string $verificationDate
 */
class UserVerification extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'security.user_verification';
    }

    public function rules()
    {
        return [
            [['id', 'is_email_verified', 'verification_hash'], 'required'],
            [['id'], 'integer'],
            [['verification_hash', 'verification_date'], 'string'],
            [['is_email_verified'], 'boolean'],
        ];
    }
}
