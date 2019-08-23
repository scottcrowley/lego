<?php

namespace App\Filters;

trait HelperPartFilters
{
    /**
     * Search the collection for a given part num.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function part_num($value)
    {
        return $this->collection = $this->collection->filter(
            function ($item) use ($value) {
                return false !== stristr($item['part_num'], $value);
            }
        );
    }

    /**
     * Search the collection for a given part category.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function part_category_id($value)
    {
        return $this->collection = $this->collection->where('part_category_id', $value);
    }
}