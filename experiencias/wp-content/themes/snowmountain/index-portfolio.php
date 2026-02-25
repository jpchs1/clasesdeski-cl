<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

snowmountain_storage_set('blog_archive', true);

// Load scripts for both 'Gallery' and 'Portfolio' layouts!
wp_enqueue_script( 'classie', snowmountain_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
wp_enqueue_script( 'imagesloaded', snowmountain_get_file_url('js/theme.gallery/imagesloaded.min.js'), array(), null, true );
wp_enqueue_script( 'masonry', snowmountain_get_file_url('js/theme.gallery/masonry.min.js'), array(), null, true );
wp_enqueue_script( 'snowmountain-gallery-script', snowmountain_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$snowmountain_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$snowmountain_sticky_out = is_array($snowmountain_stickies) && count($snowmountain_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$snowmountain_cat = snowmountain_get_theme_option('parent_cat');
	$snowmountain_post_type = snowmountain_get_theme_option('post_type');
	$snowmountain_taxonomy = snowmountain_get_post_type_taxonomy($snowmountain_post_type);
	$snowmountain_show_filters = snowmountain_get_theme_option('show_filters');
	$snowmountain_tabs = array();
	if (!snowmountain_is_off($snowmountain_show_filters)) {
		$snowmountain_args = array(
			'type'			=> $snowmountain_post_type,
			'child_of'		=> $snowmountain_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $snowmountain_taxonomy,
			'pad_counts'	=> false
		);
		$snowmountain_portfolio_list = get_terms($snowmountain_args);
		if (is_array($snowmountain_portfolio_list) && count($snowmountain_portfolio_list) > 0) {
			$snowmountain_tabs[$snowmountain_cat] = esc_html__('All', 'snowmountain');
			foreach ($snowmountain_portfolio_list as $snowmountain_term) {
				if (isset($snowmountain_term->term_id)) $snowmountain_tabs[$snowmountain_term->term_id] = $snowmountain_term->name;
			}
		}
	}
	if (count($snowmountain_tabs) > 0) {
		$snowmountain_portfolio_filters_ajax = true;
		$snowmountain_portfolio_filters_active = $snowmountain_cat;
		$snowmountain_portfolio_filters_id = 'portfolio_filters';
		if (!is_customize_preview())
			wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
		?>
		<div class="portfolio_filters snowmountain_tabs snowmountain_tabs_ajax">
			<ul class="portfolio_titles snowmountain_tabs_titles">
				<?php
				foreach ($snowmountain_tabs as $snowmountain_id=>$snowmountain_title) {
					?><li><a href="<?php echo esc_url(snowmountain_get_hash_link(sprintf('#%s_%s_content', $snowmountain_portfolio_filters_id, $snowmountain_id))); ?>" data-tab="<?php echo esc_attr($snowmountain_id); ?>"><?php echo esc_html($snowmountain_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$snowmountain_ppp = snowmountain_get_theme_option('posts_per_page');
			if (snowmountain_is_inherit($snowmountain_ppp)) $snowmountain_ppp = '';
			foreach ($snowmountain_tabs as $snowmountain_id=>$snowmountain_title) {
				$snowmountain_portfolio_need_content = $snowmountain_id==$snowmountain_portfolio_filters_active || !$snowmountain_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $snowmountain_portfolio_filters_id, $snowmountain_id)); ?>"
					class="portfolio_content snowmountain_tabs_content"
					data-blog-template="<?php echo esc_attr(snowmountain_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(snowmountain_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($snowmountain_ppp); ?>"
					data-post-type="<?php echo esc_attr($snowmountain_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($snowmountain_taxonomy); ?>"
					data-cat="<?php echo esc_attr($snowmountain_id); ?>"
					data-parent-cat="<?php echo esc_attr($snowmountain_cat); ?>"
					data-need-content="<?php echo (false===$snowmountain_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($snowmountain_portfolio_need_content) 
						snowmountain_show_portfolio_posts(array(
							'cat' => $snowmountain_id,
							'parent_cat' => $snowmountain_cat,
							'taxonomy' => $snowmountain_taxonomy,
							'post_type' => $snowmountain_post_type,
							'page' => 1,
							'sticky' => $snowmountain_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		snowmountain_show_portfolio_posts(array(
			'cat' => $snowmountain_cat,
			'parent_cat' => $snowmountain_cat,
			'taxonomy' => $snowmountain_taxonomy,
			'post_type' => $snowmountain_post_type,
			'page' => 1,
			'sticky' => $snowmountain_sticky_out
			)
		);
	}

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>