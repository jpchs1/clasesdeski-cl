<?php

/* Theme setup section
-------------------------------------------------------------------- */

// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

mounthood_storage_set('settings', array(
	
	'less_compiler'		=> 'no',								// no|lessc|less|external - Compiler for the .less
																// lessc	- fast & low memory required, but .less-map, shadows & gradients not supprted
																// less		- slow, but support all features
																// external	- used if you have external .less compiler (like WinLess or Koala)
																// no		- don't use .less, all styles stored in the theme.styles.php
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_split'		=> false,								// If true - load each file into memory, split it (see below) and compile separate.
																// Else - compile each file without loading to memory
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'no',								// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'add_image_size'	=> false,								// Add theme's thumb sizes into WP list sizes. 
																// If false - new image thumb will be generated on demand,
																// otherwise - all thumb sizes will be generated when image is loaded

	'use_list_cache'	=> true,								// Use cache for any lists (increase theme speed, but get 15-20K memory)
	'use_post_cache'	=> true 								// Use cache for post_data (increase theme speed, decrease queries number, but get more memory - up to 300K)
	)
);



// Default Theme Options
if ( !function_exists( 'mounthood_options_settings_theme_setup' ) ) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_options_settings_theme_setup', 2 );	// Priority 1 for add mounthood_filter handlers
	function mounthood_options_settings_theme_setup() {
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'mounthood_options_reset');

		// Settings 
		$socials_type = mounthood_get_theme_setting('socials_type');
				
		// Prepare arrays 
		mounthood_storage_set('options_params', apply_filters('mounthood_filter_theme_options_params', array(
			'list_fonts'				=> array('$mounthood_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$mounthood_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$mounthood_get_list_socials' => ''),
			'list_icons' 				=> array('$mounthood_get_list_icons(true)' => ''),
			'list_posts_types' 			=> array('$mounthood_get_list_posts_types' => ''),
			'list_categories' 			=> array('$mounthood_get_list_categories' => ''),
			'list_menus'				=> array('$mounthood_get_list_menus(true)' => ''),
			'list_sidebars'				=> array('$mounthood_get_list_sidebars' => ''),
			'list_positions' 			=> array('$mounthood_get_list_sidebars_positions' => ''),
			'list_color_schemes'		=> array('$mounthood_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$mounthood_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$mounthood_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$mounthood_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$mounthood_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$mounthood_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$mounthood_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$mounthood_get_list_blog_counters' => ''),
			'list_menu_hovers' 			=> array('$mounthood_get_list_menu_hovers' => ''),
			'list_button_hovers'		=> array('$mounthood_get_list_button_hovers' => ''),
			'list_input_hovers'			=> array('$mounthood_get_list_input_hovers' => ''),
			'list_search_styles'		=> array('$mounthood_get_list_search_styles' => ''),
			'list_animations_in' 		=> array('$mounthood_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$mounthood_get_list_animations_out' => ''),
			'list_filters'				=> array('$mounthood_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$mounthood_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$mounthood_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$mounthood_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$mounthood_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$mounthood_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$mounthood_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$mounthood_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$mounthood_get_list_yesno' => ''),
			'list_on_off' 				=> array('$mounthood_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$mounthood_get_list_showhide' => ''),
			'list_sorting' 				=> array('$mounthood_get_list_sortings' => ''),
			'list_ordering' 			=> array('$mounthood_get_list_orderings' => ''),
			'list_locations' 			=> array('$mounthood_get_list_dedicated_locations' => '')
			)
		));


		// Theme options array
		mounthood_storage_set('options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'mounthood'),
					"start" => "partitions",
					"override" => "category,services_group,post,page,custom",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'mounthood'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Select body style and color scheme for entire site. You can override this parameters on any page, post or category', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'mounthood'),
					"desc" => wp_kses_data( __('Add paddings above and below the page content', 'mounthood') ),
					"override" => "post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'mounthood'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the entire page', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => mounthood_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'mounthood'),
					"desc" => wp_kses_data( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'mounthood'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'mounthood'),
					"desc" => wp_kses_data( __('Top panel settings.', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'mounthood'),
					"desc" => wp_kses_data( __('Select desired style of the page header', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "header_6",
					"options" => mounthood_get_options_param('list_header_styles'),
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'mounthood'),
					"desc" => wp_kses_data( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'mounthood'),
					"desc" => wp_kses_data( __('Select position for the top panel with logo and main menu', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'mounthood'),
						'above' => esc_html__('Above slider', 'mounthood'),
						'below' => esc_html__('Below slider', 'mounthood'),
						'over'  => esc_html__('Over slider', 'mounthood')
					),
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'mounthood'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => mounthood_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'mounthood'),
					"desc" => wp_kses_data( __('Show post/page/category title', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'mounthood'),
					"desc" => wp_kses_data( __('Show path to current category (post, page)', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'mounthood'),
					"desc" => wp_kses_data( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'mounthood') ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'mounthood'),
					"desc" => wp_kses_data( __('Select the Main menu style and position', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'mounthood'),
					"desc" => wp_kses_data( __('Select main menu for the current page',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"options" => mounthood_get_options_param('list_menus'),
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'mounthood'),
					"desc" => wp_kses_data( __('Attach main menu to top of window then page scroll down', 'mounthood') ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'mounthood'), 
						"none"=>esc_html__("Don't fix menu position", 'mounthood')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_hover" => array( 
					"title" => esc_html__('Main menu hover effect', 'mounthood'),
					"desc" => wp_kses_data( __('Select hover effect for the main menu items', 'mounthood') ),
					"std" => "slide_line",
					"type" => "select",
					"options" => mounthood_get_options_param('list_menu_hovers')),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'mounthood'),
					"desc" => wp_kses_data( __('Select animation to show submenu ', 'mounthood') ),
					"std" => "fadeIn",
					"type" => "select",
					"options" => mounthood_get_options_param('list_animations_in')),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'mounthood'),
					"desc" => wp_kses_data( __('Select animation to hide submenu ', 'mounthood') ),
					"std" => "fadeOut",
					"type" => "select",
					"options" => mounthood_get_options_param('list_animations_out')),
		
		"menu_mobile" => array( 
					"title" => esc_html__('Main menu responsive', 'mounthood'),
					"desc" => wp_kses_data( __('Allow responsive version for the main menu if window width less then this value', 'mounthood') ),
					"std" => 1024,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'mounthood'),
					"desc" => wp_kses_data( __('Width for dropdown menus in main menu', 'mounthood') ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'mounthood'),
					"desc" => wp_kses_data( __("Select or upload logos for the site's header and select it position", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'mounthood'),
					"desc" => wp_kses_data( __('Main logo image', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'mounthood'),
					"desc" => wp_kses_data( __('Main logo image used on Retina display', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'mounthood'),
					"desc" => wp_kses_data( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'mounthood'),
					"desc" => wp_kses_data( __('Logo text - display it after logo image', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'mounthood'),
					"desc" => wp_kses_data( __('Height for the logo in the header area', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'mounthood'),
					"desc" => wp_kses_data( __('Top offset for the logo in the header area', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),

		'info_header_6' => array(
					"title" => esc_html__('Additional button', 'mounthood'),
					"desc" => wp_kses_data( __("Select or upload image for the site's header additional button", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'additional_button' => array(
					"title" => esc_html__('Button image', 'mounthood'),
					"desc" => wp_kses_data( __('Additional button image', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'additional_button_url' => array(
					"title" => esc_html__('Button link', 'mounthood'),
					"desc" => wp_kses_data( __('Additional button link', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => '',
					"type" => "text"
					),

		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'mounthood'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Select parameters for main slider (you can override it in each category and page)', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'mounthood'),
					"desc" => wp_kses_data( __('Do you want to show slider on each page (post)', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'mounthood'),
					"desc" => wp_kses_data( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'mounthood'),
						"fullwide"=>esc_html__("Fullwide", 'mounthood'),
						"fullscreen"=>esc_html__("Fullscreen", 'mounthood')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'mounthood'),
					"desc" => wp_kses_data( __("Slider height (in pixels) - only if slider display with fixed height.", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'mounthood'),
					"desc" => wp_kses_data( __('What engine use to show slider?', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => mounthood_get_options_param('list_sliders'),
					"type" => "radio"),

		"slider_over_content" => array(
					"title" => esc_html__('Put content over slider',  'mounthood'),
					"desc" => wp_kses_data( __('Put content below on fixed layer over this slider',  'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "editor"),

		"slider_over_scheme" => array(
					"title" => esc_html__('Color scheme for content above', 'mounthood'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the content over the slider', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "dark",
					"dir" => "horizontal",
					"options" => mounthood_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'mounthood'),
					"desc" => wp_kses_data( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => mounthood_array_merge(array(0 => esc_html__('- Select category -', 'mounthood')), mounthood_get_options_param('list_categories')),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'mounthood'),
					"desc" => wp_kses_data( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'mounthood'),
					"desc" => wp_kses_data( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => mounthood_get_options_param('list_sorting'),
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'mounthood'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => mounthood_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'mounthood'),
					"desc" => wp_kses_data( __("Interval (in ms) for slides change in slider", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'mounthood'),
					"desc" => wp_kses_data( __("Choose pagination style for the slider", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'mounthood'),
						'yes'  => esc_html__('Dots', 'mounthood'), 
						'over' => esc_html__('Titles', 'mounthood')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'mounthood'),
					"desc" => wp_kses_data( __("Do you want to show post's title, reviews rating and description on slides in slider", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'mounthood'),
						'slide' => esc_html__('Slide', 'mounthood'), 
						'fixed' => esc_html__('Fixed', 'mounthood')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'mounthood'),
					"desc" => wp_kses_data( __("Do you want to show post's category on slides in slider", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'mounthood'),
					"desc" => wp_kses_data( __("Do you want to show post's reviews rating on slides in slider", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'mounthood'),
					"desc" => wp_kses_data( __("How many characters show in the post's description in slider. 0 - no descriptions", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'mounthood'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'mounthood'),
					"desc" => wp_kses_data( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'mounthood') ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'mounthood'),
					"desc" => wp_kses_data( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'mounthood') ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'mounthood'),
					"desc" => wp_kses_data( __('Show / Hide and select main sidebar', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'mounthood'),
					"desc" => wp_kses_data( __('Select position for the main sidebar or hide it',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "right",
					"options" => mounthood_get_options_param('list_positions'),
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'mounthood'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the main sidebar', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => mounthood_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'mounthood'),
					"desc" => wp_kses_data( __('Select main sidebar content',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => mounthood_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"menu_side" => array(
					"title" => esc_html__('Select menu',  'mounthood'),
					"desc" => wp_kses_data( __('Select menu for the outer sidebar',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right'),
						'sidebar_outer_show_menu' => array('yes')
					),
					"std" => "default",
					"options" => mounthood_get_options_param('list_menus'),
					"type" => "select"),
		
		"sidebar_outer_show_widgets" => array( 
					"title" => esc_html__('Show Widgets', 'mounthood'),
					"desc" => wp_kses_data( __('Show Widgets in the outer sidebar', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_outer" => array( 
					"title" => esc_html__('Select outer sidebar',  'mounthood'),
					"desc" => wp_kses_data( __('Select outer sidebar content',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'sidebar_outer_show_widgets' => array('yes'),
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "sidebar_outer",
					"options" => mounthood_get_options_param('list_sidebars'),
					"type" => "select"),
		
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'mounthood'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'mounthood'),
					"desc" => wp_kses_data( __("Select components of the footer, set style and put the content for the user's footer area", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'mounthood'),
					"desc" => wp_kses_data( __('Select style for the footer sidebar or hide it', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'mounthood'),
					"desc" => wp_kses_data( __('Select footer sidebar for the blog page',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => mounthood_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'mounthood'),
					"desc" => wp_kses_data( __('Select columns number for the footer sidebar',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'mounthood'),
					"desc" => wp_kses_data( __("Show/Hide copyright area in the footer", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'mounthood'),
					"desc" => wp_kses_data( __('Show area with copyright information, footer menu and small social icons in footer', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'mounthood'),
						'menu' => esc_html__('Text and menu', 'mounthood')
					),
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'mounthood'),
					"desc" => wp_kses_data( __('Select footer menu for the current page',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "footer-menu",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => mounthood_get_options_param('list_menus'),
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'mounthood'),
					"desc" => wp_kses_data( __("Copyright text to show in footer area (bottom of site)", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "AxiomThemes &copy; {Y} All Rights Reserved",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'mounthood'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Animation parameters and responsive layouts for the small screens', 'mounthood') ),
					"type" => "info"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'mounthood'),
					"desc" => wp_kses_data( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'mounthood') ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'mounthood'),
					"desc" => wp_kses_data( __('Do you want use extended animations effects on your site?', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'animation_on_mobile' => array(
					"title" => esc_html__('Allow CSS animations on mobile', 'mounthood'),
					"desc" => wp_kses_data( __('Do you allow extended animations effects on mobile devices?', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"button_hover" => array( 
					"title" => esc_html__("Buttons hover", 'mounthood'),
					"desc" => wp_kses_data( __("Select hover effect for all theme's buttons (and buttons from the thirdparty plugins if possible)", 'mounthood') ),
					"std" => "fade",
					"type" => "select",
					"options" => mounthood_get_options_param('list_button_hovers')),

		"input_hover" => array( 
					"title" => esc_html__("Input fileds style", 'mounthood'),
					"desc" => wp_kses_data( __("Select style for all theme's input fields (and fields from the thirdparty plugins if possible)", 'mounthood') ),
					"std" => "default",
					"type" => "select",
					"options" => mounthood_get_options_param('list_input_hovers')),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'mounthood'),
					"desc" => wp_kses_data( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'mounthood') ),
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'mounthood'),
					"desc" => wp_kses_data( __('Do you want use responsive layouts on small screen or still use main layout?', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"page_preloader" => array( 
					"title" => esc_html__("Show page preloader", 'mounthood'),
					"desc" => wp_kses_data( __("Select one of predefined styles for the page preloader or upload preloader image", 'mounthood') ),
					"std" => "none",
					"type" => "select",
					"options" => array(
						'none'   => esc_html__('Hide preloader', 'mounthood'),
						'circle' => esc_html__('Circle', 'mounthood'),
						'square' => esc_html__('Square', 'mounthood'),
						'custom' => esc_html__('Custom', 'mounthood'),
					)),

        'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'mounthood'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'mounthood') ),
                "std"   => wp_kses_post( __( 'I agree that my submitted data is being collected and stored.', 'mounthood') ),
                "type"  => "text"
            ),


            'page_preloader_image' => array(
					"title" => esc_html__('Upload preloader image',  'mounthood'),
					"desc" => wp_kses_data( __('Upload animated GIF to use it as page preloader',  'mounthood') ),
					"dependency" => array(
						'page_preloader' => array('custom')
					),
					"std" => "",
					"type" => "media"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Specify additional parameters, used to load Google fonts', 'mounthood') ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'mounthood'),
					"desc" => wp_kses_data( __('Select subset, included into used Google fonts', 'mounthood') ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'mounthood'),
						'latin-ext' => esc_html__('Latin Extended', 'mounthood'),
						'greek' => esc_html__('Greek', 'mounthood'),
						'greek-ext' => esc_html__('Greek Extended', 'mounthood'),
						'cyrillic' => esc_html__('Cyrillic', 'mounthood'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'mounthood'),
						'vietnamese' => esc_html__('Vietnamese', 'mounthood')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'mounthood'),
					"desc" => wp_kses_data( __('Put here your custom CSS and JS code', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'mounthood'),
					"desc" => wp_kses_data( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'mounthood') ),
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'mounthood'),
					"desc" => wp_kses_data( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'mounthood') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'mounthood'),
					"desc" => wp_kses_data( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'mounthood') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'mounthood'),
					"desc" => wp_kses_data( __('Put here your invisible html/js code: Google analitics, counters, etc',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'mounthood'),
					"desc" => wp_kses_data( __('Put here your css code to correct main theme styles',  'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'mounthood'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'mounthood'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Select desired blog streampage parameters (you can override it in each category)', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'mounthood'),
					"desc" => wp_kses_data( __('Select desired blog style', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "excerpt",
					"options" => mounthood_get_options_param('list_blog_styles'),
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'mounthood'),
					"desc" => wp_kses_data( __('Select desired hover style (only for Blog style = Portfolio)', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => mounthood_get_options_param('list_hovers'),
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'mounthood'),
					"desc" => wp_kses_data( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => mounthood_get_options_param('list_hovers_dir'),
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'mounthood'),
					"desc" => wp_kses_data( __('Select article display method: boxed or stretch', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "stretch",
					"options" => mounthood_get_options_param('list_article_styles'),
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'mounthood'),
					"desc" => wp_kses_data( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => mounthood_get_options_param('list_locations'),
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'mounthood'),
					"desc" => wp_kses_data( __('What taxonomy use for filter buttons', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => mounthood_get_options_param('list_filters'),
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'mounthood'),
					"desc" => wp_kses_data( __('Select the desired sorting method for posts', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "date",
					"options" => mounthood_get_options_param('list_sorting'),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'mounthood'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "desc",
					"options" => mounthood_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'mounthood'),
					"desc" => wp_kses_data( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'mounthood'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'mounthood'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'mounthood'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'mounthood'),
					"desc" => wp_kses_data( __('Select desired style for single page', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "single-standard",
					"options" => mounthood_get_options_param('list_single_styles'),
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'mounthood'),
					"desc" => wp_kses_data( __('Select icon for output before post/category name in some layouts', 'mounthood') ),
					"override" => "services_group,post,page,custom",
					"std" => "",
					"options" => mounthood_get_options_param('list_icons'),
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'mounthood'),
					"override" => "page,post",
					"desc" => wp_kses_data( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'mounthood') ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => mounthood_get_options_param('list_alter_sizes')
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'mounthood'),
					"desc" => wp_kses_data( __("Show featured image (if selected) before post content on single pages", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'mounthood'),
					"desc" => wp_kses_data( __('Show area with post title on single pages', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'mounthood'),
					"desc" => wp_kses_data( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'mounthood'),
					"desc" => wp_kses_data( __('Show area with post info on single pages', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'mounthood'),
					"desc" => wp_kses_data( __('Show text before "Read more" tag on single pages', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'mounthood'),
					"desc" => wp_kses_data( __("Show post author information block on single post page", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'mounthood'),
					"desc" => wp_kses_data( __("Show tags block on single post page", 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'mounthood'),
					"desc" => wp_kses_data( __("Show related posts block on single post page", 'mounthood') ),
					"override" => "category,services_group,post,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'mounthood'),
					"desc" => wp_kses_data( __("How many related posts showed on single post page", 'mounthood') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,custom",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'mounthood'),
					"desc" => wp_kses_data( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'mounthood') ),
					"override" => "category,services_group,post,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 2,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'mounthood'),
					"desc" => wp_kses_data( __('Select the desired sorting method for related posts', 'mounthood') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => mounthood_get_options_param('list_sorting'),
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'mounthood'),
					"desc" => wp_kses_data( __('Select the desired ordering method for related posts', 'mounthood') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => mounthood_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'mounthood'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Select excluded categories, substitute parameters, etc.', 'mounthood') ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'mounthood'),
					"desc" => wp_kses_data( __('Select categories, which posts are exclude from blog page', 'mounthood') ),
					"std" => "",
					"options" => mounthood_get_options_param('list_categories'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'mounthood'),
					"desc" => wp_kses_data( __('Select type of the pagination on blog streampages', 'mounthood') ),
					"std" => "pages",
					"override" => "category,services_group,page,custom",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'mounthood'),
						'slider'   => esc_html__('Slider with page numbers', 'mounthood'),
						'viewmore' => esc_html__('"View more" button', 'mounthood'),
						'infinite' => esc_html__('Infinite scroll', 'mounthood')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'mounthood'),
					"desc" => wp_kses_data( __('Select counters, displayed near the post title', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "views",
					"options" => mounthood_get_options_param('list_blog_counters'),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'mounthood'),
					"desc" => wp_kses_data( __('What category display in announce block (over posts thumb) - original or nearest parental', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'mounthood'),
						'original' => esc_html__("Original post's category", 'mounthood')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'mounthood'),
					"desc" => wp_kses_data( __('Show post date after N days (before - show post age)', 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'mounthood'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'mounthood'),
					"desc" => wp_kses_data( __('Set up list of reviews criterias. You can override it in any category.', 'mounthood') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'mounthood'),
					"desc" => wp_kses_data( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'mounthood') ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'mounthood'),
					"desc" => wp_kses_data( __("Maximum level for reviews marks", 'mounthood') ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'mounthood'), 
						'10'=>esc_html__('10 stars', 'mounthood'), 
						'100'=>esc_html__('100%', 'mounthood')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'mounthood'),
					"desc" => wp_kses_data( __("Show rating marks as text or as stars/progress bars.", 'mounthood') ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'mounthood'), 
						'stars' => esc_html__('As stars or bars', 'mounthood')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'mounthood'),
					"desc" => wp_kses_data( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'mounthood') ),
					"std" => esc_html__("bad,poor,normal,good,great", 'mounthood'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'mounthood'),
					"desc" => wp_kses_data( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'mounthood') ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'mounthood'),
						'users' => esc_html__('By visitors', 'mounthood')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'mounthood'),
					"desc" => wp_kses_data( __("Do you want hide second reviews tab in widgets and single posts?", 'mounthood') ),
					"std" => "show",
					"options" => mounthood_get_options_param('list_show_hide'),
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'mounthood'),
					"desc" => wp_kses_data( __("What visitors can vote: all or only registered", 'mounthood') ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'mounthood'), 
						'registered'=>esc_html__('Only registered', 'mounthood')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'mounthood'),
					"desc" => wp_kses_data( __('Add default reviews criterias.',  'mounthood') ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'mounthood'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'mounthood'),
					"desc" => wp_kses_data( __('Set up parameters to show images, galleries, audio and video posts', 'mounthood') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'mounthood'),
					"desc" => wp_kses_data( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'mounthood') ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'mounthood'), 
						"2" => esc_html__("Retina", 'mounthood')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'mounthood'),
					"desc" => wp_kses_data( __('Quality (1-100) to save cropped images', 'mounthood') ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'mounthood'),
					"desc" => wp_kses_data( __('Substitute standard Wordpress gallery with our slider on the single pages', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'mounthood'),
					"desc" => wp_kses_data( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'mounthood'),
					"desc" => wp_kses_data( __('Maximum images number from gallery into slider', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'mounthood'),
					"desc" => wp_kses_data( __('Select engine to show popup windows with images and galleries', 'mounthood') ),
					"std" => "magnific",
					"options" => mounthood_get_options_param('list_popups'),
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'mounthood'),
					"desc" => wp_kses_data( __('Substitute audio tag with source from soundcloud to embed player', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'mounthood'),
					"desc" => wp_kses_data( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'mounthood') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'mounthood'),
					"desc" => wp_kses_data( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'mounthood'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page,post,custom",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'mounthood'),
					"desc" => wp_kses_data( __("Social networks list for site footer and Social widget", 'mounthood') ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'mounthood'),
					"desc" => wp_kses_data( __('Select icon and write URL to your profile in desired social networks.',  'mounthood') ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? mounthood_get_options_param('list_socials') : mounthood_get_options_param('list_icons'),
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'mounthood'),
					"desc" => wp_kses_data( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'mounthood'),
					"desc" => wp_kses_data( __("Show social share buttons block", 'mounthood') ),
					"override" => "category,services_group,page,post,custom",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'mounthood'),
						'vertical'	=> esc_html__('Vertical', 'mounthood'),
						'horizontal'=> esc_html__('Horizontal', 'mounthood')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'mounthood'),
					"desc" => wp_kses_data( __("Show share counters after social buttons", 'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'mounthood'),
					"desc" => wp_kses_data( __('Caption for the block with social share buttons',  'mounthood') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'mounthood'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'mounthood'),
					"desc" => wp_kses_data( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'mounthood') ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? mounthood_get_options_param('list_socials') : mounthood_get_options_param('list_icons'),
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'mounthood'),
					"desc" => wp_kses_data( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'mounthood') ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'mounthood'),
					"desc" => wp_kses_data( __('Your login (username) in Twitter',  'mounthood') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'mounthood'),
					"desc" => wp_kses_data( __('Twitter API Consumer key',  'mounthood') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'mounthood'),
					"desc" => wp_kses_data( __('Twitter API Consumer secret',  'mounthood') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'mounthood'),
					"desc" => wp_kses_data( __('Twitter API Token key',  'mounthood') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'mounthood'),
					"desc" => wp_kses_data( __('Twitter API Token secret',  'mounthood') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"info_socials_4" => array(
					"title" => esc_html__('Google API Keys', 'mounthood'),
					"desc" => wp_kses_data( __('API Keys for some Web services', 'mounthood') ),
					"type" => "info"),
		'api_google' => array(
					"title" => esc_html__('Google API Key for browsers', 'mounthood'),
					"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'mounthood') ),
					"std" => "",
					"type" => "text"),
		
		"info_socials_5" => array(
					"title" => esc_html__('Login via Socials', 'mounthood'),
					"desc" => wp_kses_data( __('Settings for the Login via Social networks', 'mounthood') ),
					"type" => "info"),
		
		"social_login" => array(
					"title" => esc_html__('Shortcode or any HTML/JS code',  'mounthood'),
					"desc" => wp_kses_data( __('Specify shortcode from your Social Login Plugin or any HTML/JS code to make Social Login section',  'mounthood') ),
					"std" => "",
					"type" => "textarea"),
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'mounthood'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'mounthood'),
					"desc" => wp_kses_data( __('Company address, phones and e-mail', 'mounthood') ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'mounthood'),
					"desc" => wp_kses_data( __('String with contact info in the left side of the site header', 'mounthood') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'mounthood'),
					"desc" => wp_kses_data( __('String with open hours in the site header', 'mounthood') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'mounthood'),
					"desc" => wp_kses_data( __('E-mail for send contact form and user registration data', 'mounthood') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'mounthood'),
					"desc" => wp_kses_data( __('Company country, post code and city', 'mounthood') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'mounthood'),
					"desc" => wp_kses_data( __('Street and house number', 'mounthood') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'mounthood'),
					"desc" => wp_kses_data( __('Phone number', 'mounthood') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'mounthood'),
					"desc" => wp_kses_data( __('Fax number', 'mounthood') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'mounthood'),
					"desc" => wp_kses_data( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'mounthood') ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'mounthood'),
					"desc" => wp_kses_data( __("Message's maxlength in the contact form shortcode", 'mounthood') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'mounthood'),
					"desc" => wp_kses_data( __("Message's maxlength in the comments form", 'mounthood') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'mounthood'),
					"desc" => wp_kses_data( __('What function use to send mail: the built-in Wordpress wp_mail or standard PHP mail function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'mounthood') ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'mounthood'),
					"desc" => wp_kses_data( __("What function use to send mail? Attention! Only wp_mail support attachment in the mail!", 'mounthood') ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'mounthood'),
						'mail' => esc_html__('PHP mail', 'mounthood')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'mounthood'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'mounthood'),
					"desc" => wp_kses_data( __('Enable/disable AJAX search and output settings for it', 'mounthood') ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'mounthood'),
					"desc" => wp_kses_data( __('Show search field in the top area and side menus', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"search_style" => array( 
					"title" => esc_html__('Select search style', 'mounthood'),
					"desc" => wp_kses_data( __('Select style for the search field', 'mounthood') ),
					"std" => "default",
					"type" => "select",
					"options" => mounthood_get_options_param('list_search_styles')),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'mounthood'),
					"desc" => wp_kses_data( __('Use incremental AJAX search for the search field in top of page', 'mounthood') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand')
					),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'mounthood'),
					"desc" => wp_kses_data( __('The minimum length of the search string',  'mounthood') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'mounthood'),
					"desc" => wp_kses_data( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'mounthood') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'mounthood'),
					"desc" => wp_kses_data( __('Select post types, what will be include in search results. If not selected - use all types.', 'mounthood') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => mounthood_get_options_param('list_posts_types'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'mounthood'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __('Number of the posts to show in search results',  'mounthood') ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'mounthood'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's thumbnail in the search results", 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'mounthood'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's publish date in the search results", 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'mounthood'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's author in the search results", 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'mounthood'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's counters (views, comments, likes) in the search results", 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'mounthood'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'mounthood'),
					"desc" => wp_kses_data( __('Basic theme functionality settings', 'mounthood') ),
					"type" => "info"),

		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'mounthood'),
					"desc" => wp_kses_data( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'mounthood') ),
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'mounthood'),
					"desc" => wp_kses_data( __("Allow authors to edit their posts in frontend area", 'mounthood') ),
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'mounthood'),
					"desc" => wp_kses_data( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'mounthood') ),
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'mounthood'),
					"desc" => wp_kses_data( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'mounthood'),
					"desc" => wp_kses_data( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'mounthood'),
					"desc" => wp_kses_data( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'mounthood') ),
					"std" => "yes",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'mounthood'),
					"desc" => wp_kses_data( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'mounthood') ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'mounthood'),
					"desc" => wp_kses_data( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'mounthood') ),
					"std" => "no",
					"options" => mounthood_get_options_param('list_yes_no'),
					"type" => "switch")
		));

	}
}


// Update all temporary vars (start with $mounthood_) in the Theme Options with actual lists
if ( !function_exists( 'mounthood_options_settings_theme_setup2' ) ) {
	add_action( 'mounthood_action_after_init_theme', 'mounthood_options_settings_theme_setup2', 1 );
	function mounthood_options_settings_theme_setup2() {
		if (mounthood_options_is_used()) {
			// Replace arrays with actual parameters
			$lists = array();
			$tmp = mounthood_storage_get('options');
			if (is_array($tmp) && count($tmp) > 0) {
				$prefix = '$mounthood_';
				$prefix_len = mounthood_strlen($prefix);
				foreach ($tmp as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (mounthood_substr($k1, 0, $prefix_len) == $prefix || mounthood_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = mounthood_substr(mounthood_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								$inherit = strpos($list_func, '(true)')!==false;
								$list_func = str_replace('(true)', '', $list_func);
								unset($tmp[$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$tmp[$k]['options'] = mounthood_array_merge($tmp[$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$tmp[$k]['options'] = $lists[$list_func] = mounthood_array_merge($tmp[$k]['options'], $list_func($inherit));
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'mounthood'), $list_func));
								}
							}
						}
					}
				}
				mounthood_storage_set('options', $tmp);
			}
		}
	}
}



// Reset old Theme Options while theme first run
if ( !function_exists( 'mounthood_options_reset' ) ) {
	//Handler of add_action('after_switch_theme', 'mounthood_options_reset');
	function mounthood_options_reset($clear=true) {
		$theme_slug = str_replace(' ', '_', trim(mounthood_strtolower(get_stylesheet())));
		$option_name = mounthood_storage_get('options_prefix') . '_' . trim($theme_slug) . '_options_reset';
		if ( get_option($option_name, false) === false ) {
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query( $wpdb->prepare(
										"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
										mounthood_storage_get('options_prefix').'_%'
										)
							);
				// Add Templates Options
				$txt = mounthood_fgc(mounthood_storage_get('demo_data_url') . 'default/templates_options.txt');
				if (!empty($txt)) {
					$data = mounthood_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = mounthood_replace_uploads_url(mounthood_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>