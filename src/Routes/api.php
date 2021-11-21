<?php

use Mist\Core\Router;
use Mist\Controllers\MainController;

Router::get('', [MainController::class, 'Api']);

