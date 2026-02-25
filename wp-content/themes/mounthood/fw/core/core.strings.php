<?php
/**
 * Mounthood Framework: strings manipulations
 *
 * @package	mounthood
 * @since	mounthood 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'MOUNTHOOD_MULTIBYTE' ) ) define( 'MOUNTHOOD_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('mounthood_strlen')) {
	function mounthood_strlen($text) {
		return MOUNTHOOD_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('mounthood_strpos')) {
	function mounthood_strpos($text, $char, $from=0) {
		return MOUNTHOOD_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('mounthood_strrpos')) {
	function mounthood_strrpos($text, $char, $from=0) {
		return MOUNTHOOD_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('mounthood_substr')) {
	function mounthood_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = mounthood_strlen($text)-$from;
		}
		return MOUNTHOOD_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('mounthood_strtolower')) {
	function mounthood_strtolower($text) {
		return MOUNTHOOD_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('mounthood_strtoupper')) {
	function mounthood_strtoupper($text) {
		return MOUNTHOOD_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('mounthood_strtoproper')) {
	function mounthood_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<mounthood_strlen($text); $i++) {
			$ch = mounthood_substr($text, $i, 1);
			$rez .= mounthood_strpos(' .,:;?!()[]{}+=', $last)!==false ? mounthood_strtoupper($ch) : mounthood_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('mounthood_strrepeat')) {
	function mounthood_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('mounthood_strshort')) {
	function mounthood_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= mounthood_strlen($str)) 
			return strip_tags($str);
		$str = mounthood_substr(strip_tags($str), 0, $maxlength - mounthood_strlen($add));
		$ch = mounthood_substr($str, $maxlength - mounthood_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = mounthood_strlen($str) - 1; $i > 0; $i--)
				if (mounthood_substr($str, $i, 1) == ' ') break;
			$str = trim(mounthood_substr($str, 0, $i));
		}
		if (!empty($str) && mounthood_strpos(',.:;-', mounthood_substr($str, -1))!==false) $str = mounthood_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('mounthood_strclear')) {
	function mounthood_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (mounthood_substr($text, 0, mounthood_strlen($open))==$open) {
					$pos = mounthood_strpos($text, '>');
					if ($pos!==false) $text = mounthood_substr($text, $pos+1);
				}
				if (mounthood_substr($text, -mounthood_strlen($close))==$close) $text = mounthood_substr($text, 0, mounthood_strlen($text) - mounthood_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('mounthood_get_slug')) {
	function mounthood_get_slug($title) {
		return mounthood_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('mounthood_strmacros')) {
	function mounthood_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('mounthood_unserialize')) {
	function mounthood_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>