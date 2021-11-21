<?php

// MIDDLEWARE
if (preg_match('/^(\/api).*$/i', $_SERVER['REQUEST_URI']))
{
    // MIDDLEWARE
    require_once ROUTES.'api.php';
}
else
{
    // MIDDLEWARE
    require_once ROUTES.'view.php';
}
