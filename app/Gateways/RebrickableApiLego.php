<?php

namespace App\Gateways;

class RebrickableApiLego
{
    use ApiCore;

    /**
     * base url for all api calls
     *
     * @var string
     */
    protected $baseUrl = 'https://rebrickable.com/api/v3/lego/';

    /**
     * special getter for all sets since the amount of data needs to be throttled
     *
     * @return Illuminate\Support\Collection
     */
    public function getAllSets()
    {
        $firstPage = $this->getType('sets', 1, 1000, '');

        if ($firstPage === false) {
            return $this->getErrors();
        }

        $response = $this->parseResponse();

        $totalPages = ceil($response['count'] / 1000);

        $baseUrl = $this->baseUrl.'sets/?ordering=name&page_size=1000&page=';

        $client = $this->generateGuzzleClient();
        $requests = $this->generateGuzzleRequests($totalPages, $baseUrl);
        $all = $this->executeGuzzlePool($client, $requests, $firstPage);

        return collect($all);
    }

    /**
     * special getter for all parts since the amount of data needs to be throttled
     *
     * @return Illuminate\Support\Collection
     */
    public function getAllParts()
    {
        $firstPage = $this->getType('parts', 1, 1000, '');

        if ($firstPage === false) {
            return $this->getErrors();
        }

        $response = $this->parseResponse();

        $totalPages = ceil($response['count'] / 1000);

        $baseUrl = $this->baseUrl.'parts/?ordering=name&page_size=1000&page=';

        $client = $this->generateGuzzleClient();
        $requests = $this->generateGuzzleRequests($totalPages, $baseUrl);
        $all = $this->executeGuzzlePool($client, $requests, $firstPage);

        return collect($all);
    }

    /**
     * gets all of an allowed type
     *
     * @param string $type
     * @return Illuminate\Support\Collection
     */
    public function getAll($type)
    {
        $allowedTypes = ['colors', 'themes', 'part_categories', 'sets'];

        abort_if(! in_array($type, $allowedTypes), 400, 'Request Type is not allowed!');

        $all = $this->getType($type, 1, 1000, '');

        if ($all === false) {
            return $this->getErrors();
        }

        $response = $this->parseResponse();

        $totalPages = ceil($response['count'] / 1000);

        for ($i = 2; $i <= $totalPages; $i++) {
            $page = $this->getType($type, $i, 1000, '');

            if ($page === false) {
                return $this->getErrors();
            }

            $all = array_merge($all, $page);
        }

        return collect($all);
    }
}
