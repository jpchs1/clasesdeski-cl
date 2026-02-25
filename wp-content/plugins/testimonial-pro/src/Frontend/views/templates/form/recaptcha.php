<?php
/**
 * Recaptcha.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/recaptcha.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php if ( $recaptcha_label ) { ?>
	<label for="tpro_recaptcha"><?php echo esc_html( $recaptcha_label ); ?></label><br>
	<?php } ?>
	<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $setting_options['captcha_site_key'] ); ?>"></div>
	<?php
	if ( ! empty( $captcha_error_msg ) ) {
		?>
	<span class="sp-tpro-form-error-msg"><?php echo esc_html( $captcha_error_msg ); ?></span>
	<?php } ?>
</div>
