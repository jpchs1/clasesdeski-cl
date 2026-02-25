<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.10
 */

// Footer menu
$snowmountain_menu_footer = snowmountain_get_nav_menu('menu_footer');
if (!empty($snowmountain_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php snowmountain_show_layout($snowmountain_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>