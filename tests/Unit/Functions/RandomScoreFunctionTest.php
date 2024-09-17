<?php

use BNock\ElasticsearchPHP\Functions\RandomScoreFunction;

describe('random score function', function() {
    it('generates correct ES query', function() {
        $function = RandomScoreFunction::create(
            'test_field',
            10,
        );

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'random_score' => [
                    'field' => 'test_field',
                    'seed' => 10,
                ],
            ]);
    });

    it('defaults to no seed', function () {
        $function = RandomScoreFunction::create('test_field');

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'random_score' => [
                    'field' => 'test_field',
                ],
            ]);
    });
});
