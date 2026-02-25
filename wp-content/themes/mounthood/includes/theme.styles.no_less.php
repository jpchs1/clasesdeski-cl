<?php
/**
 * Theme custom styles without LESS
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('mounthood_action_theme_styles_no_less_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_action_theme_styles_no_less_theme_setup', 1 );
	function mounthood_action_theme_styles_no_less_theme_setup() {

		// If no LESS support
		if (mounthood_get_theme_setting('less_compiler') == 'no') {
			// Add theme styles
			add_action('mounthood_action_add_styles', 				'mounthood_action_theme_styles_no_less_add_styles');

			// Add colors and fonts for the WP Customizer
			add_action( 'customize_controls_print_footer_scripts',	'mounthood_customizer_wp_css_template' );
			add_action( 'mounthood_filter_get_css',					'mounthood_customizer_wp_add_scheme_in_css', 100, 4 );
		}
	}
}


// Add theme stylesheets
if (!function_exists('mounthood_action_theme_styles_no_less_add_styles')) {
	//Handler of add_action('mounthood_action_add_styles', 'mounthood_action_theme_styles_no_less_add_styles');
	function mounthood_action_theme_styles_no_less_add_styles() {
		// Add stylesheet files
			wp_add_inline_style( 'mounthood-main-style', mounthood_get_custom_css() . mounthood_get_inline_css() );
	}
}


// Add scheme name in each selector in the CSS (priority 100 - after complete css)
if (!function_exists('mounthood_customizer_wp_add_scheme_in_css')) {
	//Handler of add_action( 'mounthood_filter_get_css', 'mounthood_customizer_wp_add_scheme_in_css', 100, 4 );
	function mounthood_customizer_wp_add_scheme_in_css($css, $colors, $fonts, $scheme) {
		$rez = '';
		$in_comment = $in_rule = false;
		$allow = true;
		$scheme_class = '.scheme_' . trim($scheme) . ' ';
		$self_class = '.scheme_self';
		$self_class_len = mounthood_strlen($self_class);
		$css_str = str_replace(array('{{', '}}'), array('[[',']]'), $css['colors']);
		for ($i=0; $i<strlen($css_str); $i++) {
			$ch = $css_str[$i];
			if ($in_comment) {
				$rez .= $ch;
				if ($ch=='/' && $css_str[$i-1]=='*') {
					$in_comment = false;
					$allow = !$in_rule;
				}
			} else if ($in_rule) {
				$rez .= $ch;
				if ($ch=='}') {
					$in_rule = false;
					$allow = !$in_comment;
				}
			} else {
				if ($ch=='/' && $css_str[$i+1]=='*') {
					$rez .= $ch;
					$in_comment = true;
				} else if ($ch=='{') {
					$rez .= $ch;
					$in_rule = true;
				} else if ($ch==',') {
					$rez .= $ch;
					$allow = true;
				} else if (mounthood_strpos(" \t\r\n", $ch)===false) {
					if ($allow && mounthood_substr($css_str, $i, $self_class_len) == $self_class) {
						$rez .= trim($scheme_class);
						$i += $self_class_len - 1;
					} else
						$rez .= ($allow ? $scheme_class : '') . $ch;
					$allow = false;
				} else {
					$rez .= $ch;
				}
			}
		}
		$rez = str_replace(array('[[',']]'), array('{{', '}}'), $rez);
		$css['colors'] = $rez;
		return $css;
	}
}



// Output an Underscore template for generating CSS for the color scheme.
// The template generates the css dynamically for instant display in the Customizer preview.
if ( !function_exists( 'mounthood_customizer_wp_css_template' ) ) {
	//Handler of add_action( 'customize_controls_print_footer_scripts', 'mounthood_customizer_wp_css_template' );
	function mounthood_customizer_wp_css_template() {

		// Colors
		$colors = array(
			
			// Whole block border and background
			'bg_color'				=> '{{ data.bg_color }}',
			'bd_color'				=> '{{ data.bd_color }}',
			
			// Text and links colors
			'text'					=> '{{ data.text }}',
			'text_light'			=> '{{ data.text_light }}',
			'text_dark'				=> '{{ data.text_dark }}',
			'text_link'				=> '{{ data.text_link }}',
			'text_hover'			=> '{{ data.text_hover }}',
		
			// Alternative blocks (submenu, buttons, tabs, etc.)
			'alter_bg_color'		=> '{{ data.alter_bg_color }}',
			'alter_bg_hover'		=> '{{ data.alter_bg_hover }}',
			'alter_bd_color'		=> '{{ data.alter_bd_color }}',
			'alter_bd_hover'		=> '{{ data.alter_bd_hover }}',
			'alter_dark'			=> '{{ data.alter_dark }}',
			'alter_light'			=> '{{ data.alter_light }}',
			'alter_dark'			=> '{{ data.alter_dark }}',
			'alter_link'			=> '{{ data.alter_link }}',
			'alter_hover'			=> '{{ data.alter_hover }}',
		
			// Input fields (form's fields and textarea)
			'input_bg_color'		=> '{{ data.input_bg_color }}',
			'input_bg_hover'		=> '{{ data.input_bg_hover }}',
			'input_bd_color'		=> '{{ data.input_bd_color }}',
			'input_bd_hover'		=> '{{ data.input_bd_hover }}',
			'input_text'			=> '{{ data.input_text }}',
			'input_light'			=> '{{ data.input_light }}',
			'input_dark'			=> '{{ data.input_dark }}',

			// Inverse blocks (with background equal to the links color or one of accented colors)
			'inverse_text'			=> '{{ data.inverse_text }}',
			'inverse_light'			=> '{{ data.inverse_light }}',
			'inverse_dark'			=> '{{ data.inverse_dark }}',
			'inverse_link'			=> '{{ data.inverse_link }}',
			'inverse_hover'			=> '{{ data.inverse_hover }}',

			// Additional accented colors (if used in the current theme)
			// For example:
			'accent_color'			=> '{{ data.accent_color }}',
		);

		$tmpl_holder = 'script';

		$schemes = array_keys(mounthood_get_list_color_schemes());
		if (count($schemes) > 0) {
			foreach ($schemes as $scheme) {
				echo '<'.trim($tmpl_holder).' type="text/html" id="tmpl-mounthood-color-scheme-'.esc_attr($scheme).'">'
						. trim(mounthood_get_custom_css( $colors, false, false, $scheme ))
					. '</'.trim($tmpl_holder).'>';
			}
		}

		// Fonts
		$custom_fonts = mounthood_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			$fonts = array();
			foreach ($custom_fonts as $tag => $font) {
				$fonts[$tag.'_font-family']			= '{{ data["'.$tag.'_font-family"] }}';
				$fonts[$tag.'_font-size']			= '{{ data["'.$tag.'_font-size"] }}';
				$fonts[$tag.'_line-height']			= '{{ data["'.$tag.'_line-height"] }}';
				$fonts[$tag.'_line-height_value']	= '{{ data["'.$tag.'_line-height_value"] }}';
				$fonts[$tag.'_font-weight']			= '{{ data["'.$tag.'_font-weight"] }}';
				$fonts[$tag.'_font-style']			= '{{ data["'.$tag.'_font-style"] }}';
				$fonts[$tag.'_text-decoration']		= '{{ data["'.$tag.'_text-decoration"] }}';
				$fonts[$tag.'_margin-top']			= '{{ data["'.$tag.'_margin-top"] }}';
				$fonts[$tag.'_margin-top_value']	= '{{ data["'.$tag.'_margin-top_value"] }}';
				$fonts[$tag.'_margin-bottom']		= '{{ data["'.$tag.'_margin-bottom"] }}';
				$fonts[$tag.'_margin-bottom_value']	= '{{ data["'.$tag.'_margin-bottom_value"] }}';
			}
			echo '<'.trim($tmpl_holder).' type="text/html" id="tmpl-mounthood-fonts">'
					. trim(mounthood_get_custom_css( false, $fonts, false, false ))
				. '</'.trim($tmpl_holder).'>';
		}

	}
}

// Additional (calculated) theme-specific colors
// Attention! Don't forget setup custom colors also in the core.customizer.wp.color-scheme.js
if (!function_exists('mounthood_customizer_wp_add_theme_colors')) {
	function mounthood_customizer_wp_add_theme_colors($colors) {
		if (mounthood_substr($colors['text'], 0, 2) == '{{') {
			$colors['alter_link_0_8']	= '{{ data.alter_link_0_8 }}';
			$colors['text_dark_0_05']	= '{{ data.text_dark_0_05 }}';
			$colors['text_dark_0_1']	= '{{ data.text_dark_0_1 }}';
			$colors['text_dark_0_38']	= '{{ data.text_dark_0_38 }}';
			$colors['text_dark_0_8']	= '{{ data.text_dark_0_8 }}';
			$colors['text_dark_0_89']	= '{{ data.text_dark_0_89 }}';
			$colors['text_dark_0_91']	= '{{ data.text_dark_0_91 }}';
			$colors['text_dark_0_915']	= '{{ data.text_dark_0_915 }}';
			$colors['text_light_0_2']	= '{{ data.text_light_0_2 }}';
			$colors['text_light_0_345']	= '{{ data.text_light_0_345 }}';
			$colors['text_light_0_5']	= '{{ data.text_light_0_5 }}';
			$colors['text_light_0_53']	= '{{ data.text_light_0_53 }}';
			$colors['text_light_0_77']	= '{{ data.text_light_0_77 }}';
			$colors['text_link_0_3']	= '{{ data.text_link_0_3 }}';
			$colors['text_link_0_6']	= '{{ data.text_link_0_6 }}';
			$colors['text_link_0_8']	= '{{ data.text_link_0_8 }}';
			$colors['accent_color_0_3']	= '{{ data.accent_color_0_3 }}';
			$colors['accent_color_0_6']	= '{{ data.accent_color_0_6 }}';
			$colors['accent_color_0_8']	= '{{ data.accent_color_0_8 }}';
			$colors['text_hover_0_2']	= '{{ data.text_hover_0_2 }}';
			$colors['text_hover_0_3']	= '{{ data.text_hover_0_3 }}';
			$colors['text_hover_0_8']	= '{{ data.text_hover_0_8 }}';
			$colors['inverse_text_0_1']	= '{{ data.inverse_text_0_1 }}';
			$colors['inverse_text_0_5']	= '{{ data.inverse_text_0_5 }}';
			$colors['inverse_text_0_8']	= '{{ data.inverse_text_0_8 }}';
			$colors['bg_color_0_8']		= '{{ data.bg_color_0_8 }}';
			$colors['alter_dark_0_1']	= '{{ data.alter_dark_0_1 }}';
			$colors['alter_bg_color_0_8'] = '{{ data.alter_bg_color_0_8 }}';
			$colors['alter_bg_hover_0_5'] = '{{ data.alter_bg_hover_0_5 }}';
			$colors['alter_bd_color_0_1'] = '{{ data.alter_bd_color_0_1 }}';
			$colors['bd_color_0_5'] = '{{ data.bd_color_0_5 }}';
		} else {
			$colors['alter_link_0_8']	= mounthood_hex2rgba( $colors['alter_link'], 0.8 );
			$colors['text_dark_0_05']	= mounthood_hex2rgba( $colors['text_dark'], 0.05 );
			$colors['text_dark_0_1']	= mounthood_hex2rgba( $colors['text_dark'], 0.1 );
			$colors['text_dark_0_38']	= mounthood_hex2rgba( $colors['text_dark'], 0.38 );
			$colors['text_dark_0_8']	= mounthood_hex2rgba( $colors['text_dark'], 0.8 );
			$colors['text_dark_0_89']	= mounthood_hex2rgba( $colors['text_dark'], 0.89 );
			$colors['text_dark_0_91']	= mounthood_hex2rgba( $colors['text_dark'], 0.91 );
			$colors['text_dark_0_915']	= mounthood_hex2rgba( $colors['text_dark'], 0.915 );
			$colors['text_light_0_2']	= mounthood_hex2rgba( $colors['text_light'], 0.2 );
			$colors['text_light_0_345']	= mounthood_hex2rgba( $colors['text_light'], 0.345 );
			$colors['text_light_0_5']	= mounthood_hex2rgba( $colors['text_light'], 0.5 );
			$colors['text_light_0_53']	= mounthood_hex2rgba( $colors['text_light'], 0.53 );
			$colors['text_light_0_77']	= mounthood_hex2rgba( $colors['text_light'], 0.77 );
			$colors['text_link_0_3']	= mounthood_hex2rgba( $colors['text_link'], 0.3 );
			$colors['text_link_0_6']	= mounthood_hex2rgba( $colors['text_link'], 0.6 );
			$colors['text_link_0_8']	= mounthood_hex2rgba( $colors['text_link'], 0.8 );
			$colors['accent_color_0_3']	= mounthood_hex2rgba( $colors['accent_color'], 0.3 );
			$colors['accent_color_0_6']	= mounthood_hex2rgba( $colors['accent_color'], 0.6 );
			$colors['accent_color_0_8']	= mounthood_hex2rgba( $colors['accent_color'], 0.8 );
			$colors['text_hover_0_2']	= mounthood_hex2rgba( $colors['text_hover'], 0.2 );
			$colors['text_hover_0_3']	= mounthood_hex2rgba( $colors['text_hover'], 0.3 );
			$colors['text_hover_0_8']	= mounthood_hex2rgba( $colors['text_hover'], 0.8 );
			$colors['inverse_text_0_1']	= mounthood_hex2rgba( $colors['inverse_text'], 0.1 );
			$colors['inverse_text_0_5']	= mounthood_hex2rgba( $colors['inverse_text'], 0.5 );
			$colors['inverse_text_0_8']	= mounthood_hex2rgba( $colors['inverse_text'], 0.8 );
			$colors['bg_color_0_8']		= mounthood_hex2rgba( $colors['bg_color'], 0.8 );
			$colors['alter_dark_0_1']	= mounthood_hex2rgba( $colors['alter_dark'], 0.1 );
			$colors['alter_bg_color_0_8'] = mounthood_hex2rgba( $colors['alter_bg_color'], 0.8 );
			$colors['alter_bg_hover_0_5'] = mounthood_hex2rgba( $colors['alter_bg_hover'], 0.5 );
			$colors['alter_bd_color_0_1'] = mounthood_hex2rgba( $colors['alter_bd_color'], 0.1 );
			$colors['bd_color_0_5'] = mounthood_hex2rgba( $colors['bd_color'], 0.5 );
		}
		return $colors;
	}
}
			
// Additional (calculated) theme-specific font parameters
// Attention! Don't forget setup custom fonts also in the core.customizer.wp.color-scheme.js
if (!function_exists('mounthood_customizer_wp_add_theme_fonts')) {
	function mounthood_customizer_wp_add_theme_fonts($fonts) {
		if (mounthood_substr($fonts['h1_font-family'], 0, 2) == '{{') {
			$fonts['logo_margin-top_0_72']	 = '{{ data["logo_margin-top_0_72"] }}';
			$fonts['logo_margin-top_0_75']	 = '{{ data["logo_margin-top_0_75"] }}';
			$fonts['logo_margin-top_0_8']	 = '{{ data["logo_margin-top_0_8"] }}';
			$fonts['logo_margin-bottom_0_2'] = '{{ data["logo_margin-bottom_0_2"] }}';
			$fonts['logo_margin-bottom_0_5'] = '{{ data["logo_margin-bottom_0_5"] }}';
			$fonts['menu_margin-top_1']		 = '{{ data["menu_margin-top_1"] }}';
			$fonts['menu_margin-top_0_3']	 = '{{ data["menu_margin-top_0_3"] }}';
			$fonts['menu_margin-top_0_6']	 = '{{ data["menu_margin-top_0_6"] }}';
			$fonts['menu_margin-top_0_65']	 = '{{ data["menu_margin-top_0_65"] }}';
			$fonts['menu_margin-top_0_7']	 = '{{ data["menu_margin-top_0_7"] }}';
			$fonts['menu_margin-top_0_8']	 = '{{ data["menu_margin-top_0_8"] }}';
			$fonts['menu_margin-top_1_0']	 = '{{ data["menu_margin-top_1_0"] }}';
			$fonts['menu_margin-top_1_05']	 = '{{ data["menu_margin-top_1_05"] }}';
			$fonts['menu_margin-bottom_1']	 = '{{ data["menu_margin-bottom_1"] }}';
			$fonts['menu_margin-bottom_0_5'] = '{{ data["menu_margin-bottom_0_5"] }}';
			$fonts['menu_margin-bottom_0_6'] = '{{ data["menu_margin-bottom_0_6"] }}';
			$fonts['menu_height_1']			 = '{{ data["menu_height_1"] }}';
			$fonts['menu_height_0_755'] 		 = '{{ data["menu_height_0_755"] }}';
			$fonts['submenu_margin-top_1']	 = '{{ data["submenu_margin-top_1"] }}';
			$fonts['submenu_margin-bottom_1']= '{{ data["submenu_margin-bottom_1"] }}';
		} else {
			$fonts['logo_margin-top_0_72']	 = mounthood_multiply_css_value( $fonts['logo_margin-top_value'], 0.72 );
			$fonts['logo_margin-top_0_75']	 = mounthood_multiply_css_value( $fonts['logo_margin-top_value'], 0.75 );
			$fonts['logo_margin-top_0_8']	 = mounthood_multiply_css_value( $fonts['logo_margin-top_value'], 1.32 );
			$fonts['logo_margin-bottom_0_2'] = mounthood_multiply_css_value( $fonts['logo_margin-bottom_value'], 0.2 );
			$fonts['logo_margin-bottom_0_5'] = mounthood_multiply_css_value( $fonts['logo_margin-bottom_value'], 0.5 );
			$fonts['menu_margin-top_1']		 = $fonts['menu_margin-top_value'];
			$fonts['menu_margin-top_0_3']	 = mounthood_multiply_css_value( $fonts['menu_margin-top_value'], 0.3 );
			$fonts['menu_margin-top_0_6']	 = mounthood_multiply_css_value( $fonts['menu_margin-top_value'], 0.6 );
			$fonts['menu_margin-top_0_65']	 = mounthood_multiply_css_value( $fonts['menu_margin-top_value'], 0.65 );
			$fonts['menu_margin-top_0_7']	 = mounthood_multiply_css_value( $fonts['menu_margin-top_value'], 0.7 );
			$fonts['menu_margin-top_0_8']	 = mounthood_multiply_css_value( $fonts['menu_margin-top_value'], 0.8 );
			$fonts['menu_margin-top_1_0']	 = mounthood_multiply_css_value( $fonts['menu_margin-top_value'], 1.0 );
			$fonts['menu_margin-top_1_05']	 = mounthood_multiply_css_value( $fonts['menu_margin-top_value'], 1.05 );
			$fonts['menu_margin-bottom_1']	 = $fonts['menu_margin-bottom_value'];
			$fonts['menu_margin-bottom_0_5'] = mounthood_multiply_css_value( $fonts['menu_margin-bottom_value'], 0.5 );
			$fonts['menu_margin-bottom_0_6'] = mounthood_multiply_css_value( $fonts['menu_margin-bottom_value'], 0.6 );
			$fonts['menu_height_1']			 = mounthood_summ_css_value( mounthood_summ_css_value( $fonts['menu_margin-top_value'],  $fonts['menu_margin-bottom_value'] ), $fonts['menu_line-height_value'] );
			$fonts['menu_height_0_755'] = mounthood_multiply_css_value( $fonts['menu_height_1'], 0.755 );
			$fonts['submenu_margin-top_1']	 = $fonts['submenu_margin-top_value'];
			$fonts['submenu_margin-bottom_1']= $fonts['submenu_margin-bottom_value'];
		}
		return $fonts;
	}
}

// Return CSS with custom colors and fonts
if (!function_exists('mounthood_get_custom_css')) {

	function mounthood_get_custom_css($colors=null, $fonts=null, $minify=true, $only_scheme='') {

		$add_comment = $colors===null && $fonts===null && empty($only_scheme)
						? '/* ' . strip_tags( __("ATTENTION! This file was generated automatically! Don't change it!!!", 'mounthood') ) . "*/\n"
						: '';

		$css = $rez = array(
			'fonts' => '',
			'colors' => ''
		);
		
		// Prepare fonts
		if ($fonts===null) {
			$fonts_list = mounthood_get_list_fonts(false);
			$custom_fonts = mounthood_get_custom_fonts();
			$fonts = array();
			foreach ($custom_fonts as $tag => $font) {
				$fonts[$tag.'_font-family'] = !empty($font['font-family']) && !mounthood_is_inherit_option($font['font-family'])
												? "font-family:" . str_replace(' ('.esc_html__('uploaded font', 'mounthood').')', '', $font['font-family']) 
													. (isset($fonts_list[$font['font-family']]['family']) ? ',' . $fonts_list[$font['font-family']]['family'] : '' ) 
													. ";"
												: '';
				$fonts[$tag.'_font-size'] = !empty($font['font-size']) && !mounthood_is_inherit_option($font['font-size'])
												? "font-size:" . mounthood_prepare_css_value($font['font-size']) . ";"
												: '';
				$fonts[$tag.'_line-height'] = !empty($font['line-height']) && !mounthood_is_inherit_option($font['line-height'])
												? "line-height: " . mounthood_prepare_css_value($font['line-height']) . ";"
												: '';
				$fonts[$tag.'_line-height_value'] = !empty($font['line-height'])
												? mounthood_prepare_css_value($font['line-height'])
												: 'inherit';
				$fonts[$tag.'_font-weight'] = !empty($font['font-weight']) && !mounthood_is_inherit_option($font['font-weight'])
												? "font-weight: " . trim($font['font-weight']) . ";"
												: '';
				$fonts[$tag.'_font-style'] = !empty($font['font-style']) && !mounthood_is_inherit_option($font['font-style']) && mounthood_strpos($font['font-style'], 'i')!==false
												? "font-style: italic;"
												: '';
				$fonts[$tag.'_text-decoration'] = !empty($font['font-style']) && !mounthood_is_inherit_option($font['font-style']) && mounthood_strpos($font['font-style'], 'u')!==false
												? "text-decoration: underline;"
												: '';
				$fonts[$tag.'_margin-top'] = !empty($font['margin-top']) && !mounthood_is_inherit_option($font['margin-top'])
												? "margin-top: " . mounthood_prepare_css_value($font['margin-top']) . ";"
												: '';
				$fonts[$tag.'_margin-top_value'] = !empty($font['margin-top'])
												? mounthood_prepare_css_value($font['margin-top'])
												: 'inherit';
				$fonts[$tag.'_margin-bottom'] = !empty($font['margin-bottom']) && !mounthood_is_inherit_option($font['margin-bottom'])
												? "margin-bottom: " . mounthood_prepare_css_value($font['margin-bottom']) . ";"
												: '';
				$fonts[$tag.'_margin-bottom_value'] = !empty($font['margin-bottom'])
												? mounthood_prepare_css_value($font['margin-bottom'])
												: 'inherit';
			}
		}
		if ($fonts) {
			$fonts = mounthood_customizer_wp_add_theme_fonts($fonts);
			// Attention! All font's rules mustn't have ';' in the end of the row
			$rez['fonts'] = <<<FONTS

/* Theme typography */
body {
	{$fonts['p_font-family']}
	{$fonts['p_font-size']}
	{$fonts['p_font-weight']}
	{$fonts['p_font-style']}
	{$fonts['p_line-height']}
	{$fonts['p_text-decoration']}
}

h1 {
	{$fonts['h1_font-family']}
	{$fonts['h1_font-size']}
	{$fonts['h1_font-weight']}
	{$fonts['h1_font-style']}
	{$fonts['h1_line-height']}
	{$fonts['h1_text-decoration']}
	{$fonts['h1_margin-top']}
	{$fonts['h1_margin-bottom']}
}
h2 {
	{$fonts['h2_font-family']}
	{$fonts['h2_font-size']}
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_margin-top']}
	{$fonts['h2_margin-bottom']}
}
h3 {
	{$fonts['h3_font-family']}
	{$fonts['h3_font-size']}
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_margin-top']}
	{$fonts['h3_margin-bottom']}
}
h4 {
	{$fonts['h4_font-family']}
	{$fonts['h4_font-size']}
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_margin-top']}
	{$fonts['h4_margin-bottom']}
}
h5 {
	{$fonts['h5_font-family']}
	{$fonts['h5_font-size']}
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_margin-top']}
	{$fonts['h5_margin-bottom']}
}
h6 {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-weight']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_margin-top']}
	{$fonts['h6_margin-bottom']}
	letter-spacing: 0.025em;
}

