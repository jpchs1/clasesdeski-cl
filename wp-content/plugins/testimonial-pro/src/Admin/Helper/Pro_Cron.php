<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link        http://shapedplugin.com/
 * @since      2.2.4
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Admin\Helper;

/**
 * Pro Cron
 */
class Pro_Cron {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.2.4
	 */
	public function __construct() {

		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
		add_action( 'wp', array( $this, 'schedule_events' ) );
	}

	/**
	 * Registers new cron schedules.
	 *
	 * @since 2.2.4
	 *
	 * @param array $schedules schedules.
	 * @return array
	 */
	public function add_schedules( $schedules = array() ) {
		// Adds once weekly to the existing schedules.
		$schedules['weekly'] = array(
			'interval' => WEEK_IN_SECONDS,
			'display'  => __( 'Once Weekly', 'testimonial-pro' ),
		);

		return $schedules;
	}

	/**
	 * Schedules our events
	 *
	 * @since 2.2.4
	 * @return void
	 */
	public function schedule_events() {
		$this->weekly_events();
	}

	/**
	 * Schedule weekly events
	 *
	 * @access private
	 * @since 2.2.4
	 * @return void
	 */
	private function weekly_events() {
		if ( ! wp_next_scheduled( 'testimonial_pro_weekly_scheduled_events' ) ) {
			wp_schedule_event( time(), 'weekly', 'testimonial_pro_weekly_scheduled_events' );
		}
	}

}
