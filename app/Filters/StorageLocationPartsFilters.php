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
    protected $filters = ['sort', 'sortdesc', 'name', 'part_num', 'part_category_id', 'category_label', 'color'];
}
