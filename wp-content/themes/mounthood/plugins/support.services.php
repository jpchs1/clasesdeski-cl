<?php
/**
 * Mounthood Framework: Services support
 *
 * @package	mounthood
 * @since	mounthood 1.0
 */

// Theme init
if (!function_exists('mounthood_services_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_services_theme_setup',1 );
	function mounthood_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('mounthood_filter_get_blog_type',			'mounthood_services_get_blog_type', 9, 2);
		add_filter('mounthood_filter_get_blog_title',		'mounthood_services_get_blog_title', 9, 2);
		add_filter('mounthood_filter_get_current_taxonomy',	'mounthood_services_get_current_taxonomy', 9, 2);
		add_filter('mounthood_filter_is_taxonomy',			'mounthood_services_is_taxonomy', 9, 2);
		add_filter('mounthood_filter_related_posts_title',	'mounthood_services_related_posts_title', 9, 2);
		add_filter('mounthood_filter_get_stream_page_title',	'mounthood_services_get_stream_page_title', 9, 2);
		add_filter('mounthood_filter_get_stream_page_link',	'mounthood_services_get_stream_page_link', 9, 2);
		add_filter('mounthood_filter_get_stream_page_id',	'mounthood_services_get_stream_page_id', 9, 2);
		add_filter('mounthood_filter_query_add_filters',		'mounthood_services_query_add_filters', 9, 2);
		add_filter('mounthood_filter_detect_inheritance_key','mounthood_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (mounthood_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'mounthood_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'mounthood_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_services] and [trx_services_item]
		add_action('mounthood_action_shortcodes_list',		'mounthood_services_reg_shortcodes');
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_services_reg_shortcodes_vc');
		
		// Add supported data types
		mounthood_theme_support_pt('services');
		mounthood_theme_support_tx('services_group');
	}
}

if ( !function_exists( 'mounthood_services_settings_theme_setup2' ) ) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_services_settings_theme_setup2', 3 );
	function mounthood_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		mounthood_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'custom'
			) )
		);
	}
}

// Return related posts title
if ( !function_exists( 'mounthood_services_related_posts_title' ) ) {
	//Handler of add_filter('mounthood_filter_related_posts_title',	'mounthood_services_related_posts_title', 9, 2);
	function mounthood_services_related_posts_title($title, $post_type) {
		if ($post_type == 'services')
			$title = esc_html__('Related services', 'mounthood');
		return $title;
	}
}


