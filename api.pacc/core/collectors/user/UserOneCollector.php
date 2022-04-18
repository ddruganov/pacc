<?php

namespace core\collectors\user;

use core\collectors\AbstractDataCollector;
use core\components\exceptions\user\UserNotFoundException;
use core\models\organization\OrganizationUser;
use core\models\user\User;
use yii\web\ForbiddenHttpException;

class UserOneCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $userId = $this->getParam('id');
        $user = User::findOne($userId);
        if (!$user) {
            throw new UserNotFoundException($userId);
        }
        $organizationId = $this->getParam('organizationId');
        if (!OrganizationUser::findOne(['user_id' => $userId, 'organization_id' => $organizationId])) {
            throw new ForbiddenHttpException('Пользователь #' . $userId . ' не принадлежит организации #' . $organizationId);
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'active' => $user->active,
        ];
    }
}
