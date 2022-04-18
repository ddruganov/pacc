<?php

namespace core\components\typedField;

use core\components\ExecutionResult;
use yii\db\ActiveRecord;

abstract class TypedField
{
    protected ActiveRecord $parent;
    protected string $fieldName;

    protected array $data = [];

    public function __construct(ActiveRecord $parent, string $fieldName)
    {
        $this->parent = $parent;
        $this->fieldName = $fieldName;
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

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function hasData(): bool
    {
        return !empty($this->data);
    }

    public abstract function init(): void;
    public abstract function saveData(): ExecutionResult;
    public abstract function deleteData(): ExecutionResult;
}
