<?php

namespace App\Http\Controllers;

use App\Gateways\RebrickableApiUser;

class RebrickableApiUserController extends Controller
{
    /**
     * Generate a User Token to be used for authorising user account actions in subsequent calls.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getToken()
    {
        $api = new RebrickableApiUser();
        return $api->generateToken();
    }

    /**
     * Get a list of all the user's Set Lists.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSetLists()
    {
        return cache()->rememberForever('setlists', function () {
            $api = new RebrickableApiUser();
            return $api->getAll('setlists');
        });
    }

    /**
     * Get a list of all the Sets in the user's LEGO collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSets()
    {
        return cache()->rememberForever('sets_user', function () {
            $api = new RebrickableApiUser();
            return $api->getAll('sets');
        });
    }

    /**
     * Get a list of all the Parts in all the user's Part Lists as well as the Parts inside Sets in the user's Set Lists.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllParts()
    {
        return cache()->rememberForever('allparts', function () {
            $api = new RebrickableApiUser();
            return $api->getAll('allparts');
        });
    }

    /**
     * Get details about a specific user.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProfile()
    {
        $api = new RebrickableApiUser();
        return $api->getProfile();
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

        return redirect(route('api.users.'.$type));
    }
}
