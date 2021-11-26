<?php
ini_set('display_errors', 1);

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('ROUTES', __DIR__ . '/routes' . DIRECTORY_SEPARATOR);
define('VIEWS', __DIR__ . '/views' . DIRECTORY_SEPARATOR);
define('CONFIG', __DIR__ . '/config' . DIRECTORY_SEPARATOR);

$core = new Mist\Core\Core();
$core->get(Mist\Migrations\Migration::class)->migrate();

require_once 'Core/gateway.php';
