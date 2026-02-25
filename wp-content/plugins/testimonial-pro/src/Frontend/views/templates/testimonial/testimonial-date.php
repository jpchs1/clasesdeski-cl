<?php
/**
 * Testimonial date.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/testimonial-date.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="tpro-testimonial-date">
	<?php
	do_action( 'sptpro_before_testimonial_date' );
	echo get_the_date( $testimonial_client_date_format );
	do_action( 'sptpro_after_testimonial_date' );
	?>
</div>
