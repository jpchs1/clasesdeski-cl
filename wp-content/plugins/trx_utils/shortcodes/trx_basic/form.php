<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('mounthood_sc_form_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_sc_form_theme_setup' );
	function mounthood_sc_form_theme_setup() {
		add_action('mounthood_action_shortcodes_list', 		'mounthood_sc_form_reg_shortcodes');
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_sc_form_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna."]
*/

if (!function_exists('mounthood_sc_form')) {	
	function mounthood_sc_form($atts, $content = null) {
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "form_custom",
			"action" => "",
			"return_url" => "",
			"return_page" => "",
			"align" => "",
			"title_align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_form_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= mounthood_get_css_dimensions_from_values($width);
	
		mounthood_enqueue_messages();	// Load core messages
	
		mounthood_storage_set('sc_form_data', array(
			'id' => $id,
            'counter' => 0
            )
        );
	
		if ($style == 'form_custom')
			$content = do_shortcode($content);
		
		$fields = array();
		if (!empty($return_page)) 
			$return_url = get_permalink($return_page);
		if (!empty($return_url))
			$fields[] = array(
				'name' => 'return_url',
				'type' => 'hidden',
				'value' => $return_url
			);

		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
					. ' class="sc_form_wrap'
					. ($scheme && !mounthood_param_is_off($scheme) && !mounthood_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">'
			.'<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_form'
					. ' sc_form_style_'.($style) 
					. (!empty($align) && !mounthood_param_is_off($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($title_align!='' && $title_align!='none' ? ' title_' . esc_attr($title_align) : '')
					. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
				. '>'
					. (!empty($subtitle) 
						? '<h6 class="sc_form_subtitle sc_item_subtitle">' . trim(mounthood_strmacros($subtitle)) . '</h6>' 
						: '')
					. (!empty($title) 
						? '<h2 class="sc_form_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_with_descr') . '">' . trim(mounthood_strmacros($title)) . '</h2>' 
						: '')
					. (!empty($description) 
						? '<div class="sc_form_descr sc_item_descr">' . trim(mounthood_strmacros($description)) . ($style == 1 ? do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]') : '') . '</div>' 
						: '');
		
		$output .= mounthood_show_post_layout(array(
												'layout' => $style,
												'id' => $id,
												'action' => $action,
												'content' => $content,
												'fields' => $fields,
												'show' => false
												), false);

		$output .= '</div>'
				. '</div>';
	
		return apply_filters('mounthood_shortcode_output', $output, 'trx_form', $atts, $content);
	}
	mounthood_require_shortcode("trx_form", "mounthood_sc_form");
}

if (!function_exists('mounthood_sc_form_item')) {	
	function mounthood_sc_form_item($atts, $content=null) {
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "text",
			"name" => "",
			"value" => "",
			"options" => "",
			"align" => "",
			"label" => "",
			"label_position" => "top",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		mounthood_storage_inc_array('sc_form_data', 'counter');
	
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);
		if (empty($id)) $id = mounthood_storage_get_array('sc_form_data', 'id').'_'.mounthood_storage_get_array('sc_form_data', 'counter');
	
		$label = $type!='button' && $type!='submit' && $label ? '<label for="' . esc_attr($id) . '">' . esc_attr($label) . '</label>' : $label;
	
		// Open field container
		$output = '<div class="sc_form_item sc_form_item_'.esc_attr($type)
						.' sc_form_'.($type == 'textarea' ? 'message' : ($type == 'button' || $type == 'submit' ? 'button' : 'field'))
						.' label_'.esc_attr($label_position)
						.($class ? ' '.esc_attr($class) : '')
						.($align && $align!='none' ? ' align'.esc_attr($align) : '')
					.'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
					. '>';
		
		// Label top or left
		if ($type!='button' && $type!='submit' && ($label_position=='top' || $label_position=='left'))
			$output .= $label;

		// Field output
		if ($type == 'textarea')

			$output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>';

        else if ($type=='button' || $type=='submit') {

            static $cnt = 0;
            $cnt++;
            $privacy = trx_utils_get_privacy_text();
            if (!empty($privacy)) {
                $output .= '<div class="sc_form_item sc_form_field_checkbox">
                                 <input type="checkbox" id="i_agree_privacy_policy_sc_form_custom' . esc_attr($cnt) . '" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
                                 <label for="i_agree_privacy_policy_sc_form_custom' . esc_attr($cnt) . '">' . trim($privacy) . '</label>
                             </div>';
            }

            $output .= '<button id="' . esc_attr($id) . '">' . ($label ? $label : $value) . '</button>';
        }


        else if ($type=='radio' || $type=='checkbox') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$i = 0;
					foreach ($options as $v) {
						$i++;
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<div class="sc_form_element">'
										. '<input type="'.esc_attr($type) . '"'
											. ' id="' . esc_attr($id.($i>1 ? '_'.intval($i) : '')) . '"'
											. ' name="' . esc_attr($name ? $name : $id) . (count($options) > 1 && $type=='checkbox' ? '[]' : '') . '"'
											. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
											. (in_array($parts[0], explode(',', $value)) ? ' checked="checked"' : '') 
										. '>'
										. '<label for="' . esc_attr($id.($i>1 ? '_'.intval($i) : '')) . '">' . trim(chop($parts[1])) . '</label>'
									. '</div>';
					}
				}
			}

		} else if ($type=='select') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$output .= '<div class="sc_form_select_container">'
						. '<select id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">';
					foreach ($options as $v) {
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<option'
										. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
										. (in_array($parts[0], explode(',', $value)) ? ' selected="selected"' : '') 
									. '>'
									. trim(chop($parts[1]))
									. '</option>';
					}
					$output .= '</select>'
							. '</div>';
				}
			}

		} else if ($type=='date') {
			wp_enqueue_script( 'jquery-picker', mounthood_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-picker-date', mounthood_get_file_url('/js/picker/picker.date.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_date_wrap icon-calendar-light">'
						. '<input placeholder="' . esc_attr__('Date', 'trx_utils') . '" id="' . esc_attr($id) . '" class="js__datepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';

		} else if ($type=='time') {
			wp_enqueue_script( 'jquery-picker', mounthood_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-picker-time', mounthood_get_file_url('/js/picker/picker.time.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_time_wrap icon-clock-empty">'
						. '<input placeholder="' . esc_attr__('Time', 'trx_utils') . '" id="' . esc_attr($id) . '" class="js__timepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';
	
		} else

			$output .= '<input type="'.esc_attr($type ? $type : 'text').'" id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '">';

		// Label bottom
		if ($type!='button' && $type!='submit' && $label_position=='bottom')
			$output .= $label;
		
		// Close field container
		$output .= '</div>';
	
		return apply_filters('mounthood_shortcode_output', $output, 'trx_form_item', $atts, $content);
	}
	mounthood_require_shortcode('trx_form_item', 'mounthood_sc_form_item');
}

