<?php

use BNock\ElasticsearchPHP\Enumerations\FieldValueFactorModifier;
use BNock\ElasticsearchPHP\Functions\FieldValueFactorFunction;

describe('field value factor function', function() {
    it('generates correct ES query', function() {
        $function = FieldValueFactorFunction::create(
            'test_field',
            3,
            FieldValueFactorModifier::Square,
            1,
        );

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'field_value_factor' => [
                    'field' => 'test_field',
                    'factor' => 3.0,
                    'modifier' => 'square',
                    'missing' => 1,
                ],
            ]);
    });

    it('defaults factor, modifier, and missing', function() {
        $function = FieldValueFactorFunction::create('test_field');

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'field_value_factor' => [
                    'field' => 'test_field',
                    'factor' => 1.0,
                    'modifier' => 'none',
                ],
            ]);
    });
});
