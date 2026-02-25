<?php
/**
 * Class for Admin Ajax Functions
 */
class Owl_Carousel_2_Admin_Ajax {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'wp_ajax_owl_carousel_tax', array( $this, 'owl_carousel_tax' ) );
		add_action( 'wp_ajax_owl_carousel_terms', array( $this, 'owl_carousel_terms' ) );
		add_action( 'wp_ajax_owl_carousel_posts', array( $this, 'owl_carousel_posts' ) );
		add_action( 'wp_ajax_dd_owl_get_image', array( $this, 'dd_owl_get_image' ) );

	}

	/**
	 * Ajax Function for getting taxonomy
	 */
	public function owl_carousel_tax() {
		check_ajax_referer( 'dd_admin_ajax', 'nonce' );

		$post_type = ( sanitize_text_field( $_POST['posttype'] ) === 'reviews' ) ? 'product' : sanitize_text_field( $_POST['posttype'] ); //phpcs:ignore

		// Terms to exclude from WooCommerce.
		$wc_not = array( 'product_type', 'product_visibility', 'product_shipping_class' );

		$meta = '';

		if ( isset( $_POST['postid'] ) && metadata_exists( 'post', sanitize_text_field( wp_unslash( $_POST['postid'] ) ), 'dd_owl_post_taxonomy_type' ) ) {
			$meta = get_post_meta( sanitize_text_field( wp_unslash( $_POST['postid'] ) ), 'dd_owl_post_taxonomy_type', true );
		}
		$html = '';

		$tax_objects = get_object_taxonomies( $post_type, 'objects' );

		if ( empty( $tax_objects ) ) {
			$html .= '<span class="no-cats">' . __( 'There are no matching Taxonomies', 'owl-carousel-2' ) . '</span>';
		} else {
			$html .= '<select id="dd_owl_post_taxonomy_type" name="dd_owl_post_taxonomy_type" class="dd_owl_post_taxonomy_type_field">';

			if ( ! in_array( $meta, $tax_objects, true ) ) {
				$html .= '<option value="" selected> - - Choose A Tax Type - -</option>';
			}

			foreach ( $tax_objects as $tax ) {
				if ( 'product' === $post_type && in_array( $tax->name, $wc_not, true ) ) {
					continue;
				} else {
					$label = $tax->labels->name;
					$value = $tax->name;
					$html .= '<option value="' . esc_attr( $value ) . '" ';
					$html .= ( $value === $meta ) ? 'selected' : null;
					$html .= '> ' . esc_html( $label ) . '</option>';
				}
			}

			$html .= '</select>';
		}

		wp_send_json( $html );
		die();
	}

	/**
	 * Get the Terms List.
	 */
	public function owl_carousel_terms() {
		check_ajax_referer( 'dd_admin_ajax', 'nonce' );
		if ( ! isset( $_POST['postid'] ) ) {
			exit();
		}
		$post_type = isset( $_POST['posttype'] ) ? ( 'reviews' === sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) ) ? 'product' : sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) : null;

		$html = '';

		$tax_objects = get_object_taxonomies( $post_type, 'objects' );

		$term_objects = ( isset( $_POST['taxtype'] ) ) ? get_terms( sanitize_text_field( wp_unslash( $_POST['taxtype'] ) ), 'objects' ) : null;

		$theterm = get_post_meta( sanitize_text_field( wp_unslash( $_POST['postid'] ) ), 'dd_owl_post_taxonomy_term', true );

		if ( null === $tax_objects || is_wp_error( $term_objects ) ) {
			$html .= '<span class="no-cats">' . __( 'There are no matching terms', 'owl-carousel-2' ) . '</span>';
		} else {
			if ( null === $term_objects ) {
				$html .= '<span class="no-cats">' . __( 'There are no matching terms', 'owl-carousel-2' ) . '</span>';
			} else {
				$html .= '<select id="dd_owl_post_taxonomy_term" name="dd_owl_post_taxonomy_term[]" multiple="multiple" class="dd-owl-multi-select">';
				if ( ! in_array( $theterm, $term_objects, true ) || $term_objects->errors ) {
					$html .= '<option value=""> - - Choose A Term - -</option>';
				}
				foreach ( $term_objects as $term ) {
					$label = $term->name;
					$value = $term->slug;
					$html .= '<option value="' . $value . '" ';
					if ( is_array( $theterm ) ) {
						$html .= ( ( in_array( $value, $theterm, true ) ) && ( ! isset( $term_objects->errors ) ) ) ? "selected data-selected='true'" : null;
					}
					$html .= '> ' . $label . '</option>';
				}

				$html .= '</select>';
			}
		}

		wp_send_json( $html );

		die();
	}

	/**
	 * Get the posts Ajax Function
	 */
	public function owl_carousel_posts() {
		check_ajax_referer( 'dd_admin_ajax', 'nonce' );
		if ( ! isset( $_POST['posttype'] ) || ! isset( $_POST['carousel_id'] ) ) {
			exit();
		}
		$post_type = ( 'reviews' === $_POST['posttype'] ) ? 'product' : sanitize_text_field( wp_unslash( $_POST['posttype'] ) );

		global $post;
		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => '-1',
		);

		$query       = new WP_Query( $args );
		$html        = '';
		$carousel_id = sanitize_text_field( wp_unslash( $_POST['carousel_id'] ) );
		// The Loop.
		$selected_array = get_post_meta( $carousel_id, 'dd_owl_post_ids', true );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$html .= '<option value="' . esc_attr( $post->ID ) . '"';
				if ( is_array( $selected_array ) ) {
					if ( in_array( (string) $post->ID, $selected_array, true ) ) {
						$html .= 'selected="selected"';
					}
				}
				$html .= '>';
				$html .= get_the_title( $post->ID );
				$html .= '</option>';
			}
		} else {
			$html .= '<p>No Posts Found</p>';
		}
		wp_send_json( $html );
		die();
	}

	/**
	 * Get the image
	 */
	public function dd_owl_get_image() {
		if ( isset( $_GET['id'] ) ) {
			$image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'dd-preview-image' ) );
			$data  = array(
				'image' => $image,
			);
			wp_send_json_success( $data );
		} else {
			wp_send_json_error();
		}
	}
}
