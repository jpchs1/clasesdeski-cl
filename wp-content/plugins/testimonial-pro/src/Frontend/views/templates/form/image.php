<?php
/**
 * Image.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/image.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
	<div class="sp-tpro-form-field">
	<?php
	if ( $featured_image_label ) {
		?>
	<label for="tpro_client_image<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $featured_image_label ); ?></label><br>
	<?php } ?>
	<input type="file" name="tpro_client_image" id="tpro_client_image<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $featured_image_required ); ?> accept="image/jpeg,image/jpg,image/png," />
</div>
