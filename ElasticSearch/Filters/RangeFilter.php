<?php

namespace App\Services\ElasticSearch\Filters;

use App\Services\ElasticSearch\Enumerations\RangeRelation;

class RangeFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param mixed $lowValue
     * @param mixed $highValue
     * @param bool $includeLow
     * @param bool $includeHigh
     * @param RangeRelation $relation
     * @return RangeFilter
     */
    public static function create(
        string $fieldName,
        mixed $lowValue,
        mixed $highValue,
        bool $includeLow = true,
        bool $includeHigh = true,
        RangeRelation $relation = RangeRelation::Intersects,
    ): RangeFilter {
        return new static($fieldName, $lowValue, $highValue, $includeLow, $includeHigh, $relation);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param mixed $lowValue
     * @param mixed $highValue
     * @param bool $includeLow
     * @param bool $includeHigh
     * @param RangeRelation $relation
     */
    public function __construct(
        string $fieldName,
        protected mixed $lowValue,
        protected mixed $highValue,
        protected bool $includeLow = true,
        protected bool $includeHigh = true,
        protected RangeRelation $relation = RangeRelation::Intersects,
    ) {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'range' => [
                $this->fieldName => [
                    ($this->includeLow ? 'gte' : 'gt') => $this->lowValue,
                    ($this->includeHigh ? 'lte' : 'lt') => $this->highValue,
                    'relation' => $this->relation->value,
                ],
            ],
        ];
    }
}
