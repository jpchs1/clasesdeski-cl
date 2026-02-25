<?php
/**
 * Client phone.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-phone.php.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="tpro-client-phone">
<?php
do_action( 'sptpro_before_testimonial_client_phone' );
echo wp_kses_post( $tpro_phone );
do_action( 'sptpro_after_testimonial_client_phone' );
?>
</div>
