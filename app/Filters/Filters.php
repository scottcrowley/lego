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
     * Sort the collection.
     * Sort method-hattip to Hirnhamster https://stackoverflow.com/questions/25451019/what-is-the-syntax-for-sorting-an-eloquent-collection-by-multiple-columns/
     *
     * @return \Illuminate\Support\Collection
     */
    protected function sort()
    {
        $sort = $this->prepareSortCriteria();

        $makeComparer = function ($criteria) {
            $comparer = function ($first, $second) use ($criteria) {
                foreach ($criteria as $field => $sortOrder) {
                    $sortOrder = strtolower($sortOrder);

                    // use NaturalSortCompare
                    // $natsort = NaturalSortCompare::natcompare($first[$field], $second[$field]);
                    // if ($natsort == -1) {
                    //     return $sortOrder === 'asc' ? -1 : 1;
                    // } elseif ($natsort == 1) {
                    //     return $sortOrder === 'asc' ? 1 : -1;
                    // }

                    // use non natural sort
                    if ($first[$field] < $second[$field]) {
                        return $sortOrder === 'asc' ? -1 : 1;
                    } elseif ($first[$field] > $second[$field]) {
                        return $sortOrder === 'asc' ? 1 : -1;
                    }
                }
                return 0;
            };
            return $comparer;
        };

        $comparer = $makeComparer($sort);
        return $this->collection = $this->collection->sort($comparer);
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
     * prepares the sort array based on fields provided in the sort & sortdesc url params
     *
     * @return array
     */
    protected function prepareSortCriteria()
    {
        $sort = $this->request->has('sort') ? explode(',', $this->request->sort) : [];

        $criteria = [];

        foreach ($sort as $field) {
            if (substr($field, 0, 1) == '-') {
                $criteria[substr($field, 1)] = 'desc';
                continue;
            }

            $criteria[$field] = 'asc';
        }

        return $criteria;
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
