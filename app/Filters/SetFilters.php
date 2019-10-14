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
    protected $filters = ['sort', 'name', 'set_num', 'theme_id', 'theme_label', 'year', 'minyear', 'maxyear', 'minpieces', 'maxpieces'];
}
