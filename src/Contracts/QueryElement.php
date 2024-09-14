<?php

namespace BNock\ElasticsearchPHP\Contracts;

use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;

interface QueryElement
{
    /**
     * Return the Elasticsearch-usable array representation of this Query object.
     *
     * @return array
     * @throws ElasticsearchException
     */
    public function toElasticQuery(): array;
}
