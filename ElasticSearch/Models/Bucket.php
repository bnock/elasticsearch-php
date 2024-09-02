<?php

namespace App\Services\ElasticSearch\Models;

class Bucket
{
    /**
     * Constructor.
     *
     * @param string $key
     * @param int $documentCount
     */
    public function __construct(protected string $key, protected int $documentCount)
    {
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getDocumentCount(): int
    {
        return $this->documentCount;
    }
}
