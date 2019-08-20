<?php

namespace App\Http\Controllers;

use App\UserSet;
use App\UserPart;
use App\Filters\SetFilters;
use App\Filters\PartFilters;
use App\Gateways\RebrickableApiUser;

class ApiUserController extends Controller
{
    /**
     * default number of results to display
     *
     * @var integer
     */
    protected $defaultPerPage = 30;

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
     * Get details about a specific user.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProfile()
    {
        $api = new RebrickableApiUser();
        return $api->getProfile();
    }

    public function getSets(SetFilters $filters)
    {
        $sets = $filters->apply(UserSet::all());

        $sets = $sets->values();

        return $sets->paginate($this->defaultPerPage);
    }

    public function getParts(PartFilters $filters)
    {
        $parts = $filters->apply(UserPart::all());

        $parts = $parts->values();

        return $parts->paginate($this->defaultPerPage);
    }
}
