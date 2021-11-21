<?php

use Mist\Core\Router;
use Mist\Controllers\MainController;

Router::get('/', [MainController::class, 'Api']);
Router::get('/posts/{id}', [MainController::class, 'getPost']);
Router::get(
    '/test/{id}',
    function ($id) {
        echo $id;
    }
);
