<?php

declare(strict_types=1);

namespace App\Http\Bundle\Presto\Connection;

use App\Http\Bundle\Presto\Container;
use App\Http\Bundle\Presto\Exceptions\ConnectionNotFoundException;

class Manager
{
    /**
     * The manager's container.
     *
     * @var Presto\Container
     */
    protected $container;

    /**
     * The manager's connections.
     *
     * @var array
     */
    protected $connections;

    /**
     * Create a new manager instance.
     *
     * @param App\Http\Bundle\Presto\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get a connection.
     *
     * @param  string $name
     * @return App\Http\Bundle\Presto\Connection\Connection
     */
    public function connection(string $name): Connection
    {
        return $this->makeConnection($name);
    }

    /**
     * Make a connection.
     *
     * @param  string $name
     * @return App\Http\Bundle\Presto\Connection\Connection
     */
    protected function makeConnection(string $name): Connection
    {
        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }

        if (!isset($this->container[$name])) {
            throw new ConnectionNotFoundException("Connection not found: [$name]");
        }

        return $this->connections[$name] = new Connection($this->container[$name]);
    }

    /**
     * Get all connections.
     *
     * @return array
     */
    public function getConnections(): array
    {
        return $this->container->toArray();
    }
}
