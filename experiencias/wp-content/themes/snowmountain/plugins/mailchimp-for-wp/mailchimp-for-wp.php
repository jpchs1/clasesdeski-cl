<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('snowmountain_mailchimp_theme_setup9')) {
	add_action( 'after_setup_theme', 'snowmountain_mailchimp_theme_setup9', 9 );
	function snowmountain_mailchimp_theme_setup9() {
		if (snowmountain_exists_mailchimp()) {
			add_action( 'wp_enqueue_scripts',							'snowmountain_mailchimp_frontend_scripts', 1100 );
			add_filter( 'snowmountain_filter_merge_styles',					'snowmountain_mailchimp_merge_styles');
		}
		if (is_admin()) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins',		'snowmountain_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'snowmountain_exists_mailchimp' ) ) {
	function snowmountain_exists_mailchimp() {
		return function_exists('__mc4wp_load_plugin') || defined('MC4WP_VERSION');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'snowmountain_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_mailchimp_tgmpa_required_plugins');
	function snowmountain_mailchimp_tgmpa_required_plugins($list=array()) {
		if (in_array('mailchimp-for-wp', snowmountain_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'snowmountain'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue custom styles
if ( !function_exists( 'snowmountain_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'snowmountain_mailchimp_frontend_scripts', 1100 );
	function snowmountain_mailchimp_frontend_scripts() {
		if (snowmountain_exists_mailchimp()) {
			if (snowmountain_is_on(snowmountain_get_theme_option('debug_mode')) && snowmountain_get_file_dir('plugins/mailchimp-for-wp/mailchimp-for-wp.css')!='')
				wp_enqueue_style( 'snowmountain-mailchimp-for-wp',  snowmountain_get_file_url('plugins/mailchimp-for-wp/mailchimp-for-wp.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'snowmountain_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'snowmountain_filter_merge_styles', 'snowmountain_mailchimp_merge_styles');
	function snowmountain_mailchimp_merge_styles($list) {
		$list[] = 'plugins/mailchimp-for-wp/mailchimp-for-wp.css';
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (snowmountain_exists_mailchimp()) { require_once SNOWMOUNTAIN_THEME_DIR . 'plugins/mailchimp-for-wp/mailchimp-for-wp.styles.php'; }
?>