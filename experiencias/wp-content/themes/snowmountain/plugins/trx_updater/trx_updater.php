<?php
/* TRX Updater support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('snowmountain_trx_updater_theme_setup9')) {
	add_action( 'after_setup_theme', 'snowmountain_trx_updater_theme_setup9', 9 );
	function snowmountain_trx_updater_theme_setup9() {

		if (is_admin()) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins',			'snowmountain_trx_updater_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'snowmountain_trx_updater_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_trx_updater_tgmpa_required_plugins');
	function snowmountain_trx_updater_tgmpa_required_plugins($list=array()) {
		if (in_array('trx_updater', snowmountain_storage_get('required_plugins'))) {
			$path = snowmountain_get_file_dir('plugins/trx_updater/trx_updater.zip');
			$list[] = array(
				'name' 		=> esc_html__('ThemeREX Updater', 'snowmountain'),
				'slug' 		=> 'trx_updater',
				'version'	=> '2.0.0',
				'source'	=> !empty($path) ? $path : 'upload://trx_updater.zip',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( !function_exists( 'snowmountain_exists_trx_updater' ) ) {
	function snowmountain_exists_trx_updater() {
		return function_exists( 'trx_updater_load_plugin_textdomain' );
	}
}
