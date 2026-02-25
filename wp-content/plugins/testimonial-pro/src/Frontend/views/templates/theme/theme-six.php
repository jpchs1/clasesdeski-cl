<?php
/**
 * Theme six.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/theme/theme-six.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $show_client_image ) {
	include self::sptp_locate_template( 'testimonial/client-image.php' );
}

if ( $testimonial_client_name ) {
	include self::sptp_locate_template( 'testimonial/client-name.php' );
}

if ( $show_testimonial_client_rating && '' !== $tpro_rating_star ) {
	include self::sptp_locate_template( 'testimonial/rating.php' );
}

if ( $show_client_designation && '' !== $tpro_designation || $show_client_company_name && '' !== $tpro_company_name
) {
	include self::sptp_locate_template( 'testimonial/company-name.php' );
}
require self::sptp_locate_template( 'testimonial/client-company-logo.php' );
if ( $show_testimonial_client_location && '' !== $tpro_location ) {
	include self::sptp_locate_template( 'testimonial/client-location.php' );
}
if ( $show_testimonial_client_phone && '' !== $tpro_phone ) {
	include self::sptp_locate_template( 'testimonial/client-phone.php' );
}
if ( $show_testimonial_client_email && '' !== $tpro_email ) {
	include self::sptp_locate_template( 'testimonial/client-email.php' );
}
if ( $show_testimonial_client_date ) {

	include self::sptp_locate_template( 'testimonial/testimonial-date.php' );
}
if ( $show_testimonial_client_website && '' !== $tpro_website ) {
	include self::sptp_locate_template( 'testimonial/client-website.php' );
}
require self::sptp_locate_template( 'testimonial/extra-fields.php' );
require self::sptp_locate_template( 'testimonial/social-profile.php' );


if ( $show_testimonial_title ) {
	include self::sptp_locate_template( 'testimonial/title.php' );
}
if ( $show_testimonial_text || $testimonial_read_more ) {
	include self::sptp_locate_template( 'testimonial/content.php' );
}
