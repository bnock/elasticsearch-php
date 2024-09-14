<?php

namespace BNock\ElasticsearchPHP\Functions;

use BNock\ElasticsearchPHP\Contracts\QueryElement;
use Illuminate\Support\Collection;

class ScriptScoreFunction implements QueryElement
{
    /**
     * Create an instance.
     *
     * @param string $source
     * @param Collection|null $parameters
     * @return ScriptScoreFunction
     */
    public static function create(string $source, Collection $parameters = null): ScriptScoreFunction
    {
        return new static($source, $parameters);
    }

    /**
     * Constructor.
     *
     * @param string $source
     * @param Collection|null $parameters
     */
    public function __construct(protected string $source, protected ?Collection $parameters = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $query = [
            'script_score' => [
                'source' => $this->source,
            ],
        ];

        if (!empty($this->parameters) && $this->parameters->isNotEmpty()) {
            $query['script_score']['params'] = $this->parameters->toArray();
        }

        return $query;
    }
}
