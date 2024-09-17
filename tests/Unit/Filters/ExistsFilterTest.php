<?php

use BNock\ElasticsearchPHP\Filters\ExistsFilter;

describe('exists filter', function() {
    it('generates correct ES query', function () {
        $filter = ExistsFilter::create('test_field');

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'exists' => [
                    'field' => 'test_field',
                ],
            ]);
    });
});
