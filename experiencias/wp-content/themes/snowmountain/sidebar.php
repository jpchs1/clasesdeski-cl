<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

$snowmountain_sidebar_position = snowmountain_get_theme_option('sidebar_position');
if (snowmountain_sidebar_present()) {
	ob_start();
	$snowmountain_sidebar_name = snowmountain_get_theme_option('sidebar_widgets');
	snowmountain_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($snowmountain_sidebar_name) ) {
		dynamic_sidebar($snowmountain_sidebar_name);
	}
	$snowmountain_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($snowmountain_out)) {
		?>
		<div class="sidebar <?php echo esc_attr($snowmountain_sidebar_position); ?> widget_area<?php if (!snowmountain_is_inherit(snowmountain_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(snowmountain_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'snowmountain_action_before_sidebar' );
				snowmountain_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $snowmountain_out));
				do_action( 'snowmountain_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>