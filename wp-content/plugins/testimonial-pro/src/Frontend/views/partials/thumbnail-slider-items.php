<?php
$thumbnail_slider_image_markup .= '<div class="sp-testimonial-pro-item swiper-slide">';
if ( $show_client_image ) {
	$video_url       = isset( $testimonial_data['tpro_video_url'] ) ? $testimonial_data['tpro_video_url'] : '';
	$retina_img_attr = '';
	if ( has_post_thumbnail() ) {
		$image_id       = get_post_thumbnail_id();
		$image_full_url = wp_get_attachment_image_src( $image_id, 'full' );
		$image_full_url = is_array( $image_full_url ) ? $image_full_url : array( '', '', '' );
		$image_url      = wp_get_attachment_image_src( $image_id, $image_sizes );
		$image_url      = is_array( $image_url ) ? $image_url : array( '', '', '' );

		$image_resize_url    = '';
		$image_resize_2x_url = '';
		if ( ( 'custom' === $image_sizes ) && ( ! empty( $client_image_width ) && $image_full_url[1] >= $client_image_width ) && ( ! empty( $client_image_height ) ) && $image_full_url[2] >= $client_image_height ) {
			$image_resize_url = self::sptp_image_resize( $image_full_url[0], $client_image_width, $client_image_height, $client_image_crop );
			if ( $show_2x_image && ( $image_full_url[1] >= ( $client_image_width * 2 ) ) && $image_full_url[2] >= ( $client_image_height * 2 ) ) {
				$image_resize_2x_url = self::sptp_image_resize( $image_full_url[0], $client_image_width * 2, $client_image_height * 2, $client_image_crop );
			}
		}
		$img_width  = 'custom' === $image_sizes && ! empty( $client_image_width ) ? $client_image_width : $image_url[1];
		$img_height = 'custom' === $image_sizes && ! empty( $client_image_height ) ? $client_image_height : $image_url[2];
		$image_src  = ! empty( $image_resize_url ) ? $image_resize_url : $image_url[0];
		$img_alt    = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		if ( ! empty( $image_resize_2x_url ) ) {
			$retina_img_attr = 'srcset="' . esc_attr( $image_src ) . ', ' . esc_attr( $image_resize_2x_url ) . ' 2x"';
		}
	} else {
		$img_width      = '120';
		$img_height     = '120';
		$image_src      = SP_TPRO_URL . 'Frontend/assets/images/placeholder-image.png';
		$img_alt        = __( 'Placeholder Image', 'testimonial-pro' );
		$image_full_url = array(
			SP_TPRO_URL . 'Frontend/assets/images/placeholder-image.png',
			'120',
			'120',
			false,
		);
	}
	ob_start();
	include self::sptp_locate_template( 'testimonial/client-image.php' );
	$thumbnail_slider_image_markup .= ob_get_clean();
}
$thumbnail_slider_image_markup .= '</div>'; // sp-testimonial-pro-item.

ob_start();
echo '<div class="sp-testimonial-pro-item swiper-slide">
<div class="sp-testimonial-pro">';
do_action( 'sptpro_before_testimonial' );
if ( $show_testimonial_title ) {
	include self::sptp_locate_template( 'testimonial/title.php' );
}
if ( $show_testimonial_text || $testimonial_read_more ) {
	include self::sptp_locate_template( 'testimonial/content.php' );
}
if ( $testimonial_client_name ) {
	include self::sptp_locate_template( 'testimonial/client-name.php' );
}
if ( $show_testimonial_client_rating && '' !== $tpro_rating_star ) {
	include self::sptp_locate_template( 'testimonial/rating.php' );
}
if ( $show_client_designation && '' !== $tpro_designation || $show_client_company_name && '' !== $tpro_company_name ) {
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
	do_action( 'sptpro_after_testimonial' );
if ( $testimonial_read_more && 'popup' === $testimonial_read_more_link_action ) {
	include self::sptp_locate_template( 'popup.php' );
} // Modal End.
echo '</div>';
echo '</div>';
$thumbnail_slider_content_markup .= ob_get_clean();
