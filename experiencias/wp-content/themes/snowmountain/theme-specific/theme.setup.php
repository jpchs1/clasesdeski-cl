<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.22
 */

// Theme init priorities:
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
if ( !function_exists('snowmountain_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'snowmountain_customizer_theme_setup1', 1 );
	function snowmountain_customizer_theme_setup1() {
		
		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		// For example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
		snowmountain_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Raleway',
				'family' => 'sans-serif',
				'styles' => '400,400italic,500,500italic,700,700italic,900,900italic'		// Parameter 'style' used only for the Google fonts
				),
            array(
                'name'	 => 'Kaushan Script',
                'family' => 'cursive',
                'styles' => '400'		// Parameter 'style' used only for the Google fonts
            ),
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		snowmountain_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		snowmountain_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'snowmountain'),
				'description'		=> esc_html__('Font settings of the main text of the site', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '1rem',
				'font-weight'		=> '500',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5625em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '',
				'margin-top'		=> '0em',
				'margin-bottom'		=> '1.5625em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '3.75em',
				'font-weight'		=> '900',
				'font-style'		=> 'italic',
				'line-height'		=> '1.03',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '2.075em',
				'margin-bottom'		=> '0.5em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '3em',
				'font-weight'		=> '900',
				'font-style'		=> 'italic',
				'line-height'		=> '1.13',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '2.55em',
				'margin-bottom'		=> '0.6em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '2.25em',
				'font-weight'		=> '900',
				'font-style'		=> 'italic',
				'line-height'		=> '1.14',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '3.45em',
				'margin-bottom'		=> '0.7em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '1.874em',
				'font-weight'		=> '900',
				'font-style'		=> 'italic',
				'line-height'		=> '1.1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '4.275em',
				'margin-bottom'		=> '0.85em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '1.5em',
				'font-weight'		=> '500',
				'font-style'		=> 'normal',
				'line-height'		=> '1.38',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '5.225em',
				'margin-bottom'		=> '1em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '1.124em',
				'font-weight'		=> '500',
				'font-style'		=> 'normal',
				'line-height'		=> '1.39',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '7.1em',
				'margin-bottom'		=> '0.9em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'snowmountain'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '1.2em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '600',
				'font-style'		=> 'italic',
				'line-height'		=> '1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'snowmountain'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '0.875em',
				'font-weight'		=> '500',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'snowmountain'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '500',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0.5px',
				'margin-top'		=> '0.4em',
				'margin-bottom'		=> ''
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'snowmountain'),
				'description'		=> esc_html__('Font settings of the main menu items', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '700',
				'font-style'		=> 'italic',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'snowmountain'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'snowmountain'),
				'font-family'		=> 'Raleway, sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '700',
				'font-style'		=> 'italic',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'decor' => array(
				'title'				=> esc_html__('Decor', 'snowmountain'),
				'description'		=> esc_html__('Font family setting for decor elements', 'snowmountain'),
				'font-family'		=> 'Kaushan Script, cursive',
			)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		snowmountain_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'snowmountain'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'				=> '#ffffff',
					'bd_color'				=> '#e5e5e5',
		
					// Text and links colors
					'text'					=> '#808b90',
					'text_light'			=> '#b7b7b7',
					'text_dark'				=> '#505a61',
					'text_link'				=> '#8eb2c7',
					'text_hover'			=> '#eca549',
		
					// Alternative blocks (submenu, buttons, tabs, etc.)
					'alter_bg_color'		=> '#eaf2f7',
					'alter_bg_hover'		=> '#292e32',
					'alter_bd_color'		=> '#e5e5e5',
					'alter_bd_hover'		=> '#dadada',
					'alter_text'			=> '#2f3f48',
					'alter_light'			=> '#7e8081',
					'alter_dark'			=> '#4f5051',
					'alter_link'			=> '#fe7259',
					'alter_hover'			=> '#db9740',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'		=> '#eaf2f7',
					'input_bg_hover'		=> '#f7f0ea',
					'input_bd_color'		=> '#eaf2f7',
					'input_bd_hover'		=> '#eca549',
					'input_text'			=> '#505a61',
					'input_light'			=> '#e5e5e5',
					'input_dark'			=> '#1d1d1d',
					
					// Inverse blocks (text and links on accented bg)
					'inverse_text'			=> '#ffffff',
					'inverse_light'			=> '#333333',
					'inverse_dark'			=> '#000000',
					'inverse_link'			=> '#ffffff',
					'inverse_hover'			=> '#1d1d1d',

				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'snowmountain'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'				=> '#0e0d12',
					'bd_color'				=> '#1c1b1f',
		
					// Text and links colors
					'text'					=> '#b7b7b7',
					'text_light'			=> '#5f5f5f',
					'text_dark'				=> '#ffffff',
					'text_link'				=> '#fe7259',
					'text_hover'			=> '#ffaa5f',
		
					// Alternative blocks (submenu, buttons, tabs, etc.)
					'alter_bg_color'		=> '#1e1d22',
					'alter_bg_hover'		=> '#28272e',
					'alter_bd_color'		=> '#313131',
					'alter_bd_hover'		=> '#3d3d3d',
					'alter_text'			=> '#a6a6a6',
					'alter_light'			=> '#5f5f5f',
					'alter_dark'			=> '#ffffff',
					'alter_link'			=> '#ffaa5f',
					'alter_hover'			=> '#fe7259',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'		=> '#2e2d32',
					'input_bg_hover'		=> '#2e2d32',
					'input_bd_color'		=> '#2e2d32',
					'input_bd_hover'		=> '#353535',
					'input_text'			=> '#b7b7b7',
					'input_light'			=> '#5f5f5f',
					'input_dark'			=> '#ffffff',
					
					// Inverse blocks (text and links on accented bg)
					'inverse_text'			=> '#1d1d1d',
					'inverse_light'			=> '#5f5f5f',
					'inverse_dark'			=> '#000000',
					'inverse_link'			=> '#ffffff',
					'inverse_hover'			=> '#1d1d1d',
		
				)
			)
		
		));
	}
}

			
// Additional (calculated) theme-specific colors
// Attention! Don't forget setup custom colors also in the theme.customizer.color-scheme.js
if (!function_exists('snowmountain_customizer_add_theme_colors')) {
	function snowmountain_customizer_add_theme_colors($colors) {
		if (substr($colors['text'], 0, 1) == '#') {
			$colors['bg_color_0']  = snowmountain_hex2rgba( $colors['bg_color'], 0 );
			$colors['bg_color_02']  = snowmountain_hex2rgba( $colors['bg_color'], 0.2 );
			$colors['bg_color_06']  = snowmountain_hex2rgba( $colors['bg_color'], 0.6 );
			$colors['bg_color_07']  = snowmountain_hex2rgba( $colors['bg_color'], 0.7 );
			$colors['bg_color_08']  = snowmountain_hex2rgba( $colors['bg_color'], 0.8 );
			$colors['bg_color_09']  = snowmountain_hex2rgba( $colors['bg_color'], 0.9 );
			$colors['alter_text_06']  = snowmountain_hex2rgba( $colors['alter_text'], 0.6 );
			$colors['alter_bg_color_07']  = snowmountain_hex2rgba( $colors['alter_bg_color'], 0.7 );
			$colors['alter_bg_color_04']  = snowmountain_hex2rgba( $colors['alter_bg_color'], 0.4 );
			$colors['alter_bg_color_03']  = snowmountain_hex2rgba( $colors['alter_bg_color'], 0.3 );
			$colors['alter_bg_color_02']  = snowmountain_hex2rgba( $colors['alter_bg_color'], 0.2 );
			$colors['alter_bd_color_02']  = snowmountain_hex2rgba( $colors['alter_bd_color'], 0.2 );
			$colors['text_dark_07']  = snowmountain_hex2rgba( $colors['text_dark'], 0.7 );
			$colors['text_link_02']  = snowmountain_hex2rgba( $colors['text_link'], 0.2 );
			$colors['text_link_03']  = snowmountain_hex2rgba( $colors['text_link'], 0.3 );
			$colors['text_link_07']  = snowmountain_hex2rgba( $colors['text_link'], 0.7 );
			$colors['text_hover_07']  = snowmountain_hex2rgba( $colors['text_hover'], 0.7 );
			$colors['text_hover_08']  = snowmountain_hex2rgba( $colors['text_hover'], 0.8 );
			$colors['text_link_blend'] = snowmountain_hsb2hex(snowmountain_hex2hsb( $colors['text_link'], 2, -5, 5 ));
			$colors['alter_link_blend'] = snowmountain_hsb2hex(snowmountain_hex2hsb( $colors['alter_link'], 2, -5, 5 ));
		} else {
			$colors['bg_color_0'] = '{{ data.bg_color_0 }}';
			$colors['bg_color_02'] = '{{ data.bg_color_02 }}';
			$colors['bg_color_06'] = '{{ data.bg_color_06 }}';
			$colors['bg_color_07'] = '{{ data.bg_color_07 }}';
			$colors['bg_color_08'] = '{{ data.bg_color_08 }}';
			$colors['bg_color_09'] = '{{ data.bg_color_09 }}';
			$colors['alter_text_06'] = '{{ data.alter_text_06 }}';
			$colors['alter_bg_color_07'] = '{{ data.alter_bg_color_07 }}';
			$colors['alter_bg_color_04'] = '{{ data.alter_bg_color_04 }}';
			$colors['alter_bg_color_03'] = '{{ data.alter_bg_color_03 }}';
			$colors['alter_bg_color_02'] = '{{ data.alter_bg_color_02 }}';
			$colors['alter_bd_color_02'] = '{{ data.alter_bd_color_02 }}';
			$colors['text_dark_07'] = '{{ data.text_dark_07 }}';
			$colors['text_link_02'] = '{{ data.text_link_02 }}';
			$colors['text_link_03'] = '{{ data.text_link_03 }}';
			$colors['text_link_07'] = '{{ data.text_link_07 }}';
			$colors['text_hover_07'] = '{{ data.text_hover_07 }}';
			$colors['text_hover_08'] = '{{ data.text_hover_08 }}';
			$colors['text_link_blend'] = '{{ data.text_link_blend }}';
			$colors['alter_link_blend'] = '{{ data.alter_link_blend }}';
		}
		return $colors;
	}
}


			
// Additional theme-specific fonts rules
// Attention! Don't forget setup fonts rules also in the theme.customizer.color-scheme.js
if (!function_exists('snowmountain_customizer_add_theme_fonts')) {
	function snowmountain_customizer_add_theme_fonts($fonts) {
		$rez = array();	
		foreach ($fonts as $tag => $font) {
			if (substr($font['font-family'], 0, 2) != '{{') {
				$rez[$tag.'_font-family'] 		= !empty($font['font-family']) && !snowmountain_is_inherit($font['font-family'])
														? 'font-family:' . trim($font['font-family']) . ';' 
														: '';
				$rez[$tag.'_font-size'] 		= !empty($font['font-size']) && !snowmountain_is_inherit($font['font-size'])
														? 'font-size:' . snowmountain_prepare_css_value($font['font-size']) . ";"
														: '';
				$rez[$tag.'_line-height'] 		= !empty($font['line-height']) && !snowmountain_is_inherit($font['line-height'])
														? 'line-height:' . trim($font['line-height']) . ";"
														: '';
				$rez[$tag.'_font-weight'] 		= !empty($font['font-weight']) && !snowmountain_is_inherit($font['font-weight'])
														? 'font-weight:' . trim($font['font-weight']) . ";"
														: '';
				$rez[$tag.'_font-style'] 		= !empty($font['font-style']) && !snowmountain_is_inherit($font['font-style'])
														? 'font-style:' . trim($font['font-style']) . ";"
														: '';
				$rez[$tag.'_text-decoration'] 	= !empty($font['text-decoration']) && !snowmountain_is_inherit($font['text-decoration'])
														? 'text-decoration:' . trim($font['text-decoration']) . ";"
														: '';
				$rez[$tag.'_text-transform'] 	= !empty($font['text-transform']) && !snowmountain_is_inherit($font['text-transform'])
														? 'text-transform:' . trim($font['text-transform']) . ";"
														: '';
				$rez[$tag.'_letter-spacing'] 	= !empty($font['letter-spacing']) && !snowmountain_is_inherit($font['letter-spacing'])
														? 'letter-spacing:' . trim($font['letter-spacing']) . ";"
														: '';
				$rez[$tag.'_margin-top'] 		= !empty($font['margin-top']) && !snowmountain_is_inherit($font['margin-top'])
														? 'margin-top:' . snowmountain_prepare_css_value($font['margin-top']) . ";"
														: '';
				$rez[$tag.'_margin-bottom'] 	= !empty($font['margin-bottom']) && !snowmountain_is_inherit($font['margin-bottom'])
														? 'margin-bottom:' . snowmountain_prepare_css_value($font['margin-bottom']) . ";"
														: '';
			} else {
				$rez[$tag.'_font-family']		= '{{ data["'.$tag.'_font-family"] }}';
				$rez[$tag.'_font-size']			= '{{ data["'.$tag.'_font-size"] }}';
				$rez[$tag.'_line-height']		= '{{ data["'.$tag.'_line-height"] }}';
				$rez[$tag.'_font-weight']		= '{{ data["'.$tag.'_font-weight"] }}';
				$rez[$tag.'_font-style']		= '{{ data["'.$tag.'_font-style"] }}';
				$rez[$tag.'_text-decoration']	= '{{ data["'.$tag.'_text-decoration"] }}';
				$rez[$tag.'_text-transform']	= '{{ data["'.$tag.'_text-transform"] }}';
				$rez[$tag.'_letter-spacing']	= '{{ data["'.$tag.'_letter-spacing"] }}';
				$rez[$tag.'_margin-top']		= '{{ data["'.$tag.'_margin-top"] }}';
				$rez[$tag.'_margin-bottom']		= '{{ data["'.$tag.'_margin-bottom"] }}';
			}
		}
		return $rez;
	}
}


