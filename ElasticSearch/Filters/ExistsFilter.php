<?php

namespace App\Services\ElasticSearch\Filters;

class ExistsFilter extends AbstractFilter
{
    public static function create(string $fieldName): static
    {
        return new static($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            'exists' => [
                'field' => $this->fieldName,
            ],
        ];
    }
}
