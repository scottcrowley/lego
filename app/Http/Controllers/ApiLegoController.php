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
    use ApiHelpers;

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
        $themes = $filters->apply(Theme::all());

        $themes = $themes->values();

        $page = ($themes->paginate($this->defaultPerPage))->toArray();

        if (count($page['data'])) {
            $allThemes = Theme::all();

            foreach ($page['data'] as $k => $theme) {
                $theme = $this->themeParentHierarchy($theme, $allThemes);
                $page['data'][$k] = $theme;
            }
        }

        return $page;
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

        return $parts->paginate($this->defaultPerPage);
    }

    /**
     * get all sets
     *
     * @param SetFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSets(SetFilters $filters)
    {
        $sets = $filters->apply(Set::all());

        $sets = $sets->values();

        $page = ($sets->paginate($this->defaultPerPage))->toArray();

        if (count($page['data'])) {
            $themes = Theme::all();

            foreach ($page['data'] as $k => $set) {
                if (is_null($set['theme_id'])) {
                    continue;
                }

                $setTheme = $themes->where('id', $set['theme_id'])->first();

                $theme = ($this->themeParentHierarchy($setTheme->toArray(), $themes))->toArray();

                $set['theme_details'] = $theme;

                $set['theme_label'] = (is_null($theme['parent_id'])) ? $theme['name'] : $theme['parents_label'].' -> '.$theme['name'];

                $page['data'][$k] = $set;
            }
        }

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
        $inventories = $filters->apply(Inventory::all());

        $inventories = $inventories->values();

        return $inventories->paginate($this->defaultPerPage);
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
        $parts = $filters->apply(InventoryPart::whereInventoryId($inventory->id)->get());

        $parts = $parts->values();

        return $parts->paginate($this->defaultPerPage);
    }
}
