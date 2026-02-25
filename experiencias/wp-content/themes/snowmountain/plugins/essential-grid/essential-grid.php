<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('snowmountain_essential_grid_theme_setup9')) {
	add_action( 'after_setup_theme', 'snowmountain_essential_grid_theme_setup9', 9 );
	function snowmountain_essential_grid_theme_setup9() {
		if (snowmountain_exists_essential_grid()) {
			add_action( 'wp_enqueue_scripts', 							'snowmountain_essential_grid_frontend_scripts', 1100 );
			add_filter( 'snowmountain_filter_merge_styles',					'snowmountain_essential_grid_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins',		'snowmountain_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'snowmountain_exists_essential_grid' ) ) {
	function snowmountain_exists_essential_grid() {
		return defined('EG_PLUGIN_PATH') || defined( 'ESG_PLUGIN_PATH' );
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'snowmountain_essential_grid_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_essential_grid_tgmpa_required_plugins');
	function snowmountain_essential_grid_tgmpa_required_plugins($list=array()) {
		if (in_array('essential-grid', snowmountain_storage_get('required_plugins'))) {
			$path = snowmountain_get_file_dir('plugins/essential-grid/essential-grid.zip');
			$list[] = array(
						'name' 		=> esc_html__('Essential Grid', 'snowmountain'),
						'slug' 		=> 'essential-grid',
						'version'   => '3.0.16',
						'source'	=> !empty($path) ? $path : 'upload://essential-grid.zip',
						'required' 	=> false
			);
		}
		return $list;
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'snowmountain_essential_grid_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'snowmountain_essential_grid_frontend_scripts', 1100 );
	function snowmountain_essential_grid_frontend_scripts() {
		if (snowmountain_is_on(snowmountain_get_theme_option('debug_mode')) && snowmountain_get_file_dir('plugins/essential-grid/essential-grid.css')!='')
			wp_enqueue_style( 'snowmountain-essential-grid',  snowmountain_get_file_url('plugins/essential-grid/essential-grid.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'snowmountain_essential_grid_merge_styles' ) ) {
	//Handler of the add_filter('snowmountain_filter_merge_styles', 'snowmountain_essential_grid_merge_styles');
	function snowmountain_essential_grid_merge_styles($list) {
		$list[] = 'plugins/essential-grid/essential-grid.css';
		return $list;
	}
}
?>