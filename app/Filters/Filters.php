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
     * Create a new Filters instance.
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
        if (! $collection instanceof \Illuminate\Support\Collection) {
            return $collection;
        }

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
        return $this->collection = $this->collection->sortBy($field, SORT_NATURAL);
    }

    /**
     * Sort the collection by a given field in descending order.
     *
     * @param  string $field
     * @return \Illuminate\Support\Collection
     */
    protected function sortdesc($field)
    {
        return $this->collection = $this->collection->sortBy($field, SORT_NATURAL, true);
    }

    /**
     * filters a given collection by a given key and value
     *
     * @param \Illuminate\Support\Collection $collection
     * @param string $key
     * @param mixed $value
     * @return \Illuminate\Support\Collection
     */
    protected function filterCollection($collection, $key, $value)
    {
        return $collection->filter(
            function ($item) use ($key, $value) {
                $value = trim($value);
                return false !== stristr($item[$key], $value);
            }
        );
    }

    /**
     * checks a value sting for the presence of a ',' and then returns array from each split value
     *
     * @param string $value
     * @return array
     */
    protected function getMultipleFilters($value)
    {
        if (strpos($value, ',') === false) {
            return [$value];
        }

        return explode(',', $value);
    }

    /**
     * processMultipleFilters
     *
     * @param \Illuminate\Support\Collection $collection
     * @param string $key
     * @param array $value
     * @return \Illuminate\Support\Collection
     */
    protected function processMultipleFilters($collection, $key, $values)
    {
        foreach ($values as $value) {
            $collection = $this->filterCollection($collection, $key, $value);
        }

        return $collection;
    }

    /**
     * Search the collection for a given name value.
     *
     * @param  string $value
     * @return \Illuminate\Support\Collection
     */
    protected function name($value)
    {
        return $this->collection =
            $this->processMultipleFilters(
                $this->collection,
                'name',
                $this->getMultipleFilters($value)
            );
    }
}