//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------

if ( !function_exists('snowmountain_customizer_theme_setup') ) {
	add_action( 'after_setup_theme', 'snowmountain_customizer_theme_setup' );
	function snowmountain_customizer_theme_setup() {

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size(370, 0, false);
		
		// Add thumb sizes
		// ATTENTION! If you change list below - check filter's names in the 'trx_addons_filter_get_thumb_size' hook
		$thumb_sizes = apply_filters('snowmountain_filter_add_thumb_sizes', array(
			'snowmountain-thumb-huge'		=> array(1170, 658, true),
			'snowmountain-thumb-big' 		=> array( 770, 410, true),
			'snowmountain-thumb-med' 		=> array( 740, 520, true),
			'snowmountain-thumb-team' 		=> array( 540, 520, true),
			'snowmountain-thumb-events' 		=> array( 740, 740, true),
			'snowmountain-thumb-events-wide'	=> array( 1140,740, true),
			'snowmountain-thumb-tiny' 		=> array( 280, 280, true),
			'snowmountain-thumb-masonry-big' => array( 770,   0, false),		// Only downscale, not crop
			'snowmountain-thumb-masonry'		=> array( 370,   0, false),		// Only downscale, not crop
			)
		);
		$mult = snowmountain_get_theme_option('retina_ready', 1);
		if ($mult > 1) $GLOBALS['content_width'] = apply_filters( 'snowmountain_filter_content_width', 1170*$mult);
		foreach ($thumb_sizes as $k=>$v) {
			// Add Original dimensions
			add_image_size( $k, $v[0], $v[1], $v[2]);
			// Add Retina dimensions
			if ($mult > 1) add_image_size( $k.'-@retina', $v[0]*$mult, $v[1]*$mult, $v[2]);
		}

	}
}

