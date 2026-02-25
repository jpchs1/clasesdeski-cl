<?php
/**
 * Mounthood Framework: Testimonial support
 *
 * @package	mounthood
 * @since	mounthood 1.0
 */

// Theme init
if (!function_exists('mounthood_testimonial_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_testimonial_theme_setup', 1 );
	function mounthood_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_filter('trx_utils_filter_override_options',		'mounthood_testimonial_add_override_options');

		// Save data from override options
		add_action('save_post',				'mounthood_testimonial_save_data');

		// Register shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('mounthood_action_shortcodes_list',		'mounthood_testimonials_reg_shortcodes');
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_testimonials_reg_shortcodes_vc');

		// Meta box fields
		mounthood_storage_set('testimonial_override_options', array(
			'id' => 'testimonial-override-options',
			'title' => esc_html__('Testimonial Details', 'mounthood'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'mounthood'),
					"desc" => wp_kses_data( __("Name of the testimonial's author", 'mounthood') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'mounthood'),
					"desc" => wp_kses_data( __("Position of the testimonial's author", 'mounthood') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'mounthood'),
					"desc" => wp_kses_data( __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'mounthood') ),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'mounthood'),
					"desc" => wp_kses_data( __("URL of the testimonial source or author profile page", 'mounthood') ),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
				)
			)
		);
		
		// Add supported data types
		mounthood_theme_support_pt('testimonial');
		mounthood_theme_support_tx('testimonial_group');
		
	}
}


// Add override options
if (!function_exists('mounthood_testimonial_add_override_options')) {
    function mounthood_testimonial_add_override_options($boxes = array()) {
        $boxes[] = array_merge(mounthood_storage_get('testimonial_override_options'), array('callback' => 'mounthood_testimonial_show_override_options'));
        return $boxes;
    }
}


