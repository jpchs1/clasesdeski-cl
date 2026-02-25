<?php
/**
 * Default Theme Options and Internal Theme Settings
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

// Theme init priorities:
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)

if ( !function_exists('snowmountain_options_theme_setup1') ) {
	add_action( 'after_setup_theme', 'snowmountain_options_theme_setup1', 1 );
	function snowmountain_options_theme_setup1() {
		
		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		snowmountain_storage_set('settings', array(
			
			'disable_jquery_ui'		=> false,		// Prevent loading custom jQuery UI libraries in the third-party plugins
		
			'max_load_fonts'		=> 3,			// Max fonts number to load from Google fonts or from uploaded fonts
		
			'use_mediaelements'		=> true,		// Load script "Media Elements" to play video and audio
		
			'max_excerpt_length'	=> 60,			// Max words number for the excerpt in the blog style 'Excerpt'.
													// For style 'Classic' - get half from this value
			'comment_maxlength'		=> 1000,		// Max length of the message from contact form

			'comment_after_name'	=> true			// Place 'comment' field before the 'name' and 'email'
			
		));
	}
}


// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('snowmountain_options_create')) {

	function snowmountain_options_create() {

		snowmountain_storage_set('options', array(
		
			// Section 'Title & Tagline' - add theme options in the standard WP section
			'title_tagline' => array(
				"title" => esc_html__('Title, Tagline & Site icon', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify site title and tagline (if need) and upload the site icon', 'snowmountain') ),
				"type" => "section"
				),
		
		
			// Section 'Header' - add theme options in the standard WP section
			'header_image' => array(
				"title" => esc_html__('Header', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload logo images, select header type and widgets set for the header', 'snowmountain') )
							. '<br>'
							. wp_kses_data( __('<b>Attention!</b> Some of these options can be overridden in the following sections (Homepage, Blog archive, Shop, Events, etc.) or in the settings of individual pages', 'snowmountain') ),
				"type" => "section"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'snowmountain'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'header_style' => array(
				"title" => esc_html__('Header style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select style to display the site header', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => 'header-default',
				"options" => array(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'snowmountain'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => 'default',
				"options" => array(),
				"type" => "select"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'snowmountain'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'snowmountain') ),
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'snowmountain'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"dependency" => array(
					'header_style' => array('header-default'),
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => snowmountain_get_list_range(0,6),
				"type" => "select"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'snowmountain'),
				"desc" => wp_kses_data( __('Select color scheme to decorate header area', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => "select"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'snowmountain'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwide', 'snowmountain'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"dependency" => array(
					'header_style' => array('header-default')
				),
				"std" => 1,
				"type" => "checkbox"
				),

			'menu_info' => array(
				"title" => esc_html__('Menu settings', 'snowmountain'),
				"desc" => wp_kses_data( __('Select main menu style, position, color scheme and other parameters', 'snowmountain') ),
				"type" => "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'snowmountain'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'snowmountain'),
					'left'	=> esc_html__('Left',	'snowmountain'),
					'right'	=> esc_html__('Right',	'snowmountain')
				),
				"type" => "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Menu Color Scheme', 'snowmountain'),
				"desc" => wp_kses_data( __('Select color scheme to decorate main menu area', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => "select"
				),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'snowmountain'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'snowmountain') ),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'snowmountain'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'snowmountain') ),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'snowmountain'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'snowmountain') ),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo settings', 'snowmountain'),
				"desc" => wp_kses_data( __('Select logo images for the normal and Retina displays', 'snowmountain') ),
				"type" => "info"
				),
			'logo' => array(
				"title" => esc_html__('Logo', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo', 'snowmountain') ),
				"std" => '',
				"type" => "image"
				),
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'snowmountain') ),
				"std" => '',
				"type" => "image"
				),
			'logo_inverse' => array(
				"title" => esc_html__('Logo inverse', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it on the dark background', 'snowmountain') ),
				"std" => '',
				"type" => "image"
				),
			'logo_inverse_retina' => array(
				"title" => esc_html__('Logo inverse for Retina', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'snowmountain') ),
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_header' => array(
				"title" => esc_html__('Logo for mobile header', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it on the dark background', 'snowmountain') ),
				"std" => '',
				"type" => "image"
			),
			'logo_mobile_header_retina' => array(
				"title" => esc_html__('Logo for mobile header (Retina)', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'snowmountain') ),
				"std" => '',
				"type" => "image"
			),
			'logo_side' => array(
				"title" => esc_html__('Logo side', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu', 'snowmountain') ),
				"std" => '',
				"type" => "image"
				),
			'logo_side_retina' => array(
				"title" => esc_html__('Logo side for Retina', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'snowmountain') ),
				"std" => '',
				"type" => "image"
				),
			'logo_text' => array(
				"title" => esc_html__('Logo from Site name', 'snowmountain'),
				"desc" => wp_kses_data( __('Do you want use Site name and description as Logo if images above are not selected?', 'snowmountain') ),
				"std" => 1,
				"type" => "checkbox"
				),
			
		
		
			// Section 'Content'
			'content' => array(
				"title" => esc_html__('Content', 'snowmountain'),
				"desc" => wp_kses_data( __('Options for the content area.', 'snowmountain') )
							. '<br>'
							. wp_kses_data( __('<b>Attention!</b> Some of these options can be overridden in the following sections (Homepage, Blog archive, Shop, Events, etc.) or in the settings of individual pages', 'snowmountain') ),
				"type" => "section",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select width of the body content', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"refresh" => false,
				"std" => 'wide',
				"options" => array(
					'boxed'		=> esc_html__('Boxed',		'snowmountain'),
					'wide'		=> esc_html__('Wide',		'snowmountain'),
					'fullwide'	=> esc_html__('Fullwide',	'snowmountain'),
					'fullscreen'=> esc_html__('Fullscreen',	'snowmountain')
				),
				"type" => "select"
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'snowmountain'),
				"desc" => wp_kses_data( __('Select color scheme to decorate whole site. Attention! Case "Inherit" can be used only for custom pages, not for root site content in the Appearance - Customize', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "select"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'snowmountain'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'snowmountain'),
				"desc" => wp_kses_data( __('Remove margins above and below the content area', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),
			'seo_snippets' => array(
				"title" => esc_html__('SEO snippets', 'snowmountain'),
				"desc" => wp_kses_data( __('Add structured data markup to the single posts and pages', 'snowmountain') ),
				"std" => 0,
				"type" => "checkbox"
				),
            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'snowmountain'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'snowmountain') ),
                "std"   => wp_kses( __( 'I agree that my submitted data is being collected and stored.', 'snowmountain'), 'snowmountain_kses_content' ),
                "type"  => "text"
            ),
			
			'border_radius' => array(
				"title" => esc_html__('Border radius', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify the border radius of the form fields and buttons in pixels or other valid CSS units', 'snowmountain') ),
				"std" => '5px',
				"type" => "text"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'snowmountain') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"std" => '',
				"type" => "image"
				),
			'no_image' => array(
				"title" => esc_html__('No image placeholder', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload image, used as placeholder for the posts without featured image', 'snowmountain') ),
				"std" => '',
				"type" => "image"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'snowmountain'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'snowmountain')
				),
				"std" => 'sidebar_widgets',
				"options" => array(),
				"type" => "select"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'snowmountain'),
				"desc" => wp_kses_data( __('Select color scheme to decorate sidebar', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'snowmountain')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "select"
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'snowmountain'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'snowmountain')
				),
				"refresh" => false,
				"std" => 'left',
				"options" => array(),
				"type" => "select"
				),
			'hide_sidebar_on_single' => array(
				"title" => esc_html__('Hide sidebar on the single post', 'snowmountain'),
				"desc" => wp_kses_data( __("Hide sidebar on the single post's pages", 'snowmountain') ),
				"std" => 0,
				"type" => "checkbox"
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets above the page', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'snowmountain')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'snowmountain')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'snowmountain')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets below the page', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'snowmountain')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
		
		
		
			// Section 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'snowmountain'),
				"desc" => wp_kses_data( __('Select set of widgets and columns number for the site footer', 'snowmountain') )
							. '<br>'
							. wp_kses_data( __('<b>Attention!</b> Some of these options can be overridden in the following sections (Homepage, Blog archive, Shop, Events, etc.) or in the settings of individual pages', 'snowmountain') ),
				"type" => "section"
				),
			'footer_style' => array(
				"title" => esc_html__('Footer style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select style to display the site footer', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Footer', 'snowmountain')
				),
				"std" => 'footer-default',
				"options" => array(),
				"type" => "select"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'snowmountain'),
				"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'snowmountain')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'snowmountain'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'snowmountain')
				),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 'footer_widgets',
				"options" => array(),
				"type" => "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'snowmountain'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'snowmountain')
				),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'footer_widgets' => array('^hide')
				),
				"std" => 4,
				"options" => snowmountain_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwide', 'snowmountain'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'snowmountain') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'snowmountain')
				),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'snowmountain'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'snowmountain') ),
				'refresh' => false,
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'snowmountain') ),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'logo_in_footer' => array('1')
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'snowmountain'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'snowmountain') ),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'logo_in_footer' => array('1')
				),
				"std" => '',
				"type" => "image"
				),
			'socials_in_footer' => array(
				"title" => esc_html__('Show social icons', 'snowmountain'),
				"desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'snowmountain') ),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'snowmountain'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'snowmountain') ),
				"std" => esc_html__('AncoraThemes &copy; {Y}. All rights reserved.', 'snowmountain'),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"refresh" => false,
				"type" => "textarea"
				),
		
		
		
			// Section 'Homepage' - settings for home page
			'homepage' => array(
				"title" => esc_html__('Homepage', 'snowmountain'),
				"desc" => wp_kses_data( __('Select blog style and widgets to display on the homepage', 'snowmountain') ),
				"type" => "section"
				),
			'expand_content_home' => array(
				"title" => esc_html__('Expand content', 'snowmountain'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden on the Homepage', 'snowmountain') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),
			'blog_style_home' => array(
				"title" => esc_html__('Blog style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select posts style for the homepage', 'snowmountain') ),
				"std" => 'excerpt',
				"options" => array(),
				"type" => "select"
				),
			'first_post_large_home' => array(
				"title" => esc_html__('First post large', 'snowmountain'),
				"desc" => wp_kses_data( __('Make first post large (with Excerpt layout) on the Classic layout of the Homepage', 'snowmountain') ),
				"dependency" => array(
					'blog_style_home' => array('classic')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'header_style_home' => array(
				"title" => esc_html__('Header style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select style to display the site header on the homepage', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'header_position_home' => array(
				"title" => esc_html__('Header position', 'snowmountain'),
				"desc" => wp_kses_data( __('Select position to display the site header on the homepage', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'header_widgets_home' => array(
				"title" => esc_html__('Header widgets', 'snowmountain'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on the homepage', 'snowmountain') ),
				"std" => 'header_widgets',
				"options" => array(),
				"type" => "select"
				),
			'sidebar_widgets_home' => array(
				"title" => esc_html__('Sidebar widgets', 'snowmountain'),
				"desc" => wp_kses_data( __('Select sidebar to show on the homepage', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'sidebar_position_home' => array(
				"title" => esc_html__('Sidebar position', 'snowmountain'),
				"desc" => wp_kses_data( __('Select position to show sidebar on the homepage', 'snowmountain') ),
				"refresh" => false,
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'widgets_above_page_home' => array(
				"title" => esc_html__('Widgets above the page', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'snowmountain') ),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'widgets_above_content_home' => array(
				"title" => esc_html__('Widgets above the content', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'snowmountain') ),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'widgets_below_content_home' => array(
				"title" => esc_html__('Widgets below the content', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'snowmountain') ),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'widgets_below_page_home' => array(
				"title" => esc_html__('Widgets below the page', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'snowmountain') ),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			
		
		
			// Section 'Blog archive'
			'blog' => array(
				"title" => esc_html__('Blog archive', 'snowmountain'),
				"desc" => wp_kses_data( __('Options for the blog archive', 'snowmountain') ),
				"type" => "section",
				),
			'expand_content_blog' => array(
				"title" => esc_html__('Expand content', 'snowmountain'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden on the blog archive', 'snowmountain') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),
			'blog_style' => array(
				"title" => esc_html__('Blog style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select posts style for the blog archive', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"dependency" => array(
                    '#page_template' => array( 'blog.php' ),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
					'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
				),
				"std" => 'excerpt',
				"options" => array(),
				"type" => "select"
				),
			'blog_columns' => array(
				"title" => esc_html__('Blog columns', 'snowmountain'),
				"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'snowmountain') ),
				"std" => 2,
				"options" => snowmountain_get_list_range(2,4),
				"type" => "hidden"
				),
			'post_type' => array(
				"title" => esc_html__('Post type', 'snowmountain'),
				"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"dependency" => array(
                    '#page_template' => array( 'blog.php' ),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
					'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
				),
				"linked" => 'parent_cat',
				"refresh" => false,
				"hidden" => true,
				"std" => 'post',
				"options" => array(),
				"type" => "select"
				),
			'parent_cat' => array(
				"title" => esc_html__('Category to show', 'snowmountain'),
				"desc" => wp_kses_data( __('Select category to show in the blog archive', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"dependency" => array(
                    '#page_template' => array( 'blog.php' ),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
					'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
				),
				"refresh" => false,
				"hidden" => true,
				"std" => '0',
				"options" => array(),
				"type" => "select"
				),
			'posts_per_page' => array(
				"title" => esc_html__('Posts per page', 'snowmountain'),
				"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"dependency" => array(
                    '#page_template' => array( 'blog.php' ),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
					'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
				),
				"hidden" => true,
				"std" => '10',
				"type" => "text"
				),
			"blog_pagination" => array( 
				"title" => esc_html__('Pagination style', 'snowmountain'),
				"desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"std" => "pages",
				"options" => array(
					'pages'	=> esc_html__("Page numbers", 'snowmountain'),
					'links'	=> esc_html__("Older/Newest", 'snowmountain'),
					'more'	=> esc_html__("Load more", 'snowmountain'),
					'infinite' => esc_html__("Infinite scroll", 'snowmountain')
				),
				"type" => "select"
				),
			'show_filters' => array(
				"title" => esc_html__('Show filters', 'snowmountain'),
				"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"dependency" => array(
                    '#page_template' => array( 'blog.php' ),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
					'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					'blog_style' => array('portfolio', 'gallery')
				),
				"hidden" => true,
				"std" => 0,
				"type" => "checkbox"
				),
			'first_post_large' => array(
				"title" => esc_html__('First post large', 'snowmountain'),
				"desc" => wp_kses_data( __('Make first post large (with Excerpt layout) on the Classic layout of blog archive', 'snowmountain') ),
				"dependency" => array(
					'blog_style' => array('classic')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			"blog_content" => array( 
				"title" => esc_html__('Posts content', 'snowmountain'),
				"desc" => wp_kses_data( __("Show full post's content in the blog or only post's excerpt", 'snowmountain') ),
				"std" => "excerpt",
				"options" => array(
					'excerpt'	=> esc_html__('Excerpt',	'snowmountain'),
					'fullpost'	=> esc_html__('Full post',	'snowmountain')
				),
				"type" => "select"
				),
			'time_diff_before' => array(
				"title" => esc_html__('Time difference', 'snowmountain'),
				"desc" => wp_kses_data( __("How many days show time difference instead post's date", 'snowmountain') ),
				"std" => 5,
				"type" => "text"
				),
			'related_posts' => array(
				"title" => esc_html__('Related posts', 'snowmountain'),
				"desc" => wp_kses_data( __('How many related posts should be displayed in the single post?', 'snowmountain') ),
				"std" => 2,
				"options" => snowmountain_get_list_range(2,4),
				"type" => "hidden"
				),
			'related_style' => array(
				"title" => esc_html__('Related posts style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select style of the related posts output', 'snowmountain') ),
				"std" => 2,
				"options" => snowmountain_get_list_styles(1,2),
				"type" => "hidden"
				),
			"blog_animation" => array( 
				"title" => esc_html__('Animation for the posts', 'snowmountain'),
				"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'snowmountain')
				),
				"dependency" => array(
                    '#page_template' => array( 'blog.php' ),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
					'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
				),
				"std" => "none",
				"options" => array(),
				"type" => "select"
				),
			'header_style_blog' => array(
				"title" => esc_html__('Header style', 'snowmountain'),
				"desc" => wp_kses_data( __('Select style to display the site header on the blog archive', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'header_position_blog' => array(
				"title" => esc_html__('Header position', 'snowmountain'),
				"desc" => wp_kses_data( __('Select position to display the site header on the blog archive', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'header_widgets_blog' => array(
				"title" => esc_html__('Header widgets', 'snowmountain'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on the blog archive', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'sidebar_widgets_blog' => array(
				"title" => esc_html__('Sidebar widgets', 'snowmountain'),
				"desc" => wp_kses_data( __('Select sidebar to show on the blog archive', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'sidebar_position_blog' => array(
				"title" => esc_html__('Sidebar position', 'snowmountain'),
				"desc" => wp_kses_data( __('Select position to show sidebar on the blog archive', 'snowmountain') ),
				"refresh" => false,
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'hide_sidebar_on_single_blog' => array(
				"title" => esc_html__('Hide sidebar on the single post', 'snowmountain'),
				"desc" => wp_kses_data( __("Hide sidebar on the single post", 'snowmountain') ),
				"std" => 0,
				"type" => "checkbox"
				),
			'widgets_above_page_blog' => array(
				"title" => esc_html__('Widgets above the page', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'widgets_above_content_blog' => array(
				"title" => esc_html__('Widgets above the content', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'widgets_below_content_blog' => array(
				"title" => esc_html__('Widgets below the content', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			'widgets_below_page_blog' => array(
				"title" => esc_html__('Widgets below the page', 'snowmountain'),
				"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'snowmountain') ),
				"std" => 'inherit',
				"options" => array(),
				"type" => "select"
				),
			
		
		
		
			// Section 'Colors' - choose color scheme and customize separate colors from it
			'scheme' => array(
				"title" => esc_html__('* Color scheme editor', 'snowmountain'),
				"desc" => wp_kses_data( __("<b>Simple settings</b> - you can change only accented color, used for links, buttons and some accented areas.", 'snowmountain') )
						. '<br>'
						. wp_kses_data( __("<b>Advanced settings</b> - change all scheme's colors and get full control over the appearance of your site!", 'snowmountain') ),
				"priority" => 1000,
				"type" => "section"
				),
		
			'color_settings' => array(
				"title" => esc_html__('Color settings', 'snowmountain'),
				"desc" => '',
				"std" => 'simple',
				"options" => array(
					"simple"  => esc_html__("Simple", 'snowmountain'),
					"advanced" => esc_html__("Advanced", 'snowmountain')
				),
				"refresh" => false,
				"type" => "switch"
				),
		
			'color_scheme_editor' => array(
				"title" => esc_html__('Color Scheme', 'snowmountain'),
				"desc" => wp_kses_data( __('Select color scheme to edit colors', 'snowmountain') ),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "select"
				),
		
			'scheme_storage' => array(
				"title" => esc_html__('Colors storage', 'snowmountain'),
				"desc" => esc_html__('Hidden storage of the all color from the all color shemes (only for internal usage)', 'snowmountain'),
				"std" => '',
				"refresh" => false,
				"type" => "hidden"
				),
		
			'scheme_info_single' => array(
				"title" => esc_html__('Colors for single post/page', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify colors for single post/page (not for alter blocks)', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
				
			'bg_color' => array(
				"title" => esc_html__('Background color', 'snowmountain'),
				"desc" => wp_kses_data( __('Background color of the whole page', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'bd_color' => array(
				"title" => esc_html__('Border color', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the bordered elements, separators, etc.', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'text' => array(
				"title" => esc_html__('Text', 'snowmountain'),
				"desc" => wp_kses_data( __('Plain text color on single page/post', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_light' => array(
				"title" => esc_html__('Light text', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the post meta: post date and author, comments number, etc.', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_dark' => array(
				"title" => esc_html__('Dark text', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the headers, strong text, etc.', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_link' => array(
				"title" => esc_html__('Links', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of links and accented areas', 'snowmountain') ),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_hover' => array(
				"title" => esc_html__('Links hover', 'snowmountain'),
				"desc" => wp_kses_data( __('Hover color for links and accented areas', 'snowmountain') ),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'scheme_info_alter' => array(
				"title" => esc_html__('Colors for alternative blocks', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify colors for alternative blocks - rectangular blocks with its own background color (posts in homepage, blog archive, search results, widgets on sidebar, footer, etc.)', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
		
			'alter_bg_color' => array(
				"title" => esc_html__('Alter background color', 'snowmountain'),
				"desc" => wp_kses_data( __('Background color of the alternative blocks', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_bg_hover' => array(
				"title" => esc_html__('Alter hovered background color', 'snowmountain'),
				"desc" => wp_kses_data( __('Background color for the hovered state of the alternative blocks', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_bd_color' => array(
				"title" => esc_html__('Alternative border color', 'snowmountain'),
				"desc" => wp_kses_data( __('Border color of the alternative blocks', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_bd_hover' => array(
				"title" => esc_html__('Alternative hovered border color', 'snowmountain'),
				"desc" => wp_kses_data( __('Border color for the hovered state of the alter blocks', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_text' => array(
				"title" => esc_html__('Alter text', 'snowmountain'),
				"desc" => wp_kses_data( __('Text color of the alternative blocks', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_light' => array(
				"title" => esc_html__('Alter light', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the info blocks inside block with alternative background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_dark' => array(
				"title" => esc_html__('Alter dark', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the headers inside block with alternative background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_link' => array(
				"title" => esc_html__('Alter link', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the links inside block with alternative background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_hover' => array(
				"title" => esc_html__('Alter hover', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the hovered links inside block with alternative background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'scheme_info_input' => array(
				"title" => esc_html__('Colors for the form fields', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify colors for the form fields and textareas', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
		
			'input_bg_color' => array(
				"title" => esc_html__('Inactive background', 'snowmountain'),
				"desc" => wp_kses_data( __('Background color of the inactive form fields', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_bg_hover' => array(
				"title" => esc_html__('Active background', 'snowmountain'),
				"desc" => wp_kses_data( __('Background color of the focused form fields', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_bd_color' => array(
				"title" => esc_html__('Inactive border', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the border in the inactive form fields', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_bd_hover' => array(
				"title" => esc_html__('Active border', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the border in the focused form fields', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_text' => array(
				"title" => esc_html__('Inactive field', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the text in the inactive fields', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_light' => array(
				"title" => esc_html__('Disabled field', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the disabled field', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_dark' => array(
				"title" => esc_html__('Active field', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the active field', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'scheme_info_inverse' => array(
				"title" => esc_html__('Colors for inverse blocks', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify colors for inverse blocks, rectangular blocks with background color equal to the links color or one of accented colors (if used in the current theme)', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
		
			'inverse_text' => array(
				"title" => esc_html__('Inverse text', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the text inside block with accented background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_light' => array(
				"title" => esc_html__('Inverse light', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the info blocks inside block with accented background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_dark' => array(
				"title" => esc_html__('Inverse dark', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the headers inside block with accented background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_link' => array(
				"title" => esc_html__('Inverse link', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the links inside block with accented background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_hover' => array(
				"title" => esc_html__('Inverse hover', 'snowmountain'),
				"desc" => wp_kses_data( __('Color of the hovered links inside block with accented background', 'snowmountain') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$snowmountain_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),


			// Section 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'snowmountain'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'snowmountain') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Title', 'snowmountain')
				),
				"hidden" => true,
				"std" => '',
				"type" => "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'snowmountain'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'snowmountain') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Title', 'snowmountain')
				),
				"hidden" => true,
				"std" => '',
				"type" => "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		$fonts = array(
		
			// Panel 'Fonts' - manage fonts loading and set parameters of the base theme elements
			'fonts' => array(
				"title" => esc_html__('* Fonts settings', 'snowmountain'),
				"desc" => '',
				"priority" => 1500,
				"type" => "panel"
				),

			// Section 'Load_fonts'
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'snowmountain') )
						. '<br>'
						. wp_kses_data( __('<b>Attention!</b> Press "Refresh" button to reload preview area after the all fonts are changed', 'snowmountain') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'snowmountain'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'snowmountain') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'snowmountain') ),
				"refresh" => false,
				"std" => '$snowmountain_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=snowmountain_get_theme_setting('max_load_fonts'); $i++) {
			$fonts["load_fonts-{$i}-info"] = array(
				"title" => esc_html(sprintf(__('Font %s', 'snowmountain'), $i)),
				"desc" => '',
				"type" => "info",
				);
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'snowmountain'),
				"desc" => '',
				"refresh" => false,
				"std" => '$snowmountain_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'snowmountain'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'snowmountain') )
							: '',
				"refresh" => false,
				"std" => '$snowmountain_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'snowmountain'),
					'serif' => esc_html__('serif', 'snowmountain'),
					'sans-serif' => esc_html__('sans-serif', 'snowmountain'),
					'monospace' => esc_html__('monospace', 'snowmountain'),
					'cursive' => esc_html__('cursive', 'snowmountain'),
					'fantasy' => esc_html__('fantasy', 'snowmountain')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'snowmountain'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'snowmountain') )
											. '<br>'
								. wp_kses_data( __('<b>Attention!</b> Each weight and style increase download size! Specify only used weights and styles.', 'snowmountain') )
							: '',
				"refresh" => false,
				"std" => '$snowmountain_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Sections with font's attributes for each theme element
		$theme_fonts = snowmountain_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								: esc_html(sprintf(__('%s settings', 'snowmountain'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								: wp_kses_post( sprintf(__('Font settings of the "%s" tag.', 'snowmountain'), $tag) ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = array();
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'snowmountain'),
						'100' => esc_html__('100 (Light)', 'snowmountain'), 
						'200' => esc_html__('200 (Light)', 'snowmountain'), 
						'300' => esc_html__('300 (Thin)',  'snowmountain'),
						'400' => esc_html__('400 (Normal)', 'snowmountain'),
						'500' => esc_html__('500 (Semibold)', 'snowmountain'),
						'600' => esc_html__('600 (Semibold)', 'snowmountain'),
						'700' => esc_html__('700 (Bold)', 'snowmountain'),
						'800' => esc_html__('800 (Black)', 'snowmountain'),
						'900' => esc_html__('900 (Black)', 'snowmountain')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'snowmountain'),
						'normal' => esc_html__('Normal', 'snowmountain'), 
						'italic' => esc_html__('Italic', 'snowmountain')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'snowmountain'),
						'none' => esc_html__('None', 'snowmountain'), 
						'underline' => esc_html__('Underline', 'snowmountain'),
						'overline' => esc_html__('Overline', 'snowmountain'),
						'line-through' => esc_html__('Line-through', 'snowmountain')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'snowmountain'),
						'none' => esc_html__('None', 'snowmountain'), 
						'uppercase' => esc_html__('Uppercase', 'snowmountain'),
						'lowercase' => esc_html__('Lowercase', 'snowmountain'),
						'capitalize' => esc_html__('Capitalize', 'snowmountain')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"refresh" => false,
					"std" => '$snowmountain_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters into Theme Options
		snowmountain_storage_merge_array('options', '', $fonts);

		// Add Header Video if WP version < 4.7
		if (!function_exists('get_header_video_url')) {
			snowmountain_storage_set_array_after('options', 'header_image_override', 'header_video', array(
				"title" => esc_html__('Header video', 'snowmountain'),
				"desc" => wp_kses_data( __("Select video to use it as background for the header", 'snowmountain') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'snowmountain')
				),
				"std" => '',
				"type" => "video"
				)
			);
		}
	}
}


// Return lists with choises when its need in the admin mode
if (!function_exists('snowmountain_options_get_list_choises')) {
	add_filter('snowmountain_filter_options_get_list_choises', 'snowmountain_options_get_list_choises', 10, 2);
	function snowmountain_options_get_list_choises($list, $id) {
		if (is_array($list) && count($list)==0) {
			if (strpos($id, 'header_style')===0)
				$list = snowmountain_get_list_header_styles(strpos($id, 'header_style_')===0);
			else if (strpos($id, 'header_position')===0)
				$list = snowmountain_get_list_header_positions(strpos($id, 'header_position_')===0);
			else if (strpos($id, 'header_widgets')===0)
				$list = snowmountain_get_list_sidebars(strpos($id, 'header_widgets_')===0, true);
			else if (strpos($id, 'header_scheme')===0 
					|| strpos($id, 'menu_scheme')===0
					|| strpos($id, 'color_scheme')===0
					|| strpos($id, 'sidebar_scheme')===0
					|| strpos($id, 'footer_scheme')===0)
				$list = snowmountain_get_list_schemes(true);
			else if (strpos($id, 'sidebar_widgets')===0)
				$list = snowmountain_get_list_sidebars(strpos($id, 'sidebar_widgets_')===0, true);
			else if (strpos($id, 'sidebar_position')===0)
				$list = snowmountain_get_list_sidebars_positions(strpos($id, 'sidebar_position_')===0);
			else if (strpos($id, 'widgets_above_page')===0)
				$list = snowmountain_get_list_sidebars(strpos($id, 'widgets_above_page_')===0, true);
			else if (strpos($id, 'widgets_above_content')===0)
				$list = snowmountain_get_list_sidebars(strpos($id, 'widgets_above_content_')===0, true);
			else if (strpos($id, 'widgets_below_page')===0)
				$list = snowmountain_get_list_sidebars(strpos($id, 'widgets_below_page_')===0, true);
			else if (strpos($id, 'widgets_below_content')===0)
				$list = snowmountain_get_list_sidebars(strpos($id, 'widgets_below_content_')===0, true);
			else if (strpos($id, 'footer_style')===0)
				$list = snowmountain_get_list_footer_styles(strpos($id, 'footer_style_')===0);
			else if (strpos($id, 'footer_widgets')===0)
				$list = snowmountain_get_list_sidebars(strpos($id, 'footer_widgets_')===0, true);
			else if (strpos($id, 'blog_style')===0)
				$list = snowmountain_get_list_blog_styles(strpos($id, 'blog_style_')===0);
			else if (strpos($id, 'post_type')===0)
				$list = snowmountain_get_list_posts_types();
			else if (strpos($id, 'parent_cat')===0)
				$list = snowmountain_array_merge(array(0 => esc_html__('- Select category -', 'snowmountain')), snowmountain_get_list_categories());
			else if (strpos($id, 'blog_animation')===0)
				$list = snowmountain_get_list_animations_in();
			else if ($id == 'color_scheme_editor')
				$list = snowmountain_get_list_schemes();
			else if (strpos($id, '_font-family') > 0)
				$list = snowmountain_get_list_load_fonts(true);
		}
		return $list;
	}
}




// -----------------------------------------------------------------
// -- Create and manage Theme Options
// -----------------------------------------------------------------

// Theme init priorities:
// 2 - create Theme Options
if (!function_exists('snowmountain_options_theme_setup2')) {
	add_action( 'after_setup_theme', 'snowmountain_options_theme_setup2', 2 );
	function snowmountain_options_theme_setup2() {
		snowmountain_options_create();
	}
}

// Step 1: Load default settings and previously saved mods
if (!function_exists('snowmountain_options_theme_setup5')) {
	add_action( 'after_setup_theme', 'snowmountain_options_theme_setup5', 5 );
	function snowmountain_options_theme_setup5() {
		snowmountain_storage_set('options_reloaded', false);
		snowmountain_load_theme_options();
	}
}

// Step 2: Load current theme customization mods
if (is_customize_preview()) {
	if (!function_exists('snowmountain_load_custom_options')) {
		add_action( 'wp_loaded', 'snowmountain_load_custom_options' );
		function snowmountain_load_custom_options() {
			if (!snowmountain_storage_get('options_reloaded')) {
				snowmountain_storage_set('options_reloaded', true);
				snowmountain_load_theme_options();
			}
		}
	}
}

// Load current values for each customizable option
if ( !function_exists('snowmountain_load_theme_options') ) {
	function snowmountain_load_theme_options() {
		$options = snowmountain_storage_get('options');
		$reset = (int) get_theme_mod('reset_options', 0);
		foreach ($options as $k=>$v) {
			if (isset($v['std'])) {
				if (strpos($v['std'], '$snowmountain_')!==false) {
					$func = substr($v['std'], 1);
					if (function_exists($func)) {
						$v['std'] = $func($k);
					}
				}
				$value = $v['std'];
				if (!$reset) {
					if (isset($_GET[$k]))
						$value = wp_kses_data( wp_unslash( $_GET[$k] ) );
					else {
						$tmp = get_theme_mod($k, -987654321);
						if ($tmp != -987654321) $value = $tmp;
					}
				}
				snowmountain_storage_set_array2('options', $k, 'val', $value);
				if ($reset) remove_theme_mod($k);
			}
		}
		if ($reset) {
			// Unset reset flag
			set_theme_mod('reset_options', 0);
			// Regenerate CSS with default colors and fonts
			snowmountain_customizer_save_css();
		} else {
			do_action('snowmountain_action_load_options');
		}
	}
}

// Override options with stored page/post meta
if ( !function_exists('snowmountain_override_theme_options') ) {
	add_action( 'wp', 'snowmountain_override_theme_options', 1 );
	function snowmountain_override_theme_options($query=null) {
		if (is_page_template('blog.php')) {
			snowmountain_storage_set('blog_archive', true);
			snowmountain_storage_set('blog_template', get_the_ID());
		}
		snowmountain_storage_set('blog_mode', snowmountain_detect_blog_mode());
		if (is_singular()) {
			snowmountain_storage_set('options_meta', get_post_meta(get_the_ID(), 'snowmountain_options', true));
		}
	}
}


// Return customizable option value
if (!function_exists('snowmountain_get_theme_option')) {
	function snowmountain_get_theme_option($name, $defa='', $strict_mode=false, $post_id=0) {
		$rez = $defa;
		$from_post_meta = false;
		if ($post_id > 0) {
			if (!snowmountain_storage_isset('post_options_meta', $post_id))
				snowmountain_storage_set_array('post_options_meta', $post_id, get_post_meta($post_id, 'snowmountain_options', true));
			if (snowmountain_storage_isset('post_options_meta', $post_id, $name)) {
				$tmp = snowmountain_storage_get_array('post_options_meta', $post_id, $name);
				if (!snowmountain_is_inherit($tmp)) {
					$rez = $tmp;
					$from_post_meta = true;
				}
			}
		}
		if (!$from_post_meta && snowmountain_storage_isset('options')) {
			if ( !snowmountain_storage_isset('options', $name) ) {
				$rez = $tmp = '_not_exists_';
				if (function_exists('trx_addons_get_option'))
					$rez = trx_addons_get_option($name, $tmp, false);
				if ($rez === $tmp) {
					if ($strict_mode) {
						$s = debug_backtrace();
						$s = array_shift($s);
						echo '<pre>' . sprintf(esc_html__('Undefined option "%s" called from:', 'snowmountain'), $name);
						if (function_exists('snowmountain_dco')) snowmountain_dco($s);
						else print_r($s);
						echo '</pre>';
                        wp_die();
					} else
						$rez = $defa;
				}
			} else {
				$blog_mode = snowmountain_storage_get('blog_mode');
				// Override option from GET or POST for current blog mode
				if (!empty($blog_mode) && isset($_REQUEST[$name . '_' . $blog_mode])) {
					$rez = wp_kses_data( wp_unslash( $_REQUEST[$name . '_' . $blog_mode] ) );
				// Override option from GET
				} else if (isset($_REQUEST[$name])) {
					$rez = wp_kses_data( wp_unslash( $_REQUEST[$name] ) );
				// Override option from current page settings (if exists)
				} else if (snowmountain_storage_isset('options_meta', $name) && !snowmountain_is_inherit(snowmountain_storage_get_array('options_meta', $name))) {
					$rez = snowmountain_storage_get_array('options_meta', $name);
				// Override option from current blog mode settings: 'home', 'search', 'page', 'post', 'blog', etc. (if exists)
				} else if (!empty($blog_mode) && snowmountain_storage_isset('options', $name . '_' . $blog_mode, 'val') && !snowmountain_is_inherit(snowmountain_storage_get_array('options', $name . '_' . $blog_mode, 'val'))) {
					$rez = snowmountain_storage_get_array('options', $name . '_' . $blog_mode, 'val');
				// Get saved option value
				} else if (snowmountain_storage_isset('options', $name, 'val')) {
					$rez = snowmountain_storage_get_array('options', $name, 'val');
				// Get ThemeREX Addons option value
				} else if (function_exists('trx_addons_get_option')) {
					$rez = trx_addons_get_option($name, $defa, false);
				}
			}
		}
		return $rez;
	}
}


// Check if customizable option exists
if (!function_exists('snowmountain_check_theme_option')) {
	function snowmountain_check_theme_option($name) {
		return snowmountain_storage_isset('options', $name);
	}
}


// Get dependencies list from the Theme Options
if ( !function_exists('snowmountain_get_theme_dependencies') ) {
	function snowmountain_get_theme_dependencies() {
		$options = snowmountain_storage_get('options');
		$depends = array();
		foreach ($options as $k=>$v) {
			if (isset($v['dependency'])) 
				$depends[$k] = $v['dependency'];
		}
		return $depends;
	}
}

// Return internal theme setting value
if (!function_exists('snowmountain_get_theme_setting')) {
	function snowmountain_get_theme_setting($name) {
		return snowmountain_storage_isset('settings', $name) ? snowmountain_storage_get_array('settings', $name) : false;
	}
}

// Set theme setting
if ( !function_exists( 'snowmountain_set_theme_setting' ) ) {
	function snowmountain_set_theme_setting($option_name, $value) {
		if (snowmountain_storage_isset('settings', $option_name))
			snowmountain_storage_set_array('settings', $option_name, $value);
	}
}
?>