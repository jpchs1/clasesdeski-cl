<?php
/**
 * Framework license field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 */

use ShapedPlugin\TestimonialPro\Includes\License;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPFTESTIMONIAL_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 2.2.4
	 * @version 2.2.4
	 */
	class SPFTESTIMONIAL_Field_license extends SPFTESTIMONIAL_Fields {
		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			$type                 = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';
			$manage_license       = new License( SP_TESTIMONIAL_PRO_FILE, SP_TPRO_VERSION, 'ShapedPlugin', SP_TPRO_STORE_URL, SP_TPRO_ITEM_ID, SP_TPRO_ITEM_SLUG );
			$license_key          = $manage_license->get_license_key();
			$license_key_status   = $manage_license->get_license_status();
			$license_status       = ( is_object( $license_key_status ) ? $license_key_status->license : '' );
			$license_notices      = $manage_license->license_notices();
			$license_status_class = '';
			$license_active       = '';
			$license_data         = $manage_license->api_request();

			echo '<div class="testimonial-pro-license text-center">';
			echo '<h3>' . esc_html__( 'Real Testimonials Pro License Key', 'testimonial-pro' ) . '</h3>';
			if ( 'valid' === $license_status ) {
				$license_status_class = 'license-key-active';
				$license_active       = '<span>' . esc_html__( 'Active', 'testimonial-pro' ) . '</span>';
				echo '<p>' . esc_html__( 'Your license key is active.', 'testimonial-pro' ) . '</p>';
			} elseif ( 'expired' === $license_status ) {
				echo '<p style="color: red;">Your license key expired on ' . date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) ) . '. <a href="' . SP_TPRO_STORE_URL . '/checkout/?edd_license_key=' . esc_attr( $license_key ) . '&download_id=' . SP_TPRO_ITEM_ID . '&utm_campaign=testimonial_pro&utm_source=licenses&utm_medium=expired" target="_blank">Renew license key at discount.</a></p>';
			} else {
				echo '<p>Please activate your license key to make the plugin work. <a href="https://docs.shapedplugin.com/docs/testimonial-pro/getting-started/activating-license-key/" target="_blank">How to activate license key?</a></p>';
			}
			echo '<div class="testimonial-pro-license-area">';
			echo '<div class="testimonial-pro-license-key"><input class="testimonial-pro-license-key-input ' . esc_attr( $license_status_class ) . '" type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . ' />' . wp_kses_post( $license_active ) . '</div>';// phpcs:ignore
			wp_nonce_field( 'sp_testimonial_pro_nonce', 'sp_testimonial_pro_nonce' );
			if ( 'valid' === $license_status ) {
				echo '<input style="color: #dc3545; border-color: #dc3545;" type="submit" class="button-secondary btn-license-deactivate" name="sp_testimonial_pro_license_deactivate" value="' . esc_html__( 'Deactivate', 'testimonial-pro' ) . '"/>';
			} else {
				echo '<input type="submit" class="button-secondary btn-license-save-activate" name="' . esc_attr( $this->unique ) . '[_nonce][save]" value="' . esc_html__( 'Activate', 'testimonial-pro' ) . '"/>';
				echo '<input type="hidden" class="btn-license-activate" name="sp_testimonial_pro_license_activate" value="' . esc_html__( 'Activate', 'testimonial-pro' ) . '"/>';
			}
			echo '<br><div class="testimonial-pro-license-error-notices">' . wp_kses_post( $license_notices ) . '</div>';
			echo '</div>';
			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}

	}
}
