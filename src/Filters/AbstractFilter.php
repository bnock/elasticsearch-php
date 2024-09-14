<?php

namespace BNock\ElasticsearchPHP\Filters;

use BNock\ElasticsearchPHP\Contracts\QueryElement;

abstract class AbstractFilter implements QueryElement
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
