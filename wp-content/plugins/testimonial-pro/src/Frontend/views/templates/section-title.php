<?php
/**
 * Section title.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/section-title.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $section_title ) {
	?>
<h2 class="sp-testimonial-pro-section-title"><?php echo wp_kses_post( $main_section_title ); ?></h2>
<?php } ?>
