<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WPBakery Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$snowmountain_content = '';
$snowmountain_blog_archive_mask = '%%CONTENT%%';
$snowmountain_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $snowmountain_blog_archive_mask);
if ( have_posts() ) {
	the_post(); 
	if (($snowmountain_content = apply_filters('the_content', get_the_content())) != '') {
		if (($snowmountain_pos = strpos($snowmountain_content, $snowmountain_blog_archive_mask)) !== false) {
			$snowmountain_content = preg_replace('/(\<p\>\s*)?'.$snowmountain_blog_archive_mask.'(\s*\<\/p\>)/i', $snowmountain_blog_archive_subst, $snowmountain_content);
		} else
			$snowmountain_content .= $snowmountain_blog_archive_subst;
		$snowmountain_content = explode($snowmountain_blog_archive_mask, $snowmountain_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) snowmountain_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$snowmountain_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$snowmountain_args = snowmountain_query_add_posts_and_cats($snowmountain_args, '', snowmountain_get_theme_option('post_type'), snowmountain_get_theme_option('parent_cat'));
$snowmountain_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($snowmountain_page_number > 1) {
	$snowmountain_args['paged'] = $snowmountain_page_number;
	$snowmountain_args['ignore_sticky_posts'] = true;
}
$snowmountain_ppp = snowmountain_get_theme_option('posts_per_page');
if ((int) $snowmountain_ppp != 0)
	$snowmountain_args['posts_per_page'] = (int) $snowmountain_ppp;
// Make a new query
query_posts( $snowmountain_args );
// Set a new query as main WP Query
$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];

// Set query vars in the new query!
if (is_array($snowmountain_content) && count($snowmountain_content) == 2) {
	set_query_var('blog_archive_start', $snowmountain_content[0]);
	set_query_var('blog_archive_end', $snowmountain_content[1]);
}

get_template_part('index');
?>