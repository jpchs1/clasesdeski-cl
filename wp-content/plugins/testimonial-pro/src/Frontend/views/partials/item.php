<?php
/**
 * Single item.
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

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

		$img_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
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
}

require self::sptp_locate_template( 'testimonial/before-testimonial.php' );
do_action( 'sptpro_before_testimonial' );
switch ( $theme_style ) {
	case 'theme-one':
		include self::sptp_locate_template( 'theme/theme-one.php' );
		break;
	case 'theme-two':
		include self::sptp_locate_template( 'theme/theme-two.php' );
		break;
	case 'theme-three':
		include self::sptp_locate_template( 'theme/theme-three.php' );
		break;
	case 'theme-four':
		include self::sptp_locate_template( 'theme/theme-four.php' );
		break;
	case 'theme-five':
		include self::sptp_locate_template( 'theme/theme-five.php' );
		break;
	case 'theme-six':
		include self::sptp_locate_template( 'theme/theme-six.php' );
		break;
	case 'theme-seven':
		include self::sptp_locate_template( 'theme/theme-seven.php' );
		break;
	case 'theme-eight':
		include self::sptp_locate_template( 'theme/theme-eight.php' );
		break;
	case 'theme-nine':
		include self::sptp_locate_template( 'theme/theme-nine.php' );
		break;
	case 'theme-ten':
		include self::sptp_locate_template( 'theme/theme-ten.php' );
		break;
}
do_action( 'sptpro_before_testimonial' );
require self::sptp_locate_template( 'testimonial/after-testimonial.php' );
