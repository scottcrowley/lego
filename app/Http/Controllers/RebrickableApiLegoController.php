<?php

namespace App\Http\Controllers;

use App\Filters\SetFilters;
use App\Filters\PartFilters;
use App\Filters\ColorFilters;
use App\Filters\ThemeFilters;
use App\Filters\PartCategoryFilters;
use App\Gateways\RebrickableApiLego;

class RebrickableApiLegoController extends Controller
{
    use RebrickableApiHelpers;

    /**
     * default number of results to display
     *
     * @var integer
     */
    protected $defaultPerPage = 30;

    /**
     * gets all colors
     *
     * @param ColorFilters $filters
     * @return \Illuminate\Support\Collection
     */
    public function getColors(ColorFilters $filters)
    {
        $colors = $filters->apply(
            cache()->rememberForever('colors', function () {
                $api = new RebrickableApiLego();
                return $api->getAll('colors');
            })
        );

        return $colors->paginate($this->defaultPerPage);
    }

    /**
     * gets all themes
     *
     * @param ThemeFilters $filters
     * @return \Illuminate\Support\Collection
     */
    public function getThemes(ThemeFilters $filters)
    {
        $themes = $filters->apply(
            cache()->rememberForever('themes', function () {
                $api = new RebrickableApiLego();
                return $api->getAll('themes');
            })
        );

        if (session('single_request')) {
            return redirect(route('api.lego.themes.show', session('single_request')));
        }

        return $themes->paginate($this->defaultPerPage);
    }

    /**
     * retrieve details about a given theme
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function getTheme($id)
    {
        if (! cache()->has('themes')) {
            return redirect(route('api.lego.themes'))->with('single_request', $id);
        }

        $themes = cache('themes');

        $theme = $themes->where('id', $id)->first();

        if (is_null($theme)) {
            $theme = [];
        }

        $theme = collect($this->themeParentHierarchy($theme, $themes));

        if (session('set_request')) {
            session()->put('set_theme', $theme);
            return redirect(route('api.lego.sets.show', session('set_request')));
        }

        return $theme;
    }

    /**
     * gets all themes
     *
     * @param partFilters $filters
     * @return \Illuminate\Support\Collection
     */
    public function getParts(PartFilters $filters)
    {
        $parts = $filters->apply(
            cache()->rememberForever('parts', function () {
                $api = new RebrickableApiLego();
                return $api->getAllParts();
            })
        );

        if (session('single_request')) {
            return redirect(route('api.lego.parts.show', session('single_request')));
        }

        return $parts->paginate($this->defaultPerPage);
    }

    /**
     * gets all themes
     *
     * @param SetFilters $filters
     * @return \Illuminate\Support\Collection
     */
    public function getSets(SetFilters $filters)
    {
        $sets = $filters->apply(
            cache()->rememberForever('sets', function () {
                $api = new RebrickableApiLego();
                return $api->getAllSets();
            })
        );

        if (session('single_request')) {
            return redirect(route('api.lego.sets.show', session('single_request')));
        }

        return $sets->paginate($this->defaultPerPage);
    }

    /**
     * retrieve details about a given set
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function getSet($setNum)
    {
        if (substr($setNum, -2) != '-1') {
            $setNum = $setNum.'-1';
        }

        if (! cache()->has('sets')) {
            return redirect(route('api.lego.sets'))->with('single_request', $setNum);
        }

        $sets = cache('sets');

        $set = $sets->where('set_num', $setNum)->first();

        if (is_null($set)) {
            $set = ['set_num' => null, 'theme' => null];
        }

        if (! session('set_theme') && isset($set['theme_id'])) {
            session()->put('set_request', $setNum);

            return redirect(route('api.lego.themes.show', $set['theme_id']));
        } elseif (session('set_theme')) {
            $set['theme'] = session('set_theme');
            session()->put('set_theme', null);
            session()->put('set_request', null);
        }

        return collect($set);
    }

    /**
     * gets all themes
     *
     * @param PartCategoryFilters $filters
     * @return \Illuminate\Support\Collection
     */
    public function getPartCategories(PartCategoryFilters $filters)
    {
        $categories = $filters->apply(
            cache()->rememberForever('part_categories', function () {
                $api = new RebrickableApiLego();
                return $api->getAll('part_categories');
            })
        );

        return $categories->paginate($this->defaultPerPage);
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

        return redirect(route('api.lego.'.$type));
    }
}
