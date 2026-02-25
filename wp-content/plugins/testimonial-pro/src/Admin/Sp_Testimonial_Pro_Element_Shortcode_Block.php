<?php
/**
 * Elementor shortcode block.
 *
 * @since      2.5.3
 * @package    testimonial_pro
 * @subpackage testimonial_pro/src/Admin
 */

namespace ShapedPlugin\TestimonialPro\Admin;

/**
 * Sp_Testimonial_Pro_Element_Shortcode_Block
 */
class Sp_Testimonial_Pro_Element_Shortcode_Block {
	/**
	 * Instance
	 *
	 * @since 2.5.3
	 *
	 * @access private
	 * @static
	 *
	 * @var Sp_Testimonial_Pro_Element_Shortcode_Block The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.5.3
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 2.5.3
	 *
	 * @access public
	 */
	public function __construct() {
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'sprtp_block_enqueue_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sprtp_element_block_icon' ) );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    2.5.3
	 * @return void
	 */
	public function sprtp_element_block_icon() {
		wp_enqueue_style( 'sprtp_element_block_icon', SP_TPRO_URL . 'Admin/assets/css/fontello.min.css', array(), SP_TPRO_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    2.5.3
	 */
	public function sprtp_block_enqueue_scripts() {
		// CSS Files.
		wp_enqueue_style( 'tpro-swiper' );
		wp_enqueue_style( 'tpro-bx_slider' );
		wp_enqueue_style( 'tpro-font-awesome' );
		wp_enqueue_style( 'tpro-magnific-popup' );
		wp_enqueue_style( 'tpro-remodal' );
		wp_enqueue_style( 'tpro-style' );
		wp_enqueue_style( 'tpro-form' );

		// JS Files.
		wp_enqueue_script( 'tpro-swiper-js' );
		wp_enqueue_script( 'tpro-bx_slider' );
		wp_enqueue_script( 'tpro-isotope-js' );
		wp_enqueue_script( 'tpro-validate-js' );
		wp_enqueue_script( 'tpro-magnific-popup-js' );
		wp_enqueue_script( 'tpro-remodal-js' );
		wp_enqueue_script( 'tpro-curtail-min-js' );
		wp_enqueue_script( 'tpro-chosen-jquery' );
		wp_enqueue_script( 'tpro-chosen-config' );
		wp_enqueue_script( 'tpro-recaptcha-js' );
		wp_enqueue_script( 'tpro-scripts' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.5.3
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {
		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.5.3
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 2.5.3
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register( new ElementAddons\Sp_Testimonial_Pro_Shortcode_Widget() );
	}

}

Sp_Testimonial_Pro_Element_Shortcode_Block::instance();
