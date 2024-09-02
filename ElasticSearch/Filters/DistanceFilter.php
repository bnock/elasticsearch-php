<?php

namespace App\Services\ElasticSearch\Filters;

class DistanceFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param float $latitude
     * @param float $longitude
     * @param float $distance
     * @param string $unit
     * @return DistanceFilter
     */
    public static function create(
        string $fieldName,
        float $latitude,
        float $longitude,
        float $distance,
        string $unit = 'mi',
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
     * @param string $unit
     */
    public function __construct(
        string $fieldName,
        protected float $latitude,
        protected float $longitude,
        protected float $distance,
        protected string $unit = 'mi',
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
                'distance' => $this->distance . $this->unit,
                $this->fieldName => [
                    'lat' => $this->latitude,
                    'lon' => $this->longitude,
                ],
            ],
        ];
    }
}
