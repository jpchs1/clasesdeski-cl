<?php
/**
 * Member extra field
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/extra-fields.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

$testimonial_extra_fields = isset( $testimonial_data['testimonial_extra_fields'] ) && ! empty( $testimonial_data['testimonial_extra_fields'] ) ? $testimonial_data['testimonial_extra_fields'] : '';
if ( is_array( $testimonial_extra_fields ) && ! empty( $testimonial_extra_fields ) && $show_client_addition_info ) {
	?>
<div class="sptp-testimonial-extra-fields">
	<?php do_action( 'sptp_testimonial_before_testimonial_extra_fields' ); ?>
	<?php

	foreach ( $testimonial_data['testimonial_extra_fields'] as $extra_field ) {
			$extra_field_type   = ( isset( $extra_field['testimonial_extra_fields_type'] ) && ! empty( $extra_field['testimonial_extra_fields_type'] ) ) ? $extra_field['testimonial_extra_fields_type'] : '';
			$extra_fields_icon  = ( isset( $extra_field['testimonial_extra_fields_icon'] ) && ! empty( $extra_field['testimonial_extra_fields_icon'] ) ) ? $extra_field['testimonial_extra_fields_icon'] : '';
			$extra_fields_value = ( isset( $extra_field['testimonial_extra_fields_value'] ) && ! empty( $extra_field['testimonial_extra_fields_value'] ) ) ? $extra_field['testimonial_extra_fields_value'] : '';

		?>
		<?php if ( 'text' === $extra_field_type ) { ?>
		<div class="sptp-testimonial-extra-info sptp-testimonial-extra-info-text">
			<?php if ( ! empty( $extra_fields_icon ) ) { ?>
				<span class="<?php echo esc_html( $extra_fields_icon ); ?>"></span>
			<?php } ?>
			<?php echo esc_html( $extra_fields_value ); ?>
		</div>
		<?php } elseif ( 'number' === $extra_field_type ) { ?>
		<div class="sptp-testimonial-extra-info sptp-testimonial-extra-info-number">
			<?php if ( ! empty( $extra_fields_icon ) ) { ?>
				<span class="<?php echo esc_html( $extra_fields_icon ); ?>"></span>
			<?php } ?>
			<a href="<?php echo esc_attr( $extra_fields_value ); ?>" rel="nofollow">
				<span><?php echo esc_html( $extra_fields_value ); ?></span>
			</a>
		</div>
		<?php } elseif ( 'email' === $extra_field_type ) { ?>
		<div class="sptp-testimonial-extra-info sptp-testimonial-extra-info-email">
			<?php if ( ! empty( $extra_fields_icon ) ) { ?>
				<span class="<?php echo esc_html( $extra_fields_icon ); ?>"></span>
			<?php } ?>
			<a href="<?php echo 'mailto:' . esc_attr( $extra_fields_value ); ?>" rel="nofollow">
				<span><?php echo esc_html( $extra_fields_value ); ?></span>
			</a>
		</div>
			<?php
		} elseif ( 'link' === $extra_field_type ) {
			if ( preg_match( '#^https?://#i', $extra_fields_value ) ) {
				$sptp_extra_link = $extra_fields_value;
			} else {
				$sptp_extra_link = 'https://' . $extra_fields_value;
			}
			?>
		<div class="sptp-testimonial-extra-info sptp-testimonial-extra-info-link">
			<?php if ( ! empty( $extra_fields_icon ) ) { ?>
				<span class="<?php echo esc_html( $extra_fields_icon ); ?>"></span>
			<?php } ?>
			<a href="<?php echo esc_url( $sptp_extra_link ); ?>" target="_blank">
				<span><?php echo esc_html( $sptp_extra_link ); ?></span>
			</a>
		</div>
		<?php } elseif ( 'date' === $extra_field_type ) { ?>
		<div class="sptp-testimonial-extra-info sptp-testimonial-extra-info-date">
			<?php if ( ! empty( $extra_fields_icon ) ) { ?>
				<span class="<?php echo esc_html( $extra_fields_icon ); ?>"></span>
			<?php } ?>
			<span><?php echo esc_html( $extra_fields_value ); ?></span>
		</div>
		<?php } ?>
	<?php } ?>
	<?php do_action( 'sptp_testimonial_after_testimonial_extra_fields' ); ?>
</div>
	<?php
}
