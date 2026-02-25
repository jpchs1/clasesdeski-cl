<?php
/**
 * Mounthood Framework: Theme options custom fields
 *
 * @package	mounthood
 * @since	mounthood 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'mounthood_options_custom_theme_setup' ) ) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_options_custom_theme_setup' );
	function mounthood_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'mounthood_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'mounthood_options_custom_load_scripts' ) ) {
	//Handler of add_action("admin_enqueue_scripts", 'mounthood_options_custom_load_scripts');
	function mounthood_options_custom_load_scripts() {
		wp_enqueue_script( 'mounthood-options-custom-script',	mounthood_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'mounthood_show_custom_field' ) ) {
	function mounthood_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(mounthood_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager mounthood_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'mounthood') : esc_html__( 'Choose Image', 'mounthood')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'mounthood') : esc_html__( 'Choose Image', 'mounthood')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'mounthood') : esc_html__( 'Choose Image', 'mounthood')) . '</a>';
				break;
		}
		return apply_filters('mounthood_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>