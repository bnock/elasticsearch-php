<?php

namespace BNock\ElasticsearchPHP;

use BNock\ElasticsearchPHP\Contracts\QueryElement;
use Closure;
use Illuminate\Support\Collection;

class AggregationBuilder implements QueryElement
{
    protected Collection $aggregations;

    /**
     * Create an instance.
     *
     * @return AggregationBuilder
     */
    public static function create(): AggregationBuilder
    {
        return new static();
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->aggregations = collect();
    }

    /**
     * Build an aggregation.
     *
     * @param Closure|AbstractAggregation $query
     * @return $this
     */
    public function aggregate(Closure|AbstractAggregation $query): AggregationBuilder
    {
        $this->aggregations->push($this->evaluateQuery($query));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $query = [
            'aggs' => [],
        ];

        $this->aggregations->each(function (QueryElement $aggregation) use (&$query) {
            $query['aggs'] = array_merge($query['aggs'], $aggregation->toElasticQuery());
        });

        return $query;
    }

    /**
     * Evaluate the query.
     *
     * @param Closure|AbstractAggregation $query
     * @return QueryElement
     */
    protected function evaluateQuery(Closure|AbstractAggregation $query): QueryElement
    {
        if ($query instanceof Closure) {
            $query($nestedQuery = AggregationBuilder::create());

            return $nestedQuery;
        }

        return $query;
    }
}
