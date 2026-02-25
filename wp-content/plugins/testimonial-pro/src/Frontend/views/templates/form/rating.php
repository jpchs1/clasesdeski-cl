<?php
/**
 * Rating.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/rating.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
	<div class="sp-tpro-form-field">

<?php if ( $rating_label ) { ?>
<label for="tpro_client_rating"><?php echo esc_html( $rating_label ); ?></label><br>
<?php } ?>
<div class="sp-tpro-client-rating">
	<input type="radio" name="tpro_client_rating" id="_tpro_rating_5<?php echo esc_attr( $form_id ); ?>" value="five_star">
	<label for="_tpro_rating_5<?php echo esc_attr( $form_id ); ?>" title="Five Stars"><i class="fa fa-star"></i></label>

	<input type="radio" name="tpro_client_rating" id="_tpro_rating_4<?php echo esc_attr( $form_id ); ?>" value="four_star">
	<label for="_tpro_rating_4<?php echo esc_attr( $form_id ); ?>" title="Four Stars"><i class="fa fa-star"></i></label>

	<input type="radio" name="tpro_client_rating" id="_tpro_rating_3<?php echo esc_attr( $form_id ); ?>" value="three_star">
	<label for="_tpro_rating_3<?php echo esc_attr( $form_id ); ?>" title="Three Stars"><i class="fa fa-star"></i></label>

	<input type="radio" name="tpro_client_rating" id="_tpro_rating_2<?php echo esc_attr( $form_id ); ?>" value="two_star">
	<label for="_tpro_rating_2<?php echo esc_attr( $form_id ); ?>" title="Two Star"><i class="fa fa-star"></i></label>

	<input type="radio" name="tpro_client_rating" id="_tpro_rating_1<?php echo esc_attr( $form_id ); ?>" value="one_star">
	<label for="_tpro_rating_1<?php echo esc_attr( $form_id ); ?>" title="One Star"><i class="fa fa-star"></i></label>
</div><br>
</div>
