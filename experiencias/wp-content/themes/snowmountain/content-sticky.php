<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

$snowmountain_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$snowmountain_post_format = get_post_format();
$snowmountain_post_format = empty($snowmountain_post_format) ? 'standard' : str_replace('post-format-', '', $snowmountain_post_format);
$snowmountain_animation = snowmountain_get_theme_option('blog_animation');
$snowmountain_full_content = snowmountain_get_theme_option('blog_content') != 'excerpt' || in_array($snowmountain_post_format, array('link', 'aside', 'status', 'quote'));

?><div class="column-1_<?php echo esc_attr($snowmountain_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($snowmountain_post_format) ); ?>
	<?php echo (!snowmountain_is_off($snowmountain_animation) ? ' data-animation="'.esc_attr(snowmountain_get_animation_classes($snowmountain_animation)).'"' : ''); ?>
	>

	<?php
	// Featured image
	snowmountain_show_post_featured(array(
		'thumb_size' => snowmountain_get_thumb_size($snowmountain_columns==1 ? 'big' : ($snowmountain_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($snowmountain_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
        <div class="post_excerpt_content_wrap">
            <div class="post_excerpt_content">
                <?php
                // Title and post meta
                if (get_the_title() != '') {
                    ?>
                    <div class="post_header entry-header">
                        <?php
                        do_action('snowmountain_action_before_post_title');

                        // Post title
                        the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );


                        ?>
                    </div><!-- .post_header --><?php
                }

                // Post content
                ?><div class="post_content entry-content"><?php
                    if ($snowmountain_full_content) {
                        // Post content area
                        ?><div class="post_content_inner"><?php
                        the_content( '' );
                        ?></div><?php
                        // Inner pages
                        wp_link_pages( array(
                            'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'snowmountain' ) . '</span>',
                            'after'       => '</div>',
                            'link_before' => '<span>',
                            'link_after'  => '</span>',
                            'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'snowmountain' ) . ' </span>%',
                            'separator'   => '<span class="screen-reader-text">, </span>',
                        ) );

                    } else {

                        $snowmountain_show_learn_more = !in_array($snowmountain_post_format, array('link', 'aside', 'status', 'audio', 'quote'));

                        // Post content area
                        ?><div class="post_content_inner"><?php
                        if (has_excerpt()) {
                            the_excerpt();
                        } else if (strpos(get_the_content('!--more'), '!--more')!==false) {
                            the_content( '' );
                        } else if (in_array($snowmountain_post_format, array('link', 'aside', 'status', 'quote'))) {
                            the_content();
                        } else if (substr(get_the_content(), 0, 1)!='[') {
                            the_excerpt();
                        }
                        ?></div><?php
                        // More button
                        if ( $snowmountain_show_learn_more ) {
                            ?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'snowmountain'); ?></a></p><?php
                        }

                    }
                    ?></div><!-- .entry-content --><?php
                ?>
            </div>
            <?php

            do_action('snowmountain_action_before_post_meta');

            // Post meta
            snowmountain_show_post_meta(array(
                    'categories' => true,
                    'date' => true,
                    'edit' => false,
                    'seo' => false,
                    'share' => false,
                    'counters' => 'comments'	//comments,likes,views - comma separated in any combination
                )
            );
            ?>
        </div>
		<?php
	}
	?>
</article></div>