a {
	{$fonts['link_font-family']}
	{$fonts['link_font-size']}
	{$fonts['link_font-weight']}
	{$fonts['link_font-style']}
	{$fonts['link_line-height']}
	{$fonts['link_text-decoration']}
}

/* Form fields settings */
input[type="text"],
input[type="number"],
input[type="email"],
input[type="search"],
input[type="password"],
select,
textarea {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
}

input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
}

/* Top panel - middle area */
.top_panel_middle .logo {
	{$fonts['logo_margin-top']}
	{$fonts['logo_margin-bottom']}
}
.logo .logo_text {
	{$fonts['logo_font-family']}
	{$fonts['logo_font-size']}
	{$fonts['logo_font-weight']}
	{$fonts['logo_font-style']}
	{$fonts['logo_line-height']}
	{$fonts['logo_text-decoration']}
}

.top_panel_middle .menu_main_wrap {
	margin-top: {$fonts['logo_margin-top_0_72']};
	margin-bottom: {$fonts['logo_margin-bottom_0_2']};
}
.top_panel_style_5 .top_panel_middle .logo {
	margin-bottom: {$fonts['logo_margin-bottom_0_5']};
}

body:not(.top_panel_fixed) .top_panel_style_6 .top_panel_middle .menu_main_wrap {
	{$fonts['logo_margin-top']}
}

.top_panel_style_6 .top_panel_middle .logo {
	margin-top: {$fonts['logo_margin-top_0_8']};
}


/* Main menu */
.menu_main_nav > li > a {
	padding:{$fonts['menu_margin-top_1']} 1.31em {$fonts['menu_margin-bottom_1']};
	{$fonts['menu_font-family']}
	{$fonts['menu_font-size']}
	{$fonts['menu_font-weight']}
	{$fonts['menu_font-style']}
	{$fonts['menu_line-height']}
	{$fonts['menu_text-decoration']}
}
.menu_main_nav > li ul {
	{$fonts['submenu_font-family']}
	{$fonts['submenu_font-size']}
	{$fonts['submenu_font-weight']}
	{$fonts['submenu_font-style']}
	{$fonts['submenu_line-height']}
	{$fonts['submenu_text-decoration']}
}
.menu_main_nav > li > ul {
	top:{$fonts['menu_height_0_755']};
}
.menu_main_nav > li ul li a {
	padding:{$fonts['submenu_margin-top_1']} 1.5em {$fonts['submenu_margin-bottom_1']};
}

/* Search field */
.top_panel_bottom .search_wrap,
.top_panel_inner_style_4 .search_wrap {
	padding-top:{$fonts['menu_margin-top_0_65']};
	padding-bottom:{$fonts['menu_margin-bottom_0_5']};
}

.top_panel_icon {
	margin: {$fonts['menu_margin-top_0_7']} 0 {$fonts['menu_margin-bottom_1']} 1.5em;
}

.top_panel_fixed .top_panel_icon:not(.menu_main_additional_button) {
	margin: {$fonts['menu_margin-top_1_0']} 0 {$fonts['menu_margin-bottom_0_5']} 1.5em;
}

/* Fixed menu */
.top_panel_fixed .menu_main_wrap {
	padding-top:{$fonts['menu_margin-top_0_3']};
}
.top_panel_fixed .top_panel_wrap .logo {
	margin-top: {$fonts['menu_margin-top_1_05']};
	margin-bottom: {$fonts['menu_margin-bottom_0_6']};
}

/* Header style 8 */
.top_panel_inner_style_8 .top_panel_buttons,
.top_panel_inner_style_8 .menu_pushy_wrap .menu_pushy_button {
	padding-top:{$fonts['menu_margin-top_1']};
	padding-bottom:{$fonts['menu_margin-bottom_1']};
}

/* Post info */
.post_info {
	{$fonts['info_font-family']}
	{$fonts['info_font-size']}
	{$fonts['info_font-weight']}
	{$fonts['info_font-style']}
	{$fonts['info_line-height']}
	{$fonts['info_text-decoration']}
	{$fonts['info_margin-top']}
	{$fonts['info_margin-bottom']}
}

/* Page 404 */
.post_item_404 .page_title,
.post_item_404 .page_subtitle {
	{$fonts['logo_font-family']}
}

/* Side menu */
.sidebar_outer_menu .menu_side_nav > li > a,
.sidebar_outer_menu .menu_side_responsive > li > a {
	{$fonts['menu_font-family']}
	{$fonts['menu_font-size']}
	{$fonts['menu_font-weight']}
	{$fonts['menu_font-style']}
	{$fonts['menu_line-height']}
	{$fonts['menu_text-decoration']}
}
.sidebar_outer_menu .menu_side_nav > li ul,
.sidebar_outer_menu .menu_side_responsive > li ul {
	{$fonts['submenu_font-family']}
	{$fonts['submenu_font-size']}
	{$fonts['submenu_font-weight']}
	{$fonts['submenu_font-style']}
	{$fonts['submenu_line-height']}
	{$fonts['submenu_text-decoration']}
}
.sidebar_outer_menu .menu_side_nav > li ul li a,
.sidebar_outer_menu .menu_side_responsive > li ul li a {
	padding:{$fonts['submenu_margin-top_1']} 1.5em {$fonts['submenu_margin-bottom_1']};
}


/* Booking Calendar */
.booking_font_custom,
.booking_day_container,
.booking_calendar_container_all {
	{$fonts['p_font-family']}
}
.booking_weekdays_custom {
	{$fonts['h1_font-family']}
}

/* Media Elements */
.mejs-container .mejs-controls .mejs-time {
	{$fonts['p_font-family']}
}

/* Shortcodes */
.sc_recent_news .post_item .post_title {
	{$fonts['h5_font-family']}
	{$fonts['h5_font-size']}
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_margin-top']}
	{$fonts['h5_margin-bottom']}
}
.sc_recent_news .post_item h6.post_title {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-weight']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_margin-top']}
	{$fonts['h6_margin-bottom']}
}

/* Themes Fonts */
.wpc-weather-id #wpc-weather.medium .now .time_temperature,
.sc_team_item .sc_team_item_info .sc_team_item_position,
.top_panel_wrap li li > a,
.menu_main_nav > li > a,
.top_panel_icon.menu_main_cart .cart_summa,
.top_panel_image .top_panel_image_header .top_panel_image_title,
.top_panel_image .top_panel_image_header .breadcrumbs,
.widget_area .post_item .post_title,
blockquote:before,
blockquote .sc_quote_title a,
.post_item_related .post_content_wrap a:before,
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.pagination_pages > a,
.pagination_pages > span,
.post_item .post_info,
.comments_list_wrap .comment_info .comment_author,
.comments_list_wrap .comment_reply a,
.comments_wrap .comments_field input.submit,
.widget_area .widget_title,
.widget_area .widget_categories ul li a,
.widget_area .widget_meta ul li a,
.widget_area .widget_contacts .contact_info,
.widget_area .widget_text .textwidget,
.widget_area .widget_calendar .weekday,
.widget_area .widget_calendar .month_cur a,
.widget_area .widget_product_tag_cloud a,
.widget_area .widget_tag_cloud a,
.mc4wp-form .mc4wp-form-fields input,
.sc_audio .sc_audio_author,
input[type="submit"],
input[type="button"],
button,
.sc_button,
.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item,
.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item,
figure figcaption,
.sc_image figcaption,
.sc_price_block .sc_price_block_title,
.sc_price_block .sc_price_block_money .sc_price,
.sc_price_block .sc_price_block_description,
.sc_services_item .sc_services_item_description p,
.sc_skills_style_1 .sc_skills_total,
.sc_skills_style_1 .sc_skills_info,
.sc_skills_counter .sc_skills_style_2 .sc_skills_total,
.sc_skills_counter .sc_skills_style_2 .sc_skills_info,
.sc_skills_bar .sc_skills_info,
.sc_skills_bar.sc_skills_horizontal .sc_skills_total,
.sc_skills_legend,
.sc_table table tr:first-child th,
.sc_table table tr:first-child td,
.sc_testimonials_style_testimonials-1 .sc_testimonial_item:before,
.sc_testimonials_style_testimonials-1 .sc_testimonial_author_name,
.woocommerce-ordering select,
.woocommerce ul.products li.product .price, 
.woocommerce-page ul.products li.product .price,
.woocommerce div.product p.price,
.woocommerce div.product p.price span > span,
.woocommerce .product_meta span,
.woocommerce div.product form.cart div.quantity input[type="number"], .woocommerce-page div.product form.cart div.quantity input[type="number"],
.single-product .content div.product .woocommerce-tabs .wc-tabs li a,
.woocommerce .widget_shopping_cart .cart_list li > a, .woocommerce-page .widget_shopping_cart .cart_list li > a,
.woocommerce.widget_shopping_cart .total, 
.woocommerce .widget_shopping_cart .total, 
.woocommerce-page.widget_shopping_cart .total, 
.woocommerce-page .widget_shopping_cart .total,
.woocommerce .widget_recently_viewed_products li > a,
.woocommerce .widget_price_filter .price_label,
.widget_area .widget_product_categories ul li a,
.woocommerce table.shop_table thead th, .woocommerce-page table.shop_table thead th,
[class*="sc_input_hover_"] input[type="text"],
[class*="sc_input_hover_"] input[type="number"],
[class*="sc_input_hover_"] input[type="email"],
[class*="sc_input_hover_"] input[type="tel"],
[class*="sc_input_hover_"] input[type="password"],
[class*="sc_input_hover_"] input[type="search"],
[class*="sc_input_hover_"] select,
[class*="sc_input_hover_"] textarea {
	{$fonts['h1_font-family']}
}

.widget_area_inner .widget_product_search input.search_field::-webkit-input-placeholder{
	{$fonts['h1_font-family']}
}
.widget_area_inner .widget_search input.search_field::-webkit-input-placeholder{
	{$fonts['h1_font-family']}
}
.widget_area_inner .widget_product_search input.search_field::-moz-placeholder{
	{$fonts['h1_font-family']}
}
.widget_area_inner .widget_search input.search_field::-moz-placeholder{
	{$fonts['h1_font-family']}
}
.widget_area_inner .widget_product_search input.search_field:-moz-placeholder{
	{$fonts['h1_font-family']}
}
.widget_area_inner .widget_search input.search_field:-moz-placeholder{
	{$fonts['h1_font-family']}
}
.widget_area_inner .widget_product_search input.search_field:-ms-input-placeholder{
	{$fonts['h1_font-family']}
}
.widget_area_inner .widget_search input.search_field:-ms-input-placeholder {
	{$fonts['h1_font-family']}
}

.sc_skills_counter .sc_skills_item .sc_skills_info .sc_skills_addinfo,
.widget_area .post_item .post_info,
.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount{
	{$fonts['p_font-family']}
}

