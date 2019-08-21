<?php

namespace App\Gateways;

class RebrickableApiLego extends ApiCore
{
    /**
     * base url for all api calls
     *
     * @var string
     */
    protected $baseUrl = 'https://rebrickable.com/api/v3/lego/';

    /**
     * special getter for all sets since the amount of data needs to be throttled
     *
     * @param int $totalPages
     * @return Illuminate\Support\Collection
     */
    public function getAllSets($totalPages = null)
    {
        $firstPage = $this->getType('sets', 1, $this->max, '');

        if ($firstPage === false) {
            return $this->getErrors();
        }

        $totalPages = (is_null($totalPages)) ? (int) ceil($this->jsonResponse['count'] / $this->max) : $totalPages;

        $baseUrl = $this->baseUrl."sets/?ordering=name&page_size=$this->max&page=";

        $all = $this->executePool($totalPages, $baseUrl, $firstPage);

        $unique = collect($all)->uniqueStrict(function ($item) {
            return $item['set_num'].$item['name'].$item['year'];
        });
        return $unique;
    }

    /**
     * special getter for all parts since the amount of data needs to be throttled
     *
     * @param int $totalPages
     * @return Illuminate\Support\Collection
     */
    public function getAllParts($totalPages = null)
    {
        $firstPage = $this->getType('parts', 1, $this->max, 'name');

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
        $allowedTypes = ['colors', 'themes', 'part_categories', 'sets'];

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

    public function getSetInventory($setNum)
    {
        $setNum = $this->normalizeSetNumber($setNum);

        $url = "sets/$setNum/parts/";

        $firstPage = $this->getType($url);

        if ($firstPage === false) {
            return $this->getErrors();
        }

        $totalPages = (int) ceil($this->jsonResponse['count'] / $this->max);

        if ($totalPages == 1) {
            $all = $firstPage;
        } else {
            $baseUrl = $this->baseUrl.$url."?page_size=$this->max&page=";

            $all = $this->executePool($totalPages, $baseUrl, $firstPage);
        }

        return collect($all);
    }
}
