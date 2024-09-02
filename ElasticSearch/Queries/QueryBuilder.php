<?php

namespace App\Services\ElasticSearch\Queries;

use App\Exceptions\ElasticSearchException;
use App\Services\ElasticSearch\Contracts\QueryElement;
use App\Services\ElasticSearch\Enumerations\RangeRelation;
use App\Services\ElasticSearch\Enumerations\ScoreMode;
use App\Services\ElasticSearch\Filters\BoundingBoxFilter;
use App\Services\ElasticSearch\Filters\DistanceFilter;
use App\Services\ElasticSearch\Filters\MatchFilter;
use App\Services\ElasticSearch\Filters\RangeFilter;
use App\Services\ElasticSearch\Filters\TermFilter;
use App\Services\ElasticSearch\Filters\TermsFilter;
use App\Services\ElasticSearch\Functions\FunctionScoreBuilder;
use Closure;

class QueryBuilder implements QueryElement
{
    protected QueryElement $query;

    /**
     * Create a new instance.
     *
     * @return QueryBuilder
     */
    public static function create(): QueryBuilder
    {
        return new static();
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        if (blank($this->query)) {
            throw new ElasticSearchException('This builder must have a query applied');
        }

        return $this->query->toElasticQuery();
    }

    /**
     * Create a match all query.
     *
     * @param float|null $boost
     * @return QueryBuilder
     */
    public function matchAll(float $boost = null): QueryBuilder
    {
        $this->query = MatchAllQuery::create($boost);

        return $this;
    }

    /**
     * Create a match none query.
     *
     * @return $this
     */
    public function matchNone(): QueryBuilder
    {
        $this->query = MatchNoneQuery::create();

        return $this;
    }

    /**
     * Create a boolean query.
     *
     * @param Closure $query
     * @param mixed $minimumShouldMatch
     * @return QueryBuilder
     */
    public function boolean(Closure $query, mixed $minimumShouldMatch = 1): QueryBuilder
    {
        $query($nestedQuery = new BooleanQuery($minimumShouldMatch));
        $this->query = $nestedQuery;

        return $this;
    }

    /**
     * Create a function score query.
     *
     * @param Closure $query
     * @param Closure $functions
     * @param ScoreMode $scoreMode
     * @return QueryBuilder
     */
    public function functionScore(
        Closure $query,
        Closure $functions,
        ScoreMode $scoreMode = ScoreMode::Multiply
    ): QueryBuilder {
        $query($queryBuilder = new QueryBuilder());
        $functions($functionBuilder = FunctionScoreBuilder::create());

        $this->query = new FunctionScoreQuery($queryBuilder, $functionBuilder, $scoreMode);

        return $this;
    }

    /**
     * Filter by distance.
     *
     * @param string $fieldName
     * @param float $latitude
     * @param float $longitude
     * @param float $distance
     * @param string $unit
     * @return QueryBuilder
     */
    public function distance(
        string $fieldName,
        float $latitude,
        float $longitude,
        float $distance,
        string $unit = 'mi'
    ): QueryBuilder {
        $this->query = new DistanceFilter($fieldName, $latitude, $longitude, $distance, $unit);

        return $this;
    }

    /**
     * Filter by bounding box.
     *
     * @param string $fieldName
     * @param float $topLeftLatitude
     * @param float $topLeftLongitude
     * @param float $bottomRightLatitude
     * @param float $bottomRightLongitude
     * @return QueryBuilder
     */
    public function boundingBox(
        string $fieldName,
        float $topLeftLatitude,
        float $topLeftLongitude,
        float $bottomRightLatitude,
        float $bottomRightLongitude
    ): QueryBuilder {
        $this->query = new BoundingBoxFilter(
            fieldName: $fieldName,
            topLeftLatitude: $topLeftLatitude,
            topLeftLongitude: $topLeftLongitude,
            bottomRightLatitude: $bottomRightLatitude,
            bottomRightLongitude: $bottomRightLongitude,
        );

        return $this;
    }

    /**
     * Filter by term.
     *
     * @param string $fieldName
     * @param mixed $value
     * @return QueryBuilder
     */
    public function term(string $fieldName, mixed $value): QueryBuilder
    {
        $this->query = new TermFilter($fieldName, $value);

        return $this;
    }

    /**
     * Filter by terms.
     *
     * @param string $fieldName
     * @param array $values
     * @return QueryBuilder
     */
    public function terms(string $fieldName, array $values): QueryBuilder
    {
        $this->query = new TermsFilter($fieldName, $values);

        return $this;
    }

    /**
     * Filter by match.
     *
     * @param string $fieldName
     * @param mixed $value
     * @return QueryBuilder
     */
    public function match(string $fieldName, mixed $value): QueryBuilder
    {
        $this->query = new MatchFilter($fieldName, $value);

        return $this;
    }

    /**
     * Filter by range.
     *
     * @param string $fieldName
     * @param mixed $lowValue
     * @param mixed $highValue
     * @param bool $includeLow
     * @param bool $includeHigh
     * @param RangeRelation $relation
     * @return QueryBuilder
     */
    public function range(
        string $fieldName,
        mixed $lowValue,
        mixed $highValue,
        bool $includeLow = true,
        bool $includeHigh = true,
        RangeRelation $relation = RangeRelation::Intersects
    ): QueryBuilder {
        $this->query = new RangeFilter($fieldName, $lowValue, $highValue, $includeLow, $includeHigh, $relation);

        return $this;
    }
}
