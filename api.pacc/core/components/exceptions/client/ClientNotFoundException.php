<?php

namespace core\components\exceptions\client;

use Exception;

class ClientNotFoundException extends Exception
{
    public function __construct(?int $clientId)
    {
        parent::__construct('Клиент #' . $clientId . ' не найден');
    }
}
