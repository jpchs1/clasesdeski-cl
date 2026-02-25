<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.10
 */


// Socials
if ( snowmountain_is_on(snowmountain_get_theme_option('socials_in_footer')) && ($snowmountain_output = snowmountain_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php snowmountain_show_layout($snowmountain_output); ?>
		</div>
	</div>
	<?php
}
?>