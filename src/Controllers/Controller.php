<?php

namespace Mist\Controllers;

abstract class Controller
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

    /**
     * Return Json
     *
     * @param mixed $data data
     *
     * @return void
     */
    protected static function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
