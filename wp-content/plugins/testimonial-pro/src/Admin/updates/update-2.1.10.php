<?php
/**
 * Update version.
 */
update_option( 'testimonial_pro_version', '2.1.10' );
update_option( 'testimonial_pro_db_version', '2.1.10' );

/**
 * Convert old to new typography.
 */
function tpro_convert_old_to_new_typography_2_1_10( $old_typography ) {
	foreach ( $old_typography as $old_key => $old_value ) {
		if ( 'family' === $old_key ) {
			$new_key                    = 'font-family';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		} elseif ( 'variant' === $old_key ) {
			$new_key                    = 'font-weight';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		} elseif ( 'font' === $old_key ) {
			$new_key                    = 'type';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		} elseif ( 'size' === $old_key ) {
			$new_key                    = 'font-size';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		} elseif ( 'height' === $old_key ) {
			$new_key                    = 'line-height';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		} elseif ( 'alignment' === $old_key ) {
			$new_key                    = 'text-align';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		} elseif ( 'transform' === $old_key ) {
			$new_key                    = 'text-transform';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		} elseif ( 'spacing' === $old_key ) {
			$new_key                    = 'letter-spacing';
			$old_typography[ $new_key ] = $old_typography[ $old_key ];
			unset( $old_typography[ $old_key ] );
		}
	}
	return $old_typography;
}

/**
 * Old typography.
 */
function tpro_old_typography_2_1_10( $testimonial_shortcode_data ) {
	$old_typographys[] = $testimonial_shortcode_data['section_title_typography'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_title_typography'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_title_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_title_typography_three'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_title_typography_four'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_text_typography'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_text_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_text_typography_three'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_text_typography_four'];
	$old_typographys[] = $testimonial_shortcode_data['client_name_typography'];
	$old_typographys[] = $testimonial_shortcode_data['client_name_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['client_designation_company_typography'];
	$old_typographys[] = $testimonial_shortcode_data['client_designation_company_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['client_location_typography'];
	$old_typographys[] = $testimonial_shortcode_data['client_location_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['client_phone_typography'];
	$old_typographys[] = $testimonial_shortcode_data['client_phone_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['client_email_typography'];
	$old_typographys[] = $testimonial_shortcode_data['client_email_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_date_typography'];
	$old_typographys[] = $testimonial_shortcode_data['testimonial_date_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['client_website_typography'];
	$old_typographys[] = $testimonial_shortcode_data['client_website_typography_two'];
	$old_typographys[] = $testimonial_shortcode_data['filter_typography'];
	return $old_typographys;
}

/**
 * Update old to new typography.
 */
$args          = new WP_Query(
	array(
		'post_type'      => 'sp_tpro_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );

if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {

		$testimonial_shortcode_data = get_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', true );
		$old_typography_data[]      = tpro_old_typography_2_1_10( $testimonial_shortcode_data );

		if ( $old_typography_data ) {
			foreach ( $old_typography_data as $old_typography_key => $old_typography_value ) {
				foreach ( $old_typography_value as $old_typography_sub_key => $old_typography ) {

					$new_typography = tpro_convert_old_to_new_typography_2_1_10( $old_typography );
					if ( 0 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['section_title_typography'] = $new_typography;
					} elseif ( 1 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_title_typography'] = $new_typography;
					} elseif ( 2 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_title_typography_two'] = $new_typography;
					} elseif ( 3 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_title_typography_three'] = $new_typography;
					} elseif ( 4 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_title_typography_four'] = $new_typography;
					} elseif ( 5 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_text_typography'] = $new_typography;
					} elseif ( 6 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_text_typography_two'] = $new_typography;
					} elseif ( 7 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_text_typography_three'] = $new_typography;
					} elseif ( 8 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_text_typography_four'] = $new_typography;
					} elseif ( 9 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_name_typography'] = $new_typography;
					} elseif ( 10 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_name_typography_two'] = $new_typography;
					} elseif ( 11 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_designation_company_typography'] = $new_typography;
					} elseif ( 12 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_designation_company_typography_two'] = $new_typography;
					} elseif ( 13 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_location_typography'] = $new_typography;
					} elseif ( 14 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_location_typography_two'] = $new_typography;
					} elseif ( 15 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_phone_typography'] = $new_typography;
					} elseif ( 16 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_phone_typography_two'] = $new_typography;
					} elseif ( 17 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_email_typography'] = $new_typography;
					} elseif ( 18 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_email_typography_two'] = $new_typography;
					} elseif ( 19 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_date_typography'] = $new_typography;
					} elseif ( 20 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['testimonial_date_typography_two'] = $new_typography;
					} elseif ( 21 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_website_typography'] = $new_typography;
					} elseif ( 22 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['client_website_typography_two'] = $new_typography;
					} elseif ( 23 === $old_typography_sub_key ) {
						$testimonial_shortcode_data['filter_typography'] = $new_typography;
					}
				}
			}
		}

		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $testimonial_shortcode_data );
	}
}
