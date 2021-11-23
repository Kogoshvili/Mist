<?php

use Mist\Core\Router;
use Mist\Controllers\{MainController, UserController};

Router::get('/posts/{id}', [MainController::class, 'getPost']);
Router::post('/user/login', [UserController::class, 'login']);
Router::post('/user/register', [UserController::class, 'register']);

