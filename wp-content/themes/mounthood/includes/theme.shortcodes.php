<?php
if (!function_exists('mounthood_theme_shortcodes_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_theme_shortcodes_setup', 1 );
	function mounthood_theme_shortcodes_setup() {
		add_filter('mounthood_filter_googlemap_styles', 'mounthood_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'mounthood_theme_shortcodes_googlemap_styles' ) ) {
	function mounthood_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'mounthood');
		$list['greyscale']	= esc_html__('Greyscale', 'mounthood');
		$list['inverse']	= esc_html__('Inverse', 'mounthood');
		$list['apple']		= esc_html__('Apple', 'mounthood');
		$list['dark']		= esc_html__('Dark', 'mounthood');
		return $list;
	}
}
?>