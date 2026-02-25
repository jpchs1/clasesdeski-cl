<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since        2.5.0
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Admin;

use ShapedPlugin\TestimonialPro\Admin\Views\Framework\Classes\SPFTESTIMONIAL;
use ShapedPlugin\TestimonialPro\Admin\Preview\Preview;
use ShapedPlugin\TestimonialPro\Admin\Helper\Pro_Cron;
use ShapedPlugin\TestimonialPro\Admin\DBUpdates;
use ShapedPlugin\TestimonialPro\Admin\Views\TPRO_TestimonialOrder;
/**
 * The admin class handle all the backend stuffs.
 */
class Admin {

	/**
	 * Admin construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_page' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'widgets_init', array( $this, 'register_testimonialpro_widget' ) );
		add_filter( 'init', array( $this, 'register_shortcode_post_type' ) );
		add_filter( 'init', array( $this, 'register_form_post_type' ) );
		add_filter( 'init', array( $this, 'register_testimonial_post_type' ) );
		add_filter( 'init', array( $this, 'register_taxonomy' ) );
		// Database Updater.
		new DBUpdates();
		new Preview();
		new SPFTESTIMONIAL();
		new Pro_Cron();
		// Order.
		add_action( 'plugins_loaded', array( $this, 'sp_tpro_custom_order_class_load' ) );

		add_action( 'wp_loaded', array( $this, 'init_sp_tpro_custom_order' ) );
	}

	/**
	 * Instantiate the main WordPress Term Order class
	 *
	 * @since 0.1.0
	 */
	public function sp_tpro_custom_order_class_load() {
		global $TPRO_CUSTOM_SORT_CLASS;
		$TPRO_CUSTOM_SORT_CLASS = new TPRO_TestimonialOrder();
	}

	/**
	 * Init Testimonial custom order
	 *
	 * @return void
	 */
	public function init_sp_tpro_custom_order() {
		global $TPRO_CUSTOM_SORT_CLASS;
		if ( is_admin() ) {
			$TPRO_CUSTOM_SORT_CLASS->init();
		}
	}

	/**
	 * Register custom taxonomy
	 *
	 * @since 1.0
	 */
	public function register_taxonomy() {
		$settings            = get_option( 'sp_testimonial_pro_options' );
		$singular_name       = isset( $settings['tpro_singular_name'] ) ? $settings['tpro_singular_name'] : __( 'Testimonial', 'testimonial-pro' );
		$group_singular_name = isset( $settings['tpro_group_singular_name'] ) ? $settings['tpro_group_singular_name'] : __( 'Group', 'testimonial-pro' );
		$group_plural_name   = isset( $settings['tpro_group_plural_name'] ) ? $settings['tpro_group_plural_name'] : __( 'Groups', 'testimonial-pro' );
		$capability          = apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' );
		$show_ui             = current_user_can( $capability ) ? true : false;
		$labels              = array(
			'menu_name'         => $group_plural_name,
			'name'              => $group_plural_name,
			'singular_name'     => $group_singular_name,
			'search_items'      => esc_html__( 'Search ', 'testimonial-pro' ) . $group_singular_name,
			'all_items'         => esc_html__( 'All ', 'testimonial-pro' ) . $group_plural_name,
			'parent_item'       => esc_html__( 'Parent ', 'testimonial-pro' ) . $group_singular_name,
			'parent_item_colon' => esc_html__( 'Parent ', 'testimonial-pro' ) . $group_singular_name . ':',
			'edit_item'         => esc_html__( 'Edit ', 'testimonial-pro' ) . $group_singular_name,
			'update_item'       => esc_html__( 'Update ', 'testimonial-pro' ) . $group_singular_name,
			'add_new_item'      => esc_html__( 'Add New ', 'testimonial-pro' ) . $group_singular_name,
			'new_item_name'     => esc_html__( 'New ', 'testimonial-pro' ) . $group_singular_name . ' Name',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => $show_ui,
			'show_admin_column' => true,
			'query_var'         => false,
		);

		register_taxonomy( 'testimonial_cat', array( 'spt_testimonial' ), $args );
	}

