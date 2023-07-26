<?php

declare(strict_types=1);

namespace App\Http\Bundle\Presto;

use App\Http\Bundle\Presto\Connection\Connection;
use App\Http\Bundle\Presto\Connection\Manager;

class Presto
{
    /**
     * The container instance.
     *
     * @var App\Http\Bundle\Presto\Container
     */
    protected $container;

    /**
     * Connection manager.
     *
     * @var App\Http\Bundle\Presto\Connection\Manager
     */
    protected $manager;

    /**
     * The current globally used instance.
     *
     * @var App\Http\Bundle\Presto\Presto
     */
    protected static $instance;

    /**
     * Create a new presto manager instance.
     *
     * @param App\Http\Bundle\Presto\Container|null $container
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?? new Container();
        $this->manager = new Manager($this->container);
    }

    /**
     * Register a connection.
     *
     * @param  array  $config
     * @param  string $name
     * @return void
     */
    public function addConnection(array $config, $name = 'default')
    {
        $this->container[$name] = $config;
    }

    /**
     * Make this instance available globally.
     */
    public function setAsGlobal()
    {
        static::$instance = $this;
    }

    /**
     * Get a fluent query builder instance.
     *
     * @param  string $query
     * @param  string $connection
     * @return App\Http\Bundle\Presto\QueryBuilder
     */
    public static function query($query, $connection = null): QueryBuilder
    {
        return static::$instance->connection($connection)->query($query);
    }

    /**
     * Get connection instance from manager.
     *
     * @param  string|null $connection
     * @return App\Http\Bundle\Presto\Connection\Connection
     */
    public function connection(string $connection = null): Connection
    {
        $connection = $connection ?? 'default';

        return $this->manager->connection($connection);
    }

    /**
     * Get connections from manager.
     *
     * @return array
     */
    public static function getConnections(): array
    {
        return static::$instance->manager->getConnections();
    }
}
