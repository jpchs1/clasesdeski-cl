<?php
/**
 * Rating.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/average-rating.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $average_rating_top ) {
	$half_empty_star_icon = '<i class="fa fa-star-half-o" aria-hidden="true"></i>';

	$full_star_icon  = '<i class="fa fa-star" aria-hidden="true"></i>';
	$empty_star_icon = '<i class="fa fa-star-o" aria-hidden="true"></i>';

	$total_review_text = sprintf( __( 'Based on %s Reviews', 'testimonial-pro' ), apply_filters( 'tpro_total_testimonial_review', $testimonial_items['total_testimonial'] ) );

	?>
<div class="average-rating-section">
<div class="average-rating-text"><?php echo apply_filters( 'testimonial_average_rating_text', esc_html__( 'Average Rating', 'testimonial-pro' ) ); ?></div>
	<div class="tpro-client-rating">
		<?php
		$aggregate_rating = $testimonial_items['aggregate_rating'];
		switch ( $aggregate_rating ) {
			case ( $aggregate_rating > 4.7 ):
				echo sprintf( '%1$s%1$s%1$s%1$s%1$s ', $full_star_icon );
				break;
			case ( $aggregate_rating > 4.2 ):
					echo sprintf( '%1$s%1$s%1$s%1$s%2$s ', $full_star_icon, $half_empty_star_icon );
				break;
			case ( $aggregate_rating > 3.7 ):
					echo sprintf( '%1$s%1$s%1$s%1$s%2$s  ', $full_star_icon, $empty_star_icon );
				break;
			case ( $aggregate_rating > 3.2 ):
				echo sprintf( '%1$s%1$s%1$s%2$s%3$s ', $full_star_icon, $half_empty_star_icon, $empty_star_icon );
				break;
			case ( $aggregate_rating > 2.7 ):
					echo sprintf( '%1$s%1$s%1$s%2$s%2$s ', $full_star_icon, $empty_star_icon );
				break;
			case ( $aggregate_rating > 1.6 ):
					echo sprintf( '%1$s%1$s%2$s%2$s%2$s ', $full_star_icon, $empty_star_icon );
				break;
			case ( $aggregate_rating > 1.7 ):
				echo sprintf( '%1$s%3$s%2$s%2$s%2$s ', $full_star_icon, $empty_star_icon, $half_empty_star_icon );
				break;
			case ( $aggregate_rating >= 0 ):
				echo sprintf( '%1$s%2$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon );
				break;
			default:
				echo sprintf( '%1$s%1$s%1$s%1$s%1$s ', $full_star_icon );
				break;
		}
		// %d means decimal value and %.2f means float value.
		$aggregate_rating = sprintf( __( '%s out of 5 stars', 'testimonial-pro' ), apply_filters( 'tpro_aggregate_rating', $aggregate_rating ) );
		echo '<span>' . esc_attr( $aggregate_rating ) . '</span>';
		?>
	</div>
	<div class="total-rating-text"><?php echo apply_filters( 'testimonial_total_rating_text', $total_review_text ); ?></div>
		</div>
	<?php
}
