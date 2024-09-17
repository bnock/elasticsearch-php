<?php

use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;
use BNock\ElasticsearchPHP\Filters\TermFilter;

describe('term filter', function() {
    it('generates correct ES query', function () {
        $filter = TermFilter::create('test_field', 'test term');

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'term' => [
                    'test_field' => 'test term',
                ],
            ]);
    });

    it('throws exception for null value', function () {
        TermFilter::create('test_field', null);
    })->throws(ElasticsearchException::class, 'Value must not be null');
});
