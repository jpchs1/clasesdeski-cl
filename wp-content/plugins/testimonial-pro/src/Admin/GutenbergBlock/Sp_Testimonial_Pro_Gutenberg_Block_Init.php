<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.3
 *
 * @package    testimonial_pro
 * @subpackage testimonial_pro/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Admin\GutenbergBlock;

use ShapedPlugin\TestimonialPro\Frontend\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Sp_Testimonial_Pro_Gutenberg_Block_Init' ) ) {
	/**
	 * Sp_Testimonial_Pro_Gutenberg_Block_Init class.
	 */
	class Sp_Testimonial_Pro_Gutenberg_Block_Init {
		/**
		 * Script and style suffix
		 *
		 * @since 2.5.3
		 * @access protected
		 * @var string
		 */
		protected $suffix;
		/**
		 * Custom Gutenberg Block Initializer.
		 */
		public function __construct() {
			$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
			add_action( 'init', array( $this, 'sptp_gutenberg_shortcode_block' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'sptp_block_editor_assets' ) );
		}

		/**
		 * Register block editor script for backend.
		 */
		public function sptp_block_editor_assets() {
			wp_enqueue_script(
				'sp-testimonial-pro-shortcode-block',
				plugins_url( '/GutenbergBlock/build/index.js', dirname( __FILE__ ) ),
				array( 'jquery' ),
				SP_TPRO_VERSION,
				true
			);

			/**
			 * Register block editor css file enqueue for backend.
			 */
			wp_enqueue_style( 'tpro-swiper' );
			wp_enqueue_style( 'tpro-bx_slider' );
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_style( 'tpro-remodal' );
			wp_enqueue_style( 'tpro-style' );
			wp_enqueue_style( 'tpro-form' );
		}
		/**
		 * Shortcode list.
		 *
		 * @return array
		 */
		public function sptp_post_list() {
			$shortcodes = get_posts(
				array(
					'post_type'      => 'spt_shortcodes',
					'post_status'    => 'publish',
					'posts_per_page' => 9999,
				)
			);

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			return array_map(
				function ( $shortcode ) {
						return (object) array(
							'id'    => absint( $shortcode->ID ),
							'title' => esc_html( $shortcode->post_title ),
						);
				},
				$shortcodes
			);
		}

		/**
		 * Register Gutenberg shortcode block.
		 */
		public function sptp_gutenberg_shortcode_block() {
			/**
			 * Register block editor js file enqueue for backend.
			 */
			wp_register_script( 'tpro-block-loader-js', SP_TPRO_URL . 'Admin/assets/js/blockloader.js', array( 'jquery' ), SP_TPRO_VERSION, true );
			wp_register_script( 'tpro-scripts', SP_TPRO_URL . 'Frontend/assets/js/scripts.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
			wp_localize_script(
				'tpro-scripts',
				'sp_testimonial_pro',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'url'           => esc_url( SP_TPRO_URL ),
					'loadRemodal'   => esc_url( SP_TPRO_URL . 'Frontend/assets/js/remodal.min.js' ),
					'loadThumb'     => esc_url( SP_TPRO_URL . 'Frontend/assets/js/thumbnail-slide.js' ),
					'loadScript'    => esc_url( SP_TPRO_URL . 'Frontend/assets/js/scripts.min.js' ),
					'link'          => esc_url( admin_url( 'post-new.php?post_type=spt_shortcodes' ) ),
					'shortCodeList' => $this->sptp_post_list(),
				)
			);
			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'sp-testimonial-pro/shortcode',
				array(
					'attributes'      => array(
						'shortcode'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'showInputShortcode' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					// Enqueue blocks.editor.build.js in the editor only.
					'editor_script'   => array(
						'tpro-swiper-js',
						'tpro-isotope-js',
						'tpro-validate-js',
						'tpro-magnific-popup-js',
						'tpro-remodal-js',
						'tpro-curtail-min-js',
						'tpro-chosen-jquery',
						'tpro-chosen-config',
						'tpro-recaptcha-js',
						'tpro-scripts',
						'tpro-swiper-active',
						'tpro-bx_slider',
						'tpro-thumbnail-js',
						'tpro-block-loader-js',
					),
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'    => array(),
					'render_callback' => array( $this, 'sp_testimonial_pro_render_shortcode' ),
				)
			);
		}

		/**
		 * Render callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function sp_testimonial_pro_render_shortcode( $attributes ) {

			$class_name = '';
			if ( ! empty( $attributes['className'] ) ) {
				$class_name = $attributes['className'];
			}

			if ( ! $attributes['is_admin'] ) {
				return '<div class="' . esc_attr( $class_name ) . '">' . do_shortcode( '[sp_testimonial id="' . sanitize_text_field( esc_attr( $attributes['shortcode'] ) ) . '"]' ) . '</div>';
			}
			$post_id         = (int) $attributes['shortcode'];
			$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
			$setting_options = get_option( 'sp_testimonial_pro_options' );
			// Load Google font for the existing shortcode id.
			$dynamic_style    = Helper::load_dynamic_style( $post_id, $shortcode_data, $setting_options );
			$enqueue_fonts    = Helper::load_google_fonts( $dynamic_style['typography'] );
			$load_google_font = '';
			// Google font link enqueue.
			if ( ! empty( $enqueue_fonts ) ) {
				$load_google_font .= '<link rel="stylesheet" href="' . esc_url( 'https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ) ) . '" media="all">';
			}

			$edit_page_link = get_edit_post_link( esc_attr( $post_id ) );
			return $load_google_font . '<div id="' . uniqid() . '" class="' . esc_attr( $class_name ) . '"><a href="' . esc_url( $edit_page_link ) . '" target="_blank" class="sp_testimonial_block_edit_button">Edit view</a>' . do_shortcode( '[sp_testimonial id="' . esc_attr( $post_id ) . '"]' ) . '</div>';
		}
	}
}
