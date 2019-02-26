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

        return $this->getResults();
    }

    /**
     * gets all of an allowed type
     *
     * @param string $type
     * @return Illuminate\Support\Collection
     */
    public function getAll($type)
    {
        $type = ucwords(strtolower($type));
        $allowedTypes = ['Colors', 'Themes'];

        abort_if(! in_array($type, $allowedTypes), 400, 'Request Type is not allowed!');

        $all = call_user_func([$this, 'get'.$type], 1, 100, '');

        $response = $this->parseResponse();

        $totalPages = ceil($response['count'] / 100);

        for ($i = 2; $i <= $totalPages; $i++) {
            $page = call_user_func([$this, 'get'.$type], $i, 100, '');

            $all = array_merge($all, $page);
        }

        return collect($all);
    }

    /**
     * retrieves all colors
     *
     * @param mixed int
     * @param mixed int
     * @param string $ordering
     * @return array
     */
    public function getColors(int $page = 1, int $page_size = 100, $ordering = '')
    {
        $this->appendUrl('lego/colors/');
        $this->appendUrlParam('page='.$page);
        $this->appendUrlParam('page_size='.$page_size);
        $this->appendUrlParam('ordering='.$ordering);

        $this->executeGet();

        return $this->getResults();
    }

    /**
     * retrieves all themes
     *
     * @param mixed int
     * @param mixed int
     * @param string $ordering
     * @return array
     */
    public function getThemes(int $page = 1, int $page_size = 100, $ordering = '')
    {
        $this->appendUrl('lego/themes/');
        $this->appendUrlParam('page='.$page);
        $this->appendUrlParam('page_size='.$page_size);
        $this->appendUrlParam('ordering='.$ordering);

        $this->executeGet();

        return $this->getResults();
    }
}
