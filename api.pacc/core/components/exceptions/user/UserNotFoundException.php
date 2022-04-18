<?php

namespace core\components\exceptions\user;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct(?int $userId)
    {
        parent::__construct('Пользователь #' . $userId . ' не найден');
    }
}
