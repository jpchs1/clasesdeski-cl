<?php
/* Tribe Events Calendar support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('snowmountain_tribe_events_theme_setup1')) {
	add_action( 'after_setup_theme', 'snowmountain_tribe_events_theme_setup1', 1 );
	function snowmountain_tribe_events_theme_setup1() {
		add_filter( 'snowmountain_filter_list_sidebars', 'snowmountain_tribe_events_list_sidebars' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('snowmountain_tribe_events_theme_setup3')) {
	add_action( 'after_setup_theme', 'snowmountain_tribe_events_theme_setup3', 3 );
	function snowmountain_tribe_events_theme_setup3() {
		if (snowmountain_exists_tribe_events()) {
		
			snowmountain_storage_merge_array('options', '', array(
				// Section 'Tribe Events' - settings for show pages
				'events' => array(
					"title" => esc_html__('Events', 'snowmountain'),
					"desc" => wp_kses_data( __('Select parameters to display the events pages', 'snowmountain') ),
					"type" => "section"
					),
				'expand_content_events' => array(
					"title" => esc_html__('Expand content', 'snowmountain'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'snowmountain') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'header_style_events' => array(
					"title" => esc_html__('Header style', 'snowmountain'),
					"desc" => wp_kses_data( __('Select style to display the site header on the events pages', 'snowmountain') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_position_events' => array(
					"title" => esc_html__('Header position', 'snowmountain'),
					"desc" => wp_kses_data( __('Select position to display the site header on the events pages', 'snowmountain') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_widgets_events' => array(
					"title" => esc_html__('Header widgets', 'snowmountain'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the events pages', 'snowmountain') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_widgets_events' => array(
					"title" => esc_html__('Sidebar widgets', 'snowmountain'),
					"desc" => wp_kses_data( __('Select sidebar to show on the events pages', 'snowmountain') ),
					"std" => 'tribe_events_widgets',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_position_events' => array(
					"title" => esc_html__('Sidebar position', 'snowmountain'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the events pages', 'snowmountain') ),
					"refresh" => false,
					"std" => 'left',
					"options" => array(),
					"type" => "select"
					),
				'hide_sidebar_on_single_events' => array(
					"title" => esc_html__('Hide sidebar on the single event', 'snowmountain'),
					"desc" => wp_kses_data( __("Hide sidebar on the single event's page", 'snowmountain') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_events' => array(
					"title" => esc_html__('Widgets above the page', 'snowmountain'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'snowmountain') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_above_content_events' => array(
					"title" => esc_html__('Widgets above the content', 'snowmountain'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'snowmountain') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_content_events' => array(
					"title" => esc_html__('Widgets below the content', 'snowmountain'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'snowmountain') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_page_events' => array(
					"title" => esc_html__('Widgets below the page', 'snowmountain'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'snowmountain') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'footer_scheme_events' => array(
					"title" => esc_html__('Footer Color Scheme', 'snowmountain'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'snowmountain') ),
					"std" => 'dark',
					"options" => array(),
					"type" => "select"
					),
				'footer_widgets_events' => array(
					"title" => esc_html__('Footer widgets', 'snowmountain'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'snowmountain') ),
					"std" => 'footer_widgets',
					"options" => array(),
					"type" => "select"
					),
				'footer_columns_events' => array(
					"title" => esc_html__('Footer columns', 'snowmountain'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'snowmountain') ),
					"dependency" => array(
						'footer_widgets_events' => array('^hide')
					),
					"std" => 0,
					"options" => snowmountain_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_events' => array(
					"title" => esc_html__('Footer fullwide', 'snowmountain'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'snowmountain') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('snowmountain_tribe_events_theme_setup9')) {
	add_action( 'after_setup_theme', 'snowmountain_tribe_events_theme_setup9', 9 );
	function snowmountain_tribe_events_theme_setup9() {
		
		if (snowmountain_exists_tribe_events()) {
			add_action( 'wp_enqueue_scripts', 								'snowmountain_tribe_events_frontend_scripts', 1100 );
			add_filter( 'snowmountain_filter_merge_styles',						'snowmountain_tribe_events_merge_styles' );
			add_filter( 'snowmountain_filter_post_type_taxonomy',				'snowmountain_tribe_events_post_type_taxonomy', 10, 2 );
			if (!is_admin()) {
				add_filter( 'snowmountain_filter_detect_blog_mode',				'snowmountain_tribe_events_detect_blog_mode' );
				add_filter( 'snowmountain_filter_get_post_categories', 			'snowmountain_tribe_events_get_post_categories');
				add_filter( 'snowmountain_filter_get_post_date',		 			'snowmountain_tribe_events_get_post_date');
			} else {
				add_action( 'admin_enqueue_scripts',						'snowmountain_tribe_events_admin_scripts' );
			}
		}
		if (is_admin()) {
			add_filter( 'snowmountain_filter_tgmpa_required_plugins',			'snowmountain_tribe_events_tgmpa_required_plugins' );
		}

	}
}



// Check if Tribe Events is installed and activated
if ( !function_exists( 'snowmountain_exists_tribe_events' ) ) {
	function snowmountain_exists_tribe_events() {
		return class_exists( 'Tribe__Events__Main' );
	}
}

// Return true, if current page is any tribe_events page
if ( !function_exists( 'snowmountain_is_tribe_events_page' ) ) {
	function snowmountain_is_tribe_events_page() {
		$rez = false;
		if (snowmountain_exists_tribe_events())
			if (!is_search()) $rez = tribe_is_event() || tribe_is_event_query() || tribe_is_event_category() || tribe_is_event_venue() || tribe_is_event_organizer();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'snowmountain_tribe_events_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'snowmountain_filter_detect_blog_mode', 'snowmountain_tribe_events_detect_blog_mode' );
	function snowmountain_tribe_events_detect_blog_mode($mode='') {
		if (snowmountain_is_tribe_events_page())
			$mode = 'events';
		return $mode;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'snowmountain_tribe_events_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'snowmountain_filter_post_type_taxonomy',	'snowmountain_tribe_events_post_type_taxonomy', 10, 2 );
	function snowmountain_tribe_events_post_type_taxonomy($tax='', $post_type='') {
		if (snowmountain_exists_tribe_events() && $post_type == Tribe__Events__Main::POSTTYPE)
			$tax = Tribe__Events__Main::TAXONOMY;
		return $tax;
	}
}

// Show categories of the current event
if ( !function_exists( 'snowmountain_tribe_events_get_post_categories' ) ) {
	//Handler of the add_filter( 'snowmountain_filter_get_post_categories', 		'snowmountain_tribe_events_get_post_categories');
	function snowmountain_tribe_events_get_post_categories($cats='') {
		if (get_post_type() == Tribe__Events__Main::POSTTYPE)
			$cats = snowmountain_get_post_terms(', ', get_the_ID(), Tribe__Events__Main::TAXONOMY);
		return $cats;
	}
}

// Return date of the current event
if ( !function_exists( 'snowmountain_tribe_events_get_post_date' ) ) {
	//Handler of the add_filter( 'snowmountain_filter_get_post_date', 'snowmountain_tribe_events_get_post_date');
	function snowmountain_tribe_events_get_post_date($dt='') {
		if (get_post_type() == Tribe__Events__Main::POSTTYPE) {
			$dt = tribe_get_start_date(null, true, 'Y-m-d');
			$dt = sprintf($dt < date('Y-m-d') 
								? esc_html__('Started on %s', 'snowmountain') 
								: esc_html__('Starting %s', 'snowmountain'),
								date(get_option('date_format'), strtotime($dt)));
		}
		return $dt;
	}
}
	
// Enqueue Tribe Events admin scripts and styles
if ( !function_exists( 'snowmountain_tribe_events_admin_scripts' ) ) {
	//Handler of the add_action( 'admin_enqueue_scripts', 'snowmountain_tribe_events_admin_scripts' );
	function snowmountain_tribe_events_admin_scripts() {
	}
}

// Enqueue Tribe Events custom scripts and styles
if ( !function_exists( 'snowmountain_tribe_events_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'snowmountain_tribe_events_frontend_scripts', 1100 );
	function snowmountain_tribe_events_frontend_scripts() {
		if (snowmountain_is_tribe_events_page()) {
			if (snowmountain_is_on(snowmountain_get_theme_option('debug_mode')) && snowmountain_get_file_dir('plugins/the-events-calendar/the-events-calendar.css')!='')
				wp_enqueue_style( 'snowmountain-the-events-calendar',  snowmountain_get_file_url('plugins/the-events-calendar/the-events-calendar.css'), array(), null );
				wp_enqueue_style( 'snowmountain-the-events-calendar-images',  snowmountain_get_file_url('css/the-events-calendar.css'), array(), null );
		}
	}
}

// Merge custom styles
if ( !function_exists( 'snowmountain_tribe_events_merge_styles' ) ) {
	//Handler of the add_filter('snowmountain_filter_merge_styles', 'snowmountain_tribe_events_merge_styles');
	function snowmountain_tribe_events_merge_styles($list) {
		$list[] = 'plugins/the-events-calendar/the-events-calendar.css';
		$list[] = 'css/the-events-calendar.css';
		return $list;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'snowmountain_tribe_events_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('snowmountain_filter_tgmpa_required_plugins',	'snowmountain_tribe_events_tgmpa_required_plugins');
	function snowmountain_tribe_events_tgmpa_required_plugins($list=array()) {
		if (in_array('the-events-calendar', snowmountain_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Tribe Events Calendar', 'snowmountain'),
					'slug' 		=> 'the-events-calendar',
					'required' 	=> false
				);
		return $list;
	}
}



// Add Tribe Events specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'snowmountain_tribe_events_list_sidebars' ) ) {
	//Handler of the add_filter( 'snowmountain_filter_list_sidebars', 'snowmountain_tribe_events_list_sidebars' );
	function snowmountain_tribe_events_list_sidebars($list=array()) {
		$list['tribe_events_widgets'] = array(
											'name' => esc_html__('Tribe Events Widgets', 'snowmountain'),
											'description' => esc_html__('Widgets to be shown on the Tribe Events pages', 'snowmountain')
											);
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (snowmountain_exists_tribe_events()) { require_once SNOWMOUNTAIN_THEME_DIR . 'plugins/the-events-calendar/the-events-calendar.styles.php'; }
?>