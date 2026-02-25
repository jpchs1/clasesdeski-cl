<?php
/**
 * Submit btn.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/submit-btn.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-submit-button">
			<?php
			echo wp_nonce_field( 'testimonial_form', 'testimonial_form_nonce', true, false );
			if ( 'v3' === $captcha_version ) {
				?>
	<input type="hidden" id="token" name="token">
					<?php } ?>
	<input type="submit" value="<?php echo esc_html( $submit_btn['label'] ); ?>" id="submit" name="submit" />
	<input type="hidden" name="action" value="testimonial_form<?php echo esc_attr( $form_id ); ?>" />
</div>
