<?php

use BNock\ElasticsearchPHP\Filters\MatchFilter;

describe('match filter', function() {
    it('generates correct ES query', function() {
        $filter = MatchFilter::create('test_field', 'match text');

        expect($filter->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'match' => [
                    'test_field' => 'match text',
                ],
            ]);
    });
});
