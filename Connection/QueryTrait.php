<?php

declare(strict_types=1);

namespace App\Http\Bundle\Presto\Connection;

use App\Http\Bundle\Presto\Processor;
use App\Http\Bundle\Presto\QueryBuilder;

trait QueryTrait
{
    /**
     * Begin a fluent query against a raw query.
     *
     * @param  string $query
     * @return Presto\QueryBuilder
     */
    public function query(string $query): QueryBuilder
    {
        return $this->getBuilder()->raw($query);
    }

    /**
     * Get query builder with processor.
     *
     * @param  Presto\Processor|null $processor
     * @return Presto\QueryBuilder
     */
    protected function getBuilder(Processor $processor = null): QueryBuilder
    {
        $processor = $processor ?? new Processor($this);

        return new QueryBuilder($processor);
    }
}
