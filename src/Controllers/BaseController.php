<?php

namespace Vitra\Controllers;

abstract class BaseController
{
    /**
     * View
     *
     * @param string $view view name
     * @param array $data data
     *
     * @return void
     */
    protected static function view($view, $data = [])
    {
        include_once VIEWS . $view . '.phtml';
    }

    /**
     * Redirect
     *
     * @param string $route route
     *
     * @return void
     */
    protected static function redirect($route)
    {
        header("location: {$route}");
        exit;
    }
}
