<?php
/**
 * Preloader.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/preloader.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $preloader ) {
	$preloader_image = SP_TPRO_URL . 'Admin/assets/images/spinner.svg';
	$preloader_image = apply_filters( 'sp_testimonial_pro_preloader_image', $preloader_image );
	?>
<div class="tpro-preloader" id="tpro-preloader-<?php echo esc_attr( $post_id ); ?>">
	<img src="<?php echo esc_url( $preloader_image ); ?>" alt="loader-image"/>
</div>
<?php } ?>
