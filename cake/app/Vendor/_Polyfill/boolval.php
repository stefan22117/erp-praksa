<?php

if (!function_exists('boolval')) {

    /**
     * Get the boolean value of a variable
     * 
     * @param mixed $var
     * @return bool
     */
    function boolval($var) {
        return (bool) $var;
    }

}
