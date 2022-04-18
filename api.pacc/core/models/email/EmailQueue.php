<?php

namespace core\models\email;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "email.email_queue".
 *
 * @property int $id
 * @property string $creationDate
 * @property string $lastSendDate
 * @property int $status
 * @property string $content
 * @property int $retryCount
 * @property string $subject
 * @property int $receiverId
 * @property int $receiverModelTypeId
 */
class EmailQueue extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'email.email_queue';
    }

    public function rules()
    {
        return [
            [['creation_date', 'status', 'content', 'retry_count', 'subject', 'receiver_id', 'receiver_model_type_id'], 'required'],
            [['status', 'retry_count', 'receiver_id', 'receiver_model_type_id'], 'integer'],
            [['creation_date', 'last_send_date', 'content', 'subject'], 'string'],
        ];
    }
}
