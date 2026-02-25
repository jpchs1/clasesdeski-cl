<?php
/**
 * Update version.
 */
update_option( 'testimonial_pro_version', '2.2.2' );
update_option( 'testimonial_pro_db_version', '2.2.2' );

/**
 * Old post type to new for shortcode.
 */
global $wpdb;
$old_post_types = array( 'sp_tpro_shortcodes' => 'spt_shortcodes' );
foreach ( $old_post_types as $old_type => $type ) {
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->posts} SET post_type = REPLACE(post_type, %s, %s) 
                        WHERE post_type LIKE %s",
			$old_type,
			$type,
			$old_type
		)
	);
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
                        WHERE guid LIKE %s",
			"post_type={$old_type}",
			"post_type={$type}",
			"%post_type={$type}%"
		)
	);
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
                        WHERE guid LIKE %s",
			"/{$old_type}/",
			"/{$type}/",
			"%/{$old_type}/%"
		)
	);
}
