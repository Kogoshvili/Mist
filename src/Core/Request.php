<?php

namespace Mist\Core;

class Request
{
    /**
     * Api Pattern. Used for removing the api prefix from the request uri.
     *
     * @var string
     */
    private static $pattern = '/^(\/api).*$/i';

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
     * Request parameters.
     *
     * @var array
     */
    public $params;

    /**
     * Raw request body.
     *
     * @var string
     */
    public $rawRequest;

    function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->rawRequest = $_REQUEST;
        $this->route = preg_replace(self::$pattern, '', $_SERVER['REQUEST_URI']);
    }
}
