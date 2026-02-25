<?php
/**
 * Company name.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/company-name.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $show_client_designation && '' !== $tpro_designation || $show_client_company_name && '' !== $tpro_company_name
) {
	echo '<div class="tpro-client-designation-company">';
	do_action( 'sptpro_before_testimonial_client_designation_company' );
	if ( $identity_linking_website && '' !== $tpro_website ) {
		echo '<a target="' . esc_attr( $website_link_target ) . '" rel="noreferrer" href="' . esc_url( $tpro_website ) . '">';
	}
	if ( $show_client_designation && '' !== $tpro_designation ) {
		echo wp_kses_post( $tpro_designation );
	}
	if ( $show_client_designation && '' !== $tpro_designation && $show_client_company_name && '' !== $tpro_company_name ) {
		echo ' - ';
	}
	if ( $show_client_company_name && '' !== $tpro_company_name ) {
		echo wp_kses_post( $tpro_company_name );
	}
	if ( $identity_linking_website && '' !== $tpro_website ) {
		echo '</a>';
	}
	do_action( 'sptpro_after_testimonial_client_designation_company' );
	echo '</div>';
}
