<?php
/**
 * Testimonial content.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/testimonial-content.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php if ( $testimonial_label ) { ?>
	<label for="tpro_client_testimonial<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $testimonial_label ); ?></label><br>
	<?php } ?>
	<textarea rows="7" type="text" name="tpro_client_testimonial" id="tpro_client_testimonial<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $testimonial_required ); ?> placeholder="<?php echo esc_html( $testimonial['placeholder'] ); ?>"></textarea>
</div>
