<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('snowmountain_mailchimp_get_css')) {
	add_filter('snowmountain_filter_get_css', 'snowmountain_mailchimp_get_css', 10, 4);
	function snowmountain_mailchimp_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

CSS;
		
			
			$rad = snowmountain_get_border_radius();
			$css['fonts'] .= <<<CSS

.mc4wp-form .mc4wp-form-fields input[type="email"],
.mc4wp-form .mc4wp-form-fields input[type="submit"] {
	-webkit-border-radius: {$rad};
	    -ms-border-radius: {$rad};
			border-radius: {$rad};
}

CSS;
		}

		
		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

.mc4wp-form input[type="email"] {
	background-color: {$colors['bg_color_02']};
	color: {$colors['inverse_text']};
}
.mc4wp-form input[type="email"]::-webkit-input-placeholder {
	color: {$colors['inverse_text']};
}
.mc4wp-form .mc4wp-form-fields input[type="submit"] {
	background-color: {$colors['bg_color']};
	color: {$colors['text_dark']};
}
.mc4wp-form .mc4wp-form-fields input[type="submit"]:hover,
.mc4wp-form .mc4wp-form-fields input[type="submit"]:focus {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

CSS;
		}

		return $css;
	}
}
?>