<?php

namespace App\Http\Controllers;

use App\Set;
use App\Part;
use App\Color;
use App\Theme;
use App\PartCategory;
use App\PartRelationship;
use App\Filters\SetFilters;
use App\Filters\PartFilters;
use App\Filters\ColorFilters;
use App\Filters\ThemeFilters;
use App\Filters\PartCategoryFilters;
use App\Filters\PartRelationshipFilters;

class ApiLegoController extends Controller
{
    /**
     * default number of results to display
     *
     * @var integer
     */
    protected $defaultPerPage = 30;

    /**
     * Create a new RebrickableApiLegoController instance.
     */
    public function __construct()
    {
        if (request()->has('perpage')) {
            $this->defaultPerPage = request('perpage');
        }
    }

    /**
     * get all colors
     *
     * @param ColorFilters $filters
     * @return Illuminate\Pagination\Paginator
     */
    public function getColors(ColorFilters $filters)
    {
        $colors = $filters->apply(Color::all());

        return $colors->paginate($this->defaultPerPage);
    }

    /**
     * get all part categories
     *
     * @param PartCategoryFilters $filters
     * @return Illuminate\Pagination\Paginator
     */
    public function getPartCategories(PartCategoryFilters $filters)
    {
        $categories = $filters->apply(PartCategory::all());

        return $categories->paginate($this->defaultPerPage);
    }

    /**
     * get all part relationships
     *
     * @param PartRelationshipFilters $filters
     * @return Illuminate\Pagination\Paginator
     */
    public function getPartRelationships(PartRelationshipFilters $filters)
    {
        $relationships = $filters->apply(PartRelationship::all());

        return $relationships->paginate($this->defaultPerPage);
    }

    /**
     * get all part relationships
     *
     * @param ThemeFilter $filters
     * @return Illuminate\Pagination\Paginator
     */
    public function getThemes(ThemeFilters $filters)
    {
        $themes = $filters->apply(Theme::all());

        return $themes->paginate($this->defaultPerPage);
    }

    /**
     * get all parts
     *
     * @param PartFilters $filters
     * @return Illuminate\Pagination\Paginator
     */
    public function getParts(PartFilters $filters)
    {
        $parts = $filters->apply(Part::all());

        return $parts->paginate($this->defaultPerPage);
    }

    /**
     * get all sets
     *
     * @param SetFilters $filters
     * @return Illuminate\Pagination\Paginator
     */
    public function getSets(SetFilters $filters)
    {
        $sets = $filters->apply(Set::all());

        return $sets->paginate($this->defaultPerPage);
    }
}
