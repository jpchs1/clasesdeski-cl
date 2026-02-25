<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.3
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Sp_Testimonial_Pro_Gutenberg_Block' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class Sp_Testimonial_Pro_Gutenberg_Block {

		/**
		 * Block Initializer.
		 */
		public function __construct() {
			new GutenbergBlock\Sp_Testimonial_Pro_Gutenberg_Block_Init();
		}

	}
}
