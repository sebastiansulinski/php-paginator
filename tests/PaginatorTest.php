<?php

namespace Tests;

use SSD\Paginator\Collection;
use SSD\Paginator\Pagination;
use SSD\Paginator\VueSelectPaginator;

class PaginatorTest extends BaseCase
{
    /**
     * @test
     */
    public function returns_pagination_instance()
    {
        $paginator = new VueSelectPaginator(
            $pagination = new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            new Collection
        );

        $this->assertInstanceOf(Pagination::class, $paginator->pagination());

        $this->assertSame($pagination, $paginator->pagination());
    }

    /**
     * @test
     */
    public function returns_correct_records_collection()
    {
        $paginator = new VueSelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            $empty = new Collection
        );

        $this->assertSame($empty, $paginator->records());


        $paginator = new VueSelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            $records = $this->getRecords(8)
        );

        $this->assertSame($records, $paginator->records());
    }

    /**
     * @test
     */
    public function correctly_determines_whether_there_are_records_available()
    {
        $paginator = new VueSelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            new Collection
        );

        $this->assertFalse($paginator->hasRecords());


        $paginator = new VueSelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            $this->getRecords(8)
        );

        $this->assertTrue($paginator->hasRecords());
    }

    /**
     * @test
     */
    public function returns_correct_total_number_of_records()
    {
        $paginator = new VueSelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                187,
                10
            ),
            new Collection
        );

        $this->assertEquals(187, $paginator->count());
    }

    /**
     * @test
     */
    public function returns_empty_pagination_view_with_total_records_less_than_per_page()
    {
        $paginator = new VueSelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            new Collection
        );

        $this->assertEmpty($paginator->render());
    }

    /**
     * @test
     */
    public function returns_empty_pagination_view_with_total_records_matching_per_page()
    {
        $paginator = new VueSelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                10,
                10
            ),
            new Collection
        );

        $this->assertEmpty($paginator->render());
    }

    /**
     * @test
     */
    public function returns_pagination_view_with_correct_links()
    {
        $request = $this->get('/', ['page' => 1]);

        $paginator = new VueSelectPaginator(
            new Pagination(
                $request,
                31,
                10
            ),
            new Collection
        );

        $this->assertStringContainsString($request->url(), $rendered = $paginator->render());

        $this->assertStringContainsString($this->purifyPath($request->fullUrlWithQuery(['page' => 2])), $rendered);
        $this->assertStringContainsString($this->purifyPath($request->fullUrlWithQuery(['page' => 3])), $rendered);
        $this->assertStringContainsString($this->purifyPath($request->fullUrlWithQuery(['page' => 4])), $rendered);
        $this->assertStringNotContainsString($this->purifyPath($request->fullUrlWithQuery(['page' => 5])), $rendered);
    }

    /**
     * Purify path ready for assertions.
     */
    private function purifyPath(string $string): string
    {
        return trim(json_encode($string), '"');
    }

    /**
     * @test
     */
    public function returns_array_representation_of_the_object()
    {
        $request = $this->get('/', ['page' => 2]);

        $paginator = new VueSelectPaginator(
            new Pagination(
                $request,
                31,
                10
            ),
            new Collection
        );

        $this->assertEquals([
            'range' => new Collection([1, 2, 3, 4]),
            'current' => 2,
            'previous' => 1,
            'next' => 3,
            'number_of_records' => 31,
            'number_of_pages' => 4,
            'per_page' => 10,
        ], $paginator->toArray());
    }
}