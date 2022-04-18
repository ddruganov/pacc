<?php

namespace core\models\email;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "email.email_status".
 *
 * @property int $id
 * @property string $description
 */
class EmailStatus extends ExtendedActiveRecord
{
    public const NEW = 0;
    public const SUCCESS = 1;
    public const UNKNOWN_ERROR = 9999;

    public static function tableName()
    {
        return 'email.email_status';
    }

    public function rules()
    {
        return [
            [['id', 'description'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string'],
        ];
    }
}
