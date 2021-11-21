<?php
ini_set('display_errors', 1);

define('ROOT', __DIR__ . './../src' . DIRECTORY_SEPARATOR);
define('ROUTES', __DIR__ . './../src/routes' . DIRECTORY_SEPARATOR);
define('VIEWS', __DIR__ . './../src/views' . DIRECTORY_SEPARATOR);
define('CONFIG', __DIR__ . './../src/config' . DIRECTORY_SEPARATOR);

$core = new Mist\Core\Core();

require_once 'Core/Gateway.php';
