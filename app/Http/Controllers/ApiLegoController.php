<?php

namespace App\Http\Controllers;

use App\Set;
use App\Part;
use App\Color;
use App\Theme;
use App\Inventory;
use App\PartCategory;
use App\InventoryPart;
use App\PartRelationship;
use App\Filters\SetFilters;
use App\Filters\PartFilters;
use App\Filters\ColorFilters;
use App\Filters\ThemeFilters;
use App\Filters\InventoryFilters;
use App\Filters\PartCategoryFilters;
use App\Filters\InventoryPartFilters;
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
     * Create a new ApiLegoController instance.
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
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getColors(ColorFilters $filters)
    {
        $colors = $filters->apply(Color::all());

        $colors = $colors->values();

        return $colors->paginate($this->defaultPerPage);
    }

    /**
     * get all part categories
     *
     * @param PartCategoryFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPartCategories(PartCategoryFilters $filters)
    {
        $categories = $filters->apply(PartCategory::with('categoryPartCount')->get());

        $categories = $categories->each->append('part_count')->values();

        return $categories->paginate($this->defaultPerPage);
    }

    /**
     * get all part relationships
     *
     * @param PartRelationshipFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
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
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getThemes(ThemeFilters $filters)
    {
        $allThemes = Theme::all();
        $themes = $filters->apply($allThemes);

        $themes = $themes->values();

        return $themes->paginate($this->defaultPerPage);
    }

    /**
     * get all parts
     *
     * @param PartFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getParts(PartFilters $filters)
    {
        $parts = $filters->apply(Part::all());

        $parts = $parts->values();

        $page = $parts->paginate($this->defaultPerPage);

        $page->load('partImageUrl');

        return $page;
    }

    /**
     * get all sets
     *
     * @param SetFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSets(SetFilters $filters)
    {
        $sets = $filters->apply(Set::with('theme')->get());

        $sets = $sets->each->append('theme_label')->values();

        $page = $sets->paginate($this->defaultPerPage);

        $page->load('setImageUrl');

        return $page;
    }

    /**
     * get all inventories
     *
     * @param InventoryFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getInventories(InventoryFilters $filters)
    {
        // $inventories = $filters->apply(Inventory::all()); //doesnt allow for sorting with any of the additional fields
        $inventories = $filters->apply(Inventory::with('set')->with('theme')->get());

        $inventories->each->setAppends([
            'image_url',
            'year',
            'num_parts',
            'name',
            'theme_id',
            'theme_label'
        ])->values();

        $page = $inventories->paginate($this->defaultPerPage);

        // $page->load('set')->load('theme'); //doesnt allow for sorting with any of the additional fields

        return $page;
    }

    /**
     * get all inventories
     *
     * @param Inventory $inventory
     * @param InventoryFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getInventoryParts(Inventory $inventory, InventoryPartFilters $filters)
    {
        $parts = $filters->apply(
            InventoryPart::whereInventoryId($inventory->id)
            ->with('part.storageLocation')
            ->with('part.partImageUrl')
            ->with('color')
            ->get()
        );

        $parts->each->append('location_name');

        $parts = $parts->values();

        return $parts->paginate($this->defaultPerPage);
    }
}
