<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'mounthood_template_no_search_theme_setup' ) ) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_template_no_search_theme_setup', 1 );
	function mounthood_template_no_search_theme_setup() {
		mounthood_add_template(array(
			'layout' => 'no-search',
			'mode'   => 'internal',
			'title'  => esc_html__('No search results found', 'mounthood')
		));
	}
}

// Template output
if ( !function_exists( 'mounthood_template_no_search_output' ) ) {
	function mounthood_template_no_search_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php echo sprintf(esc_html__('Search: %s', 'mounthood'), get_search_query()); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'mounthood' ); ?></p>
				<p><?php echo wp_kses_data( sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'mounthood'), esc_url(home_url('/')), get_bloginfo()) ); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'mounthood'); ?></p>
				<?php if (function_exists('mounthood_sc_search')) mounthood_show_layout(mounthood_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>