<?php
/**
 * Client website.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-website.php.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="tpro-client-website">
<?php do_action( 'sptpro_before_testimonial_client_website' ); ?>
	<a target="<?php echo esc_attr( $website_link_target ); ?>" rel="noopener" href="<?php echo esc_url( $tpro_website ); ?>">
		<?php echo wp_kses_post( $tpro_website ); ?>
	</a>
<?php do_action( 'sptpro_after_testimonial_client_website' ); ?>
</div>
