<?php

namespace App\Filters;

trait HelperSetFilters
{
    /**
     * Search the collection for a given theme id.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function theme_id($value)
    {
        return $this->collection = $this->collection->where('theme_id', $value);
    }

    /**
     * Search the collection for a given set num.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function set_num($value)
    {
        return $this->collection = $this->collection->where('set_num', $value);
    }
}
