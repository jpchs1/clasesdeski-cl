<?php
/**
 * Designation.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/position.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php
	if ( $identity_position_label ) {
		?>
	<label for="tpro_client_designation<?php echo esc_attr( $form_id ); ?>"> <?php echo esc_html( $identity_position_label ); ?></label><br>
	<?php } ?>
	<input type="text" name="tpro_client_designation" id="tpro_client_designation<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $identity_position_required ); ?> placeholder="<?php echo esc_html( $identity_position['placeholder'] ); ?>" />
</div>
