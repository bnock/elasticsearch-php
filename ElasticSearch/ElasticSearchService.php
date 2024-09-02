<?php

namespace App\Services\ElasticSearch;

use App\Services\ElasticSearch\Exceptions\ElasticSearchException;
use App\Factories\ElasticSearchFactory;
use App\Services\ElasticSearch\Models\Aggregation;
use App\Services\ElasticSearch\Models\Bucket;
use App\Services\ElasticSearch\Models\ScrollResult;
use App\Services\ElasticSearch\Models\SearchResult;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Illuminate\Support\Collection;

class ElasticSearchService
{
    /**
     * Create an index with mappings.
     *
     * @param string $name
     * @param array $mappings
     * @return void
     * @throws AuthenticationException
     * @throws ElasticSearchException
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function createIndex(string $name, array $mappings): void
    {
        $response = ElasticSearchFactory::getClient()->indices()->create([
            'index' => $name,
            'body' => [
                'mappings' => [
                    'properties' => $mappings,
                ],
            ],
        ]);

        if (!$this->isAcknowledged($response)) {
            throw new ElasticSearchException(sprintf('Unable to create %s index', $name));
        }
    }

    /**
     * Remove an index.
     *
     * @param string $name
     * @return void
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ElasticSearchException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function removeIndex(string $name): void
    {
        $response = ElasticSearchFactory::getClient()->indices()->delete([
            'index' => $name,
        ]);

        if (!$this->isAcknowledged($response)) {
            throw new ElasticSearchException(sprintf('Unable to remove %s index', $name));
        }
    }

    /**
     * Index a document.
     *
     * @param string $name
     * @param string $id
     * @param array $body
     * @param bool $isUpdate
     * @return void
     * @throws ElasticSearchException
     * @throws AuthenticationException
     * @throws ClientResponseException
     */
    public function index(string $name, string $id, array $body, bool $isUpdate): void
    {
        $method = $isUpdate ? 'update' : 'index';
        $successMethod = $isUpdate ? 'isUpdated' : 'isIndexed';

        $response = ElasticSearchFactory::getClient()->$method([
            'index' => $name,
            'id' => $id,
            'body' => $isUpdate ? (['doc' => $body]) : $body,
        ]);

        if (!$this->$successMethod($response)) {
            throw new ElasticSearchException(sprintf(
                'Unable to index document %s using %s method in %s index',
                $id,
                $method,
                $name
            ));
        }
    }

    /**
     * Upsert a document.
     *
     * @param string $name
     * @param string $id
     * @param array $body
     * @return void
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ElasticSearchException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function upsert(string $name, string $id, array $body): void
    {
        $response = ElasticSearchFactory::getClient()->update([
            'index' => $name,
            'id' => $id,
            'body' => [
                'doc' => $body,
                'doc_as_upsert' => true,
            ],
        ]);

        if (!$this->isUpdated($response)) {
            throw new ElasticSearchException(sprintf('Unable to upsert document %s in %s index', $id, $name));
        }
    }

    /**
     * Delete a document from an index.
     *
     * @param string $name
     * @param string $id
     * @return void
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ElasticSearchException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function remove(string $name, string $id): void
    {
        $response = ElasticSearchFactory::getClient()->delete([
            'index' => $name,
            'id' => $id,
        ]);

        if (!$this->isDeleted($response)) {
            throw new ElasticSearchException(sprintf('Unable to delete document %s from %s index', $id, $name));
        }
    }

    /**
     * Search an index.
     *
     * @param string $indexName
     * @param SearchBuilder $builder
     * @return SearchResult
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ElasticSearchException
     * @throws ServerResponseException
     */
    public function search(string $indexName, SearchBuilder $builder): SearchResult
    {
        $response = ElasticSearchFactory::getClient()->search([
            'index' => $indexName,
            'body' => $builder->toElasticQuery(),
            'timeout' => '3s',
        ]);

        return $this->parseSearchResults($response, $builder->hasSelectedFields(), $builder->isSourceIncluded());
    }

