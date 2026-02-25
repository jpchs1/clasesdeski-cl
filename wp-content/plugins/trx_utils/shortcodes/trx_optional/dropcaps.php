<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('mounthood_sc_dropcaps_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_sc_dropcaps_theme_setup' );
	function mounthood_sc_dropcaps_theme_setup() {
		add_action('mounthood_action_shortcodes_list', 		'mounthood_sc_dropcaps_reg_shortcodes');
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('mounthood_sc_dropcaps')) {	
	function mounthood_sc_dropcaps($atts, $content=null){
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= mounthood_get_css_dimensions_from_values($width, $height);
		$style = min(4, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = mounthood_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(mounthood_substr($content, 0, 1)) . '</span>' . trim(mounthood_substr($content, 1))
			. '</div>';
		return apply_filters('mounthood_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	mounthood_require_shortcode('trx_dropcaps', 'mounthood_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('mounthood_action_shortcodes_list', 'mounthood_sc_dropcaps_reg_shortcodes');
	function mounthood_sc_dropcaps_reg_shortcodes() {
	
		mounthood_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'trx_utils'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'trx_utils') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'trx_utils'),
					"desc" => wp_kses_data( __("Dropcaps style", 'trx_utils') ),
					"value" => "1",
					"type" => "checklist",
					"options" => mounthood_get_list_styles(1, 2)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'trx_utils'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'trx_utils') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => mounthood_shortcodes_width(),
				"height" => mounthood_shortcodes_height(),
				"top" => mounthood_get_sc_param('top'),
				"bottom" => mounthood_get_sc_param('bottom'),
				"left" => mounthood_get_sc_param('left'),
				"right" => mounthood_get_sc_param('right'),
				"id" => mounthood_get_sc_param('id'),
				"class" => mounthood_get_sc_param('class'),
				"animation" => mounthood_get_sc_param('animation'),
				"css" => mounthood_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('mounthood_action_shortcodes_list_vc', 'mounthood_sc_dropcaps_reg_shortcodes_vc');
	function mounthood_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'trx_utils'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'trx_utils') ),
			"category" => esc_html__('Content', 'trx_utils'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'trx_utils'),
					"description" => wp_kses_data( __("Dropcaps style", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(mounthood_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('animation'),
				mounthood_get_vc_param('css'),
				mounthood_vc_width(),
				mounthood_vc_height(),
				mounthood_get_vc_param('margin_top'),
				mounthood_get_vc_param('margin_bottom'),
				mounthood_get_vc_param('margin_left'),
				mounthood_get_vc_param('margin_right')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_Dropcaps extends MOUNTHOOD_VC_ShortCodeContainer {}
	}
}
?>