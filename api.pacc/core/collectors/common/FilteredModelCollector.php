<?php

namespace core\collectors\common;

use core\collectors\AbstractDataCollector;
use core\components\filter\ModelQueryFilter;

class FilteredModelCollector extends AbstractDataCollector
{
    private string $modelClass;
    private ?array $filter = null;
    private ?string $order = 'id desc';
    private ?int $page = null;
    private ?int $maxCountPerPage = 8;

    public function get(): array
    {
        $filter = (new ModelQueryFilter())
            ->setModelClass($this->modelClass)
            ->setFilter($this->filter)
            ->setPage($this->page)
            ->setMaxCountPerPage($this->maxCountPerPage)
            ->run();

        $models = $filter
            ->getQuery()
            ->orderBy($this->order)
            ->all();

        return [
            'currentPage' => $filter->getPage(),
            'pageCount' => $filter->getPageCount(),
            'models' => $models
        ];
    }

    public function setModelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;
        return $this;
    }

    public function setPage(?int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function setFilter(?array $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    public function setOrder(?string $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function setMaxCountPerPage(?int $maxCountPerPage): self
    {
        $this->maxCountPerPage = $maxCountPerPage;
        return $this;
    }
}
