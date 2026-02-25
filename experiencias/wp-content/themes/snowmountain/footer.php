<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

						// Widgets area inside page content
						snowmountain_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					snowmountain_create_widgets_area('widgets_below_page');

					$snowmountain_body_style = snowmountain_get_theme_option('body_style');
					if ($snowmountain_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$snowmountain_footer_style = snowmountain_get_theme_option("footer_style");
			if (strpos($snowmountain_footer_style, 'footer-custom-')===0) $snowmountain_footer_style = 'footer-custom';
			get_template_part( "templates/{$snowmountain_footer_style}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (snowmountain_is_on(snowmountain_get_theme_option('debug_mode')) && snowmountain_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(snowmountain_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>