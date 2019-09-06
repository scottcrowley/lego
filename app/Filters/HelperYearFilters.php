<?php

namespace App\Filters;

trait HelperYearFilters
{
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
     * Search the collection for results equal or after a given year.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function minyear($value)
    {
        return $this->collection = $this->collection->where('year', '>=', $value);
    }

    /**
     * Search the collection for results equal or before a given year.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function maxyear($value)
    {
        return $this->collection = $this->collection->where('year', '<=', $value);
    }
}
