<?php

use BNock\ElasticsearchPHP\Functions\ScriptScoreFunction;

describe('script score function', function() {
    it('generates correct ES query', function() {
        $function = ScriptScoreFunction::create(
            "params.a / Math.pow(params.b, doc['my-int'].value)",
            collect([
                'a' => 5,
                'b' => 1.2,
            ]),
        );

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'script_score' => [
                    'source' => "params.a / Math.pow(params.b, doc['my-int'].value)",
                    'params' => [
                        'a' => 5,
                        'b' => 1.2,
                    ],
                ],
            ]);
    });

    it('defaults to no params', function() {
        $function = ScriptScoreFunction::create("Math.pow(1.2, doc['my-int'].value)");

        expect($function->toElasticQuery())
            ->toBeArray()
            ->toBe([
                'script_score' => [
                    'source' => "Math.pow(1.2, doc['my-int'].value)",
                ],
            ]);
    });
});