if ( !function_exists('snowmountain_customizer_image_sizes') ) {
	add_filter( 'image_size_names_choose', 'snowmountain_customizer_image_sizes' );
	function snowmountain_customizer_image_sizes( $sizes ) {
		$thumb_sizes = apply_filters('snowmountain_filter_add_thumb_sizes', array(
			'snowmountain-thumb-huge'		=> esc_html__( 'Fullsize image', 'snowmountain' ),
			'snowmountain-thumb-big'			=> esc_html__( 'Large image', 'snowmountain' ),
			'snowmountain-thumb-med'			=> esc_html__( 'Medium image', 'snowmountain' ),
			'snowmountain-thumb-tiny'		=> esc_html__( 'Small square avatar', 'snowmountain' ),
			'snowmountain-thumb-masonry-big'	=> esc_html__( 'Masonry Large (scaled)', 'snowmountain' ),
			'snowmountain-thumb-masonry'		=> esc_html__( 'Masonry (scaled)', 'snowmountain' ),
			)
		);
		$mult = snowmountain_get_theme_option('retina_ready', 1);
		foreach($thumb_sizes as $k=>$v) {
			$sizes[$k] = $v;
			if ($mult > 1) $sizes[$k.'-@retina'] = $v.' '.esc_html__('@2x', 'snowmountain' );
		}
		return $sizes;
	}
}

