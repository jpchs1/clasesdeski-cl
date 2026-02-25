<?php
/**
 * Location.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/location.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php
	if ( $location_label ) {
		?>
	<label for="tpro_client_location<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $location_label ); ?></label><br>
		<?php
	}
	?>
	<input type="text" name="tpro_client_location" id="tpro_client_location<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $location_required ); ?> placeholder="<?php echo esc_attr( $location['placeholder'] ); ?>" />
</div>
