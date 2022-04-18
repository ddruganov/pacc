<?php

namespace core\components;

interface SaveableInterface
{
    public static function saveWithAttributes(array $attributes): ExecutionResult;
}
