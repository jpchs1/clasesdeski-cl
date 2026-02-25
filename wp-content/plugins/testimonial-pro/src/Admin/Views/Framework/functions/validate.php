<?php
/**
 * Real Testimonials Pro validate functions
 *
 * @since 1.0
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! function_exists( 'spftestimonial_validate_email' ) ) {
	/**
	 *
	 * Email validate
	 *
	 * @param mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_validate_email( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			return esc_html__( 'Please enter a valid email address.', 'testimonial-pro' );
		}

	}
}

if ( ! function_exists( 'spftestimonial_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate
	 *
	 * @param mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_validate_numeric( $value ) {

		if ( ! is_numeric( $value ) ) {
			return esc_html__( 'Please enter a valid number.', 'testimonial-pro' );
		}

	}
}

if ( ! function_exists( 'spftestimonial_validate_required' ) ) {
	/**
	 *
	 * Required validate
	 *
	 * @param mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_validate_required( $value ) {

		if ( empty( $value ) ) {
			return esc_html__( 'This field is required.', 'testimonial-pro' );
		}

	}
}

if ( ! function_exists( 'spftestimonial_validate_url' ) ) {
	/**
	 *
	 * URL validate
	 *
	 * @param mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_validate_url( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return esc_html__( 'Please enter a valid URL.', 'testimonial-pro' );
		}

	}
}

if ( ! function_exists( 'spftestimonial_customize_validate_email' ) ) {
	/**
	 *
	 * Email validate for Customizer
	 *
	 * @param mixed $validity value.
	 * @param mixed $value value.
	 * @param mixed $wp_customize value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_customize_validate_email( $validity, $value, $wp_customize ) {

		if ( ! sanitize_email( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid email address.', 'testimonial-pro' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'spftestimonial_customize_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate for Customizer
	 *
	 * @param mixed $validity value.
	 * @param mixed $value value.
	 * @param mixed $wp_customize value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_customize_validate_numeric( $validity, $value, $wp_customize ) {

		if ( ! is_numeric( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid number.', 'testimonial-pro' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'spftestimonial_customize_validate_required' ) ) {
	/**
	 *
	 * Required validate for Customizer
	 *
	 * @param mixed $validity value.
	 * @param mixed $value value.
	 * @param mixed $wp_customize value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_customize_validate_required( $validity, $value, $wp_customize ) {

		if ( empty( $value ) ) {
			$validity->add( 'required', esc_html__( 'This field is required.', 'testimonial-pro' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'spftestimonial_customize_validate_url' ) ) {
	/**
	 *
	 * URL validate for Customizer
	 *
	 * @param mixed $validity value.
	 * @param mixed $value value.
	 * @param mixed $wp_customize value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_customize_validate_url( $validity, $value, $wp_customize ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid URL.', 'testimonial-pro' ) );
		}

		return $validity;

	}
}