// AJAX Callback: Send contact form data
if ( !function_exists( 'mounthood_sc_form_send' ) ) {
	function mounthood_sc_form_send() {
	
		if ( !wp_verify_nonce( mounthood_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'');
		if (!($contact_email = mounthood_get_theme_option('contact_email')) && !($contact_email = mounthood_get_theme_option('admin_email'))) 
			$response['error'] = esc_html__('Unknown admin email!', 'trx_utils');
		else {
			$type = mounthood_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

			if (in_array($type, array('form_1', 'form_2'))) {
				$user_name	= mounthood_strshort($post_data['username'],	100);
				$user_email	= mounthood_strshort($post_data['email'],	100);
				$user_subj	= mounthood_strshort($post_data['subject'],	100);
				$user_msg	= mounthood_strshort($post_data['message'],	mounthood_get_theme_option('message_maxlength_contacts'));
		
				$subj = sprintf(esc_html__('Site %s - Contact form message from %s', 'trx_utils'), get_bloginfo('site_name'), $user_name);
				$msg = "\n".esc_html__('Name:', 'trx_utils')   .' '.esc_html($user_name)
					.  "\n".esc_html__('E-mail:', 'trx_utils') .' '.esc_html($user_email)
					.  "\n".esc_html__('Subject:', 'trx_utils').' '.esc_html($user_subj)
					.  "\n".esc_html__('Message:', 'trx_utils').' '.esc_html($user_msg);

			} else {

				$subj = sprintf(esc_html__('Site %s - Custom form data', 'trx_utils'), get_bloginfo('site_name'));
				$msg = '';
				if (is_array($post_data) && count($post_data) > 0) {
					foreach ($post_data as $k=>$v) {
						if (is_array($v) || is_object($v)) {
							$msg .= "\n{$k}: [";
							foreach ($v as $k1=>$v1)
								$msg .= ($k1 > 0 ? ' ,' : '') . $v1;
							$msg .= ']';
						} else
							$msg .= "\n{$k}: $v";
					}
				}
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . esc_url(home_url('/')) . ") ............";


            $mail = mounthood_get_theme_option('mail_function') == 'mail' ? 'mail' : 'wp_mail';
            if (is_email($contact_email) && !@$mail($contact_email, $subj, apply_filters('mounthood_filter_form_send_message', $msg))) {
				$response['error'] = esc_html__('Error send message!', 'trx_utils');
			}
		
			echo json_encode($response);
			die();
		}
	}
}

// Show additional fields in the form
if ( !function_exists( 'mounthood_sc_form_show_fields' ) ) {
	function mounthood_sc_form_show_fields($fields) {
		if (is_array($fields) && count($fields)>0) {
			foreach ($fields as $f) {
				if (in_array($f['type'], array('hidden', 'text'))) {
					echo '<input type="'.esc_attr($f['type']).'" name="'.esc_attr($f['name']).'" value="'.esc_attr($f['value']).'">';
				}
			}
		}
	}
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_form_reg_shortcodes' ) ) {
	//add_action('mounthood_action_shortcodes_list', 'mounthood_sc_form_reg_shortcodes');
	function mounthood_sc_form_reg_shortcodes() {
	
		$pages = mounthood_get_list_pages(false);

		mounthood_sc_map("trx_form", array(
			"title" => esc_html__("Form", 'trx_utils'),
			"desc" => wp_kses_data( __("Insert form with specified style or with set of custom fields", 'trx_utils') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'trx_utils'),
					"desc" => wp_kses_data( __("Title for the block", 'trx_utils') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'trx_utils'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'trx_utils') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'trx_utils'),
					"desc" => wp_kses_data( __("Short description for the block", 'trx_utils') ),
					"value" => "",
					"type" => "text"
				),
				"title_align" => array(
					"title" => esc_html__("Alignment title", 'trx_utils'),
					"desc" => wp_kses_data( __("Alignment of the title", 'trx_utils') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => mounthood_get_sc_param('align')
				),						
				"style" => array(
					"title" => esc_html__("Style", 'trx_utils'),
					"desc" => wp_kses_data( __("Select style of the form (if 'style' is not equal 'Custom Form' - all tabs 'Field #' are ignored!)", 'trx_utils') ),
					"divider" => true,
					"value" => 'form_custom',
					"options" => mounthood_get_sc_param('forms'),
					"type" => "checklist"
				), 
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'trx_utils'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'trx_utils') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"options" => mounthood_get_sc_param('schemes')
				),
				"action" => array(
					"title" => esc_html__("Action", 'trx_utils'),
					"desc" => wp_kses_data( __("Contact form action (URL to handle form data). If empty - use internal action", 'trx_utils') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"return_page" => array(
					"title" => esc_html__("Page after submit", 'trx_utils'),
					"desc" => wp_kses_data( __("Select page to redirect after form submit", 'trx_utils') ),
					"value" => "0",
					"type" => "select",
					"options" => $pages
				),
				"return_url" => array(
					"title" => esc_html__("URL to redirect", 'trx_utils'),
					"desc" => wp_kses_data( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", 'trx_utils') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'trx_utils'),
					"desc" => wp_kses_data( __("Select form alignment", 'trx_utils') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => mounthood_get_sc_param('align')
				),
				"width" => mounthood_shortcodes_width(),
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
				"name" => "trx_form_item",
				"title" => esc_html__("Field", 'trx_utils'),
				"desc" => wp_kses_data( __("Custom field", 'trx_utils') ),
				"container" => false,
				"params" => array(
					"type" => array(
						"title" => esc_html__("Type", 'trx_utils'),
						"desc" => wp_kses_data( __("Type of the custom field", 'trx_utils') ),
						"value" => "text",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => mounthood_get_sc_param('field_types')
					), 
					"name" => array(
						"title" => esc_html__("Name", 'trx_utils'),
						"desc" => wp_kses_data( __("Name of the custom field", 'trx_utils') ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Default value", 'trx_utils'),
						"desc" => wp_kses_data( __("Default value of the custom field", 'trx_utils') ),
						"value" => "",
						"type" => "text"
					),
					"options" => array(
						"title" => esc_html__("Options", 'trx_utils'),
						"desc" => wp_kses_data( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", 'trx_utils') ),
						"dependency" => array(
							'type' => array('radio', 'checkbox', 'select')
						),
						"value" => "",
						"type" => "text"
					),
					"label" => array(
						"title" => esc_html__("Label", 'trx_utils'),
						"desc" => wp_kses_data( __("Label for the custom field", 'trx_utils') ),
						"value" => "",
						"type" => "text"
					),
					"label_position" => array(
						"title" => esc_html__("Label position", 'trx_utils'),
						"desc" => wp_kses_data( __("Label position relative to the field", 'trx_utils') ),
						"value" => "top",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => mounthood_get_sc_param('label_positions')
					), 
					"top" => mounthood_get_sc_param('top'),
					"bottom" => mounthood_get_sc_param('bottom'),
					"left" => mounthood_get_sc_param('left'),
					"right" => mounthood_get_sc_param('right'),
					"id" => mounthood_get_sc_param('id'),
					"class" => mounthood_get_sc_param('class'),
					"animation" => mounthood_get_sc_param('animation'),
					"css" => mounthood_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'mounthood_sc_form_reg_shortcodes_vc' ) ) {
	//add_action('mounthood_action_shortcodes_list_vc', 'mounthood_sc_form_reg_shortcodes_vc');
	function mounthood_sc_form_reg_shortcodes_vc() {

		$pages = mounthood_get_list_pages(false);
	
		vc_map( array(
			"base" => "trx_form",
			"name" => esc_html__("Form", 'trx_utils'),
			"description" => wp_kses_data( __("Insert form with specefied style of with set of custom fields", 'trx_utils') ),
			"category" => esc_html__('Content', 'trx_utils'),
			'icon' => 'icon_trx_form',
			"class" => "trx_sc_collection trx_sc_form",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('except' => 'trx_form'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'trx_utils'),
					"description" => wp_kses_data( __("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"std" => "form_custom",
					"value" => array_flip(mounthood_get_sc_param('forms')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'trx_utils'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'trx_utils') ),
					"class" => "",
					"value" => array_flip(mounthood_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "action",
					"heading" => esc_html__("Action", 'trx_utils'),
					"description" => wp_kses_data( __("Contact form action (URL to handle form data). If empty - use internal action", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "return_page",
					"heading" => esc_html__("Page after submit", 'trx_utils'),
					"description" => wp_kses_data( __("Select page to redirect after form submit", 'trx_utils') ),
					"class" => "",
					"std" => 0,
					"value" => array_flip($pages),
					"type" => "dropdown"
				),
				array(
					"param_name" => "return_url",
					"heading" => esc_html__("URL to redirect", 'trx_utils'),
					"description" => wp_kses_data( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", 'trx_utils') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'trx_utils'),
					"description" => wp_kses_data( __("Select form alignment", 'trx_utils') ),
					"class" => "",
					"value" => array_flip(mounthood_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'trx_utils'),
					"description" => wp_kses_data( __("Title for the block", 'trx_utils') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'trx_utils'),
					"description" => wp_kses_data( __("Subtitle for the block", 'trx_utils') ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'trx_utils'),
					"description" => wp_kses_data( __("Description for the block", 'trx_utils') ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "title_align",
					"heading" => esc_html__("Alignment title", 'trx_utils'),
					"description" => wp_kses_data( __("Alignment of the title", 'trx_utils') ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => array_flip(mounthood_get_sc_param('align')),
					"type" => "dropdown"
				),					
				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('animation'),
				mounthood_get_vc_param('css'),
				mounthood_vc_width(),
				mounthood_get_vc_param('margin_top'),
				mounthood_get_vc_param('margin_bottom'),
				mounthood_get_vc_param('margin_left'),
				mounthood_get_vc_param('margin_right')
			)
		) );
		
		
		vc_map( array(
			"base" => "trx_form_item",
			"name" => esc_html__("Form item (custom field)", 'trx_utils'),
			"description" => wp_kses_data( __("Custom field for the contact form", 'trx_utils') ),
			"class" => "trx_sc_item trx_sc_form_item",
			'icon' => 'icon_trx_form_item',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_form,trx_column_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'trx_utils'),
					"description" => wp_kses_data( __("Select type of the custom field", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(mounthood_get_sc_param('field_types')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "name",
					"heading" => esc_html__("Name", 'trx_utils'),
					"description" => wp_kses_data( __("Name of the custom field", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Default value", 'trx_utils'),
					"description" => wp_kses_data( __("Default value of the custom field", 'trx_utils') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "options",
					"heading" => esc_html__("Options", 'trx_utils'),
					"description" => wp_kses_data( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", 'trx_utils') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('radio','checkbox','select')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label",
					"heading" => esc_html__("Label", 'trx_utils'),
					"description" => wp_kses_data( __("Label for the custom field", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label_position",
					"heading" => esc_html__("Label position", 'trx_utils'),
					"description" => wp_kses_data( __("Label position relative to the field", 'trx_utils') ),
					"class" => "",
					"value" => array_flip(mounthood_get_sc_param('label_positions')),
					"type" => "dropdown"
				),
				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('animation'),
				mounthood_get_vc_param('css'),
				mounthood_get_vc_param('margin_top'),
				mounthood_get_vc_param('margin_bottom'),
				mounthood_get_vc_param('margin_left'),
				mounthood_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Form extends MOUNTHOOD_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Form_Item extends MOUNTHOOD_VC_ShortCodeItem {}
	}
}
?>