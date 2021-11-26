<?php

/**
 * Globally available helper functions
 */

if (!function_exists('app')) {
    function app()
    {
        return Mist\Core\Core::instance();
    }
}

if (!function_exists('base64url_encode')) {
    function base64url_encode($str) {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}
