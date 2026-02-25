<?php
/**
 * Mounthood Framework: theme variables storage
 *
 * @package	mounthood
 * @since	mounthood 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('mounthood_storage_get')) {
	function mounthood_storage_get($var_name, $default='') {
		global $MOUNTHOOD_STORAGE;
		return isset($MOUNTHOOD_STORAGE[$var_name]) ? $MOUNTHOOD_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('mounthood_storage_set')) {
	function mounthood_storage_set($var_name, $value) {
		global $MOUNTHOOD_STORAGE;
		$MOUNTHOOD_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('mounthood_storage_empty')) {
	function mounthood_storage_empty($var_name, $key='', $key2='') {
		global $MOUNTHOOD_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($MOUNTHOOD_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($MOUNTHOOD_STORAGE[$var_name][$key]);
		else
			return empty($MOUNTHOOD_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('mounthood_storage_isset')) {
	function mounthood_storage_isset($var_name, $key='', $key2='') {
		global $MOUNTHOOD_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($MOUNTHOOD_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($MOUNTHOOD_STORAGE[$var_name][$key]);
		else
			return isset($MOUNTHOOD_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('mounthood_storage_inc')) {
	function mounthood_storage_inc($var_name, $value=1) {
		global $MOUNTHOOD_STORAGE;
		if (empty($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = 0;
		$MOUNTHOOD_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('mounthood_storage_concat')) {
	function mounthood_storage_concat($var_name, $value) {
		global $MOUNTHOOD_STORAGE;
		if (empty($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = '';
		$MOUNTHOOD_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('mounthood_storage_get_array')) {
	function mounthood_storage_get_array($var_name, $key, $key2='', $default='') {
		global $MOUNTHOOD_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($MOUNTHOOD_STORAGE[$var_name][$key]) ? $MOUNTHOOD_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($MOUNTHOOD_STORAGE[$var_name][$key][$key2]) ? $MOUNTHOOD_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('mounthood_storage_set_array')) {
	function mounthood_storage_set_array($var_name, $key, $value) {
		global $MOUNTHOOD_STORAGE;
		if (!isset($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = array();
		if ($key==='')
			$MOUNTHOOD_STORAGE[$var_name][] = $value;
		else
			$MOUNTHOOD_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('mounthood_storage_set_array2')) {
	function mounthood_storage_set_array2($var_name, $key, $key2, $value) {
		global $MOUNTHOOD_STORAGE;
		if (!isset($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = array();
		if (!isset($MOUNTHOOD_STORAGE[$var_name][$key])) $MOUNTHOOD_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$MOUNTHOOD_STORAGE[$var_name][$key][] = $value;
		else
			$MOUNTHOOD_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('mounthood_storage_set_array_after')) {
	function mounthood_storage_set_array_after($var_name, $after, $key, $value='') {
		global $MOUNTHOOD_STORAGE;
		if (!isset($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = array();
		if (is_array($key))
			mounthood_array_insert_after($MOUNTHOOD_STORAGE[$var_name], $after, $key);
		else
			mounthood_array_insert_after($MOUNTHOOD_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('mounthood_storage_set_array_before')) {
	function mounthood_storage_set_array_before($var_name, $before, $key, $value='') {
		global $MOUNTHOOD_STORAGE;
		if (!isset($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = array();
		if (is_array($key))
			mounthood_array_insert_before($MOUNTHOOD_STORAGE[$var_name], $before, $key);
		else
			mounthood_array_insert_before($MOUNTHOOD_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('mounthood_storage_push_array')) {
	function mounthood_storage_push_array($var_name, $key, $value) {
		global $MOUNTHOOD_STORAGE;
		if (!isset($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($MOUNTHOOD_STORAGE[$var_name], $value);
		else {
			if (!isset($MOUNTHOOD_STORAGE[$var_name][$key])) $MOUNTHOOD_STORAGE[$var_name][$key] = array();
			array_push($MOUNTHOOD_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('mounthood_storage_pop_array')) {
	function mounthood_storage_pop_array($var_name, $key='', $defa='') {
		global $MOUNTHOOD_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($MOUNTHOOD_STORAGE[$var_name]) && is_array($MOUNTHOOD_STORAGE[$var_name]) && count($MOUNTHOOD_STORAGE[$var_name]) > 0) 
				$rez = array_pop($MOUNTHOOD_STORAGE[$var_name]);
		} else {
			if (isset($MOUNTHOOD_STORAGE[$var_name][$key]) && is_array($MOUNTHOOD_STORAGE[$var_name][$key]) && count($MOUNTHOOD_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($MOUNTHOOD_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('mounthood_storage_inc_array')) {
	function mounthood_storage_inc_array($var_name, $key, $value=1) {
		global $MOUNTHOOD_STORAGE;
		if (!isset($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = array();
		if (empty($MOUNTHOOD_STORAGE[$var_name][$key])) $MOUNTHOOD_STORAGE[$var_name][$key] = 0;
		$MOUNTHOOD_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('mounthood_storage_concat_array')) {
	function mounthood_storage_concat_array($var_name, $key, $value) {
		global $MOUNTHOOD_STORAGE;
		if (!isset($MOUNTHOOD_STORAGE[$var_name])) $MOUNTHOOD_STORAGE[$var_name] = array();
		if (empty($MOUNTHOOD_STORAGE[$var_name][$key])) $MOUNTHOOD_STORAGE[$var_name][$key] = '';
		$MOUNTHOOD_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('mounthood_storage_call_obj_method')) {
	function mounthood_storage_call_obj_method($var_name, $method, $param=null) {
		global $MOUNTHOOD_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($MOUNTHOOD_STORAGE[$var_name]) ? $MOUNTHOOD_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($MOUNTHOOD_STORAGE[$var_name]) ? $MOUNTHOOD_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('mounthood_storage_get_obj_property')) {
	function mounthood_storage_get_obj_property($var_name, $prop, $default='') {
		global $MOUNTHOOD_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($MOUNTHOOD_STORAGE[$var_name]->$prop) ? $MOUNTHOOD_STORAGE[$var_name]->$prop : $default;
	}
}
?>