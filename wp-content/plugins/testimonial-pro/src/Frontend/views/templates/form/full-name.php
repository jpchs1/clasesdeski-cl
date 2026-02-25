<?php
/**
 * Full name.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/full-name.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php if ( $full_name_label ) { ?>
	<label for="tpro_client_name<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $full_name_label ); ?></label><br>
	<?php } ?>
	<input type="text" id="tpro_client_name<?php echo esc_attr( $form_id ); ?>" name="tpro_client_name" <?php echo esc_html( $full_name_required ); ?> placeholder="<?php echo esc_html( $full_name['placeholder'] ); ?>" />
</div>
