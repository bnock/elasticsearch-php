<?php

namespace BNock\ElasticsearchPHP\Queries;

use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;
use BNock\ElasticsearchPHP\Contracts\QueryElement;
use Closure;
use Illuminate\Support\Collection;

class BooleanQuery implements QueryElement
{
    protected Collection $mustQueries;
    protected Collection $mustNotQueries;
    protected Collection $shouldQueries;

    /**
     * Create an instance.
     *
     * @return BooleanQuery
     */
    public static function create(): BooleanQuery
    {
        return new static();
    }

    /**
     * Constructor.
     *
     * @param mixed|null $minimumShouldMatch
     */
    public function __construct(protected mixed $minimumShouldMatch = 1)
    {
        $this->mustQueries = collect();
        $this->mustNotQueries = collect();
        $this->shouldQueries = collect();
    }

    /**
     * Add a must query.
     *
     * @param QueryElement|Closure $query
     * @return BooleanQuery
     */
    public function must(QueryElement|Closure $query): BooleanQuery
    {
        $this->mustQueries->push($this->evaluateQuery($query));

        return $this;
    }

    /**
     * Add a must not query.
     *
     * @param QueryElement|Closure $query
     * @return BooleanQuery
     */
    public function mustNot(QueryElement|Closure $query): BooleanQuery
    {
        $this->mustNotQueries->push($this->evaluateQuery($query));

        return $this;
    }

    /**
     * @param QueryElement|Closure $query
     * @return $this
     */
    public function should(QueryElement|Closure $query): BooleanQuery
    {
        $this->shouldQueries->push($this->evaluateQuery($query));

        return $this;
    }

    /**
     * Evaluate the query.
     *
     * @param QueryElement|Closure $query
     * @return QueryElement
     */
    protected function evaluateQuery(QueryElement|Closure $query): QueryElement
    {
        if ($query instanceof Closure) {
            $query($nestedQuery = QueryBuilder::create());

            return $nestedQuery;
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        if ($this->mustQueries->isEmpty() && $this->mustNotQueries->isEmpty() && $this->shouldQueries->isEmpty()) {
            throw new ElasticSearchException('Boolean query must have at least one of: must, must not, or should');
        }

        $query = [
            'bool' => [],
        ];

        if ($this->mustQueries->isNotEmpty()) {
            $mustQueries = [];

            $this->mustQueries->each(function (QueryElement $query) use (&$mustQueries) {
                $mustQueries[] = $query->toElasticQuery();
            });

            $query['bool']['must'] = $mustQueries;
        }

        if ($this->mustNotQueries->isNotEmpty()) {
            $mustNotQueries = [];

            $this->mustNotQueries->each(function (QueryElement $query) use (&$mustNotQueries) {
                $mustNotQueries[] = $query->toElasticQuery();
            });

            $query['bool']['must_not'] = $mustNotQueries;
        }

        if ($this->shouldQueries->isNotEmpty()) {
            $shouldQueries = [];

            $this->shouldQueries->each(function (QueryElement $query) use (&$shouldQueries) {
                $shouldQueries[] = $query->toElasticQuery();
            });

            $query['bool']['should'] = $shouldQueries;

            if (!empty($this->minimumShouldMatch)) {
                $query['bool']['minimum_should_match'] = $this->minimumShouldMatch;
            }
        }

        return $query;
    }
}
