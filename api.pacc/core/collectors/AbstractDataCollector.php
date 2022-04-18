<?php

namespace core\collectors;

abstract class AbstractDataCollector
{
    public const DATA_SOURCE_GET = 'get';
    public const DATA_SOURCE_JSON = 'json';

    protected ?array $params = null;

    public function setParams(?array $params): self
    {
        $this->params = $params;
        return $this;
    }

    public function addParams(array $params): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function get(): array
    {
        return [];
    }

    protected function getParam(string $paramName)
    {
        $nestedParams = explode('.', $paramName);
        $value = $this->params;
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

    public function getDataSource(): string
    {
        return self::DATA_SOURCE_JSON;
    }
}
