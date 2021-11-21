<?php

// MIDDLEWARE
if (preg_match('/^(\/api).*$/i', $_SERVER['REQUEST_URI'])) {
    // MIDDLEWARE
    include_once ROUTES.'api.php';
} else {
    // MIDDLEWARE
    include_once ROUTES.'view.php';
}
