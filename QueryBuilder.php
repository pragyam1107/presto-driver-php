<?php

declare(strict_types=1);

namespace App\Http\Bundle\Presto;

use App\Http\Bundle\Presto\Collectors\AssocCollector;
use App\Http\Bundle\Presto\Collectors\BasicCollector;

class QueryBuilder
{
    /**
     * The processor for query.
     *
     * @var App\Http\Bundle\Presto\Processor
     */
    protected $processor;

    /**
     * The raw of query.
     *
     * @var string
     */
    protected $raw = '';

    /**
     * Create a new query builder instance.
     *
     * @param App\Http\Bundle\Presto\Processor $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Set raw query.
     *
     * @param  string $query
     * @return App\Http\Bundle\Presto\QueryBuilder
     */
    public function raw(string $query): QueryBuilder
    {
        $this->raw = $query;

        return $this;
    }

    /**
     * Execute the query statement.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->processor->execute($this->toSql(), new BasicCollector());
    }

    public function getAsyncSearchId(): string
    {
        return $this->processor->getSearchId($this->toSql(), new BasicCollector());
    }

    /**
     * Execute the query statement with assoc column.
     *
     * @return array
     */
    public function getAssoc(): array
    {
        return $this->processor->execute($this->toSql(), new AssocCollector());
    }

    /**
     * Get raw query statement.
     *
     * @return string
     */
    public function toSql(): string
    {
        return $this->raw;
    }
}
