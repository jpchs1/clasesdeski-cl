<?php
/**
 * Framework media field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPFTESTIMONIAL_Field_media' ) ) {
	/**
	 *
	 * Field: media
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPFTESTIMONIAL_Field_media extends SPFTESTIMONIAL_Fields {
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

			$args = wp_parse_args(
				$this->field,
				array(
					'url'            => true,
					'preview'        => true,
					'preview_width'  => '',
					'preview_height' => '',
					'library'        => array(),
					'button_title'   => esc_html__( 'Upload', 'testimonial-pro' ),
					'remove_title'   => esc_html__( 'Remove', 'testimonial-pro' ),
					'preview_size'   => 'thumbnail',
				)
			);

			$default_values = array(
				'url'         => '',
				'id'          => '',
				'width'       => '',
				'height'      => '',
				'thumbnail'   => '',
				'alt'         => '',
				'title'       => '',
				'description' => '',
			);

			// fallback.
			if ( is_numeric( $this->value ) ) {

				$this->value = array(
					'id'        => $this->value,
					'url'       => wp_get_attachment_url( $this->value ),
					'thumbnail' => wp_get_attachment_image_src( $this->value, 'thumbnail', true )[0],
				);

			}

			$this->value = wp_parse_args( $this->value, $default_values );

			$library     = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
			$library     = ( ! empty( $library ) ) ? implode( ',', $library ) : '';
			$preview_src = ( 'thumbnail' !== $args['preview_size'] ) ? $this->value['url'] : $this->value['thumbnail'];
			$hidden_url  = ( empty( $args['url'] ) ) ? ' hidden' : '';
			$hidden_auto = ( empty( $this->value['url'] ) ) ? ' hidden' : '';
			$placeholder = ( empty( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_html__( 'Not selected', 'testimonial-pro' ) . '"' : '';

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $args['preview'] ) ) {

				$preview_width  = ( ! empty( $args['preview_width'] ) ) ? 'max-width:' . esc_attr( $args['preview_width'] ) . 'px;' : '';
				$preview_height = ( ! empty( $args['preview_height'] ) ) ? 'max-height:' . esc_attr( $args['preview_height'] ) . 'px;' : '';
				$preview_style  = ( ! empty( $preview_width ) || ! empty( $preview_height ) ) ? ' style="' . esc_attr( $preview_width . $preview_height ) . '"' : '';

				echo '<div class="spftestimonial--preview' . esc_attr( $hidden_auto ) . '">';
				echo '<div class="spftestimonial-image-preview"' . wp_kses_post( $preview_style ) . '>';
				echo '<i class="spftestimonial--remove fa fa-times"></i><span><img src="' . esc_url( $preview_src ) . '" class="spftestimonial--src" /></span>';
				echo '</div>';
				echo '</div>';

			}

			echo '<div class="spftestimonial--placeholder">';
			echo '<input type="text" name="' . esc_attr( $this->field_name( '[url]' ) ) . '" value="' . esc_attr( $this->value['url'] ) . '" class="spftestimonial--url' . esc_attr( $hidden_url ) . '" readonly="readonly"' . $this->field_attributes() . $placeholder . ' />';// phpcs:ignore
			echo '<a href="#" class="button button-primary spftestimonial--button" data-library="' . esc_attr( $library ) . '" data-preview-size="' . esc_attr( $args['preview_size'] ) . '">' . esc_html( $args['button_title'] ) . '</a>';
			echo ( empty( $args['preview'] ) ) ? '<a href="#" class="button button-secondary spftestimonial-warning-primary spftestimonial--remove' . esc_attr( $hidden_auto ) . '">' . esc_html( $args['remove_title'] ) . '</a>' : '';
			echo '</div>';

			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[id]' ) ) . '" value="' . esc_attr( $this->value['id'] ) . '" class="spftestimonial--id"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[width]' ) ) . '" value="' . esc_attr( $this->value['width'] ) . '" class="spftestimonial--width"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[height]' ) ) . '" value="' . esc_attr( $this->value['height'] ) . '" class="spftestimonial--height"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[thumbnail]' ) ) . '" value="' . esc_attr( $this->value['thumbnail'] ) . '" class="spftestimonial--thumbnail"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[alt]' ) ) . '" value="' . esc_attr( $this->value['alt'] ) . '" class="spftestimonial--alt"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[title]' ) ) . '" value="' . esc_attr( $this->value['title'] ) . '" class="spftestimonial--title"/>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[description]' ) ) . '" value="' . esc_attr( $this->value['description'] ) . '" class="spftestimonial--description"/>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
