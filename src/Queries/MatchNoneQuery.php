<?php

namespace BNock\ElasticsearchPHP\Queries;

use BNock\ElasticsearchPHP\Contracts\QueryElement;

class MatchNoneQuery implements QueryElement
{
    /**
     * Create an instance.
     *
     * @return MatchNoneQuery
     */
    public static function create(): MatchNoneQuery
    {
        return new static();
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'match_none' => [],
        ];
    }
}
