<?php
/* Date & Time Picker support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'snowmountain_date_time_picker_field_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'snowmountain_date_time_picker_field_theme_setup9', 9 );
	function snowmountain_date_time_picker_field_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins', 'snowmountain_date_time_picker_field_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'snowmountain_date_time_picker_field_tgmpa_required_plugins' ) ) {
		//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_wp_gdpr_compliance_tgmpa_required_plugins');
		function snowmountain_date_time_picker_field_tgmpa_required_plugins( $list = array() ) {
			if (in_array('date-time-picker-field', snowmountain_storage_get('required_plugins'))) {
			$list[] = array(
				'name' 		=> esc_html__('Date Time Picker Field', 'snowmountain'),
				'slug'     => 'date-time-picker-field',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'snowmountain_exists_date_time_picker_field' ) ) {
	function snowmountain_exists_date_time_picker_field() {
		return class_exists( 'CMoreira\\Plugins\\DateTimePicker\\Init' );
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'snowmountain_date_time_picker_field_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'snowmountain_date_time_picker_field_importer_set_options' );
	function snowmountain_date_time_picker_field_importer_set_options($options=array()) {
		if ( snowmountain_exists_date_time_picker_field() && in_array('date-time-picker-field', $options['required_plugins']) ) {
			if (is_array($options)) {
				$options['additional_options'][] = 'dtpicker';
			}
		}
		return $options;
	}
}
