<?php

namespace App\Filters;

class ThemeFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'name', 'parent_id'];

    /**
     * Search the collection for a given parent theme id.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function parent_id($value)
    {
        return $this->collection = $this->collection->filter(
            function ($item) use ($value) {
                return false !== stristr($item['parent_id'], $value);
            }
        );
    }
}
