<?php

namespace App\Http\Controllers;

use App\UserSet;
use App\UserPart;
use App\StorageLocation;
use App\Filters\SetFilters;
use App\Filters\PartFilters;
use App\Gateways\RebrickableApiUser;
use App\Filters\StorageLocationPartsFilters;

class ApiUserController extends Controller
{
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
        $sets = $filters->apply(UserSet::all());

        $sets = $sets->values();

        return $sets->paginate($this->defaultPerPage);
    }

    /**
     * get all user parts
     *
     * @param PartFilters $filters
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getParts(PartFilters $filters)
    {
        $parts = $filters->apply(UserPart::all())->values();

        return $parts->paginate($this->defaultPerPage);

        // return $this->processPartPages($parts, 'part', ['name', 'category_label']);
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

        // return $this->processPartPages($parts, 'part', ['name', 'image_url', 'category_label']);
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

        // return $this->processPartPages($parts, 'part', ['name', 'image_url', 'category_label', 'location']);
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

    /**
     * processPartPages
     *
     * @param mixed $parts
     * @param null|array|string $loads
     * @param null|array|string $appends
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    protected function processPartPages($parts, $loads = null, $appends = null)
    {
        $page = $parts->paginate($this->defaultPerPage);

        if (! is_null($loads) && ! is_array($loads)) {
            $page->load($loads);
        } elseif (! is_null($loads)) {
            foreach ($loads as $load) {
                $page->load($load);
            }
        }

        if (! is_null($appends)) {
            foreach ($page->items() as $key => $part) {
                if (is_array($appends)) {
                    $page->items()[$key]->append($appends);
                    continue;
                }

                foreach ($appends as $append) {
                    $page->items()[$key]->append($append);
                }
            }
        }

        return $page;
    }
}
