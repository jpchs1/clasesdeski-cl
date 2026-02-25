<?php
/**
 * Top section of each layouts.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/top-section.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

require self::sptp_locate_template( 'preloader.php' );
if ( $show_testimonial_text && 'content_with_limit' === $testimonial_content_type && $testimonial_read_more && 'expand' === $testimonial_read_more_link_action ) {
	echo '<div class="sp-tpro-rm-config"  ' . wp_kses_post( $read_more_config ) . '></div>';
}
require self::sptp_locate_template( 'section-title.php' );
require self::sptp_locate_template( 'search-form.php' );
require self::sptp_locate_template( 'average-rating.php' );
require self::sptp_locate_template( 'live-filter-buttons.php' );
