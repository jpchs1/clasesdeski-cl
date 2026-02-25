<?php
/**
 * Plugin Name: Formidable Datepicker Options
 * Description: Set blackout dates, days of the week, and dynamic minimim and maximum dates
 * Version: 1.0.04
 * Plugin URI: https://formidableforms.com
 * Author URI: https://formidableforms.com
 * Author: Strategy11
 * Text Domain: frmdates
 *
 * @package formidable-dates
 */

/**
 * Autoload the classes for this plugin
 *
 * @param string $class_name The name of the class to load.
 */
function frm_dates_autoloader( $class_name ) {
	$path = dirname( __FILE__ );

	// Only load Frm classes here.
	if ( ! preg_match( '/^FrmDates.+$/', $class_name ) ) {
		return;
	}

	if ( is_callable( 'frm_class_autoloader' ) ) {
		frm_class_autoloader( $class_name, dirname( __FILE__ ) );
	}
}
// Add the autoloader.
spl_autoload_register( 'frm_dates_autoloader' );

// Load the plugin.
add_action( 'plugins_loaded', array( 'FrmDatesHooksController', 'load_hooks' ) );
