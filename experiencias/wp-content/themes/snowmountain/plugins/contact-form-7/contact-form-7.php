<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('snowmountain_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'snowmountain_cf7_theme_setup9', 9 );
	function snowmountain_cf7_theme_setup9() {
		
		if (snowmountain_exists_cf7()) {
			add_action( 'wp_enqueue_scripts', 								'snowmountain_cf7_frontend_scripts', 1100 );
			add_filter( 'snowmountain_filter_merge_styles',						'snowmountain_cf7_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins',			'snowmountain_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'snowmountain_cf7_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_cf7_tgmpa_required_plugins');
	function snowmountain_cf7_tgmpa_required_plugins($list=array()) {
		if (in_array('contact-form-7', snowmountain_storage_get('required_plugins'))) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> esc_html__('Contact Form 7', 'snowmountain'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if cf7 installed and activated
if ( !function_exists( 'snowmountain_exists_cf7' ) ) {
	function snowmountain_exists_cf7() {
		return class_exists('WPCF7');
	}
}

// Removing P and BR tags in form content (wpcf7)
if ( ! function_exists( 'snowmountain_wpcf7_autop_or_not' ) ) {
	add_filter( 'wpcf7_autop_or_not', 'snowmountain_wpcf7_autop_or_not' );
	function snowmountain_wpcf7_autop_or_not() {
	return false;
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'snowmountain_cf7_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'snowmountain_cf7_frontend_scripts', 1100 );
	function snowmountain_cf7_frontend_scripts() {
		if (snowmountain_is_on(snowmountain_get_theme_option('debug_mode')) && snowmountain_get_file_dir('plugins/contact-form-7/contact-form-7.css')!='')
			wp_enqueue_style( 'snowmountain-contact-form-7',  snowmountain_get_file_url('plugins/contact-form-7/contact-form-7.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'snowmountain_cf7_merge_styles' ) ) {
	//Handler of the add_filter('snowmountain_filter_merge_styles', 'snowmountain_cf7_merge_styles');
	function snowmountain_cf7_merge_styles($list) {
		$list[] = 'plugins/contact-form-7/contact-form-7.css';
		return $list;
	}
}
?>