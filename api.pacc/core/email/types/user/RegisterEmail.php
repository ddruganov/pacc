<?php

namespace core\email\types\user;

use core\email\Email;
use core\models\security\UserVerification;
use core\models\user\User;
use Yii;

class RegisterEmail extends Email
{
    public function __construct(User $user)
    {
        $this->templatePath = Yii::getAlias('@core/email/templates/user/register.php');

        $adminLink = Yii::$app->params['hosts']['admin'];
        $hash = UserVerification::findOne($user->id)->verificationHash;
        $link = $adminLink . '/auth/verifyEmail?hash=' . $hash;

        $this->params = [
            'email' => $user->email,
            'name' => $user->name,
            'adminLink' => $adminLink,
            'verifyEmailLink' => $link
        ];
    }

    public function getSubject(): string
    {
        return 'Завершение регистрации на pacc.com';
    }
}
