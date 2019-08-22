<?php

namespace App\Gateways;

class ApiCore
{
    use GuzzleWrapper;

    /**
     * Maximum number of items to get per page.
     *
     * @var integer
     */
    protected $max = 1000;

    /**
     * array of credentials from the .env file
     *
     * @var array
     */
    protected $credentials;

    /**
     * constructor that sets & verifies the proper credentials
     *
     * @return void
     */
    public function __construct()
    {
        $this->credentials = config('rebrickable.api');

        $this->verifyCredentials();

        if (class_basename($this) == 'RebrickableApiUser') {
            $this->baseUrl = $this->baseUrl.$this->credentials['token'].'/';
        }
    }

    /**
     * Updates the credentials
     *
     * @param string $type
     * @param mixed $value
     * @return void
     */
    public function updateCredentials($type, $value)
    {
        if (isset($this->credentials[$type])) {
            $this->credentials[$type] = $value;
        }
    }

    /**
     * generate user token
     *
     * @return array
     */
    public function generateToken()
    {
        $this->resetRequest();

        $this->url = 'https://rebrickable.com/api/v3/users/_token/';
        $this->addPostParam('username', $this->credentials['email']);
        $this->addPostParam('password', $this->credentials['password']);

        $this->executePost();

        $results = $this->getResults();

        if ($results === false) {
            return $this->getErrors();
        }

        return $results;
    }

    /**
     * verifies the credentials from the .env file
     *
     * @return bool
     */
    public function verifyCredentials()
    {
        abort_if(
            is_null($this->credentials)
            || (
                ! is_null($this->credentials) &&
                (
                    $this->credentials['email'] == '' ||
                    $this->credentials['password'] == '' ||
                    $this->credentials['key'] == '' ||
                    $this->credentials['token'] == ''
                )
            ),
            403,
            'The Rebrickable Credentials, from the .env file, are invalid or do not exist.'
        );

        return true;
    }

    /**
     * verifies the credentials on rebrickable site. Also compares the token in the .env file with what is return from rebrickable
     *
     * @return bool
     */
    public function verifyRemoteCredentials()
    {
        $token = $this->generateToken();

        abort_if(
            $this->hasErrors(),
            403,
            'The Rebrickable Credentials, from the .env file, do not match the credentials on the Rebrickable site.'
        );

        abort_if(
            $token != $this->credentials['token'],
            403,
            'The Rebrickable token, from the .env file, does not match the token on the Rebrickable site.'
        );

        return true;
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

    /**
     * Makes sure that a set number has the trailing -1 on it
     *
     * @param string $setNum
     * @return string
     */
    public function normalizeSetNumber($setNum)
    {
        if (substr($setNum, -2, 2) != '-1') {
            $setNum = $setNum .= '-1';
        }
        return $setNum;
    }

    /**
     * Public setter for a url param
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setUrlParam($name, $value)
    {
        $this->appendUrlParam($name.'='.$value);
    }
}
