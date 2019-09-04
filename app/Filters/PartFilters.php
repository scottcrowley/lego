<?php

namespace App\Filters;

class PartFilters extends Filters
{
    use HelperPartFilters;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'name', 'part_num', 'part_category_id', 'category_label'];
}
