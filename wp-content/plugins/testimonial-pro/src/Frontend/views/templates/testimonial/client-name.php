<?php
/**
 * Client name.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-name.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<<?php echo esc_attr( $testimonial_client_name_tag ); ?> class="tpro-client-name">
<?php
do_action( 'sptpro_before_testimonial_client_name' );
	echo wp_kses_post( $tpro_name );
do_action( 'sptpro_after_testimonial_client_name' );
?>
</<?php echo esc_attr( $testimonial_client_name_tag ); ?>>
