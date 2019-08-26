<?php

namespace Tests;

use SSD\Paginator\Collection;
use SSD\Paginator\Pagination;
use SSD\Paginator\SelectPaginator;

class PaginatorTest extends BaseCase
{
    /**
     * @test
     */
    public function returns_pagination_instance()
    {
        $paginator = new SelectPaginator(
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
        $paginator = new SelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            $empty = new Collection
        );

        $this->assertSame($empty, $paginator->records());


        $paginator = new SelectPaginator(
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
        $paginator = new SelectPaginator(
            new Pagination(
                $this->get('/', ['page' => 1]),
                8,
                10
            ),
            new Collection
        );

        $this->assertFalse($paginator->hasRecords());


        $paginator = new SelectPaginator(
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
        $paginator = new SelectPaginator(
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
        $paginator = new SelectPaginator(
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
        $paginator = new SelectPaginator(
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

        $paginator = new SelectPaginator(
            new Pagination(
                $request,
                31,
                10
            ),
            new Collection
        );

        $this->assertStringContainsString($request->url(), $paginator->render());

        $this->assertStringContainsString($request->fullUrlWithQuery(['page' => 2]), $paginator->render());
        $this->assertStringContainsString($request->fullUrlWithQuery(['page' => 3]), $paginator->render());
        $this->assertStringContainsString($request->fullUrlWithQuery(['page' => 4]), $paginator->render());
        $this->assertStringNotContainsString($request->fullUrlWithQuery(['page' => 5]), $paginator->render());
    }
}