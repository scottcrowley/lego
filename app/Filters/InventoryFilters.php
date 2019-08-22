<?php

namespace App\Filters;

class InventoryFilters extends Filters
{
    use HelperYearFilters, HelperSetFilters;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'set_num', 'theme_id', 'year', 'minyear', 'maxyear'];
}
