<?php
/**
 * Website.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/website.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php
	if ( $website_label ) {
		?>
	<label for="tpro_client_website<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $website_label ); ?></label><br>
	<?php } ?>
	<input type="text" name="tpro_client_website" id="tpro_client_website<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $website_required ); ?> placeholder="<?php echo esc_attr( $website['placeholder'] ); ?>" />
</div>
