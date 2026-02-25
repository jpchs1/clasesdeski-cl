<?php
/**
 * Testimonial cat.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/testimonial-cat.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php if ( $group_label ) { ?>
	<label for="tpro_client_testimonial_cat<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $group_label ); ?></label><br>
		<?php
	}
	if ( ! empty( $groups_list ) ) {
		?>
	<select name="tpro_client_testimonial_cat[]" id="tpro_client_testimonial_cat<?php echo esc_attr( $form_id ); ?>" class="chosen-select"
		data-placeholder="<?php echo esc_attr( $groups['placeholder'] ); ?>" <?php echo wp_kses_post( $groups_multiple_selection ); ?>
		data-depend-id="tpro_client_testimonial_cat">
		<?php
		foreach ( $groups_list as $group_id ) {
			$cat_term = get_term( $group_id );
			echo '<option value="' . wp_kses_post( $cat_term->term_id ) . '">' . wp_kses_post( $cat_term->name ) . '</option>';
		}
		echo '</select>';
	} else {
		$terms = get_terms(
			'testimonial_cat',
			array(
				'hide_empty' => 0,
			)
		);
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			?>
		<select name="tpro_client_testimonial_cat[]" id="tpro_client_testimonial_cat" class="chosen-select"
			data-placeholder="<?php echo esc_attr( $groups['placeholder'] ); ?>"
			<?php echo wp_kses_post( $groups_multiple_selection ); ?> data-depend-id="tpro_client_testimonial_cat">
			<?php
			foreach ( $terms as $cat_term ) {
				echo '<option value="' . wp_kses_post( $cat_term->term_id ) . '">' . wp_kses_post( $cat_term->name ) . '</option>';
			}
			echo '</select>';
		};
	}
	?>
</div>
