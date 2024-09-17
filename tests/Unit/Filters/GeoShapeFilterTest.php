<?php

use BNock\ElasticsearchPHP\Enumerations\GeoShapeRelation;
use BNock\ElasticsearchPHP\Enumerations\GeoShapeType;
use BNock\ElasticsearchPHP\Filters\GeoShapeFilter;
use BNock\ElasticsearchPHP\Models\Coordinate;
use BNock\ElasticsearchPHP\Models\GeoShape;

describe('geo-shape filter', function() {
    it('generates correct ES query', function() {
        $filter = GeoShapeFilter::create(
            'test_field',
            new GeoShape(
                GeoShapeType::Polygon,
                collect([
                    Coordinate::create(41.2681302, -105.8353329),
                    Coordinate::create(35.9675868, -103.1326962),
                    Coordinate::create(40.1354471, -101.8802547),
                    Coordinate::create(41.2681302, -105.8353329),
                ]),
            ),
            GeoShapeRelation::Within,
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'geo_shape' => [
                    'test_field' => [
                        'shape' => [
                            'type' => 'polygon',
                            'coordinates' => [
                                [-105.8353329, 41.2681302],
                                [-103.1326962, 35.9675868],
                                [-101.8802547, 40.1354471],
                                [-105.8353329, 41.2681302],
                            ],
                        ],
                        'relation' => 'within',
                    ],
                ],
            ]);
    });

    it('defaults relation to intersects', function() {
        $filter = GeoShapeFilter::create(
            'test_field',
            new GeoShape(
                GeoShapeType::Polygon,
                collect([
                    Coordinate::create(41.2681302, -105.8353329),
                    Coordinate::create(35.9675868, -103.1326962),
                    Coordinate::create(40.1354471, -101.8802547),
                    Coordinate::create(41.2681302, -105.8353329),
                ]),
            ),
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'geo_shape' => [
                    'test_field' => [
                        'shape' => [
                            'type' => 'polygon',
                            'coordinates' => [
                                [-105.8353329, 41.2681302],
                                [-103.1326962, 35.9675868],
                                [-101.8802547, 40.1354471],
                                [-105.8353329, 41.2681302],
                            ],
                        ],
                        'relation' => 'intersects',
                    ],
                ],
            ]);
    });
});
