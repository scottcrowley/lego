<?php

namespace App\Filters;

class SetFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'name', 'theme'];

    /**
     * Search the collection for a given theme id.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function theme($value)
    {
        return $this->collection = $this->collection->where('theme_id', $value);
    }
}
