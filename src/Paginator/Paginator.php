<?php

namespace SSD\Paginator;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * Class View
 */
abstract class Paginator implements Arrayable
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
     */
    public function __construct(Pagination $pagination, Collection $records)
    {
        $this->pagination = $pagination;
        $this->records = $records;
    }

    /**
     * Get pagination html.
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
     */
    abstract protected function html(): string;

    /**
     * Get pagination instance.
     */
    public function pagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * Get records.
     */
    public function records(): Collection
    {
        return $this->records;
    }

    /**
     * Check if there are any records.
     */
    public function hasRecords(): bool
    {
        return ! $this->records->isEmpty();
    }

    /**
     * Get total number of records.
     */
    public function count(): int
    {
        return $this->pagination->numberOfRecords();
    }

    /**
     * Get object as array.
     */
    public function toArray(): array
    {
        return [
            'range' => $this->pagination->range(),
            'current' => $this->pagination->current(),
            'previous' => $this->pagination->previous(),
            'next' => $this->pagination->next(),
            'number_of_records' => $this->count(),
            'number_of_pages' => $this->pagination->numberOfPages(),
            'per_page' => $this->pagination->perPage(),
        ];
    }
}
