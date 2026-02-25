<?php
/* WP-Cloudy support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('mounthood_wpcloudy_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_wpcloudy_theme_setup', 1 );
	function mounthood_wpcloudy_theme_setup() {
		if (is_admin()) {
			add_filter( 'mounthood_filter_importer_required_plugins',		'mounthood_wpcloudy_importer_required_plugins', 10, 2 );
			add_filter( 'mounthood_filter_required_plugins',					'mounthood_wpcloudy_required_plugins' );
		}
	}
}

// Check if WP-Cloudy installed and activated
if ( !function_exists( 'mounthood_exists_wpcloudy' ) ) {
	function mounthood_exists_wpcloudy() {
		return function_exists('weather_activation');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'mounthood_wpcloudy_required_plugins' ) ) {
	//Handler of add_filter('mounthood_filter_required_plugins',	'mounthood_wpcloudy_required_plugins');
	function mounthood_wpcloudy_required_plugins($list=array()) {
		if (in_array('wpcloudy', mounthood_storage_get('required_plugins'))){
				$list[] = array(
					'name' 		=> 'WP-Cloudy',
					'slug' 		=> 'wp-cloudy',
					'required' 	=> false
				);
		}
		return $list;
	}
}


// Check WP-Cloudy in the required plugins
if ( !function_exists( 'mounthood_wpcloudy_importer_required_plugins' ) ) {
	//Handler of add_filter( 'mounthood_filter_importer_required_plugins',	'mounthood_wpcloudy_importer_required_plugins', 10, 2 );
	function mounthood_wpcloudy_importer_required_plugins($not_installed='', $list='') {
		if (in_array('wpcloudy', mounthood_storage_get('required_plugins')) && !mounthood_exists_wpcloudy() )
		if (mounthood_strpos($list, 'wpcloudy')!==false && !mounthood_exists_wpcloudy() )
			$not_installed .= '<br>WP Cloudy';
		return $not_installed;
	}
}
?>