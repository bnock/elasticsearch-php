<?php

namespace App\Services\ElasticSearch\Functions;

use App\Services\ElasticSearch\Contracts\QueryElement;

class WeightFunction implements QueryElement
{
    /**
     * Create an instance.
     *
     * @param float $weight
     * @return WeightFunction
     */
    public static function create(float $weight): WeightFunction
    {
        return new static($weight);
    }

    /**
     * Constructor.
     *
     * @param float $weight
     */
    public function __construct(protected float $weight)
    {
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'weight' => $this->weight,
        ];
    }
}
