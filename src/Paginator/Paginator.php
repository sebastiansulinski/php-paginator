<?php

namespace SSD\Paginator;

use Illuminate\Support\Collection;

/**
 * Class View
 *
 * @package SSD\Paginator
 */
abstract class Paginator
{
    /**
     * @var \SSD\Paginator\Pagination
     */
    protected $pagination;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $records;

    /**
     * PaginationView constructor.
     *
     * @param  \SSD\Paginator\Pagination $pagination
     * @param  \Illuminate\Support\Collection $records
     */
    public function __construct(Pagination $pagination, Collection $records)
    {
        $this->pagination = $pagination;
        $this->records = $records;
    }

    /**
     * Get pagination html.
     *
     * @return string
     */
    public function render(): string
    {
        if ($this->pagination->hasOnlyOnePage()) {
            return '';
        }

        return $this->html();
    }

    /**
     * Get pagination html.
     *
     * @return string
     */
    abstract protected function html(): string;

    /**
     * Get pagination instance.
     *
     * @return \SSD\Paginator\Pagination
     */
    public function pagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * Get records.
     *
     * @return \Illuminate\Support\Collection
     */
    public function records(): Collection
    {
        return $this->records;
    }

    /**
     * Check if there are any records.
     *
     * @return bool
     */
    public function hasRecords(): bool
    {
        return !$this->records->isEmpty();
    }

    /**
     * Get total number of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->pagination->numberOfRecords();
    }
}