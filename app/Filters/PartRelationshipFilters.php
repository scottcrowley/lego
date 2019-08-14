<?php

namespace App\Filters;

class PartRelationshipFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'parent_part_num', 'child_part_num'];

    /**
     * Search the collection for a given parent partNum value.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function parent_part_num($value)
    {
        return $this->collection = $this->collection->where('parent_part_num', $value);
    }

    /**
     * Search the collection for a given child partNum value.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function child_part_num($value)
    {
        return $this->collection = $this->collection->where('child_part_num', $value);
    }
}
