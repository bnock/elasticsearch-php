<?php

namespace BNock\ElasticsearchPHP\Filters;

use BNock\ElasticsearchPHP\Contracts\QueryElement;
use BNock\ElasticsearchPHP\Enumerations\MultiMatchOperator;
use BNock\ElasticsearchPHP\Enumerations\MultiMatchType;
use Exception;
use Illuminate\Support\Collection;

class MultiMatchFilter implements QueryElement
{
    public static function create(
        Collection $fieldNames,
        string $query,
        MultiMatchType $type = MultiMatchType::BestFields,
        MultiMatchOperator $operator = null,
        float $tieBreaker = 0,
    ): static {
        return new static($fieldNames, $query, $type, $operator, $tieBreaker);
    }

    public function __construct(
        protected Collection $fieldNames,
        protected string $query,
        protected MultiMatchType $type = MultiMatchType::BestFields,
        protected ?MultiMatchOperator $operator = null,
        protected float $tieBreaker = 0,
    ) {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function toElasticQuery(): array
    {
        $query = [
            'multi_match' => [
                'query' => $this->query,
                'fields' => $this->fieldNames->toArray(),
                'type' => $this->type->value,
            ]
        ];

        if (!empty($this->operator)) {
            $query['multi_match']['operator'] = $this->operator->value;
        }

        if (!empty($this->tieBreaker)) {
            if ($this->tieBreaker < 0 || $this->tieBreaker > 1) {
                throw new Exception(sprintf('Invalid tie_breaker value: %f', $this->tieBreaker));
            }

            $query['multi_match']['tie_breaker'] = $this->tieBreaker;
        }

        return $query;
    }
}
