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
     * @param callable $function callback function
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
     * @param callable $function callback function
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
     * @param callable $function callback function
     *
     * @return void
     */
    protected static function init($route, $callback)
    {
        $url = explode('/', preg_replace('/^(\/api).*$/i', '', $_SERVER['REQUEST_URI']));

        if ($url[0] === $route) {
            if (gettype($callback) === 'array') {
                Core::call($callback[0], $callback[1]);
            } else {
                $callback();
            }
        }
    }
}