	/**
	 * Shortcode Post Type
	 */
	public function register_shortcode_post_type() {
		$capability = apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		register_post_type(
			'spt_shortcodes',
			array(
				'label'               => __( 'Manage Views', 'testimonial-pro' ),
				'description'         => __( 'Manage Views', 'testimonial-pro' ),
				'public'              => false,
				'has_archive'         => false,
				'publicaly_queryable' => false,
				'show_ui'             => $show_ui,
				'show_in_menu'        => 'edit.php?post_type=spt_testimonial',
				'hierarchical'        => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'capability_type'     => 'post',
				'labels'              => array(
					'name'               => __( 'Manage Views', 'testimonial-pro' ),
					'singular_name'      => __( 'Manage View', 'testimonial-pro' ),
					'menu_name'          => __( 'Manage Views', 'testimonial-pro' ),
					'add_new'            => __( 'Add New', 'testimonial-pro' ),
					'add_new_item'       => __( 'Add New View', 'testimonial-pro' ),
					'edit'               => __( 'Edit', 'testimonial-pro' ),
					'edit_item'          => __( 'Edit View', 'testimonial-pro' ),
					'new_item'           => __( 'New View', 'testimonial-pro' ),
					'search_items'       => __( 'Search View', 'testimonial-pro' ),
					'not_found'          => __( 'No View Found', 'testimonial-pro' ),
					'not_found_in_trash' => __( 'No View Found in Trash', 'testimonial-pro' ),
					'parent'             => __( 'Parent View', 'testimonial-pro' ),
				),
			)
		);
	}

	/**
	 * Form Post Type
	 */
	public function register_form_post_type() {
		$capability = apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		register_post_type(
			'spt_testimonial_form',
			array(
				'label'               => __( 'Forms', 'testimonial-pro' ),
				'description'         => __( 'Generate forms for Frontend.', 'testimonial-pro' ),
				'public'              => false,
				'has_archive'         => false,
				'publicaly_queryable' => false,
				'show_ui'             => $show_ui,
				'show_in_menu'        => 'edit.php?post_type=spt_testimonial',
				'hierarchical'        => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'capability_type'     => 'post',
				'labels'              => array(
					'name'               => __( 'Testimonial Forms', 'testimonial-pro' ),
					'singular_name'      => __( 'Testimonial Form', 'testimonial-pro' ),
					'menu_name'          => __( 'Testimonial Forms', 'testimonial-pro' ),
					'add_new'            => __( 'Add New', 'testimonial-pro' ),
					'add_new_item'       => __( 'Add New Form', 'testimonial-pro' ),
					'edit'               => __( 'Edit', 'testimonial-pro' ),
					'item_updated'       => __( 'Form updated', 'testimonial-pro' ),
					'edit_item'          => __( 'Edit Form', 'testimonial-pro' ),
					'new_item'           => __( 'New Form', 'testimonial-pro' ),
					'search_items'       => __( 'Search Forms', 'testimonial-pro' ),
					'not_found'          => __( 'No Form Found', 'testimonial-pro' ),
					'not_found_in_trash' => __( 'No Form Found in Trash', 'testimonial-pro' ),
					'parent'             => __( 'Parent Form', 'testimonial-pro' ),
				),
			)
		);
	}

