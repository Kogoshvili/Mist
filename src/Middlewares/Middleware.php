<?php

namespace Mist\Middlewares;

use Mist\Core\Request;

abstract class Middleware
{
    abstract public function handle(Request $request);
}
