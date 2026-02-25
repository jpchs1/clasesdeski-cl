<?php
/**
 * Framework options.class file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 */

namespace ShapedPlugin\TestimonialPro\Admin\ElementAddons;

use ShapedPlugin\TestimonialPro\Frontend\Helper;

/**
 * Elementor real testimonial pro shortcode Widget.
 *
 * @since 2.5.3
 */
class Sp_Testimonial_Pro_Shortcode_Widget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 2.5.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'real_testimonial_Pro_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.5.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Real Testimonial Pro', 'testimonial-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.5.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'icon-sp-real-testimonial-pro';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.5.3
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get all post list.
	 *
	 * @since 2.5.3
	 * @return array
	 */
	public function sprtp_post_list() {
		$post_list   = array();
		$sprtp_posts = new \WP_Query(
			array(
				'post_type'      => 'spt_shortcodes',
				'post_status'    => 'publish',
				'posts_per_page' => 9999,
			)
		);
		$posts       = $sprtp_posts->posts;
		foreach ( $posts as $post ) {
			$post_list[ $post->ID ] = $post->post_title;
		}
		krsort( $post_list );
		return $post_list;
	}

	/**
	 * Controls register.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'testimonial-pro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_testimonial_pro_shortcode',
			array(
				'label'       => __( 'Real Testimonial Shortcode(s)', 'testimonial-pro' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sprtp_post_list(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render testimonial shortcode widget output on the frontend.
	 *
	 * @since 2.5.3
	 * @access protected
	 */
	protected function render() {

		$settings                 = $this->get_settings_for_display();
		$sp_testimonial_shortcode = $settings['sp_testimonial_pro_shortcode'];

		if ( '' === $sp_testimonial_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$generator_id = (int) $sp_testimonial_shortcode;

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$post_id         = $generator_id;
			$setting_options = get_option( 'sp_testimonial_pro_options' );
			$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
			// Load dynamic style.
			$dynamic_style = Helper::load_dynamic_style( $post_id, $shortcode_data, $setting_options );
			// Google font link enqueue.
			$enqueue_fonts = Helper::load_google_fonts( $dynamic_style['typography'] );
			if ( ! empty( $enqueue_fonts ) ) {
				echo '<link rel="stylesheet" href="' . esc_url( 'https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ) ) . '" media="all">';
			}
			echo '<style id="sp_testimonial_external_style">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';
			$main_section_title = get_the_title( $post_id );
			Helper::sp_tpro_html_show( $post_id, $setting_options, $shortcode_data, $main_section_title );
			?>
			<script src="<?php echo esc_url( SP_TPRO_URL . 'Frontend/assets/js/scripts.min.js' ); ?>" ></script>
			<script>
			jQuery('#tpro-preloader-' + <?php echo esc_attr( $generator_id ); ?>).animate({ opacity: 0, zIndex: -99 }, 600);
			</script>
			<script src="<?php echo esc_url( SP_TPRO_URL . 'Frontend/assets/js/thumbnail-slide.min.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( '[sp_testimonial id="' . esc_attr( $generator_id ) . '"]' );
		}

	}

}