FONTS;
		}

		if ($colors!==false) {
			$schemes = empty($only_scheme) ? array_keys(mounthood_get_list_color_schemes()) : array($only_scheme);
			if (count($schemes) > 0) {
				$step = 1;
				foreach ($schemes as $scheme) {
					// Prepare colors
					if (empty($only_scheme)) $colors = mounthood_get_scheme_colors($scheme);

					// Make theme-specific colors and tints
					$colors = mounthood_customizer_wp_add_theme_colors($colors);

					// Make styles
					$rez['colors'] = <<<CSS

/* 2. Theme Colors
------------------------------------------------------------------------- */

h1, h2, h3, h4, h5, h6,
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
	color: {$colors['alter_text']};
}
a {
	color: {$colors['text_link']};
}

.single-product .product [class*="sc_button_hover_fade"]:hover {
	color: {$colors['text_link']}!important;
}
a:hover {
	color: {$colors['text_hover']};
}

blockquote {
	background-color: {$colors['alter_bg_color']};
}
blockquote:before {
	color: {$colors['text_link']};
}
blockquote, blockquote p {
	color: {$colors['inverse_text']};
}
blockquote a {
	color: {$colors['inverse_text']};
}
blockquote a:hover {
	color: {$colors['accent_color']};
}

.accent1 {			color: {$colors['text_link']}; }
.accent1_bgc {		background-color: {$colors['text_link']}; }
.accent1_bg {		background: {$colors['text_link']}; }
.accent1_border {	border-color: {$colors['text_link']}; }
a.accent1:hover {	color: {$colors['text_hover']}; }

/* Portfolio hovers */
.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect3.colored .info,
.post_content.ih-item.circle.effect4.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect6.colored .info,
.post_content.ih-item.circle.effect7.colored .info,
.post_content.ih-item.circle.effect8.colored .info,
.post_content.ih-item.circle.effect9.colored .info,
.post_content.ih-item.circle.effect10.colored .info,
.post_content.ih-item.circle.effect11.colored .info,
.post_content.ih-item.circle.effect12.colored .info,
.post_content.ih-item.circle.effect13.colored .info,
.post_content.ih-item.circle.effect14.colored .info,
.post_content.ih-item.circle.effect15.colored .info,
.post_content.ih-item.circle.effect16.colored .info,
.post_content.ih-item.circle.effect18.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect1.colored .info,
.post_content.ih-item.square.effect2.colored .info,
.post_content.ih-item.square.effect3.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect5.colored .info,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect8.colored .info,
.post_content.ih-item.square.effect9.colored .info .info-back,
.post_content.ih-item.square.effect10.colored .info,
.post_content.ih-item.square.effect11.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect14.colored .info,
.post_content.ih-item.square.effect15.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect_book.colored .info,
.post_content.ih-item.square.effect_pull.colored .post_descr {
	background: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect_more.colored .info,
.post_content.ih-item.square.effect_dir.colored .info,
.post_content.ih-item.square.effect_shift.colored .info {
	background: {$colors['text_link_0_6']};
	color: {$colors['inverse_text']};
}
.post_content.ih-item.square.effect_border.colored .img,
.post_content.ih-item.square.effect_fade.colored .img,
.post_content.ih-item.square.effect_slide.colored .img {
	background: {$colors['text_link']};
}
.post_content.ih-item.square.effect_border.colored .info,
.post_content.ih-item.square.effect_fade.colored .info,
.post_content.ih-item.square.effect_slide.colored .info {
	color: {$colors['inverse_text']};
}
.post_content.ih-item.square.effect_border.colored .info:before,
.post_content.ih-item.square.effect_border.colored .info:after {
	border-color: {$colors['inverse_text']};
}

.post_content.ih-item.circle.effect1 .spinner {
	border-right-color: {$colors['text_link']};
	border-bottom-color: {$colors['text_link']};
}
.post_content.ih-item .post_readmore .post_readmore_label,
.post_content.ih-item .info a,
.post_content.ih-item .info a > span {
	color: {$colors['inverse_link']};
}
.post_content.ih-item .post_readmore:hover .post_readmore_label,
.post_content.ih-item .info a:hover,
.post_content.ih-item .info a:hover > span {
	color: {$colors['inverse_hover']};
}

/* Tables */
td, th {
	border-color: {$colors['bd_color']};
}
 table {
	color: {$colors['text_dark']};
}
 table th,
table td {
	border-color: {$colors['alter_bg_hover']};
}
.sc_table table tr:first-child {
	/*background-color: {$colors['text_hover']};*/
	/*color: {$colors['inverse_dark']};*/
}

.sc_table table tr td:nth-child(2n+1){
	background-color: {$colors['alter_bg_hover']};
	color: {$colors['inverse_text']};
}
.sc_table table tr td:nth-child(2n+0){
	background-color: {$colors['alter_bd_hover']};
	color: {$colors['inverse_text']};
}

.sc_table table tr:first-child td:nth-child(2n+1){
	background-color: {$colors['accent_color']};
	color: {$colors['text_dark']};
}
.sc_table table tr:first-child td:nth-child(2n+0){
	background-color: {$colors['accent_color']};
	color: {$colors['text_dark']};
}

/* Table of contents */
pre.code,
#toc .toc_item.current,
#toc .toc_item:hover {
	border-color: {$colors['text_link']};
}


::selection,
::-moz-selection { 
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

/* 3. Form fields settings
-------------------------------------------------------------- */

input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="search"],
input[type="password"],
select,
textarea {
	color: {$colors['inverse_text']};
	border-color: {$colors['alter_light']};
	background-color: {$colors['alter_light']};
}
input[type="text"]:hover,
input[type="number"]:hover,
input[type="email"]:hover,
input[type="tel"]:hover,
input[type="search"]:hover,
input[type="password"]:hover,
select:hover,
textarea:hover,
input[type="text"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="search"]:focus,
input[type="password"]:focus,
select:focus,
textarea:focus {
	color: {$colors['inverse_text']};
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
input::-webkit-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea::-webkit-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
input::-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea::-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:-ms-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:-ms-input-placeholder {
	color: {$colors['inverse_text']} !important;
}
input:hover::-webkit-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:hover::-webkit-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:hover::-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:hover::-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:hover:-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:hover:-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:hover:-ms-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:hover:-ms-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:focus::-webkit-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:focus::-webkit-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:focus::-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:focus::-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:focus:-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:focus:-moz-placeholder{
	color: {$colors['inverse_text']} !important;
}
input:focus:-ms-input-placeholder{
	color: {$colors['inverse_text']} !important;
}
textarea:focus:-ms-input-placeholder {
	color: {$colors['inverse_text']} !important;
}

fieldset {
	border-color: {$colors['bd_color']};
}
fieldset legend {
	background-color: {$colors['bg_color']};
	color: {$colors['text']};
}

/* ======================== INPUT'S STYLES ================== */

/* Accent */
.sc_input_hover_accent input[type="text"]:focus,
.sc_input_hover_accent input[type="number"]:focus,
.sc_input_hover_accent input[type="email"]:focus,
.sc_input_hover_accent input[type="password"]:focus,
.sc_input_hover_accent input[type="search"]:focus,
.sc_input_hover_accent select:focus,
.sc_input_hover_accent textarea:focus {
	box-shadow: 0px 0px 0px 2px {$colors['text_link']};
}
.sc_input_hover_accent input[type="text"] + label:before,
.sc_input_hover_accent input[type="number"] + label:before,
.sc_input_hover_accent input[type="email"] + label:before,
.sc_input_hover_accent input[type="password"] + label:before,
.sc_input_hover_accent input[type="search"] + label:before,
.sc_input_hover_accent select + label:before,
.sc_input_hover_accent textarea + label:before {
	color: {$colors['text_link_0_6']};
}

/* Path */
.sc_input_hover_path input[type="text"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="number"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="email"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="password"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="search"] + label > .sc_form_graphic,
.sc_input_hover_path textarea + label > .sc_form_graphic {
	stroke: {$colors['input_bd_color']};
}

/* Jump */
.sc_input_hover_jump .sc_form_label_content:before {
	color: {$colors['inverse_text']};
}
.sc_input_hover_jump input[type="text"],
.sc_input_hover_jump input[type="number"],
.sc_input_hover_jump input[type="email"],
.sc_input_hover_jump input[type="password"],
.sc_input_hover_jump input[type="search"],
.sc_input_hover_jump textarea {
	border-color: {$colors['input_bd_color']};
}
.sc_input_hover_jump input[type="text"]:focus,
.sc_input_hover_jump input[type="number"]:focus,
.sc_input_hover_jump input[type="email"]:focus,
.sc_input_hover_jump input[type="password"]:focus,
.sc_input_hover_jump input[type="search"]:focus,
.sc_input_hover_jump textarea:focus,
.sc_input_hover_jump input[type="text"].filled,
.sc_input_hover_jump input[type="number"].filled,
.sc_input_hover_jump input[type="email"].filled,
.sc_input_hover_jump input[type="password"].filled,
.sc_input_hover_jump input[type="search"].filled,
.sc_input_hover_jump textarea.filled {
	border-color: {$colors['text_link']};
}

/* Underline */
.sc_input_hover_underline input[type="text"] + label:before,
.sc_input_hover_underline input[type="number"] + label:before,
.sc_input_hover_underline input[type="email"] + label:before,
.sc_input_hover_underline input[type="password"] + label:before,
.sc_input_hover_underline input[type="search"] + label:before,
.sc_input_hover_underline textarea + label:before {
	background-color: {$colors['input_bd_color']};
}
.sc_input_hover_jump input[type="text"]:focus + label:before,
.sc_input_hover_jump input[type="number"]:focus + label:before,
.sc_input_hover_jump input[type="email"]:focus + label:before,
.sc_input_hover_jump input[type="password"]:focus + label:before,
.sc_input_hover_jump input[type="search"]:focus + label:before,
.sc_input_hover_jump textarea:focus + label:before,
.sc_input_hover_jump input[type="text"].filled + label:before,
.sc_input_hover_jump input[type="number"].filled + label:before,
.sc_input_hover_jump input[type="email"].filled + label:before,
.sc_input_hover_jump input[type="password"].filled + label:before,
.sc_input_hover_jump input[type="search"].filled + label:before,
.sc_input_hover_jump textarea.filled + label:before {
	background-color: {$colors['input_bd_hover']};
}
.sc_input_hover_underline input[type="text"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="number"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="email"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="password"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="search"] + label > .sc_form_label_content,
.sc_input_hover_underline textarea + label > .sc_form_label_content {
	color: {$colors['input_text']};
}
.sc_input_hover_underline input[type="text"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="number"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="email"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="password"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="search"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline textarea:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="text"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="number"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="email"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="password"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="search"].filled + label > .sc_form_label_content,
.sc_input_hover_underline textarea.filled + label > .sc_form_label_content {
	color: {$colors['text_dark']};
}

/* Iconed */
.sc_input_hover_iconed input[type="text"] + label,
.sc_input_hover_iconed input[type="number"] + label,
.sc_input_hover_iconed input[type="email"] + label,
.sc_input_hover_iconed input[type="password"] + label,
.sc_input_hover_iconed input[type="search"] + label,
.sc_input_hover_iconed textarea + label {
	color: {$colors['input_text']};
}
.sc_input_hover_iconed input[type="text"]:focus + label,
.sc_input_hover_iconed input[type="number"]:focus + label,
.sc_input_hover_iconed input[type="email"]:focus + label,
.sc_input_hover_iconed input[type="password"]:focus + label,
.sc_input_hover_iconed input[type="search"]:focus + label,
.sc_input_hover_iconed textarea:focus + label,
.sc_input_hover_iconed input[type="text"].filled + label,
.sc_input_hover_iconed input[type="number"].filled + label,
.sc_input_hover_iconed input[type="email"].filled + label,
.sc_input_hover_iconed input[type="password"].filled + label,
.sc_input_hover_iconed input[type="search"].filled + label,
.sc_input_hover_iconed textarea.filled + label {
	color: {$colors['text_dark']};
}

/* ======================== END INPUT'S STYLES ================== */



/* 6. Page layouts
-------------------------------------------------------------- */
.body_wrap {
	color: {$colors['text']};
}
.body_style_boxed .body_wrap {
	background-color: {$colors['bg_color']};
}

/* 7. Section's decorations
-------------------------------------------------------------- */

/* If in the Theme options set "Body filled", else - leave this sections transparent */
body:not(.video_bg_show),
body:not(.video_bg_show) .page_wrap,
.copy_wrap,
.sidebar_cart,
.widget_area_inner,
#page_preloader {
	background-color: {$colors['bg_color']};
}


.article_style_boxed .content > article > .post_content,
.article_style_boxed[class*="single-"] .content > .comments_wrap,
.article_style_boxed[class*="single-"] .content > article > .post_info_share,
.article_style_boxed:not(.layout_excerpt):not(.single) .content .post_item {
	background-color: {$colors['alter_bg_color']};
}


/* 7.1 Top panel
-------------------------------------------------------------- */

.top_panel_wrap_inner {
	background-color: {$colors['bg_color']};
}
.top_panel_fixed .top_panel_position_over.top_panel_wrap_inner {
	background-color: {$colors['alter_bg_color']} !important;
}

.top_panel_inner_style_3 .top_panel_cart_button,
.top_panel_inner_style_4 .top_panel_cart_button {
	background-color: {$colors['text_hover_0_2']};
}


body:not(.top_panel_over) .top_panel_style_6 {
	background-color: {$colors['alter_bg_hover']};
}
.top_panel_style_6 .top_panel_position_above .logo .logo_text,
.top_panel_style_6 .top_panel_position_above .logo .logo_slogan{
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_7 .top_panel_cart_button {
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_7 .top_panel_cart_button:hover {
	color: #b3c200;
}


.top_panel_style_8 .top_panel_buttons .top_panel_cart_button:before {
	background-color: {$colors['text_link']};
}
.top_panel_style_8 .top_panel_buttons .top_panel_cart_button:after {
	color: {$colors['inverse_text']};
}

.top_panel_middle .sidebar_cart:after,
.top_panel_middle .sidebar_cart {
	border-color: {$colors['alter_bg_color']};
	background-color: {$colors['alter_bg_color']};
}

.top_panel_inner_style_3 .top_panel_top,
.top_panel_inner_style_4 .top_panel_top,
.top_panel_inner_style_5 .top_panel_top,
.top_panel_inner_style_3 .top_panel_top .sidebar_cart,
.top_panel_inner_style_4 .top_panel_top .sidebar_cart {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}

.top_panel_top a {
	color: {$colors['text']};
}
.top_panel_top a:hover {
	color: {$colors['text_hover']};
}
.top_panel_inner_style_3 .top_panel_top a,
.top_panel_inner_style_3 .sc_socials.sc_socials_type_icons a,
.top_panel_inner_style_4 .top_panel_top a,
.top_panel_inner_style_4 .sc_socials.sc_socials_type_icons a,
.top_panel_inner_style_5 .top_panel_top a,
.top_panel_inner_style_5 .sc_socials.sc_socials_type_icons a {
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_3 .top_panel_top a:hover,
.top_panel_inner_style_3 .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_4 .top_panel_top a:hover,
.top_panel_inner_style_4 .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_5 .top_panel_top a:hover,
.top_panel_inner_style_5 .sc_socials.sc_socials_type_icons a:hover {
	color: {$colors['inverse_hover']};
}

.top_panel_inner_style_3 .search_results .post_info a > span,
.top_panel_inner_style_3 .search_results .post_info a[class*="icon-"] {
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_3 .search_results .post_info a[class*="icon-"]:hover {
	color: {$colors['inverse_hover']};
}

/* User menu */
.menu_user_nav > li > a {
	color: {$colors['text']};
}
.menu_user_nav > li > a:hover {
	color: {$colors['text_hover']};
}
.top_panel_inner_style_3 .menu_user_nav > li > a,
.top_panel_inner_style_4 .menu_user_nav > li > a,
.top_panel_inner_style_5 .menu_user_nav > li > a {
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_3 .menu_user_nav > li > a:hover,
.top_panel_inner_style_4 .menu_user_nav > li > a:hover,
.top_panel_inner_style_5 .menu_user_nav > li > a:hover {
	color: {$colors['inverse_hover']};
}

.menu_user_nav > li ul:not(.cart_list) {
	border-color: {$colors['bd_color']};
	background-color: {$colors['bg_color']};
}
.top_panel_inner_style_1 .menu_user_nav > li > ul:after,
.top_panel_inner_style_2 .menu_user_nav > li > ul:after {
	border-color: {$colors['bd_color']};
	background-color: {$colors['bg_color']};
}

.top_panel_inner_style_3 .menu_user_nav > li > ul:after,
.top_panel_inner_style_4 .menu_user_nav > li > ul:after,
.top_panel_inner_style_5 .menu_user_nav > li > ul:after,
.top_panel_inner_style_3 .menu_user_nav > li ul,
.top_panel_inner_style_4 .menu_user_nav > li ul,
.top_panel_inner_style_5 .menu_user_nav > li ul {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
	border-color: {$colors['text_hover']};
}
.menu_user_nav > li ul li a {
	color: {$colors['alter_dark']};
}
.menu_user_nav > li ul li a:hover,
.menu_user_nav > li ul li.current-menu-item > a,
.menu_user_nav > li ul li.current-menu-ancestor > a {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_hover']};
}
.top_panel_inner_style_3 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-ancestor > a {
	background-color: {$colors['text_hover']};
}

.menu_user_nav > li.menu_user_controls .user_avatar {
	border-color: {$colors['bd_color']};
}
.top_panel_inner_style_3 .menu_user_nav > li.menu_user_controls .user_avatar,
.top_panel_inner_style_4 .menu_user_nav > li.menu_user_controls .user_avatar,
.top_panel_inner_style_5 .menu_user_nav > li.menu_user_controls .user_avatar {
	border-color: {$colors['inverse_text']};
}

/* Bookmarks */
.menu_user_nav > li.menu_user_bookmarks .bookmarks_add {
	border-bottom-color: {$colors['alter_bd_color']};
}


/* Top panel - middle area */
body:not(.top_panel_fixed) .top_panel_middle {
	background-color: {$colors['alter_bg_hover']};
}
.top_panel_position_over.top_panel_middle {
	background-color: {$colors['alter_bg_color_0_8']};
}
.logo .logo_text {
	color: {$colors['text_dark']};
}
.logo .logo_slogan {
	color: {$colors['text']};
}


/* Top panel (bottom area) */
.top_panel_bottom {
	background-color: {$colors['text_link']};
}



/* Top panel image in the header 7  */
.top_panel_image_hover {
	background-color: {$colors['accent_color_0_8']};
}
.top_panel_image_header,
.top_panel_image_title,
.top_panel_over:not(.top_panel_fixed) .top_panel_style_7 .logo_text,
.top_panel_over:not(.top_panel_fixed) .top_panel_style_7 .logo_slogan {
	color: {$colors['inverse_text']};
}
.top_panel_image_header a,
.top_panel_image_title > a,
.top_panel_over:not(.top_panel_fixed) .top_panel_style_7 .menu_main_nav > li > a {
	color: {$colors['inverse_link']};
}
.top_panel_style_7 + .top_panel_image a:hover {
	/*color: {$colors['text_dark']};*/
}
.top_panel_style_7 + .top_panel_image a:hover::after{
	background-color: {$colors['inverse_link']};
}
.post_featured .post_header a:hover,
.top_panel_over:not(.top_panel_fixed) .top_panel_style_7 .menu_main_nav > li > a:hover {
	color: {$colors['inverse_hover']};
}

.top_panel_image .top_panel_image_header .top_panel_image_title {
	color: {$colors['alter_text']};
}



/* Main menu */
.menu_main_nav > li > a {
	color: {$colors['inverse_text']};
}
.menu_main_nav > li ul {
	/*color: {$colors['alter_dark']};*/
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bg_color']};
}
.menu_main_nav > a:hover,
.menu_main_nav > li > a:hover,
.menu_main_nav > li.sfHover > a,
.menu_main_nav > li.current-menu-item > a,
.menu_main_nav > li.current-menu-parent > a,
.menu_main_nav > li.current-menu-ancestor > a {
	color: {$colors['inverse_text']};
}
.menu_main_nav > li ul li a {

	color: {$colors['inverse_text']};
}
.menu_main_nav > li ul li a:hover,
.menu_main_nav > li ul li.current-menu-item > a,
.menu_main_nav > li ul li.current-menu-ancestor > a {
	color: #b3c200;
	/*background-color: {$colors['alter_bg_hover']};*/
}



.top_panel_inner_style_1 .menu_main_nav > li > a,
.top_panel_inner_style_2 .menu_main_nav > li > a {
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_1 .menu_main_nav > li ul,
.top_panel_inner_style_2 .menu_main_nav > li ul {
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_link']};
}
.top_panel_inner_style_1 .menu_main_nav > li ul li a,
.top_panel_inner_style_2 .menu_main_nav > li ul li a {
	color: {$colors['inverse_link']};
}
.top_panel_inner_style_1 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-ancestor > a {
	color: {$colors['inverse_hover']};
	background-color: {$colors['text_hover']};
}


/* ---------------------- MENU HOVERS ----------------------- */

/* fade */
.top_panel_inner_style_1 .menu_hover_fade .menu_main_nav > a:hover,
.top_panel_inner_style_1 .menu_hover_fade .menu_main_nav > li > a:hover,
.top_panel_inner_style_1 .menu_hover_fade .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_1 .menu_hover_fade .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_1 .menu_hover_fade .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_1 .menu_hover_fade .menu_main_nav > li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_hover_fade .menu_main_nav > a:hover,
.top_panel_inner_style_2 .menu_hover_fade .menu_main_nav > li > a:hover,
.top_panel_inner_style_2 .menu_hover_fade .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_2 .menu_hover_fade .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_2 .menu_hover_fade .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_2 .menu_hover_fade .menu_main_nav > li.current-menu-ancestor > a {
	color: {$colors['inverse_hover']};
	background-color: {$colors['text_hover']};
}

/* slide_box */
.menu_hover_slide_box .menu_main_nav > li#blob {
	background-color: {$colors['alter_bg_hover']};
}
.top_panel_inner_style_1 .menu_hover_slide_box .menu_main_nav > li#blob,
.top_panel_inner_style_2 .menu_hover_slide_box .menu_main_nav > li#blob {
	background-color: {$colors['text_hover']};
}

/* slide_line */
.menu_hover_slide_line .menu_main_nav > li#blob {
	background-color: {$colors['inverse_text']};
}
.top_panel_inner_style_1 .menu_hover_slide_line .menu_main_nav > li#blob,
.top_panel_inner_style_2 .menu_hover_slide_line .menu_main_nav > li#blob {
	background-color: {$colors['inverse_text']};
}

/* zoom_line */
.menu_hover_zoom_line .menu_main_nav > li > a:before {
	background-color: {$colors['alter_hover']};
}
.top_panel_inner_style_1 .menu_hover_zoom_line .menu_main_nav > li > a:before,
.top_panel_inner_style_2 .menu_hover_zoom_line .menu_main_nav > li > a:before {
	background-color: {$colors['inverse_text']};
}

/* path_line */
.menu_hover_path_line .menu_main_nav > li:before,
.menu_hover_path_line .menu_main_nav > li:after,
.menu_hover_path_line .menu_main_nav > li > a:before,
.menu_hover_path_line .menu_main_nav > li > a:after {
	background-color: {$colors['alter_hover']};
}
.top_panel_inner_style_1 .menu_hover_path_line .menu_main_nav > li:before,
.top_panel_inner_style_1 .menu_hover_path_line .menu_main_nav > li:after,
.top_panel_inner_style_1 .menu_hover_path_line .menu_main_nav > li > a:before,
.top_panel_inner_style_1 .menu_hover_path_line .menu_main_nav > li > a:after,
.top_panel_inner_style_2 .menu_hover_path_line .menu_main_nav > li:before,
.top_panel_inner_style_2 .menu_hover_path_line .menu_main_nav > li:after,
.top_panel_inner_style_2 .menu_hover_path_line .menu_main_nav > li > a:before,
.top_panel_inner_style_2 .menu_hover_path_line .menu_main_nav > li > a:after {
	background-color: {$colors['inverse_text']};
}

/* roll_down */
.menu_hover_roll_down .menu_main_nav > li > a:before {
	background-color: {$colors['alter_hover']};
}
.top_panel_inner_style_1 .menu_hover_roll_down .menu_main_nav > li > a:before,
.top_panel_inner_style_2 .menu_hover_roll_down .menu_main_nav > li > a:before {
	background-color: {$colors['inverse_text']};
}

/* color_line */
.menu_hover_color_line .menu_main_nav > li > a:hover,
.menu_hover_color_line .menu_main_nav > li > a:focus {
	color: {$colors['alter_dark']};
}
.top_panel_inner_style_1 .menu_hover_color_line .menu_main_nav > li > a:hover,
.top_panel_inner_style_1 .menu_hover_color_line .menu_main_nav > li > a:focus,
.top_panel_inner_style_2 .menu_hover_color_line .menu_main_nav > li > a:hover,
.top_panel_inner_style_2 .menu_hover_color_line .menu_main_nav > li > a:focus {
	color: {$colors['inverse_text']};
}
.menu_hover_color_line .menu_main_nav > li > a:before {
	background-color: {$colors['alter_dark']};
}
.top_panel_inner_style_1 .menu_hover_color_line .menu_main_nav > li > a:before,
.top_panel_inner_style_2 .menu_hover_color_line .menu_main_nav > li > a:before {
	background-color: {$colors['inverse_text']};
}
.menu_hover_color_line .menu_main_nav > li > a:after {
	background-color: {$colors['alter_hover']};
}
.top_panel_inner_style_1 .menu_hover_color_line .menu_main_nav > li > a:after,
.top_panel_inner_style_2 .menu_hover_color_line .menu_main_nav > li > a:after {
	background-color: {$colors['inverse_hover']};
}
.menu_hover_color_line .menu_main_nav > li.sfHover > a,
.menu_hover_color_line .menu_main_nav > li > a:hover,
.menu_hover_color_line .menu_main_nav > li > a:focus {
	color: {$colors['alter_hover']};
}
.top_panel_inner_style_1 .menu_hover_color_line .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_1 .menu_hover_color_line .menu_main_nav > li > a:hover,
.top_panel_inner_style_1 .menu_hover_color_line .menu_main_nav > li > a:focus,
.top_panel_inner_style_2 .menu_hover_color_line .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_2 .menu_hover_color_line .menu_main_nav > li > a:hover,
.top_panel_inner_style_2 .menu_hover_color_line .menu_main_nav > li > a:focus {
	color: {$colors['inverse_hover']};
}

/* ---------------------- END MENU HOVERS ----------------------- */

/* Contact fields */
.top_panel_middle .contact_field,
.top_panel_middle .contact_field > a {
	color: {$colors['text_dark']}; 
}
.top_panel_middle .contact_icon {
	color: {$colors['text_light']}; 
	/*background-color: {$colors['bg_color']}; */
}

/* Search field */

.top_panel_icon.search_wrap input.search_field {
	color: {$colors['inverse_text']}; 
}

.top_panel_icon.search_wrap input.search_field::-webkit-input-placeholder{
	color: {$colors['inverse_text']};
}
.top_panel_icon.search_wrap input.search_field::-moz-placeholder{
	color: {$colors['inverse_text']};
}
.top_panel_icon.search_wrap input.search_field:-moz-placeholder{
	color: {$colors['inverse_text']};
}
.top_panel_icon.search_wrap input.search_field:-ms-input-placeholder{
	color: {$colors['inverse_text']};
}

.top_panel_icon.search_wrap .search_results_content {
	color: {$colors['text_dark']};
}

.top_panel_inner_style_1 .search_field,
.top_panel_inner_style_2 .search_field,
.top_panel_inner_style_3 .search_field {
	border-color: {$colors['inverse_text_0_1']};
	background-color: {$colors['text_hover_0_2']}; 
}
.top_panel_inner_style_4 .search_field {
	background-color: {$colors['text_dark_0_05']}; 
}
.content .search_wrap .search_form_wrap .search_field,
.content .search_wrap .search_form_wrap .search_submit{
	color: {$colors['inverse_text']}; 
}
.content .search_wrap .search_form_wrap .search_field:hover,
.content .search_wrap .search_form_wrap .search_field:focus{
	color: {$colors['alter_dark']}; 
}

.content .search_wrap .search_form_wrap .search_submit:hover,
.content .search_wrap .search_form_wrap .search_submit:focus{
	color: {$colors['text_link']}; 
}


.content .search_field {
	background-color: {$colors['alter_bg_color']}; 
}
.content .search_field,
.content .search_submit {
	color: {$colors['alter_dark']};
}
.content .search_field:focus,
.content .search_submit:hover {
	color: {$colors['alter_dark']};
}
.top_panel_inner_style_1 .search_field,
.top_panel_inner_style_1 .search_submit,
.top_panel_inner_style_2 .search_field,
.top_panel_inner_style_2 .search_submit,
.top_panel_inner_style_4 .search_field,
.top_panel_inner_style_4 .search_submit {
	color: {$colors['text_dark']};
}
.top_panel_inner_style_3 .search_field,
.top_panel_inner_style_3 .search_submit {
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_3 .search_field:focus,
.top_panel_inner_style_3 .search_submit:focus,
.top_panel_inner_style_3 .search_submit:hover {
	color: {$colors['inverse_text']};
}
.top_panel_inner_style_8 .search_field:hover,
.top_panel_inner_style_8 .search_field:focus {
	color: {$colors['alter_dark']} !important;
	background-color: {$colors['alter_bg_hover']} !important;
}

.top_panel_icon.search_wrap {
	/*background-color: {$colors['bg_color']};*/
	color: {$colors['inverse_text']};
}
.top_panel_icon .contact_icon,
.top_panel_icon .search_submit {
	color: {$colors['inverse_text']};
}

.top_panel_icon.menu_main_cart .contact_icon {
	/*background-color: {$colors['bg_color']};*/
}
.top_panel_middle a:hover .contact_icon,
.top_panel_icon.search_wrap:hover,
.top_panel_icon:hover .contact_icon,
.top_panel_icon:hover .search_submit {
	/*background-color: {$colors['text_link']};*/
}
.top_panel_icon .cart_summa {
	color: {$colors['inverse_text']};
}

.top_panel_icon.search_wrap:hover input,
.top_panel_icon.search_wrap:hover button,
.top_panel_icon:hover .search_submit {
	color: {$colors['text_link']};
}

.top_panel_middle a:hover .contact_icon,
.top_panel_icon:hover .contact_icon,
.top_panel_icon:hover .cart_summa {
    color: #b3c200;
}

.search_style_fullscreen.search_state_closed:not(.top_panel_icon) .search_submit,
.search_style_slide.search_state_closed:not(.top_panel_icon) .search_submit {
	color: {$colors['inverse_text']};
}
.search_style_expand.search_state_opened:not(.top_panel_icon) .search_submit:hover,
.search_style_slide.search_state_opened:not(.top_panel_icon) .search_submit:hover {
	color: {$colors['text_dark']};
}
.top_panel_inner_style_3 .search_style_expand.search_state_opened .search_submit:hover,
.top_panel_inner_style_3 .search_style_slide.search_state_opened .search_submit:hover {
	color: {$colors['inverse_text']};
}
.search_style_slide.search_state_opened:not(.top_panel_icon) .search_form_wrap {
	top: {$fonts['menu_margin-top_0_8']};
}


/* Search results */
.search_results .post_more,
.search_results .search_results_close {
	color: {$colors['text_link']};
}
.search_results .post_more:hover,
.search_results .search_results_close:hover {
	color: {$colors['text_hover']};
}
.top_panel_inner_style_1 .search_results,
.top_panel_inner_style_1 .search_results:after,
.top_panel_inner_style_2 .search_results,
.top_panel_inner_style_2 .search_results:after,
.top_panel_inner_style_3 .search_results,
.top_panel_inner_style_3 .search_results:after {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']}; 
	border-color: {$colors['text_hover']}; 
}
.top_panel_inner_style_1 .search_results a,
.top_panel_inner_style_1 .search_results .post_info a,
.top_panel_inner_style_1 .search_results .post_info a > span,
.top_panel_inner_style_1 .search_results .post_more,
.top_panel_inner_style_1 .search_results .search_results_close,
.top_panel_inner_style_2 .search_results a,
.top_panel_inner_style_2 .search_results .post_info a,
.top_panel_inner_style_2 .search_results .post_info a > span,
.top_panel_inner_style_2 .search_results .post_more,
.top_panel_inner_style_2 .search_results .search_results_close,
.top_panel_inner_style_3 .search_results a,
.top_panel_inner_style_3 .search_results .post_info a,
.top_panel_inner_style_3 .search_results .post_info a > span,
.top_panel_inner_style_3 .search_results .post_more,
.top_panel_inner_style_3 .search_results .search_results_close {
	color: {$colors['inverse_link']};
}
.top_panel_inner_style_1 .search_results a:hover,
.top_panel_inner_style_1 .search_results .post_info a:hover,
.top_panel_inner_style_1 .search_results .post_info a:hover > span,
.top_panel_inner_style_1 .search_results .post_more:hover,
.top_panel_inner_style_1 .search_results .search_results_close:hover,
.top_panel_inner_style_2 .search_results a:hover,
.top_panel_inner_style_2 .search_results .post_info a:hover,
.top_panel_inner_style_2 .search_results .post_info a:hover > span,
.top_panel_inner_style_2 .search_results .post_more:hover,
.top_panel_inner_style_2 .search_results .search_results_close:hover,
.top_panel_inner_style_3 .search_results a:hover,
.top_panel_inner_style_3 .search_results .post_info a:hover,
.top_panel_inner_style_3 .search_results .post_info a:hover > span,
.top_panel_inner_style_3 .search_results .post_more:hover,
.top_panel_inner_style_3 .search_results .search_results_close:hover {
	color: {$colors['inverse_hover']};
}

/* Header style 8 */
.top_panel_inner_style_8 .menu_pushy_wrap .menu_pushy_button {
	color: {$colors['alter_dark']}; 
}
.top_panel_inner_style_8 .menu_pushy_wrap .menu_pushy_button:hover {
	color: {$colors['alter_dark']}; 
}
.top_panel_inner_style_8 .top_panel_buttons .contact_icon,
.top_panel_inner_style_8 .top_panel_buttons .top_panel_icon .search_submit {
	color: {$colors['alter_dark']}; 
}
.top_panel_inner_style_8 .top_panel_buttons a:hover .contact_icon,
.top_panel_inner_style_8 .top_panel_buttons .top_panel_icon:hover .search_submit {
	color: {$colors['alter_dark']}; 
}
.pushy_inner {
	color: {$colors['text']}; 
	background-color: {$colors['bg_color']}; 
}
.pushy_inner a {
	color: {$colors['text_link']}; 
}
.pushy_inner a:hover {
	color: {$colors['text_hover']}; 
}
.pushy_inner ul ul {
	background-color: {$colors['alter_bg_color_0_8']}; 
}

/* Register and login popups */
.top_panel_inner_style_3 .popup_wrap a,
.top_panel_inner_style_3 .popup_wrap .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_4 .popup_wrap a,
.top_panel_inner_style_4 .popup_wrap .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_5 .popup_wrap a,
.top_panel_inner_style_5 .popup_wrap .sc_socials.sc_socials_type_icons a:hover {
	color: {$colors['text_link']};
}
.top_panel_inner_style_3 .popup_wrap a:hover,
.top_panel_inner_style_4 .popup_wrap a:hover,
.top_panel_inner_style_5 .popup_wrap a:hover {
	color: {$colors['text_hover']};
}
.top_panel_inner_style_3 .popup_wrap,
.top_panel_inner_style_4 .popup_wrap,
.top_panel_inner_style_5 .popup_wrap,
.top_panel_inner_style_3 .popup_wrap .popup_close,
.top_panel_inner_style_3 .popup_wrap .sc_socials.sc_socials_type_icons a,
.top_panel_inner_style_4 .popup_wrap .popup_close,
.top_panel_inner_style_4 .popup_wrap .sc_socials.sc_socials_type_icons a,
.top_panel_inner_style_5 .popup_wrap .popup_close,
.top_panel_inner_style_5 .popup_wrap .sc_socials.sc_socials_type_icons a {
	color: {$colors['text']};
}
.top_panel_inner_style_3 .popup_wrap .popup_close:hover,
.top_panel_inner_style_4 .popup_wrap .popup_close:hover,
.top_panel_inner_style_5 .popup_wrap .popup_close:hover {
	color: {$colors['text_dark']};
}


/* Header mobile */
.header_mobile {
	background-color: {$colors['alter_bg_hover']};
}
.header_mobile .sidebar_cart{
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bg_color']};
}
.header_mobile .menu_button,
.header_mobile .menu_main_cart .top_panel_cart_button .contact_icon {
	color: {$colors['inverse_text']};
}
.header_mobile .side_wrap {
	color: {$colors['inverse_text']};
}
.header_mobile .panel_top,
.header_mobile .side_wrap {
	background-color: {$colors['text_link']};
}
.header_mobile .panel_middle {
	background-color: {$colors['text_link']};
}
.header_mobile .panel_bottom {
	background-color: {$colors['text_hover']};
}

.header_mobile .menu_button:hover,
.header_mobile .menu_main_cart .top_panel_cart_button .contact_icon:hover,
.header_mobile .menu_main_cart.top_panel_icon:hover .top_panel_cart_button .contact_icon,
.header_mobile .side_wrap .close:hover{
	color: {$colors['text_link']};
}

.header_mobile .menu_main_nav > li a,
.header_mobile .menu_main_nav > li > a:hover {
	color: {$colors['inverse_link']};
}
.header_mobile .menu_main_nav > a:hover, 
.header_mobile .menu_main_nav > li.sfHover > a, 
.header_mobile .menu_main_nav > li.current-menu-item > a, 
.header_mobile .menu_main_nav > li.current-menu-parent > a, 
.header_mobile .menu_main_nav > li.current-menu-ancestor > a,
.header_mobile .menu_main_nav > li > a:hover,
.header_mobile .menu_main_nav > li ul li a:hover, 
.header_mobile .menu_main_nav > li ul li.current-menu-item > a, 
.header_mobile .menu_main_nav > li ul li.current-menu-ancestor > a,
.header_mobile .login a:hover {
    color: {$colors['inverse_hover']};
}
.header_mobile .popup_wrap .popup_close:hover {
    color: {$colors['text_dark']};
}

.header_mobile .search_wrap,
.header_mobile .login {
	border-color: {$colors['text_link']};
}
.header_mobile .login .popup_link,
.header_mobile .sc_socials.sc_socials_type_icons a {
	color: {$colors['inverse_link']};
}

.header_mobile .search_wrap .search_field,
.header_mobile .search_wrap .search_field:focus {  
	color: {$colors['inverse_text']};
}

.header_mobile .widget_shopping_cart ul.cart_list > li > a:hover {
	color: {$colors['inverse_text']};
}

.header_mobile .popup_wrap .sc_socials.sc_socials_type_icons a {
	color: {$colors['text_light']};
}




/* 7.2 Main Slider
-------------------------------------------------------------- */
.tparrows.default {
	color: {$colors['bg_color']};
}
.tp-bullets.simplebullets.round .bullet {
	background-color: {$colors['bg_color']};
}
.tp-bullets.simplebullets.round .bullet.selected {
	border-color: {$colors['bg_color']};
}
.slider_over_content_inner {
	background-color: {$colors['bg_color_0_8']};
}
.slider_over_button {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color_0_8']};
}
.slider_over_close {
	color: {$colors['text_dark']};
}
.tparrows:before{
	color: {$colors['text_dark']} !important;
}
.tparrows:hover::before{
	color: {$colors['inverse_text']} !important;
}


/* 7.3 Top panel: Page title and breadcrumbs
-------------------------------------------------------------- */

.top_panel_title_inner {
	background-color: {$colors['alter_bg_hover']};
	border-color: {$colors['alter_bd_color_0_1']};
}

.top_panel_title_inner .page_title {
	color: {$colors['alter_dark']};
}

.top_panel_title_inner .post_navi .post_navi_item a,
.top_panel_title_inner .breadcrumbs a.breadcrumbs_item {
	color: {$colors['alter_dark']};
}
.top_panel_title_inner .post_navi .post_navi_item a:hover,
.top_panel_title_inner .breadcrumbs a.breadcrumbs_item:hover {
	color: {$colors['alter_light']};
}
.top_panel_title_inner .post_navi span,
.top_panel_title_inner .breadcrumbs span {
	color: {$colors['alter_light']};
}
.post_navi .post_navi_item + .post_navi_item:before,
.top_panel_title_inner .breadcrumbs .breadcrumbs_delimiter {
	color: {$colors['alter_light']};
}





/* 7.4 Main content wrapper
-------------------------------------------------------------- */

/* Layout Excerpt */
.post_title .post_icon {
	color: {$colors['text_link']};
}

/* Blog pagination */
.pagination > a {
	border-color: {$colors['text_link']};
}



/* 7.5 Post formats
-------------------------------------------------------------- */

/* Aside */
.post_format_aside.post_item_single .post_content p,
.post_format_aside .post_descr {
	border-color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}
.post_item .post_info,
.post_item .post_info a,
.post_item .post_info a > span,
.post_item .post_info a[class*="icon-"]{
	color: {$colors['text_light']};
}

.post_content .post_info_bottom .post_info_item a,
.post_content .post_info_bottom .post_info_item a > span,
.post_content .post_info_bottom .post_info_item a[class*="icon-"] {
	color: {$colors['text_link']};
}
.post_content .post_info_bottom .post_info_item a:hover,
.post_content .post_info_bottom .post_info_item a:hover > span,
.post_content .post_info_bottom .post_info_item a[class*="icon-"]:hover {
	color: {$colors['alter_dark']};
}

.post_item .post_info_share .social_icons{
	color: {$colors['inverse_text']};
	background-color: {$colors['alter_dark']};
}

.post_item .post_info_share .social_icons > span {
	color: {$colors['inverse_text']};
}
.post_item .post_info_share .social_icons:hover{
	/*color: {$colors['inverse_text']};*/
	background-color: {$colors['text_link']};
}

.post_item .post_info_share .social_icons:hover > span {
	color: {$colors['text_dark']};
}


/* 7.6 Posts layouts
-------------------------------------------------------------- */

/* Hover icon */
.hover_icon:before {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.hover_icon:after {
	background-color: {$colors['bg_color_0_8']};
}
.sc_video_play_button.hover_icon:hover::before {
	/*color: {$colors['inverse_text']};*/
	/*background-color: {$colors['alter_bg_color']};*/
}

/* Post info */
.post_info a,
.post_info a > span {
	color: {$colors['text']};
}



.post_info a[class*="icon-"] {
	color: {$colors['text_link']};
}
.post_info a:hover,
.post_info a:hover > span {
	color: {$colors['text_hover']};
}
.post_item .post_readmore_label {
	color: {$colors['text_dark']};
}
.post_item .post_readmore:hover .post_readmore_label {
	color: {$colors['text_hover']};
}

/* Related posts */
.post_item_related .hover_icon:before {
	/*background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_text']};*/
}

.post_item_related .post_content_wrap a:before {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_text']};
}

.post_item_related .hover_icon:after {
	background-color: {$colors['text_link_0_8']};
}
.post_item_related .post_info a {
	color: {$colors['text']};
}
.post_item_related .post_info a:hover,
.post_item_related .post_title a:hover {
	color: {$colors['text_dark']};
}
.related_wrap .post_item_related,
.article_style_stretch .post_item_related {
	/*background-color: {$colors['alter_bg_color']};*/
}
.article_style_boxed.sidebar_show[class*="single-"] .related_wrap .post_item_related {
	background-color: {$colors['alter_bg_color']};
}


/* Style "Colored" */
.isotope_item_colored .post_featured .post_mark_new,
.isotope_item_colored .post_featured .post_title,
.isotope_item_colored .post_content.ih-item.square.colored .info {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.isotope_item_colored .post_featured .post_title a {
	color: {$colors['inverse_text']};
}

.isotope_item_colored .post_category a,
.isotope_item_colored .post_rating .reviews_stars_bg,
.isotope_item_colored .post_rating .reviews_stars_hover,
.isotope_item_colored .post_rating .reviews_value {
	color: {$colors['text_link']};
}

.isotope_item_colored .post_featured .post_descr {
	background-color: {$colors['alter_bg_color']};
}
.article_style_boxed .isotope_item_colored .post_featured .post_descr {
	background-color: {$colors['alter_bg_color']};
}
.isotope_item_colored .post_info_wrap .post_button .sc_button {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}

.isotope_item_colored_1 .post_item {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['alter_dark']};
}
.isotope_item_colored_1 a,
.isotope_item_colored_1 .post_title a {
	color: {$colors['text_link']};
}
.isotope_item_colored_1 a:hover,
.isotope_item_colored_1 .post_title a:hover,
.isotope_item_colored_1 .post_category a:hover {
	color: {$colors['alter_hover']};
}

/* Masonry and Portfolio */
.isotope_wrap .isotope_item_colored_1 .post_featured {
	border-color: {$colors['text_link']};
}
.isotope_wrap .isotope_item_masonry .post_content_overlay .post_title:hover a{
	color: {$colors['text_link']};
}

/* Masonry Classic */
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay:after {
	background-color: {$colors['accent_color_0_8']};
}
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content {
	background-color: {$colors['inverse_text']};
}

.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover {
	/*background-color: {$colors['text_link']};*/
}
.isotope_wrap .isotope_item_classic.isotope_column_3 .post_item .post_content_overlay .post_content .post_title:hover a {
	color: {$colors['text_link']};
}
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content .post_descr .sc_button{
	background-color: {$colors['alter_bg_color']} !important;
	color: {$colors['inverse_text']} !important;
}
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content .post_descr .sc_button:hover{
	background-color: {$colors['text_link']} !important;
	color: {$colors['inverse_text']} !important;
	/*border-color: {$colors['inverse_text']};*/
}

.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_info a,
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_info a > span,
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_title a,
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_descr p{
	/*color: {$colors['inverse_text']};*/
}

.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_info a:hover,
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_info a:hover > span,
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_title a:hover{
}

.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_descr .sc_button{
	/*color: {$colors['inverse_text']};	*/
	/*border-color: {$colors['inverse_text']};*/
}
.isotope_wrap .isotope_item_classic .post_item .post_content_overlay .post_content:hover .post_descr .sc_button:hover{
	/*color: {$colors['text_link']} !important;	*/
	/*border-color: {$colors['inverse_text']};*/
	/*background-color: {$colors['inverse_text']} !important;*/
}

.isotope_wrap .isotope_item_portfolio .post_item .post_info a{
	color: {$colors['inverse_text']};
}

/* Isotope filters */
.isotope_filters a {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.isotope_filters a.active,
.isotope_filters a:hover {
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}




/* 7.7 Paginations
-------------------------------------------------------------- */

/* Style 'Pages' and 'Slider' */
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.pagination_pages > a,
.pagination_pages > span {
	/*border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};*/
	color: {$colors['alter_dark']};
}
.pagination_single > .pager_numbers,
.pagination_single a:hover,
.pagination_slider .pager_cur:hover,
.pagination_slider .pager_cur:focus,
.pagination_pages > .active,
.pagination_pages > a:hover {
	/*background-color: {$colors['bg_color']};*/
	color: {$colors['text_link']};
}

.pagination_slider .pager_slider {
	border-color: {$colors['bd_color']};
	background-color: {$colors['bg_color']};
}

.pagination_wrap .pager_next,
.pagination_wrap .pager_prev,
.pagination_wrap .pager_last,
.pagination_wrap .pager_first {
	color: {$colors['alter_dark']};
}
.pagination_wrap .pager_next:hover,
.pagination_wrap .pager_prev:hover,
.pagination_wrap .pager_last:hover,
.pagination_wrap .pager_first:hover {
	color: {$colors['text_link']};
}



/* Style 'Load more' */
.pagination_viewmore > a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.pagination_viewmore > a:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}

/* Loader picture */
.viewmore_loader,
.mfp-preloader span,
.sc_video_frame.sc_video_active:before {
	background-color: {$colors['text_hover']};
}



/* 8 Single page parts
-------------------------------------------------------------- */


/* 8.1 Attachment and Portfolio post navigation
------------------------------------------------------------- */
.post_featured .post_nav_item {
	color: {$colors['inverse_text']};
}
.post_featured .post_nav_item:before {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.post_featured .post_nav_item .post_nav_info {
	background-color: {$colors['text_link']};
}


/* 8.2 Reviews block
-------------------------------------------------------------- */
.reviews_block .reviews_summary .reviews_item {
	background-color: {$colors['text_link']};
}
.reviews_block .reviews_summary,
.reviews_block .reviews_max_level_100 .reviews_stars_bg {
	background-color: {$colors['alter_bg_hover']};
}
.sidebar .reviews_block .reviews_summary,
.sidebar .reviews_block .reviews_max_level_100 .reviews_stars_bg {
	background-color: {$colors['bg_color']};
}
.reviews_block .reviews_max_level_100 .reviews_stars_hover,
.reviews_block .reviews_item .reviews_slider {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.reviews_block .reviews_item .reviews_stars_hover {
	color: {$colors['text_link']};
}
.reviews_block .reviews_value {
	color: {$colors['inverse_text']};
}
.reviews_block .reviews_summary .reviews_criteria {
	color: {$colors['text']};
}
.reviews_block .reviews_summary .reviews_value {
	color: {$colors['inverse_text']};
}

.reviews_block .reviews_editor .reviews_max_level_100 .reviews_criteria {
	color: {$colors['text_dark']};
}
.sidebar .reviews_block .reviews_editor .reviews_max_level_100 .reviews_criteria {
	color: {$colors['inverse_text']};
}
.widget_area .reviews_block .reviews_max_level_100 .reviews_value{

}

/* Summary stars in the post item (under the title) */
.post_item .post_rating .reviews_stars_hover {
	color: {$colors['inverse_text']};
}

.post_item .post_rating .reviews_value{
	color: {$colors['text_light_0_5']};
}

.post_item .post_rating .reviews_stars_bg{
	color: {$colors['text_light_0_2']};
}


/* 8.3 Post author
-------------------------------------------------------------- */
.post_author {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_text']};
}
.post_author .post_author_title {
	color: {$colors['text_link']};
}
.post_author .post_author_title a {
	color: {$colors['text_link']};
}
.post_author .post_author_title a:hover {
	color: {$colors['inverse_text']};
}
.post_author .post_author_info .sc_socials_shape_square a {
	color: {$colors['alter_dark']};
}
.post_author .post_author_info .sc_socials_shape_square a:hover {
	color: {$colors['text_hover']};
}

.post_line_over_author,
.post_line_under_author {
	border-color: {$colors['bd_color_0_5']};
}

/* 8.4 Comments
-------------------------------------------------------- */
.comments_list_wrap ul.children,
.comments_list_wrap ul > li + li {
	border-top-color: {$colors['text_light_0_5']};
}
.comments_list_wrap .comment-respond {
	border-bottom-color: {$colors['text_link']};
}
.comments_list_wrap > ul {
	border-bottom-color: {$colors['text_light_0_5']};
}
.comments_list_wrap .comment_info > span.comment_author,
.comments_list_wrap .comment_info > .comment_date > .comment_date_value {
	color: {$colors['alter_dark']};
}
.comments_list_wrap .comment_reply a{
	color: {$colors['alter_dark']};
}
.comments_list_wrap .comment_reply a:hover{
	color: {$colors['text_link']};
}
.comments_list_wrap .comment_text {
	color: {$colors['alter_dark']};
}

/* 8.5 Page 404
-------------------------------------------------------------- */
.post_item_404 .page_title,
.post_item_404 .page_subtitle {
	color: {$colors['text_link']};
}




/* 9. Sidebars
-------------------------------------------------------------- */

/* Side menu */
.sidebar_outer_menu .menu_side_nav li > a,
.sidebar_outer_menu .menu_side_responsive li > a {
	color: {$colors['text_dark']};
}
.sidebar_outer_menu .menu_side_nav li > a:hover,
.sidebar_outer_menu .menu_side_nav li.sfHover > a,
.sidebar_outer_menu .menu_side_responsive li > a:hover,
.sidebar_outer_menu .menu_side_responsive li.sfHover > a {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_hover']};
}
.sidebar_outer_menu .menu_side_nav > li ul,
.sidebar_outer_menu .menu_side_responsive > li ul {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
}
.sidebar_outer_menu .menu_side_nav li.current-menu-item > a,
.sidebar_outer_menu .menu_side_nav li.current-menu-parent > a,
.sidebar_outer_menu .menu_side_nav li.current-menu-ancestor > a,
.sidebar_outer_menu .menu_side_responsive li.current-menu-item > a,
.sidebar_outer_menu .menu_side_responsive li.current-menu-parent > a,
.sidebar_outer_menu .menu_side_responsive li.current-menu-ancestor > a {
	color: {$colors['text_light']};
}
.sidebar_outer_menu .sidebar_outer_menu_buttons > a {
	color: {$colors['text_dark']};
}
.sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover {
	color: {$colors['text_link']};
}
.sidebar.widget_area .widget{
	color: {$colors['inverse_text']};
}

/* Common rules */
.sidebar_inner aside:nth-child(3n+4),
.sidebar_inner aside:nth-child(3n+5),
.sidebar_inner aside:nth-child(3n+6),
.sidebar_outer_inner aside:nth-child(3n+4),
.sidebar_outer_inner aside:nth-child(3n+5),
.sidebar_outer_inner aside:nth-child(3n+6),
.widget_area_inner aside:nth-child(2n+3),
.widget_area_inner aside:nth-child(2n+4),
.widget_area_inner aside+aside {
	border-color: {$colors['bd_color']};
}
.widget_area_inner {
	color: {$colors['text']};
}
.sidebar_inner.widget_area_inner {
	background-color: {$colors['alter_bg_hover']};
}
.widget_area_inner a,
.widget_area_inner ul li:before,
.widget_area_inner ul li a:hover,
.widget_area_inner button:before {
	color: {$colors['text_link']};
}
.widget_area_inner a:hover,
.widget_area_inner ul li a,
.widget_area_inner button:hover:before {
	color: {$colors['inverse_text']};
}
.widget_area_inner .post_title a {
	color: {$colors['text_dark']};
}
.widget_area_inner .widget_text a:not(.sc_button),
.widget_area_inner .post_info a {
	color: {$colors['text_link']};
}
.widget_area_inner .widget_text a:not(.sc_button):hover,
.widget_area_inner .post_info a:hover {
	color: {$colors['text_hover']};
}
.widget_area .widget_recent_posts .post_info a,
.widget_area .widget_recent_posts .post_info a > span {
	color: {$colors['text_light_0_53']};
}
.widget_area .widget_recent_posts .post_info a:hover,
.widget_area .widget_recent_posts .post_info a:hover > span {
	color: {$colors['inverse_text']};
}

.widget_area .widget_title {
	color: {$colors['inverse_text']};	
}

.widget_area .widget_title:after {
	border-color: {$colors['accent_color']};	
}

.widget_area:not(.search_results) .post_item .post_title a{
	color: {$colors['inverse_text']};	
}
.widget_area .post_item .post_title a:hover{
	color: {$colors['text_link']};	
}


/* Widget: Search */
.widget_area_inner .widget_product_search .search_form,
.widget_area_inner .widget_search .search_form {
	background-color: {$colors['text']};
	border-color: {$colors['text']};
}
.widget_area_inner .widget_product_search .search_field,
.widget_area_inner .widget_search .search_field {
	color: {$colors['inverse_text']};
}
.widget_area_inner .widget_product_search input.search_field:hover,
.widget_area_inner .widget_search input.search_field:hover,
.widget_area_inner .widget_product_search input.search_field:focus,
.widget_area_inner .widget_search input.search_field:focus {
	/*background-color: {$colors['text']};*/
	background-color: {$colors['text_link']};
	border-color: {$colors['text']};
	color: {$colors['text']} !important;
}
.widget_area_inner .widget_product_search .search_button,
.widget_area_inner .widget_search .search_button {
	/*color: {$colors['alter_dark']};*/
	background-color: {$colors['text_link']};
}
.widget_area_inner .widget_product_search .search_button:hover,
.widget_area_inner .widget_search .search_button:hover {
	/*color: {$colors['alter_dark']};*/
	background-color: {$colors['inverse_text']} !important;
}

.widget_area_inner .widget_product_search .search_button:before,
.widget_area_inner .widget_search .search_button:before {
	color: {$colors['inverse_text']};
}
.widget_area_inner .widget_product_search .search_button:hover:before,
.widget_area_inner .widget_search .search_button:hover:before {
	color: {$colors['text_link']};
}



/* Widget: Calendar */
.widget_area_inner .widget_calendar .weekday {
	color: {$colors['inverse_text']};
}
.widget_area_inner .widget_calendar td a {
	background-color: {$colors['text']};
	color: {$colors['inverse_text']};
}

.widget_area_inner .widget_calendar td a:hover {
	background-color: {$colors['text_link']};
	color: {$colors['text_dark']};
}
.widget_area .widget_calendar td .day_wrap {
	color: {$colors['inverse_text']};
}
.widget_area_inner .widget_calendar .today .day_wrap {
	border-color: {$colors['accent_color']};
	background-color: {$colors['accent_color']};
	color: {$colors['alter_bg_hover']};
}

.widget_area_inner .widget_calendar [class*="month_"] a {
	color: {$colors['inverse_text']};
}
.widget_area_inner .widget_calendar [class*="month_"] a:hover {
	color: {$colors['text_link']};
}

/* Widget: Tag Cloud */
.widget_area_inner .widget_product_tag_cloud a,
.widget_area_inner .widget_tag_cloud a {
	background-color: {$colors['text']};
	color: {$colors['inverse_text']} !important;
}
.widget_area_inner .widget_product_tag_cloud a:hover,
.widget_area_inner .widget_tag_cloud a:hover {
	background-color: {$colors['text_link']} !important;
	color: {$colors['inverse_text']} !important;
}
.widget_area_inner .widget_product_tag_cloud a:not([class*="sc_button_hover_"]):hover,
.widget_area_inner .widget_tag_cloud a:not([class*="sc_button_hover_"]):hover {
	color: {$colors['text_link']};
}


/* Left or Right sidebar */
.sidebar_outer_inner aside,
.sidebar_inner aside {
	border-top-color: {$colors['bd_color']};
}

.sidebar aside:nth-child(2n+1) {
	background-color: {$colors['alter_bg_hover']};
}
.sidebar aside:nth-child(2n+0) {
	background-color: {$colors['alter_bd_hover']};
}

/* 10. Footer areas
-------------------------------------------------------------- */

.footer_wrap .footer_wrap_inner{
	background-color: {$colors['alter_bg_hover']};
}

.footer_wrap aside.widget {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_text']};
}
/* Contacts */
.contacts_wrap_inner {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}

/* Testimonials and Twitter */
.testimonials_wrap_inner,
.twitter_wrap_inner {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}

/* Copyright */
.copyright_wrap .copyright_wrap_inner{
	background-color: {$colors['alter_bg_hover']};
}
.copyright_wrap_inner {
    background-color: {$colors['bg_color']};
}
.copyright_wrap_inner .copyright_text {
    color: {$colors['inverse_text']};
}
.copyright_wrap_inner .copyright_text a{
	color: {$colors['accent_color']};
}
.copyright_wrap_inner .copyright_text a:hover{
    color: {$colors['inverse_text']};
}
.copyright_wrap_inner .menu_footer_nav li a {
    color: {$colors['inverse_text']};
}
.copyright_wrap_inner .menu_footer_nav li a:hover {
    color: {$colors['accent_color']};
}
.copyright_style_menu .menu_footer_nav li+li:before {
	color: {$colors['text_light_0_345']};
}



/* 11. Utils
-------------------------------------------------------------- */

/* Scroll to top */
.scroll_to_top {
	color: {$colors['inverse_text']};
	background-color: {$colors['accent_color']};
}
.scroll_to_top:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}

/* Preloader */
#page_preloader {
	background-color: {$colors['bg_color']};
}
.preloader_wrap > div {
	background-color: {$colors['text_link']};
}

/* Gallery preview */
.scheme_self.gallery_preview:before {
	background-color: {$colors['bg_color']};
}

/* 12. Registration and Login popups
-------------------------------------------------------------- */
.popup_wrap {
	background-color: {$colors['bg_color']};
}




/* 13. Third party plugins
------------------------------------------------------- */

/* 13.1 Magnific popup 
-------------------------------------------------------- */
.mfp-title {
    color: {$colors['inverse_text']} !important;
}

/* 13.2 WooCommerce
------------------------------------------------------ */

.woocommerce-ordering:after {
	color: {$colors['inverse_text']} !important;
}

.woocommerce-ordering:hover::after,
.woocommerce-ordering:focus::after {
	color: {$colors['alter_dark']} !important;
}

.woocommerce .woocommerce-checkout .select2-container .select2-choice {
	background-color: {$colors['alter_light']};
	color: {$colors['inverse_text']};
}

.mode_buttons a{
	color: {$colors['alter_dark']} !important;
}
.mode_buttons a:hover {
	color: {$colors['text_link']} !important;
}

.woocommerce.widget_shopping_cart .quantity, 
.woocommerce .widget_shopping_cart .quantity, 
.woocommerce-page.widget_shopping_cart .quantity, 
.woocommerce-page .widget_shopping_cart .quantity {
	color: {$colors['text_link']};
}

.woocommerce.widget_shopping_cart .quantity{
    color: #b3c200;
}

body:not(.woocommerce-cart) .woocommerce a.remove {
	color: {$colors['inverse_text']} !important;
}
:not(.woocommerce-cart) .woocommerce a.remove:hover {
	color: {$colors['text_link']} !important;
}

/* Theme colors */
/*
.woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,
.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #content input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,
.woocommerce .quantity input[type="button"]:hover, .woocommerce #content input[type="button"]:hover, .woocommerce-page .quantity input[type="button"]:hover, .woocommerce-page #content .quantity input[type="button"]:hover,
*/
.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,
.woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page ul.products li.product .price,
.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,
.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,
.woocommerce a:hover h3, .woocommerce-page a:hover h3, .woocommerce a:hover .woocommerce-loop-category__title, .woocommerce-page a:hover .woocommerce-loop-category__title,
.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,
.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,
.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before,
.widget_area_inner .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a
{
	color: {$colors['text_link']};
}

.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount,
.woocommerce.widget_shopping_cart .total .amount{
    color: {$colors['inverse_text']};
}

.woocommerce #reviews #comments ol.commentlist li .meta {
	color: {$colors['text_link']};
}
.woocommerce div.quantity span, .woocommerce-page div.quantity span {
	color: {$colors['inverse_text']};
	/*background-color: {$colors['text_link']};*/
}
.woocommerce div.quantity span:hover, .woocommerce-page div.quantity span:hover {
	/*background-color: {$colors['text_hover']};*/
	color: {$colors['text_link']};
}

.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content {
	background-color: {$colors['alter_light']};
}
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range
{ 
	background-color: #b3c200;
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle
{
	background: #b3c200;
}

.woocommerce .widget_price_filter .price_label {
	color: {$colors['inverse_text']};
}
.woocommerce .widget_price_filter .price_label span {
	color: #b3c200;
}

.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,
.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,
.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active
{ 
	border-top-color: {$colors['text_link']};
}

.woocommerce .product_meta span {
	color: {$colors['text_light']};
}

.woocommerce .product_meta span span {
	color: {$colors['text_link']};
}

.woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active{
	background-color: {$colors['alter_dark']} !important;
}

.woocommerce div.product .woocommerce-tabs h3, .woocommerce #content div.product .woocommerce-tabs h3, .woocommerce-page div.product .woocommerce-tabs h3, .woocommerce-page #content div.product .woocommerce-tabs h3{
	color: {$colors['inverse_text']};
}

.single-product .content div.product .woocommerce-tabs .panel {
	background-color: {$colors['alter_dark']} !important;
	color: {$colors['inverse_text']} !important;
}
.single-product .content div.product .woocommerce-tabs .panel h2{
	color: {$colors['inverse_text']} !important;
}

.woocommerce .related.products h2:after {
	border-color: {$colors['text_link']} !important;
}

/* Buttons */
.woocommerce a.button, .woocommerce button.button, .woocommerce input.button,
.woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, 
.woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, 
.woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, 
.woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, 
.woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce-account .addresses .title .edit,
.woocommerce ul.products li.product .add_to_cart_button, .woocommerce-page ul.products li.product .add_to_cart_button {
	background-color: {$colors['text_link']};
	color: {$colors['alter_dark']};
}

.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,
.woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, 
.woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover,
.woocommerce-page #content input.button:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,
.woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover,
.woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce-account .addresses .title .edit:hover,
.woocommerce ul.products li.product .add_to_cart_button:hover, .woocommerce-page ul.products li.product .add_to_cart_button:hover {
	background-color: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}

.woocommerce ul.products li.product h3 a:hover, .woocommerce-page ul.products li.product h3 a:hover{
	color: {$colors['text_link']};
}

.woocommerce .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce-page #content .quantity input.qty{
	color: {$colors['inverse_text']};
}

/* Messages */
.article_style_boxed.woocommerce .woocommerce-error, .article_style_boxed.woocommerce .woocommerce-info, .article_style_boxed.woocommerce .woocommerce-message,
.article_style_boxed.woocommerce-page .woocommerce-error, .article_style_boxed.woocommerce-page .woocommerce-info, .article_style_boxed.woocommerce-page .woocommerce-message {
	background-color: {$colors['alter_bg_color']};
}
.article_style_boxed.woocommerce.archive .woocommerce-error, .article_style_boxed.woocommerce.archive .woocommerce-info, .article_style_boxed.woocommerce.archive .woocommerce-message,
.article_style_boxed.woocommerce-page.archive .woocommerce-error, .article_style_boxed.woocommerce-page.archive .woocommerce-info, .article_style_boxed.woocommerce-page.archive .woocommerce-message {
	background-color: {$colors['alter_bg_color']};
}

/* Products stream */
.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale {
	background-color: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}
.article_style_boxed.woocommerce ul.products li.product .post_item_wrap, .article_style_boxed.woocommerce-page ul.products li.product .post_item_wrap {
	background-color: {$colors['alter_bg_color']};
}

.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price {
	color: {$colors['text_link']};
}
.woocommerce .products .post_item_wrap .post_featured .post_thumb a.button:hover{
	color: {$colors['alter_text']} !important;
}
.woocommerce ul.products li.product .star-rating span{
	color: {$colors['text']};
}
.woocommerce .products .star-rating:before{
	color: {$colors['text_light_0_77']};
}
.woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del {
	color: {$colors['text_light']};
}

.scheme_self.article_style_boxed.woocommerce ul.products li.product .post_item_wrap {
	background-color: {$colors['alter_bg_hover']};
}
.scheme_self.article_style_boxed.woocommerce-page ul.products li.product .post_item_wrap {
	background-color: {$colors['alter_bg_hover']};
}
.scheme_self.article_style_boxed.woocommerce ul.products li.product .post_content {
	background-color: {$colors['alter_bg_color']};
}
.scheme_self.article_style_boxed.woocommerce-page ul.products li.product .post_content {
	background-color: {$colors['alter_bg_color']};
}

/* Pagination */
.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span.current {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}

/* Tabs */
.woocommerce div.product .woocommerce-tabs .panel, .woocommerce #content div.product .woocommerce-tabs .panel, .woocommerce-page div.product .woocommerce-tabs .panel, .woocommerce-page #content div.product .woocommerce-tabs .panel {
	border-color: {$colors['bd_color']};
}

/* Tabs on single product page */
.scheme_self.woocommerce-tabs.trx-stretch-width {
	background-color: {$colors['bg_color']};
}
.single-product .content div.product .woocommerce-tabs .wc-tabs li a {
	color: {$colors['text']};
}
.single-product .content div.product .woocommerce-tabs .wc-tabs li a:hover,
.single-product .content div.product .woocommerce-tabs .wc-tabs li.active a {
	color: {$colors['inverse_text']};
	background-color: {$colors['alter_dark']};
}
.single-product .content div.product .woocommerce-tabs .wc-tabs li.active a:after {
	background-color: {$colors['text_link']};
}

/* Cart */
.woocommerce table.cart thead th, 
.woocommerce #content table.cart thead th, 
.woocommerce-page table.cart thead th, 
.woocommerce-page #content table.cart thead th {
	color: {$colors['text_dark']};
}

.woocommerce table.cart thead th:nth-child(2n+1), 
.woocommerce #content table.cart thead th:nth-child(2n+1), 
.woocommerce-page table.cart thead th:nth-child(2n+1), 
.woocommerce-page #content table.cart thead th:nth-child(2n+1) {
	background-color: {$colors['text_link']};
}

.woocommerce table.cart thead th:nth-child(2n+0), 
.woocommerce #content table.cart thead th:nth-child(2n+0), 
.woocommerce-page table.cart thead th:nth-child(2n+0), 
.woocommerce-page #content table.cart thead th:nth-child(2n+0) {
	background-color: {$colors['text_link']};
}


