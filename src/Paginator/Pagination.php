<?php

namespace SSD\Paginator;

use Illuminate\Http\Request;

class Pagination
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var int
     */
    private $number_of_records;

    /**
     * @var int
     */
    private $per_page;

    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $number_of_pages = 1;

    /**
     * @var int
     */
    private $previous = 1;

    /**
     * @var int
     */
    private $current = 1;

    /**
     * @var int
     */
    private $next = 1;

    /**
     * @var array
     */
    private $query_without_page_key = [];

    /**
     * Pagination constructor.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $number_of_records
     * @param  int $per_page
     * @param  string $key
     */
    public function __construct(
        Request $request,
        int $number_of_records,
        int $per_page = 10,
        string $key = 'page'
    ) {
        $this->request = $request;
        $this->number_of_records = $number_of_records;
        $this->per_page = $per_page;
        $this->key = $key;

        $this->setUp();
    }

    /**
     * Set all properties.
     *
     * @return void
     */
    private function setUp(): void
    {
        $this->setQueryWithoutPageKey();

        if ($this->hasOnlyOnePage()) {
            return;
        }

        $this->setNumberOfPages();
        $this->setCurrentPage();
        $this->setPreviousPage();
        $this->setNextPage();
    }

    /**
     * Check if there's only one page.
     *
     * @return bool
     */
    public function hasOnlyOnePage(): bool
    {
        return $this->number_of_records <= $this->per_page;
    }

    /**
     * Set number of pages.
     *
     * @return void
     */
    private function setNumberOfPages(): void
    {
        $this->number_of_pages = (int)ceil($this->number_of_records / $this->per_page);
    }

    /**
     * Set current page.
     *
     * @return void
     */
    private function setCurrentPage(): void
    {
        $this->current = $this->parseCurrentPage(
            $this->request->get($this->key)
        );
    }

    /**
     * Parse current page.
     *
     * @param  mixed|null $current_page
     * @return int
     */
    private function parseCurrentPage($current_page = null)
    {
        if (is_null($current_page) || ($current_page = (int)$current_page) < 1) {
            return 1;
        }

        return $this->purifyPageNumber($current_page);
    }

    /**
     * Purify page number.
     *
     * @param  int $number
     * @return int
     */
    private function purifyPageNumber(int $number): int
    {
        if ($number > $this->number_of_pages) {
            return $this->number_of_pages;
        }

        if ($number < 1) {
            return 1;
        }

        return $number;
    }

    /**
     * Get current page.
     *
     * @return int
     */
    public function current(): int
    {
        return $this->current;
    }

    /**
     * Set previous page.
     *
     * @return void
     */
    private function setPreviousPage(): void
    {
        $this->previous = $this->getPreviousPage();
    }

    /**
     * Get previous page.
     *
     * @return int
     */
    private function getPreviousPage(): int
    {
        if ($this->hasOnlyOnePage() || $this->isFirstPage()) {
            return $this->current;
        }

        return $this->current - 1;
    }

    /**
     * Check if current page is the first one.
     *
     * @return bool
     */
    public function isFirstPage(): bool
    {
        return $this->current === 1;
    }

    /**
     * Check if argument matches current page.
     *
     * @param  int $value
     * @return bool
     */
    public function isCurrentPage(int $value): bool
    {
        return $this->current === $value;
    }

    /**
     * Check if current page is the last one.
     *
     * @return bool
     */
    public function isLastPage(): bool
    {
        return $this->current === $this->number_of_pages;
    }

    /**
     * Set next page.
     *
     * @return void
     */
    private function setNextPage(): void
    {
        $this->next = $this->getNextPage();
    }

    /**
     * Set query without page key.
     *
     * @return void
     */
    private function setQueryWithoutPageKey(): void
    {
        $this->query_without_page_key = array_filter(
            $this->request->query(),
            function($key) {
                return $key !== $this->key;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Get next page.
     *
     * @return int
     */
    private function getNextPage(): int
    {
        if ($this->hasOnlyOnePage() || $this->isLastPage()) {
            return $this->current;
        }

        return $this->current + 1;
    }

    /**
     * Get offset.
     *
     * @return int
     */
    public function offset(): int
    {
        if ($this->isFirstPage()) {
            return 0;
        }

        return ($this->current - 1) * $this->per_page;
    }

    /**
     * Get number of records per page.
     *
     * @return int
     */
    public function limit(): int
    {
        return $this->per_page;
    }

    /**
     * Get total number of records.
     *
     * @return int
     */
    public function numberOfRecords(): int
    {
        return $this->number_of_records;
    }

    /**
     * Get number of pages.
     *
     * @return int
     */
    public function numberOfPages(): int
    {
        return $this->number_of_pages;
    }

    /**
     * Get pagination key.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Get url.
     *
     * @param  int $page_number
     * @return string
     */
    public function url(int $page_number): string
    {
        $page_number = $this->purifyPageNumber($page_number);

        if ($page_number === 1) {
            return $this->firstPageUrl();
        }

        return $this->request->fullUrlWithQuery([
            $this->key => $page_number
        ]);
    }

    /**
     * Get first page url.
     *
     * @return string
     */
    public function firstPageUrl(): string
    {
        if (empty($this->query_without_page_key)) {
            return $this->request->url();
        }

        return $this->urlWithoutPageKey();
    }

    /**
     * Get question mark for query string.
     *
     * @param  string|null $path
     * @return string
     */
    private function withQuestion($path): string
    {
        $uri = $this->request->getBaseUrl().$this->request->getPathInfo();

        if (!$path) {
            return $uri.$path;
        }

        return $uri == '/' ? '/?'.$path : '?'.$path;
    }

    /**
     * Get url without page key in query string.
     *
     * @return string
     */
    private function urlWithoutPageKey(): string
    {
        $queryString = http_build_query($this->query_without_page_key, '', '&');

        return $this->request->url().$this->withQuestion($queryString);
    }

    /**
     * Get last page url.
     *
     * @return string
     */
    public function lastPageUrl(): string
    {
        if ($this->hasOnlyOnePage()) {
            return $this->firstPageUrl();
        }

        return $this->url($this->number_of_pages);
    }

    /**
     * Get previous url.
     *
     * @return string
     */
    public function previousUrl(): string
    {
        if ($this->previous < 2) {
            return $this->firstPageUrl();
        }

        return $this->url($this->previous);
    }

    /**
     * Get current url.
     *
     * @return string
     */
    public function currentUrl(): string
    {
        if ($this->hasOnlyOnePage() || $this->isFirstPage()) {
            return $this->firstPageUrl();
        }

        return $this->url($this->current);
    }

    /**
     * Get next url.
     *
     * @return string
     */
    public function nextUrl(): string
    {
        if ($this->hasOnlyOnePage()) {
            return $this->firstPageUrl();
        }

        return $this->url($this->next);
    }
}