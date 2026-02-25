<?php
/**
 * The Helper class to manage all public facing stuffs.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_pro.
 * @subpackage Testimonial_pro/Frontend.
 */

namespace ShapedPlugin\TestimonialPro\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Functions
 */
class SP_Testimonial_Pro_Functions {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_filter( 'post_updated_messages', array( $this, 'sp_tpro_change_default_post_update_message' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
		add_action( 'admin_action_sp_tpro_duplicate_shortcode', array( $this, 'sp_tpro_duplicate_shortcode' ) );
		add_filter( 'post_row_actions', array( $this, 'sp_tpro_duplicate_shortcode_link' ), 10, 2 );
		add_image_size( 'tf-client-image-size', 120, 120, true );

	}

	/**
	 * Update notices function.
	 *
	 * @param array $message message.
	 * @return statement
	 */
	public function sp_tpro_change_default_post_update_message( $message ) {
		$screen = get_current_screen();
		if ( 'spt_shortcodes' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'View updated.', 'testimonial-pro' );
			$message['post'][4]  = esc_html__( 'View updated.', 'testimonial-pro' );
			$message['post'][6]  = esc_html__( 'View published.', 'testimonial-pro' );
			$message['post'][8]  = esc_html__( 'View submitted.', 'testimonial-pro' );
			$message['post'][10] = esc_html__( 'View draft updated.', 'testimonial-pro' );
		} elseif ( 'spt_testimonial' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'Testimonial updated.', 'testimonial-pro' );
			$message['post'][4]  = esc_html__( 'Testimonial updated.', 'testimonial-pro' );
			$message['post'][6]  = esc_html__( 'Testimonial published.', 'testimonial-pro' );
			$message['post'][8]  = esc_html__( 'Testimonial submitted.', 'testimonial-pro' );
			$message['post'][10] = esc_html__( 'Testimonial draft updated.', 'testimonial-pro' );
		} elseif ( 'spt_testimonial_form' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'Form updated.', 'testimonial-pro' );
			$message['post'][4]  = esc_html__( 'Form updated.', 'testimonial-pro' );
			$message['post'][6]  = esc_html__( 'Form published.', 'testimonial-pro' );
			$message['post'][8]  = esc_html__( 'Form submitted.', 'testimonial-pro' );
			$message['post'][10] = esc_html__( 'Form draft updated.', 'testimonial-pro' );
		}
		return $message;
	}


	/**
	 * Shortcode converter function.
	 *
	 * @param  mixed $id shortcode id.
	 * @return void
	 */
	public function testimonial_pro_id( $id ) {
		echo do_shortcode( '[testimonial_pro id="' . $id . '"]' );
	}

	/**
	 * Review Text
	 *
	 * @param string $text text.
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {
		$screen = get_current_screen();
		if ( 'spt_testimonial' === get_post_type() || 'spt_testimonial_page_tpro_help' === $screen->id || 'spt_testimonial_page_tpro_settings' === $screen->id || 'testimonial_cat' === $screen->taxonomy || 'spt_shortcodes' === $screen->post_type ) {
			$url  = 'https://wordpress.org/support/plugin/testimonial-free/reviews/?filter=5#new-post';
			$text = sprintf( __( 'If you like <strong>Real Testimonials Pro</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'testimonial-pro' ), $url );
		}

		return $text;
	}

	/**
	 * Function creates testimonial slider duplicate as a draft.
	 */
	public function sp_tpro_duplicate_shortcode() {
		global $wpdb;
		/**
		 * Nonce verification.
		 */
		if ( ! isset( $_GET['sp_tpro_duplicate_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['sp_tpro_duplicate_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sp_tpro_duplicate_shortcode' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'testimonial-pro' ) );
		}
		/**
		 * Get the original shortcode id
		 */
		$post_id    = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		$capability = apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( ! $show_ui && get_post_type( $post_id ) !== 'spt_shortcodes' ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'testimonial-pro' ) );
		}
		/**
		 * And all the original shortcode data then.
		 */
		$post = get_post( $post_id );

		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/**
		 * If shortcode data exists, create the shortcode duplicate
		 */
		if ( isset( $post ) && null !== $post ) {

			/**
			 * New shortcode data array.
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/**
			 * Insert the shortcode by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/**
			 * Get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");.
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/**
			 * Duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = get_post_custom( $post_id );
			// Duplicate all post meta.
			foreach ( $post_meta_infos as $key => $values ) {
				foreach ( $values as $value ) {
					$value = wp_slash( maybe_unserialize( $value ) ); // Unserialize data to avoid conflicts.
					add_post_meta( $new_post_id, $key, $value );
				}
			}
			/**
			 * Finally, redirect to the edit post screen for the new draft.
			 */
			wp_safe_redirect( esc_url( admin_url( 'edit.php?post_type=' . esc_attr( $post->post_type ) ) ) );
			exit;
		} else {
			wp_die( esc_html__( 'Shortcode creation failed, could not find original post: ', 'testimonial-pro' ) . esc_attr( $post_id ) );
		}
	}

	/**
	 * Add the duplicate link to action list for post_row_actions.
	 *
	 * @param  array  $actions  actions.
	 * @param  object $post post.
	 * @return array
	 */
	public function sp_tpro_duplicate_shortcode_link( $actions, $post ) {
		$capability = apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( $show_ui && 'spt_shortcodes' === $post->post_type ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=sp_tpro_duplicate_shortcode&post=' . $post->ID, basename( __FILE__ ), 'sp_tpro_duplicate_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'testimonial-pro' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Social profile list.
	 *
	 * @return array
	 */
	public function social_profile_list() {

		$social_list_one = array(
			'facebook'       => array(
				'name' => esc_html__( 'Facebook', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-facebook"></i>',
			),
			'twitter'        => array(
				'name' => esc_html__( 'Twitter', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-twitter"></i>',
			),
			'linkedin'       => array(
				'name' => esc_html__( 'LinkedIn', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-linkedin"></i>',
			),
			'skype'          => array(
				'name' => esc_html__( 'Skype', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-skype"></i>',
			),
			'dropbox'        => array(
				'name' => esc_html__( 'Dropbox', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-dropbox"></i>',
			),
			'wordpress'      => array(
				'name' => esc_html__( 'WordPress', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-wordpress"></i>',
			),
			'vimeo'          => array(
				'name' => esc_html__( 'Vimeo', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-vimeo"></i>',
			),
			'slideshare'     => array(
				'name' => esc_html__( 'SlideShare', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-slideshare"></i>',
			),
			'vk'             => array(
				'name' => esc_html__( 'VK', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-vk"></i>',
			),
			'tumblr'         => array(
				'name' => esc_html__( 'Tumblr', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-tumblr"></i>',
			),
			'yahoo'          => array(
				'name' => esc_html__( 'Yahoo', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-yahoo"></i>',
			),
			'pinterest'      => array(
				'name' => esc_html__( 'Pinterest', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-pinterest"></i>',
			),
			'youtube'        => array(
				'name' => esc_html__( 'Youtube', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-youtube"></i>',
			),
			'stumbleupon'    => array(
				'name' => esc_html__( 'StumbleUpon', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-stumbleupon"></i>',
			),
			'reddit'         => array(
				'name' => esc_html__( 'Reddit', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-reddit-alien"></i>',
			),
			'quora'          => array(
				'name' => esc_html__( 'Quora', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-quora"></i>',
			),
			'yelp'           => array(
				'name' => esc_html__( 'Yelp', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-yelp"></i>',
			),
			'weibo'          => array(
				'name' => esc_html__( 'Weibo', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-weibo"></i>',
			),
			'product-hunt'   => array(
				'name' => esc_html__( 'ProductHunt', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-product-hunt"></i>',
			),
			'hacker-news'    => array(
				'name' => esc_html__( 'HackerNews', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-hacker-news"></i>',
			),
			'soundcloud'     => array(
				'name' => esc_html__( 'Soundcloud', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-soundcloud"></i>',
			),
			'whatsapp'       => array(
				'name' => esc_html__( 'WhatsApp', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-whatsapp"></i>',
			),
			'medium'         => array(
				'name' => esc_html__( 'Medium', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-medium"></i>',
			),
			'vine'           => array(
				'name' => esc_html__( 'Vine', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-vine"></i>',
			),
			'slack'          => array(
				'name' => esc_html__( 'Slack', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-slack"></i>',
			),
			'instagram'      => array(
				'name' => esc_html__( 'Instagram', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-instagram"></i>',
			),
			'dribbble'       => array(
				'name' => esc_html__( 'Dribble', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-dribbble"></i>',
			),
			'flickr'         => array(
				'name' => esc_html__( 'Flickr', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-flickr"></i>',
			),
			'foursquare'     => array(
				'name' => esc_html__( 'FourSquare', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-foursquare"></i>',
			),
			'behance'        => array(
				'name' => esc_html__( 'Behance', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-behance"></i>',
			),
			'snapchat'       => array(
				'name' => esc_html__( 'SnapChat', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-snapchat-ghost"></i>',
			),
			'github'         => array(
				'name' => esc_html__( 'Github', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-github"></i>',
			),
			'bitbucket'      => array(
				'name' => esc_html__( 'Bitbucket', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-bitbucket"></i>',
			),
			'stack-overflow' => array(
				'name' => esc_html__( 'Stack Overflow', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-stack-overflow"></i>',
			),
			'codepen'        => array(
				'name' => esc_html__( 'Codepen', 'testimonial-pro' ),
				'icon' => '<i class="fa fa-codepen"></i>',
			),
		);
		$social_list_two = apply_filters(
			'sp_testimonial_social_profile_list',
			array()
		);
		$social_list     = array_merge( $social_list_one, $social_list_two );
		return $social_list;
	}

	/**
	 * Social profile name list.
	 *
	 * @return statement
	 */
	public function social_profile_name_list() {

		$function    = new SP_Testimonial_Pro_Functions();
		$social_list = $function->social_profile_list();

		if ( ! empty( $social_list ) ) {
			$social_name_option = '';
			foreach ( $social_list as $social_id => $social_value ) {
				$social_name_option .= '<option value="' . $social_id . '">' . $social_value['name'] . '</option>';
			}
			return $social_name_option;
		}
	}

}
