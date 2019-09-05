<?php

namespace App\Http\Controllers;

use App\UserSet;
use App\UserPart;
use App\StorageLocation;
use App\Filters\SetFilters;
use App\Filters\PartFilters;
use App\Filters\UserPartFilters;
use App\Gateways\RebrickableApiUser;
use App\Filters\StorageLocationPartsFilters;

class ApiUserController extends Controller
{
    use ApiControllerFunctions;

    /**
     * default number of results to display
     *
     * @var integer
     */
    protected $defaultPerPage = 30;

    /**
     * Create a new ApiUserController instance.
     */
    public function __construct()
    {
        if (request()->has('perpage')) {
            $this->defaultPerPage = request('perpage');
        }
    }

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

    /**
     * get all user sets
     *
     * @param SetFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSets(SetFilters $filters)
    {
        $sets = $filters->apply(UserSet::all())->values();

        return $sets->paginate($this->defaultPerPage);
    }

    /**
     * get all user parts
     *
     * @param PartFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllParts(UserPartFilters $filters)
    {
        $parts = $filters->apply(UserPart::all())->unique('part_num')->values();

        return $parts->paginate($this->defaultPerPage);
    }

    /**
     * get all individual user parts
     *
     * @param PartFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllIndividualParts(UserPartFilters $filters)
    {
        $parts = $filters->apply(UserPart::all())->values();

        return $this->processPartPages($parts, null, ['ldraw_image_url']);
    }

    /**
     * get all user parts associated with a storage location
     *
     * @param StorageLocation $location
     * @param StorageLocationPartsFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getStorageLocationParts(StorageLocation $location, StorageLocationPartsFilters $filters)
    {
        $parts = $filters->apply($location->parts)->unique('part_num')->values();

        return $parts->paginate($this->defaultPerPage);
    }

    /**
     * get all user parts associated with a storage location
     *
     * @param StorageLocation $location
     * @param StorageLocationPartsFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getStorageLocationIndividualParts(StorageLocation $location, StorageLocationPartsFilters $filters)
    {
        $parts = $filters->apply($location->parts)->values();

        return $this->processPartPages($parts, null, ['ldraw_image_url']);
    }

    /**
     * get all user parts for editing storage location parts
     *
     * @param StorageLocation $location
     * @param StorageLocationPartsFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function editStorageLocationParts(StorageLocation $location, StorageLocationPartsFilters $filters)
    {
        $parts = $filters->apply(UserPart::all())->unique('part_num')->values();

        return $parts->paginate($this->defaultPerPage);
    }

    /**
     * toggle the part to storage location association
     *
     * @param StorageLocation $location
     * @param UserPart $part
     * @return void
     */
    public function togglePartInLocation(StorageLocation $location, UserPart $part)
    {
        $location->togglePart($part);

        return $part->fresh('part');
    }
}
