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
        return $this->collection =
            $this->processMultipleFilters(
                $this->collection,
                'part_num',
                $this->getMultipleFilters($value)
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

    /**
     * Search the collection for a given category label.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function category_label($value)
    {
        return $this->collection =
            $this->processMultipleFilters(
                $this->collection,
                'category_label',
                $this->getMultipleFilters($value)
            );
    }

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
        return $this->collection =
            $this->processMultipleFilters(
                $this->collection,
                'color_name',
                $this->getMultipleFilters($value)
            );
    }
}
