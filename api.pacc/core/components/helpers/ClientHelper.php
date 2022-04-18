<?php

namespace core\components\helpers;

use core\models\client\Client;
use core\models\organization\OrganizationClient;
use core\models\token\TokenHelper;

class ClientHelper
{
    private static ?Client $currentClient = null;

    public static function id(): ?int
    {
        if (self::$currentClient) {
            return self::$currentClient->id;
        }
        return (new TokenHelper(new Client()))->getModelId();
    }

    public static function get(): ?Client
    {
        $id = self::id();
        if (!$id) {
            return null;
        }
        self::$currentClient = Client::findOne($id);
        return self::$currentClient;
    }

    public static function getDefaultOrganizationId(): ?int
    {
        return OrganizationClient::findOne(['client_id' => self::id(), 'is_default' => true])->organizationId;
    }
}
