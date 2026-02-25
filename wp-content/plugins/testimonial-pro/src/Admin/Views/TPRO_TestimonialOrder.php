<?php
/**
 * Real Testimonials Pro Widget
 *
 * @since 1.0
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Admin\Views;

/**
 * TPRO_TestimonialOrder
 */
class TPRO_TestimonialOrder {

	/**
	 * Current_post_type
	 *
	 * @var string
	 */
	public $current_post_type = 'spt_testimonial';

	/**
	 * Class Construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'posts_orderby', array( $this, 'spt_testimonial_orderby' ), 10, 2 );
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( &$this, 'archiveDragDrop' ), 10 );

		add_action( 'wp_ajax_update-custom-type-order', array( &$this, 'saveAjaxOrder' ) );
		add_action( 'wp_ajax_update-custom-type-order-archive', array( &$this, 'saveArchiveAjaxOrder' ) );
	}

	/**
	 * Testimonial orderby.
	 *
	 * @param  string $order_by orderBy.
	 * @param  object $query query.
	 * @return statement
	 */
	public function spt_testimonial_orderby( $order_by, $query ) {
		if ( 'spt_testimonial' === $query->query_vars['post_type'] ) {
			global $wpdb;

			if ( isset( $query->query_vars['ignore_custom_sort'] ) && $query->query_vars['ignore_custom_sort'] ) {
				return $order_by;
			}

			// Ignore the bbpress.
			if ( isset( $query->query_vars['post_type'] ) && ( ( is_array( $query->query_vars['post_type'] ) && in_array( 'reply', $query->query_vars['post_type'] ) ) || ( 'reply' === $query->query_vars['post_type'] ) ) ) {
				return $order_by;
			}
			if ( isset( $query->query_vars['post_type'] ) && ( ( is_array( $query->query_vars['post_type'] ) && in_array( 'topic', $query->query_vars['post_type'] ) ) || ( 'topic' === $query->query_vars['post_type'] ) ) ) {
				return $order_by;
			}

			// Check for orderby GET paramether in which case return default data.
			// $_GET is checked with empty/isset, so there is no need to add a nonce here.
			// phpcs:ignore
			if ( isset( $_GET['orderby'] ) && 'menu_order' !== $_GET['orderby'] && $_GET['orderby'] ) {
				return $order_by;
			}

			// check to ignore.
			/**
			 * Deprecated filter
			 * do not rely on this anymore
			 */
			if ( false === apply_filters( 'pto/posts_orderby', $order_by, $query ) ) {
				return $order_by;
			}

			$ignore = apply_filters( 'pto/posts_orderby/ignore', false, $order_by, $query );
			if ( $ignore ) {
				return $order_by;
			}

			if ( is_admin() ) {

				global $post;

				// Temporary ignore ACF group and admin ajax calls, should be fixed within ACF plugin sometime later.
				// if $_GET is checked with empty/isset, so there is no need to add a nonce here.
				// phpcs:ignore
				if ( is_object( $post ) && $post->post_type == 'acf-field-group' || ( defined( 'DOING_AJAX' ) && isset( $_REQUEST['action'] ) && strpos( $_REQUEST['action'], 'acf/' ) === 0 )
				) {
					return $order_by;
				}
				// if $_GET is checked with empty/isset, so there is no need to add a nonce here.
				// phpcs:ignore
				if ( isset( $_POST['query'] ) && isset( $_POST['query']['post__in'] ) && is_array( $_POST['query']['post__in'] ) && count( $_POST['query']['post__in'] ) > 0 ) {
					return $order_by;
				}

				$order_by = "{$wpdb->posts}.menu_order, {$wpdb->posts}.post_date DESC";

			} else {
				// ignore search.
				if ( $query->is_search() ) {
					return ( $order_by );
				}
			}
		}
		return ( $order_by );
	}


	/**
	 * Load archive drag&drop sorting dependencies.
	 *
	 * Since version 2.0
	 */
	public function archiveDragDrop() {
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'spt_testimonial' === $the_current_post_type ) {
			wp_register_script( 'testimonial-pro-order-js', SP_TPRO_URL . 'Admin/assets/js/order.min.js', array( 'jquery', 'jquery-ui-sortable' ), SP_TPRO_VERSION, false );
		}
		$userdata = wp_get_current_user();
		// Localize the script with new data.
		$CPTO_variables = array(
			'archive_sort_nonce' => wp_create_nonce( 'SPCPS_archive_sort_nonce_' . $userdata->ID ),
		);
		wp_localize_script( 'testimonial-pro-order-js', 'SPCPS', $CPTO_variables );

		// Enqueued script with localized data.
		wp_enqueue_script( 'testimonial-pro-order-js' );
	}

	/**
	 * Save the order set throgh the Archive
	 *
	 * @return void
	 */
	public function saveArchiveAjaxOrder() {

		set_time_limit( 600 );

		global $wpdb, $userdata;
		$post_type = 'spt_testimonial';
		$paged     = filter_var( $_POST['paged'], FILTER_SANITIZE_NUMBER_INT );
		$nonce     = $_POST['archive_sort_nonce'];

		// verify the nonce.
		if ( ! wp_verify_nonce( $nonce, 'SPCPS_archive_sort_nonce_' . $userdata->ID ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Error: Invalid nonce verification.', 'testimonial-pro' ) ), 401 );
		}

		parse_str( $_POST['order'], $data );

		if ( ! is_array( $data ) || count( $data ) < 1 ) {
			die();
		}

		// retrieve a list of all objects.
		$mysql_query = $wpdb->prepare(
			'SELECT ID FROM ' . $wpdb->posts . "  WHERE post_type = %s AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') ORDER BY menu_order, post_date DESC",
			$post_type
		);
		$results     = $wpdb->get_results( $mysql_query );

		if ( ! is_array( $results ) || count( $results ) < 1 ) {
			die();
		}

		// create the list of ID's.
		$objects_ids = array();
		foreach ( $results as $result ) {
			$objects_ids[] = (int) $result->ID;
		}

		global $userdata;
		$objects_per_page = get_user_meta( $userdata->ID, 'edit_' . $post_type . '_per_page', true );
		if ( empty( $objects_per_page ) ) {
			$objects_per_page = 20;
		}

		$edit_start_at = $paged * $objects_per_page - $objects_per_page;
		$index         = 0;
		for ( $i = $edit_start_at; $i < ( $edit_start_at + $objects_per_page ); $i ++ ) {
			if ( ! isset( $objects_ids[ $i ] ) ) {
				break;
			}

			$objects_ids[ $i ] = (int) $data['post'][ $index ];
			$index ++;
		}

		// update the menu_order within database.
		foreach ( $objects_ids as $menu_order => $id ) {
			$data = array(
				'menu_order' => $menu_order,
			);
			$data = apply_filters( 'post-types-order_save-ajax-order', $data, $menu_order, $id );

			$wpdb->update( $wpdb->posts, $data, array( 'ID' => $id ) );
		}

	}

}
