<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

$snowmountain_blog_style = explode('_', snowmountain_get_theme_option('blog_style'));
$snowmountain_columns = empty($snowmountain_blog_style[1]) ? 2 : max(2, $snowmountain_blog_style[1]);
$snowmountain_expanded = !snowmountain_sidebar_present() && snowmountain_is_on(snowmountain_get_theme_option('expand_content'));
$snowmountain_post_format = get_post_format();
$snowmountain_post_format = empty($snowmountain_post_format) ? 'standard' : str_replace('post-format-', '', $snowmountain_post_format);
$snowmountain_animation = snowmountain_get_theme_option('blog_animation');

?><div class="<?php snowmountain_show_layout($snowmountain_blog_style[0] == 'classic' ? 'column' : 'masonry_item masonry_item'); ?>-1_<?php echo esc_attr($snowmountain_columns); ?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($snowmountain_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($snowmountain_columns)
					. ' post_layout_'.esc_attr($snowmountain_blog_style[0]) 
					. ' post_layout_'.esc_attr($snowmountain_blog_style[0]).'_'.esc_attr($snowmountain_columns)
					); ?>
	<?php echo (!snowmountain_is_off($snowmountain_animation) ? ' data-animation="'.esc_attr(snowmountain_get_animation_classes($snowmountain_animation)).'"' : ''); ?>
	>

	<?php

	// Featured image
	snowmountain_show_post_featured( array( 'thumb_size' => snowmountain_get_thumb_size($snowmountain_blog_style[0] == 'classic'
													? (strpos(snowmountain_get_theme_option('body_style'), 'full')!==false 
															? ( $snowmountain_columns > 2 ? 'big' : 'huge' )
															: (	$snowmountain_columns > 2
																? ($snowmountain_expanded ? 'med' : 'small')
																: ($snowmountain_expanded ? 'big' : 'med')
																)
														)
													: (strpos(snowmountain_get_theme_option('body_style'), 'full')!==false 
															? ( $snowmountain_columns > 2 ? 'masonry-big' : 'full' )
															: (	$snowmountain_columns <= 2 && $snowmountain_expanded ? 'masonry-big' : 'masonry')
														)
								) ) );

	if ( !in_array($snowmountain_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php 
			do_action('snowmountain_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('snowmountain_action_before_post_meta'); 

			// Post meta
            snowmountain_show_post_meta(array(
                    'categories' => false,
                    'date' => true,
                    'edit' => false,
                    'seo' => false,
                    'share' => false,
                    'counters' => 'comments'	//comments,likes,views - comma separated in any combination
                )
            );
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$snowmountain_show_learn_more = false;
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($snowmountain_post_format, array('link', 'aside', 'status', 'quote'))) {
				the_content();
			} else if (substr(get_the_content(), 0, 1)!='[') {
				the_excerpt();
			}
			?>
		</div>
		<?php
		// Post meta
		if (in_array($snowmountain_post_format, array('link', 'aside', 'status', 'quote'))) {
			snowmountain_show_post_meta(array(
				'share' => false,
				'counters' => 'comments'
				)
			);
		}
		// More button
		if ( $snowmountain_show_learn_more ) {
			?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'snowmountain'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>