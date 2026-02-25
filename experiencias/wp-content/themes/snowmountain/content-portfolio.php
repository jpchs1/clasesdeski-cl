<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($snowmountain_columns).' post_format_'.esc_attr($snowmountain_post_format) ); ?>
	<?php echo (!snowmountain_is_off($snowmountain_animation) ? ' data-animation="'.esc_attr(snowmountain_get_animation_classes($snowmountain_animation)).'"' : ''); ?>
	>

	<?php
	$snowmountain_image_hover = snowmountain_get_theme_option('image_hover');
	// Featured image
	snowmountain_show_post_featured(array(
		'thumb_size' => snowmountain_get_thumb_size(strpos(snowmountain_get_theme_option('body_style'), 'full')!==false || $snowmountain_columns < 3 ? 'masonry-big' : 'masonry'),
		'show_no_image' => true,
		'class' => $snowmountain_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $snowmountain_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>