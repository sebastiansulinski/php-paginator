<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SSD\Paginator\Collection;
use SSD\Paginator\Request;

abstract class BaseCase extends TestCase
{
    /**
     * Get instance of Request with path and query string.
     */
    public function get(string $uri = '/', array $query = [], array $server = []): Request
    {
        return Request::create(
            '/'.ltrim($uri, '/'), 'GET', $query, [], [], $server
        );
    }

    /**
     * Get collection of records.
     */
    protected function getRecords(int $number = 10): Collection
    {
        return new Collection(range(1, $number));
    }
}
