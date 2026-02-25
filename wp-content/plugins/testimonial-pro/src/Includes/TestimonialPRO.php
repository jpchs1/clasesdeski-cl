<?php
/**
 * The Testimonial pro class handles core plugin hooks and action setup.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_pro.
 * @subpackage Testimonial_pro/Frontend.
 */

namespace ShapedPlugin\TestimonialPro\Includes;

use ShapedPlugin\TestimonialPro\Includes\SP_Testimonial_Pro_Functions;
use ShapedPlugin\TestimonialPro\Admin\Admin;
use ShapedPlugin\TestimonialPro\Admin\Sp_Testimonial_Pro_Gutenberg_Block;
use ShapedPlugin\TestimonialPro\Includes\License;
use ShapedPlugin\TestimonialPro\Includes\Import_Export;
use ShapedPlugin\TestimonialPro\Frontend\Frontend;
use ShapedPlugin\TestimonialPro\Admin\Sp_Testimonial_Pro_Element_Shortcode_Block;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @package real-testimonials-pro
 * @since 2.0
 */
class TestimonialPRO {
	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '2.5.1';

	/**
	 * Testimonial
	 *
	 * @var SP_TPRO_Testimonial $testimonial
	 */
	public $testimonial;

	/**
	 * Shortcode
	 *
	 * @var SP_TPRO_Shortcode $shortcode
	 */
	public $shortcode;

	/**
	 * Form
	 *
	 * @var SP_TPRO_Form $shortcode
	 */
	public $form;

	/**
	 * Taxonomy
	 *
	 * @var SP_TPRO_Taxonomy $taxonomy
	 */
	public $taxonomy;

	/**
	 * Help
	 *
	 * @var SP_TPRO_Help $help
	 */
	public $help;

	/**
	 * Router
	 *
	 * @var SP_TPRO_Router $router
	 */
	public $router;

	/**
	 * Plugin Instance
	 *
	 * @var null
	 * @since 2.0
	 */
	protected static $_instance = null;

	/**
	 * Instance
	 *
	 * @return TestimonialPRO
	 * @since 2.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * SP_Testimonial_PRO constructor.
	 */
	public function __construct() {

		new SP_Testimonial_Pro_Functions();
		// Initialize the filter hooks.
		$this->init_filters();

		// Initialize the action hooks.
		$this->init_actions();
		new Admin();
		new Frontend();
	}

