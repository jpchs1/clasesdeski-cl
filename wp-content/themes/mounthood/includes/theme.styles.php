<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('mounthood_action_theme_styles_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_action_theme_styles_theme_setup', 1 );
	function mounthood_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('mounthood_filter_used_fonts',			'mounthood_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('mounthood_filter_list_fonts',			'mounthood_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('mounthood_action_add_styles',			'mounthood_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('mounthood_filter_add_styles_inline',		'mounthood_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('mounthood_action_add_scripts',			'mounthood_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('mounthood_filter_localize_script',		'mounthood_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('mounthood_filter_compile_less',			'mounthood_filter_theme_styles_compile_less');

		// Add color schemes
		mounthood_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'mounthood'),
			
			// Accent Colors
			'accent_color'			=> '#f9f600',

			// Whole block border and background
			'bd_color'				=> '#cecece',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#3c3c3c',
			'text_light'			=> '#aeaeae',
			'text_dark'				=> '#070707',
			'text_link'				=> '#004eb3',
			'text_hover'			=> '#3c3c3c',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#3c3c3c',
			'input_light'			=> '#cecece',
			'input_dark'			=> '#c4c4c4',
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)

			'alter_text'			=> '#333333',
			'alter_light'			=> '#3b3b3b',
			'alter_dark'			=> '#363636',
			'alter_link'			=> '#004eb3',
			'alter_hover'			=> '#3c3c3c',
			'alter_bd_color'		=> '#363636',
			'alter_bd_hover'		=> '#222222',
			'alter_bg_color'		=> '#262626',
			'alter_bg_hover'		=> '#1b1b1b',

			)
		);

		// Add color schemes

		mounthood_add_color_scheme('light', array(

			'title'					=> esc_html__('Light', 'mounthood'),

			// Accent Colors
			'accent_color'			=> '#c3d300',

			// Whole block border and background
			'bd_color'				=> '#cecece',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#3c3c3c',
			'text_light'			=> '#cecece',
			'text_dark'				=> '#070707',
			'text_link'				=> '#b3c200',
			'text_hover'			=> '#3c3c3c',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#3c3c3c',
			'input_light'			=> '#cecece',
			'input_dark'			=> '#070707',
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)

			'alter_text'			=> '#333333',
			'alter_light'			=> '#3b3b3b',
			'alter_dark'			=> '#363636',
			'alter_link'			=> '#3c3c3c',
			'alter_hover'			=> '#3c3c3c',
			'alter_bd_color'		=> '#363636',
			'alter_bd_hover'		=> '#222222',
			'alter_bg_color'		=> '#262626',
			'alter_bg_hover'		=> '#1b1b1b',

			)
		);

		// Add color schemes
		mounthood_add_color_scheme('green', array(

			'title'					=> esc_html__('Green', 'mounthood'),
			
			// Accent Colors
			'accent_color'			=> '#c3d300',

			// Whole block border and background
			'bd_color'				=> '#cecece',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#3c3c3c',
			'text_light'			=> '#aeaeae',
			'text_dark'				=> '#070707',
			'text_link'				=> '#c3d300',
			'text_hover'			=> '#3c3c3c',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#3c3c3c',
			'input_light'			=> '#cecece',
			'input_dark'			=> '#070707',
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)

			'alter_text'			=> '#333333',
			'alter_light'			=> '#3b3b3b',
			'alter_dark'			=> '#363636',
			'alter_link'			=> '#c3d300',
			'alter_hover'			=> '#3c3c3c',
			'alter_bd_color'		=> '#363636',
			'alter_bd_hover'		=> '#222222',
			'alter_bg_color'		=> '#262626',
			'alter_bg_hover'		=> '#1b1b1b',

			)
		);

		// Add color schemes

		mounthood_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'mounthood'),

			// Accent Colors
			'accent_color'			=> '#f9f600',

			// Whole block border and background
			'bd_color'				=> '#cecece',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#3c3c3c',
			'text_light'			=> '#aeaeae',
			'text_dark'				=> '#070707',
			'text_link'				=> '#004eb3',
			'text_hover'			=> '#3c3c3c',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#3c3c3c',
			'input_light'			=> '#cecece',
			'input_dark'			=> '#070707',
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)

			'alter_text'			=> '#333333',
			'alter_light'			=> '#3b3b3b',
			'alter_dark'			=> '#363636',
			'alter_link'			=> '#004eb3',
			'alter_hover'			=> '#ffffff',
			'alter_bd_color'		=> '#363636',
			'alter_bd_hover'		=> '#222222',
			'alter_bg_color'		=> '#262626',
			'alter_bg_hover'		=> '#1b1b1b',
			)
		);

		// Add color schemes

		mounthood_add_color_scheme('dark_alter', array(

			'title'					=> esc_html__('Dark Alter', 'mounthood'),

			// Accent Colors
			'accent_color'			=> '#c3d300',

			// Whole block border and background
			'bd_color'				=> '#cecece',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#3c3c3c',
			'text_light'			=> '#aeaeae',
			'text_dark'				=> '#070707',
			'text_link'				=> '#c3d300',
			'text_hover'			=> '#c3d300',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#3c3c3c',
			'input_light'			=> '#cecece',
			'input_dark'			=> '#070707',
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)

			'alter_text'			=> '#333333',
			'alter_light'			=> '#3b3b3b',
			'alter_dark'			=> '#363636',
			'alter_link'			=> '#3c3c3c',
			'alter_hover'			=> '#3c3c3c',
			'alter_bd_color'		=> '#363636',
			'alter_bd_hover'		=> '#222222',
			'alter_bg_color'		=> '#262626',
			'alter_bg_hover'		=> '#1b1b1b',

			)
		);

		// Add Custom fonts
		mounthood_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '"Montserrat", sans-serif',
			'font-size' 	=> '3.333em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1em',
			'margin-bottom'	=> '0.4em'
			)
		);
		mounthood_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '"Montserrat", sans-serif',
			'font-size' 	=> '2.400em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.4em',
			'margin-bottom'	=> '0.78em'
			)
		);
		mounthood_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '"Montserrat", sans-serif',
			'font-size' 	=> '1.600em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '2.075em',
			'margin-bottom'	=> '0.86em'
			)
		);
		mounthood_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '"Montserrat", sans-serif',
			'font-size' 	=> '1.333em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '2.65em',
			'margin-bottom'	=> '1.1em'
			)
		);
		mounthood_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '"Montserrat", sans-serif',
			'font-size' 	=> '1.067em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '3.6em',
			'margin-bottom'	=> '1.45em'
			)
		);
		mounthood_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '"Montserrat", sans-serif',
			'font-size' 	=> '0.933em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '3.75em',
			'margin-bottom'	=> '1.75em'
			)
		);
		mounthood_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '"Open Sans",sans-serif',
			'font-size' 	=> '15px',
			'font-weight'	=> '200',
			'font-style'	=> '',
			'line-height'	=> '1.9em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		mounthood_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		mounthood_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.8571em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		mounthood_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '1.9em',
			'margin-bottom'	=> '2.51em'
			)
		);
		mounthood_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		mounthood_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '2em'
			)
		);
		mounthood_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		mounthood_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'mounthood'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('mounthood_filter_theme_styles_used_fonts')) {
	//Handler of add_filter('mounthood_filter_used_fonts', 'mounthood_filter_theme_styles_used_fonts');
	function mounthood_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Open+Sans'] = 1;
		$theme_fonts['Montserrat'] = 1;
		return $theme_fonts;
	}
}

