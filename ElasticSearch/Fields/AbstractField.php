<?php

namespace App\Services\ElasticSearch\Fields;

use App\Services\ElasticSearch\Contracts\QueryElement;

abstract class AbstractField implements QueryElement
{
    /**
     * Constructor.
     *
     * @param string $fieldName
     */
    public function __construct(protected string $fieldName)
    {
    }

    /**
     * Set the field name.
     *
     * @param string $fieldName
     * @return void
     */
    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    /**
     * Get the field name.
     *
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
