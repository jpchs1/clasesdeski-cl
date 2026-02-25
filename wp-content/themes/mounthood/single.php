<?php
/**
 * Single post
 */
get_header(); 

$single_style = mounthood_storage_get('single_style');
if (empty($single_style)) $single_style = mounthood_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	mounthood_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !mounthood_param_is_off(mounthood_get_custom_option('show_sidebar_main')),
			'content' => mounthood_get_template_property($single_style, 'need_content'),
			'terms_list' => mounthood_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>