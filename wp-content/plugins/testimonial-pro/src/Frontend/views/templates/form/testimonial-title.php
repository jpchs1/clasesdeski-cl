<?php
/**
 * Testimonial title
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/testimonial-title.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php
	if ( $testimonial_title_label ) {
		?>
		<label for="tpro_testimonial_title<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $testimonial_title_label ); ?> </label><br>
	<?php } ?>
			<input type="text" name="tpro_testimonial_title" id="tpro_testimonial_title<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $testimonial_title_required ); ?> placeholder="<?php echo esc_html( $testimonial_title['placeholder'] ); ?>" />
</div>
