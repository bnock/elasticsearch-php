<?php

namespace BNock\ElasticsearchPHP\Sorts;

use BNock\ElasticsearchPHP\Contracts\QueryElement;
use BNock\ElasticsearchPHP\Enumerations\SortDirection;

class Sort implements QueryElement
{
    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param SortDirection $direction
     */
    public function __construct(
        protected string $fieldName,
        protected SortDirection $direction = SortDirection::Ascending,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            $this->fieldName => [
                'order' => $this->direction->value,
            ],
        ];
    }
}
