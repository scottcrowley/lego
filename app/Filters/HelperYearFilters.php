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
        if (strpos($value, '-') !== false) {
            if (substr($value, 0, 1) == '-') {
                return $this->maxyear(substr($value, 1));
            }
            if (substr($value, -1) == '-') {
                return $this->minyear(substr($value, 0, -1));
            }
            if (strlen($value) != 9) {
                return $this->collection;
            }
            return $this->collection = $this->collection->whereBetween('year', [
                substr($value, 0, 4),
                substr($value, 5)
            ]);
        }
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
