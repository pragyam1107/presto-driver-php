<?php

declare(strict_types=1);

namespace App\Http\Bundle\Presto\Connection;

class Connection
{
    use QueryTrait;

    /**
     * Default user.
     *
     * @var string
     */
    const DEFAULT_USER = 'presto';

    /**
     * Default host.
     *
     * @var string
     */
    const DEFAULT_HOST = 'localhost:8080';

    /**
     * Default catalog.
     *
     * @var string
     */
    const DEFAULT_CATALOG = 'default';

    const DEFAULT_RESOURCE_ESTIMATE = '';

    const DEFAULT_RESOURCE_GROUP = 'global';

    const DEFAULT_CLIENT_TAGS = 'global';

    const DEFAULT_SOURCE = 'presto-cli';

    /**
     * The user of connection.
     *
     * @var string
     */
    protected $user;

    /**
     * The host of connection.
     *
     * @var string
     */
    protected $host;

    /**
     * The schema of connection.
     *
     * @var string
     */
    protected $schema;

    /**
     * The catalog of connection.
     *
     * @var string
     */
    protected $catalog;
    /**
     * @var string
     */
    protected $client_tags;
    /**
     * @var string
     */
    protected $source;
    /**
     * @var string
     */
    protected $resource_estimate;

    /**
     * Create a new connection instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setupConfig($config);
    }

    /**
     * Setup config.
     *
     * @param array $config
     */
    protected function setupConfig(array $config)
    {
        $this->user = $config['user'] ?? static::DEFAULT_USER;
        $this->host = $config['host'] ?? static::DEFAULT_HOST;
        $this->schema = $config['schema'] ?? '';
        $this->catalog = $config['catalog'] ?? static::DEFAULT_CATALOG;
        $this->client_tags = $config['client_tags'] ?? static::DEFAULT_CLIENT_TAGS;
        $this->source = $config['source'] ?? static::DEFAULT_SOURCE;
        $this->resource_estimate = $config['resource_estimate'] ?? static::DEFAULT_RESOURCE_ESTIMATE;
        $this->resource_group = $config['resource_group'] ?? static::DEFAULT_RESOURCE_GROUP;
    }

    /**
     * Get connection user.
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Get connection host.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get connection schema.
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Get connection catalog.
     *
     * @return string
     */
    public function getCatalog(): string
    {
        return $this->catalog;
    }

    /**
     * @return string
     */
    public function getResourceEstimate(): string
    {
        return $this->resource_estimate;
    }

    /**
     * @return string
     */
    public function getClientTags(): string
    {
        return $this->client_tags;
    }

    /**
     * @return string
     */
    public function getResourceGroup(): string
    {
        return $this->resource_group;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }
}
