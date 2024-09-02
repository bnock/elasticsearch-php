<?php

namespace App\Services\ElasticSearch\Queries;

use App\Services\ElasticSearch\Contracts\QueryElement;
use App\Services\ElasticSearch\Enumerations\ScoreMode;
use App\Services\ElasticSearch\Functions\FunctionScoreBuilder;

class FunctionScoreQuery implements QueryElement
{
    /**
     * Create an instance.
     *
     * @param QueryBuilder $query
     * @param FunctionScoreBuilder $functions
     * @param ScoreMode $scoreMode
     * @return FunctionScoreQuery
     */
    public static function create(
        QueryBuilder $query,
        FunctionScoreBuilder $functions,
        ScoreMode $scoreMode = ScoreMode::Multiply,
    ): FunctionScoreQuery {
        return new static($query, $functions, $scoreMode);
    }

    /**
     * Constructor.
     *
     * @param QueryBuilder $query
     * @param FunctionScoreBuilder $functions
     * @param ScoreMode $scoreMode
     */
    public function __construct(
        protected QueryBuilder $query,
        protected FunctionScoreBuilder $functions,
        protected ScoreMode $scoreMode = ScoreMode::Multiply,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'function_score' => [
                'query' => $this->query->toElasticQuery(),
                'functions' => $this->functions->toElasticQuery(),
                'score_mode' => $this->scoreMode->value,
            ],
        ];
    }
}
