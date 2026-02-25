<?php
/**
 * Testimonial title.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/title.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="tpro-testimonial-title">
	<?php do_action( 'sptpro_before_testimonial_title' ); ?>
	<<?php echo esc_attr( $testimonial_title_tag ); ?> class="sp-tpro-testimonial-title">
		<?php echo wp_kses_post( $testimonial_title_with_limit ); ?>
	</<?php echo esc_attr( $testimonial_title_tag ); ?>>
	<?php do_action( 'sptpro_after_testimonial_title' ); ?>
</div>
