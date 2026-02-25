<?php
/**
 * The Frontend class to manage all public-facing functionality of the plugin.
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Frontend;

use ShapedPlugin\TestimonialPro\Includes\License;
use ShapedPlugin\TestimonialPro\Frontend\Helper;
use ShapedPlugin\TestimonialPro\Frontend\OldForm;
/**
 * The Frontend class to manage all public facing stuffs.
 *
 * @since 2.1.0
 */
class Frontend {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'front_styles' ) );
		add_action( 'wp_loaded', array( $this, 'register_front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_front_scripts' ) );
		add_shortcode( 'testimonial_pro', array( $this, 'shortcode_render' ) );
		add_shortcode( 'sp_testimonial', array( $this, 'shortcode_render' ) );

		add_shortcode( 'sp_testimonial_form', array( $this, 'frontend_form' ) );
		add_action( 'save_post', array( $this, 'delete_page_testimonial_option_on_save' ) );
		// Ajax Search Member.
		add_action( 'wp_ajax_search_testimonial', array( $this, 'search_testimonial' ) );
		add_action( 'wp_ajax_nopriv_search_testimonial', array( $this, 'search_testimonial' ) );
		// Old Testimonial form.
		new OldForm();
	}
	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_testimonial_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_testimonial_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} else {
			if ( get_option( 'sp_testimonial_page_id' . $post_ID ) ) {
				delete_option( 'sp_testimonial_page_id' . $post_ID );
			}
		}
		if ( get_option( 'sp_testimonial_page_id0' ) ) {
			delete_option( 'sp_testimonial_page_id0' );
		}

	}
	/**
	 * Plugin all Scripts and Styles registering.
	 *
	 * @return void
	 */
	public function register_front_scripts() {
		$setting_options                = get_option( 'sp_testimonial_pro_options' );
		$dequeue_swiper_css             = isset( $setting_options['tpro_dequeue_swiper_css'] ) ? $setting_options['tpro_dequeue_swiper_css'] : true;
		$dequeue_bx_css                 = isset( $setting_options['tpro_dequeue_bx_css'] ) ? $setting_options['tpro_dequeue_bx_css'] : true;
		$dequeue_font_awesome_css       = isset( $setting_options['tpro_dequeue_fa_css'] ) ? $setting_options['tpro_dequeue_fa_css'] : true;
		$dequeue_magnific_popup_css     = isset( $setting_options['tpro_dequeue_magnific_popup_css'] ) ? $setting_options['tpro_dequeue_magnific_popup_css'] : true;
		$tpro_dequeue_magnific_popup_js = isset( $setting_options['tpro_dequeue_magnific_popup_js'] ) ? $setting_options['tpro_dequeue_magnific_popup_js'] : true;
		$dequeue_swiper_js              = isset( $setting_options['tpro_dequeue_swiper_js'] ) ? $setting_options['tpro_dequeue_swiper_js'] : true;
		$dequeue_bx_js                  = isset( $setting_options['tpro_dequeue_bx_js'] ) ? $setting_options['tpro_dequeue_bx_js'] : true;
		$dequeue_isotope_js             = isset( $setting_options['tpro_dequeue_isotope_js'] ) ? $setting_options['tpro_dequeue_isotope_js'] : true;

		// CSS Files.
		if ( $dequeue_swiper_css ) {
			wp_register_style( 'tpro-swiper', SP_TPRO_URL . 'Frontend/assets/css/swiper.min.css', array(), SP_TPRO_VERSION );
		}
		if ( $dequeue_bx_css ) {
			wp_register_style( 'tpro-bx_slider', SP_TPRO_URL . 'Frontend/assets/css/bxslider.min.css', array(), SP_TPRO_VERSION );
		}
		if ( $dequeue_font_awesome_css ) {
			wp_register_style( 'tpro-font-awesome', SP_TPRO_URL . 'Frontend/assets/css/font-awesome.min.css', array(), SP_TPRO_VERSION );
		}
		if ( $dequeue_magnific_popup_css ) {
			wp_register_style( 'tpro-magnific-popup', SP_TPRO_URL . 'Frontend/assets/css/magnific-popup.min.css', array(), SP_TPRO_VERSION );
		}
		wp_register_style( 'tpro-remodal', SP_TPRO_URL . 'Frontend/assets/css/remodal.min.css', array(), SP_TPRO_VERSION );
		wp_register_style( 'tpro-style', SP_TPRO_URL . 'Frontend/assets/css/style.min.css', array(), SP_TPRO_VERSION );
		wp_register_style( 'tpro-form', SP_TPRO_URL . 'Frontend/assets/css/form.min.css', array(), SP_TPRO_VERSION );

		// JS Files.
		if ( $dequeue_swiper_js ) {
			wp_register_script( 'tpro-swiper-js', SP_TPRO_URL . 'Frontend/assets/js/swiper.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		if ( $dequeue_isotope_js ) {
			wp_register_script( 'tpro-isotope-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.isotope.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		wp_register_script( 'tpro-validate-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.validate.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		if ( $tpro_dequeue_magnific_popup_js ) {
			wp_register_script( 'tpro-magnific-popup-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		wp_register_script( 'tpro-remodal-js', SP_TPRO_URL . 'Frontend/assets/js/remodal.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		if ( $dequeue_bx_js ) {
			wp_register_script( 'tpro-bx_slider', SP_TPRO_URL . 'Frontend/assets/js/bxslider.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		wp_register_script( 'tpro-curtail-min-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.curtail.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-chosen-jquery', SP_TPRO_URL . 'Frontend/assets/js/chosen.jquery.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-chosen-config', SP_TPRO_URL . 'Frontend/assets/js/form-config.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-recaptcha-js', '//www.google.com/recaptcha/api.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-scripts', SP_TPRO_URL . 'Frontend/assets/js/scripts.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-thumbnail-js', SP_TPRO_URL . 'Frontend/assets/js/thumbnail-slide.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_localize_script(
			'tpro-scripts',
			'testimonial_vars',
			array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'sp-testimonial-nonce' ),
				'not_found' => __( ' No result found ', 'testimonial-pro' ),
			)
		);
	}

	/**
	 * Plugin Styles.
	 *
	 * @return void
	 */
	public function front_styles() {
		$get_page_data      = Helper::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		if ( $found_generator_id ) {
			wp_enqueue_style( 'tpro-remodal' );
			wp_enqueue_style( 'tpro-swiper' );
			wp_enqueue_style( 'tpro-bx_slider' );
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_style( 'tpro-style' );

			// Load dynamic style.
			$dynamic_style = Helper::load_dynamic_style( $found_generator_id );
			// Google font link enqueue.
			$enqueue_fonts = Helper::load_google_fonts( $dynamic_style['typography'] );
			wp_add_inline_style( 'tpro-style', $dynamic_style['dynamic_css'] );
			/**
			 * Google font link enqueue
			 */
			if ( ! empty( $enqueue_fonts ) ) {
				wp_enqueue_style( 'sp-tpro-google-fonts', 'https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ), array(), SP_TPRO_VERSION );
			}
		}
	}

	/**
	 * Plugin Scripts and Styles for live preview.
	 *
	 * @return void
	 */
	public function admin_front_scripts() {
		// CSS Files.
		$wpscreen = get_current_screen();
		if ( 'spt_testimonial_form' === $wpscreen->post_type || 'spt_shortcodes' === $wpscreen->post_type || 'spt_testimonial' === $wpscreen->post_type ) {
			wp_enqueue_style( 'tpro-swiper' );
			wp_enqueue_style( 'tpro-bx_slider' );
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_style( 'tpro-form' );
			wp_enqueue_style( 'tpro-remodal' );
			wp_enqueue_style( 'tpro-style' );

			// JS Files.
			wp_enqueue_script( 'tpro-swiper-js' );
			wp_enqueue_script( 'tpro-bx_slider' );
			wp_enqueue_script( 'tpro-isotope-js' );
			wp_enqueue_script( 'tpro-validate-js' );
			wp_enqueue_script( 'tpro-magnific-popup-js' );
			wp_enqueue_script( 'tpro-curtail-min-js' );
			wp_enqueue_script( 'tpro-chosen-jquery' );
			wp_enqueue_script( 'tpro-chosen-config' );
			wp_enqueue_script( 'tpro-recaptcha-js' );
			wp_enqueue_script( 'jquery-masonry' );
			wp_enqueue_script( 'tpro-scripts' );
			wp_localize_script(
				'tpro-scripts',
				'testimonial_vars',
				array(
					'ajax_url'  => admin_url( 'admin-ajax.php' ),
					'nonce'     => wp_create_nonce( 'sp-testimonial-nonce' ),
					'not_found' => __( ' No result found ', 'testimonial-pro' ),
				)
			);

		}
	}

	/**
	 * Shortcode render.
	 *
	 * @param  array $attributes id.
	 * @return statement
	 * @since 2.0
	 */
	public function shortcode_render( $attributes ) {
		$manage_license     = new License( SP_TESTIMONIAL_PRO_FILE, SP_TPRO_VERSION, 'ShapedPlugin', SP_TPRO_STORE_URL, SP_TPRO_ITEM_ID, SP_TPRO_ITEM_SLUG );
		$license_key_status = $manage_license->get_license_status();
		$license_status     = ( is_object( $license_key_status ) && $license_key_status ? $license_key_status->license : '' );
		$if_license_status  = array( 'valid', 'expired' );
		$first_version      = get_option( 'testimonial_pro_first_version' );
		if ( ! in_array( $license_status, $if_license_status ) && 1 === version_compare( $first_version, '2.2.3' ) ) {
			$activation_notice = '';
			if ( current_user_can( apply_filters( 'testimonial_pro_user_role_permission', 'manage_options' ) ) ) {
				$activation_notice = sprintf(
					'<div class="testimonial-pro-license-notice" style="background: #ffebee;color: #444;padding: 18px 16px;border: 1px solid #d0919f;border-radius: 4px;font-size: 18px;line-height: 28px;">Please <strong><a href="%1$s">activate</a></strong> the license key to get the output of the <strong>Real Testimonials Pro</strong> plugin.</div>',
					esc_url( admin_url( 'edit.php?post_type=spt_testimonial&page=tpro_settings#tab=license-key' ) )
				);
			}
			return $activation_notice;
		}

		$post_id = esc_attr( (int) $attributes['id'] );
		// Check the shortcode status.
		if ( empty( $post_id ) || 'spt_shortcodes' !== get_post_type( $post_id ) || 'trash' === get_post_status( $post_id ) ) {
			return;
		}

		ob_start();
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		$get_page_data      = Helper::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		// This shortcode id not in page id option. Enqueue stylesheets in the shortcode.
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $post_id, $found_generator_id ) ) {
			wp_enqueue_style( 'tpro-swiper' );
			wp_enqueue_style( 'tpro-bx_slider' );
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_style( 'tpro-remodal' );
			wp_enqueue_style( 'tpro-style' );

			// Load dynamic style.
			$dynamic_style = Helper::load_dynamic_style( $post_id, $shortcode_data, $setting_options );
			echo '<style id="sp_testimonial_dynamic_css' . esc_attr( $post_id ) . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';
			// Google font link enqueue.
			$enqueue_fonts = Helper::load_google_fonts( $dynamic_style['typography'] );
			if ( ! empty( $enqueue_fonts ) ) {
				wp_enqueue_style( 'sp-tpro-google-fonts' . esc_attr( $post_id ), 'https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ), array(), SP_TPRO_VERSION );
			}
		}

		// Update options if the existing shortcode id option not found.
		Helper::db_options_update( $post_id, $get_page_data );
		$main_section_title = get_the_title( $post_id );
		Helper::sp_tpro_html_show( $post_id, $setting_options, $shortcode_data, $main_section_title );
		return Helper::minify_output( ob_get_clean() );
	}

	/**
	 * Ajax-Search functionality of the plugin.
	 *
	 * @return statement
	 */
	public function search_testimonial() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( wp_unslash( $nonce ), 'sp-testimonial-nonce' ) ) {
			return false;
		}
		$page  = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
		$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
		$group = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		if ( 'all' === $group ) {
			$group = '';
		}
		$post_id                   = isset( $_POST['generator_id'] ) ? sanitize_text_field( wp_unslash( $_POST['generator_id'] ) ) : '';
		$setting_options           = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data            = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
		$grid_pagination           = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
		$layout                    = isset( $shortcode_data['layout'] ) ? $shortcode_data['layout'] : 'slider';
		$testimonial_query_and_ids = Helper::testimonial_query( $shortcode_data, $post_id, $value, $group, $page );
		$testimonial_items         = Helper::testimonial_items( $testimonial_query_and_ids, $shortcode_data, $layout, $post_id );
		echo $testimonial_items['output'];
		$post_query = $testimonial_query_and_ids['query'];
		echo "<div class='filter-pagination hidden'>";
		if ( $grid_pagination && $post_query->max_num_pages > 1 ) {
			$paged_var  = 'paged' . $post_id;
			$args       = array(
				'format'    => '?paged' . $post_id . '=%#%',
				'total'     => $post_query->max_num_pages,
				'current'   => isset( $_GET[ "$paged_var" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$paged_var" ] ) ) : 1,
				'prev_next' => false,
				'next_text' => '<i class="fa fa-angle-right"></i>',
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'type'      => 'array',
				'show_all'  => true,
			);
			$page_links = (array) paginate_links( $args );
			$prev_link  = '<a class="next page-numbers" href="#"><i class="fa fa-angle-right"></i></a>';
			$next_link  = '<a class="prev page-numbers current" href="#"><i class="fa fa-angle-left"></i></a>';
			array_unshift( $page_links, $next_link );
			$page_links[] = $prev_link;
			$html         = '';
			$p_num        = 0;
			foreach ( $page_links as $page_link ) {
				$class = 'page-numbers ';
				if ( strpos( $page_link, 'current' ) !== false ) {
					$class .= 'current ';
				}
				if ( strpos( $page_link, 'next' ) !== false ) {
					$data_page = 'data-page="next"';
					$class    .= 'next ';
				} elseif ( strpos( $page_link, 'prev' ) !== false ) {
					$data_page = 'data-page="prev"';
					$class    .= ' prev';
				} else {
					$data_page = 'data-page="' . $p_num . '"';
				}
				$page_link = preg_replace( '/<span[^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = preg_replace( '/<a [^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = str_replace( '</span>', '</a>', $page_link );
				$html     .= $page_link;
				$p_num++;
			}
			echo $html;
			echo '</div>';
		}
			wp_die();
	}

	/**
	 * Frontend form.
	 *
	 * @param array $attributes attributes.
	 *
	 * @return string
	 * @since 2.0
	 */
	public function frontend_form( $attributes ) {
		$form_id = esc_attr( (int) $attributes['id'] );
		if ( empty( $form_id ) || 'spt_testimonial_form' !== get_post_type( $form_id ) || 'trash' === get_post_status( $form_id ) ) {
			return;
		}
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$form_elements   = get_post_meta( $form_id, 'sp_tpro_form_elements_options', true );
		$form_data       = get_post_meta( $form_id, 'sp_tpro_form_options', true );
		if ( ! is_array( $form_data ) ) {
			return;
		}
		wp_enqueue_script( 'tpro-validate-js' );
		ob_start();
		Helper::frontend_form_html( $form_id, $setting_options, $form_elements, $form_data );
		return ob_get_clean();
	}
}
