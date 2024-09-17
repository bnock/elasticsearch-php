<?php

use BNock\ElasticsearchPHP\Enumerations\DecayFunctionType;
use BNock\ElasticsearchPHP\Functions\DecayFunction;

describe('decay function', function() {
    it('generates correct ES query', function() {
        $function = DecayFunction::create(
            'test_field',
            DecayFunctionType::Gaussian,
            '39.7392,-104.9903',
            '2mi',
            '0.5mi',
            0.25,
        );

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'gauss' => [
                    'test_field' => [
                        'origin' => '39.7392,-104.9903',
                        'scale' => '2mi',
                        'offset' => '0.5mi',
                        'decay' => 0.25,
                    ],
                ],
            ]);
    });

    it('defaults offset and decay', function() {
        $function = DecayFunction::create(
            'test_field',
            DecayFunctionType::Gaussian,
            '39.7392,-104.9903',
            '2mi',
        );

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'gauss' => [
                    'test_field' => [
                        'origin' => '39.7392,-104.9903',
                        'scale' => '2mi',
                        'offset' => 0,
                        'decay' => 0.5,
                    ],
                ],
            ]);
    });
});
