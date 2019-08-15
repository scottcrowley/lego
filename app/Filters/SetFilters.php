<?php

namespace App\Filters;

class SetFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'name', 'set_num', 'theme_id', 'year', 'minyear', 'maxyear'];

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

    /**
     * Search the collection for a given exact year.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function year($value)
    {
        return $this->collection = $this->collection->where('year', $value);
    }

    /**
     * Search the collection for a results before or equal to a given year.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function minyear($value)
    {
        return $this->collection = $this->collection->where('year', '<=', $value);
    }

    /**
     * Search the collection for a results before or equal to a given year.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function maxyear($value)
    {
        return $this->collection = $this->collection->where('year', '>=', $value);
    }
}
