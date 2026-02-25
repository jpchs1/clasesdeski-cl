<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

$snowmountain_blog_style = explode('_', snowmountain_get_theme_option('blog_style'));
$snowmountain_columns = empty($snowmountain_blog_style[1]) ? 2 : max(2, $snowmountain_blog_style[1]);
$snowmountain_post_format = get_post_format();
$snowmountain_post_format = empty($snowmountain_post_format) ? 'standard' : str_replace('post-format-', '', $snowmountain_post_format);
$snowmountain_animation = snowmountain_get_theme_option('blog_animation');
$snowmountain_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($snowmountain_columns).' post_format_'.esc_attr($snowmountain_post_format) ); ?>
	<?php echo (!snowmountain_is_off($snowmountain_animation) ? ' data-animation="'.esc_attr(snowmountain_get_animation_classes($snowmountain_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($snowmountain_image[1]) && !empty($snowmountain_image[2])) echo intval($snowmountain_image[1]) .'x' . intval($snowmountain_image[2]); ?>"
	data-src="<?php if (!empty($snowmountain_image[0])) echo esc_url($snowmountain_image[0]); ?>"
	>

	<?php
	$snowmountain_image_hover = 'icon';
	if (in_array($snowmountain_image_hover, array('icons', 'zoom'))) $snowmountain_image_hover = 'dots';
	// Featured image
	snowmountain_show_post_featured(array(
		'hover' => $snowmountain_image_hover,
		'thumb_size' => snowmountain_get_thumb_size( strpos(snowmountain_get_theme_option('body_style'), 'full')!==false || $snowmountain_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. snowmountain_show_post_meta(array(
									'categories' => true,
									'date' => true,
									'edit' => false,
									'seo' => false,
									'share' => true,
									'counters' => 'comments',
									'echo' => false
									))
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'snowmountain') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>