<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

// Page (category, tag, archive, author) title

if ( snowmountain_need_page_title() ) {
	snowmountain_sc_layouts_showed('title', true);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title">
						<?php

						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$snowmountain_blog_title = snowmountain_get_blog_title();
							$snowmountain_blog_title_text = $snowmountain_blog_title_class = $snowmountain_blog_title_link = $snowmountain_blog_title_link_text = '';
							if (is_array($snowmountain_blog_title)) {
								$snowmountain_blog_title_text = $snowmountain_blog_title['text'];
								$snowmountain_blog_title_class = !empty($snowmountain_blog_title['class']) ? ' '.$snowmountain_blog_title['class'] : '';
								$snowmountain_blog_title_link = !empty($snowmountain_blog_title['link']) ? $snowmountain_blog_title['link'] : '';
								$snowmountain_blog_title_link_text = !empty($snowmountain_blog_title['link_text']) ? $snowmountain_blog_title['link_text'] : '';
							} else
								$snowmountain_blog_title_text = $snowmountain_blog_title;
							?>
							<h1 class="sc_layouts_title_caption<?php echo esc_attr($snowmountain_blog_title_class); ?>"><?php
								$snowmountain_top_icon = snowmountain_get_category_icon();
								if (!empty($snowmountain_top_icon)) {
									$snowmountain_attr = snowmountain_getimagesize($snowmountain_top_icon);
									?><img src="<?php echo esc_url($snowmountain_top_icon); ?>" alt="<?php esc_attr__('Image', 'snowmountain')?>" <?php if (!empty($snowmountain_attr[3])) snowmountain_show_layout($snowmountain_attr[3]);?>><?php
								}
								echo wp_kses_post($snowmountain_blog_title_text);
							?></h1>
							<?php
							if (!empty($snowmountain_blog_title_link) && !empty($snowmountain_blog_title_link_text)) {
								?><a href="<?php echo esc_url($snowmountain_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($snowmountain_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'snowmountain_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>