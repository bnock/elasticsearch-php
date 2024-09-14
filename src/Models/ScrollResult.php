<?php

namespace BNock\ElasticsearchPHP\Models;

class ScrollResult
{
    /**
     * Constructor.
     *
     * @param string $scrollId
     * @param SearchResult $searchResult
     */
    public function __construct(protected string $scrollId, protected SearchResult $searchResult)
    {
    }

    /**
     * Get the scroll ID.
     *
     * @return string
     */
    public function getScrollId(): string
    {
        return $this->scrollId;
    }

    /**
     * Get the search result.
     *
     * @return SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        return $this->searchResult;
    }
}
