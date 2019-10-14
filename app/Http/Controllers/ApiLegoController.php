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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use App\Filters\PartRelationshipFilters;

class ApiLegoController extends Controller
{
    use ApiControllerFunctions;

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
        $colors = $filters->apply(Color::all())->values();

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
        $relationships = $filters->apply(PartRelationship::all())->values();

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
        $themes = $filters->apply(Theme::all())->values();

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
        $parts = $filters->apply(Part::all())->values();

        return $this->processPartPages($parts, ['partImageUrl', 'userParts'], ['owns_part', 'owned_part_count', 'owned_part_location_name']);
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

        $inventories = $inventories->each->setAppends([
            'image_url',
            'year',
            'num_parts',
            'name',
            'theme_id',
            'theme_label'
        ])->values();

        $page = $inventories->paginate($this->defaultPerPage);

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
                ->with('userParts')
                ->with('stickeredParts')
                ->get()
        );

        $parts = $parts->each(function ($part, $key) {
            $part->dimmed = false;
            $part->append('location_name');
            $part->append('quantity_label');
            $part->append('stickered_parts_count');
        })->values();

        return $parts->paginate($this->defaultPerPage);
    }

    /**
     * gets all cached inventory parts that are marked as selected for a given inventory_id
     *
     * @return array
     */
    public function getPartSelection($inventory)
    {
        return Cache::get($inventory, []);
    }

    /**
     * updates cache for any inventory parts selected for a given inventory_id
     *
     * @return array
     */
    public function updatePartSelection()
    {
        $request = request()->all();
        $key = $request['part_num'].'-'.$request['color_id'].'-'.$request['is_spare'];
        $cachedInventory = Cache::get($request['inventory_id'], []);
        $inCache = isset($cachedInventory[$key]);

        if (! $inCache && $request['selected']) {
            $cachedInventory[$key] = true;
        } elseif ($inCache && ! $request['selected']) {
            unset($cachedInventory[$key]);
        }

        Cache::put($request['inventory_id'], $cachedInventory, now()->addDays(2));

        return $cachedInventory;
    }
}
