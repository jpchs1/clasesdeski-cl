<?php
/**
 * Real Testimonials Pro sanitize functions
 *
 * @since 1.0
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'spftestimonial_sanitize_replace_a_to_b' ) ) {
	/**
	 *
	 * Sanitize
	 * Replace letter a to letter b
	 *
	 * @param mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_sanitize_replace_a_to_b( $value ) {
		return str_replace( 'a', 'b', $value );
	}
}

if ( ! function_exists( 'spftestimonial_sanitize_title' ) ) {
	/**
	 *
	 * Sanitize title
	 *
	 * @param mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_sanitize_title( $value ) {
		return sanitize_title( $value );
	}
}
