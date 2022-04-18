<?php

namespace core\components\helpers;

use core\models\organization\OrganizationUser;
use core\models\token\TokenHelper;
use core\models\user\User;

class UserHelper
{
    private static ?User $currentUser = null;

    public static function id(): ?int
    {
        if (self::$currentUser) {
            return self::$currentUser->id;
        }
        return (new TokenHelper(new User()))->getModelId();
    }

    public static function get(): ?User
    {
        if (self::$currentUser) {
            return self::$currentUser;
        }

        $id = self::id();
        if (!$id) {
            return null;
        }

        self::$currentUser = User::findOne($id);

        return self::$currentUser;
    }

    public static function getOrganizationId(): ?int
    {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        return intval($headers['organizationid']);
    }

    public static function getOrganizationUserId(): ?int
    {
        $organizationUser = OrganizationUser::findOne(['organization_id' => self::getOrganizationId(), 'user_id' => self::id()]);
        return $organizationUser ? $organizationUser->id : null;
    }
}
