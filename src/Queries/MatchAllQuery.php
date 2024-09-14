<?php

namespace BNock\ElasticsearchPHP\Queries;

use BNock\ElasticsearchPHP\Contracts\QueryElement;

class MatchAllQuery implements QueryElement
{
    /**
     * Create an instance.
     *
     * @param float|null $boost
     * @return MatchAllQuery
     */
    public static function create(float $boost = null): MatchAllQuery
    {
        return new static($boost);
    }

    /**
     * Constructor.
     *
     * @param float|null $boost
     */
    public function __construct(protected ?float $boost = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        if (!empty($this->boost)) {
            return [
                'match_all' => [
                    'boost' => $this->boost,
                ],
            ];
        } else {
            return [
                'match_all' => [],
            ];
        }
    }
}
