<?php

namespace Vitra\Core;

class Router
{
    /**
     * Checks route and executes callable GET
     *
     * @param string $route route
     * @param callable $function callback function
     *
     * @return void
     */
    public static function get($route, $function)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') return;
        self::init($route, $function);
    }

    /**
     * Checks route and executes callable POST
     *
     * @param string $route route
     * @param callable $function callback function
     *
     * @return void
     */
    public static function post($route, $function)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        self::init($route, $function);
    }

    /**
     * Checks route and executes callable
     *
     * @param string $route route
     * @param callable $function callback function
     *
     * @return void
     */
    protected static function init($route, $function)
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);

        if ($url[1] === $route) {
            $function();
        }
    }
}