table.shop_table tr td:nth-child(2n+1){
	background-color: {$colors['alter_bg_hover']};
	color: {$colors['inverse_text']};
}
table.shop_table tr td:nth-child(2n+0){
	background-color: {$colors['alter_bd_hover']};
	color: {$colors['inverse_text']};
}

.cart_totals  table.shop_table tr td{
	background-color: transparent !important;
}

.woocommerce table.shop_table td.product-remove a, 
.woocommerce-page table.shop_table td.product-remove a{
	color: {$colors['inverse_text']} !important;
}
.woocommerce table.shop_table td.product-name a, 
.woocommerce-page table.shop_table td.product-name a{
	color: {$colors['inverse_text']} !important;
}
.woocommerce table.shop_table td.product-name a:hover, 
.woocommerce-page table.shop_table td.product-name a:hover{
	color: {$colors['text_link']} !important;
}
/* My Account */
.woocommerce-account .woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-navigation li+li {
	border-color: {$colors['bd_color']};
}
.woocommerce-MyAccount-navigation li.is-active a {
	color: {$colors['text_dark']};
}

table.shop_table.woocommerce-checkout-review-order-table tr td{
	background-color: transparent !important;
	color: {$colors['text_dark']};
}

/* Widgets */
.top_panel_inner_style_4 .widget_shopping_cart .empty,
.top_panel_inner_style_4 .widget_shopping_cart .quantity,
.top_panel_inner_style_4 .widget_shopping_cart .quantity .amount,
.top_panel_inner_style_4 .widget_shopping_cart .total,
.top_panel_inner_style_4 .widget_shopping_cart .total .amount {
	color: {$colors['inverse_text']};
}

