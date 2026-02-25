<?php
/**
 * Social profile.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/social-profile.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $show_social_profile ) {
	$social_profiles = isset( $testimonial_data['tpro_social_profiles'] ) ? $testimonial_data['tpro_social_profiles'] : '';
	if ( $social_profiles ) {
		echo '<div class="tpro-social-profile">';
		do_action( 'sptpro_before_testimonial_social_profiles' );
		foreach ( $social_profiles as $profile ) {
			$social_name = isset( $profile['social_name'] ) ? $profile['social_name'] : '';
			$social_url  = isset( $profile['social_url'] ) ? $profile['social_url'] : '';
			if ( ! empty( $social_name && $social_url ) ) {
				echo '<a href="' . esc_url( $social_url ) . '" class="tpro-' . $social_name . '" target="_blank" rel="noopener">' . self::tpro_social_icon( $social_name ) . '</a>';
			}
		}
		do_action( 'sptpro_after_testimonial_social_profiles' );
		echo '</div>';
	}
}