	/**
	 * Register testimonial post type.
	 *
	 * @since 1.0
	 */
	public function register_testimonial_post_type() {
		$settings      = get_option( 'sp_testimonial_pro_options' );
		$singular_name = isset( $settings['tpro_singular_name'] ) ? $settings['tpro_singular_name'] : __( 'Testimonial', 'testimonial-pro' );
		$plural_name   = isset( $settings['tpro_plural_name'] ) ? $settings['tpro_plural_name'] : __( 'Testimonials', 'testimonial-pro' );
		$capability    = apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' );
		$show_ui       = current_user_can( $capability ) ? true : false;
		if ( post_type_exists( 'spt_testimonial' ) ) {
			return;
		}

		$labels = apply_filters(
			'sp_testimonial_post_type_labels',
			array(
				'name'                  => __( 'All ', 'testimonial-pro' ) . $plural_name,
				'singular_name'         => $singular_name,
				'menu_name'             => __( 'Real Testimonials', 'testimonial-pro' ),
				'all_items'             => __( 'All ', 'testimonial-pro' ) . $plural_name,
				'add_new'               => __( 'Add New', 'testimonial-pro' ),
				'add_new_item'          => __( 'Add New ', 'testimonial-pro' ) . $singular_name,
				'edit'                  => __( 'Edit', 'testimonial-pro' ),
				'edit_item'             => __( 'Edit ', 'testimonial-pro' ) . $singular_name,
				'item_updated'          => $singular_name . __( ' updated', 'testimonial-pro' ),
				'new_item'              => __( 'New ', 'testimonial-pro' ) . $singular_name,
				'search_items'          => __( 'Search ', 'testimonial-pro' ) . $plural_name,
				'not_found'             => __( 'No ', 'testimonial-pro' ) . $plural_name . __( ' found', 'testimonial-pro' ),
				'not_found_in_trash'    => __( 'No ', 'testimonial-pro' ) . $plural_name . __( ' found in Trash', 'testimonial-pro' ),
				'parent'                => __( 'Parent ', 'testimonial-pro' ) . $plural_name,
				'featured_image'        => $singular_name . __( ' Image', 'testimonial-pro' ),
				'set_featured_image'    => __( 'Set reviewer image', 'testimonial-pro' ),
				'remove_featured_image' => __( 'Remove image', 'testimonial-pro' ),
				'use_featured_image'    => __( 'Use as image', 'testimonial-pro' ),
			)
		);

		$args = apply_filters(
			'sp_testimonial_post_type_args',
			array(
				'label'               => $singular_name . __( ' pro', 'testimonial-pro' ),
				'description'         => $singular_name . __( ' custom post type.', 'testimonial-pro' ),
				'taxonomies'          => array(),
				'public'              => false,
				'has_archive'         => false,
				'publicaly_queryable' => false,
				'show_ui'             => $show_ui,
				'show_in_menu'        => true,
				'menu_icon'           => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNDc4LjI0OCA0NzguMjQ4IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NzguMjQ4IDQ3OC4yNDg7IiB4bWw6c3BhY2U9InByZXNlcnZlIiBjbGFzcz0iIj48Zz48Zz4KCTxnPgoJCTxnPgoJCQk8cGF0aCBkPSJNNDU2LjAyLDQ0LjgyMUgyNjQuODNjLTEyLjI2LDAtMjIuMjMyLDkuOTcyLTIyLjIzMiwyMi4yMjl2OTguNjUyYzAsMTIuMjU4LDkuOTc0LDIyLjIzLDIyLjIzMiwyMi4yM2gxNi43ODd2MzkuMTYxICAgICBjMCwyLjcwNywxLjU4LDUuMTY1LDQuMDQzLDYuMjkyYzAuOTIsMC40MiwxLjkwMSwwLjYyNywyLjg3NSwwLjYyN2MxLjYzMSwwLDMuMjQ0LTAuNTc2LDQuNTIzLTEuNjg1bDUxLjM4My00NC4zOTZoMTExLjU3NiAgICAgYzEyLjI2LDAsMjIuMjMtOS45NzMsMjIuMjMtMjIuMjNWNjcuMDVDNDc4LjI1LDU0Ljc5Miw0NjguMjc3LDQ0LjgyMSw0NTYuMDIsNDQuODIxeiBNMzE5LjkyMiwxMTIuMjUybC0xMC4yMDksOS45NTMgICAgIGwyLjQxLDE0LjA1NGMwLjE3NCwxLjAxNS0wLjI0MiwyLjAzOC0xLjA3NiwyLjY0M2MtMC40NjksMC4zNDItMS4wMjcsMC41MTYtMS41ODgsMC41MTZjLTAuNDI4LDAtMC44NjEtMC4xMDMtMS4yNTYtMC4zMSAgICAgbC0xMi42MjEtNi42MzVsLTEyLjYxOSw2LjYzNWMtMC45MTIsMC40NzgtMi4wMTYsMC4zOTgtMi44NDgtMC4yMDZzLTEuMjQ4LTEuNjI4LTEuMDc0LTIuNjQzbDIuNDEtMTQuMDU0bC0xMC4yMTEtOS45NTMgICAgIGMtMC43MzQtMC43MTgtMS4wMDItMS43OTItMC42ODUtMi43NjljMC4zMTctMC45NzgsMS4xNjQtMS42OTEsMi4xODMtMS44MzlsMTQuMTEtMi4wNWw2LjMxLTEyLjc4NiAgICAgYzAuNDU3LTAuOTIzLDEuMzk2LTEuNTA3LDIuNDI0LTEuNTA3czEuOTY5LDAuNTg0LDIuNDIyLDEuNTA3bDYuMzEyLDEyLjc4NmwxNC4xMDcsMi4wNWMxLjAyLDAuMTQ4LDEuODYzLDAuODYxLDIuMTg0LDEuODM5ICAgICBDMzIwLjkyNCwxMTAuNDYsMzIwLjY1OCwxMTEuNTM1LDMxOS45MjIsMTEyLjI1MnogTTM4NC43NjYsMTEyLjI1MmwtMTAuMjExLDkuOTUzbDIuNDEyLDE0LjA1NCAgICAgYzAuMTcyLDEuMDE1LTAuMjQ0LDIuMDM4LTEuMDc2LDIuNjQzYy0wLjQ2OSwwLjM0Mi0xLjAyNSwwLjUxNi0xLjU4OCwwLjUxNmMtMC40MywwLTAuODU5LTAuMTAzLTEuMjYtMC4zMWwtMTIuNjE5LTYuNjM1ICAgICBsLTEyLjYxOSw2LjYzNWMtMC45MTIsMC40NzgtMi4wMTQsMC4zOTgtMi44NDYtMC4yMDZjLTAuODM0LTAuNjA0LTEuMjUtMS42MjgtMS4wNzYtMi42NDNsMi40MS0xNC4wNTRsLTEwLjIwOS05Ljk1MyAgICAgYy0wLjczNC0wLjcxOC0xLjAwMi0xLjc5Mi0wLjY4NC0yLjc2OWMwLjMxNi0wLjk3OCwxLjE2LTEuNjkxLDIuMTgyLTEuODM5bDE0LjEwOS0yLjA1bDYuMzExLTEyLjc4NiAgICAgYzAuNDU1LTAuOTIzLDEuMzk2LTEuNTA3LDIuNDIyLTEuNTA3YzEuMDI5LDAsMS45NjcsMC41ODQsMi40MjIsMS41MDdsNi4zMTIsMTIuNzg2bDE0LjEwOSwyLjA1ICAgICBjMS4wMjEsMC4xNDgsMS44NjMsMC44NjEsMi4xODIsMS44MzlDMzg1Ljc2OCwxMTAuNDYsMzg1LjUsMTExLjUzNSwzODQuNzY2LDExMi4yNTJ6IE00NDkuNjA3LDExMi4yNTJsLTEwLjIxMSw5Ljk1MyAgICAgbDIuNDA4LDE0LjA1NGMwLjE3NiwxLjAxNS0wLjIzOCwyLjAzOC0xLjA3MiwyLjY0M2MtMC40NzEsMC4zNDItMS4wMjcsMC41MTYtMS41OSwwLjUxNmMtMC40MywwLTAuODU5LTAuMTAzLTEuMjU4LTAuMzEgICAgIGwtMTIuNjIxLTYuNjM1bC0xMi42MjEsNi42MzVjLTAuOTA4LDAuNDc4LTIuMDEyLDAuMzk4LTIuODQ0LTAuMjA2Yy0wLjgzNC0wLjYwNC0xLjI0OC0xLjYyOC0xLjA3Ni0yLjY0M2wyLjQxMi0xNC4wNTQgICAgIGwtMTAuMjExLTkuOTUzYy0wLjczNC0wLjcxOC0xLTEuNzkyLTAuNjg0LTIuNzY5YzAuMzE2LTAuOTc4LDEuMTY0LTEuNjkxLDIuMTgyLTEuODM5bDE0LjExMS0yLjA1bDYuMzExLTEyLjc4NiAgICAgYzAuNDUzLTAuOTIzLDEuMzk1LTEuNTA3LDIuNDItMS41MDdjMS4wMjcsMCwxLjk3MSwwLjU4NCwyLjQyNiwxLjUwN0w0MzQsMTA1LjU5NGwxNC4xMDksMi4wNSAgICAgYzEuMDE4LDAuMTQ4LDEuODYxLDAuODYxLDIuMTgyLDEuODM5QzQ1MC42MDksMTEwLjQ2LDQ1MC4zNDQsMTExLjUzNSw0NDkuNjA3LDExMi4yNTJ6IiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBjbGFzcz0iYWN0aXZlLXBhdGgiIGRhdGEtb2xkX2NvbG9yPSIjMDAwMDAwIiBmaWxsPSIjOUZBNEE5Ii8+CgkJCTxwYXRoIGQ9Ik0xNTIuODQ0LDExMi45MjRjLTQ2Ljc2LDAtNzIuNjM5LDI0LjIzMS03Mi4xNjYsNzAuOTIxYzAuNjg2LDYzLjk0NywyNy44NTksMTAyLjc0LDcyLjE2NiwxMDIuMDYzICAgICBjMCwwLDcyLjEzMSwyLjkyNCw3Mi4xMzEtMTAyLjA2M0MyMjQuOTc1LDEzNy4xNTUsMjAwLjYwNSwxMTIuOTI0LDE1Mi44NDQsMTEyLjkyNHoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiIGZpbGw9IiM5RkE0QTkiLz4KCQkJPHBhdGggZD0iTTI4MC40MjgsMzM0LjQ0NGwtNzIuMDc0LTI4LjczNmwtMTYuODc3LTE0LjIyM2MtNC40NTctMy43NjYtMTEuMDQxLTMuNDg4LTE1LjE3OCwwLjYyMWwtMjMuNDYzLDIzLjMzNmwtMjMuNTMzLTIzLjM0MiAgICAgYy00LjEzNy00LjEwNC0xMC43MTMtNC4zNjktMTUuMTY0LTAuNjE1bC0xNi44ODEsMTQuMjIzbC03Mi4wNzQsMjguNzM5QzEuOTc1LDM0My42OSwxLjk5NSw0MjUuODg0LDAsNDMzLjQyN2gzMDUuNjQ2ICAgICBDMzAzLjY1Niw0MjUuOSwzMDMuNjQ2LDM0My42NzksMjgwLjQyOCwzMzQuNDQ0eiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgY2xhc3M9ImFjdGl2ZS1wYXRoIiBkYXRhLW9sZF9jb2xvcj0iIzAwMDAwMCIgZmlsbD0iIzlGQTRBOSIvPgoJCTwvZz4KCTwvZz4KPC9nPjwvZz4gPC9zdmc+Cg==',
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'hierarchical'        => false,
				'query_var'           => false,
				'menu_position'       => 20,
				'supports'            => array(
					'title',
					'editor',
					'thumbnail',
				),
				'capability_type'     => 'post',
				'labels'              => $labels,
			)
		);

		register_post_type( 'spt_testimonial', $args );
	}