.top_panel_wrap .widget_shopping_cart ul.cart_list > li > a {
	color: {$colors['inverse_text']}; 
}
.top_panel_wrap .widget_shopping_cart ul.cart_list > li > a:hover {
	color: #b3c200;
}
.top_panel_wrap .widget_shopping_cart ul.cart_list > li.empty{
	color: {$colors['inverse_text']}; 
}

/* Widget Product Categories */
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li+li {
	border-color: {$colors['alter_bd_color']};
}
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li,
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li > a {
	color: {$colors['inverse_text']};
}
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li:hover,
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li:hover > a,
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li > a:hover {
	color: {$colors['text_link']};
}
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories ul {
	background-color: {$colors['alter_bg_color']};
}


/* 13.3 Tribe Events
------------------------------------------------------- */
.tribe-events-calendar thead th {
	background-color: {$colors['text_link']};
}

/* Buttons */
a.tribe-events-read-more,
.tribe-events-button,
.tribe-events-nav-previous a,
.tribe-events-nav-next a,
.tribe-events-widget-link a,
.tribe-events-viewmore a {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
a.tribe-events-read-more:hover,
.tribe-events-button:hover,
.tribe-events-nav-previous a:hover,
.tribe-events-nav-next a:hover,
.tribe-events-widget-link a:hover,
.tribe-events-viewmore a:hover {
	background-color: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}




/* 13.4 BB Press and Buddy Press
------------------------------------------------------- */

/* Buttons */
#bbpress-forums div.bbp-topic-content a,
#buddypress button, #buddypress a.button, #buddypress input[type="submit"], #buddypress input[type="button"], #buddypress input[type="reset"], #buddypress ul.button-nav li a, #buddypress div.generic-button a, #buddypress .comment-reply-link, a.bp-title-button, #buddypress div.item-list-tabs ul li.selected a,
#buddypress .acomment-options a {
	background: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
#bbpress-forums div.bbp-topic-content a:hover,
#buddypress button:hover, #buddypress a.button:hover, #buddypress input[type="submit"]:hover, #buddypress input[type="button"]:hover, #buddypress input[type="reset"]:hover, #buddypress ul.button-nav li a:hover, #buddypress div.generic-button a:hover, #buddypress .comment-reply-link:hover, a.bp-title-button:hover, #buddypress div.item-list-tabs ul li.selected a:hover,
#buddypress .acomment-options a:hover {
	background: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}
