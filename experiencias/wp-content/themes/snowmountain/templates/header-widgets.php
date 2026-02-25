<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

// Header sidebar
$snowmountain_header_name = snowmountain_get_theme_option('header_widgets');
$snowmountain_header_present = !snowmountain_is_off($snowmountain_header_name) && is_active_sidebar($snowmountain_header_name);
if ($snowmountain_header_present) { 
	snowmountain_storage_set('current_sidebar', 'header');
	$snowmountain_header_wide = snowmountain_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($snowmountain_header_name) ) {
		dynamic_sidebar($snowmountain_header_name);
	}
	$snowmountain_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($snowmountain_widgets_output)) {
		$snowmountain_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $snowmountain_widgets_output);
		$snowmountain_need_columns = strpos($snowmountain_widgets_output, 'columns_wrap')===false;
		if ($snowmountain_need_columns) {
			$snowmountain_columns = max(0, (int) snowmountain_get_theme_option('header_columns'));
			if ($snowmountain_columns == 0) $snowmountain_columns = min(6, max(1, substr_count($snowmountain_widgets_output, '<aside ')));
			if ($snowmountain_columns > 1)
				$snowmountain_widgets_output = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($snowmountain_columns).' widget ', $snowmountain_widgets_output);
			else
				$snowmountain_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($snowmountain_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$snowmountain_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($snowmountain_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'snowmountain_action_before_sidebar' );
				snowmountain_show_layout($snowmountain_widgets_output);
				do_action( 'snowmountain_action_after_sidebar' );
				if ($snowmountain_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$snowmountain_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>