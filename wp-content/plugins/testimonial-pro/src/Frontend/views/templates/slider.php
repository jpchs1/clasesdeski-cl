<?php
/**
 * Slider.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/slider.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div id="sp-testimonial-pro-wrapper-<?php echo esc_attr( $post_id ); ?>" class="sp-testimonial-pro-wrapper sp-testimonial-carousel sp_tpro_nav_position_<?php echo esc_attr( $navigation_position ); ?> tpro-layout-slider-<?php echo esc_attr( $slider_mode ); ?> <?php echo esc_attr( $image_zoom ); ?>" <?php echo esc_attr( $testimonial_same_height ); ?>  data-testimonial-id= "<?php echo esc_attr( $post_id ); ?>" data-layout= "<?php echo esc_attr( $layout ); ?>">
<?php
require self::sptp_locate_template( 'top-section.php' );
?>
<div class="sp-testimonial-carousel-wrapper">
<div id="sp-testimonial-pro-<?php echo esc_attr( $post_id ); ?>" <?php echo wp_kses_post( $the_rtl ) . ' ' . wp_kses_post( $data_attr ); ?> class="sp-tpCarousel swiper-container sp-testimonial-pro-section <?php echo ' tpro-layout-slider-' . esc_attr( $slider_mode ) . ' sp-testimonial-pro-read-more ' . esc_attr( $fade_carousel_class ) . ' tpro-readmore-' . esc_attr( $testimonial_read_more_link_action ) . '-' . esc_attr( $testimonial_read_more_class ) . ' tpro-style-' . esc_attr( $theme_style ); ?>">
<div class="swiper-wrapper">
<?php
echo $testimonial_items['output'];
?>
</div>

<?php
if ( 'true' === $pagination_dots && 'ticker' !== $slider_mode ) {
	?>
			<div class="sp-testimonial-pagination swiper-pagination"></div>
<?php } ?>
			<?php if ( 'true' === $navigation_arrows && 'ticker' !== $slider_mode ) { ?>
				<div class="tpro-button-next tpro-arrow swiper-button-next <?php echo esc_html( $navigation_position ); ?>"><i class="fa fa-<?php echo esc_html( $navigation_icons ); ?>-right"></i></div>
				<div class="tpro-button-prev tpro-arrow swiper-button-prev <?php echo esc_html( $navigation_position ); ?>"><i class="fa fa-<?php echo esc_html( $navigation_icons ); ?>-left"></i></div><?php } ?>
</div>
</div>
</div>
