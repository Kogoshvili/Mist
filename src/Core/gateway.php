<?php

/**
 * Request entry point
 */

$middlewares = require_once CONFIG . 'middlewares.php';

foreach ($middlewares['core'] as $middleware) {
    $core->call($middleware, 'handle');
}

if (preg_match('/^(\/api).*$/i', $_SERVER['REQUEST_URI'])) {
    foreach ($middlewares['api'] as $middleware) {
        $core->call($middleware, 'handle');
    }

    include_once ROUTES . 'api.php';
} else {
    foreach ($middlewares['view'] as $middleware) {
        $core->call($middleware, 'handle');
    }

    include_once ROUTES . 'view.php';
}
