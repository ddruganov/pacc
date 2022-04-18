<?php

namespace core\email;

interface EmailReceiverInterface
{
    public function getId(): int;
    public function getModelTypeId(): int;
    public function getEmail(): string;
    public function getName(): string;
}
