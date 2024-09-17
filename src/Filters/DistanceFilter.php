<?php

namespace BNock\ElasticsearchPHP\Filters;

use BNock\ElasticsearchPHP\Enumerations\DistanceUnit;

class DistanceFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param float $latitude
     * @param float $longitude
     * @param float $distance
     * @param DistanceUnit $unit
     * @return DistanceFilter
     */
    public static function create(
        string $fieldName,
        float $latitude,
        float $longitude,
        float $distance,
        DistanceUnit $unit = DistanceUnit::Meter,
    ): DistanceFilter {
        return new static($fieldName, $latitude, $longitude, $distance, $unit);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param float $latitude
     * @param float $longitude
     * @param float $distance
     * @param DistanceUnit $unit
     */
    public function __construct(
        string $fieldName,
        protected float $latitude,
        protected float $longitude,
        protected float $distance,
        protected DistanceUnit $unit = DistanceUnit::Meter,
    ) {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'geo_distance' => [
                'distance' => $this->distance . $this->unit->value,
                $this->fieldName => [
                    'lat' => $this->latitude,
                    'lon' => $this->longitude,
                ],
            ],
        ];
    }
}
