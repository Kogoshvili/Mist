<?php

namespace Mist\Core;

class Request
{
    /**
     * Request method.
     *
     * @var string
     */
    public $method;

    /**
     * Request uri.
     *
     * @var string
     */
    public $uri;

    /**
     * Request route.
     *
     * @var string
     */
    public $route;

    /**
     * Raw request parameters.
     *
     * @var array
     */
    public $rawParams = [];

    /**
     * Request parameters.
     *
     * @var array
     */
    public $params = [];

    /**
     * Raw request body.
     *
     * @var string
     */
    public $rawRequest;

    /**
     * Flag for checking if the request is an api request.
     *
     * @var bool
     */
    public $isApiRequest = false;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->rawRequest = $_REQUEST;
        $requestArray = $this->brakeDownUrl($this->uri, $this->isApiRequest);
        $this->route = $requestArray[0];
        $this->rawParams = array_slice($requestArray, 1);
    }

    /**
     * Breakdown url into parts
     *
     * @param string $url url
     *
     * @return array
     */
    public function brakeDownUrl($url, &$apiFlag = null)
    {
        preg_match_all('/(?<=\/).+?(?=\/|$)/', $url, $result);

        if (!empty($result[0][0])) {
            if (preg_match('/api/i', $result[0][0])) {
                $apiFlag = true;
                array_shift($result[0]);
            }
        }

        return $result[0] ?: [''];
    }
}
