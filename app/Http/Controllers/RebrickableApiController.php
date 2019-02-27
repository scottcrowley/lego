<?php

namespace App\Http\Controllers;

use App\Filters\SetFilters;
use App\Filters\ColorFilters;
use App\Filters\ThemeFilters;
use App\Gateways\RebrickableApi;
use App\Filters\PartCategoryFilters;

class RebrickableApiController extends Controller
{
    /**
     * gets all colors
     *
     * @return \Illuminate\Support\Collection
     */
    public function getColors(ColorFilters $filters)
    {
        return $filters->apply(
            cache()->rememberForever('colors', function () {
                $api = new RebrickableApi();
                return $api->getAll('colors');
            })
        );
    }

    /**
     * gets all themes
     *
     * @return \Illuminate\Support\Collection
     */
    public function getThemes(ThemeFilters $filters)
    {
        return $filters->apply(
            cache()->rememberForever('themes', function () {
                $api = new RebrickableApi();
                return $api->getAll('themes');
            })
        );
    }

    /**
     * gets all themes
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPartCategories(PartCategoryFilters $filters)
    {
        return $filters->apply(
            cache()->rememberForever('part_categories', function () {
                $api = new RebrickableApi();
                return $api->getAll('part_categories');
            })
        );
    }

    /**
     * gets all themes
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSets(SetFilters $filters)
    {
        return $filters->apply(
            cache()->rememberForever('sets', function () {
                $api = new RebrickableApi();
                return $api->getAllSets();
            })
        );
    }

    /**
     * clears a given cache store
     *
     * @param string $type
     * @return void
     */
    public function clearCache($type)
    {
        cache()->forget($type);

        return redirect(route('api.'.$type));
    }
}
