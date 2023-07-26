<?php

declare(strict_types=1);

namespace App\Http\Bundle\Presto;

use App\Http\Bundle\Presto\Collectors\Collectorable;
use App\Http\Bundle\Presto\Connection\Connection;
use App\Http\Bundle\Presto\Exceptions\PrestoException;
use GuzzleHttp\Client;

class Processor
{
    /**
     * The statement uri.
     *
     * @var string
     */
    const STATEMENT_URI = '/v1/statement';

    /**
     * Send request delay milliseconds.
     *
     * @var int
     */
    const DELAY = 50;

    /**
     * The connection information.
     *
     * @var App\Http\Bundle\Presto\Connection\Connection
     */
    protected $connection;

    /**
     * Http client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Response next uri.
     *
     * @var ?string
     */
    protected $nextUri = null;

    protected $waitingForPrerequisites = null;

    /**
     * Collect response data.
     *
     * @var App\Http\Bundle\Presto\Collectors\Collectorable
     */
    protected $collector;

    /**
     * Create a new instance.
     *
     * @param App\Http\Bundle\Presto\Connection\Connection $connection
     * @param \GuzzleHttp\Client|null                $client
     */
    public function __construct(Connection $connection, Client $client = null)
    {
        $this->connection = $connection;
        $this->client = $client ?? new Client(['delay' => static::DELAY]);
    }

    /**
     * Execute connection query.
     *
     * @param  string                                   $query
     * @param App\Http\Bundle\Presto\Collectors\Collectorable $collector
     * @return array
     */
    public function execute(string $query, Collectorable $collector): array
    {
        $this->collector = $collector;

        $this->resolve($this->sendQuery($query));

        while ($this->hasNextUri()) {
            $this->resolve($this->sendNextUri());
        }

        return $this->collector->get();
    }

    public function getSearchId(string $query, Collectorable $collector): string
    {
        $this->collector = $collector;

        $this->resolve($this->sendQuery($query));

        if ($this->hasNextUri()) {
            $this->resolveQueryId($this->sendNextUri());
        }

        return $this->collector->buildSearchId($this->nextUri);
    }

    /**
     * Send query request.
     *
     * @param  string $query
     * @return object
     */
    protected function sendQuery(string $query): object
    {
        $baseUri = $this->connection->getHost() . static::STATEMENT_URI;
        $headers = [
            'X-Presto-User' => $this->connection->getUser(),
            'X-Presto-Schema' => $this->connection->getSchema(),
            'X-Presto-Catalog' => $this->connection->getCatalog(),
            'X-Presto-Source' => $this->connection->getSource(),
            'X-Presto-Client-Tags' => $this->connection->getClientTags(),
            'X-Presto-Resource-Estimate' => $this->connection->getResourceEstimate(),
            'X-Resource-Group' => $this->connection->getResourceGroup()
        ];

        $response = $this->client->post($baseUri, ['headers' => $headers, 'body' => $query]);

        return json_decode((string) $response->getBody());
    }

    /**
     * Send next query.
     *
     * @return object
     */
    protected function sendNextUri(): object
    {
        $response = $this->client->get($this->nextUri);

        return json_decode((string) $response->getBody());
    }

    /**
     * Resolve response.
     *
     * @param object $response
     */
    protected function resolve(object $response)
    {
        $this->checkState($response);

        $this->setNextUri($response);

        $this->collector->collect($response);


    }

    protected function resolveQueryId(object $response)
    {
        $this->setNextUri($response);
    }

    /**
     * Check response state.
     *
     * @param  object $response
     *
     * @throws App\Http\Bundle\Presto\Exceptions\PrestoException
     */
    protected function checkState(object $response)
    {
        if ($response->stats->state === PrestoState::FAILED) {
            $message = "{$response->error->errorName}: {$response->error->message}";
            throw new PrestoException($message);
        }
    }

    /**
     * Set next uri.
     *
     * @param  object $response
     */
    protected function setNextUri(object $response)
    {
        $this->nextUri = $response->nextUri ?? null;
    }

    /**
     * Determine if next uri is set or not.
     *
     * @return bool
     */
    protected function hasNextUri(): bool
    {
        return isset($this->nextUri);
    }

}
