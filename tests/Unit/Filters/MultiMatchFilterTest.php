<?php

use BNock\ElasticsearchPHP\Enumerations\MultiMatchOperator;
use BNock\ElasticsearchPHP\Enumerations\MultiMatchType;
use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;
use BNock\ElasticsearchPHP\Filters\MultiMatchFilter;

describe('multi-match filter', function() {
    it('generates correct ES query', function() {
        $filter = MultiMatchFilter::create(
            collect(['test_field', 'another_test_field']),
            'test query',
            MultiMatchType::Phrase,
            MultiMatchOperator::Or,
            0.5,
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'multi_match' => [
                    'query' => 'test query',
                    'fields' => ['test_field', 'another_test_field'],
                    'type' => 'phrase',
                    'operator' => 'or',
                    'tie_breaker' => 0.5,
                ],
            ]);
    });

    it('defaults type, operator, and tie breaker', function() {
        $filter = MultiMatchFilter::create(
            collect(['test_field', 'another_test_field']),
            'test query',
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'multi_match' => [
                    'query' => 'test query',
                    'fields' => ['test_field', 'another_test_field'],
                    'type' => 'best_fields',
                ],
            ]);
    });

    it('throws exception for invalid tie breaker', function () {
        MultiMatchFilter::create(
            collect(['test_field', 'another_test_field']),
            'test query',
            tieBreaker: 1.2,
        );
    })->throws(ElasticsearchException::class, 'Invalid tie_breaker value: 1.2');
});
