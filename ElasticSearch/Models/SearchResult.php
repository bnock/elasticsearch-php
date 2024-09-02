<?php

namespace App\Services\ElasticSearch\Models;

use Illuminate\Support\Collection;

class SearchResult
{
    /**
     * Constructor.
     *
     * @param Collection $items
     * @param int $total
     * @param int $took
     * @param float|null $maxScore
     * @param Collection|null $aggregations
     */
    public function __construct(
        protected Collection $items,
        protected int $total,
        protected int $took,
        protected ?float $maxScore = null,
        protected ?Collection $aggregations = null,
    ) {
    }

    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return float|null
     */
    public function getMaxScore(): ?float
    {
        return $this->maxScore;
    }

    /**
     * @return int
     */
    public function getTook(): int
    {
        return $this->took;
    }

    /**
     * @return Collection|null
     */
    public function getAggregations(): ?Collection
    {
        return $this->aggregations;
    }
}
