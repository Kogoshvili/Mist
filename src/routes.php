<?php

use Vitra\Core\Router;
use Vitra\Controllers\MainController;

Router::get(
    '',
    fn () => $core->get(MainController::class)->Home()
);
