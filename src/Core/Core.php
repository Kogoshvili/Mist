<?php

namespace Vitra\Core;

class Core
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get($class)
    {
        return $this->container->get($class);
    }
}
