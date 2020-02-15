<?php

namespace App\Filters;

class StorageLocationPartsFilters extends Filters
{
    use HelperPartFilters;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'name', 'part_num', 'part_category_id', 'category_label', 'color', 'exclude_assigned', 'only_unassigned'];

    /**
     * exlude all results that have an existing storage location except the given location_id
     *
     * @param mixed $value
     * @return \Illuminate\Support\Collection
     */
    protected function exclude_assigned($value)
    {
        if ($value >= 0) {
            return $this->collection = $this->collection->filter(function ($item) use ($value) {
                $value = trim($value);
                return (
                    is_null($item->location) ||
                    (! is_null($item->location) && $value != 0 && $item->location->id == $value)
                );
            });
        }
    }

    /**
     * exlude all results that have an existing storage location
     *
     * @return \Illuminate\Support\Collection
     */
    protected function only_unassigned()
    {
        return $this->exclude_assigned(0);
    }
}
