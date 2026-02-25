<?php
// Get template args
extract(mounthood_template_get_args('reviews-block'));

$reviews_markup = '';
if (($avg_author > 0 || $avg_users > 0) && mounthood_param_is_on(mounthood_get_custom_option('show_reviews'))) { 
	$reviews_first_author = mounthood_get_theme_option('reviews_first')=='author';
	$reviews_second_hide = mounthood_get_theme_option('reviews_second')=='hide';
	$use_tabs = !$reviews_second_hide;
	if ($use_tabs) wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
	$max_level = max(5, (int) mounthood_get_custom_option('reviews_max_level'));
	$allow_user_marks = (!$reviews_first_author || !$reviews_second_hide) && (!isset($_COOKIE['mounthood_votes']) || mounthood_strpos($_COOKIE['mounthood_votes'], ','.($post_data['post_id']).',')===false) && (mounthood_get_theme_option('reviews_can_vote')=='all' || is_user_logged_in());
	$reviews_markup = '<div class="reviews_block'.($use_tabs ? ' sc_tabs sc_tabs_style_2' : '').'">';
	$output = $marks = $users = '';
	if ($use_tabs) {
		$author_tab = '<li class="sc_tabs_title"><a href="#author_marks" class="theme_button">'.esc_html__('Author', 'mounthood').'</a></li>';
		$users_tab = '<li class="sc_tabs_title"><a href="#users_marks" class="theme_button">'.esc_html__('Users', 'mounthood').'</a></li>';
		$output .= '<ul class="sc_tabs_titles">' . ($reviews_first_author ? ($author_tab) . ($users_tab) : ($users_tab) . ($author_tab)) . '</ul>';
	}
	// Criterias list
	$field = array(
		"options" => mounthood_get_theme_option('reviews_criterias')
	);
	if (!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy']]->terms)) {
		foreach ($post_data['post_terms'][$post_data['post_taxonomy']]->terms as $cat) {
			$id = (int) $cat->term_id;
			$prop = mounthood_taxonomy_get_inherited_property($post_data['post_taxonomy'], $id, 'reviews_criterias');
			if (!empty($prop) && !mounthood_is_inherit_option($prop)) {
				$field['options'] = $prop;
				break;
			}
		}
	}
	// Author marks
	if ($reviews_first_author || !$reviews_second_hide) {
		$field["id"] = "reviews_marks_author";
		$field["descr"] = strip_tags($post_data['post_excerpt']);
		$field["accept"] = false;
		$marks = mounthood_reviews_marks_to_display(mounthood_reviews_marks_prepare(mounthood_get_custom_option('reviews_marks'), count($field['options'])));
		$output .= '<div id="author_marks" class="sc_tabs_content">' . trim(mounthood_reviews_get_markup($field, $marks, false, false, $reviews_first_author)) . '</div>';
	}
	// Users marks
	if (!$reviews_first_author || !$reviews_second_hide) {
		$marks = mounthood_reviews_marks_to_display(mounthood_reviews_marks_prepare(get_post_meta($post_data['post_id'], mounthood_storage_get('options_prefix').'_reviews_marks2', true), count($field['options'])));
		$users = max(0, get_post_meta($post_data['post_id'], mounthood_storage_get('options_prefix').'_reviews_users', true));
		$field["id"] = "reviews_marks_users";
		$field["descr"] = wp_kses_data( sprintf(__("Summary rating from <b>%s</b> user's marks.", 'mounthood'), $users) 
									. ' ' 
                                    . ( !isset($_COOKIE['mounthood_votes']) || mounthood_strpos($_COOKIE['mounthood_votes'], ','.($post_data['post_id']).',')===false
											? __('You can set own marks for this article - just click on stars above and press "Accept".', 'mounthood')
                                            : __('Thanks for your vote!', 'mounthood')
                                      ) );
		$field["accept"] = $allow_user_marks;
		$output .= '<div id="users_marks" class="sc_tabs_content"'.(!$output ? ' style="display: block;"' : '') . '>' . trim(mounthood_reviews_get_markup($field, $marks, $allow_user_marks, false, !$reviews_first_author)) . '</div>';
	}
	$reviews_markup .= $output . '</div>';
	if ($allow_user_marks) {
		wp_enqueue_script('jquery-ui-draggable', false, array('jquery', 'jquery-ui-core'), null, true);
		mounthood_storage_set_array('js_vars', 'reviews_allow_user_marks', $allow_user_marks);
		mounthood_storage_set_array('js_vars', 'reviews_max_level', $max_level);
		mounthood_storage_set_array('js_vars', 'reviews_levels', mounthood_get_theme_option('reviews_criterias_levels'));
		mounthood_storage_set_array('js_vars', 'reviews_vote', isset($_COOKIE['mounthood_votes']) ? $_COOKIE['mounthood_votes'] : '');
		mounthood_storage_set_array('js_vars', 'reviews_marks', explode(',', $marks));
		mounthood_storage_set_array('js_vars', 'reviews_users', max(0, $users));
		mounthood_storage_set_array('js_vars', 'post_id', $post_data['post_id']);
	}
}
mounthood_storage_set('reviews_markup', $reviews_markup);
?>