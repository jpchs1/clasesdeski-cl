<?php
/**
 * Schema markup.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/schema-markup.php
 *
 * @since    2.5.4
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "<?php echo esc_html( $schema_type ); ?>",
	"name": "<?php echo esc_html( $tpro_global_item_name ); ?>",
	"aggregateRating": {
		"@type": "AggregateRating",
		"bestRating": "5",
		"ratingValue": "<?php echo esc_html( $aggregate_rating ); ?>",
		"worstRating": "1",
		"reviewCount": "<?php echo esc_html( $total_posts ); ?>"
	},
	"review": [<?php echo $schema_html; ?>]
}
</script>
