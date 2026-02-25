<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('mounthood_sc_br_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_sc_br_theme_setup' );
	function mounthood_sc_br_theme_setup() {
		add_action('mounthood_action_shortcodes_list', 		'mounthood_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('mounthood_sc_br')) {	
	function mounthood_sc_br($atts, $content = null) {
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('mounthood_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	mounthood_require_shortcode("trx_br", "mounthood_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_br_reg_shortcodes' ) ) {
	//add_action('mounthood_action_shortcodes_list', 'mounthood_sc_br_reg_shortcodes');
	function mounthood_sc_br_reg_shortcodes() {
	
		mounthood_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'trx_utils'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'trx_utils') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'trx_utils'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'trx_utils') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'trx_utils'),
						'left' => esc_html__('Left', 'trx_utils'),
						'right' => esc_html__('Right', 'trx_utils'),
						'both' => esc_html__('Both', 'trx_utils')
					)
				)
			)
		));
	}
}
?>