// Return true, if current page is services page
if ( !function_exists( 'mounthood_is_services_page' ) ) {
	function mounthood_is_services_page() {
		$is = in_array(mounthood_storage_get('page_template'), array('blog-services', 'single-service'));
		if (!$is) {
			if (!mounthood_storage_empty('pre_query'))
				$is = mounthood_storage_call_obj_method('pre_query', 'get', 'post_type')=='services' 
						|| mounthood_storage_call_obj_method('pre_query', 'is_tax', 'services_group') 
						|| (mounthood_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=mounthood_get_template_page_id('blog-services')) > 0 
								&& $id==mounthood_storage_get_obj_property('pre_query', 'queried_object_id', 0) 
							);
			else
				$is = get_query_var('post_type')=='services' 
						|| is_tax('services_group') 
						|| (is_page() && ($id=mounthood_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'mounthood_services_detect_inheritance_key' ) ) {
	//Handler of add_filter('mounthood_filter_detect_inheritance_key',	'mounthood_services_detect_inheritance_key', 9, 1);
	function mounthood_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return mounthood_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'mounthood_services_get_blog_type' ) ) {
	//Handler of add_filter('mounthood_filter_get_blog_type',	'mounthood_services_get_blog_type', 9, 2);
	function mounthood_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'mounthood_services_get_blog_title' ) ) {
	//Handler of add_filter('mounthood_filter_get_blog_title',	'mounthood_services_get_blog_title', 9, 2);
	function mounthood_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( mounthood_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = mounthood_get_post_title();
			} else {
				$title = esc_html__('All services', 'mounthood');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'mounthood_services_get_stream_page_title' ) ) {
	//Handler of add_filter('mounthood_filter_get_stream_page_title',	'mounthood_services_get_stream_page_title', 9, 2);
	function mounthood_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (mounthood_strpos($page, 'services')!==false) {
			if (($page_id = mounthood_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = mounthood_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'mounthood');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'mounthood_services_get_stream_page_id' ) ) {
	//Handler of add_filter('mounthood_filter_get_stream_page_id',	'mounthood_services_get_stream_page_id', 9, 2);
	function mounthood_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (mounthood_strpos($page, 'services')!==false) $id = mounthood_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'mounthood_services_get_stream_page_link' ) ) {
	//Handler of add_filter('mounthood_filter_get_stream_page_link',	'mounthood_services_get_stream_page_link', 9, 2);
	function mounthood_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (mounthood_strpos($page, 'services')!==false) {
			$id = mounthood_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'mounthood_services_get_current_taxonomy' ) ) {
	//Handler of add_filter('mounthood_filter_get_current_taxonomy',	'mounthood_services_get_current_taxonomy', 9, 2);
	function mounthood_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( mounthood_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'mounthood_services_is_taxonomy' ) ) {
	//Handler of add_filter('mounthood_filter_is_taxonomy',	'mounthood_services_is_taxonomy', 9, 2);
	function mounthood_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'mounthood_services_query_add_filters' ) ) {
	//Handler of add_filter('mounthood_filter_query_add_filters',	'mounthood_services_query_add_filters', 9, 2);
	function mounthood_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

if ( !function_exists( 'mounthood_sc_services' ) ) {
	function mounthood_sc_services($atts, $content=null){	
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"equalheight" => "no",
			"align" => "",
			"title_align" => "",
			"custom" => "no",
			"margins_service" => "yes",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('Learn more', 'mounthood'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'mounthood'),
			"link" => '',
			"scheme" => '',
			"image" => '',
			"image_align" => '',
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
	
		if (mounthood_param_is_off($slider) && $columns > 1 && $style == 'services-5' && !empty($image)) $columns = 2;
		if (!empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
		}

		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && mounthood_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		
		$class .= ($class ? ' ' : '') . mounthood_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = mounthood_get_css_dimensions_from_values($width);
		$hs = mounthood_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$columns = max(1, min(12, (int) $columns));
		$count = max(1, (int) $count);
		if (mounthood_param_is_off($custom) && $count < $columns) $columns = $count;

		if (mounthood_param_is_on($slider)) mounthood_enqueue_slider('swiper');

		mounthood_storage_set('sc_services_data', array(
			'id' => $id,
            'style' => $style,
            'type' => $type,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs,
            'readmore' => $readmore
            )
        );

        $alt = basename($image);
        $alt = substr($alt,0,strlen($alt) - 4);

        $output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
						. ' class="sc_services_wrap'
						. ($scheme && !mounthood_param_is_off($scheme) && !mounthood_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(mounthood_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($title_align!='' && $title_align!='none' ? ' title_' . esc_attr($title_align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!mounthood_param_is_off($equalheight) ? ' data-equal-height=".sc_services_item"' : '')
						. (!mounthood_param_is_off($animation) ? ' data-animation="'.esc_attr(mounthood_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(mounthood_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_with_descr') . '">' . trim(mounthood_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(mounthood_strmacros($description)) . '</div>' : '')
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
							? ($style == 'services-5' && !empty($image) 
								? '<div class="sc_service_container sc_align_'.esc_attr($image_align).'">'
									. '<div class="sc_services_image"><img src="'.esc_url($image).'" alt="'.esc_html($alt).'"></div>'
								: '')
								. '<div class="sc_columns columns_wrap'
								. (!empty($margins_service) && mounthood_param_is_off($margins_service) ? ' no_margins' : '') 
								.'">' 
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
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = mounthood_query_add_sort_order($args, $orderby, $order);
			$args = mounthood_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			
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
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= mounthood_show_post_layout($args);
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
			if ($style == 'services-5' && !empty($image))
				$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.mounthood_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		// Add template specific scripts and styles
		do_action('mounthood_action_blog_scripts', $style);
	
		return apply_filters('mounthood_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	mounthood_require_shortcode('trx_services', 'mounthood_sc_services');
}


if ( !function_exists( 'mounthood_sc_services_item' ) ) {
	function mounthood_sc_services_item($atts, $content=null) {
		if (mounthood_in_shortcode_blogger()) return '';
		extract(mounthood_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"subtitle" => "",
			"title_align" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		mounthood_storage_inc_array('sc_services_data', 'counter');

		$id = $id ? $id : (mounthood_storage_get_array('sc_services_data', 'id') ? mounthood_storage_get_array('sc_services_data', 'id') . '_' . mounthood_storage_get_array('sc_services_data', 'counter') : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? mounthood_storage_get_array('sc_services_data', 'readmore') : $readmore;
		$subtitle = $subtitle=='(none)' ? mounthood_storage_get_array('sc_services_data', 'subtitle') : $subtitle;

		$type = mounthood_storage_get_array('sc_services_data', 'type');
		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = mounthood_get_thumb_sizes(array('layout' => mounthood_storage_get_array('sc_services_data', 'style')));
			$image = mounthood_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_subtitle' => $subtitle,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => mounthood_storage_get_array('sc_services_data', 'style'),
			'number' => mounthood_storage_get_array('sc_services_data', 'counter'),
			'columns_count' => mounthood_storage_get_array('sc_services_data', 'columns'),
			'slider' => mounthood_storage_get_array('sc_services_data', 'slider'),
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => mounthood_storage_get_array('sc_services_data', 'css_wh')
		);
		$output = mounthood_show_post_layout($args, $post_data);
		return apply_filters('mounthood_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	mounthood_require_shortcode('trx_services_item', 'mounthood_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('mounthood_services_reg_shortcodes')) {
	//Handler of add_filter('mounthood_action_shortcodes_list',	'mounthood_services_reg_shortcodes');
	function mounthood_services_reg_shortcodes() {
		if (mounthood_storage_isset('shortcodes')) {

			$services_groups = mounthood_get_list_terms(false, 'services_group');
			$services_styles = mounthood_get_list_templates('services');
			$controls 		 = mounthood_get_list_slider_controls();

			mounthood_sc_map_after('trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", 'mounthood'),
					"desc" => wp_kses_data( __("Insert services list in your page (post)", 'mounthood') ),
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
							"title" => esc_html__("Services style", 'mounthood'),
							"desc" => wp_kses_data( __("Select style to display services list", 'mounthood') ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"image" => array(
								"title" => esc_html__("Item's image", 'mounthood'),
								"desc" => wp_kses_data( __("Item's image", 'mounthood') ),
								"dependency" => array(
									'style' => 'services-5'
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
						),
						"image_align" => array(
							"title" => esc_html__("Image alignment", 'mounthood'),
							"desc" => wp_kses_data( __("Alignment of the image", 'mounthood') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => mounthood_get_sc_param('align')
						),
						"type" => array(
							"title" => esc_html__("Icon's type", 'mounthood'),
							"desc" => wp_kses_data( __("Select type of icons: font icon or image", 'mounthood') ),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'mounthood'),
								'images' => esc_html__('Images', 'mounthood')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'mounthood'),
							"desc" => wp_kses_data( __("How many columns use to show services list", 'mounthood') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'mounthood'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'mounthood') ),
							"value" => "",
							"type" => "checklist",
							"options" => mounthood_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'mounthood'),
							"desc" => wp_kses_data( __("Use slider to show services", 'mounthood') ),
							"value" => "no",
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
							"desc" => wp_kses_data( __("Alignment of the services block", 'mounthood') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => mounthood_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'mounthood'),
							"desc" => wp_kses_data( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", 'mounthood') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => mounthood_get_sc_param('yes_no')
						),
						"margins_service" => array(
							"title" => esc_html__("Margins between columns", 'mounthood'),
							"desc" => wp_kses_data( __("Add margins between columns", 'mounthood') ),
							"value" => "yes",
							"type" => "switch",
							"options" => mounthood_get_sc_param('yes_no')
						), 
						"cat" => array(
							"title" => esc_html__("Categories", 'mounthood'),
							"desc" => wp_kses_data( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => mounthood_array_merge(array(0 => esc_html__('- Select category -', 'mounthood')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'mounthood'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'mounthood') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
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
						"readmore" => array(
							"title" => esc_html__("Read more", 'mounthood'),
							"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'mounthood') ),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'mounthood'),
							"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'mounthood') ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'mounthood'),
							"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'mounthood') ),
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
					),
					"children" => array(
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", 'mounthood'),
						"desc" => wp_kses_data( __("Service item", 'mounthood') ),
						"container" => true,
						"params" => array(
							"subtitle" => array(
								"title" => esc_html__("Subtitle", 'mounthood'),
								"desc" => wp_kses_data( __("Item's subtitle only for Services style 2", 'mounthood') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"title" => array(
								"title" => esc_html__("Title", 'mounthood'),
								"desc" => wp_kses_data( __("Item's title", 'mounthood') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'mounthood'),
								"desc" => wp_kses_data( __('Select icon for the item from Fontello icons set',  'mounthood') ),
								"value" => "",
								"type" => "icons",
								"options" => mounthood_get_sc_param('icons')
							),
							"image" => array(
								"title" => esc_html__("Item's image", 'mounthood'),
								"desc" => wp_kses_data( __("Item's image (if icon not selected)", 'mounthood') ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", 'mounthood'),
								"desc" => wp_kses_data( __("Link on service's item page", 'mounthood') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", 'mounthood'),
								"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'mounthood') ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'mounthood'),
								"desc" => wp_kses_data( __("Item's short description", 'mounthood') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => mounthood_get_sc_param('id'),
							"class" => mounthood_get_sc_param('class'),
							"animation" => mounthood_get_sc_param('animation'),
							"css" => mounthood_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('mounthood_services_reg_shortcodes_vc')) {
	//Handler of add_filter('mounthood_action_shortcodes_list_vc',	'mounthood_services_reg_shortcodes_vc');
	function mounthood_services_reg_shortcodes_vc() {

		$services_groups = mounthood_get_list_terms(false, 'services_group');
		$services_styles = mounthood_get_list_templates('services');
		$controls		 = mounthood_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", 'mounthood'),
				"description" => wp_kses_data( __("Insert services list", 'mounthood') ),
				"category" => esc_html__('Content', 'mounthood'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", 'mounthood'),
						"description" => wp_kses_data( __("Select style to display services list", 'mounthood') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", 'mounthood'),
						"description" => wp_kses_data( __("Select type of icons: font icon or image", 'mounthood') ),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Icons', 'mounthood') => 'icons',
							esc_html__('Images', 'mounthood') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "equalheight",
						"heading" => esc_html__("Equal height", 'mounthood'),
						"description" => wp_kses_data( __("Make equal height for all items in the row", 'mounthood') ),
						"value" => array("Equal height" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'mounthood'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'mounthood') ),
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'mounthood'),
						"description" => wp_kses_data( __("Item's image", 'mounthood') ),
						'dependency' => array(
							'element' => 'style',
							'value' => 'services-5'
						),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_align",
						"heading" => esc_html__("Image alignment", 'mounthood'),
						"description" => wp_kses_data( __("Alignment of the image", 'mounthood') ),
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'mounthood'),
						"description" => wp_kses_data( __("Use slider to show services", 'mounthood') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'mounthood'),
						"class" => "",
						"std" => "no",
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
						"description" => wp_kses_data( __("Alignment of the services block", 'mounthood') ),
						"class" => "",
						"value" => array_flip(mounthood_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'mounthood'),
						"description" => wp_kses_data( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", 'mounthood') ),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "margins_service",
						"heading" => esc_html__("Margins between columns", 'mounthood'),
						"description" => wp_kses_data( __("Add margins between columns", 'mounthood') ),
						"std" => "yes",
						"value" => array(esc_html__('Disable margins between columns', 'mounthood') => 'no'),
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
						"description" => wp_kses_data( __("Select category to show services. If empty - select services from any category (group) or from IDs list", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(mounthood_array_merge(array(0 => esc_html__('- Select category -', 'mounthood')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'mounthood'),
						"description" => wp_kses_data( __("How many columns use to show services list", 'mounthood') ),
						"group" => esc_html__('Query', 'mounthood'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'mounthood'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'mounthood') ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'mounthood'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
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
						"heading" => esc_html__("Service's IDs list", 'mounthood'),
						"description" => wp_kses_data( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'mounthood') ),
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
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'mounthood'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'mounthood') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'mounthood'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'mounthood') ),
						"group" => esc_html__('Captions', 'mounthood'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'mounthood'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'mounthood') ),
						"group" => esc_html__('Captions', 'mounthood'),
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
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'mounthood' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'mounthood' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'mounthood' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'mounthood' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", 'mounthood'),
				"description" => wp_kses_data( __("Custom services item - all data pull out from shortcode parameters", 'mounthood') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'mounthood'),
						"description" => wp_kses_data( __("Item's subtitle only for Services style 2", 'mounthood') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'mounthood'),
						"description" => wp_kses_data( __("Item's title", 'mounthood') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'mounthood'),
						"description" => wp_kses_data( __("Select icon for the item from Fontello icons set", 'mounthood') ),
						"admin_label" => true,
						"class" => "",
						"value" => mounthood_get_sc_param('icons'),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'mounthood'),
						"description" => wp_kses_data( __("Item's image (if icon is empty)", 'mounthood') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'mounthood'),
						"description" => wp_kses_data( __("Link on item's page", 'mounthood') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'mounthood'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'mounthood') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					mounthood_get_vc_param('id'),
					mounthood_get_vc_param('class'),
					mounthood_get_vc_param('animation'),
					mounthood_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Services extends MOUNTHOOD_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends MOUNTHOOD_VC_ShortCodeCollection {}

	}
}
?>