// Remove some thumb-sizes from the ThemeREX Addons list
if ( !function_exists( 'snowmountain_customizer_trx_addons_add_thumb_sizes' ) ) {
	add_filter( 'trx_addons_filter_add_thumb_sizes', 'snowmountain_customizer_trx_addons_add_thumb_sizes');
	function snowmountain_customizer_trx_addons_add_thumb_sizes($list=array()) {
		if (is_array($list)) {
			foreach ($list as $k=>$v) {
				if (in_array($k, array(
								'trx_addons-thumb-huge',
								'trx_addons-thumb-big',
								'trx_addons-thumb-medium',
								'trx_addons-thumb-tiny',
								'trx_addons-thumb-masonry-big',
								'trx_addons-thumb-masonry',
								)
							)
						) unset($list[$k]);
			}
		}
		return $list;
	}
}

// and replace removed styles with theme-specific thumb size
if ( !function_exists( 'snowmountain_customizer_trx_addons_get_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_get_thumb_size', 'snowmountain_customizer_trx_addons_get_thumb_size');
	function snowmountain_customizer_trx_addons_get_thumb_size($thumb_size='') {
		return str_replace(array(
							'trx_addons-thumb-huge',
							'trx_addons-thumb-huge-@retina',
							'trx_addons-thumb-big',
							'trx_addons-thumb-big-@retina',
							'trx_addons-thumb-medium',
							'trx_addons-thumb-medium-@retina',
							'trx_addons-thumb-tiny',
							'trx_addons-thumb-tiny-@retina',
							'trx_addons-thumb-masonry-big',
							'trx_addons-thumb-masonry-big-@retina',
							'trx_addons-thumb-masonry',
							'trx_addons-thumb-masonry-@retina',
                            'trx_addons-thumb-events',
							'trx_addons-thumb-events-@retina',
                            'trx_addons-thumb-events-wide',
							'trx_addons-thumb-events-wide-@retina',
                            'trx_addons-thumb-team',
							'trx_addons-thumb-team-@retina',
							),
							array(
							'snowmountain-thumb-huge',
							'snowmountain-thumb-huge-@retina',
							'snowmountain-thumb-big',
							'snowmountain-thumb-big-@retina',
							'snowmountain-thumb-med',
							'snowmountain-thumb-med-@retina',
							'snowmountain-thumb-tiny',
							'snowmountain-thumb-tiny-@retina',
							'snowmountain-thumb-masonry-big',
							'snowmountain-thumb-masonry-big-@retina',
							'snowmountain-thumb-masonry',
							'snowmountain-thumb-masonry-@retina',
                            'snowmountain-thumb-events',
							'snowmountain-thumb-events-@retina',
                            'snowmountain-thumb-events-wide',
							'snowmountain-thumb-events-wide-@retina',
                            'snowmountain-thumb-team',
							'snowmountain-thumb-team-@retina',
							),
							$thumb_size);
	}
}
?>