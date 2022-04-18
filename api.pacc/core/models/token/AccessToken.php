<?php

namespace core\models\token;

use core\components\ExtendedActiveRecord;
use core\components\helpers\CookieHelper;
use core\components\helpers\DateHelper;
use core\models\common\ModelType;
use Firebase\JWT\JWT;
use Throwable;

/**
 * @property int $id
 * @property string $value
 * @property string $ip
 * @property string $userAgent
 * @property bool $isBlacklisted
 */
class AccessToken extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'token.access_token';
    }

    public function rules()
    {
        return [
            [['value', 'ip', 'user_agent', 'is_blacklisted'], 'required'],
            [['value', 'ip', 'user_agent'], 'string'],
            [['is_blacklisted'], 'boolean'],
        ];
    }

    public function verify(): bool
    {
        $modelTypeId = $this->getModelTypeId();
        $modelType = ModelType::findOne($modelTypeId);
        if (!$modelType) {
            return false;
        }

        if ($this->isBlacklisted || $this->isExpired() || $this->isEmpty()) {
            CookieHelper::removeCookie($modelType->tableName . 'AccessToken');
            return false;
        }

        $modelClass = $modelType->class;
        $modelId = $this->getModelId();
        $tokenConsumer = $modelClass::findOne($modelId);
        if (!$tokenConsumer) {
            return false;
        }

        try {
            JWT::decode($this->value, $tokenConsumer->getPassword(), ['HS256']);
        } catch (Throwable $t) {
            return false;
        }

        return true;
    }

    public function isExpired(): bool
    {
        return $this->getExpirationDate() < DateHelper::now();
    }

    public function isEmpty(): bool
    {
        return !$this->value;
    }

    public function blacklist()
    {
        $this->is_blacklisted = true;
        $this->save();
    }

    public function onLogout()
    {
        $this->blacklist();
        $this->verify();

        $model = new RefreshToken(['modelId' => $this->getModelId(), 'modelTypeId' => $this->getModelTypeId()]);
        $model->voidCurrentTokens();
    }

    public function getModelId(): ?int
    {
        return $this->getJwtData('modelId');
    }

    public function getModelTypeId(): ?int
    {
        return $this->getJwtData('modelTypeId');
    }

    public function getIssueDate(): ?string
    {
        return $this->getJwtData('issueDate');
    }

    public function getExpirationDate(): ?string
    {
        return $this->getJwtData('expirationDate');
    }

    private function getJwtData(string $key)
    {
        if ($this->isEmpty()) {
            return null;
        }

        $payload = json_decode(base64_decode(explode('.', $this->value)[1]), true);

        return isset($payload[$key]) ? $payload[$key] : null;
    }
}
