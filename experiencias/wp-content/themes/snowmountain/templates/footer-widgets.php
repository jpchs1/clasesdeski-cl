<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.10
 */

// Footer sidebar
$snowmountain_footer_name = snowmountain_get_theme_option('footer_widgets');
$snowmountain_footer_present = !snowmountain_is_off($snowmountain_footer_name) && is_active_sidebar($snowmountain_footer_name);
if ($snowmountain_footer_present) { 
	snowmountain_storage_set('current_sidebar', 'footer');
	$snowmountain_footer_wide = snowmountain_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($snowmountain_footer_name) ) {
		dynamic_sidebar($snowmountain_footer_name);
	}
	$snowmountain_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($snowmountain_out)) {
		$snowmountain_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $snowmountain_out);
		$snowmountain_need_columns = true;
		if ($snowmountain_need_columns) {
			$snowmountain_columns = max(0, (int) snowmountain_get_theme_option('footer_columns'));
			if ($snowmountain_columns == 0) $snowmountain_columns = min(4, max(1, substr_count($snowmountain_out, '<aside ')));
			if ($snowmountain_columns > 1)
				$snowmountain_out = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($snowmountain_columns).' widget ', $snowmountain_out);
			else
				$snowmountain_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($snowmountain_footer_wide) ? ' footer_fullwidth' : ''; ?>">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$snowmountain_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($snowmountain_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'snowmountain_action_before_sidebar' );
				snowmountain_show_layout($snowmountain_out);
				do_action( 'snowmountain_action_after_sidebar' );
				if ($snowmountain_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$snowmountain_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>