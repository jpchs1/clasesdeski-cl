<?php
/**
 * Thumbnail slider.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/thumbnail-slider.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div id="sp-testimonial-pro-wrapper-<?php echo esc_attr( $post_id ); ?>" class="sp-testimonial-pro-wrapper sp-tpro-thumbnail-slider sp_tpro_nav_position_<?php echo esc_attr( $navigation_position ); ?> <?php echo esc_attr( $image_zoom ); ?>">
<?php
require self::sptp_locate_template( 'top-section.php' );
// Thumb slider.
?>
<div id="sp-testimonial-pro-<?php echo esc_attr( $post_id ); ?>" <?php echo wp_kses_post( $the_rtl ) . ' ' . wp_kses_post( $data_attr ); ?>  class="sp-testimonial-pro-section sp-testimonial-pro-section-thumb" data-thumb_swiper='{"slidesToShow": <?php echo esc_attr( $slides_to_show ); ?>, "autoplay": <?php echo esc_attr( $slider_auto_play ); ?>, "autoplaySpeed": <?php echo esc_attr( $slider_auto_play_speed ); ?>, "speed": <?php echo esc_attr( $slider_scroll_speed ); ?>, "arrows": <?php echo esc_attr( $navigation_arrows ); ?>, "dots": <?php echo esc_attr( $pagination_dots ); ?>, "effect": <?php echo esc_attr( $slider_fade_effect_thumb ); ?>, "adaptiveHeight": <?php echo esc_attr( $adaptive_height ); ?>, "pauseOnHover": <?php echo esc_attr( $slider_pause_on_hover_thumb ); ?>, "swipe": <?php echo esc_attr( $slider_swipe_thumb ); ?>, "swipeToSlide": <?php echo esc_attr( $swipe_to_slide ); ?>, "draggable": <?php echo esc_attr( $slider_draggable_thumb ); ?>, "rtl": <?php echo esc_attr( $rtl_mode ); ?>, "slidesPerView": {"lg_desktop": <?php echo esc_attr( $columns_large_desktop ); ?>, "desktop": <?php echo esc_attr( $columns_desktop ); ?>, "laptop": <?php echo esc_attr( $columns_laptop ); ?>, "tablet": <?php echo esc_attr( $columns_tablet ); ?>, "mobile": <?php echo esc_attr( $columns_mobile ); ?>}}' data-arrowicon="angle">
<div class="swiper-wrapper">
	<?php echo $testimonial_items['thumbnail_slider_image_markup']; ?>
</div>
</div>
<!-- Content Slider -->
<div id="sp-testimonial-pro-<?php echo esc_attr( $post_id ); ?>" <?php echo wp_kses_post( $the_rtl ) . ' ' . wp_kses_post( $data_attr ); ?> class="sp-testimonial-pro-section sp-testimonial-pro-read-more tpro-readmore-<?php echo esc_attr( $testimonial_read_more_link_action . '-' . $testimonial_read_more_class ); ?> sp-testimonial-pro-section-content tpro-style-theme-one">
<div class="swiper-wrapper">
<?php echo wp_kses_post( $testimonial_items['thumbnail_slider_content_markup'] ); ?>
</div>
<?php
if ( 'true' === $pagination_dots && $thumbnail_slider ) {
	?>
	<div class="sp-testimonial-pagination swiper-pagination"></div>
<?php } ?>
	<?php if ( 'true' === $navigation_arrows && $thumbnail_slider ) { ?>
		<div class="tpro-button-next tpro-arrow swiper-button-next <?php echo esc_html( $navigation_position ); ?>"><i class="fa fa-<?php echo esc_html( $navigation_icons ); ?>-right"></i></div>
		<div class="tpro-button-prev tpro-arrow swiper-button-prev <?php echo esc_html( $navigation_position ); ?>"><i class="fa fa-<?php echo esc_html( $navigation_icons ); ?>-left"></i></div><?php } ?>
</div><!-- /Content Slider -->
</div>
