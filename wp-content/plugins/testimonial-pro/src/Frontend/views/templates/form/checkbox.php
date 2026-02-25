<?php
/**
 * Website.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/checkbox.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field tpro-checkbox-field">
<input type="checkbox" name="tpro_client_checkbox" id="tpro_client_checkbox<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $checkbox_required ); ?> />
	<?php
	if ( $checkbox_label ) {
		?>
	<label for="tpro_client_checkbox<?php echo esc_attr( $form_id ); ?>"><?php echo wp_kses_post( $checkbox_label ); ?></label>
	<?php } ?>
</div>
