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
    protected $filters = ['sort', 'sortdesc', 'name', 'part_num', 'part_category_id', 'category_label', 'color', 'exclude_assigned'];

    protected function exclude_assigned($value)
    {
        if ($value) {
            return $this->collection = $this->collection->filter(function ($item) use ($value) {
                $value = trim($value);
                return (
                    is_null($item->location) ||
                    (! is_null($item->location) && $item->location->id == $value)
                );
            });
        }
    }
}
