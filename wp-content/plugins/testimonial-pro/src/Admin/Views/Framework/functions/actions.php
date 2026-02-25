<?php
/**
 * Real Testimonials Pro action functions
 *
 * @since 1.0
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

use ShapedPlugin\TestimonialPro\Admin\Views\Framework\Classes\SPFTESTIMONIAL;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! function_exists( 'spftestimonial_get_icons' ) ) {
	/**
	 *
	 * Get icons from admin ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_get_icons() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'spftestimonial_icon_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'testimonial-pro' ) ) );
		}

		ob_start();

		$icon_library = ( apply_filters( 'spftestimonial_fa4', true ) ) ? 'fa4' : 'fa5';

		SPFTESTIMONIAL::include_plugin_file( 'fields/icon/' . $icon_library . '-icons.php' );

		$icon_lists = apply_filters( 'spftestimonial_field_icon_add_icons', spftestimonial_get_default_icons() );

		if ( ! empty( $icon_lists ) ) {

			foreach ( $icon_lists as $list ) {

				echo ( count( $icon_lists ) >= 2 ) ? '<div class="spftestimonial-icon-title">' . esc_attr( $list['title'] ) . '</div>' : '';

				foreach ( $list['icons'] as $icon ) {
					echo '<i title="' . esc_attr( $icon ) . '" class="' . esc_attr( $icon ) . '"></i>';
				}
			}
		} else {

				echo '<div class="spftestimonial-error-text">' . esc_html__( 'No data available.', 'testimonial-pro' ) . '</div>';

		}

		$content = ob_get_clean();

		wp_send_json_success( array( 'content' => $content ) );

	}
	add_action( 'wp_ajax_spftestimonial-get-icons', 'spftestimonial_get_icons' );
}


if ( ! function_exists( 'spftestimonial_reset_ajax' ) ) {
	/**
	 *
	 * Reset Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_reset_ajax() {

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'spftestimonial_backup_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'testimonial-pro' ) ) );
		}
		$unique = ( ! empty( $_POST['unique'] ) ) ? sanitize_text_field( wp_unslash( $_POST['unique'] ) ) : '';

		// Success.
		delete_option( $unique );

		wp_send_json_success();

	}
	add_action( 'wp_ajax_spftestimonial-reset', 'spftestimonial_reset_ajax' );
}

if ( ! function_exists( 'spftestimonial_chosen_ajax' ) ) {
	/**
	 *
	 * Chosen Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spftestimonial_chosen_ajax() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'spftestimonial_chosen_ajax_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'testimonial-pro' ) ) );
		}

		$type  = ( ! empty( $_POST['type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$term  = ( ! empty( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$query = ( ! empty( $_POST['query_args'] ) ) ? wp_kses_post_deep( $_POST['query_args'] ) : array();

		if ( empty( $type ) || empty( $term ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'testimonial-pro' ) ) );
		}

		$capability = apply_filters( 'spftestimonial_chosen_ajax_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'testimonial-pro' ) ) );
		}

		// Success.
		$options = SPFTESTIMONIAL_Fields::field_data( $type, $term, $query );

		wp_send_json_success( $options );

	}
	add_action( 'wp_ajax_spftestimonial-chosen', 'spftestimonial_chosen_ajax' );
}
