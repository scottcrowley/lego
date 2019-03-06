<?php

namespace App\Gateways;

class RebrickableApiUser
{
    use ApiCore;

    /**
     * base url for all api calls
     *
     * @var string
     */
    protected $baseUrl = 'https://rebrickable.com/api/v3/users/';

    /**
     * generate user token
     *
     * @return array
     */
    public function generateToken()
    {
        $this->url = 'https://rebrickable.com/api/v3/users/_token/';
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

    /**
     * gets all of an allowed type
     *
     * @param string $type
     * @return Illuminate\Support\Collection
     */
    public function getAll($type)
    {
        $allowedTypes = ['setlists', 'sets', 'allparts'];

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
