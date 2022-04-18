<?php

namespace core\components;

class ExecutionResult
{
    private bool $success = false;
    private array $errors = [];
    private array $data = [];

    public function __construct(bool $success, array $errors = [], array $data = [])
    {
        $this->success = $success;
        $this->errors = $errors;
        $this->data = $data;
    }

    public function asApiResponse(): array
    {
        return [
            'success' => $this->success,
            'error' => @reset($this->errors) ?: null,
            'data' => $this->data
        ];
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getData(?string $name = null)
    {
        if (is_null($name)) {
            return $this->data;
        }

        $nestedParams = explode('.', $name);
        $value = $this->data;
        foreach ($nestedParams as $param) {
            if (isset($value[$param])) {
                $value = $value[$param];
            } else {
                $value = null;
                break;
            }
        }
        return $value;
    }
}
