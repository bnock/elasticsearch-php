<?php

namespace BNock\ElasticsearchPHP\Filters;

use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;

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
     * @throws ElasticsearchException
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
     * @throws ElasticsearchException
     */
    public function __construct(
        string $fieldName,
        protected float $topLeftLatitude,
        protected float $topLeftLongitude,
        protected float $bottomRightLatitude,
        protected float $bottomRightLongitude,
    ) {
        parent::__construct($fieldName);

        if ($this->topLeftLatitude <= $this->bottomRightLatitude) {
            throw new ElasticsearchException('Top left latitude must be greater than bottom right latitude');
        }

        if ($this->bottomRightLongitude <= $this->topLeftLongitude) {
            throw new ElasticsearchException('Top left longitude must be less than bottom right longitude');
        }
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
