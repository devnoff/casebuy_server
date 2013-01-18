<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function keysToLower($o)
{
    if (is_object($o)) {
        $o = (array)$o;
    }
    if (is_array($o)) {
        return array_map('keys_to_lower', array_change_key_case($o));
    }
    else {
        return $o;
    }

}

function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }
 
        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }
 

?>

