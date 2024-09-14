<?php

namespace BNock\ElasticsearchPHP\Filters;

class BoundingBoxFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param float $topLeftLatitude
     * @param float $topLeftLongitude
     * @param float $bottomRightLatitude
     * @param float $bottomRightLongitude
     * @return BoundingBoxFilter
     */
    public static function create(
        string $fieldName,
        float $topLeftLatitude,
        float $topLeftLongitude,
        float $bottomRightLatitude,
        float $bottomRightLongitude,
    ): BoundingBoxFilter {
        return new static($fieldName, $topLeftLatitude, $topLeftLongitude, $bottomRightLatitude, $bottomRightLongitude);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param float $topLeftLatitude
     * @param float $topLeftLongitude
     * @param float $bottomRightLatitude
     * @param float $bottomRightLongitude
     */
    public function __construct(
        string $fieldName,
        protected float $topLeftLatitude,
        protected float $topLeftLongitude,
        protected float $bottomRightLatitude,
        protected float $bottomRightLongitude,
    ) {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'geo_bounding_box' => [
                $this->fieldName => [
                    'top_left' => [
                        'lat' => $this->topLeftLatitude,
                        'lon' => $this->topLeftLongitude,
                    ],
                    'bottom_right' => [
                        'lat' => $this->bottomRightLatitude,
                        'lon' => $this->bottomRightLongitude,
                    ],
                ],
            ],
        ];
    }
}
