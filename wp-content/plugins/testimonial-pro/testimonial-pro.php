<?php
/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://shapedplugin.com
 * @since             1.0
 * @package           Testimonial_Pro
 *
 * Plugin Name:       Real Testimonials Pro
 * Plugin URI:        https://shapedplugin.com/real-testimonials/
 * Description:       Most Customizable and Powerful Testimonials Showcase Plugin for WordPress that allows you to manage and display Testimonials or Reviews on any page or widget.
 * Version:           2.8.0
 * Author:            ShapedPlugin
 * Author URI:        https://shapedplugin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       testimonial-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$settingslc                = get_option( 'sp_testimonial_pro_options');
$settingslc['license_key'] = 'xxxxxxxxxxxxx';
update_option( 'sp_testimonial_pro_options', $settingslc);
update_option( 'testimonial_pro_license_key_status', (object) ['license' => 'valid', 'expires' => '10.10.2040']);
require_once __DIR__ . '/vendor/autoload.php';

define( 'SP_TESTIMONIAL_PRO_FILE', __FILE__ );
define( 'SP_TPRO_ITEM_NAME', 'Real Testimonials Pro' );
define( 'SP_TPRO_ITEM_SLUG', 'testimonial-pro' );
define( 'SP_TPRO_ITEM_ID', 2422 );
define( 'SP_TPRO_STORE_URL', 'https://shapedplugin.com' );
define( 'SP_TPRO_PRODUCT_URL', 'https://shapedplugin.com/real-testimonials/' );
define( 'SP_TPRO_VERSION', '2.8.0' );
define( 'SP_TPRO_PATH', plugin_dir_path( __FILE__ ) . 'src/' );
define( 'SP_TPRO_URL', plugin_dir_url( __FILE__ ) . 'src/' );
define( 'SP_TPRO_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1
 */
function sp_testimonial_pro() {
	new ShapedPlugin\TestimonialPro\Includes\TestimonialPRO();
}
if ( function_exists( 'sp_testimonial_pro' ) ) {
	sp_testimonial_pro();
}
