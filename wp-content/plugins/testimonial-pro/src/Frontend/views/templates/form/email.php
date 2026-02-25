<?php
/**
 * Email.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/email.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
			<?php
			if ( $email_address_label ) {
				?>
	<label for="tpro_client_email<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $email_address_label ); ?></label><br>
			<?php } ?>
	<input type="email" name="tpro_client_email" id="tpro_client_email<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $email_address_required ); ?> placeholder="<?php echo esc_html( $email_address['placeholder'] ); ?>" />
</div>
