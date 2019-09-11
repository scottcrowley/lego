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
    protected $filters = ['sort', 'sortdesc', 'set_num', 'name', 'theme_id', 'theme_label', 'year', 'minyear', 'maxyear'];
}
