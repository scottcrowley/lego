<?php

namespace App\Filters;

class UserPartFilters extends Filters
{
    use HelperPartFilters;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'name', 'part_num', 'part_category_id', 'category_label', 'location_id', 'location_name'];

    /**
    * return results for a given location
    *
    * @param mixed $value
    * @return \Illuminate\Support\Collection
    */
    protected function location_id($value)
    {
        if ($value) {
            return $this->collection = $this->collection->filter(function ($item) use ($value) {
                $value = trim($value);
                return (! is_null($item->location) && $item->location->id == $value);
            });
        }
    }

    /**
    * return results for a given location name
    *
    * @param string $value
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
}
