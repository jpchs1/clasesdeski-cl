<?php
/**
 * The admin backend preview.
 *
 * @link        https://shapedplugin.com/
 * @since      2.4.0
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 */

namespace ShapedPlugin\TestimonialPro\Admin\Preview;

use ShapedPlugin\TestimonialPro\Frontend\Helper;
/**
 * The admin preview.
 */
class Preview {

	/**
	 * Script and style suffix
	 *
	 * @since 2.4.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.4.0
	 */
	public function __construct() {
		$this->pcp_preview_action();
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function pcp_preview_action() {
		// Admin Preview.
		add_action( 'wp_ajax_sp_tpro_preview_meta_box', array( $this, 'testimonial_pro_backend_preview' ) );
		// Admin Preview for testimonial form.
		add_action( 'wp_ajax_sp_testimonial_form_preview', array( $this, 'testimonial_pro_form_backend_preview' ) );
	}

	/**
	 * Function Backed preview.
	 *
	 * @since 2.4.0
	 */
	public function testimonial_pro_backend_preview() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';// phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'spftestimonial_metabox_nonce' ) ) {
			return;
		}
		$setting = array();
		// XSS ok.
		// No worries, This "POST" requests is sanitizing in the below array map.
		$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] )  : ''; // phpcs:ignore
		parse_str( $data, $setting );
		// Preset Layouts.
		$post_id            = $setting['post_ID'];
		$setting_options    = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data     = $setting['sp_tpro_shortcode_options'];
		$main_section_title = $setting['post_title'];
		// External style and typography include for the backend preview.
		$dynamic_style = Helper::load_dynamic_style( $post_id, $shortcode_data, $setting_options );
		$enqueue_fonts = Helper::load_google_fonts( $dynamic_style['typography'] );
		// Google font link enqueue.
		if ( ! empty( $enqueue_fonts ) ) {
			echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ) . '" media="all">';
		}
		echo '<style>' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';
		Helper::sp_tpro_html_show( $post_id, $setting_options, $shortcode_data, $main_section_title );
		?>
		<script src="<?php echo esc_url( SP_TPRO_URL . 'Frontend/assets/js/remodal.min.js' ); ?>" ></script>
		<script src="<?php echo esc_url( SP_TPRO_URL . 'Frontend/assets/js/scripts.min.js' ); ?>" ></script>
		<script src="<?php echo esc_url( SP_TPRO_URL . 'Frontend/assets/js/thumbnail-slide.min.js' ); ?>" ></script>
		<?php
		die();
	}

	/**
	 * Function Backed preview for form.
	 *
	 * @since 2.4.0
	 */
	public function testimonial_pro_form_backend_preview() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';// phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'spftestimonial_metabox_nonce' ) ) {
			return;
		}
		$setting = array();
		// XSS ok.
		// No worries, This "POST" requests is sanitizing in the below array map.
		$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] )  : ''; // phpcs:ignore
		parse_str( $data, $setting );
		// Preset Layouts.
		$form_id         = $setting['post_ID'];
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$form_elements   = $setting['sp_tpro_form_elements_options'];
		$form_data       = $setting['sp_tpro_form_options'];
		?>
		<style>
		.post-type-spt_testimonial_form .spftestimonial-section form#testimonial_form {
			margin: auto;
			display: table;
			max-width: 100%;
			width: 450px;
		}
		.post-type-spt_testimonial_form .spftestimonial-section input#submit {
			border-style: solid;
			border-top-width: 0;
			border-right-width: 0;
			border-left-width: 0;
			border-bottom-width: 0;
			border-radius: 2px;
			padding-top: 15px;
			padding-right: 30px;
			padding-bottom: 15px;
			padding-left: 30px;
			font-family: inherit;
			font-weight: inherit;
			line-height: 1;
			-webkit-border-radius: 2px;
			-moz-border-radius: 2px;
			-ms-border-radius: 2px;
			-o-border-radius: 2px;
		}
		.post-type-spt_testimonial_form .spftestimonial-section input[type=email],
		.post-type-spt_testimonial_form .spftestimonial-section input[type=text] {
			width: 100%;
			max-width: 450px;
			border: 1px solid #ddd;
			padding: 8px 14px;
			border-radius: 4px;
			font-size: 14px;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			-ms-border-radius: 4px;
			-o-border-radius: 4px;
			background: #fafafa;
			box-shadow: none;
			box-sizing: border-box;
			transition: all .2s linear;
			line-height: 1;
		}
		.post-type-spt_testimonial_form .spftestimonial-section label {
			margin-bottom: 5px;
		}

		.post-type-spt_testimonial_form select {
			color: #666;
			height: auto;
			border-width: 1px;
			border-style: solid;
			border-color: #eaeaea;
			border-radius: 2px;
			box-shadow: none;
			box-sizing: border-box;
			transition: all .2s linear;
			-webkit-border-radius: 2px;
			-moz-border-radius: 2px;
			-ms-border-radius: 2px;
			-o-border-radius: 2px;
		}
		.post-type-spt_testimonial_form #sp_tpro_form_live_preview .handle-order-higher,
		.post-type-spt_shortcodes #sp_tpro_live_preview .handle-order-higher,
		.post-type-spt_shortcodes #sp_tpro_live_preview .handle-order-lower,
		.post-type-spt_testimonial_form #sp_tpro_form_live_preview .handle-order-lower {
			display: none;
		}
		#sp_tpro-preview-box {
			overflow-x: auto;
			padding: 0 45px;
			resize: vertical;
		}
		.post-type-spt_testimonial_form .sp-tpro-fronted-form .sp-tpro-form-field .chosen-container-multi .chosen-choices li.search-choice .search-choice-close {
			padding-top: 7px;
			top: 0;
		}
		.post-type-spt_testimonial_form .sp-tpro-fronted-form .sp-tpro-form-field .chosen-container-single .chosen-single div {
			top: 0;
		}
		</style>
		<?php
		Helper::frontend_form_html( $form_id, $setting_options, $form_elements, $form_data );
		die();
	}

}