	/**
	 * Add SubMenu Page
	 */
	public function help_page() {
		add_submenu_page( 'edit.php?post_type=spt_testimonial', __( 'Real Testimonials Pro Help', 'testimonial-pro' ), __( 'Help', 'testimonial-pro' ), 'manage_options', 'tpro_help', array( $this, 'help_page_callback' ) );
	}

	/**
	 * Help Page Callback
	 */
	public function help_page_callback() {
		?>
<div class="wrap about-wrap sp-tpro-help">
	<h1><?php esc_html_e( 'Welcome to Real Testimonials Pro!', 'testimonial-pro' ); ?></h1>
	<p class="about-text">
		<?php
			esc_html_e( 'Thank you for installing Real Testimonials Pro! You\'re now running the most popular Testimonial Premium plugin. This video playlist will help you get started with the plugin.', 'testimonial-pro' );
		?>
	</p>
	<div class="headline-feature-video">
		<div class="headline-feature feature-video">
			<iframe width="560" height="315"
				src="https://www.youtube.com/embed/OA7LgaZHwIY?list=PLoUb-7uG-5jM2sjscSqBVj07VXOqt0qHZ" frameborder="0"
				allowfullscreen></iframe>
		</div>
	</div>
	<div class="feature-section three-col">
		<div class="col">
			<div class="sp-tpro-feature text-center">
				<h3><i class="sp-tpro-font-icon fa fa-file-text"></i>
				<?php echo esc_html__( 'Documentation', 'smart-post-show-pro' ); ?> </h3>
				<p><?php echo esc_html__( 'Check out our documentation page and more information about what you can do with Smart Post Show Pro.', 'smart-post-show-pro' ); ?>
				</p>
				<a href="https://docs.shapedplugin.com/docs/testimonial-pro/introduction/" target="_blank" class="button button-primary"><?php echo esc_html__( 'Browse Docs', 'smart-post-show-pro' ); ?></a>
			</div>
		</div>
		<div class="col">
			<div class="sp-tpro-feature text-center">
				<h3><i class="sp-tpro-font-icon fa fa-envelope"></i>
					<?php echo esc_html__( 'Support', 'smart-post-show-pro' ); ?></h3>
				<p> <?php echo esc_html__( "Need one-to-one assistance? Get in touch with our top-notch support team! We'd love to help you immediately.", 'smart-post-show-pro' ); ?>
				</p>
				<a href="https://shapedplugin.com/support/" target="_blank"
					class="button button-primary"><?php echo esc_html__( 'Get Support', 'smart-post-show-pro' ); ?></a>
			</div>

		</div>
		<div class="col">
			<div class="sp-tpro-feature text-center">
				<h3><i class="sp-tpro-font-icon fa fa-file-video-o"></i>
					<?php echo esc_html__( 'Video Tutorials', 'smart-post-show-pro' ); ?></h3>
				<p><?php echo esc_html__( 'Check our video tutorials which cover everything you need to know about Smart Post Show Pro.', 'smart-post-show-pro' ); ?>
				</p>
				<a href="https://www.youtube.com/watch?v=4wtkBqZ4Urw&list=PLoUb-7uG-5jM2sjscSqBVj07VXOqt0qHZ"
					target="_blank"
					class="button button-primary"><?php echo esc_html__( 'Watch Now', 'smart-post-show-pro' ); ?> </a>
			</div>
		</div>
	</div>
	<div class="about-wrap plugin-section">
		<div class="sp-plugin-section-title">
			<h2>Take your website beyond the typical with more premium plugins!</h2>
			<h4>Some more premium plugins are ready to make your website awesome.</h4>
		</div>
		<div class="feature-section  first-cols three-col">
			<div class="col">
				<a href="https://smartpostshow.com/" alt="Smart Post Show" target="_blank" class="tpro-plugin-link" >
					<div class="sp-tpro-feature text-center">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/smart-post-show.png" alt="Smart Post Show" class="tpro-help-img">
						<h3><?php echo esc_html__( 'Smart Post Show', 'wp-carousel-pro' ); ?></h3>
						<p><?php echo esc_html__( 'Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.', 'wp-carousel-pro' ); ?></p>
					</div>
				</a>
			</div>
			<div class="col">
				<a href="https://shapedplugin.com/wp-carousel/" alt="WP Carousel" target="_blank" class="tpro-plugin-link" >
					<div class="sp-tpro-feature">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/wp-carousel.png" alt="WP Carousel" class="tpro-help-img">
						<h3><?php echo esc_html__( 'WP Carousel', 'smart-post-show-pro' ); ?></h3>
						<p><?php echo esc_html__( 'The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.', 'smart-post-show-pro' ); ?></p>
					</div>
				</a>
			</div>
			<div class="col">
				<a href="https://shapedplugin.com/woocommerce-product-slider/"  target="_blank" alt="WooCommerce Product Slider Pro" class="tpro-plugin-link">
					<div class="sp-tpro-feature">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/woo-product-slider.png" alt="WooCommerce Product Slider Pro" class="tpro-help-img">
						<h3><?php echo esc_html__( 'Product Slider for WooCommerce', 'smart-post-show-pro' ); ?></h3>
						<p><?php echo esc_html__( 'Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.', 'smart-post-show-pro' ); ?></p>
					</div>
				</a>
			</div>
		</div>
		<div class="feature-section three-col">
				<div class="col">
					<a href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/" target="_blank" alt="Gallery Slider for WooCommerce" class="tpro-plugin-link">
					<div class="sp-tpro-feature">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/gallery-slider-for-woocommerce.png" alt="Gallery Slider for WooCommerce" class="tpro-help-img">
						<h3><?php echo esc_html__( 'Gallery Slider for WooCommerce', 'smart-post-show-pro' ); ?></h3>
						<p><?php echo esc_html__( 'Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.', 'smart-post-show-pro' ); ?></p>
					</div>
					</a>
				</div>
				<div class="col">
					<a href="https://shapedplugin.com/easy-accordion/" alt="Easy Accordion" target="_blank" class="tpro-plugin-link">
					<div class="sp-tpro-feature">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/easy-accordion.png" alt="Easy Accordion" class="tpro-help-img">
						<h3><?php echo esc_html__( 'Easy Accordion', 'smart-post-show-pro' ); ?></h3>
						<p><?php echo esc_html__( 'Minimize customer support by offering comprehensive FAQs and increasing conversions.', 'smart-post-show-pro' ); ?></p>
					</div>
					</a>
				</div>
				<div class="col">
				<a href="https://shapedplugin.com/logo-carousel/" alt="Logo Carousel" target="_blank" class="tpro-plugin-link">
					<div class="sp-tpro-feature">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/logo-carousel.png" alt="Logo Carousel" class="tpro-help-img">
						<h3><?php echo esc_html__( 'Logo Carousel', 'smart-post-show-pro' ); ?></h3>
						<p><?php echo esc_html__( 'Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.', 'smart-post-show-pro' ); ?></p>
					</div>
					</a>
				</div>
			</div>
	</div>

</div>
		<?php
	}

	/**
	 * Admin scripts.
	 *
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'testimonial-pro-admin', SP_TPRO_URL . 'Admin/assets/css/admin.css', array(), SP_TPRO_VERSION );

		$screen = get_current_screen();
		if ( 'spt_testimonial_page_tpro_help' === $screen->id ) {
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'testimonial-pro-help', SP_TPRO_URL . 'Admin/assets/css/help.css', array(), SP_TPRO_VERSION );
		}
		if ( 'spt_testimonial' === $screen->post_type ) {
			wp_enqueue_style( 'testimonial-pro-order', SP_TPRO_URL . 'Admin/assets/css/order.css', array(), SP_TPRO_VERSION );
		}
	}

	/**
	 * Register the widget for the public-facing side of the site.
	 *
	 * The register_widget should have full path of namespace of the Widget class file.
	 *
	 * @param object $widget Widget instance.
	 *
	 * @since    2.0.0
	 */
	public function register_testimonialpro_widget( $widget ) {
		register_widget( 'ShapedPlugin\TestimonialPro\Admin\Views\TPRO_Widget_Content' );
		return $widget;
	}

}
