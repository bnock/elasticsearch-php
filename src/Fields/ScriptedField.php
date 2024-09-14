<?php

namespace BNock\ElasticsearchPHP\Fields;

use Illuminate\Support\Collection;

class ScriptedField extends AbstractField
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param string $source
     * @param Collection|null $parameters
     * @return static
     */
    public static function create(string $fieldName, string $source, Collection $parameters = null): static
    {
        return new static($fieldName, $source, $parameters);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param string $source
     * @param Collection|null $parameters
     */
    public function __construct(string $fieldName, protected string $source, protected ?Collection $parameters = null)
    {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $query = [
            $this->fieldName => [
                'script' => [
                    'source' => $this->source,
                ]
            ]
        ];

        if (!empty($this->parameters) && $this->parameters->isNotEmpty()) {
            $query[$this->fieldName]['script']['params'] = $this->parameters->toArray();
        }

        return $query;
    }
}
