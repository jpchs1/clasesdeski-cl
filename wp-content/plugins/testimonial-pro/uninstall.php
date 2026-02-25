<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://shapedplugin.com
 * @since      2.0.0
 *
 * @package Testimonial_pro.
 * @subpackage Testimonial_pro.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Load TPro file.
require_once plugin_dir_path( __FILE__ ) . '/testimonial-pro.php';


$setting_options = get_option( 'sp_testimonial_pro_options' );
$data_deletion   = isset( $setting_options['testimonial_data_remove'] ) ? $setting_options['testimonial_data_remove'] : false;

if ( $data_deletion ) {

	// Delete testimonials and shortcodes.
	global $wpdb;
	$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'spt_testimonial'" );
	$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'spt_shortcodes'" );
	$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'spt_testimonial_form'" );
	$wpdb->query( 'DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)' );
	$wpdb->query( 'DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)' );
	$wpdb->query( "DELETE FROM wp_term_taxonomy WHERE taxonomy = 'testimonial_cat' AND term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM wp_term_relationships)" );
	$wpdb->query( 'DELETE FROM wp_terms WHERE term_id NOT IN (SELECT term_id FROM wp_term_taxonomy)' );

	// Remove Option.
	delete_option( 'sp_testimonial_pro_options' );
	delete_option( 'testimonial_pro_version' );
	delete_option( 'testimonial_pro_first_version' );
	delete_option( 'testimonial_pro_activation_date' );
	delete_option( 'testimonial_pro_db_version' );
	delete_option( 'widget_tpro_widget_content' );
	delete_option( 'testimonial_cat_children' );

	// Site options in Multisite.
	delete_site_option( 'sp_testimonial_pro_options' );
	delete_site_option( 'testimonial_pro_version' );
	delete_site_option( 'testimonial_pro_first_version' );
	delete_site_option( 'testimonial_pro_activation_date' );
	delete_site_option( 'testimonial_pro_db_version' );
	delete_site_option( 'widget_tpro_widget_content' );
	delete_site_option( 'testimonial_cat_children' );

	// Delete Cron.
	$crons            = _get_cron_array();
	$testimonial_cron = 'testimonial_pro_weekly_scheduled_events';
	foreach ( $crons as $timestamp => $cron ) {
		if ( isset( $cron[ $testimonial_cron ] ) ) {
			unset( $cron[ $testimonial_cron ] );
		}
		if ( ! empty( $cron ) ) {
			$new_cron[ $timestamp ] = $cron;
		}
	}
	update_option( 'cron', $new_cron );

}
