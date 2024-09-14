<?php

namespace BNock\ElasticsearchPHP;

class TermsAggregation extends AbstractAggregation
{
    /**
     * Create an instance.
     *
     * @param string $name
     * @param string $fieldName
     * @param int $minimumDocumentCount
     * @param int $size
     * @return TermsAggregation
     */
    public static function create(
        string $name,
        string $fieldName,
        int $minimumDocumentCount = 1,
        int $size = 10,
    ): TermsAggregation {
        return new static($name, $fieldName, $minimumDocumentCount, $size);
    }

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $fieldName
     * @param int $minimumDocumentCount
     * @param int $size
     */
    public function __construct(
        string $name,
        string $fieldName,
        protected int $minimumDocumentCount = 1,
        protected int $size = 10,
    ) {
        parent::__construct($name, $fieldName);
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        return [
            $this->name => [
                'terms' => [
                    'field' => $this->fieldName,
                    'size' => $this->size,
                    'min_doc_count' => $this->minimumDocumentCount,
                ],
            ],
        ];
    }
}
