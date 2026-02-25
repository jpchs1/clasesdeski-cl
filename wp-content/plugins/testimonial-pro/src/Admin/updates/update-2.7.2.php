<?php
/**
 * Update version.
 */
update_option( 'testimonial_pro_version', '2.7.2' );
update_option( 'testimonial_pro_db_version', '2.7.2' );

/**
 * Shortcode query for id.
 */
$args          = new WP_Query(
	array(
		'post_type'      => 'spt_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {
		$shortcode_data = get_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', true );
		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}
		$space_between = isset( $shortcode_data['testimonial_margin']['all'] ) && $shortcode_data['testimonial_margin']['all'] ? $shortcode_data['testimonial_margin']['all'] : '0';
		if ( isset( $shortcode_data['testimonial_margin'] ) ) {
			$shortcode_data['testimonial_margin']['top']   = $space_between;
			$shortcode_data['testimonial_margin']['right'] = $space_between;
		}
		// Set border radius for theme 1 to 9.
		$testimonial_border_radius_for_one = isset( $shortcode_data['testimonial_border_radius_for_one'] ) ? $shortcode_data['testimonial_border_radius_for_one'] : '0';
		if ( isset( $shortcode_data['testimonial_border_for_one'] ) ) {
			$shortcode_data['testimonial_border_for_one']['radius'] = $testimonial_border_radius_for_one;
		}
		if ( isset( $shortcode_data['testimonial_border'] ) ) {
			$shortcode_data['testimonial_border']['radius'] = $testimonial_border_radius_for_one;
		}

		// Set border, border radius for theme 10.
		$testimonial_border = isset( $shortcode_data['testimonial_border'] ) && is_array( $shortcode_data['testimonial_border'] ) ? $shortcode_data['testimonial_border'] : array(
			'all'   => '1',
			'style' => 'solid',
			'color' => '#e3e3e3',
		);

		$border_radius_theme_ten = isset( $shortcode_data['testimonial_border_radius'] ) ? $shortcode_data['testimonial_border_radius'] : '10';
		if ( ! empty( $testimonial_border ) ) {
			$shortcode_data['testimonial_border_theme_ten'] = array(
				'all'    => $testimonial_border['all'],
				'style'  => $testimonial_border['style'],
				'color'  => $testimonial_border['color'],
				'radius' => $border_radius_theme_ten,
			);
		}
		// Set border radius for Thumbnail layout.
		$testimonial_border_radius_for_thumbnail = isset( $shortcode_data['testimonial_border_radius_for_thumbnail'] ) ? $shortcode_data['testimonial_border_radius_for_thumbnail'] : '0';
		if ( isset( $shortcode_data['testimonial_border_for_thumbnail'] ) ) {
			$shortcode_data['testimonial_border_for_thumbnail']['radius'] = $testimonial_border_radius_for_thumbnail;
		}

		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $shortcode_data );
	}
}
