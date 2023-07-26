<?php

declare(strict_types=1);

namespace App\Http\Bundle\Presto\Collectors;

class BasicCollector implements Collectorable
{
    /**
     * The array of collect data.
     *
     * @var array
     */
    protected $collection = [];

    protected $searchId = null;

    /**
     * Collect data from presto response.
     *
     * @param object $response
     */
    public function collect(object $response)
    {
        if (!isset($response->data)) {
            return;
        }

        $this->collection = array_merge($this->collection, $response->data);
    }

    public function buildSearchId($nextUri)
    {
        $url_components = parse_url($nextUri);
        parse_str($url_components['query'], $params);
        $count = explode('?',explode('/',$nextUri)[7])[0];
        $queryId = explode('/',$nextUri)[6];
        $slug = str_replace("?slug=", "", $params['slug']);
        $searchId = $queryId.':'.$count.':'.$slug;
        return $searchId;
    }


    /**
     * Get collect data.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->collection;
    }
}