#buddypress #item-nav,
#buddypress div#subnav.item-list-tabs,
#buddypress div.item-list-tabs {
	background-color: {$colors['alter_bg_color']};
}
#buddypress #item-nav li:not(.selected) a,
#buddypress div#subnav.item-list-tabs li:not(.selected) a,
#buddypress div.item-list-tabs li:not(.selected) a {
	color: {$colors['alter_dark']};
}
#buddypress #item-nav li:not(.selected) a:hover,
#buddypress div#subnav.item-list-tabs li:not(.selected) a:hover,
#buddypress div.item-list-tabs li:not(.selected) a:hover {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_hover']};
}
#buddypress .dir-search input[type="search"], #buddypress .dir-search input[type="text"], #buddypress .groups-members-search input[type="search"], #buddypress .groups-members-search input[type="text"], #buddypress .standard-form input[type="color"], #buddypress .standard-form input[type="date"], #buddypress .standard-form input[type="datetime-local"], #buddypress .standard-form input[type="datetime"], #buddypress .standard-form input[type="email"], #buddypress .standard-form input[type="month"], #buddypress .standard-form input[type="number"], #buddypress .standard-form input[type="password"], #buddypress .standard-form input[type="range"], #buddypress .standard-form input[type="search"], #buddypress .standard-form input[type="tel"], #buddypress .standard-form input[type="text"], #buddypress .standard-form input[type="time"], #buddypress .standard-form input[type="url"], #buddypress .standard-form input[type="week"], #buddypress .standard-form select, #buddypress .standard-form textarea,
#buddypress form#whats-new-form textarea {
	color: {$colors['input_text']};
	background-color: {$colors['input_bg_color']};
	border-color: {$colors['input_bd_color']};
}
#buddypress .dir-search input[type="search"]:focus, #buddypress .dir-search input[type="text"]:focus, #buddypress .groups-members-search input[type="search"]:focus, #buddypress .groups-members-search input[type="text"]:focus, #buddypress .standard-form input[type="color"]:focus, #buddypress .standard-form input[type="date"]:focus, #buddypress .standard-form input[type="datetime-local"]:focus, #buddypress .standard-form input[type="datetime"]:focus, #buddypress .standard-form input[type="email"]:focus, #buddypress .standard-form input[type="month"]:focus, #buddypress .standard-form input[type="number"]:focus, #buddypress .standard-form input[type="password"]:focus, #buddypress .standard-form input[type="range"]:focus, #buddypress .standard-form input[type="search"]:focus, #buddypress .standard-form input[type="tel"]:focus, #buddypress .standard-form input[type="text"]:focus, #buddypress .standard-form input[type="time"]:focus, #buddypress .standard-form input[type="url"]:focus, #buddypress .standard-form input[type="week"]:focus, #buddypress .standard-form select:focus, #buddypress .standard-form textarea:focus,
#buddypress form#whats-new-form textarea:focus {
	color: {$colors['text_dark']};
	background-color: {$colors['input_bg_hover']};
	border-color: {$colors['input_bd_hover']};
}

#buddypress #reply-title small a span, #buddypress a.bp-primary-action span {
	color: {$colors['text_link']};
	background-color: {$colors['inverse_text']};
}

#buddypress .activity .activity-item:nth-child(2n+1) {
	background-color: {$colors['alter_bg_color']};
}


/* 13.5 WPBakery PageBuilder
------------------------------------------------------ */
.scheme_self.vc_row {
	background-color: {$colors['bg_color']};
}


/* 13.6 Booking Calendar
------------------------------------------------------ */
.booking_month_container_custom,
.booking_month_navigation_button_custom {
	background-color: {$colors['alter_bg_color']} !important;
}
.booking_month_name_custom,
.booking_month_navigation_button_custom {
	color: {$colors['alter_dark']} !important;
}
.booking_month_navigation_button_custom:hover {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_hover']} !important;
}


/* 13.6 LearnDash LMS
------------------------------------------------------ */
#learndash_next_prev_link > a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
#learndash_next_prev_link > a:hover {
	background-color: {$colors['text_hover']};
}
.widget_area dd.course_progress div.course_progress_blue {
	background-color: {$colors['text_hover']};
}


/* 13.7 HTML5 Audio Player
------------------------------------------------------- */
#myplayer .ttw-music-player .progress-wrapper {
	background-color: {$colors['alter_bg_hover']};
}
#myplayer .ttw-music-player .tracklist li.track {
	border-color: {$colors['bd_color']};
}
#myplayer .ttw-music-player .tracklist,
#myplayer .ttw-music-player .buy,
#myplayer .ttw-music-player .description,
#myplayer .ttw-music-player .artist,
#myplayer .ttw-music-player .artist-outer {
	color: {$colors['text']};
}
#myplayer .ttw-music-player .player .title,
#myplayer .ttw-music-player .tracklist li:hover {
	color: {$colors['text_dark']};
}


/* 13.8 MailChimp
------------------------------------------------------- */

.mc4wp-response .mc4wp-error {
	color: {$colors['inverse_text']};
}
.mc4wp-response .mc4wp-success {
	color: {$colors['text_link']};
}


/* 13.9 Essential Grid
------------------------------------------------------- */
.custom-1 .eg-custom-skin-1-element-10 {
	color: {$colors['inverse_dark']};
}
.custom-1 .eg-custom-skin-1-element-10:hover {
	color: {$colors['text_link']};
}

.custom-1 .eg-custom-skin-1-element-12 {
	color: {$colors['accent_color']};
}
.custom-1 .eg-custom-skin-1-element-12:hover {
	color: {$colors['inverse_dark']};
}


/* 15. Shortcodes
-------------------------------------------------------------- */

