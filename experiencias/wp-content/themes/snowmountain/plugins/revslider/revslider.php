<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('snowmountain_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'snowmountain_revslider_theme_setup9', 9 );
	function snowmountain_revslider_theme_setup9() {
		if (is_admin()) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins',	'snowmountain_revslider_tgmpa_required_plugins' );
		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'snowmountain_exists_revslider' ) ) {
	function snowmountain_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'snowmountain_revslider_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_revslider_tgmpa_required_plugins');
	function snowmountain_revslider_tgmpa_required_plugins($list=array()) {
		if (in_array('revslider', snowmountain_storage_get('required_plugins'))) {
			$path = snowmountain_get_file_dir('plugins/revslider/revslider.zip');
			$list[] = array(
					'name' 		=> esc_html__('Revolution Slider', 'snowmountain'),
					'slug' 		=> 'revslider',
					'version'   => '6.6.7',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}
?>