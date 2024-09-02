<?php

namespace App\Services\ElasticSearch\Models;

class ScrollResult
{
    public function __construct(protected string $scrollId, protected SearchResult $searchResult)
    {
    }

    public function getScrollId(): string
    {
        return $this->scrollId;
    }

    public function getSearchResult(): SearchResult
    {
        return $this->searchResult;
    }
}
