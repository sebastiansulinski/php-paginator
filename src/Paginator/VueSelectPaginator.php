<?php

namespace SSD\Paginator;

class VueSelectPaginator extends Paginator
{
    /**
     * Get pagination html.
     *
     * @return string
     */
    protected function html(): string
    {
        return '<ssd-paginator '.$this->attributes().'></ssd-paginator>';
    }

    /**
     * Get component's attributes.
     *
     * @return string
     */
    private function attributes(): string
    {
        return (new Collection([

            ':options' => htmlentities($this->pagination->urlList()),
            'current' => $this->pagination->currentUrl(),
            'previous' => $this->pagination->previousUrl(),
            'next' => $this->pagination->nextUrl(),
            'first' => $this->pagination->firstPageUrl(),
            'last' => $this->pagination->lastPageUrl(),
            ':number-of-pages' => $this->pagination->numberOfPages(),

        ]))->map(function($value, string $key) {

            return $key.'="'.$value.'"';

        })->implode(' ');
    }
}