    /**
     * Scroll search results.
     *
     * @param string $indexName
     * @param int $size
     * @param Collection $fields
     * @param int $ttlMinutes
     * @param string|null $scrollId
     * @return ScrollResult
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws ElasticSearchException
     */
    public function scroll(
        string $indexName,
        int $size,
        Collection $fields,
        int $ttlMinutes,
        string $scrollId = null,
    ): ScrollResult {
        if ($ttlMinutes > 60 * 24) { // Cannot exceed 1 day
            throw new ElasticSearchException('Scroll TTL cannot exceed 1 day');
        }

        if (filled($scrollId)) {
            $response = ElasticSearchFactory::getClient()->scroll([
                'scroll_id' => $scrollId,
                'scroll' => $ttlMinutes . 'm',
            ]);
        } else {
            $response = ElasticSearchFactory::getClient()->search([
                'index' => $indexName,
                'scroll' => $ttlMinutes . 'm',
                'body' => [
                    'size' => $size,
                    '_source' => false,
                    'fields' => $fields->all(),
                ]
            ]);
        }

        return new ScrollResult($response['_scroll_id'], $this->parseSearchResults($response, true, false));
    }

    /**
     * Clear a scroll.
     *
     * @param string $scrollId
     * @return void
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function clearScroll(string $scrollId): void
    {
        ElasticSearchFactory::getClient()->clearScroll(['scroll_id' => $scrollId]);
    }

    /**
     * Parse the Elasticsearch response.
     *
     * @param Elasticsearch $response
     * @param bool $hasSelectedFields
     * @param bool $includeSource
     * @return SearchResult
     * @throws ElasticSearchException
     */
    protected function parseSearchResults(
        Elasticsearch $response,
        bool $hasSelectedFields,
        bool $includeSource,
    ): SearchResult {
        $aggregations = null;

        if (isset($response['hits']['hits']) && is_array($response['hits']['hits'])) {
            if (isset($response['aggregations']) && is_array($response['aggregations'])) {
                $aggregations = collect();

                foreach ($response['aggregations'] as $name => $aggregation) {
                    $buckets = collect();

                    foreach ($aggregation['buckets'] as $bucket) {
                        $buckets->push(new Bucket($bucket['key'], $bucket['doc_count']));
                    }

                    $aggregations->put($name, new Aggregation($name, $buckets));
                }
            }

            $items = collect($response['hits']['hits'])->map(function (array $hits) use (
                $hasSelectedFields,
                $includeSource,
            ) {
                $items = collect($includeSource ? $hits['_source'] : []);

                if ($hasSelectedFields) {
                    $items = $items->merge($this->extractFields($hits['fields'] ?? []));
                }

                $items->put('_id', $hits['_id'] ?? null);

                return $items;
            });

            return new SearchResult(
                $items,
                $response['hits']['total']['value'],
                $response['took'],
                $response['hits']['max_score'],
                $aggregations,
            );
        } else {
            throw new ElasticSearchException('No hits found in Elasticsearch response');
        }
    }

    /**
     * Extra the data from the field array.
     *
     * @param array $fieldsArray
     * @return Collection
     */
    protected function extractFields(array $fieldsArray): Collection
    {
        if (blank($fieldsArray)) {
            return collect();
        }

        return collect($fieldsArray)->mapWithKeys(function (array $data, string $fieldName) {
            return [$fieldName => value(fn (array $data) => count($data) === 1 ? $data[0] : $data, $data)];
        });
    }

    /**
     * Determines if the response indicated acknowledgement.
     *
     * @param object $response
     * @return bool
     */
    protected function isAcknowledged(object $response): bool
    {
        return isset($response['acknowledged']) && $response['acknowledged'] === true;
    }

    /**
     * Determine if the response indicates successful creation.
     *
     * @param object $response
     * @return bool
     */
    protected function isIndexed(object $response): bool
    {
        return isset($response['result']) && in_array($response['result'], ['created', 'updated']);
    }

    /**
     * Determine if the response indicates a successful update. Note: this could also mean a noop occurred because
     * nothing changed.
     *
     * @param object $response
     * @return bool
     */
    protected function isUpdated(object $response): bool
    {
        return isset($response['result']) && in_array($response['result'], ['updated', 'noop', 'created'], true);
    }

    /**
     * Determine if the response indicates successful deletion.
     *
     * @param object $response
     * @return bool
     */
    protected function isDeleted(object $response): bool
    {
        return isset($response['result']) && $response['result'] === 'deleted';
    }
}
