<?php

namespace App\Services\ElasticSearch\Queries;

use App\Services\ElasticSearch\Contracts\QueryElement;

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
        if (filled($this->boost)) {
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
