<?php
/**
 * Real Testimonials Pro helper functions
 *
 * @since 1.0
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'spftestimonial_array_search' ) ) {
	/**
	 *
	 * Array search key & value
	 *
	 * @param mixed $array array.
	 * @param mixed $key key.
	 * @param mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_array_search( $array, $key, $value ) {

		$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, spftestimonial_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;

	}
}

if ( ! function_exists( 'spftestimonial_timeout' ) ) {
	/**
	 *
	 * Between Microtime
	 *
	 * @param mixed $timenow curren time.
	 * @param mixed $starttime start time.
	 * @param mixed $timeout timeout.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_timeout( $timenow, $starttime, $timeout = 30 ) {
		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
	}
}

if ( ! function_exists( 'spftestimonial_wp_editor_api' ) ) {
	/**
	 *
	 * Check for wp editor api
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_wp_editor_api() {
		global $wp_version;
		return version_compare( $wp_version, '4.8', '>=' );
	}
}
