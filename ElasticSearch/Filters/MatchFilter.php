<?php

namespace App\Services\ElasticSearch\Filters;

class MatchFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param mixed $value
     * @return MatchFilter
     */
    public static function create(string $fieldName, mixed $value): MatchFilter
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
            'match' => [
                $this->fieldName => $this->value,
            ],
        ];
    }
}
