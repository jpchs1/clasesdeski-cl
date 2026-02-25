<?php
/**
 * The template for homepage posts with "Excerpt" style
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

snowmountain_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	?><div class="posts_container"><?php
	
	$snowmountain_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$snowmountain_sticky_out = is_array($snowmountain_stickies) && count($snowmountain_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($snowmountain_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	while ( have_posts() ) { the_post(); 
		if ($snowmountain_sticky_out && !is_sticky()) {
			$snowmountain_sticky_out = false;
			?></div><?php
		}
		get_template_part( 'content', $snowmountain_sticky_out && is_sticky() ? 'sticky' : 'excerpt' );
	}
	if ($snowmountain_sticky_out) {
		$snowmountain_sticky_out = false;
		?></div><?php
	}
	
	?></div><?php

	snowmountain_show_pagination();

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>