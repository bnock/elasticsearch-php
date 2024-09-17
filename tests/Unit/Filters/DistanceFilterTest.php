<?php

use BNock\ElasticsearchPHP\Enumerations\DistanceUnit;
use BNock\ElasticsearchPHP\Filters\DistanceFilter;

describe('distance filter', function () {
    it('generates correct ES query', function () {
        $filter = DistanceFilter::create(
            'test_field',
            39.7392,
            -104.9903,
            30,
            DistanceUnit::Mile,
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'geo_distance' => [
                    'distance' => '30mi',
                    'test_field' => [
                        'lat' => 39.7392,
                        'lon' => -104.9903,
                    ],
                ],
            ]);
    });

    it('defaults distance unit to meters', function () {
        $filter = DistanceFilter::create(
            'test_field',
            39.7392,
            -104.9903,
            30,
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'geo_distance' => [
                    'distance' => '30m',
                    'test_field' => [
                        'lat' => 39.7392,
                        'lon' => -104.9903,
                    ],
                ],
            ]);
    });
});
