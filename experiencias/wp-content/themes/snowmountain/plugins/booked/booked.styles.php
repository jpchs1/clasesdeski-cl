<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('snowmountain_booked_get_css')) {
	add_filter('snowmountain_filter_get_css', 'snowmountain_booked_get_css', 10, 4);
	function snowmountain_booked_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS


CSS;
		}
		
		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS


CSS;
		}

		return $css;
	}
}
?>