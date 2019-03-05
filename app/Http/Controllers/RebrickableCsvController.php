<?php

namespace App\Http\Controllers;

use App\Gateways\RebrickableCsv;

class RebrickableCsvController extends Controller
{
    /**
     * gets all results from csv file on the Rebrickable downloads page for a given type
     *
     * @param string $type
     * @return \Illuminate\Support\Collection
     */
    public function getType($type)
    {
        $allowedTypes = ['sets', 'themes', 'parts', 'part_categories', 'colors', 'inventories', 'inventory_parts', 'inventory_sets', 'part_relationships'];

        abort_if(! in_array($type, $allowedTypes), 400, 'Request Type is not allowed!');

        $results = cache()->
            rememberForever($type, function () use ($type) {
                $csv = new RebrickableCsv();
                return $csv->getAllType($type);
            });

        return $results;
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

        return redirect(url('csv/'.$type));
    }
}
