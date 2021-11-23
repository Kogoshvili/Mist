<?php

/**
 * Globally available array helper functions
 */

function array_some($callback, $arr)
{
    foreach ($arr as $key => $value) {
        if (call_user_func($callback, $value, $key)) {
            return true;
        }
    }

    return false;
}


function array_every($callback, $arr)
{
    foreach ($arr as $key => $value) {
        if (!call_user_func($callback, $value, $key)) {
            return false;
        }
    }

    return true;
}
