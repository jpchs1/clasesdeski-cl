<?php
/**
 * Client location.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-location.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="tpro-client-location">
<?php
do_action( 'sptpro_before_testimonial_client_location' );
echo esc_html( $tpro_location );
do_action( 'sptpro_after_testimonial_client_location' );
?>
</div>
