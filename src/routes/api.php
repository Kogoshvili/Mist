<?php

use Mist\Core\Router;
use Mist\Controllers\MainController;

// Router::get('/', [MainController::class, 'Api']);
Router::get('/posts/{id}/{etc}', [MainController::class, 'getPost']);
