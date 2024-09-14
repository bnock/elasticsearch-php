<?php

namespace BNock\ElasticsearchPHP\Functions;

use BNock\ElasticsearchPHP\Enumerations\FieldValueFactorModifier;

class FieldValueFactorFunction extends AbstractFunction
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param float $factor
     * @param FieldValueFactorModifier $modifier
     * @param mixed|null $missing
     * @return FieldValueFactorFunction
     */
    public static function create(
        string $fieldName,
        float $factor = 1,
        FieldValueFactorModifier $modifier = FieldValueFactorModifier::None,
        mixed $missing = null,
    ): FieldValueFactorFunction {
        return new static($fieldName, $factor, $modifier, $missing);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param float $factor
     * @param FieldValueFactorModifier $modifier
     * @param mixed|null $missing
     */
    public function __construct(
        string $fieldName,
        protected float $factor = 1,
        protected FieldValueFactorModifier $modifier = FieldValueFactorModifier::None,
        protected mixed $missing = null,
    ) {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $query = [
            'field_value_factor' => [
                'field' => $this->fieldName,
                'factor' => $this->factor,
                'modifier' => $this->modifier->value,
            ],
        ];

        if (!empty($this->missing)) {
            $query['field_value_factor']['missing'] = $this->missing;
        }

        return $query;
    }
}
