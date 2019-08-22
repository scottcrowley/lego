<?php

namespace App\Filters;

class SetFilters extends Filters
{
    use HelperYearFilters, HelperSetFilters;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'name', 'set_num', 'theme_id', 'year', 'minyear', 'maxyear'];
}
