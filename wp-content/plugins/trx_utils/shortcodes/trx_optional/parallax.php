<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('mounthood_sc_parallax_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_sc_parallax_theme_setup' );
	function mounthood_sc_parallax_theme_setup() {
		add_action('mounthood_action_shortcodes_list', 		'mounthood_sc_parallax_reg_shortcodes');
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_sc_parallax_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_parallax id="unique_id" style="light|dark" dir="up|down" image="" color='']Content for parallax block[/trx_parallax]
*/

if (!function_exists('mounthood_sc_parallax')) {	
	function mounthood_sc_parallax($atts, $content=null){	
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"gap" => "no",
			"dir" => "up",
			"speed" => 0.3,
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_image_x" => "",
			"bg_image_y" => "",
			"bg_video" => "",
			"bg_video_ratio" => "16:9",
			"bg_overlay" => "",
			"bg_texture" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if ($bg_video!='') {
			$info = pathinfo($bg_video);
			$ext = !empty($info['extension']) ? $info['extension'] : 'mp4';
			$bg_video_ratio = empty($bg_video_ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $bg_video_ratio);
			$ratio = explode(':', $bg_video_ratio);
			$bg_video_width = !empty($width) && mounthood_substr($width, -1) >= '0' && mounthood_substr($width, -1) <= '9'  ? $width : 1280;
			$bg_video_height = round($bg_video_width / $ratio[0] * $ratio[1]);
			if (mounthood_get_theme_option('use_mediaelement')=='yes')
				wp_enqueue_script('wp-mediaelement');
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		$bg_image_x = $bg_image_x!='' ? str_replace('%', '', $bg_image_x).'%' : "50%";
		$bg_image_y = $bg_image_y!='' ? str_replace('%', '', $bg_image_y).'%' : "50%";
		$speed = ($dir=='down' ? -1 : 1) * abs($speed);
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = mounthood_get_scheme_color('bg');
			$rgb = mounthood_hex2rgb($bg_color);
		}
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= mounthood_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			;
		$output = (mounthood_param_is_on($gap) ? mounthood_gap_start() : '')
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_parallax' 
					. ($bg_video!='' ? ' sc_parallax_with_video' : '') 
					. ($scheme && !mounthood_param_is_off($scheme) && !mounthood_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. ' data-parallax-speed="'.esc_attr($speed).'"'
				. ' data-parallax-x-pos="'.esc_attr($bg_image_x).'"'
				. ' data-parallax-y-pos="'.esc_attr($bg_image_y).'"'
				. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
				. '>'
			. ($bg_video!='' 
				? '<div class="sc_video_bg_wrapper"><video class="sc_video_bg"'
					. ' width="'.esc_attr($bg_video_width).'" height="'.esc_attr($bg_video_height).'" data-width="'.esc_attr($bg_video_width).'" data-height="'.esc_attr($bg_video_height).'" data-ratio="'.esc_attr($bg_video_ratio).'" data-frame="no"'
					. ' preload="metadata" autoplay="autoplay" loop="loop" src="'.esc_attr($bg_video).'"><source src="'.esc_url($bg_video).'" type="video/'.esc_attr($ext).'"></source></video></div>' 
				: '')
			. '<div class="sc_parallax_content" style="' . ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . '); background-position:'.esc_attr($bg_image_x).' '.esc_attr($bg_image_y).';' : '').'">'
			. ($bg_overlay>0 || $bg_texture!=''
				? '<div class="sc_parallax_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
					. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
						. (mounthood_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
						. '"'
						. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
						. '>' 
				: '')
			. do_shortcode($content)
			. ($bg_overlay > 0 || $bg_texture!='' ? '</div>' : '')
			. '</div>'
			. '</div>'
			. (mounthood_param_is_on($gap) ? mounthood_gap_end() : '');
		return apply_filters('mounthood_shortcode_output', $output, 'trx_parallax', $atts, $content);
	}
	mounthood_require_shortcode('trx_parallax', 'mounthood_sc_parallax');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_parallax_reg_shortcodes' ) ) {
	//add_action('mounthood_action_shortcodes_list', 'mounthood_sc_parallax_reg_shortcodes');
	function mounthood_sc_parallax_reg_shortcodes() {
	
		mounthood_sc_map("trx_parallax", array(
			"title" => esc_html__("Parallax", "mounthood"),
			"desc" => wp_kses_data( __("Create the parallax container (with asinc background image)", "mounthood") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"gap" => array(
					"title" => esc_html__("Create gap", "mounthood"),
					"desc" => wp_kses_data( __("Create gap around parallax container", "mounthood") ),
					"value" => "no",
					"size" => "small",
					"options" => mounthood_get_sc_param('yes_no'),
					"type" => "switch"
				), 
				"dir" => array(
					"title" => esc_html__("Dir", "mounthood"),
					"desc" => wp_kses_data( __("Scroll direction for the parallax background", "mounthood") ),
					"value" => "up",
					"size" => "medium",
					"options" => array(
						'up' => esc_html__('Up', 'trx_utils'),
						'down' => esc_html__('Down', 'trx_utils')
					),
					"type" => "switch"
				), 
				"speed" => array(
					"title" => esc_html__("Speed", "mounthood"),
					"desc" => wp_kses_data( __("Image motion speed (from 0.0 to 1.0)", "mounthood") ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0.3",
					"type" => "spinner"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "mounthood"),
					"desc" => wp_kses_data( __("Select color scheme for this block", "mounthood") ),
					"value" => "",
					"type" => "checklist",
					"options" => mounthood_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Text color", "mounthood"),
					"desc" => wp_kses_data( __("Select color for text object inside parallax block", "mounthood") ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "mounthood"),
					"desc" => wp_kses_data( __("Select color for parallax background", "mounthood") ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image", "mounthood"),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the parallax background", "mounthood") ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_image_x" => array(
					"title" => esc_html__("Image X position", "mounthood"),
					"desc" => wp_kses_data( __("Image horizontal position (as background of the parallax block) - in percent", "mounthood") ),
					"min" => "0",
					"max" => "100",
					"value" => "50",
					"type" => "spinner"
				),
				"bg_video" => array(
					"title" => esc_html__("Video background", "mounthood"),
					"desc" => wp_kses_data( __("Select video from media library or paste URL for video file from other site to show it as parallax background", "mounthood") ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'trx_utils'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'trx_utils'),
							'update' => esc_html__('Select video file', 'trx_utils')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"bg_video_ratio" => array(
					"title" => esc_html__("Video ratio", "mounthood"),
					"desc" => wp_kses_data( __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "mounthood") ),
					"value" => "16:9",
					"type" => "text"
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", "mounthood"),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", "mounthood") ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", "mounthood"),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", "mounthood") ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"_content_" => array(
					"title" => esc_html__("Content", "mounthood"),
					"desc" => wp_kses_data( __("Content for the parallax container", "mounthood") ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"width" => mounthood_shortcodes_width(),
				"height" => mounthood_shortcodes_height(),
				"top" => mounthood_get_sc_param('top'),
				"bottom" => mounthood_get_sc_param('bottom'),
				"left" => mounthood_get_sc_param('left'),
				"right" => mounthood_get_sc_param('right'),
				"id" => mounthood_get_sc_param('id'),
				"class" => mounthood_get_sc_param('class'),
				"animation" => mounthood_get_sc_param('animation'),
				"css" => mounthood_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_parallax_reg_shortcodes_vc' ) ) {
	//add_action('mounthood_action_shortcodes_list_vc', 'mounthood_sc_parallax_reg_shortcodes_vc');
	function mounthood_sc_parallax_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_parallax",
			"name" => esc_html__("Parallax", "mounthood"),
			"description" => wp_kses_data( __("Create the parallax container (with asinc background image)", "mounthood") ),
			"category" => esc_html__('Structure', 'trx_utils'),
			'icon' => 'icon_trx_parallax',
			"class" => "trx_sc_collection trx_sc_parallax",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "gap",
					"heading" => esc_html__("Create gap", "mounthood"),
					"description" => wp_kses_data( __("Create gap around parallax container (not need in fullscreen pages)", "mounthood") ),
					"class" => "",
					"value" => array(esc_html__('Create gap', 'trx_utils') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", "mounthood"),
					"description" => wp_kses_data( __("Scroll direction for the parallax background", "mounthood") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Up', 'trx_utils') => 'up',
							esc_html__('Down', 'trx_utils') => 'down'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "speed",
					"heading" => esc_html__("Speed", "mounthood"),
					"description" => wp_kses_data( __("Parallax background motion speed (from 0.0 to 1.0)", "mounthood") ),
					"class" => "",
					"value" => "0.3",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "mounthood"),
					"description" => wp_kses_data( __("Select color scheme for this block", "mounthood") ),
					"group" => esc_html__('Colors and Images', 'trx_utils'),
					"class" => "",
					"value" => array_flip(mounthood_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "mounthood"),
					"description" => wp_kses_data( __("Select color for text object inside parallax block", "mounthood") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Backgroud color", "mounthood"),
					"description" => wp_kses_data( __("Select color for parallax background", "mounthood") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", "mounthood"),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the parallax background", "mounthood") ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_image_x",
					"heading" => esc_html__("Image X position", "mounthood"),
					"description" => wp_kses_data( __("Parallax background X position (in percents)", "mounthood") ),
					"class" => "",
					"value" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_video",
					"heading" => esc_html__("Video background", "mounthood"),
					"description" => wp_kses_data( __("Paste URL for video file to show it as parallax background", "mounthood") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_video_ratio",
					"heading" => esc_html__("Video ratio", "mounthood"),
					"description" => wp_kses_data( __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "mounthood") ),
					"class" => "",
					"value" => "16:9",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", "mounthood"),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", "mounthood") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", "mounthood"),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", "mounthood") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('animation'),
				mounthood_get_vc_param('css'),
				mounthood_vc_width(),
				mounthood_vc_height(),
				mounthood_get_vc_param('margin_top'),
				mounthood_get_vc_param('margin_bottom'),
				mounthood_get_vc_param('margin_left'),
				mounthood_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Parallax extends MOUNTHOOD_VC_ShortCodeCollection {}
	}
}
?>