<?php
/**
 * After Testimonial.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/after-testimonial.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
</div> <!--  sp-testimonial-pro -->
<?php
// Modal Start.
if ( $testimonial_read_more && 'popup' === $testimonial_read_more_link_action ) {
	include self::sptp_locate_template( 'popup.php' );
}// Modal End.
?>
</div>  <!-- sp-testimonial-pro-item. -->
