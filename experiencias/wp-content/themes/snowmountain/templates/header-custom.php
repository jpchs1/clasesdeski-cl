<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.06
 */

$snowmountain_header_css = $snowmountain_header_image = '';
$snowmountain_header_video = snowmountain_get_header_video();
if (true || empty($snowmountain_header_video)) {
	$snowmountain_header_image = get_header_image();
	if (snowmountain_is_on(snowmountain_get_theme_option('header_image_override')) && apply_filters('snowmountain_filter_allow_override_header_image', true)) {
		if (is_category()) {
			if (($snowmountain_cat_img = snowmountain_get_category_image()) != '')
				$snowmountain_header_image = $snowmountain_cat_img;
		} else if (is_singular() || snowmountain_storage_isset('blog_archive')) {
			if (has_post_thumbnail()) {
				$snowmountain_header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				if (is_array($snowmountain_header_image)) $snowmountain_header_image = $snowmountain_header_image[0];
			} else
				$snowmountain_header_image = '';
		}
	}
}

$snowmountain_header_id = str_replace('header-custom-', '', snowmountain_get_theme_option("header_style"));
if ((int) $snowmountain_header_id == 0) {
	$snowmountain_header_id = snowmountain_get_post_id(array(
			'name' => $snowmountain_header_id,
			'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
		)
	);
} else {
	$snowmountain_header_id = apply_filters('trx_addons_filter_get_translated_layout', $snowmountain_header_id);
}

$snowmountain_header_meta = get_post_meta($snowmountain_header_id, 'trx_addons_options', true);

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($snowmountain_header_id); 
						?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($snowmountain_header_id)));
						echo !empty($snowmountain_header_image) || !empty($snowmountain_header_video) 
							? ' with_bg_image' 
							: ' without_bg_image';
						if ($snowmountain_header_video!='') 
							echo ' with_bg_video';
						if ($snowmountain_header_image!='') 
							echo ' '.esc_attr(snowmountain_add_inline_css_class('background-image: url('.esc_url($snowmountain_header_image).');'));
						if (!empty($snowmountain_header_meta['margin']) != '') 
							echo ' '.esc_attr(snowmountain_add_inline_css_class('margin-bottom: '.esc_attr(snowmountain_prepare_css_value($snowmountain_header_meta['margin'])).';'));
						if (is_single() && has_post_thumbnail()) 
							echo ' with_featured_image';
						if (snowmountain_is_on(snowmountain_get_theme_option('header_fullheight'))) 
							echo ' header_fullheight trx-stretch-height';
						?> scheme_<?php echo esc_attr(snowmountain_is_inherit(snowmountain_get_theme_option('header_scheme')) 
														? snowmountain_get_theme_option('color_scheme') 
														: snowmountain_get_theme_option('header_scheme'));
						?>"><?php

	// Background video
	if (!empty($snowmountain_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('snowmountain_action_show_layout', $snowmountain_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>