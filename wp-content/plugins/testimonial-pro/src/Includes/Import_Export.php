<?php
/**
 * Custom import export.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_pro.
 * @subpackage Testimonial_pro/Includes.
 */

namespace ShapedPlugin\TestimonialPro\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Custom import export.
 */
class Import_Export {

	/**
	 * Export testimonials.
	 *
	 * @param  mixed $shortcode_ids Export testimonials and shortcode ids.
	 * @param  mixed $text_ids Export testimonials.
	 * @return object
	 */
	public function export( $shortcode_ids, $text_ids ) {
		$export = array();
		if ( ! empty( $shortcode_ids ) ) {
			if ( 'all_testimonial' === $shortcode_ids ) {
				$post_type   = 'spt_testimonial';
				$post_status = array( 'inherit', 'publish' );
			} elseif ( 'all_spt_shortcodes' === $shortcode_ids || 'select_shortcodes' === $text_ids ) {
				$post_type   = 'spt_shortcodes';
				$post_status = array( 'inherit', 'publish' );
			} elseif ( 'all_spt_form' === $shortcode_ids || 'select_forms' === $text_ids ) {
				$post_type   = 'spt_testimonial_form';
				$post_status = array( 'inherit', 'pending', 'publish' );
			}
			$post_in    = 'select_forms' === $text_ids || 'select_shortcodes' === $text_ids ? $shortcode_ids : '';
			$args       = array(
				'post_type'        => $post_type,
				'post_status'      => $post_status,
				'orderby'          => 'modified',
				'suppress_filters' => 1, // wpml, ignore language filter.
				'posts_per_page'   => -1,
				'post__in'         => $post_in,
			);
			$shortcodes = get_posts( $args );
			if ( ! empty( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
					if ( 'all_testimonial' !== $shortcode_ids ) {
						$shortcode_export = array(
							'title'       => $shortcode->post_title,
							'original_id' => $shortcode->ID,
							'spt_post'    => $post_type,
							'meta'        => array(),
						);
					}
					if ( 'all_testimonial' === $shortcode_ids ) {
						$terms            = get_the_terms( $shortcode->ID, 'testimonial_cat' );
						$shortcode_export = array(
							'title'           => $shortcode->post_title,
							'original_id'     => $shortcode->ID,
							'content'         => $shortcode->post_content,
							'image'           => get_the_post_thumbnail_url( $shortcode->ID, 'single-post-thumbnail' ),
							'category'        => $terms,
							'all_testimonial' => 'all_testimonial',
							'spt_post'        => $post_type,
							'meta'            => array(),
						);
					}

					foreach ( get_post_meta( $shortcode->ID ) as $metakey => $value ) {
						$shortcode_export['meta'][ $metakey ] = $value[0];
					}
					$export['shortcode'][] = $shortcode_export;

					unset( $shortcode_export );
				}
				$export['metadata'] = array(
					'version' => SP_TPRO_VERSION,
					'date'    => gmdate( 'Y/m/d' ),
				);
			}
			return $export;
		}
	}

