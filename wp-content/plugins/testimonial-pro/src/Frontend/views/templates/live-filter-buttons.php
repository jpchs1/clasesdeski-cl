<?php
/**
 * The Testimonial live filter button.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/live-filter-buttons.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

use ShapedPlugin\TestimonialPro\Frontend\Helper;
$filter_all_btn_text['taxonomy'] = isset( $shortcode_data['all_tab_text'] ) ? $shortcode_data['all_tab_text'] : 'All';
$filter_all_btn_switch           = isset( $shortcode_data['all_tab'] ) ? $shortcode_data['all_tab'] : true;
$filter_type                     = isset( $shortcode_data['live_filter_type'] ) ? $shortcode_data['live_filter_type'] : 'filter_button';
if ( $testimonial_live_filter ) {
	$filter_button_array = Helper::isotope_button_meta( $testimonial_query_and_ids['all_testimonial_ids'], $shortcode_data, $post_id );
	?>
<div class="sp-testimonial-live-filter">
	<?php
	if ( 'filter_button' === $filter_type ) {
		?>
<div class="button-group filters-button-group testimonial-ajax-live-filter" data-filter-group="taxonomy">
		<?php if ( $filter_all_btn_switch && ! empty( $filter_all_btn_text['taxonomy'] ) ) { ?>
			<button class="button is-checked" data-slug=""><?php echo esc_html( $filter_all_btn_text['taxonomy'] ); ?></button>
			<?php
		}
		?>
		<?php
		$first_button_key = array_key_first( $filter_button_array['category'] );
		foreach ( $filter_button_array['category'] as $testimonial_group ) :
			$button_checked = ( strtolower( $first_button_key ) === $testimonial_group['slug'] && ( '0' === $filter_all_btn_switch ) || strtolower( $first_button_key ) === $testimonial_group['slug'] && ( '1' === $filter_all_btn_switch && empty( $filter_all_btn_text['taxonomy'] ) ) ) ? 'is-checked' : '';
			?>
			<?php
			if ( isset( $testimonial_group['name'] ) && ! empty( $testimonial_group['name'] ) ) {
				?>
		<button class="button fltr-controls <?php echo esc_attr( $button_checked ); ?> <?php echo esc_attr( $testimonial_group['slug'] ); ?>" data-slug="<?php echo esc_attr( $testimonial_group['slug'] ); ?>"><?php echo esc_html( $testimonial_group['name'] ); ?></button>
			<?php } ?>
			<?php
			endforeach;
		?>
</div>

		<?php
	}
	if ( 'filter_button' !== $filter_type ) {
		?>
	<div class="sp-testimonial-select testimonial-ajax-live-filter">
		<select class="filterSelect" data-filter-group="taxonomy">
			<?php if ( $filter_all_btn_switch ) : ?>
			<option value="all"><?php echo esc_html( $filter_all_btn_text['taxonomy'] ); ?></option>
				<?php
				endif;
			foreach ( $filter_button_array['category'] as $testimonial_cat ) :
				?>
			<option value="<?php echo esc_attr( $testimonial_cat['slug'] ); ?>"><?php echo esc_html( $testimonial_cat['name'] ); ?></option>
				<?php
				endforeach;
			?>
		</select>
	</div>

		<?php
	}
	?>
</div>
	<?php
}
