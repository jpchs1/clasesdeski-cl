<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.howardehrenberg.com
 * @since      1.0.0
 *
 * @package    Owl_Carousel_2
 * @subpackage Owl_Carousel_2/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Owl_Carousel_2
 * @subpackage Owl_Carousel_2/includes
 * @author     Howard Ehrenberg <howard@howardehrenberg.com>
 */
class Owl_Carousel_2_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-owl-carousel-2-admin.php';   
        
        Owl_Carousel_2_Admin::add_carousel_cpt();
        flush_rewrite_rules();
	}

}
