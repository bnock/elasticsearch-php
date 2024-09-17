<?php

namespace BNock\ElasticsearchPHP\Filters;

use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;

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
     * @throws ElasticsearchException
     */
    public function __construct(string $fieldName, protected mixed $value)
    {
        parent::__construct($fieldName);

        if (is_null($this->value)) {
            throw new ElasticsearchException('Value must not be null');
        }
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
