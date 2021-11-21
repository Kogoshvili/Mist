<?php

namespace Mist\Core;

class Core
{
    public $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->container, $name)) {
            return call_user_func_array([$this->container, $name], $arguments);
        }
    }

    public function __callStatic($name, $arguments)
    {
        return $GLOBALS['core']->$name(...$arguments);
    }
}
