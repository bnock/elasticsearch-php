<?php

namespace BNock\ElasticsearchPHP\Functions;

class RandomScoreFunction extends AbstractFunction
{
    /**
     * Create an instance.
     *
     * @param string $fieldName
     * @param int|null $seed
     * @return RandomScoreFunction
     */
    public static function create(string $fieldName, int $seed = null): RandomScoreFunction
    {
        return new static($fieldName, $seed);
    }

    /**
     * Constructor.
     *
     * @param string $fieldName
     * @param int|null $seed
     */
    public function __construct(string $fieldName, protected ?int $seed = null)
    {
        parent::__construct($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $query = [
            'random_score' => [
                'field' => $this->fieldName,
            ],
        ];

        if (!empty($this->seed)) {
            $query['random_score']['seed'] = $this->seed;
        }

        return $query;
    }
}
