<?php

/**
 * Globally available helper functions
 */

function app()
{
    return Mist\Core\Core::instance();
}

function base64url_encode($str) {
    return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}
