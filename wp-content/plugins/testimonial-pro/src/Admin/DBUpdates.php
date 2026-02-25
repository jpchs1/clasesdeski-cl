<?php
/**
 * Fired during plugin updates
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.10
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Views
 */

namespace ShapedPlugin\TestimonialPro\Admin;

// Don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DBUpdates
 */
class DBUpdates {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = array(
		'2.1.10' => 'updates/update-2.1.10.php',
		'2.2.0'  => 'updates/update-2.2.0.php',
		'2.2.2'  => 'updates/update-2.2.2.php',
		'2.2.4'  => 'updates/update-2.2.4.php',
		'2.7.2'  => 'updates/update-2.7.2.php',
	);

	/**
	 * Binding all events
	 *
	 * @since 2.1.10
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ) );
	}

	/**
	 * Check if need any update
	 *
	 * @since 2.1.10
	 *
	 * @return boolean
	 */
	public function is_needs_update() {
		$installed_version = get_option( 'testimonial_pro_version' );
		$first_version     = get_option( 'testimonial_pro_first_version' );
		$activation_date   = get_option( 'testimonial_pro_activation_date' );

		if ( false === $installed_version ) {
			update_option( 'testimonial_pro_version', SP_TPRO_VERSION );
			update_option( 'testimonial_pro_db_version', SP_TPRO_VERSION );
		}
		if ( false === $first_version ) {
			update_option( 'testimonial_pro_first_version', SP_TPRO_VERSION );
		}
		if ( false === $activation_date ) {
			update_option( 'testimonial_pro_activation_date', current_time( 'timestamp' ) );
		}

		if ( version_compare( $installed_version, SP_TPRO_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Do updates.
	 *
	 * @since 2.1.10
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates.
	 *
	 * @since 2.1.10
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->is_needs_update() ) {
			return;
		}

		$installed_version = get_option( 'testimonial_pro_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'testimonial_pro_version', $version );
			}
		}
		update_option( 'testimonial_pro_version', SP_TPRO_VERSION );
	}

}
