<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('mounthood_sc_list_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_sc_list_theme_setup' );
	function mounthood_sc_list_theme_setup() {
		add_action('mounthood_action_shortcodes_list', 		'mounthood_sc_list_reg_shortcodes');
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('mounthood_sc_list')) {	
	function mounthood_sc_list($atts, $content=null){	
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		mounthood_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || mounthood_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('mounthood_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	mounthood_require_shortcode('trx_list', 'mounthood_sc_list');
}


if (!function_exists('mounthood_sc_list_item')) {	
	function mounthood_sc_list_item($atts, $content=null) {
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		mounthood_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || mounthood_param_is_inherit($icon)) $icon = mounthood_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || mounthood_param_is_inherit($icon_color)) $icon_color = mounthood_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (mounthood_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (mounthood_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (mounthood_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('mounthood_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	mounthood_require_shortcode('trx_list_item', 'mounthood_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_list_reg_shortcodes' ) ) {
	//add_action('mounthood_action_shortcodes_list', 'mounthood_sc_list_reg_shortcodes');
	function mounthood_sc_list_reg_shortcodes() {
	
		mounthood_sc_map("trx_list", array(
			"title" => esc_html__("List", 'trx_utils'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'trx_utils') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'trx_utils'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'trx_utils') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => mounthood_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'trx_utils'),
					"desc" => wp_kses_data( __("List items color", 'trx_utils') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'trx_utils'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'trx_utils') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => mounthood_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'trx_utils'),
					"desc" => wp_kses_data( __("List icons color", 'trx_utils') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => mounthood_get_sc_param('top'),
				"bottom" => mounthood_get_sc_param('bottom'),
				"left" => mounthood_get_sc_param('left'),
				"right" => mounthood_get_sc_param('right'),
				"id" => mounthood_get_sc_param('id'),
				"class" => mounthood_get_sc_param('class'),
				"animation" => mounthood_get_sc_param('animation'),
				"css" => mounthood_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'trx_utils'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'trx_utils') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'trx_utils'),
						"desc" => wp_kses_data( __("Current list item content", 'trx_utils') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'trx_utils'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'trx_utils') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'trx_utils'),
						"desc" => wp_kses_data( __("Text color for this item", 'trx_utils') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'trx_utils'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'trx_utils') ),
						"value" => "",
						"type" => "icons",
						"options" => mounthood_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'trx_utils'),
						"desc" => wp_kses_data( __("Icon color for this item", 'trx_utils') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'trx_utils'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'trx_utils') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'trx_utils'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'trx_utils') ),
						"value" => "",
						"type" => "text"
					),
					"id" => mounthood_get_sc_param('id'),
					"class" => mounthood_get_sc_param('class'),
					"css" => mounthood_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('mounthood_action_shortcodes_list_vc', 'mounthood_sc_list_reg_shortcodes_vc');
	function mounthood_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'trx_utils'),
			"description" => wp_kses_data( __("List items with specific bullets", 'trx_utils') ),
			"category" => esc_html__('Content', 'trx_utils'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'trx_utils'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'trx_utils') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(mounthood_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'trx_utils'),
					"description" => wp_kses_data( __("List items color", 'trx_utils') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'trx_utils'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => mounthood_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'trx_utils'),
					"description" => wp_kses_data( __("List icons color", 'trx_utils') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
				),
				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('animation'),
				mounthood_get_vc_param('css'),
				mounthood_get_vc_param('margin_top'),
				mounthood_get_vc_param('margin_bottom'),
				mounthood_get_vc_param('margin_left'),
				mounthood_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'trx_utils'),
			"description" => wp_kses_data( __("List item with specific bullet", 'trx_utils') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'trx_utils'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'trx_utils'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'trx_utils') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'trx_utils'),
					"description" => wp_kses_data( __("Link target for the current list item", 'trx_utils') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'trx_utils'),
					"description" => wp_kses_data( __("Text color for this item", 'trx_utils') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'trx_utils'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => mounthood_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'trx_utils'),
					"description" => wp_kses_data( __("Icon color for this item", 'trx_utils') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends MOUNTHOOD_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends MOUNTHOOD_VC_ShortCodeContainer {}
	}
}
?>