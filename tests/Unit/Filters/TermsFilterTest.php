<?php

use BNock\ElasticsearchPHP\Filters\TermsFilter;

describe('terms filter', function() {
    it('generates correct ES query', function() {
        $filter = TermsFilter::create(
            'test_field',
            collect(['test term', 'another term']),
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'terms' => [
                    'test_field' => ['test term', 'another term'],
                ],
            ]);
    });

    it('allows array input', function () {
        $filter = TermsFilter::create(
            'test_field',
            ['test term', 'another term'],
        );

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'terms' => [
                    'test_field' => ['test term', 'another term'],
                ],
            ]);
    });
});
