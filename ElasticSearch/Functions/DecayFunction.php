<?php

namespace App\Services\ElasticSearch\Functions;

use App\Services\ElasticSearch\Enumerations\DecayFunctionType;

class DecayFunction extends AbstractFunction
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param DecayFunctionType $type
     * @param string $origin
     * @param string $scale
     * @param string|int $offset
     * @param float $decay
     * @return DecayFunction
     */
    public static function create(
        string $fieldName,
        DecayFunctionType $type,
        string $origin,
        string $scale,
        string|int $offset = 0,
        float $decay = 0.5,
    ): DecayFunction {
        return new static($fieldName, $type, $origin, $scale, $offset, $decay);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param DecayFunctionType $type
     * @param string $origin
     * @param string $scale
     * @param string|int $offset
     * @param float $decay
     */
    public function __construct(
        string $fieldName,
        protected DecayFunctionType $type,
        protected string $origin,
        protected string $scale,
        protected string|int $offset = 0,
        protected float $decay = 0.5,
    ) {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            $this->type->value => [
                $this->fieldName => [
                    'origin' => $this->origin,
                    'scale' => $this->scale,
                    'offset' => $this->offset,
                    'decay' => $this->decay,
                ],
            ],
        ];
    }
}
