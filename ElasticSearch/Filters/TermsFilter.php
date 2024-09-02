<?php

namespace App\Services\ElasticSearch\Filters;

use Illuminate\Support\Collection;

class TermsFilter extends AbstractFilter
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param Collection|array $values
     * @return TermsFilter
     */
    public static function create(string $fieldName, Collection|array $values): TermsFilter
    {
        return new static($fieldName, $values);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param Collection|array $values
     */
    public function __construct(string $fieldName, protected Collection|array $values)
    {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $values = $this->values instanceof Collection ? $this->values->toArray() : $this->values;

        return [
            'terms' => [
                $this->fieldName => $values,
            ],
        ];
    }
}
