<?php

namespace App\Http\Controllers;

use App\PartCategory;
use App\Gateways\RebrickableApiLego;

class RebrickableApiController extends Controller
{
    /**
     * default number of results to display
     *
     * @var integer
     */
    protected $defaultPerPage = 30;

    public function getPartsByCategory(PartCategory $category)
    {
        $api = new RebrickableApiLego;

        $api->setUrlParam('part_cat_id', $category->id);
        // $api->setUrlParam('inc_part_details', 1);

        $parts = $api->getAllParts();

        return $parts->paginate($this->defaultPerPage);
    }
}
