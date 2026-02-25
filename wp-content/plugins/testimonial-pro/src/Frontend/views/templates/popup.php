<?php
/**
 * Popup content.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/popup.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="remodal sp-tpro-modal-testimonial-<?php echo esc_attr( $post_id ); ?> sp-tpro-modal-testimonial" data-remodal-id="sp-tpro-testimonial-id-<?php echo esc_attr( get_the_ID() ); ?>">
	<a data-remodal-action="close" class="remodal-close"></a>
	<div class="sp-testimonial-pro-item">
		<div class="sp-testimonial-pro">
			<?php
			if ( has_post_thumbnail() && $show_client_image ) {
				echo sprintf( '<div class="tpro-client-image tpro-image-style-%1$s" itemprop="image"><img src="%2$s"></div>', esc_attr( $client_image_style ), esc_url( $image_src ) );
			}
			if ( $show_testimonial_title ) {
				include self::sptp_locate_template( 'testimonial/title.php' );
			}
			if ( $show_testimonial_text || $testimonial_read_more ) {
				echo sprintf( '<div class="tpro-client-testimonial">%1$s</div>', apply_filters( 'the_content', get_the_content() ) );
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
			require self::sptp_locate_template( 'testimonial/social-profile.php' );
			?>
		</div> <!--  sp-testimonial-pro -->
	</div> <!-- sp-testimonial-pro-item -->
</div>
