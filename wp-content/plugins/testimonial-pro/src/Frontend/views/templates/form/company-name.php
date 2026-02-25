<?php
/**
 * Company name
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/company_name.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php if ( $company_name_label ) { ?>
	<label for="tpro_client_company_name<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $company_name_label ); ?></label><br>
	<?php } ?>
	<input type="text" name="tpro_client_company_name" id="tpro_client_company_name<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $company_name_required ); ?> placeholder="<?php echo esc_html( $company_name['placeholder'] ); ?> " />
</div>
