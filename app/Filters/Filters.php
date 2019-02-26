<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create a new ThreadFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters.
     *
     * @param  \Illuminate\Support\Collection $collection
     * @return \Illuminate\Support\Collection
     */
    public function apply($collection)
    {
        $this->collection = $collection;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->collection;
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }

    /**
     * Sort the collection by a given field.
     *
     * @param  string $field
     * @return \Illuminate\Support\Collection
     */
    protected function sort($field)
    {
        return $this->collection = $this->collection->sortBy($field);
    }

    /**
     * Sort the collection by a given field in descending order.
     *
     * @param  string $field
     * @return \Illuminate\Support\Collection
     */
    protected function sortdesc($field)
    {
        return $this->collection = $this->collection->sortByDesc($field);
    }

    /**
     * Search the collection for a given name value.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function name($value)
    {
        return $this->collection = $this->collection->filter(
            function ($item) use ($value) {
                return false !== stristr($item['name'], $value);
            }
        );
    }
}