	/**
	 * Initialize WordPress filter hooks
	 *
	 * @return void
	 */
	private function init_filters() {
		add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 2 );
		add_filter( 'manage_spt_testimonial_form_posts_columns', array( $this, 'add_testimonial_form_column' ) );
		add_filter( 'manage_spt_shortcodes_posts_columns', array( $this, 'add_shortcode_column' ) );
		add_filter( 'manage_spt_testimonial_posts_columns', array( $this, 'add_testimonial_column' ) );
		add_filter( 'plugin_row_meta', array( $this, 'after_testimonial_pro_row_meta' ), 10, 4 );
	}

	/**
	 * Initialize WordPress action hooks
	 *
	 * @return void
	 */
	private function init_actions() {
		add_action( 'manage_spt_testimonial_form_posts_custom_column', array( $this, 'add_testimonial_form_code' ), 10, 2 );
		add_action( 'manage_spt_shortcodes_posts_custom_column', array( $this, 'add_shortcode_form' ), 10, 2 );
		add_action( 'manage_spt_testimonial_posts_custom_column', array( $this, 'add_testimonial_extra_column' ), 10, 2 );

		// License Page.
		$manage_license = new License( SP_TESTIMONIAL_PRO_FILE, SP_TPRO_VERSION, 'ShapedPlugin', SP_TPRO_STORE_URL, SP_TPRO_ITEM_ID, SP_TPRO_ITEM_SLUG );

		// Admin Menu.
		add_action( 'admin_init', array( $manage_license, 'testimonial_pro_activate_license' ) );
		add_action( 'admin_init', array( $manage_license, 'testimonial_pro_deactivate_license' ) );

		add_action( 'testimonial_pro_weekly_scheduled_events', array( $manage_license, 'check_license_status' ) );
		$import_export = new Import_Export( SP_TPRO_ITEM_NAME, SP_TPRO_VERSION );

		add_action( 'wp_ajax_spt_export_shortcodes', array( $import_export, 'export_shortcodes' ) );
		add_action( 'wp_ajax_spt_import_shortcodes', array( $import_export, 'import_shortcodes' ) );

		// Init Updater.
		add_action( 'admin_init', array( $manage_license, 'init_updater' ), 0 );

		// Display notices to admins.
		add_action( 'admin_notices', array( $manage_license, 'license_active_notices' ) );
		add_action( 'in_plugin_update_message-' . SP_TPRO_BASENAME, array( $manage_license, 'plugin_row_license_missing' ), 10, 2 );

		// Redirect after active.
		add_action( 'activated_plugin', array( $this, 'redirect_to' ) );

		// Polylang plugin support for multi language support.
		if ( class_exists( 'Polylang' ) ) {
			add_filter( 'pll_get_post_types', array( $this, 'sp_tpro_testimonial_polylang' ), 10, 2 );
			add_filter( 'pll_get_taxonomies', array( $this, 'sp_tpro_cat_polylang' ), 10, 2 );
		}

		// Elementor shortcode block.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			new Sp_Testimonial_Pro_Element_Shortcode_Block();
		}
		/**
		 * Gutenberg block.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '5.3', '>=' ) ) {
			new Sp_Testimonial_Pro_Gutenberg_Block();
		}
		add_filter( 'spt_testimonial_pro_suppress_filters', array( $this, 'modify_suppress_filers' ), 10, 2 );

	}

	/**
	 * Taxonomy polylang.
	 *
	 * @param  array $taxonomies taxonomies.
	 * @param  bool  $is_settings is_settings.
	 * @return array
	 */
	public function sp_tpro_cat_polylang( $taxonomies, $is_settings ) {
		if ( $is_settings ) {
			unset( $taxonomies['testimonial_cat'] );
		} else {
			$taxonomies['testimonial_cat'] = 'testimonial_cat';
		}
		return $taxonomies;
	}

	/**
	 * Testimonial polylang.
	 *
	 * @param  array $post_types post type.
	 * @param  bool  $is_settings is_settings.
	 * @return array
	 */
	public function sp_tpro_testimonial_polylang( $post_types, $is_settings ) {
		if ( $is_settings ) {
			// hides 'spt_testimonial,spt_shortcodes' from the list of custom post types in Polylang settings.
			unset( $post_types['spt_testimonial'] );
			unset( $post_types['spt_shortcodes'] );
		} else {
			// enables language and translation management for 'spt_testimonial,spt_shortcodes'.
			$post_types['spt_testimonial'] = 'spt_testimonial';
			$post_types['spt_shortcodes']  = 'spt_shortcodes';
		}
		return $post_types;
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name name.
	 * @param string|bool $value value.
	 */
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Add plugin action menu
	 *
	 * @param array  $links links.
	 * @param string $file file.
	 *
	 * @return array
	 */
	public function add_plugin_action_links( $links, $file ) {

		$manage_license     = new License( SP_TESTIMONIAL_PRO_FILE, SP_TPRO_VERSION, 'ShapedPlugin', SP_TPRO_STORE_URL, SP_TPRO_ITEM_ID, SP_TPRO_ITEM_SLUG );
		$license_key_status = $manage_license->get_license_status();
		$license_status     = ( is_object( $license_key_status ) ? $license_key_status->license : '' );

		if ( SP_TPRO_BASENAME === $file ) {
			if ( 'valid' === $license_status ) {
				$new_links = array(
					sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=spt_shortcodes' ), __( 'Manage Views', 'testimonial-pro' ) ),
				);
			} else {
				$new_links = array(
					sprintf( '<a style="color: red; font-weight: 600;" href="%s">%s</a>', admin_url( 'edit.php?post_type=spt_testimonial&page=tpro_settings#tab=license-key' ), __( 'Activate license', 'testimonial-pro' ) ),
				);
			}
			return array_merge( $new_links, $links );
		}

		return $links;
	}

	/**
	 * Add plugin row meta link.
	 *
	 * @since 2.0
	 *
	 * @param array $plugin_meta meta.
	 * @param mix   $file file.
	 *
	 * @return array
	 */
	public function after_testimonial_pro_row_meta( $plugin_meta, $file ) {
		if ( SP_TPRO_BASENAME === $file ) {
			$plugin_meta[] = '<a href="https://shapedplugin.com/support/" target="_blank">' . __( 'Support', 'testimonial-pro' ) . '</a>';
			$plugin_meta[] = '<a href="https://demo.shapedplugin.com/testimonial/" target="_blank">' . __( 'Live Demo', 'testimonial-pro' ) . '</a>';
		}

		return $plugin_meta;
	}

	/**
	 * ShortCode Column
	 *
	 * @return array
	 */
	public function add_shortcode_column() {
		$new_columns['cb']        = '<input type="checkbox" />';
		$new_columns['title']     = __( 'Title', 'testimonial-pro' );
		$new_columns['shortcode'] = __( 'Shortcode', 'testimonial-pro' );
		$new_columns['']          = '';
		$new_columns['date']      = __( 'Date', 'testimonial-pro' );

		return $new_columns;
	}

	/**
	 * Testimonial Form Column
	 *
	 * @return array
	 */
	public function add_testimonial_form_column() {
		$new_columns['cb']        = '<input type="checkbox" />';
		$new_columns['title']     = __( 'Title', 'testimonial-pro' );
		$new_columns['status']    = __( 'Status', 'testimonial-pro' );
		$new_columns['shortcode'] = __( 'Shortcode', 'testimonial-pro' );
		$new_columns['']          = '';
		$new_columns['date']      = __( 'Date', 'testimonial-pro' );

		return $new_columns;
	}

	/**
	 * Shortcode code.
	 *
	 * @param array  $column column.
	 * @param string $post_id id.
	 */
	public function add_shortcode_form( $column, $post_id ) {

		switch ( $column ) {

			case 'shortcode':
				$column_field = '<input  class="stpro_input" style="width: 230px;padding: 4px 8px;" type="text"  readonly="readonly" value="[sp_testimonial id=&quot;' . $post_id . '&quot;]"/><div class="sptpro-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>';
				echo $column_field;
				break;

		} // end switch

	}

	/**
	 * Testimonial form code.
	 *
	 * @param array  $column column.
	 * @param string $post_id id.
	 * @return void
	 */
	public function add_testimonial_form_code( $column, $post_id ) {
		$form_data                   = get_post_meta( $post_id, 'sp_tpro_form_options', true );
		$testimonial_approval_status = isset( $form_data['testimonial_approval_status'] ) ? $form_data['testimonial_approval_status'] : '';
		switch ( $column ) {

			case 'status':
				switch ( $testimonial_approval_status ) {
					case 'publish':
						$color = '#018a01';
						break;
					case 'pending':
						$color = '#ce0505';
						break;
					case 'private':
						$color = '#059ece';
						break;
					case 'draft':
						$color = '#ababab';
						break;
					default:
						$color = '#ababab';
				}
				$column_field = '<span style="color: ' . $color . ';">' . ucfirst( $testimonial_approval_status ) . '</span>';
				echo wp_kses_post( $column_field );
				break;
			case 'shortcode':
				$column_field = '<input class="stpro_input" style="width: 230px;padding: 4px 8px;cursor: pointer;" type="text" onClick="this.select();" readonly="readonly" value="[sp_testimonial_form id=&quot;' . esc_attr( $post_id ) . '&quot;]"/> <div class="sptpro-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>';
				echo $column_field;
				break;

		} // end switch

	}

	/**
	 * Testimonial Column
	 *
	 * @return array
	 */
	public function add_testimonial_column() {
		$new_columns['cb']                       = '<input type="checkbox" />';
		$new_columns['title']                    = __( 'Title', 'testimonial-pro' );
		$new_columns['image']                    = __( 'Image', 'testimonial-pro' );
		$new_columns['name']                     = __( 'Name', 'testimonial-pro' );
		$new_columns['taxonomy-testimonial_cat'] = __( 'Groups', 'testimonial-pro' );
		$new_columns['rating']                   = __( 'Rating', 'testimonial-pro' );
		$new_columns['']                         = '';
		$new_columns['date']                     = __( 'Date', 'testimonial-pro' );

		return $new_columns;
	}

	/**
	 * Testimonial extra column.
	 *
	 * @param array  $column column.
	 * @param string $post_id id.
	 */
	public function add_testimonial_extra_column( $column, $post_id ) {

		switch ( $column ) {

			case 'rating':
				$testimonial_data = get_post_meta( $post_id, 'sp_tpro_meta_options', true );
				if ( isset( $testimonial_data['tpro_rating'] ) ) {
					$rating_star = $testimonial_data['tpro_rating'];
					$fill_star   = '<i style="color: #f3bb00;" class="fa fa-star"></i>';
					$empty_star  = '<i class="fa fa-star"></i>';
					switch ( $rating_star ) {
						case 'one_star':
							$column_field = '<span style="font-size: 16px; color: #d4d4d4;">' . $fill_star . $empty_star . $empty_star . $empty_star . $empty_star . '</span>';
							break;
						case 'two_star':
							$column_field = '<span style="font-size: 16px; color: #d4d4d4;">' . $fill_star . $fill_star . $empty_star . $empty_star . $empty_star . '</span>';
							break;
						case 'three_star':
							$column_field = '<span style="font-size: 16px; color: #d4d4d4;">' . $fill_star . $fill_star . $fill_star . $empty_star . $empty_star . '</span>';
							break;
						case 'four_star':
							$column_field = '<span style="font-size: 16px; color: #d4d4d4;">' . $fill_star . $fill_star . $fill_star . $fill_star . $empty_star . '</span>';
							break;
						case 'five_star':
							$column_field = '<span style="font-size: 16px; color: #d4d4d4;">' . $fill_star . $fill_star . $fill_star . $fill_star . $fill_star . '</span>';
							break;
						default:
							$column_field = '<span aria-hidden="true">—</span>';
							break;
					}

					echo wp_kses_post( $column_field );
				}

				break;
			case 'image':
				add_image_size( 'sp_tpro_client_small_img', 50, 50, true );

				$thumb_id                 = get_post_thumbnail_id( $post_id );
				$testimonial_client_image = wp_get_attachment_image_src( $thumb_id, 'sp_tpro_client_small_img' );
				if ( '' !== $testimonial_client_image && is_array( $testimonial_client_image ) ) {
					echo '<img  src="' . esc_url( $testimonial_client_image[0] ) . '" width="' . esc_attr( $testimonial_client_image[1] ) . '"  height="' . esc_attr( $testimonial_client_image[2] ) . '"/>';
				} else {
					echo '<span aria-hidden="true">—</span>';
				}
				break;
			default:
				break;
			case 'name':
				$testimonial_data = get_post_meta( $post_id, 'sp_tpro_meta_options', true );
				if ( isset( $testimonial_data['tpro_name'] ) ) {
					$testimonial_client_name = $testimonial_data['tpro_name'];
					if ( '' !== $testimonial_client_name ) {
						echo wp_kses_post( $testimonial_client_name );
					} else {
						echo '<span aria-hidden="true">—</span>';
					}
				}
				break;

		} // end switch

	}

	/**
	 * Redirect after active.
	 *
	 * @param Mix $plugin plugins.
	 * @return void
	 */
	public function redirect_to( $plugin ) {
		$manage_license     = new License( SP_TESTIMONIAL_PRO_FILE, SP_TPRO_VERSION, 'ShapedPlugin', SP_TPRO_STORE_URL, SP_TPRO_ITEM_ID, SP_TPRO_ITEM_SLUG );
		$license_key_status = $manage_license->get_license_status();
		$license_status     = ( is_object( $license_key_status ) ? $license_key_status->license : '' );
		deactivate_plugins( 'testimonial-free/testimonial-free.php' );
		if ( SP_TPRO_BASENAME === $plugin && 'valid' !== $license_status ) {
			exit( wp_safe_redirect( esc_url( admin_url( 'edit.php?post_type=spt_testimonial&page=tpro_settings#tab=license-key' ) ) ) );
		}
	}

	/**
	 * This function modifies the value of $suppress_filers based on AJAX request status.
	 *
	 * @param boolean $suppress_filers The original value of $suppress_filers.
	 * @param int     $post_id The ID of the post.
	 * @return boolean The modified value of $suppress_filers
	 */
	public function modify_suppress_filers( $suppress_filers, $post_id ) {
		// Check if the code is being executed during an AJAX request.
		$suppress_filers = defined( 'DOING_AJAX' ) && DOING_AJAX ? true : false;

		// Return the modified value of $suppress_filers.
		return $suppress_filers;
	}

}
