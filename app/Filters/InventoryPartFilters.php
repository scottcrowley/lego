<?php

namespace App\Filters;

class InventoryPartFilters extends Filters
{
    use HelperPartFilters;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'name', 'part_num', 'part_category_id', 'color_id', 'color'];

    /**
     * Search the collection for a given color_id.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function color_id($value)
    {
        return $this->collection = $this->collection->where('color_id', $value);
    }

    /**
     * Search the collection for a given color name.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function color($value)
    {
        return $this->collection = $this->collection->filter(
            function ($item) use ($value) {
                return false !== stristr($item['color_name'], $value);
            }
        );
    }
}
