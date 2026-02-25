<?php

/**
 * @link              https://www.howardehrenberg.com
 * @since             1.0.0
 * @package           Owl_Carousel_2
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Post Carousels with Owl
 * Plugin URI:        https://www.duckdiverllc.com/dd-owl-carousel-2/
 * Description:       Easily add any post type post as a custom post carousel with Owl Carousel 2. Works with any cusotm post type, WooCommerce Products, Featured Products, FAQ, etc.
 * Version:           1.4.6
 * Author:            Howard Ehrenberg
 * Author URI:        https://www.howardehrenberg.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       owl-carousel-2
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 6.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

const DD_Owl_Carousel_2 = '1.4.6';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-owl-carousel-2-activator.php
 */
function activate_owl_carousel_2() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-owl-carousel-2-activator.php';
	Owl_Carousel_2_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-owl-carousel-2-deactivator.php
 */
function deactivate_owl_carousel_2() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-owl-carousel-2-deactivator.php';
	Owl_Carousel_2_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_owl_carousel_2' );
register_deactivation_hook( __FILE__, 'deactivate_owl_carousel_2' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-owl-carousel-2.php';
require_once plugin_dir_path(__FILE__) . 'includes/aq_resizer.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_owl_carousel_2() {

	$plugin = new Owl_Carousel_2();
	$plugin->run();

}
run_owl_carousel_2();

// Add Donate Link to Plugin Page
add_filter( 'plugin_row_meta', 'dd_owl_2_plugin_row_meta', 10, 2 );

function dd_owl_2_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'owl-carousel-2.php' ) !== false ) {
		$new_links = array(
				'donate' => '<a href="https://www.duckdiverllc.com/owl-carousel-2-plguin/" target="_blank">Donate</a>',
				);

		$links = array_merge( $links, $new_links );
	}

	return $links;
}
