<?php

namespace SSD\Paginator;

class SelectPaginator extends Paginator
{
    /**
     * @var string
     */
    private $prev_text = '<i class="fa fa-angle-left"></i>';

    /**
     * @var string
     */
    private $next_text = '<i class="fa fa-angle-right"></i>';

    /**
     * Get pagination html.
     *
     * @return string
     */
    protected function html(): string
    {
        $out  = '<form class="ssd-paginator">';
        $out .= $this->previousHtml();
        $out .= $this->pageLabel();
        $out .= $this->currentHtml();
        $out .= $this->ofLabel();
        $out .= $this->nextHtml();
        $out .= '</form>';

        return $out;
    }

    /**
     * Get previous button.
     *
     * @return string
     */
    private function previousHtml(): string
    {
        if ($this->pagination->isFirstPage()) {
            return $this->inactivePreviousHtml();
        }

        $format = '<a href="%s" class="paginator-button">' . $this->prev_text . '</a>';

        return sprintf(
            $format,
            $this->pagination->previousUrl()
        );
    }

    /**
     * Get previous disabled button.
     *
     * @return string
     */
    private function inactivePreviousHtml(): string
    {
        return '<span class="paginator-button disabled">' . $this->prev_text . '</span>';
    }

    /**
     * Get 'Page' label.
     *
     * @return string
     */
    private function pageLabel(): string
    {
        return '<span class="paginator-label">Page</span>';
    }

    /**
     * Get 'of ?' label.
     *
     * @return string
     */
    private function ofLabel(): string
    {
        return '<span class="paginator-label">of ' . $this->pagination->numberOfPages() . '</span>';
    }

    /**
     * Get current html string.
     *
     * @return string
     */
    private function currentHtml(): string
    {
        $out  = '<select>';
        $out .= $this->options();
        $out .= '</select>';

        return $out;
    }

    /**
     * Get all select options.
     *
     * @return string
     */
    private function options(): string
    {
        $options = [];

        foreach(range(1, $this->pagination->numberOfPages()) as $page) {
            $options[] = $this->option($page);
        }

        return implode($options);
    }

    /**
     * Get a single option.
     *
     * @param  int $page
     * @return string
     */
    private function option(int $page): string
    {
        $option  = '<option value="';
        $option .= $this->optionValue($page);
        $option .= '"';
        $option .= $this->selected($page);
        $option .= '>';
        $option .= $page;
        $option .= '</option>';

        return $option;
    }

    /**
     * Get option value.
     *
     * @param  int $page
     * @return string
     */
    private function optionValue(int $page): string
    {
        return $this->pagination->url($page);
    }

    /**
     * Get selected attribute.
     *
     * @param  int $page
     * @return string
     */
    private function selected(int $page): string
    {
        if ( ! $this->pagination->isCurrentPage($page)) {
            return '';
        }

        return ' selected="selected"';
    }

    /**
     * Get next button.
     *
     * @return string
     */
    private function nextHtml(): string
    {
        if ($this->pagination->isLastPage()) {
            return $this->inactiveNextHtml();
        }

        $format = '<a href="%s" class="paginator-button">' . $this->next_text . '</a>';

        return sprintf(
            $format,
            $this->pagination->nextUrl()
        );
    }

    /**
     * Get next disabled button.
     *
     * @return string
     */
    private function inactiveNextHtml(): string
    {
        return '<span class="paginator-button disabled">' . $this->next_text . '</span>';
    }
}