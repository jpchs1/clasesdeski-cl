<?php
/**
 * Phone.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/phone.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php
	if ( $phone_mobile_label ) {
		?>
	<label for="tpro_client_phone<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $phone_mobile_label ); ?></label><br>
	<?php } ?>
	<input type="text" name="tpro_client_phone" id="tpro_client_phone<?php echo esc_attr( $form_id ); ?>"
		<?php echo esc_html( $phone_mobile_required ); ?> placeholder="<?php echo esc_attr( $phone_mobile['placeholder'] ); ?>" />
</div>
