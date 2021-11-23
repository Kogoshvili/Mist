<?php
ini_set('display_errors', 1);

define('ROOT', __DIR__ . './../src' . DIRECTORY_SEPARATOR);
define('ROUTES', __DIR__ . './../src/routes' . DIRECTORY_SEPARATOR);
define('VIEWS', __DIR__ . './../src/views' . DIRECTORY_SEPARATOR);
define('CONFIG', __DIR__ . './../src/config' . DIRECTORY_SEPARATOR);

require_once 'Helpers/array.php';
require_once 'Helpers/globals.php';

$core = new Mist\Core\Core();
$core->get(Mist\Migrations\Migration::class)->migrate();

require_once 'Core/gateway.php';
