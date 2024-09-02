<?php

namespace App\Services\ElasticSearch;

use App\Exceptions\ElasticSearchException;
use App\Services\ElasticSearch\Aggregations\AggregationBuilder;
use App\Services\ElasticSearch\Contracts\QueryElement;
use App\Services\ElasticSearch\Enumerations\SortDirection;
use App\Services\ElasticSearch\Fields\ScriptedField;
use App\Services\ElasticSearch\Queries\QueryBuilder;
use App\Services\ElasticSearch\Sorts\Sort;
use Closure;
use Illuminate\Support\Collection;

class SearchBuilder implements QueryElement
{
    protected QueryBuilder $query;
    protected Collection $sorts;
    protected Collection $fields;
    protected bool $includeSource = true;
    protected Collection $scriptedFields;
    protected ?AggregationBuilder $aggregation = null;

    /**
     * Create a new SearchBuilder instance.
     *
     * @param int $from
     * @param int $take
     * @return SearchBuilder
     */
    public static function create(int $from = 0, int $take = 10): SearchBuilder
    {
        return new static($from, $take);
    }

    /**
     * Constructor.
     *
     * @param int $from
     * @param int $take
     */
    public function __construct(protected int $from = 0, protected int $take = 10)
    {
        $this->fields = collect();
        $this->scriptedFields = collect();
        $this->sorts = collect();
        $this->query = QueryBuilder::create();
    }

    /**
     * Create a query.
     *
     * @param Closure $query
     * @return SearchBuilder
     */
    public function query(Closure $query): SearchBuilder
    {
        $query($this->query);

        return $this;
    }

    /**
     * Set the fields on the query.
     *
     * @param Collection $fields
     * @return $this
     */
    public function withFields(Collection $fields): SearchBuilder
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Add a field to the query.
     *
     * @param string $field
     * @return $this
     */
    public function withField(string $field): SearchBuilder
    {
        $this->fields->push($field);

        return $this;
    }

    /**
     * Include the source document in the response.
     *
     * @param bool $include
     * @return $this
     */
    public function includeSource(bool $include = true): SearchBuilder
    {
        $this->includeSource = $include;

        return $this;
    }

    /**
     * Add scripted field.
     *
     * @param ScriptedField $field
     * @return $this
     */
    public function withScriptedField(ScriptedField $field): SearchBuilder
    {
        $this->scriptedFields->push($field);

        return $this;
    }

    /**
     * Start taking records from.
     *
     * @param int $from
     * @return SearchBuilder
     */
    public function from(int $from): SearchBuilder
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Take 'take' records.
     *
     * @param int $take
     * @return SearchBuilder
     */
    public function take(int $take): SearchBuilder
    {
        $this->take = $take;

        return $this;
    }

    /**
     * Add a sort to the query.
     *
     * @param string $fieldName
     * @param SortDirection $direction
     * @return SearchBuilder
     */
    public function sort(string $fieldName, SortDirection $direction = SortDirection::Ascending): SearchBuilder
    {
        $this->sorts->push(new Sort($fieldName, $direction));

        return $this;
    }

    /**
     * Add an aggregation to the query.
     *
     * @param Closure $query
     * @return SearchBuilder
     */
    public function aggregate(Closure $query): SearchBuilder
    {
        $this->aggregation = AggregationBuilder::create();

        $query($this->aggregation);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toElasticQuery(): array
    {
        $query = [
            'from' => $this->from,
            'size' => $this->take,
            'query' => $this->query->toElasticQuery(),
            '_source' => $this->includeSource,
        ];

        if ($this->fields->isNotEmpty()) {
            $query['fields'] = $this->fields->toArray();
        }

        if ($this->scriptedFields->isNotEmpty()) {
            $query['script_fields'] = [];

            $this->scriptedFields->each(function (ScriptedField $field) use (&$query) {
                $query['script_fields'] = array_merge($query['script_fields'], $field->toElasticQuery());
            });
        }

        if ($this->sorts->isNotEmpty()) {
            $query['sort'] = [];

            $this->sorts->each(function (Sort $sort) use (&$query) {
                $query['sort'][] = $sort->toElasticQuery();
            });
        }

        if (filled($this->aggregation)) {
            $query = array_merge($query, $this->aggregation->toElasticQuery());
        }

        return $query;
    }

    /**
     * Get a formatted JSON string that can be pasted into the Kibana Dev Console.
     *
     * @throws ElasticSearchException
     */
    public function toConsoleString(): string
    {
        return json_encode(
            value: $this->toElasticQuery(),
            flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS |
                JSON_UNESCAPED_UNICODE,
        );
    }

    /**
     * Does this builder have any selected fields?
     *
     * @return bool
     */
    public function hasSelectedFields(): bool
    {
        return $this->fields->isNotEmpty() || $this->scriptedFields->isNotEmpty();
    }

    /**
     * Is the document source to be included in this query response?
     *
     * @return bool
     */
    public function isSourceIncluded(): bool
    {
        return $this->includeSource;
    }
}
