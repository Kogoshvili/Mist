<?php

namespace Mist\Middlewares;

use Mist\Core\Request;

abstract class Middleware
{
    public function handle(Request $request, $next)
    {
        return $next($request);
    }
}
