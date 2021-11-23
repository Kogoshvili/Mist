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
        $request = app()->get(Request::class);
        $routeArray = $request->brakeDownUrl($route);
        $requestLength = count($request->route);
        $routeLength = count($routeArray['route']);

        if ($requestLength === $routeLength) {
            $matches = 0;
            foreach ($request->route as $key => $value) {
                if ($routeArray['route'][$key] == $value) {
                    $matches++;
                }
            }

            if ($matches + count($routeArray['params']) === $requestLength) {
                foreach ($routeArray['params'] as $index => $key) {
                    $request->params[substr($key, 1, -1)] = $request->route[$matches + $index];
                }

                if (gettype($callback) === 'array') {
                    app()->call($callback[0], $callback[1], $request->params);
                } else {
                    $callback(...$request->params);
                }
            }
        }
    }
}
