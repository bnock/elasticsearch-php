<?php

namespace App\Services\ElasticSearch\Functions;

use App\Services\ElasticSearch\Contracts\QueryElement;
use App\Services\ElasticSearch\Enumerations\DecayFunctionType;
use App\Services\ElasticSearch\Enumerations\FieldValueFactorModifier;
use Illuminate\Support\Collection;

class FunctionScoreBuilder implements QueryElement
{
    protected Collection $functions;

    /**
     * Create an instance.
     *
     * @return FunctionScoreBuilder
     */
    public static function create(): FunctionScoreBuilder
    {
        return new static();
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->functions = collect();
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $functions = [];

        $this->functions->each(function (QueryElement $function) use (&$functions) {
            $functions[] = $function->toElasticQuery();
        });

        return $functions;
    }

    /**
     * Add a script score function.
     *
     * @param string $source
     * @param Collection|null $parameters
     * @return FunctionScoreBuilder
     */
    public function scriptScore(string $source, Collection $parameters = null): FunctionScoreBuilder
    {
        $this->functions->push(new ScriptScoreFunction($source, $parameters));

        return $this;
    }

    /**
     * Add a weight function.
     *
     * @param float $weight
     * @return FunctionScoreBuilder
     */
    public function weight(float $weight): FunctionScoreBuilder
    {
        $this->functions->push(new WeightFunction($weight));

        return $this;
    }

    /**
     * Add a random score function.
     *
     * @param string $fieldName
     * @param int|null $seed
     * @return $this
     */
    public function randomScore(string $fieldName, int $seed = null): FunctionScoreBuilder
    {
        $this->functions->push(new RandomScoreFunction($fieldName, $seed));

        return $this;
    }

    /**
     * Add a field value factor function.
     *
     * @param string $fieldName
     * @param float $factor
     * @param FieldValueFactorModifier $modifier
     * @param mixed|null $missing
     * @return FunctionScoreBuilder
     */
    public function fieldValueFactor(
        string $fieldName,
        float $factor = 1,
        FieldValueFactorModifier $modifier = FieldValueFactorModifier::None,
        mixed $missing = null
    ): FunctionScoreBuilder {
        $this->functions->push(new FieldValueFactorFunction($fieldName, $factor, $modifier, $missing));

        return $this;
    }

    /**
     * Add a decay function.
     *
     * @param string $fieldName
     * @param DecayFunctionType $type
     * @param string $origin
     * @param string $scale
     * @param mixed $offset
     * @param float $decay
     * @return FunctionScoreBuilder
     */
    public function decayFunction(
        string $fieldName,
        DecayFunctionType $type,
        string $origin,
        string $scale,
        mixed $offset = 0,
        float $decay = 0.5
    ): FunctionScoreBuilder {
        $this->functions->push(new DecayFunction($fieldName, $type, $origin, $scale, $offset, $decay));

        return $this;
    }
}