	/**
	 * Export Testimonials by ajax.
	 *
	 * @return void
	 */
	public function export_shortcodes() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'spftestimonial_options_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Error: Invalid nonce verification.', 'testimonial-pro' ) ), 401 );
		}
		$shortcode_ids = '';
		if ( isset( $_POST['lcp_ids'] ) ) {
			$shortcode_ids = is_array( $_POST['lcp_ids'] ) ? wp_unslash( array_map( 'absint', $_POST['lcp_ids'] ) ) : sanitize_text_field( wp_unslash( $_POST['lcp_ids'] ) );
		}
		$text_ids = '';
		if ( isset( $_POST['text_ids'] ) ) {
			$text_ids = is_array( $_POST['text_ids'] ) ? wp_unslash( array_map( 'absint', $_POST['text_ids'] ) ) : sanitize_text_field( wp_unslash( $_POST['text_ids'] ) );
		}

		$export = $this->export( $shortcode_ids, $text_ids );

		if ( is_wp_error( $export ) ) {
			wp_send_json_error(
				array(
					'message' => $export->get_error_message(),
				),
				400
			);
		}

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
            // @codingStandardsIgnoreLine
            echo wp_json_encode($export, JSON_PRETTY_PRINT);
			die;
		}

		wp_send_json( $export, 200 );
	}
	/**
	 * Insert an attachment from an URL address.
	 *
	 * @param  String $url remote url.
	 * @param  Int    $parent_post_id parent post id.
	 * @return Int    Attachment ID.
	 */
	public function insert_attachment_from_url( $url, $parent_post_id = null ) {

		if ( ! class_exists( 'WP_Http' ) ) {
			include_once ABSPATH . WPINC . '/class-http.php';
		}
		$attachment_title = sanitize_file_name( pathinfo( $url, PATHINFO_FILENAME ) );
		// Does the attachment already exist ?
		if ( post_exists( $attachment_title, '', '', 'attachment' ) ) {
			$attachment = get_page_by_title( $attachment_title, OBJECT, 'attachment' );
			if ( ! empty( $attachment ) ) {
				$attachment_id = $attachment->ID;
				return $attachment_id;
			}
		}
		$http     = new \WP_Http();
		$response = $http->request( $url );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$upload = wp_upload_bits( basename( $url ), null, $response['body'] );
		if ( ! empty( $upload['error'] ) ) {
			return false;
		}

		$file_path     = $upload['file'];
		$file_name     = basename( $file_path );
		$file_type     = wp_check_filetype( $file_name, null );
		$wp_upload_dir = wp_upload_dir();

		$post_info = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Create the attachment.
		$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

		// Include image.php.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Define attachment metadata.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment.
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;

	}

	/**
	 * Import logo ans shortcode.
	 *
	 * @param  mixed $shortcodes Import logo and carousel shortcode array.
	 * @throws \Exception Exception.
	 * @return object
	 */
	public function import( $shortcodes ) {
		$errors        = array();
		$spt_post_type = 'spt_testimonial';
		foreach ( $shortcodes as $index => $shortcode ) {
			$errors[ $index ] = array();
			$new_shortcode_id = 0;

			$spt_post_type = isset( $shortcode['spt_post'] ) ? $shortcode['spt_post'] : '';
			$post_status   = 'spt_testimonial_form' === $spt_post_type ? 'pending' : 'publish';
			try {
				$new_shortcode_id = wp_insert_post(
					array(
						'post_title'   => isset( $shortcode['title'] ) ? $shortcode['title'] : '',
						'post_content' => isset( $shortcode['content'] ) ? $shortcode['content'] : '',
						'post_status'  => $post_status,
						'post_type'    => $spt_post_type,
					),
					true
				);
				if ( isset( $shortcode['all_testimonial'] ) ) {
					$categories = isset( $shortcode['category'] ) ? $shortcode['category'] : array();
					if ( is_array( $categories ) && ! empty( $categories ) ) {
						$terms_id = array();
						foreach ( $categories as $category ) {
							$name        = isset( $category['name'] ) ? $category['name'] : '';
							$slug        = isset( $category['slug'] ) ? $category['slug'] : '';
							$description = isset( $category['description'] ) ? $category['description'] : '';
							if ( term_exists( $slug, 'testimonial_cat' ) ) {
								$imterm  = get_term_by( 'slug', $slug, 'testimonial_cat' );
								$term_id = $imterm->term_id;
								array_push( $terms_id, $term_id );
							} else {
								$lcp_cat_id   = wp_insert_category(
									array(
										'taxonomy' => 'testimonial_cat',
										'cat_name' => $name,
										'category_nicename' => $slug,
										'category_description' => $description,
									),
									$wp_error = false
								);
								array_push( $terms_id, $lcp_cat_id );
							}
						}
						$terms_id = implode( ',', $terms_id );
						wp_set_post_terms( $new_shortcode_id, $terms_id, 'testimonial_cat' );
					}
					$url = isset( $shortcode['image'] ) && ! empty( $shortcode['image'] ) ? $shortcode['image'] : '';
					// Insert attachment id.
					$thumb_id                           = $this->insert_attachment_from_url( $url, $new_shortcode_id );
					$shortcode['meta']['_thumbnail_id'] = $thumb_id;
				}
				if ( is_wp_error( $new_shortcode_id ) ) {
					throw new \Exception( $new_shortcode_id->get_error_message() );
				}

				if ( isset( $shortcode['meta'] ) && is_array( $shortcode['meta'] ) ) {
					foreach ( $shortcode['meta'] as $key => $value ) {
						update_post_meta(
							$new_shortcode_id,
							$key,
							maybe_unserialize( str_replace( '{#ID#}', $new_shortcode_id, $value ) )
						);
					}
				}
			} catch ( \Exception $e ) {
				array_push( $errors[ $index ], $e->getMessage() );

				// If there was a failure somewhere, clean up.
				wp_trash_post( $new_shortcode_id );
			}

			// If no errors, remove the index.
			if ( ! count( $errors[ $index ] ) ) {
				unset( $errors[ $index ] );
			}

			// External modules manipulate data here.
			do_action( 'testimonial_pro_shortcode_imported', $new_shortcode_id );
		}

		$errors = reset( $errors );
		return isset( $errors[0] ) ? new \WP_Error( 'import_testimonial_error', $errors[0] ) : $spt_post_type;
	}

	/**
	 * Import Testimonials by ajax.
	 *
	 * @return void
	 */
	public function import_shortcodes() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'spftestimonial_options_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Error: Invalid nonce verification.', 'testimonial-pro' ) ), 401 );
		}
		$data       = isset( $_POST['shortcode'] ) ? wp_kses_data( wp_unslash( $_POST['shortcode'] ) ) : '';
		$data       = json_decode( $data );
		$data       = json_decode( $data, true );
		$shortcodes = $data['shortcode'];
		if ( ! $data ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nothing to import.', 'testimonial-pro' ),
				),
				400
			);
		}

		$status = $this->import( $shortcodes );

		if ( is_wp_error( $status ) ) {
			wp_send_json_error(
				array(
					'message' => $status->get_error_message(),
				),
				400
			);
		}

		wp_send_json_success( $status, 200 );
	}
}
