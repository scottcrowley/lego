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
    protected $filters = ['sort', 'name', 'part_num', 'part_category_id', 'category_label', 'color_id', 'color', 'location_name', 'exclude_spare'];

    /**
     * Search the collection for a given storage location name.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function location_name($value)
    {
        return $this->collection =
            $this->processMultipleFilters(
                $this->collection,
                'location_name',
                $this->getMultipleFilters($value)
            );
    }

    /**
     * exlude all spare parts
     *
     * @return \Illuminate\Support\Collection
     */
    protected function exclude_spare()
    {
        return $this->collection = $this->collection->where('is_spare', 'f');
    }
}
