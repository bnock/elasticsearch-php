<?php

namespace App\Services\ElasticSearch\Aggregations;

use App\Services\ElasticSearch\Contracts\QueryElement;

abstract class AbstractAggregation implements QueryElement
{
    /**
     * Constructor.
     *
     * @param string $name
     * @param string $fieldName
     */
    public function __construct(protected string $name, protected string $fieldName)
    {
    }

    /**
     * @inheritDoc
     */
    abstract public function toElasticQuery(): array;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
