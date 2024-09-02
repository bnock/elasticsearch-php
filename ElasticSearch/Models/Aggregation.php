<?php

namespace App\Services\ElasticSearch\Models;

use Illuminate\Support\Collection;

class Aggregation
{
    /**
     * @param string $name
     * @param Collection<Bucket> $buckets
     */
    public function __construct(protected string $name, protected Collection $buckets)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<Bucket>
     */
    public function getBuckets(): Collection
    {
        return $this->buckets;
    }

    /**
     * Get a bucket by key.
     *
     * @param string $key
     * @return Bucket|null
     */
    public function getBucketByKey(string $key): ?Bucket
    {
        return $this->buckets->first(fn(Bucket $bucket) => $bucket->getKey() === $key);
    }
}