// Callback function to show fields in override options
if (!function_exists('mounthood_testimonial_show_override_options')) {
	function mounthood_testimonial_show_override_options() {
		global $post;

		// Use nonce for verification
		echo '<input type="hidden" name="override_options_testimonial_nonce" value="'.esc_attr(wp_create_nonce(admin_url())).'" />';
		
		$data = get_post_meta($post->ID, mounthood_storage_get('options_prefix').'_testimonial_data', true);
	
		$fields = mounthood_storage_get_array('testimonial_override_options', 'fields');
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from override options
if (!function_exists('mounthood_testimonial_save_data')) {
	//Handler of add_action('save_post', 'mounthood_testimonial_save_data');
	function mounthood_testimonial_save_data($post_id) {
		// verify nonce
		if ( !wp_verify_nonce( mounthood_get_value_gp('override_options_testimonial_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = mounthood_storage_get_array('testimonial_override_options', 'fields');

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, mounthood_storage_get('options_prefix').'_testimonial_data', $data);
	}
}






// ---------------------------------- [trx_testimonials] ---------------------------------------

if (!function_exists('mounthood_sc_testimonials')) {
	function mounthood_sc_testimonials($atts, $content=null){	
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-1",
			"columns" => 1,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"title_align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && mounthood_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = mounthood_get_scheme_color('bg');
			$rgb = mounthood_hex2rgb($bg_color);
		}
		
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = mounthood_get_css_dimensions_from_values($width);
		$hs = mounthood_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (mounthood_param_is_off($custom) && $count < $columns) $columns = $count;
		
		mounthood_storage_set('sc_testimonials_data', array(
			'id' => $id,
            'style' => $style,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs
            )
        );

		if (mounthood_param_is_on($slider)) mounthood_enqueue_slider('swiper');
	
		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || mounthood_strlen($bg_texture)>2 || ($scheme && !mounthood_param_is_off($scheme) && !mounthood_param_is_inherit($scheme))
					? '<div class="sc_testimonials_wrap sc_section'
							. ($scheme && !mounthood_param_is_off($scheme) && !mounthood_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
							. '"'
						.' style="'
							. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '"'
						. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
						. '>'
						. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
								. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
									. (mounthood_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
									. '"'
									. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
									. '>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
 					. ' ' . esc_attr(mounthood_get_template_property($style, 'container_classes'))
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. ($title_align!='' && $title_align!='none' ? ' title_' . esc_attr($title_align) : '')
					. '"'
				. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(mounthood_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_with_descr') . '">' . trim(mounthood_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(mounthood_strmacros($description)) . '</div>' : '')
			. (mounthood_param_is_on($slider) 
				? ('<div class="sc_slider_swiper swiper-slider-container'
								. ' ' . esc_attr(mounthood_get_slider_controls_classes($controls))
								. (mounthood_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
								. ($hs ? ' sc_slider_height_fixed' : '')
								. '"'
							. (!empty($width) && mounthood_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
							. (!empty($height) && mounthood_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
							. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
							. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
							. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
							. ' data-slides-min-width="250"'
						. '>'
					. '<div class="slides swiper-wrapper">')
				: ($columns > 1 
					? '<div class="sc_columns columns_wrap">' 
					: '')
				);
	
		if (mounthood_param_is_on($custom) && $content) {
			$output .= do_shortcode($content);
		} else {
			global $post;
		
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = mounthood_query_add_sort_order($args, $orderby, $order);
			$args = mounthood_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');
	
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => mounthood_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = mounthood_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], mounthood_storage_get('options_prefix').'_testimonial_data', true);
				$thumb_sizes = mounthood_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				$mult = mounthood_get_retina_multiplier();
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*$mult);
				$output .= mounthood_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}
	
		if (mounthood_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || mounthood_strlen($bg_texture)>2 || ($scheme && !mounthood_param_is_off($scheme) && !mounthood_param_is_inherit($scheme))
						?  '</div></div>'
						: '');
	
		// Add template specific scripts and styles
		do_action('mounthood_action_blog_scripts', $style);

		return apply_filters('mounthood_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	mounthood_require_shortcode('trx_testimonials', 'mounthood_sc_testimonials');
}
	
	
if (!function_exists('mounthood_sc_testimonials_item')) {
	function mounthood_sc_testimonials_item($atts, $content=null){	
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		mounthood_storage_inc_array('sc_testimonials_data', 'counter');
	
		$id = $id ? $id : (mounthood_storage_get_array('sc_testimonials_data', 'id') ? mounthood_storage_get_array('sc_testimonials_data', 'id') . '_' . mounthood_storage_get_array('sc_testimonials_data', 'counter') : '');
	
		$thumb_sizes = mounthood_get_thumb_sizes(array('layout' => mounthood_storage_get_array('sc_testimonials_data', 'style')));

		if (empty($photo)) {
			if (!empty($email))
				$mult = mounthood_get_retina_multiplier();
				$photo = get_avatar($email, $thumb_sizes['w']*$mult);
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = mounthood_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => mounthood_storage_get_array('sc_testimonials_data', 'style'),
			'number' => mounthood_storage_get_array('sc_testimonials_data', 'counter'),
			'columns_count' => mounthood_storage_get_array('sc_testimonials_data', 'columns'),
			'slider' => mounthood_storage_get_array('sc_testimonials_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => mounthood_storage_get_array('sc_testimonials_data', 'css_wh'),
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = mounthood_show_post_layout($args, $post_data);

		return apply_filters('mounthood_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	mounthood_require_shortcode('trx_testimonials_item', 'mounthood_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('mounthood_testimonials_reg_shortcodes')) {
	//Handler of add_filter('mounthood_action_shortcodes_list',	'mounthood_testimonials_reg_shortcodes');
	function mounthood_testimonials_reg_shortcodes() {
		if (mounthood_storage_isset('shortcodes')) {

			$testimonials_groups = mounthood_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = mounthood_get_list_templates('testimonials');
			$controls = mounthood_get_list_slider_controls();

			mounthood_sc_map_before('trx_title', array(
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", 'mounthood'),
					"desc" => wp_kses_data( __("Insert testimonials into post (page)", 'mounthood') ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'mounthood'),
							"desc" => wp_kses_data( __("Title for the block", 'mounthood') ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'mounthood'),
							"desc" => wp_kses_data( __("Subtitle for the block", 'mounthood') ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'mounthood'),
							"desc" => wp_kses_data( __("Short description for the block", 'mounthood') ),
							"value" => "",
							"type" => "textarea"
						),
						"title_align" => array(
							"title" => esc_html__("Alignment title", 'mounthood'),
							"desc" => wp_kses_data( __("Alignment of the title", 'mounthood') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => mounthood_get_sc_param('align')
						),						
						"style" => array(
							"title" => esc_html__("Testimonials style", 'mounthood'),
							"desc" => wp_kses_data( __("Select style to display testimonials", 'mounthood') ),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'mounthood'),
							"desc" => wp_kses_data( __("How many columns use to show testimonials", 'mounthood') ),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'mounthood'),
							"desc" => wp_kses_data( __("Use slider to show testimonials", 'mounthood') ),
							"value" => "yes",
							"type" => "switch",
							"options" => mounthood_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'mounthood'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'mounthood') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'mounthood'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'mounthood') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'mounthood'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'mounthood') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'mounthood'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'mounthood') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => mounthood_get_sc_param('yes_no')
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'mounthood'),
							"desc" => wp_kses_data( __("Alignment of the testimonials block", 'mounthood') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => mounthood_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'mounthood'),
							"desc" => wp_kses_data( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'mounthood') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => mounthood_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'mounthood'),
							"desc" => wp_kses_data( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => mounthood_array_merge(array(0 => esc_html__('- Select category -', 'mounthood')), $testimonials_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'mounthood'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'mounthood'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'mounthood'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => mounthood_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'mounthood'),
							"desc" => wp_kses_data( __("Select desired posts order", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => mounthood_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'mounthood'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'mounthood'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'mounthood') ),
							"value" => "",
							"type" => "checklist",
							"options" => mounthood_get_sc_param('schemes')
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'mounthood'),
							"desc" => wp_kses_data( __("Any background color for this section", 'mounthood') ),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'mounthood'),
							"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'mounthood') ),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'mounthood'),
							"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'mounthood') ),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'mounthood'),
							"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'mounthood') ),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
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
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", 'mounthood'),
						"desc" => wp_kses_data( __("Testimonials item (custom parameters)", 'mounthood') ),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", 'mounthood'),
								"desc" => wp_kses_data( __("Name of the testimonmials author", 'mounthood') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'mounthood'),
								"desc" => wp_kses_data( __("Link URL to the testimonmials author page", 'mounthood') ),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'mounthood'),
								"desc" => wp_kses_data( __("E-mail of the testimonmials author (to get gravatar)", 'mounthood') ),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'mounthood'),
								"desc" => wp_kses_data( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'mounthood') ),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", 'mounthood'),
								"desc" => wp_kses_data( __("Current testimonials text", 'mounthood') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => mounthood_get_sc_param('id'),
							"class" => mounthood_get_sc_param('class'),
							"css" => mounthood_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('mounthood_testimonials_reg_shortcodes_vc')) {
	//Handler of add_filter('mounthood_action_shortcodes_list_vc',	'mounthood_testimonials_reg_shortcodes_vc');
	function mounthood_testimonials_reg_shortcodes_vc() {

		$testimonials_groups = mounthood_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = mounthood_get_list_templates('testimonials');
		$controls			 = mounthood_get_list_slider_controls();
			
		// Testimonials			
		vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", 'mounthood'),
				"description" => wp_kses_data( __("Insert testimonials slider", 'mounthood') ),
				"category" => esc_html__('Content', 'mounthood'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_columns trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Testimonials style", 'mounthood'),
						"description" => wp_kses_data( __("Select style to display testimonials", 'mounthood') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($testimonials_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'mounthood'),
						"description" => wp_kses_data( __("Use slider to show testimonials", 'mounthood') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'mounthood'),
						"class" => "",
						"std" => "yes",
						"value" => array_flip(mounthood_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'mounthood'),
						"description" => wp_kses_data( __("Slider controls style and position", 'mounthood') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'mounthood'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'mounthood'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'mounthood') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'mounthood'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'mounthood'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'mounthood') ),
						"group" => esc_html__('Slider', 'mounthood'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'mounthood'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'mounthood') ),
						"group" => esc_html__('Slider', 'mounthood'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'mounthood'),
						"description" => wp_kses_data( __("Alignment of the testimonials block", 'mounthood') ),
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'mounthood'),
						"description" => wp_kses_data( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'mounthood') ),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'mounthood'),
						"description" => wp_kses_data( __("Title for the block", 'mounthood') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'mounthood'),
						"description" => wp_kses_data( __("Subtitle for the block", 'mounthood') ),
						"group" => esc_html__('Captions', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'mounthood'),
						"description" => wp_kses_data( __("Description for the block", 'mounthood') ),
						"group" => esc_html__('Captions', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "title_align",
						"heading" => esc_html__("Alignment title", 'mounthood'),
						"description" => wp_kses_data( __("Alignment of the title", 'mounthood') ),
						"group" => esc_html__('Captions', 'mounthood'),
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('align')),
						"type" => "dropdown"
					),					
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'mounthood'),
						"description" => wp_kses_data( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(mounthood_array_merge(array(0 => esc_html__('- Select category -', 'mounthood')), $testimonials_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'mounthood'),
						"description" => wp_kses_data( __("How many columns use to show testimonials", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'mounthood'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'mounthood'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'mounthood'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "date",
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'mounthood'),
						"description" => wp_kses_data( __("Select desired posts order", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "desc",
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", 'mounthood'),
						"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'mounthood'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'mounthood') ),
						"group" => esc_html__('Colors and Images', 'mounthood'),
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'mounthood'),
						"description" => wp_kses_data( __("Any background color for this section", 'mounthood') ),
						"group" => esc_html__('Colors and Images', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", 'mounthood'),
						"description" => wp_kses_data( __("Select background image from library for this section", 'mounthood') ),
						"group" => esc_html__('Colors and Images', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'mounthood'),
						"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'mounthood') ),
						"group" => esc_html__('Colors and Images', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'mounthood'),
						"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'mounthood') ),
						"group" => esc_html__('Colors and Images', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					mounthood_vc_width(),
					mounthood_vc_height(),
					mounthood_get_vc_param('margin_top'),
					mounthood_get_vc_param('margin_bottom'),
					mounthood_get_vc_param('margin_left'),
					mounthood_get_vc_param('margin_right'),
					mounthood_get_vc_param('id'),
					mounthood_get_vc_param('class'),
					mounthood_get_vc_param('animation'),
					mounthood_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnsView'
		) );
			
			
		vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", 'mounthood'),
				"description" => wp_kses_data( __("Single testimonials item", 'mounthood') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", 'mounthood'),
						"description" => wp_kses_data( __("Name of the testimonmials author", 'mounthood') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'mounthood'),
						"description" => wp_kses_data( __("Link URL to the testimonmials author page", 'mounthood') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", 'mounthood'),
						"description" => wp_kses_data( __("E-mail of the testimonmials author", 'mounthood') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", 'mounthood'),
						"description" => wp_kses_data( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'mounthood') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					mounthood_get_vc_param('id'),
					mounthood_get_vc_param('class'),
					mounthood_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
		) );
			
		class WPBakeryShortCode_Trx_Testimonials extends MOUNTHOOD_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends MOUNTHOOD_VC_ShortCodeCollection {}
		
	}
}
?>