<?php

namespace App\Filters;

class InventoryFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort', 'sortdesc', 'set_num'];
}
