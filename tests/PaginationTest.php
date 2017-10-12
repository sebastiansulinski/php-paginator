<?php

namespace Tests;

use SSD\Paginator\Pagination;

class PaginationTest extends BaseCase
{
    /**
     * @test
     */
    public function correctly_determines_whether_there_is_only_one_page()
    {
        $pagination = new Pagination(
            $this->get('/'),
            187,
            10
        );

        $this->assertFalse($pagination->hasOnlyOnePage());


        $pagination = new Pagination(
            $this->get('/'),
            10,
            10
        );

        $this->assertTrue($pagination->hasOnlyOnePage());
    }

    /**
     * @test
     */
    public function correctly_returns_current_page_as_first_when_there_are_not_enough_records()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 3]),
            10,
            10,
            'page'
        );

        $this->assertEquals(1, $pagination->current());


        $pagination = new Pagination(
            $this->get('/', ['page' => 3]),
            20,
            10,
            'page'
        );

        $this->assertEquals(2, $pagination->current());
    }

    /**
     * @test
     */
    public function converts_page_number_from_string_to_integer()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 'something']),
            10,
            10,
            'page'
        );

        $this->assertEquals(1, $pagination->current());
    }

    /**
     * @test
     */
    public function converts_negative_page_number_to_first_page()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => -1]),
            20,
            10,
            'page'
        );

        $this->assertEquals(1, $pagination->current());
    }

    /**
     * @test
     */
    public function correctly_returns_current_page_when_number_of_records_is_right()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 3]),
            30,
            10,
            'page'
        );

        $this->assertEquals(3, $pagination->current());
    }

    /**
     * @test
     */
    public function correctly_identifies_first_page()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 1]),
            30,
            10,
            'page'
        );

        $this->assertTrue($pagination->isFirstPage());


        $pagination = new Pagination(
            $this->get('/', ['page' => 2]),
            30,
            10,
            'page'
        );

        $this->assertFalse($pagination->isFirstPage());
    }

    /**
     * @test
     */
    public function correctly_identifies_current_page()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 2]),
            30,
            10,
            'page'
        );

        $this->assertTrue($pagination->isCurrentPage(2));


        $pagination = new Pagination(
            $this->get('/', ['page' => 2]),
            30,
            10,
            'page'
        );

        $this->assertFalse($pagination->isCurrentPage(3));
    }

    /**
     * @test
     */
    public function correctly_identifies_last_page()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 3]),
            30,
            10,
            'page'
        );

        $this->assertTrue($pagination->isLastPage());


        $pagination = new Pagination(
            $this->get('/', ['page' => 2]),
            30,
            10,
            'page'
        );

        $this->assertFalse($pagination->isLastPage());
    }

    /**
     * @test
     */
    public function returns_correct_offset()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 1]),
            30,
            10,
            'page'
        );

        $this->assertEquals(0, $pagination->offset());


        $pagination = new Pagination(
            $this->get('/', ['page' => 2]),
            30,
            10,
            'page'
        );

        $this->assertEquals(10, $pagination->offset());


        $pagination = new Pagination(
            $this->get('/', ['page' => 3]),
            30,
            10,
            'page'
        );

        $this->assertEquals(20, $pagination->offset());


        $pagination = new Pagination(
            $this->get('/', ['page' => 4]),
            30,
            10,
            'page'
        );

        $this->assertEquals(20, $pagination->offset());
    }

    /**
     * @test
     */
    public function returns_correct_limit()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 1]),
            30,
            12,
            'page'
        );

        $this->assertEquals(12, $pagination->limit());
    }

    /**
     * @test
     */
    public function correctly_returns_total_number_of_records()
    {
        $pagination = new Pagination(
            $this->get('/'),
            187,
            10
        );

        $this->assertEquals(187, $pagination->numberOfRecords());
    }

    /**
     * @test
     */
    public function correctly_returns_number_of_pages()
    {
        $pagination = new Pagination(
            $this->get('/'),
            187,
            18
        );

        $this->assertEquals(11, $pagination->numberOfPages());
    }

    /**
     * @test
     */
    public function returns_correct_key()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 1]),
            10,
            10
        );

        $this->assertEquals('page', $pagination->key());


        $pagination = new Pagination(
            $this->get('/', ['page' => 2]),
            30,
            10,
            'id'
        );

        $this->assertEquals('id', $pagination->key());
        $this->assertEquals(1, $pagination->current());


        $pagination = new Pagination(
            $this->get('/', ['id' => 2]),
            30,
            10,
            'id'
        );

        $this->assertEquals('id', $pagination->key());
        $this->assertEquals(2, $pagination->current());
    }

    /**
     * @test
     */
    public function returns_correct_url_for_page_number_within_available_page_range()
    {
        $pagination = new Pagination(
            $this->get('/news'),
            30,
            10
        );

        $this->assertEquals('http://localhost/news', $pagination->url(-1));


        $pagination = new Pagination(
            $this->get('/news'),
            30,
            10
        );

        $this->assertEquals('http://localhost/news', $pagination->url(1));


        $pagination = new Pagination(
            $this->get('/news'),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?page=2', $pagination->url(2));


        $pagination = new Pagination(
            $this->get('/news', ['id' => 3]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?id=3&page=3', $pagination->url(5));
    }

    /**
     * @test
     */
    public function returns_correct_url_with_additional_parameters_in_the_query_string_replacing_page_number()
    {
        $params = ['page' => 1, 'id' => 223, 'q' => 'Some query'];

        $query = http_build_query(array_merge($params, ['page' => 2]));

        $pagination = new Pagination(
            $this->get('/news', $params),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?'.$query, $pagination->url(2));
    }

    /**
     * @test
     */
    public function returns_correct_first_page_url()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 2, 'id' => 23]),
            30,
            10
        );

        $this->assertEquals('http://localhost/?id=23', $pagination->firstPageUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 2, 'id' => 23]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?id=23', $pagination->firstPageUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => -2, 'id' => 23]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?id=23', $pagination->firstPageUrl());


        $pagination = new Pagination(
            $this->get('/news'),
            30,
            10
        );

        $this->assertEquals('http://localhost/news', $pagination->firstPageUrl());
    }

    /**
     * @test
     */
    public function returns_correct_last_page_url()
    {
        $pagination = new Pagination(
            $this->get('/', ['page' => 2, 'id' => 23]),
            10,
            10
        );

        $this->assertEquals('http://localhost/?id=23', $pagination->lastPageUrl());


        $pagination = new Pagination(
            $this->get('/'),
            10,
            10
        );

        $this->assertEquals('http://localhost', $pagination->lastPageUrl());


        $pagination = new Pagination(
            $this->get('/', ['page' => 4]),
            30,
            10
        );

        $this->assertEquals('http://localhost/?page=3', $pagination->lastPageUrl());
    }

    /**
     * @test
     */
    public function returns_correct_previous_url_with_additional_parameters_in_the_query_string()
    {
        $pagination = new Pagination(
            $this->get('/news', ['page' => 3, 'id' => 23]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?page=2&id=23', $pagination->previousUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 2, 'id' => 23]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?id=23', $pagination->previousUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 1, 'id' => 23]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?id=23', $pagination->previousUrl());


        $pagination = new Pagination(
            $this->get('/news', ['id' => 23]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?id=23', $pagination->previousUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 2]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news', $pagination->previousUrl());


        $pagination = new Pagination(
            $this->get('/news'),
            30,
            10
        );

        $this->assertEquals('http://localhost/news', $pagination->previousUrl());
    }

    /**
     * @test
     */
    public function returns_correct_current_url()
    {
        $pagination = new Pagination(
            $this->get('/news', ['page' => 1]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news', $pagination->currentUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 2]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?page=2', $pagination->currentUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 4]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?page=3', $pagination->currentUrl());
    }

    /**
     * @test
     */
    public function returns_correct_next_url()
    {
        $pagination = new Pagination(
            $this->get('/news', ['page' => 1]),
            10,
            10
        );

        $this->assertEquals('http://localhost/news', $pagination->nextUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 2]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?page=3', $pagination->nextUrl());


        $pagination = new Pagination(
            $this->get('/news', ['page' => 4]),
            30,
            10
        );

        $this->assertEquals('http://localhost/news?page=3', $pagination->nextUrl());
    }
}