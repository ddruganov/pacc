<?php

namespace core\models\token;

use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;

/**
 * This is the model class for table "token.refresh_token".
 *
 * @property int $id
 * @property int $modelId
 * @property int $modelTypeId
 * @property string $issueDate
 * @property string $expirationDate
 * @property string $value
 * @property string $ip
 * @property string $userAgent
 */
class RefreshToken extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'token.refresh_token';
    }

    public function rules()
    {
        return [
            [['model_id', 'model_type_id', 'issue_date', 'expiration_date', 'value', 'ip', 'user_agent'], 'required'],
            [['issue_date', 'expiration_date', 'value', 'ip', 'user_agent'], 'string'],
            [['model_id', 'model_type_id'], 'integer'],
        ];
    }

    public function void(): bool
    {
        $this->expirationDate = DateHelper::datetimeAsString('Y-m-d H:i:s', strtotime('-1 second'));
        return $this->save();
    }

    public function voidCurrentTokens(): void
    {
        $list =
            self::find()
            ->where('model_id = :model_id and model_type_id = :model_type_id and expiration_date > :now', [
                ':model_id' => $this->modelId,
                ':model_type_id' => $this->modelTypeId,
                ':now' => DateHelper::now()
            ])
            ->all();

        array_walk($list, fn ($model) => $model->void());
    }

    public function isExpired(): bool
    {
        return $this->expirationDate < DateHelper::now();
    }
}
