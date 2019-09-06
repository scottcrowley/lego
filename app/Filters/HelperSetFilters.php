<?php

namespace App\Filters;

trait HelperSetFilters
{
    /**
     * Search the collection for a given theme id.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function theme_id($value)
    {
        return $this->collection = $this->collection->where('theme_id', $value);
    }

    /**
     * Search the collection for a given set num.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function set_num($value)
    {
        return $this->collection = $this->collection->where('set_num', $value);
    }

    /**
     * Search the collection for a given theme label.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function theme_label($value)
    {
        return $this->collection =
            $this->processMultipleFilters(
                $this->collection,
                'theme_label',
                $this->getMultipleFilters($value)
            );
    }

    /**
     * Search the collection for results equal or greater parts than the given amount.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function minpieces($value)
    {
        return $this->collection = $this->collection->where('num_parts', '>=', $value);
    }

    /**
     * Search the collection for results equal or fewer parts than the given amount.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function maxpieces($value)
    {
        return $this->collection = $this->collection->where('num_parts', '<=', $value);
    }
}
