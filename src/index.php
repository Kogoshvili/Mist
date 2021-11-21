<?php

define('ROOT', __DIR__ . './../src' . DIRECTORY_SEPARATOR);
define('ROUTES', __DIR__ . './../src/Routes' . DIRECTORY_SEPARATOR);
define('VIEWS', __DIR__ . './../src/Views' . DIRECTORY_SEPARATOR);
define('CONFIG', __DIR__ . './../src/Config' . DIRECTORY_SEPARATOR);

$core = new Mist\Core\Core();

require_once 'Core/Gateway.php';