/* Accordion */
.sc_accordion .sc_accordion_item .sc_accordion_title {
	border-color: {$colors['bd_color']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title .sc_accordion_icon {
	color: {$colors['alter_light']};
	background-color: {$colors['alter_bg_color']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title.ui-state-active {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon_opened {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title:hover {
	color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title:hover .sc_accordion_icon_opened {
	background-color: {$colors['text_hover']};
}
.sc_accordion .sc_accordion_item .sc_accordion_content {
	border-color: {$colors['bd_color']};
}


/* Audio */

/* Standard style */
/*
.sc_audio .sc_audio_author_name,
.sc_audio .sc_audio_title {
	color: {$colors['text_link']};
}
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .mejs-controls .mejs-time-rail .mejs-time-current {
	background: {$colors['text_link']} !important;
}
*/

/* Modern style */
.sc_audio.sc_audio_info {
	border-color: {$colors['alter_bg_color']};
	background-color: {$colors['alter_bg_color']};
}
.sc_audio .sc_audio_author_by {
	color: {$colors['text_dark']};
}
.sc_audio .sc_audio_author_name {
	color: {$colors['text_light']};
}

.sc_audio_player{
	background-color: {$colors['alter_bg_color']};	
}
.sc_audio_player .sc_audio_title{
	color: {$colors['text_link']};
}
.sc_audio_player .sc_audio_author{
	color: {$colors['inverse_text']};
}


.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
.mejs-controls .mejs-volume-button .mejs-volume-slider,
.mejs-controls .mejs-time-rail .mejs-time-current {
	background: {$colors['text_link']} !important;
}
.mejs-container, .mejs-embed, .mejs-embed body, .mejs-container .mejs-controls {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
}
.mejs-container .mejs-controls .mejs-time {
	color: {$colors['text_light']};
}
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total:before,
.mejs-controls .mejs-time-rail .mejs-time-total:before {
	background-color: {$colors['bd_color']};
}
.mejs-controls .mejs-time-rail .mejs-time-loaded {
	background: {$colors['alter_dark_0_1']} !important;
}
.mejs-container .mejs-controls .mejs-fullscreen-button,
.mejs-container .mejs-controls .mejs-volume-button,
.mejs-container .mejs-controls .mejs-playpause-button {
	background: {$colors['text_link']} !important;
}



/* Button */
input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button.sc_button_style_filled {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_dark']} !important;
}

.sc_call_to_action_accented .sc_call_to_action_buttons .sc_item_button>a {
	color: {$colors['text_link']} !important;
}

.sc_call_to_action_button .sc_button.sc_button_style_filled:hover {
	color: {$colors['inverse_dark']} !important;
}

input[type="submit"]:not([class*="sc_button_hover_"]):hover,
input[type="reset"]:not([class*="sc_button_hover_"]):hover,
input[type="button"]:not([class*="sc_button_hover_"]):hover,
button:not([class*="sc_button_hover_"]):hover,
.sc_button.sc_button_style_filled:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_text']};
}
.sc_button.sc_button_style_border {
	border-color: {$colors['text_link']};
	color: {$colors['text_link']};
}
.sc_button.sc_button_style_border:hover {
	border-color: {$colors['alter_bg_color']} !important;
}

.sc_button.sc_button_style_simple,
.sc_button.sc_button_style_simple_alt_hover{
	color: {$colors['text_dark']} !important;
	background-color: transparent !important;
}
.sc_button.sc_button_style_simple:hover {
	color: {$colors['text_link']} !important;
	background-color: transparent !important;
}

.sc_button.sc_button_style_simple_alt_hover:hover {
	color: {$colors['inverse_text']} !important;
	background-color: transparent !important;
}
.sc_button.sc_button_style_simple_white{
	color: {$colors['inverse_text']} !important;
	background-color: transparent !important;
}
.sc_button.sc_button_style_simple_white:hover {
	color: {$colors['text_link']} !important;
	background-color: transparent !important;
}
.sc_button.sc_button_style_simple_alter{
	color: {$colors['input_dark']} !important;
	background-color: transparent !important;
}
.sc_button.sc_button_style_simple_alter:hover {
	color: {$colors['text_link']} !important;
	background-color: transparent !important;
}



/* ================= BUTTON'S HOVERS ==================== */

/* Fade */
[class*="sc_button_hover_fade"] {
	color: {$colors['inverse_text']} !important;
}
[class*="sc_button_hover_fade"]:hover {
	background-color: {$colors['text_hover']} !important;
	color: {$colors['inverse_text']} !important;
}

.product [class*="sc_button_hover_fade"] {
	color: {$colors['text']} !important;
}

/* Slide */
/* This way via :after element
[class*="sc_button_hover_slide"]:after {
	background-color: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}
*/
/* This way via gradient */
[class*="sc_button_hover_slide"] {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_link']};
}
[class*="sc_button_hover_slide"]:hover {
	background-color: {$colors['text_hover']} !important;
}
.sc_button_hover_slide_left {
    background: linear-gradient(to right, {$colors['text_hover']} 50%, {$colors['text_link']} 50%) repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0) !important;
}
.sc_button_hover_slide_top {
    background: linear-gradient(to bottom, {$colors['text_hover']} 50%, {$colors['text_link']} 50%) repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0) !important;
}

/* ================= END BUTTON'S HOVERS ==================== */



/* Blogger */
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date { 
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date .year:before {
	border-color: {$colors['inverse_text']};
}
.sc_blogger.layout_date .sc_blogger_item::before {
	background-color: {$colors['alter_bg_color']};
}
.sc_blogger_item.sc_plain_item {
	background-color: {$colors['alter_bg_color']};
}
.sc_blogger.layout_polaroid .photostack nav span.current {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_blogger.layout_polaroid .photostack nav span.current.flip {
	background-color: {$colors['text_hover']};
}

/* Call to Action */
.sc_call_to_action .sc_call_to_action_descr {
	color: {$colors['text_dark']};
}
.sc_call_to_action_accented {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_call_to_action_accented .sc_item_title,
.sc_call_to_action_accented .sc_item_subtitle,
.sc_call_to_action_accented .sc_item_descr {
	color: {$colors['inverse_text']};
}
.sc_call_to_action_accented .sc_item_button > a {
	color: {$colors['text_link']};
	background-color: {$colors['inverse_text']};
}
.sc_call_to_action_accented .sc_item_button > a:before {
	/*background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};*/
}

.sc_call_to_action.sc_call_to_action_style_1:not(.sc_call_to_action_accented) {
	background-color: {$colors['alter_bg_color']};
}

.sc_call_to_action.sc_call_to_action_style_1:not(.sc_call_to_action_accented) .sc_call_to_action_subtitle{
	color: {$colors['inverse_text']};
}

.sc_call_to_action.sc_call_to_action_style_1:not(.sc_call_to_action_accented) .sc_call_to_action_title{
	color: {$colors['text_link']};
}

.sc_call_to_action.sc_call_to_action_style_1:not(.sc_call_to_action_accented) .sc_call_to_action_descr{
	color: {$colors['inverse_text']};
}

.sc_call_to_action.sc_call_to_action_style_1 .sc_call_to_action_line {
    background-color: {$colors['alter_dark']};
}
.sc_call_to_action.sc_call_to_action_accented .sc_call_to_action_line {
    background-color: {$colors['inverse_text']};
}


/* Chat */
.sc_chat:after {
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_color']};
}
.sc_chat_inner {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_color']};
}
.sc_chat_inner a {
	color: {$colors['text_link']};
}
.sc_chat_inner a:hover {
	color: {$colors['alter_hover']};
}

/* Clients */
.sc_clients_style_clients-2 .sc_client_image .sc_client_hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark_0_8']};
}
.sc_clients_style_clients-2 .sc_client_title,
.sc_clients_style_clients-2 .sc_client_title a {
	color: {$colors['inverse_text']};
}
.sc_clients_style_clients-2 .sc_client_title a:hover {
	color: {$colors['text_link']};
}
.sc_clients_style_clients-2 .sc_client_description:before,
.sc_clients_style_clients-2 .sc_client_position {
	color: {$colors['text_link']};
}

/* Contact form */
.sc_form .sc_form_item.sc_form_button button { 
	//color: {$colors['text_link']};
	//border-color: {$colors['text_link']};
}
.sc_form .sc_form_button button:hover { 
	//color: {$colors['inverse_text']};
	//background-color: {$colors['text_link']};
}
.sc_form .sc_form_address_label,
.sc_form .sc_form_item > label {
	color: {$colors['text_dark']};
}
.sc_form .sc_form_item .sc_form_element input[type="radio"] + label:before,
.sc_form .sc_form_item .sc_form_element input[type="checkbox"] + label:before {
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
.sc_form_select_container {
	background-color: {$colors['input_bg_color']};
}

/* picker */
.sc_form .picker {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
.picker__month,
.picker__year {
	color: {$colors['text_dark']};
}
.sc_form .picker__nav--prev:before,
.sc_form .picker__nav--next:before {
	color: {$colors['input_text']};
}
.sc_form .picker__nav--prev:hover:before,
.sc_form .picker__nav--next:hover:before {
	color: {$colors['text_dark']};
}
.sc_form .picker__nav--disabled,
.sc_form .picker__nav--disabled:hover,
.sc_form .picker__nav--disabled:before,
.sc_form .picker__nav--disabled:before:hover {
	color: {$colors['input_light']};
}
.sc_form table.picker__table th {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_form .picker__day--infocus {
	color: {$colors['text_dark']};
}
.sc_form .picker__day--today,
.sc_form .picker__day--infocus:hover,
.sc_form .picker__day--outfocus:hover,
.sc_form .picker__day--highlighted:hover,
.sc_form .picker--focused .picker__day--highlighted {
	color: {$colors['text_dark']};
	background-color: {$colors['input_bg_hover']};
}
.sc_form .picker__day--disabled,
.sc_form .picker__day--disabled:hover {
	color: {$colors['input_light']};
}
.sc_form .picker__day--highlighted.picker__day--disabled,
.sc_form .picker__day--highlighted.picker__day--disabled:hover {
	color: {$colors['input_light']};
	background-color: {$colors['input_bg_hover']} !important;
}
.sc_form .picker__day--today:before,
.sc_form .picker__button--today:before,
.sc_form .picker__button--clear:before,
.sc_form button:focus {
	border-color: {$colors['text_link']};
}
.sc_form .picker__button--close:before {
	color: {$colors['text_link']};
}
.sc_form .picker--time .picker__button--clear:hover,
.sc_form .picker--time .picker__button--clear:focus {
	background-color: {$colors['text_hover']};
}
.sc_form .picker__footer {
	border-color: {$colors['input_bd_color']};
}
.sc_form .picker__button--today,
.sc_form .picker__button--clear,
.sc_form .picker__button--close {
	color: {$colors['input_text']};
}
.sc_form .picker__button--today:hover,
.sc_form .picker__button--clear:hover,
.sc_form .picker__button--close:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['input_bg_hover']} !important;
}
.sc_form .picker__button--today[disabled],
.sc_form .picker__button--today[disabled]:hover {
	color: {$colors['input_light']};
	background-color: {$colors['input_bg_hover']};
	border-color: {$colors['input_bg_hover']};
}
.sc_form .picker__button--today[disabled]:before {
	border-top-color: {$colors['input_light']};
}

/* Time picker */
.sc_form .picker__list-item {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_color']};
}
.sc_form .picker__list-item:hover,
.sc_form .picker__list-item--highlighted,
.sc_form .picker__list-item--highlighted:hover,
.sc_form .picker--focused .picker__list-item--highlighted,
.sc_form .picker__list-item--selected,
.sc_form .picker__list-item--selected:hover,
.sc_form .picker--focused .picker__list-item--selected {
	color: {$colors['text_dark']};
	background-color: {$colors['input_bg_hover']};
	border-color: {$colors['input_bd_hover']};
}
.sc_form .picker__list-item--disabled,
.sc_form .picker__list-item--disabled:hover,
.sc_form .picker--focused .picker__list-item--disabled {
	color: {$colors['input_light']};
	background-color: {$colors['input_bg_color']};
	border-color: {$colors['input_bd_color']};
}


/* Countdown Style 1 */
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits,
.sc_countdown.sc_countdown_style_1 .sc_countdown_separator {
	color: {$colors['text_link']};
}
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.sc_countdown.sc_countdown_style_1 .sc_countdown_label {
	color: {$colors['text_link']};
}

/* Countdown Style 2 */
.sc_countdown.sc_countdown_style_2 .sc_countdown_separator {
	color: {$colors['text_link']};
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_digits span {
	background-color: {$colors['text_link']};
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_label {
	color: {$colors['text_link']};
}

/* Dropcaps */
.sc_dropcaps .sc_dropcaps_item {
	color: {$colors['inverse_text']};
}
.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item {
	color: {$colors['text_link']};
}
.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item {
	color: {$colors['text_dark']};
} 


/* Events */
.sc_events_item .sc_events_item_readmore {
	color: {$colors['text_dark']};
}
.sc_events_item .sc_events_item_readmore span {
	color: {$colors['text_link']};
}
.sc_events_item .sc_events_item_readmore:hover,
.sc_events_item .sc_events_item_readmore:hover span {
	color: {$colors['text_hover']};
}
.sc_events_style_events-1 .sc_events_item {
	background-color: {$colors['bg_color']};
	color: {$colors['text']};
}
.sc_events_style_events-2 .sc_events_item {
	border-color: {$colors['bd_color']};
}
.sc_events_style_events-2 .sc_events_item_date {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_events_style_events-2 .sc_events_item_time:before,
.sc_events_style_events-2 .sc_events_item_details:before {
	background-color: {$colors['bd_color']};
}

/* Google map */
.sc_googlemap {
	background-color: {$colors['text_dark']} !important;
}
.sc_googlemap_content {
	background-color: {$colors['bg_color']};
}

/* Highlight */
.sc_highlight_style_1 {
	background-color: {$colors['text_link']};
	/*color: {$colors['inverse_text']};*/
}
.sc_highlight_style_2 {
	/*background-color: {$colors['text_hover']};*/
	color: {$colors['text_link']};
}
.sc_highlight_style_3 {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['alter_dark']};
}

/* Icon */
.sc_icon_hover:hover,
a:hover .sc_icon_hover {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_link']} !important; 
}

.sc_icon_shape_round.sc_icon,
.sc_icon_shape_square.sc_icon {	
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.sc_icon_shape_round.sc_icon:hover,
.sc_icon_shape_square.sc_icon:hover,
a:hover .sc_icon_shape_round.sc_icon,
a:hover .sc_icon_shape_square.sc_icon {	
	color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}


/* Image */
figure figcaption,
.sc_image figcaption {
	background-color: {$colors['alter_bg_color']};
}

.sc_image a:before {
	background-color: {$colors['text_link']};	
}

.sc_image a:after {
    background-color: {$colors['inverse_text']};
    color:{$colors['text_dark']};
}

/* Infobox */
.sc_infobox.sc_infobox_style_regular {
	background-color: {$colors['text_link']};
}

/* Intro */
.sc_intro_inner .sc_intro_subtitle {
	color: {$colors['inverse_link']};
} 
.sc_intro_inner .sc_intro_title {
	color: {$colors['inverse_dark']};
}
.sc_intro_inner .sc_intro_descr,
.sc_intro_inner .sc_intro_icon {
	color: {$colors['inverse_dark']};
}

/* List */
.sc_list_style_iconed li:before,
.sc_list_style_iconed .sc_list_icon {
	color: {$colors['text_link']};
}
.sc_list_style_iconed li .sc_list_title {
	color: {$colors['text_dark']};
}
.sc_list_style_iconed li a:hover .sc_list_title {
	color: {$colors['text_hover']};
}


/* Line */
.sc_line {
	border-color: {$colors['bd_color']};
}
.sc_line .sc_line_title {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color']};
}



/* Matches */
.match_block .player_country {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.match_block .player_name a {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_color']};
}
.match_block .player_name a:hover{
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.match_block .match_score {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_color']};
}
.match_block .match_category a {
	color: {$colors['text']};
}
.match_block .match_category a:hover,
.match_block .match_date {
	color: {$colors['text_link']};
}
.post_item_colored .match_date {
	color: {$colors['text_link']};
}
.matches_hover > a:after {
	background-color: {$colors['text_link_0_3']};
}
.sc_matches.style_matches-1 .sc_matches_next {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_matches_next h2,
.sc_matches_next .sc_item_subtitle {
	color: {$colors['inverse_text']};
}
.sc_matches_next .sc_item_title:after {
	background-color: {$colors['inverse_text']};
}
.sc_match_date {
	background-color: {$colors['inverse_text']};
	color: {$colors['text_hover']};
}
.sc_matches.style_matches-2 .sc_match_info {
	background-color: {$colors['alter_bg_hover_0_5']};
}
.sc_matches.style_matches-2 .sc_matches_next .sc_item_title {
	background-color: {$colors['text_link']};
}
.sc_matches.style_matches-2 .sc_matches_next .sc_match_date  {
	background-color: {$colors['alter_bg_hover']};
}

/* Players */
.post_item_single_players .post_title:after {
	background-color: {$colors['text_link']};
}
.post_item_single_players .player_info span {
	color: {$colors['text_dark']};
}
.sc_player .sc_player_info .sc_player_title a {
	color: {$colors['text_dark']};
}
.sc_player .sc_player_info .sc_player_club,
.sc_player .sc_player_info .sc_player_title a:hover {
	color: {$colors['text_link']};
}
.sc_player .sc_player_info {
	border-color: {$colors['text_link']};
}
.sc_player .sc_player_avatar .sc_player_hover {
	background-color: {$colors['text_link_0_8']};
}
.sc_player .sc_socials.sc_socials_type_icons a,
.sc_player .sc_socials.sc_socials_type_icons a:hover {
	color: {$colors['inverse_link']};
	border-color: {$colors['inverse_link']};
}
.post_item_colored .player_info {
	color: {$colors['text_link']};
}
.sc_players_table table tr .country {
	color: {$colors['text_light']};
}
.sc_players_table.style_2 table tr:nth-child(n+2) {
	background-color: {$colors['alter_bg_hover_0_5']};
}
.sc_players_table.style_2 .sc_table td {
	border-color: {$colors['bg_color']};
}

/* Menu items */
.sc_menuitems_style_menuitems-1 .sc_menuitem_price {
	color: {$colors['text_dark']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_spicy {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_box_title {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_content,
.sc_menuitems_style_menuitems-2 .sc_menuitem_ingredients,
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions {
	color: {$colors['text']};
	border-color: {$colors['bd_color']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_content_title,
.sc_menuitems_style_menuitems-2 .sc_menuitem_ingredients_title, 
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_title {
	color: {$colors['text_dark']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_content_title span,
.sc_menuitems_style_menuitems-2 .sc_menuitem_ingredients_title span,
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_title span {
	color: {$colors['text_link']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_list li {
	color: {$colors['text_dark']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_list li:before,
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_list li span {
	color: {$colors['text_link']};
}
.popup_menuitem > .sc_menuitems_wrap {
	background-color: {$colors['bg_color']};
}


/* Popup */
.sc_popup:before {
	background-color: {$colors['text_link']};
}


/* Price */
.sc_price .sc_price_currency, .sc_price .sc_price_money, .sc_price .sc_price_penny {
	color: {$colors['text_dark']};
}
.sc_price .sc_price_info {
	color: {$colors['text_light']};
}

/* Price block */
.sc_price_block,
.sc_price_block .sc_price_block_money * {
	color: {$colors['inverse_text']};
}

.sc_price_block .sc_price_block_description{
	color: {$colors['text_light']};
}

.sc_price_block.sc_price_block_style_1 {
	background-color: {$colors['alter_bg_color']};
}
.sc_price_block.sc_price_block_style_1 .sc_price_block_money .sc_price > *{
	color: {$colors['text_link']};
}
.sc_price_block.sc_price_block_style_3,
.sc_price_block.sc_price_block_style_3 .sc_price_block_money * {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_hover']};
}
.sc_price_block.sc_price_block_style_3 .sc_price_block_title {
	color: {$colors['alter_dark']};
}
.sc_price_block .sc_price_block_line {
	background-color: {$colors['alter_dark']};	
}

.sc_price_block .sc_price_block_link .sc_button{
	/*background-color: {$colors['text_link']} !important;*/
}

/* Promo */
.sc_promo_image{
	background-color: {$colors['inverse_text']};
}
.sc_promo_block {
	background-color: {$colors['alter_bg_color']};
}

.sc_promo .sc_promo_subtitle{
	color: {$colors['inverse_text']};
}
.sc_promo .sc_promo_title {
	color: {$colors['inverse_text']};
}
.sc_promo .sc_promo_descr {
	color: {$colors['inverse_text']};
}

.sc_promo .sc_promo_line{
	background-color: {$colors['text_link']};
}

.sc_promo .sc_button.sc_button_style_simple {
	color: {$colors['inverse_text']} !important;
}
.sc_promo .sc_button.sc_button_style_simple:hover {
	color: {$colors['text_link']} !important;
}
.sc_promo .sc_button.sc_button_style_simple:hover::before {
	color: {$colors['text_link']} !important;
}


/* Mounthood - Recent News */
.sc_recent_news_header {
	border-color: {$colors['text_dark']};
}
.sc_recent_news_header_category_item_more {
	color: {$colors['text_link']};
}
.sc_recent_news_header_more_categories {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.sc_recent_news_header_more_categories > a {
	color: {$colors['text_link']};
}
.sc_recent_news_header_more_categories > a:hover {
	color: {$colors['alter_hover']};
	background-color: {$colors['alter_bg_hover']};
}
.sc_recent_news .post_counters_item,
.sc_recent_news .post_counters .post_edit a {
	background-color: {$colors['alter_bg_color']};
}
.sidebar .sc_recent_news .post_counters_item,
.sidebar .sc_recent_news .post_counters .post_edit a {
	background-color: {$colors['bg_color']};
}
.sc_recent_news .post_counters .post_edit a {
	color: {$colors['alter_dark']};
}
.sc_recent_news_style_news-magazine .post_accented_border {
	border-color: {$colors['bd_color']};
}
.sc_recent_news_style_news-excerpt .post_item {
	border-color: {$colors['bd_color']};
}


/* Section */
.sc_section_inner {
	color: {$colors['text']};
}


/* Services */
.sc_services_item .sc_services_item_title{
	color: {$colors['text_link']};
}

.sc_services_item .sc_services_item_readmore {
	color: {$colors['text_dark']};
}
.sc_services_item .sc_services_item_readmore span {
	color: {$colors['text_link']};
}
.sc_services_item .sc_services_item_readmore:hover,
.sc_services_item .sc_services_item_readmore:hover span {
	color: {$colors['text_hover']};
}
.sc_services_style_services-1 .sc_services_item {
	background-color: {$colors['bg_color']};
	color: {$colors['text']};
}
.sc_services_style_services-1 .sc_icon,
.sc_services_style_services-2 .sc_icon {
	color: {$colors['alter_text']};
	/*border-color: {$colors['text_link']};*/
}
.sc_services_style_services-1 .sc_icon:hover,
.sc_services_style_services-1 a:hover .sc_icon,
.sc_services_style_services-2 .sc_icon:hover,
.sc_services_style_services-2 a:hover .sc_icon {
	/*color: {$colors['inverse_text']};*/
	/*background-color: {$colors['text_link']};*/
}

.sc_services_style_services-2 .post_thumb{
	background-color: {$colors['accent_color']};
}
.sc_services_style_services-2 .sc_services_item_subtitle{
	color: {$colors['inverse_text']};
}

.sc_services_style_services-2 .sc_services_item_overlay:hover .sc_services_item_content{
	background-color: {$colors['inverse_text']};
}
.sc_services_style_services-2 .sc_services_item_overlay:hover .sc_services_item_subtitle{
	color: {$colors['text_light']};
}
.sc_services_style_services-2 .sc_services_item_overlay .sc_services_item_title a:hover{
	color: {$colors['text_link']};
}
.sc_services_style_services-2 .sc_services_item_description .sc_button{
	background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_text']} !important;
}
.sc_services_style_services-2 .sc_services_item_overlay .sc_services_item_description .sc_button:hover{
	background-color: {$colors['text_link']} !important;
}
.sc_services_style_services-3 .sc_services_item_title a:hover{
	color: {$colors['text_link']} !important;
}
.sc_services_style_services-3 .sc_services_item_description .sc_button{
	background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_text']} !important;
}
.sc_services_style_services-3 .sc_services_item_description .sc_button:hover{
	background-color: {$colors['text_link']} !important;
}


.sc_services_style_services-3 a:hover .sc_icon,
.sc_services_style_services-3 .sc_icon:hover {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}
.sc_services_style_services-3 a:hover .sc_services_item_title {
	color: {$colors['text_link']};
}
.sc_services_style_services-4 .sc_icon {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_services_style_services-4 .sc_services_item_title {
	color: {$colors['text_dark']};
}
.sc_services_style_services-4 a:hover .sc_icon,
.sc_services_style_services-4 .sc_icon:hover {
	background-color: {$colors['text_hover']};
}
.sc_services_style_services-4 a:hover .sc_services_item_title {
	color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon {
	border-color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon {
	color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon:hover,
.sc_services_style_services-5 a:hover .sc_icon {
	background-color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon:hover,
.sc_services_style_services-5 a:hover .sc_icon {
	color: {$colors['inverse_text']};
}


/* Scroll controls */
.sc_scroll_controls_wrap a {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_scroll_controls_type_side .sc_scroll_controls_wrap a {
	background-color: {$colors['text_link_0_8']};
}
.sc_scroll_controls_wrap a:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}
.sc_scroll_bar .swiper-scrollbar-drag:before {
	background-color: {$colors['text_link']};
}
.sc_scroll .sc_scroll_bar {
	border-color: {$colors['alter_bg_color']};
}

/* Skills */
.sc_skills_bar .sc_skills_item {
	background-color: {$colors['alter_bg_color']};
}
.sc_skills_counter .sc_skills_item .sc_skills_icon {
	color: {$colors['text_link']};
}
.sc_skills_counter .sc_skills_item:hover .sc_skills_icon {
	color: {$colors['text_hover']};
}
.sc_skills_counter .sc_skills_item .sc_skills_info {
	color: {$colors['alter_text']};
}
.sc_skills_counter .sc_skills_item .sc_skills_info .sc_skills_addinfo{
	color: {$colors['text']};
}
.sc_skills_bar .sc_skills_item .sc_skills_count {
	border-color: {$colors['text_link']};
}

.sc_skills_legend {
	color: {$colors['alter_text']};
}

.sc_skills_legend_title, .sc_skills_legend_value {
	color: {$colors['alter_text']};
}

.sc_skills_counter .sc_skills_subtitle{
	color: {$colors['alter_text']};
}

.sc_skills_counter .sc_skills_item.sc_skills_style_1 {
	/*background-color: {$colors['alter_bg_color']};*/
}
.sc_skills_counter .sc_skills_item.sc_skills_style_1:hover {
	/*background-color: {$colors['alter_bg_hover']};*/
}

.sc_skills_counter .sc_skills_item.sc_skills_style_1 .sc_skills_total{
	color: {$colors['text_link']};	
}

.sc_skills_counter .sc_skills_item.sc_skills_style_1 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_1 .sc_skills_info {
	/*color: {$colors['alter_dark']};*/
}
.sc_skills_counter .sc_skills_item.sc_skills_style_1 .sc_skills_info:before {
	/*background-color: {$colors['alter_bd_color']};*/
}

.sc_skills_counter .sc_skills_item.sc_skills_style_2 {
	/*background-color: {$colors['alter_bg_color']};*/
}
.sc_skills_counter .sc_skills_item.sc_skills_style_2:hover {
	/*background-color: {$colors['alter_bg_hover']};*/
}

.sc_skills_counter .sc_skills_item.sc_skills_style_2 .sc_skills_total{
	color: {$colors['text_link']};	
}

.sc_skills_counter .sc_skills_item.sc_skills_style_2 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_2 .sc_skills_info {
	/*color: {$colors['alter_dark']};*/
}
.sc_skills_counter .sc_skills_item.sc_skills_style_2 .sc_skills_info:before {
	/*background-color: {$colors['alter_bd_color']};*/
}


.sc_skills_bar .sc_skills_item .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}

.sc_skills_bar .sc_skills_info .sc_skills_label {
	color: {$colors['alter_dark']};	
}

.sc_skills_bar.sc_skills_horizontal .sc_skills_total{
	color: {$colors['inverse_text']};	
}

/* Slider */
.sc_slider_controls_wrap a {
	color: {$colors['text_dark']};
	/*border-color: {$colors['bd_color']};*/
	background-color: transparent !important;
}
.sc_slider_controls_wrap a:hover {
	color: {$colors['text_link']} !important;
	/*border-color: {$colors['text_link']};*/
	background-color: transparent !important;
}
.sc_slider_swiper .sc_slider_pagination_wrap .swiper-pagination-bullet-active,
.sc_slider_swiper .sc_slider_pagination_wrap span:hover {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.sc_slider_swiper .sc_slider_info {
	background-color: {$colors['text_link_0_8']} !important;
}
.sc_slider_pagination.widget_area .post_item + .post_item {
	border-color: {$colors['bd_color']};
}
.sc_slider_pagination_over .sc_slider_pagination {
	background-color: {$colors['alter_bg_color_0_8']};
}
.sc_slider_pagination_over .sc_slider_pagination_wrap span {
	border-color: {$colors['bd_color']};
}
.sc_slider_pagination_over .sc_slider_pagination_wrap span:hover,
.sc_slider_pagination_over .sc_slider_pagination_wrap .swiper-pagination-bullet-active {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.sc_slider_pagination_over .sc_slider_pagination .post_title {
	color: {$colors['alter_dark']};
}
.sc_slider_pagination_over .sc_slider_pagination .post_info {
	color: {$colors['alter_dark']};
}
.sc_slider_pagination_area .sc_slider_pagination .post_item.active {
	/*background-color: {$colors['alter_bg_color']} !important;*/
}

/* Socials */
.widget_area_inner .widget_text .sc_socials a,
.sc_socials.sc_socials_type_icons a {
	color: {$colors['alter_bg_color']};
	border-color: {$colors['inverse_text']};
	background-color: {$colors['inverse_text']};
}
.widget_area_inner .widget_text .sc_socials a:hover,
.sc_socials.sc_socials_type_icons a:hover {
	color: {$colors['inverse_text']};
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.sc_socials.sc_socials_share.sc_socials_dir_vertical .sc_socials_item a {
	background-color: {$colors['alter_bg_color']};
}

/* Tabs */
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a {
	color: {$colors['text_dark']};
	border-color: {$colors['bd_color']};
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.sc_tabs_active a,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a:hover {
	color: {$colors['text_link']};
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a:after,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.sc_tabs_active a:after {
	background-color: {$colors['text_link']};
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_content,
.sc_tabs.sc_tabs_style_2 .sc_tabs_content {
	border-color: {$colors['bd_color']};
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.sc_tabs_active a {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}

.sidebar .sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
.sidebar .sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a,
.sidebar .sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.sc_tabs_active a {
	color: {$colors['inverse_text']};
	border-color: {$colors['inverse_text']};
}



/* Team */
.sc_team_item .sc_team_item_info .sc_team_item_title a {
	color: {$colors['text_dark']};
}
.sc_team_item .sc_team_item_info .sc_team_item_title a:hover {
	color: {$colors['text_hover']};
}
.sc_team_item .sc_team_item_info .sc_team_item_position {
	color: {$colors['text_light']};
}

.sc_team_style_team-1 .sc_team_item{
	background-color: {$colors['alter_bd_color']};
}

.sc_team_style_team-1 .sc_team_item .sc_team_item_info .sc_team_item_title a{
	color: {$colors['text_link']};
}

.sc_team_style_team-1 .sc_team_item .sc_team_item_info .sc_team_item_description{
	color: {$colors['text_light']};
}


.sc_team_style_team-1 .sc_team_item_info,
.sc_team_style_team-3 .sc_team_item_info {
	border-color: {$colors['text_link']};
	color: {$colors['text']};
}
.sc_team.sc_team_style_team-3 .sc_socials_item a {
	color: {$colors['inverse_text']};
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.sc_team.sc_team_style_team-3 .sc_socials_item a:hover {
	color: {$colors['inverse_dark']};
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}
.sc_team_style_team-3 .sc_team_item_info .sc_team_item_title a:hover{
	color: {$colors['text_link']};
}
.sc_team.sc_team_style_team-3 .sc_team_item_avatar .sc_team_item_hover {
	background-color: {$colors['inverse_text_0_8']};
}

.sc_team_style_team-4 {
	background-color: {$colors['text_dark_0_38']};
}

.sc_team.sc_team_style_team-4 .sc_socials_item a {
	color: {$colors['inverse_link']};
	border-color: {$colors['alter_bg_color']};
	background-color: {$colors['alter_bg_color']};
}
.sc_team.sc_team_style_team-4 .sc_socials_item a:hover {
	color: {$colors['inverse_link']};
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.sc_team.sc_team_style_team-4 .sc_team_item_avatar .sc_team_item_hover {
	/*color: {$colors['inverse_text']};*/
	background-color: {$colors['inverse_text']};
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a {
	color: {$colors['alter_dark']};
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a:hover {
	color: {$colors['text_link']};
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_position {
	color: {$colors['inverse_text']};
}

.post_item_single_team .single_team_post_description .team_position {
	color: {$colors['text_link']};
}

/* Testimonials */
.sc_testimonials {
	color: {$colors['text']};
}
.sc_testimonial_author_name {
	color: {$colors['text_dark']};
}
.sc_testimonial_position_position {
	color: {$colors['text_light']};
}
.sc_testimonials_style_testimonials-1 .sc_testimonial_item {
	background-color: {$colors['bg_color']};
}
.sc_testimonials_style_testimonials-1 .sc_testimonial_content p:first-child:before,
.sc_testimonials_style_testimonials-1 .sc_testimonial_author_position {
	color: {$colors['text_link']};
}

.sc_testimonials_style_testimonials-1 .sc_item_subtitle {
	color: {$colors['inverse_text']};
}

.sc_testimonials_style_testimonials-1 .sc_item_title{
	color: {$colors['alter_dark']};
}

/*.sc_testimonials_style_testimonials-1 .sc_item_title:after {
	background-color: {$colors['inverse_text']};
}*/
.scheme_self.sc_testimonials_wrap .sc_testimonials_style_testimonials-1 .sc_item_title:after {
	background-color: {$colors['inverse_text']};
}

.sc_testimonials_style_testimonials-1 .sc_testimonial_item:before{
	color: {$colors['accent_color']};
}

.sc_testimonials_style_testimonials-1 .sc_slider_controls_bottom .sc_slider_controls_wrap a{
	color: {$colors['text_dark']};
}
.sc_testimonials_style_testimonials-1 .sc_slider_controls_bottom .sc_slider_controls_wrap a:hover{
	color: {$colors['inverse_text']} !important;
}

.sc_testimonials_style_testimonials-2 .sc_testimonial_author_name:before {
	color: {$colors['text_light']};
}
.sc_testimonials_style_testimonials-3 .sc_testimonial_content,
.sc_testimonials_style_testimonials-3 .sc_testimonial_content:after {
	background-color: {$colors['bg_color']};
}
.sc_testimonials_style_testimonials-3 .sc_testimonial_content p:first-child:before,
.sc_testimonials_style_testimonials-3 .sc_testimonial_author_position {
	color: {$colors['text_link']};
}

/* Title */
.sc_title_icon {
	color: {$colors['text_link']};
}
.sc_title_underline::after {
	border-color: {$colors['text_link']};
}
.sc_title_underline_dark::after {
	border-color: {$colors['alter_dark']};
}
.sc_title_underline_white::after {
	border-color: {$colors['inverse_text']};
}
.sc_title_divider .sc_title_divider_before,
.sc_title_divider .sc_title_divider_after {
	background-color: {$colors['text_dark']};
}

/* Toggles */
.sc_toggles .sc_toggles_item .sc_toggles_title {
	border-color: {$colors['bd_color']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title .sc_toggles_icon {
	color: {$colors['alter_light']};
	background-color: {$colors['alter_bg_color']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon_opened {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover {
	color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover .sc_toggles_icon_opened {
	background-color: {$colors['text_hover']};
}
.sc_toggles .sc_toggles_item .sc_toggles_content {
	border-color: {$colors['bd_color']};
}


/* Tooltip */
.sc_tooltip_parent {
	color: {$colors['text_link']};	
}
.sc_tooltip_parent .sc_tooltip,
.sc_tooltip_parent .sc_tooltip:before {
	background-color: {$colors['text_dark']};
}


/* Twitter */
.sc_twitter {
	color: {$colors['text']};
}
.sc_twitter .sc_slider_controls_wrap a {
	color: {$colors['inverse_text']};
}

/* Common styles (title, subtitle and description for some shortcodes) */
.sc_item_subtitle {
	/*color: {$colors['text_link']};*/
	color: {$colors['alter_dark']};
}
.sc_item_title:after {
	background-color: {$colors['accent_color']};
}
.sc_section_subtitle{
	color: {$colors['text_dark']};
}

.sc_item_button > a:before {
	/*color: {$colors['text_link']};*/
	/*background-color: {$colors['inverse_text']};*/
}
.sc_item_button > a:hover:before {
	/*color: {$colors['text_hover']};*/
	color: {$colors['inverse_text']};
}
.sc_item_button > a:hover:before {
	/*color: {$colors['text_hover']};*/
	color: {$colors['inverse_text']};
}

.sc_blogger_button a.sc_button_style_simple:hover:before{
	color: {$colors['text_link']};
}

/* Booked */

body #booked-profile-page input[type=submit].button-primary:hover, 
body .booked-list-view button.button:hover, 
body .booked-list-view input[type=submit].button-primary:hover, 
body table.booked-calendar input[type=submit].button-primary:hover, 
body .booked-modal input[type=submit].button-primary:hover, 
body table.booked-calendar thead, 
body table.booked-calendar thead th, 
body table.booked-calendar .booked-appt-list .timeslot .timeslot-people button:hover, 
body #booked-profile-page .booked-profile-header, 
body #booked-profile-page .booked-tabs li.active a, 
body #booked-profile-page .booked-tabs li.active a:hover, 
body #booked-profile-page .appt-block .google-cal-button > a:hover {
	background: {$colors['inverse_dark']} !important;
	border-color: {$colors['inverse_dark']} !important;
	color: {$colors['text']} !important;
}

body table.booked-calendar thead th .page-right, 
body table.booked-calendar thead th .page-left {
	color: {$colors['text_hover']} !important;
}
body table.booked-calendar thead th .page-right:hover, 
body table.booked-calendar thead th .page-left:hover {
	color: {$colors['text_link']} !important;
}

body table.booked-calendar tr.days th, 
body #booked-profile-page .booked-tabs {
	border-color: {$colors['accent_color']} !important;
}

body table.booked-calendar tr.days, 
body table.booked-calendar tr.days th, 
body .booked-calendarSwitcher.calendar, 
body .booked-calendarSwitcher.calendar select, 
body #booked-profile-page .booked-tabs {
	background: {$colors['accent_color']} !important;
}

body table.booked-calendar td.today .date span {
	border-color: {$colors['text_link']} !important;
}
body table.booked-calendar td.today:hover .date span {
	border-color: {$colors['text_hover']} !important;
}
body table.booked-calendar tbody td:nth-child(2n+1) span{
	background-color: {$colors['alter_bg_hover']} !important;
	color: {$colors['inverse_text']};
}
body table.booked-calendar tbody td:nth-child(2n+0) span{
	background-color: {$colors['alter_bd_hover']} !important;
	color: {$colors['inverse_text']};
}
body table.booked-calendar td.next-month .date span, 
body table.booked-calendar td.prev-month .date span{
	color: {$colors['inverse_text_0_5']};
}
body table.booked-calendar td.next-month:hover .date.tooltipster span, 
body table.booked-calendar td.prev-month:hover .date.tooltipster span{
	color: {$colors['accent_color']} !important;
}

body table.booked-calendar td:hover .date span {
	color: {$colors['text_dark']} !important;
}
body table.booked-calendar td[class=""]:hover .date span {
	color: {$colors['accent_color']} !important;
}
body table.booked-calendar td.active .date span {
	color: {$colors['inverse_link']} !important;
}
body table.booked-calendar td.today:hover .date span{
	color: {$colors['text_dark']} !important;
}

body table.booked-calendar tr.week td.active .date .number{
	color: {$colors['inverse_link']} !important;
}
body table.booked-calendar tr.week td.active:hover .date .number{
	color: {$colors['accent_color']} !important;
}
body table.booked-calendar tr.week td.active.today:hover .date .number{
	color: {$colors['text_dark']} !important;
}
body table.booked-calendar td.today .date span{
	color: {$colors['inverse_link']} !important;
}
body table.booked-calendar .entryBlock .booked-appt-list h2 span{
	color: {$colors['text_dark']} !important;
	background-color: transparent !important;
}

body table.booked-calendar .booked-appt-list .timeslot:not(.timeslot-unavailable) .timeslot-people button{
	border-color: {$colors['accent_color']} !important;
	background-color: {$colors['accent_color']} !important;
}
body table.booked-calendar .booked-appt-list .timeslot:not(.timeslot-unavailable) .timeslot-people button:hover{
	border-color: {$colors['inverse_link']} !important;
	background-color: {$colors['inverse_link']} !important;
}

body .booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button span{
	color: {$colors['text_dark']} !important;
	background-color: transparent !important;
}

body .booked-modal p.booked-title-bar {
	color: {$colors['text_dark']} !important;
	border-color: {$colors['accent_color']} !important;
	background-color: {$colors['accent_color']} !important;	
}

body #booked-profile-page input[type=submit].button-primary, body table.booked-calendar input[type=submit].button-primary, body .booked-list-view button.button, body .booked-list-view input[type=submit].button-primary, body .booked-list-view button.button, body .booked-list-view input[type=submit].button-primary, body .booked-modal input[type=submit].button-primary, body #booked-profile-page .appt-block .google-cal-button > a, body table.booked-calendar .booked-appt-list .timeslot .timeslot-people button, body .booked-list-view a.booked_list_date_picker_trigger.booked-dp-active, body .booked-list-view a.booked_list_date_picker_trigger.booked-dp-active:hover {
	color: {$colors['text_dark']} !important;
	border-color: {$colors['accent_color']} !important;
	background-color: {$colors['accent_color']} !important;	
}

body #booked-profile-page input[type=submit], body #booked-profile-page button, body .booked-list-view input[type=submit], body .booked-list-view button, body table.booked-calendar input[type=submit], body table.booked-calendar button, body .booked-modal input[type=submit], body .booked-modal button {
	color: {$colors['text_dark']} !important;
}
body #booked-profile-page input[type=submit]:hover, body #booked-profile-page button:hover, body .booked-list-view input[type=submit]:hover, body .booked-list-view button:hover, body table.booked-calendar input[type=submit]:hover, body table.booked-calendar button:hover, body .booked-modal input[type=submit]:hover, body .booked-modal button:hover {
	color: {$colors['inverse_link']} !important;
}

body .booked-modal .bm-window .close {
	color: {$colors['text_dark']} !important;	
}
body .booked-modal .bm-window .close:hover {
	color: {$colors['text_dark_0_38']} !important;	
}

CSS;
					
					$rez = apply_filters('mounthood_filter_get_css', $rez, $colors, $fonts, $scheme);
					
					$css['colors'] .= $rez['colors'];
					if ($step == 1) $css['fonts'] = $rez['fonts'];
					$step++;
				}
			}
		} else
			$css['fonts'] = $rez['fonts'];
		
		$css_str = (!empty($css['fonts']) ? $css['fonts'] : '')
					. (!empty($css['colors']) ? $css['colors'] : '');
		
		if (!empty($css_str))
			$css_str = $add_comment . ($minify ? mounthood_minify_css($css_str) : $css_str);

		return $css_str;
	}
}
?>