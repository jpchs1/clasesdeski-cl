<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

$snowmountain_post_id    = get_the_ID();
$snowmountain_post_date  = snowmountain_get_date();
$snowmountain_post_title = get_the_title();
$snowmountain_post_link  = get_permalink();
$snowmountain_post_author_id   = get_the_author_meta('ID');
$snowmountain_post_author_name = get_the_author_meta('display_name');
$snowmountain_post_author_url  = get_author_posts_url($snowmountain_post_author_id, '');

$snowmountain_args = get_query_var('snowmountain_args_widgets_posts');
$snowmountain_show_date = isset($snowmountain_args['show_date']) ? (int) $snowmountain_args['show_date'] : 1;
$snowmountain_show_image = isset($snowmountain_args['show_image']) ? (int) $snowmountain_args['show_image'] : 1;
$snowmountain_show_author = isset($snowmountain_args['show_author']) ? (int) $snowmountain_args['show_author'] : 1;
$snowmountain_show_counters = isset($snowmountain_args['show_counters']) ? (int) $snowmountain_args['show_counters'] : 1;
$snowmountain_show_categories = isset($snowmountain_args['show_categories']) ? (int) $snowmountain_args['show_categories'] : 1;

$snowmountain_output = snowmountain_storage_get('snowmountain_output_widgets_posts');

$snowmountain_post_counters_output = '';
if ( $snowmountain_show_counters ) {
	$snowmountain_post_counters_output = '<span class="post_info_item post_info_counters">'
								. snowmountain_get_post_counters('comments')
							. '</span>';
}


$snowmountain_output .= '<article class="post_item with_thumb">';

if ($snowmountain_show_image) {
	$snowmountain_post_thumb = get_the_post_thumbnail($snowmountain_post_id, snowmountain_get_thumb_size('tiny'), array(
		'alt' => the_title_attribute( array( 'echo' => false ) )
	));
	if ($snowmountain_post_thumb) $snowmountain_output .= '<div class="post_thumb">' . ($snowmountain_post_link ? '<a href="' . esc_url($snowmountain_post_link) . '">' : '') . ($snowmountain_post_thumb) . ($snowmountain_post_link ? '</a>' : '') . '</div>';
}

$snowmountain_output .= '<div class="post_content">'
			. ($snowmountain_show_categories 
					? '<div class="post_categories">'
						. snowmountain_get_post_categories()
						. $snowmountain_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($snowmountain_post_link ? '<a href="' . esc_url($snowmountain_post_link) . '">' : '') . ($snowmountain_post_title) . ($snowmountain_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('snowmountain_filter_get_post_info', 
								'<div class="post_info">'
									. ($snowmountain_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($snowmountain_post_link ? '<a href="' . esc_url($snowmountain_post_link) . '" class="post_info_date">' : '') 
											. esc_html($snowmountain_post_date) 
											. ($snowmountain_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($snowmountain_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'snowmountain') . ' ' 
											. ($snowmountain_post_link ? '<a href="' . esc_url($snowmountain_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($snowmountain_post_author_name) 
											. ($snowmountain_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$snowmountain_show_categories && $snowmountain_post_counters_output
										? $snowmountain_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
snowmountain_storage_set('snowmountain_output_widgets_posts', $snowmountain_output);
?>