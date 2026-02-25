<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

$snowmountain_link = get_permalink();
$snowmountain_post_format = get_post_format();
$snowmountain_post_format = empty($snowmountain_post_format) ? 'standard' : str_replace('post-format-', '', $snowmountain_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($snowmountain_post_format) ); ?>><?php
	snowmountain_show_post_featured(array(
		'thumb_size' => snowmountain_get_thumb_size( 'big' ),
		'show_no_image' => false,
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">' . snowmountain_get_post_categories('') . '</div>'
							. '<h6 class="post_title entry-title"><a href="' . esc_url($snowmountain_link) . '">' . wp_kses_post( get_the_title() ) . '</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="' . esc_url($snowmountain_link) . '">' . snowmountain_get_date() . '</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>