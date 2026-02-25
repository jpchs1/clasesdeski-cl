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
$snowmountain_columns = empty($snowmountain_blog_style[1]) ? 1 : max(1, $snowmountain_blog_style[1]);
$snowmountain_expanded = !snowmountain_sidebar_present() && snowmountain_is_on(snowmountain_get_theme_option('expand_content'));
$snowmountain_post_format = get_post_format();
$snowmountain_post_format = empty($snowmountain_post_format) ? 'standard' : str_replace('post-format-', '', $snowmountain_post_format);
$snowmountain_animation = snowmountain_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($snowmountain_columns).' post_format_'.esc_attr($snowmountain_post_format) ); ?>
	<?php echo (!snowmountain_is_off($snowmountain_animation) ? ' data-animation="'.esc_attr(snowmountain_get_animation_classes($snowmountain_animation)).'"' : ''); ?>
	>

	<?php
	// Add anchor
	if ($snowmountain_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.the_title_attribute( array( 'echo' => false ) ).'"]');
	}

	// Featured image
	snowmountain_show_post_featured( array(
											'class' => $snowmountain_columns == 1 ? 'trx-stretch-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => snowmountain_get_thumb_size(
																	strpos(snowmountain_get_theme_option('body_style'), 'full')!==false
																		? ( $snowmountain_columns > 1 ? 'huge' : 'original' )
																		: (	$snowmountain_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('snowmountain_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			
			do_action('snowmountain_action_before_post_meta'); 

			// Post meta
			$snowmountain_post_meta = snowmountain_show_post_meta(array(
                    'categories' => false,
                    'date' => true,
                    'edit' => false,
                    'seo' => false,
                    'share' => false,
                    'counters' => 'comments'	//comments,likes,views - comma separated in any combination
                )
            );
			snowmountain_show_layout($snowmountain_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$snowmountain_show_learn_more = !in_array($snowmountain_post_format, array('link', 'aside', 'status', 'quote'));
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
				snowmountain_show_layout($snowmountain_post_meta);
			}
			// More button
			if ( $snowmountain_show_learn_more ) {
				?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'snowmountain'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>