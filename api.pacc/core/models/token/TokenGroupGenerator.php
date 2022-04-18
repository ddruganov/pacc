<?php

namespace core\models\token;

use core\components\helpers\CookieHelper;
use core\models\common\ModelType;
use Yii;
use Firebase\JWT\JWT;

class TokenGroupGenerator
{
    public function issueTokenGroup(TokenConsumerInterface $consumer): ?AccessToken
    {
        $issueDate = time();
        $accessTokenExpirationDate = $issueDate + Yii::$app->params['token']['accessTTL'];
        $refreshTokenExpirationDate = $issueDate + Yii::$app->params['token']['refreshTTL'];

        // generate access token
        $accessToken = JWT::encode([
            'issuer' => Yii::$app->params['hosts']['admin'],
            'audience' => $consumer->getAudience(),
            'modelId' => $consumer->getId(),
            'modelTypeId' => $consumer->getModelTypeId(),
            'issueDate' => date('Y-m-d H:i:s', $issueDate),
            'expirationDate' => date('Y-m-d H:i:s', $accessTokenExpirationDate)
        ], $consumer->getPassword());
        // save access token
        $accessTokenAr = new AccessToken();
        $accessTokenAr->setAttributes([
            'value' => $accessToken,
            'ip' => $this->getIp(),
            'userAgent' => $this->getUserAgent(),
            'isBlacklisted' => false
        ]);
        if (!$accessTokenAr->save()) {
            return null;
        }


        // generate refresh token
        $refreshToken = $this->generateRefreshToken($accessToken);
        // save refresh token and kill other active tokens
        $refreshTokenAr = new RefreshToken();
        $refreshTokenAr->setAttributes([
            'modelId' => $consumer->getId(),
            'modelTypeId' => $consumer->getModelTypeId(),
            'issueDate' => date('Y-m-d H:i:s', $issueDate),
            'expirationDate' => date('Y-m-d H:i:s', $refreshTokenExpirationDate),
            'value' => $refreshToken,
            'ip' => $this->getIp(),
            'userAgent' => $this->getUserAgent()
        ]);
        $refreshTokenAr->voidCurrentTokens();
        if (!$refreshTokenAr->save()) {
            return null;
        }

        // save tokens to cookies
        $modelType = ModelType::findOne($consumer->getModelTypeId());
        CookieHelper::setCookie($modelType->tableName . 'AccessToken', $accessToken, $accessTokenExpirationDate);
        CookieHelper::setCookie($modelType->tableName . 'RefreshToken', $refreshToken, $refreshTokenExpirationDate);

        return $accessTokenAr;
    }

    // Метод обновляет токен-группу
    public function refreshTokenGroup(RefreshToken $refreshTokenAr): ?AccessToken
    {
        if (!$refreshTokenAr) {
            return null;
        }

        $modelType = ModelType::findOne($refreshTokenAr->modelTypeId);
        if (!$modelType) {
            return null;
        }

        if ($refreshTokenAr->isExpired()) {
            CookieHelper::removeCookie($modelType->tableName . 'RefreshToken');
            return null;
        }

        $modelClass = $modelType->class;
        $tokenConsumer = $modelClass::findOne($refreshTokenAr->modelId);

        return $tokenConsumer ? $this->issueTokenGroup($tokenConsumer) : null;
    }

    private function generateRefreshToken(string $accessToken): string
    {
        return md5(time() . $accessToken);
    }

    private function getIp(): string
    {
        return Yii::$app->request->getUserIP();
    }

    private function getUserAgent(): string
    {
        return Yii::$app->request->getUserAgent();
    }
}
