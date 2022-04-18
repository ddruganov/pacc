<?php

namespace core\components\exceptions\pass;

use Exception;

class PassNotFoundException extends Exception
{
    public function __construct(?int $passId)
    {
        parent::__construct('Абонемент #' . $passId . ' не найден');
    }
}
