<?php

use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;
use BNock\ElasticsearchPHP\Filters\BoundingBoxFilter;

describe('bounding box filter', function() {
    it('generates correct ES query', function () {
        $filter = BoundingBoxFilter::create(
            'test_field',
            40.112936,
            -105.630369,
            39.420481,
            -104.290037,
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'geo_bounding_box' => [
                    'test_field' => [
                        'top_left' => [
                            'lat' => 40.112936,
                            'lon' => -105.630369,
                        ],
                        'bottom_right' => [
                            'lat' => 39.420481,
                            'lon' => -104.290037,
                        ],
                    ],
                ],
            ]);
    });

    it('throws exception for invalid latitudes', function () {
        BoundingBoxFilter::create(
            'test_field',
            39.420481,
            -105.630369,
            40.112936,
            -104.290037,
        );
    })->throws(
        ElasticsearchException::class,
        'Top left latitude must be greater than bottom right latitude',
    );

    it('throws exception for invalid longitudes', function () {
        BoundingBoxFilter::create(
            'test_field',
            40.112936,
            -104.290037,
            39.420481,
            -105.630369,
        );
    })->throws(
        ElasticsearchException::class,
        'Top left longitude must be less than bottom right longitude',
    );
});
