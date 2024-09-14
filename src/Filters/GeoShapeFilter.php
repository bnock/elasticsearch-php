<?php

namespace BNock\ElasticsearchPHP\Filters;

use BNock\ElasticsearchPHP\Enumerations\GeoShapeRelation;
use BNock\ElasticsearchPHP\Models\GeoShape;

class GeoShapeFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param GeoShape $shape
     * @param GeoShapeRelation $relation
     * @return GeoShapeFilter
     */
    public static function create(
        string $fieldName,
        GeoShape $shape,
        GeoShapeRelation $relation = GeoShapeRelation::Intersects,
    ): GeoShapeFilter {
        return new static($fieldName, $shape, $relation);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param GeoShape $shape
     * @param GeoShapeRelation $relation
     */
    public function __construct(
        string $fieldName,
        protected GeoShape $shape,
        protected GeoShapeRelation $relation = GeoShapeRelation::Intersects,
    ) {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'geo_shape' => [
                $this->fieldName => [
                    'shape' => [
                        'type' => $this->shape->getType()->value,
                        'coordinates' => $this->shape->getCoordinates(),
                    ],
                    'relation' => $this->relation->value,
                ]
            ]
        ];
    }
}
