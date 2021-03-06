<?php

namespace App\Filters;

class ThemeFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'name', 'parent_id'];

    /**
     * Search the collection for a given parent theme id.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function parent_id($value)
    {
        return $this->collection = $this->collection->where('parent_id', $value);
    }
}
