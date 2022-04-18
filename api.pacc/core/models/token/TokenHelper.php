<?php

namespace core\models\token;

use core\components\helpers\CookieHelper;
use core\models\common\ModelType;

class TokenHelper
{
    private TokenConsumerInterface $tokenConsumer;

    public function __construct(TokenConsumerInterface $tokenConsumer)
    {
        $this->tokenConsumer = $tokenConsumer;
    }

    public function getAccessTokenFromCookies(): ?AccessToken
    {
        $modelName = ModelType::findOne($this->tokenConsumer->getModelTypeId())->tableName;

        // проверяем, жив ли access token
        if ($accessTokenCookie = CookieHelper::getCookie($modelName . 'AccessToken')) {
            $at = AccessToken::findOne(['value' => $accessTokenCookie]);
            if ($at && $at->verify()) {
                return $at;
            }
        }

        // если access token помер, то надо выдать новый, но только при условии, что клиент передал нам верный refresh token
        if ($refreshTokenCookie = CookieHelper::getCookie($modelName . 'RefreshToken')) {
            $refreshTokenAr = RefreshToken::findOne(['value' => $refreshTokenCookie]);
            if ($refreshTokenAr) {
                return (new TokenGroupGenerator())->refreshTokenGroup($refreshTokenAr);
            }
        }

        // если же refresh token неверный или его нет, то человек протупил и разлогинился, либо с его страницы сидит кто-то другой
        return null;
    }

    public function getModelId(): ?int
    {
        $ta = $this->getAccessTokenFromCookies();
        return $ta ? $ta->getModelId() : null;
    }
}
