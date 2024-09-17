<?php

use BNock\ElasticsearchPHP\Functions\WeightFunction;

describe('weight function', function() {
    it('generates correct ES query', function() {
        $function = WeightFunction::create(1.2);

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'weight' => 1.2,
            ]);
    });
});
