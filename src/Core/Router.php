<?php

namespace Mist\Core;

class Router
{
    private static $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Checks route and executes callable GET
     *
     * @param string $route route
     * @param callable $callback callback function
     *
     * @return void
     */
    public static function get($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') return;
        self::$routes['GET'][$route] = $callback;
        self::init($route, $callback);
    }

    /**
     * Checks route and executes callable POST
     *
     * @param string $route route
     * @param callable $callback callback function
     *
     * @return void
     */
    public static function post($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        self::$routes['POST'][$route] = $callback;
        self::init($route, $callback);
    }

    /**
     * Checks route and executes callable
     *
     * @param string $route route
     * @param callable $callback callback function
     *
     * @return void
     */
    protected static function init($route, $callback)
    {
        $requestArray = self::_brakeDownUrl($_SERVER['REQUEST_URI']);
        $routeArray = self::_brakeDownUrl($route);

        if ($requestArray[0] === $routeArray[0]) {
            array_shift($requestArray);
            array_shift($routeArray);
            if (count($requestArray) === count($routeArray)) {
                if (gettype($callback) === 'array') {
                    app()->call($callback[0], $callback[1], [...$requestArray]);
                } else {
                    $callback(...$requestArray);
                }
            }
        }
    }

    /**
     * Breakdown url into parts
     *
     * @param string $url url
     *
     * @return array
     */
    private static function _brakeDownUrl($url)
    {
        preg_match_all('/(?<=\/).+?(?=\/|$)/', $url, $result);

        if (!empty($result[0][0])) {
            if (preg_match('/api/i', $result[0][0])) {
                array_shift($result[0]);
            }
        }

        return $result[0] ?: [''];
    }
}
