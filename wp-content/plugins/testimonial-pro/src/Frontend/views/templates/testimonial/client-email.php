<?php
/**
 * Client email.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-email.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>

<div class="tpro-client-email">
<?php
do_action( 'sptpro_before_testimonial_email' );
echo esc_html( $tpro_email );
do_action( 'sptpro_after_testimonial_email' );
?>
</div>
