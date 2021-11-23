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
    public $isApi = false;

    /**
     * Query
     *
     * @var array
     */
    public $query = [];

    /**
     * Json Body
     *
     * @var array
     */
    public $json;

    /**
     * Get Body
     *
     * @var array
     */
    public $get;

    /**
     * Post Body
     *
     * @var array
     */
    public $post;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->rawRequest = $_REQUEST;

        $this->json = json_decode(file_get_contents('php://input'), true);
        $this->get = $_GET;
        $this->post = $_POST;

        $request = $this->brakeDownUrl($this->uri);
        $this->isApi = $request['isApi'];
        $this->route = $request['route'];
        $this->rawParams = $request['params'];
        $this->query = $request['query'];

        if ($_SERVER["HTTP_AUTHORIZATION"]) {
            $this->token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"]);
        }
    }

    public function __get($name)
    {
        if (isset($this->json[$name])) {
            return $this->json[$name];
        }

        if (isset($this->get[$name])) {
            return $this->get[$name];
        }

        if (isset($this->post[$name])) {
            return $this->post[$name];
        }

        return null;
    }

    /**
     * Breakdown url into parts
     *
     * @param string $url url
     *
     * @return array
     */
    public function brakeDownUrl($url)
    {
        $result = [
            'isApi' => false,
            'route' => [],
            'params' => [],
            'query' => ''
        ];

        preg_match_all('/(?<=\/).+?(?=\/|$)/', $url, $result['route']);
        $result['route'] = $result['route'][0] ?? [];

        preg_match_all('/{.+?}/', $url, $result['params']);
        $result['params'] = $result['params'][0] ?? [];

        preg_match_all('/\?.+/', $url, $result['query']);
        $result['query'] = $result['query'][0] ?? '';

        if (array_key_exists(0, $result['route']) && $result['route'][0] === 'api') {
            $result['isApi'] = true;
            $result['route'] = array_slice($result['route'], 1);
        }

        return $result;
    }
}
