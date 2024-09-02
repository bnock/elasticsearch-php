<?php

namespace App\Services\ElasticSearch\Filters;

class TermFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param mixed $value
     * @return TermFilter
     */
    public static function create(string $fieldName, mixed $value): TermFilter
    {
        return new static($fieldName, $value);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param mixed $value
     */
    public function __construct(string $fieldName, protected mixed $value)
    {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'term' => [
                $this->fieldName => $this->value,
            ],
        ];
    }
}
