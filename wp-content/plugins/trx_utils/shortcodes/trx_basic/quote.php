<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('mounthood_sc_quote_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_sc_quote_theme_setup' );
	function mounthood_sc_quote_theme_setup() {
		add_action('mounthood_action_shortcodes_list', 		'mounthood_sc_quote_reg_shortcodes');
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('mounthood_sc_quote')) {	
	function mounthood_sc_quote($atts, $content=null){	
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= mounthood_get_css_dimensions_from_values($width);
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (mounthood_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
			. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('mounthood_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	mounthood_require_shortcode('trx_quote', 'mounthood_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_quote_reg_shortcodes' ) ) {
	//add_action('mounthood_action_shortcodes_list', 'mounthood_sc_quote_reg_shortcodes');
	function mounthood_sc_quote_reg_shortcodes() {
	
		mounthood_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'trx_utils'),
			"desc" => wp_kses_data( __("Quote text", 'trx_utils') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'trx_utils'),
					"desc" => wp_kses_data( __("URL for quote cite", 'trx_utils') ),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title (author)", 'trx_utils'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'trx_utils') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'trx_utils'),
					"desc" => wp_kses_data( __("Quote content", 'trx_utils') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => mounthood_shortcodes_width(),
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
if ( !function_exists( 'mounthood_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('mounthood_action_shortcodes_list_vc', 'mounthood_sc_quote_reg_shortcodes_vc');
	function mounthood_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'trx_utils'),
			"description" => wp_kses_data( __("Quote text", 'trx_utils') ),
			"category" => esc_html__('Content', 'trx_utils'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'trx_utils'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'trx_utils') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'trx_utils'),
					"description" => wp_kses_data( __("Quote title (author name)", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'trx_utils'),
					"description" => wp_kses_data( __("Quote content", 'trx_utils') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('animation'),
				mounthood_get_vc_param('css'),
				mounthood_vc_width(),
				mounthood_get_vc_param('margin_top'),
				mounthood_get_vc_param('margin_bottom'),
				mounthood_get_vc_param('margin_left'),
				mounthood_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends MOUNTHOOD_VC_ShortCodeSingle {}
	}
}
?>