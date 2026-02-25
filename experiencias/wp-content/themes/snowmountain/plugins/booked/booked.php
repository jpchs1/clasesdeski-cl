<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('snowmountain_booked_theme_setup9')) {
	add_action( 'after_setup_theme', 'snowmountain_booked_theme_setup9', 9 );
	function snowmountain_booked_theme_setup9() {
		if (snowmountain_exists_booked()) {
			add_action( 'wp_enqueue_scripts', 							'snowmountain_booked_frontend_scripts', 1100 );
			add_filter( 'snowmountain_filter_merge_styles',					'snowmountain_booked_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins',		'snowmountain_booked_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'snowmountain_exists_booked' ) ) {
	function snowmountain_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'snowmountain_booked_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_booked_tgmpa_required_plugins');
	function snowmountain_booked_tgmpa_required_plugins($list=array()) {
		if (in_array('booked', snowmountain_storage_get('required_plugins'))) {
			$path = snowmountain_get_file_dir('plugins/booked/booked.zip');
			$list[] = array(
					'name' 		=> esc_html__('Booked Appointments', 'snowmountain'),
					'slug' 		=> 'booked',
					'version'   => '2.4',
					'source' 	=> !empty($path) ? $path : 'upload://booked.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'snowmountain_booked_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'snowmountain_booked_frontend_scripts', 1100 );
	function snowmountain_booked_frontend_scripts() {
		if (snowmountain_is_on(snowmountain_get_theme_option('debug_mode')) && snowmountain_get_file_dir('plugins/booked/booked.css')!='')
			wp_enqueue_style( 'snowmountain-booked',  snowmountain_get_file_url('plugins/booked/booked.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'snowmountain_booked_merge_styles' ) ) {
	//Handler of the add_filter('snowmountain_filter_merge_styles', 'snowmountain_booked_merge_styles');
	function snowmountain_booked_merge_styles($list) {
		$list[] = 'plugins/booked/booked.css';
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (snowmountain_exists_booked()) { require_once SNOWMOUNTAIN_THEME_DIR . 'plugins/booked/booked.styles.php'; }
?>