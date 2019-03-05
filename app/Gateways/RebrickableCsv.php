<?php

namespace App\Gateways;

use Zttp\Zttp;

class RebrickableCsv
{
    /**
     * additional url path requirements based on the api action requested
     *
     * @var string
     */
    protected $url = 'https://rebrickable.com/media/downloads/';

    /**
     * The entire response from the Zttp request. GuzzleHttp\Psr7\Response
     *
     * @var null
     */
    protected $response;

    /**
     * getter for all items of a given type
     *
     * @return Illuminate\Support\Collection
     */
    public function getAllType($type)
    {
        $this->appendUrl($type.'.csv');
        $this->executeGet();

        return collect($this->getResults());
    }

    /**
     * appends a new path to the url
     *
     * @param string $path
     * @return void
     */
    protected function appendUrl($path)
    {
        $this->url = $this->url.$path;
    }

    /**
     * executes the actual Zttp get request
     *
     * @return bool
     */
    protected function executeGet()
    {
        $this->response = Zttp::get($this->url);

        return true;
    }

    /**
     * parses Zttp response to create an array of results
     *
     * @return array
     */
    protected function getResults()
    {
        if (is_null($this->response)) {
            return [];
        }

        $body = $this->response->body();

        $array = array_map('str_getcsv', explode("\n", $body));
        $colNames = array_shift($array); //column headers
        $boolPos = null;
        array_walk(
            $colNames,
            function ($v, $k) use (&$boolPos) {
                if (substr($v, 0, 3) == 'is_') {
                    $boolPos = $k;
                }
            }
        );
        $count = count($array);

        if (is_array($array[$count - 1]) && is_null($array[$count - 1][0])) {
            array_pop($array);
        }

        $result = [];

        //reassign the colnames to each element
        array_walk(
            $array,
            function ($v, $k) use (&$result, $colNames, $boolPos) {
                if (! is_null($boolPos)) {
                    $v[$boolPos] = ($v[$boolPos] == 'f') ? false : true;
                }
                $result[] = array_combine($colNames, $v);
            }
        );

        return $result;
    }
}
