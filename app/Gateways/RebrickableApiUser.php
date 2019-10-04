<?php

namespace App\Gateways;

class RebrickableApiUser extends ApiCore
{
    /**
     * base url for all api calls
     *
     * @var string
     */
    protected $baseUrl = 'https://rebrickable.com/api/v3/users/';

    /**
     * Total number of User Parts
     *
     * @var int
     */
    protected $userPartCount = 0;

    /**
     * Get details about a specific user.
     *
     * @return Illuminate\Support\Collection
     */
    public function getProfile()
    {
        $results = $this->getType('profile');

        if ($results === false) {
            $results = $this->getErrors();
        }

        return collect($results);
    }

    public function getPartsFirstPage($page_size = null)
    {
        $parts = $this->getType('allparts', 1, $page_size);

        if ($parts === false) {
            return $this->getErrors();
        }

        $this->userPartCount = $this->jsonResponse['count'];

        return collect($parts);
    }

    public function getPartCount()
    {
        return $this->userPartCount;
    }

    public function getPartsByPage($page, $page_size = null)
    {
        $parts = $this->getType('allparts', $page, $page_size);

        if ($parts === false) {
            return $this->getErrors();
        }

        return collect($parts);
    }

    /**
     * special getter for all parts since the amount of data needs to be throttled
     *
     * @param int $totalPages
     * @return Illuminate\Support\Collection
     */
    public function getAllParts($totalPages = null)
    {
        $firstPage = $this->getType('allparts', 1, $this->max);

        if ($firstPage === false) {
            return $this->getErrors();
        }

        $totalPages = (is_null($totalPages)) ? (int) ceil($this->jsonResponse['count'] / $this->max) : $totalPages;

        $this->removeUrlParam('page');

        $url = $this->getRequestUrl().'&page=';

        $all = $this->executePool($totalPages, $url, $firstPage);

        return collect($all);
    }

    /**
     * gets all of an allowed type
     *
     * @param string $type
     * @param int $totalPages
     * @return Illuminate\Support\Collection
     */
    public function getAll($type, $totalPages = null)
    {
        $allowedTypes = ['setlists', 'sets'];

        abort_if(! in_array($type, $allowedTypes), 400, 'Request Type is not allowed!');

        $all = $this->getType($type, 1, $this->max, '');

        if ($all === false) {
            return $this->getErrors();
        }

        $totalPages = (is_null($totalPages)) ? (int) ceil($this->jsonResponse['count'] / $this->max) : $totalPages;

        for ($i = 2; $i <= $totalPages; $i++) {
            $page = $this->getType($type, $i, $this->max, '');

            if ($page === false) {
                return $this->getErrors();
            }

            $all = array_merge($all, $page);
        }

        return collect($all);
    }

    /**
     * Adds a set to the default Rebrickable set list
     *
     * @param string $setNum
     * @param int $qty
     * @param bool $incSpares
     * @return int
     */
    public function addSet($setNum, $qty = 1, bool $incSpares = true)
    {
        $setNum = $this->normalizeSetNumber($setNum);

        if ($qty < 1) {
            $qty = 1;
        }

        $this->appendUrl('sets');
        $this->addPostParam('set_num', $setNum);
        $this->addPostParam('quantity', $qty);
        $this->addPostParam('include_spares', $incSpares);

        $this->executePost();

        $results = $this->getResults();

        if ($results === false) {
            return $this->getErrors();
        }

        return $this->getStatus();
    }

    /**
     * Deletes a set
     *
     * @param string $setNum
     * @return int
     */
    public function deleteSet($setNum)
    {
        $setNum = $this->normalizeSetNumber($setNum);

        $this->appendUrl("sets/$setNum");
        $this->appendUrlParam('set_num', $setNum);

        $this->executeDelete();

        $results = $this->getResults();

        if ($results === false) {
            return $this->getErrors();
        }

        return $this->getStatus();
    }

    /**
     * Get details about building a given set
     *
     * @param mixed $setNum
     * @return Illuminate\Support\Collection
     */
    public function buildSet($setNum)
    {
        $setNum = $this->normalizeSetNumber($setNum);

        $this->appendUrl("build/$setNum");

        $this->executeGet();

        $results = $this->getResults();

        if ($results === false) {
            return $this->getErrors();
        }

        return collect($results);
    }

    /**
     * Get details about a given set from a given set list
     *
     * @param int $listId
     * @param string $setNum
     * @return Illuminate\Support\Collection
     */
    public function getSetListSet($listId, $setNum)
    {
        $setNum = $this->normalizeSetNumber($setNum);

        $this->appendUrl("setlists/$listId/sets/$setNum");

        $this->executeGet();

        $results = $this->getResults();

        if ($results === false) {
            return $this->getErrors();
        }

        return collect($results);
    }
}
