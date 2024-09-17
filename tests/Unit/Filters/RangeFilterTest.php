<?php

use BNock\ElasticsearchPHP\Enumerations\RangeRelation;
use BNock\ElasticsearchPHP\Filters\RangeFilter;

describe('range filter', function() {
    it('generates correct ES query', function() {
        $filter = RangeFilter::create(
            'test_field',
            1,
            10,
            false,
            false,
            RangeRelation::Within,
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'range' => [
                    'test_field' => [
                        'gt' => 1,
                        'lt' => 10,
                        'relation' => 'within',
                    ],
                ],
            ]);
    });

    it('defaults inclusivity fields and relation', function() {
        $filter = RangeFilter::create(
            'test_field',
            1,
            10,
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'range' => [
                    'test_field' => [
                        'gte' => 1,
                        'lte' => 10,
                        'relation' => 'intersects',
                    ],
                ],
            ]);
    });
});
