<?php

namespace Tests;

use SSD\Paginator\Request;
use SSD\Paginator\Collection;
use PHPUnit\Framework\TestCase;

abstract class BaseCase extends TestCase
{
    /**
     * Get instance of Request with path and query string.
     *
     * @param  string $uri
     * @param  array $query
     * @param  array $server
     * @return \SSD\Paginator\Request
     */
    public function get(string $uri = '/', array $query = [], array $server = []): Request
    {
        return Request::create(
            '/'.ltrim($uri, '/'), 'GET', $query, [], [], $server
        );
    }

    /**
     * Get collection of records.
     *
     * @param  int $number
     * @return \SSD\Paginator\Collection
     */
    protected function getRecords(int $number = 10): Collection
    {
        return new Collection(range(1, $number));
    }
}