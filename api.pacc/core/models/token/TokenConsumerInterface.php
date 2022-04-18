<?php

namespace core\models\token;

interface TokenConsumerInterface
{
    public function getId(): int;
    public function getModelTypeId(): int;
    public function getAudience(): string;
    public function getPassword(): string;
}