// Add theme fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('mounthood_filter_theme_styles_list_fonts')) {
	//Handler of add_filter('mounthood_filter_list_fonts', 'mounthood_filter_theme_styles_list_fonts');
	function mounthood_filter_theme_styles_list_fonts($list) {
		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('mounthood_filter_theme_styles_compile_less')) {
	//Handler of add_filter('mounthood_filter_compile_less', 'mounthood_filter_theme_styles_compile_less');
	function mounthood_filter_theme_styles_compile_less($files) {
		if (file_exists(mounthood_get_file_dir('css/theme.less'))) {
		 	$files[] = mounthood_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('mounthood_action_theme_styles_add_styles')) {
	//Handler of add_action('mounthood_action_add_styles', 'mounthood_action_theme_styles_add_styles');
	function mounthood_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( mounthood_get_theme_setting('less_compiler') != 'no' ) {
			wp_enqueue_style( 'mounthood-theme-style', mounthood_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'mounthood-theme-style', mounthood_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('mounthood_filter_theme_styles_add_styles_inline')) {
	//Handler of add_filter('mounthood_filter_add_styles_inline', 'mounthood_filter_theme_styles_add_styles_inline');
	function mounthood_filter_theme_styles_add_styles_inline($custom_style) {
		// Todo: add theme specific styles in the $custom_style to override

		// Submenu width
		$menu_width = mounthood_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = mounthood_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = mounthood_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = mounthood_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= mounthood_get_custom_option('custom_css');

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('mounthood_action_theme_styles_add_scripts')) {
	//Handler of add_action('mounthood_action_add_scripts', 'mounthood_action_theme_styles_add_scripts');
	function mounthood_action_theme_styles_add_scripts() {
		if (mounthood_get_theme_option('show_theme_customizer') == 'yes' && file_exists(mounthood_get_file_dir('js/theme.customizer.js')))
			wp_enqueue_script( 'mounthood-theme-styles-customizer-script', mounthood_get_file_url('js/theme.customizer.js'), array(), null, true );
	}
}

// Add theme scripts inline
if (!function_exists('mounthood_filter_theme_styles_localize_script')) {
	//Handler of add_filter('mounthood_filter_localize_script',		'mounthood_filter_theme_styles_localize_script');
	function mounthood_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = mounthood_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = mounthood_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = mounthood_get_scheme_color('bg_color');
		return $vars;
	}
}
?>