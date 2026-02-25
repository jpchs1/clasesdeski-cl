<?php
/**
 * Rating.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/rating.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

switch ( $star_icon ) {
	case 'fa fa-star':
		$full_star_icon  = '<i class="fa fa-star" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-star-o" aria-hidden="true"></i>';
		break;
	case 'fa fa-heart':
		$full_star_icon  = '<i class="fa fa-heart" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-heart-o" aria-hidden="true"></i>';
		break;
	case 'fa fa-thumbs-up':
		$full_star_icon  = '<i class="fa fa-thumbs-up" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>';
		break;
	case 'fa fa-hourglass':
		$full_star_icon  = '<i class="fa fa-hourglass" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-hourglass-o" aria-hidden="true"></i>';
		break;
	case 'fa fa-circle':
		$full_star_icon  = '<i class="fa fa-circle" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-circle-thin" aria-hidden="true"></i>';
		break;
	case 'fa fa-square':
		$full_star_icon  = '<i class="fa fa-square" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-square-o" aria-hidden="true"></i>';
		break;
	case 'fa fa-flag':
		$full_star_icon  = '<i class="fa fa-flag" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-flag-o" aria-hidden="true"></i>';
		break;
	case 'fa fa-smile-o':
		$full_star_icon  = '<i class="fa fa-smile-o" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="fa fa-frown-o" aria-hidden="true"></i>';
		break;
}
$full_star_icon  = apply_filters( 'testimonial_client_rating_full_star_icon', $full_star_icon, get_the_ID() );
$empty_star_icon = apply_filters( 'testimonial_client_rating_empty_star_icon', $empty_star_icon, get_the_ID() );

?>
<div class="tpro-client-rating">
<?php do_action( 'sptpro_before_testimonial_client_rating' ); ?>
	<?php
	switch ( $tpro_rating_star ) {
		case 'five_star':
			echo sprintf( '%1$s%1$s%1$s%1$s%1$s', $full_star_icon );
			break;
		case 'four_star':
			echo sprintf( '%1$s%1$s%1$s%1$s%2$s', $full_star_icon, $empty_star_icon );
			break;
		case 'three_star':
			echo sprintf( '%1$s%1$s%1$s%2$s%2$s', $full_star_icon, $empty_star_icon );
			break;
		case 'two_star':
			echo sprintf( '%1$s%1$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon );
			break;
		case 'one_star':
			echo sprintf( '%1$s%2$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon );
			break;
	};
	?>
<?php do_action( 'sptpro_after_testimonial_client_rating' ); ?>
</div>
