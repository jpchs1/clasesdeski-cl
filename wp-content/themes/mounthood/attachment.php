<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move mounthood_set_post_views to the javascript - counter will work under cache system
	if (mounthood_get_custom_option('use_ajax_views_counter')=='no') {
		mounthood_set_post_views(get_the_ID());
	}

	mounthood_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !mounthood_param_is_off(mounthood_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>