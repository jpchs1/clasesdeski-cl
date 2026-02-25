<?php
/**
 * List Layout.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/list.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div id="sp-testimonial-pro-wrapper-<?php echo esc_attr( $post_id ); ?>" class="sp-testimonial-pro-wrapper <?php echo esc_attr( $image_zoom ); ?>" data-testimonial-id= "<?php echo esc_attr( $post_id ); ?>" data-layout= "<?php echo esc_attr( $layout ); ?>">
<?php
require self::sptp_locate_template( 'top-section.php' );
?>
<div id="sp-testimonial-pro-<?php echo esc_attr( $post_id ); ?>" <?php echo wp_kses_post( $the_rtl ) . ' ' . wp_kses_post( $data_attr ); ?> class="sp-testimonial-pro-section <?php echo esc_attr( $pagination_type ) . ' sp-testimonial-pro-read-more tpro-readmore-' . esc_attr( $testimonial_read_more_link_action ) . '-' . esc_attr( $testimonial_read_more_class ) . ' tpro-style-' . esc_attr( $theme_style ); ?>">
<div class="sp-tpro-items">
	<?php
	echo $testimonial_items['output'];
	?>
</div>
<?php require self::sptp_locate_template( 'pagination.php' ); ?>
</div>
</div>
