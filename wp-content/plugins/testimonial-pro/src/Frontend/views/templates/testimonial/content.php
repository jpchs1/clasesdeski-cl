<?php
/**
 * Testimonial content.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/content.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="tpro-client-testimonial">
	<?php do_action( 'sptpro_before_testimonial_content' ); ?>
	<?php if ( $show_testimonial_text ) { ?>
	<div class="tpro-testimonial-text"><?php echo wp_kses_post( $review_text ); ?></div>
	<?php } ?>
	<?php echo wp_kses_post( $read_more_link ); ?>
	<?php do_action( 'sptpro_after_testimonial_content' ); ?>
	</div>
<?php
