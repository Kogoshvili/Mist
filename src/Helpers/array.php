<?php

/**
 * Globally available array helper functions
 */

if (!function_exists('array_some')) {
    function array_some($callback, $arr)
    {
        foreach ($arr as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('array_every')) {
    function array_every($callback, $arr)
    {
        foreach ($arr as $key => $value) {
            if (!call_user_func($callback, $value, $key)) {
                return false;
            }
        }

        return true;
    }
}


