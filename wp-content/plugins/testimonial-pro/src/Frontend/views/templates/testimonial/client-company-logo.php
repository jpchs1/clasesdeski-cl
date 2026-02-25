<?php
/**
 * Client name.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-company-image.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $tpro_company_logo && $show_client_company_logo ) {
	$image_full_url = wp_get_attachment_image_src( $tpro_company_logo, 'full' );
	$image_full_url = is_array( $image_full_url ) ? $image_full_url : array( '', '', '' );
	$image_url      = wp_get_attachment_image_src( $tpro_company_logo, $company_image_sizes );
	$image_url      = is_array( $image_url ) ? $image_url : array( '', '', '' );

	$image_resize_url = '';
	if ( ( 'custom' === $company_image_sizes ) && ( ! empty( $company_image_width ) && $image_full_url[1] >= $company_image_width ) && ( ! empty( $company_image_height ) ) && $image_full_url[2] >= $company_image_height ) {
		$image_resize_url = self::sptp_image_resize( $image_full_url[0], $company_image_width, $company_image_height, $company_image_crop );
	}
	$img_width        = 'custom' === $company_image_sizes && ! empty( $company_image_width ) ? $company_image_width : $image_url[1];
	$img_height       = 'custom' === $company_image_sizes && ! empty( $company_image_height ) ? $company_image_height : $image_url[2];
	$company_logo_src = ! empty( $image_resize_url ) ? $image_resize_url : $image_url[0];
	$img_alt          = get_post_meta( $tpro_company_logo, '_wp_attachment_image_alt', true );
	?>
<div class="tpro-client-company-image">
	<img src="<?php echo esc_attr( $company_logo_src ); ?>" width="<?php echo esc_attr( $img_width ); ?>" height="<?php echo esc_attr( $img_height ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
</div>
<?php } ?>
