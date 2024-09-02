<?php

namespace App\Services\ElasticSearch\Contracts;

use App\Exceptions\ElasticSearchException;

interface QueryElement
{
    /**
     * Return the Elasticsearch-usable array representation of this Query object.
     *
     * @return array
     * @throws ElasticSearchException
     */
    public function toElasticQuery(): array;
}
