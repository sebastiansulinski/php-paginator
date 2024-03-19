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
     */
    public function hasOnlyOnePage(): bool
    {
        return $this->number_of_records <= $this->per_page;
    }

    /**
     * Set number of pages.
     */
    private function setNumberOfPages(): void
    {
        $this->number_of_pages = (int) ceil($this->number_of_records / $this->per_page);
    }

    /**
     * Set current page.
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
     * @param  mixed|null  $current_page
     */
    private function parseCurrentPage($current_page = null): int
    {
        if (is_null($current_page) || ($current_page = (int) $current_page) < 1) {
            return 1;
        }

        return $this->purifyPageNumber($current_page);
    }

    /**
     * Purify page number.
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
     */
    public function current(): int
    {
        return $this->current;
    }

    /**
     * Get previous page.
     */
    public function previous(): int
    {
        return $this->previous;
    }

    /**
     * Get next page.
     */
    public function next(): int
    {
        return $this->next;
    }

    /**
     * Get number of items per page.
     */
    public function perPage(): int
    {
        return $this->per_page;
    }

    /**
     * Set previous page.
     */
    private function setPreviousPage(): void
    {
        $this->previous = $this->getPreviousPage();
    }

    /**
     * Get previous page.
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
     */
    public function isFirstPage(): bool
    {
        return $this->current === 1;
    }

    /**
     * Check if argument matches current page.
     */
    public function isCurrentPage(int $value): bool
    {
        return $this->current === $value;
    }

    /**
     * Check if current page is the last one.
     */
    public function isLastPage(): bool
    {
        return $this->current === $this->number_of_pages;
    }

    /**
     * Set next page.
     */
    private function setNextPage(): void
    {
        $this->next = $this->getNextPage();
    }

    /**
     * Set query without page key.
     */
    private function setQueryWithoutPageKey(): void
    {
        $this->query_without_page_key = array_filter(
            $this->request->query(),
            function ($key) {
                return $key !== $this->key;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Get next page.
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
     */
    public function limit(): int
    {
        return $this->per_page;
    }

    /**
     * Get total number of records.
     */
    public function numberOfRecords(): int
    {
        return $this->number_of_records;
    }

    /**
     * Get number of pages.
     */
    public function numberOfPages(): int
    {
        return $this->number_of_pages;
    }

    /**
     * Get pagination key.
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Get url.
     */
    public function url(int $page_number): string
    {
        $page_number = $this->purifyPageNumber($page_number);

        if ($page_number === 1) {
            return $this->firstPageUrl();
        }

        return $this->request->fullUrlWithQuery([
            $this->key => $page_number,
        ]);
    }

    /**
     * Get first page url.
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
     * @param  string|null  $path
     */
    private function withQuestion($path): string
    {
        $uri = $this->request->getBaseUrl().$this->request->getPathInfo();

        if (! $path) {
            return $uri.$path;
        }

        return $uri == '/' ? '/?'.$path : '?'.$path;
    }

    /**
     * Get url without page key in query string.
     */
    private function urlWithoutPageKey(): string
    {
        $queryString = http_build_query($this->query_without_page_key);

        return $this->request->url().$this->withQuestion($queryString);
    }

    /**
     * Get last page url.
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
     */
    public function nextUrl(): string
    {
        if ($this->hasOnlyOnePage()) {
            return $this->firstPageUrl();
        }

        return $this->url($this->next);
    }

    /**
     * Get list of all urls.
     */
    public function urlList(): Collection
    {
        return $this->range()->mapWithKeys(function (int $page) {
            return [$page => $this->url($page)];
        });
    }

    /**
     * Get range of pages.
     */
    public function range(): Collection
    {
        return new Collection(range(1, $this->numberOfPages()));
    }
}
