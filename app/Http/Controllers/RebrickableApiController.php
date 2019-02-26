<?php

namespace App\Http\Controllers;

use App\Filters\ColorFilters;
use App\Filters\ThemeFilters;
use App\Gateways\RebrickableApi;

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
}
