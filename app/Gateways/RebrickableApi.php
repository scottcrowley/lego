<?php

namespace App\Gateways;

class RebrickableApi
{
    use ApiCore;

    /**
     * base url for all api calls
     *
     * @var string
     */
    protected $baseUrl = 'https://rebrickable.com/api/v3/';

    /**
     * generate user token
     *
     * @return array
     */
    public function generateToken()
    {
        $this->appendUrl('users/_token/');
        $this->addPostParam('username', $this->credentials['email']);
        $this->addPostParam('password', $this->credentials['password']);

        $this->executePost();

        $results = $this->getResults();

        if ($results === false) {
            $results = $this->getErrors();
        }

        return $results;
    }

    /**
     * special getter for all sets since the amount of data needs to be throttled
     *
     * @return Illuminate\Support\Collection
     */
    public function getAllSets()
    {
        $firstPage = $this->getType('lego/sets', 1, 1000, '');

        if ($firstPage === false) {
            return $this->getErrors();
        }

        $response = $this->parseResponse();

        $totalPages = ceil($response['count'] / 1000);

        $baseUrl = $this->baseUrl.'lego/sets/?ordering=name&page_size=1000&page=';

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
        $firstPage = $this->getType('lego/parts', 1, 1000, '');

        if ($firstPage === false) {
            return $this->getErrors();
        }

        $response = $this->parseResponse();

        $totalPages = ceil($response['count'] / 1000);

        $baseUrl = $this->baseUrl.'lego/parts/?ordering=name&page_size=1000&page=';

        $client = $this->generateGuzzleClient();
        $requests = $this->generateGuzzleRequests($totalPages, $baseUrl);
        $all = $this->executeGuzzlePool($client, $requests, $firstPage);

        $all = collect($all);

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

        $all = $this->getType('lego/'.$type, 1, 1000, '');

        if ($all === false) {
            return $this->getErrors();
        }

        $response = $this->parseResponse();

        $totalPages = ceil($response['count'] / 1000);

        for ($i = 2; $i <= $totalPages; $i++) {
            $page = $this->getType('lego/'.$type, $i, 1000, '');

            if ($page === false) {
                return $this->getErrors();
            }

            $all = array_merge($all, $page);
        }

        return collect($all);
    }

    /**
     * retrieves all of a given type
     *
     * @param string $type
     * @param int $page
     * @param int $page_size
     * @param string $ordering
     * @return array
     */
    public function getType($type, int $page = 1, int $page_size = 1000, $ordering = '')
    {
        $this->appendUrl($type);
        $this->appendUrlParam('page='.$page);
        $this->appendUrlParam('page_size='.$page_size);
        $this->appendUrlParam('ordering='.$ordering);

        $this->executeGet();

        return $this->getResults();
    }
}
