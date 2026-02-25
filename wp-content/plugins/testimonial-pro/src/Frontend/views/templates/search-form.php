<?php
/**
 * Logo search form
 *
 * This template can be overridden by copying it to yourtheme/logo-carousel-pro/templates/search-form.php
 *
 * @package    logo-carousel-pro
 * @subpackage logo-carousel-pro/Frontend
 * @since 2.0.0
 */

$testimonial_search_align = 'center';
if ( $ajax_testimonial_search ) {
	?>
<div class="sp_testimonial_search" style="text-align:<?php echo esc_attr( $testimonial_search_align ); ?>">
	<label for="search">
	<?php echo esc_html( $search_text ); ?>
		<input type="text" name="s" id="testimonial_search-<?php echo esc_attr( 'sp-testimonial-pro-wrapper-' . $post_id ); ?>" placeholder="<?php echo esc_html( apply_filters( 'sp_testimonial_search_placeholder_text', __( 'Search Testimonial', 'testimonial-pro' ) ) ); ?>" value="" />
	</label>
</div>
<?php } ?>
