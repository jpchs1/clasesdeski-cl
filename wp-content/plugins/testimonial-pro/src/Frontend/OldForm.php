<?php
/**
 * The OldForm class to manage old form for all public facing stuffs.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_pro.
 * @subpackage Testimonial_pro/Frontend.
 */

namespace ShapedPlugin\TestimonialPro\Frontend;

/**
 * OldForm testimonial form.
 */
class OldForm {
	/**
	 * Initialize the class.
	 */
	public function __construct() {
		// Old Testimonial form.
		add_shortcode( 'testimonial_pro_form', array( $this, 'form_shortcode_render' ) );
	}

	/**
	 * Old Testimonial form shortcode render.
	 *
	 * @param array $attributes attributes.
	 *
	 * @return string
	 * @since 2.0
	 */
	public function form_shortcode_render( $attributes ) {
		extract( shortcode_atts( array(), $attributes, 'testimonial_pro_form' ) );
		wp_enqueue_script( 'tpro-validate-js' );
		$setting_options = get_option( '_sp_options' );
		// Form Scripts and Styles.
		if ( '' !== $setting_options['captcha_site_key'] && '' !== $setting_options['captcha_secret_key'] ) {
			wp_enqueue_script( 'tpro-recaptcha-js' );
		}
		wp_enqueue_script( 'tpro-chosen-jquery' );
		wp_enqueue_script( 'tpro-chosen-config' );
		wp_enqueue_style( 'tpro-form' );
		$outline = '';
		// Testimonial submit form.
		$nonce = isset( $_POST['testimonial_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['testimonial_form_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'testimonial_form' ) && isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && 'old_testimonial_form' === $_POST['action'] ) {
			// Do some minor form validation to make sure there is content.
			$tpro_client_name                = isset( $_POST['tpro_client_name'] ) ? wp_strip_all_tags( wp_unslash( $_POST['tpro_client_name'] ) ) : '';
			$tpro_client_email               = isset( $_POST['tpro_client_email'] ) ? sanitize_email( wp_unslash( $_POST['tpro_client_email'] ) ) : '';
			$tpro_client_designation         = isset( $_POST['tpro_client_designation'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_designation'] ) ) : '';
			$tpro_client_company_name        = isset( $_POST['tpro_client_company_name'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_company_name'] ) ) : '';
			$tpro_client_location            = isset( $_POST['tpro_client_location'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_location'] ) ) : '';
			$tpro_client_phone               = isset( $_POST['tpro_client_phone'] ) ? preg_replace( '/[^0-9+-]/', '', sanitize_text_field( wp_unslash( $_POST['tpro_client_phone'] ) ) ) : '';
			$tpro_client_website             = isset( $_POST['tpro_client_website'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_website'] ) ) ) : '';
			$tpro_client_video_url           = isset( $_POST['tpro_client_video_url'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_video_url'] ) ) ) : '';
			$tpro_client_testimonial_cat     = isset( $_POST['tpro_client_testimonial_cat'] ) ? wp_unslash( $_POST['tpro_client_testimonial_cat'] ) : '';
			$tpro_testimonial_title          = isset( $_POST['tpro_testimonial_title'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_testimonial_title'] ) ) : '';
			$tpro_client_testimonial         = isset( $_POST['tpro_client_testimonial'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tpro_client_testimonial'] ) ) : '';
			$tpro_client_rating              = isset( $_POST['tpro_client_rating'] ) ? sanitize_key( $_POST['tpro_client_rating'] ) : '';
			$tpro_social_profile_facebook    = isset( $_POST['tpro_social_profile_facebook'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_facebook'] ) ) ) : '';
			$tpro_social_profile_twitter     = isset( $_POST['tpro_social_profile_twitter'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_twitter'] ) ) ) : '';
			$tpro_social_profile_linkedin    = isset( $_POST['tpro_social_profile_linkedin'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_linkedin'] ) ) ) : '';
			$tpro_social_profile_instagram   = isset( $_POST['tpro_social_profile_instagram'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_instagram'] ) ) ) : '';
			$tpro_social_profile_youtube     = isset( $_POST['tpro_social_profile_youtube'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_youtube'] ) ) ) : '';
			$tpro_social_profile_pinterest   = isset( $_POST['tpro_social_profile_pinterest'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_pinterest'] ) ) ) : '';
			$tpro_social_profile_skype       = isset( $_POST['tpro_social_profile_skype'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_skype'] ) ) ) : '';
			$tpro_social_profile_stumbleupon = isset( $_POST['tpro_social_profile_stumbleupon'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_stumbleupon'] ) ) ) : '';
			$tpro_social_profile_reddit      = isset( $_POST['tpro_social_profile_reddit'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_reddit'] ) ) ) : '';
			$tpro_social_profile_dribbble    = isset( $_POST['tpro_social_profile_dribbble'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_dribbble'] ) ) ) : '';
			$tpro_social_profile_snapchat    = isset( $_POST['tpro_social_profile_snapchat'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_social_profile_snapchat'] ) ) ) : '';

			// ADD THE FORM INPUT TO $testimonial_form ARRAY.
			$testimonial_form = array(
				'post_title'   => $tpro_testimonial_title,
				'post_content' => $tpro_client_testimonial,
				'post_status'  => $setting_options['testimonial_approval_status'],
				'post_type'    => 'spt_testimonial',
				'meta_input'   => array(
					'sp_tpro_meta_options' => array(
						'tpro_name'            => $tpro_client_name,
						'tpro_email'           => $tpro_client_email,
						'tpro_designation'     => $tpro_client_designation,
						'tpro_company_name'    => $tpro_client_company_name,
						'tpro_location'        => $tpro_client_location,
						'tpro_phone'           => $tpro_client_phone,
						'tpro_website'         => $tpro_client_website,
						'tpro_video_url'       => $tpro_client_video_url,
						'tpro_rating'          => $tpro_client_rating,
						'tpro_social_profiles' => array(
							array(
								'social_name' => 'facebook',
								'social_url'  => $tpro_social_profile_facebook,
							),
							array(
								'social_name' => 'twitter',
								'social_url'  => $tpro_social_profile_twitter,
							),
							array(
								'social_name' => 'linkedin',
								'social_url'  => $tpro_social_profile_linkedin,
							),
							array(
								'social_name' => 'instagram',
								'social_url'  => $tpro_social_profile_instagram,
							),
							array(
								'social_name' => 'youtube',
								'social_url'  => $tpro_social_profile_youtube,
							),
							array(
								'social_name' => 'pinterest',
								'social_url'  => $tpro_social_profile_pinterest,
							),
							array(
								'social_name' => 'skype',
								'social_url'  => $tpro_social_profile_skype,
							),
							array(
								'social_name' => 'stumbleupon',
								'social_url'  => $tpro_social_profile_stumbleupon,
							),
							array(
								'social_name' => 'reddit',
								'social_url'  => $tpro_social_profile_reddit,
							),
							array(
								'social_name' => 'dribbble',
								'social_url'  => $tpro_social_profile_dribbble,
							),
							array(
								'social_name' => 'snapchat',
								'social_url'  => $tpro_social_profile_snapchat,
							),
						),
					),
				),
			);

			$tpro_redirect = $setting_options['tpro_redirect'];

			if ( '' !== $setting_options['captcha_site_key'] && '' !== $setting_options['captcha_secret_key'] ) {
				// Empty MSG.
				$captcha_error_msg = '';
				$validation_msg    = '';

				if ( isset( $_POST['submit'] ) && ! empty( $_POST['submit'] ) ) {
					if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
						// your site secret key.
						$secret = $setting_options['captcha_secret_key'];
						// get verify response data.
						$verify_response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response'] );
						$response_data   = json_decode( $verify_response['body'], true );
						$response_data2  = json_decode( $response_data['success'], true );

						if ( $response_data2 ) {

							// Save The Testimonial.
							$pid = wp_insert_post( $testimonial_form );

							if ( $pid ) {
								wp_set_post_terms( $pid, $tpro_client_testimonial_cat, 'testimonial_cat' );

								// Thanks message.
								switch ( $tpro_redirect ) {
									case 'to_a_page':
										Helper::tpro_redirect( get_page_link( $setting_options['tpro_redirect_to_page'] ) );
										break;
									case 'custom_url':
										Helper::tpro_redirect( esc_url( $setting_options['tpro_redirect_custom_url'] ) );
										break;
									default:
										$validation_msg .= $setting_options['successful_message'];
										break;
								}
							}
						} else {
							$captcha_error_msg .= $setting_options['verification_fail_message'];
						}
					} else {
						$captcha_error_msg .= $setting_options['captcha_missing_message'];
					}
				}
			} else {
				// Empty MSG.
				$validation_msg = '';

				// Save The Testimonial.
				$pid = wp_insert_post( $testimonial_form );

				if ( $pid ) {
					wp_set_post_terms( $pid, $tpro_client_testimonial_cat, 'testimonial_cat' );

					// Thanks message.
					switch ( $tpro_redirect ) {
						case 'to_a_page':
							Helper::tpro_redirect( get_page_link( $setting_options['tpro_redirect_to_page'] ) );
							break;
						case 'custom_url':
							Helper::tpro_redirect( esc_url( $setting_options['tpro_redirect_custom_url'] ) );
							break;
						default:
							$validation_msg .= $setting_options['successful_message'];
							break;
					}
				}
			}

			// Client Image.
			if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
			}
			if ( $_FILES ) {
				foreach ( $_FILES as $file => $array ) {
					if ( UPLOAD_ERR_OK !== $_FILES[ $file ]['error'] ) {

					} else {
						$attach_id = media_handle_upload( $file, $pid );

						if ( $attach_id > 0 ) {
							// Set post image.
							update_post_meta( $pid, '_thumbnail_id', $attach_id );
						}
					}
				}
			}
		} // END THE IF STATEMENT THAT STARTED THE WHOLE FORM.

		$outline .= '<style>';
		// Form Style.
		$outline .= '.sp-tpro-fronted-old-form .sp-tpro-form-field label{
				color: ' . $setting_options['label_text_color'] . ' ;
				display: inline-block;
				max-width: 100%;
				margin-bottom: 5px;
				font-weight: 700;
			}
			.sp-tpro-fronted-old-form .sp-tpro-form-submit-button input[type="submit"]{
				background: ' . $setting_options['submit_button_bg'] . ';
				color: ' . $setting_options['submit_button_color'] . ';
				border: 0;
				padding: 8px 16px;
			}';
		$outline .= '</style>';

		$outline .= '<!-- Frontend Submission Form --> <div class="sp-tpro-fronted-form sp-tpro-fronted-old-form">
		<form id="old_testimonial_form" name="old_testimonial_form" method="post" action="" enctype="multipart/form-data">';
		if ( ! empty( $validation_msg ) && 'top' === $setting_options['tpro_message_position'] ) {
			$outline .= '<div class="sp-tpro-form-validation-msg">' . stripslashes( $validation_msg ) . '</div>';
		}
		if ( $setting_options['client_name_field'] ) {
			$client_name = '
				<!-- Name -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_name_label'] ) {
				$client_name .= '<label for="tpro_client_name">' . $setting_options['client_name_label'] . '</label><br>';
			}
			$client_name .= '<input type="text" id="tpro_client_name" name="tpro_client_name"';
			if ( $setting_options['client_name_required'] ) {
				$client_name .= 'required ';
			}
			if ( '' !== $setting_options['client_name_placeholder'] ) {
				$client_name .= 'placeholder="' . $setting_options['client_name_placeholder'] . '"';
			}
			$client_name .= '/>
				</div>';
		} else {
			$client_name = '';
		}
		if ( $setting_options['client_email_field'] ) {
			$client_email = '
				<!-- Email -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_email_label'] ) {
				$client_email .= '<label for="tpro_client_email">' . $setting_options['client_email_label'] . '</label><br>';
			}
			$client_email .= '<input type="email" name="tpro_client_email" id="tpro_client_email" ';
			if ( $setting_options['tpro_client_email_required'] ) {
				$client_email .= 'required ';
			}
			if ( '' !== $setting_options['client_email_placeholder'] ) {
				$client_email .= 'placeholder="' . $setting_options['client_email_placeholder'] . '"';
			}
			$client_email .= '/>
				</div>';
		} else {
			$client_email = '';
		}

		if ( $setting_options['client_designation_field'] ) {
			$client_designation = '
				<!-- Designation -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_designation_label'] ) {
				$client_designation .= '<label for="tpro_client_designation">' . $setting_options['client_designation_label'] . '</label><br>';
			}
			$client_designation .= '<input type="text" name="tpro_client_designation" id="tpro_client_designation" ';
			if ( $setting_options['client_designation_required'] ) {
				$client_designation .= 'required ';
			}
			if ( '' !== $setting_options['client_designation_placeholder'] ) {
				$client_designation .= 'placeholder="' . $setting_options['client_designation_placeholder'] . '"';
			}
			$client_designation .= '/>
				</div>';
		} else {
			$client_designation = '';
		}

		if ( $setting_options['client_company_name_field'] ) {
			$client_company_name = '
				<!-- Company Name -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_company_name_label'] ) {
				$client_company_name .= '<label for="tpro_client_company_name">' . $setting_options['client_company_name_label'] . '</label><br>';
			}
			$client_company_name .= '<input type="text" name="tpro_client_company_name" id="tpro_client_company_name" ';
			if ( $setting_options['client_company_name_required'] ) {
				$client_company_name .= 'required ';
			}
			if ( '' !== $setting_options['client_company_name_placeholder'] ) {
				$client_company_name .= 'placeholder="' . $setting_options['client_company_name_placeholder'] . '"';
			}
			$client_company_name .= '/>
				</div>';
		} else {
			$client_company_name = '';
		}

		if ( $setting_options['client_location_field'] ) {
			$client_location = '
				<!-- Location -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_location_label'] ) {
				$client_location .= '<label for="tpro_client_location">' . $setting_options['client_location_label'] . '</label><br>';
			}
			$client_location .= '<input type="text" name="tpro_client_location" id="tpro_client_location" ';
			if ( $setting_options['client_location_required'] ) {
				$client_location .= 'required ';
			}
			if ( '' !== $setting_options['client_location_placeholder'] ) {
				$client_location .= 'placeholder="' . $setting_options['client_location_placeholder'] . '"';
			}
			$client_location .= '/>
				</div>';
		} else {
			$client_location = '';
		}

		if ( $setting_options['client_phone_field'] ) {
			$client_phone  = '
				<!-- Phone -->
				<div class="sp-tpro-form-field">';
			$client_phone .= '<label for="tpro_client_phone">' . $setting_options['client_phone_label'] . '</label><br>';
			$client_phone .= '<input type="text" name="tpro_client_phone" id="tpro_client_phone" ';
			if ( $setting_options['client_phone_required'] ) {
				$client_phone .= 'required ';
			}
			if ( '' !== $setting_options['client_phone_placeholder'] ) {
				$client_phone .= 'placeholder="' . $setting_options['client_phone_placeholder'] . '"';
			}
			$client_phone .= '/>
				</div>';
		} else {
			$client_phone = '';
		}

		if ( $setting_options['client_website_field'] ) {
			$client_website = '
				<!-- Website -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_website_label'] ) {
				$client_website .= '<label for="tpro_client_website">' . $setting_options['client_website_label'] . '</label><br>';
			}
			$client_website .= '<input type="text" name="tpro_client_website" id="tpro_client_website" ';
			if ( $setting_options['client_website_required'] ) {
				$client_website .= 'required ';
			}
			if ( '' !== $setting_options['client_website_placeholder'] ) {
				$client_website .= 'placeholder="' . $setting_options['client_website_placeholder'] . '"';
			}
			$client_website .= '/>
				</div>';
		} else {
			$client_website = '';
		}

		if ( $setting_options['client_video_url_field'] ) {
			$client_video_url = '
				<!-- Video URL -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_video_url_label'] ) {
				$client_video_url .= '<label for="tpro_client_video_url">' . $setting_options['client_video_url_label'] . '</label><br>';
			}
			$client_video_url .= '<input type="text" name="tpro_client_video_url" id="tpro_client_video_url" ';
			if ( $setting_options['client_video_url_required'] ) {
				$client_video_url .= 'required ';
			}
			if ( '' !== $setting_options['client_video_url_placeholder'] ) {
				$client_video_url .= 'placeholder="' . $setting_options['client_video_url_placeholder'] . '"';
			}
			$client_video_url .= '/>
				</div>';
		} else {
			$client_video_url = '';
		}

		if ( $setting_options['client_image_field'] ) {
			$client_image = '
				<!-- Image -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_image_label'] ) {
				$client_image .= '<label for="tpro_client_image">' . $setting_options['client_image_label'] . '</label><br>';
			}
			$client_image .= '<input type="file" name="tpro_client_image" id="tpro_client_image" ';
			if ( $setting_options['client_image_required'] ) {
				$client_image .= 'required ';
			}
			$client_image .= 'accept="image/jpeg,image/jpg,image/png">
				</div>';
		} else {
			$client_image = '';
		}

		if ( $setting_options['client_category_field'] ) {
			$client_testimonial_cat = '
				<!-- Category -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_category_label'] ) {
				$client_testimonial_cat .= '<label for="tpro_client_testimonial_cat">' . $setting_options['client_category_label'] . '</label><br>';
			}

			$client_selected_categories = $setting_options['client_selected_category'];

			if ( ! empty( $client_selected_categories ) ) {
				$client_testimonial_cat .= '<select name="tpro_client_testimonial_cat[]" id="tpro_client_testimonial_cat" class="chosen-select" data-placeholder="' . $setting_options['client_category_placeholder'] . '" ';
				if ( $setting_options['client_category_multiple'] ) {
					$client_testimonial_cat .= 'multiple="multiple" ';
				}
				$client_testimonial_cat .= 'data-depend-id="tpro_client_testimonial_cat">';
				foreach ( $client_selected_categories as $cat_id ) {
					$term                    = get_term( $cat_id );
					$client_testimonial_cat .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
				}
				$client_testimonial_cat .= '</select>';

			} else {
				$terms = get_terms(
					'testimonial_cat',
					array(
						'hide_empty' => 0,
					)
				);
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$client_testimonial_cat .= '<select name="tpro_client_testimonial_cat[]" id="tpro_client_testimonial_cat" class="chosen-select" data-placeholder="' . $setting_options['client_category_placeholder'] . '" ';
					if ( $setting_options['client_category_multiple'] ) {
						$client_testimonial_cat .= 'multiple="multiple" ';
					}
					$client_testimonial_cat .= 'data-depend-id="tpro_client_testimonial_cat">';
					foreach ( $terms as $term ) {
						$client_testimonial_cat .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
					}
					$client_testimonial_cat .= '</select>';
				}
			}

			$client_testimonial_cat .= '</div>';
		} else {
			$client_testimonial_cat = '';
		}

		if ( $setting_options['client_title_field'] ) {
			$client_testimonial_title = '
				<!-- Title -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_title_label'] ) {
				$client_testimonial_title .= '<label for="tpro_testimonial_title">' . $setting_options['client_title_label'] . '</label><br>';
			}
			$client_testimonial_title .= '<input type="text" name="tpro_testimonial_title" id="tpro_testimonial_title" ';
			if ( $setting_options['client_title_required'] ) {
				$client_testimonial_title .= 'required ';
			}
			if ( '' !== $setting_options['client_title_placeholder'] ) {
				$client_testimonial_title .= 'placeholder="' . $setting_options['client_title_placeholder'] . '"';
			}
			$client_testimonial_title .= '/>
				</div>';
		} else {
			$client_testimonial_title = '';
		}

		if ( $setting_options['client_testimonial_field'] ) {
			$client_testimonial = '
				<!-- Testimonial -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_testimonial_label'] ) {
				$client_testimonial .= '<label for="tpro_client_testimonial">' . $setting_options['client_testimonial_label'] . '</label><br>';
			}
			$client_testimonial .= '<textarea id="tpro_client_testimonial" name="tpro_client_testimonial" ';
			if ( $setting_options['client_testimonial_required'] ) {
				$client_testimonial .= 'required ';
			}
			if ( '' !== $setting_options['client_testimonial_placeholder'] ) {
				$client_testimonial .= 'placeholder="' . $setting_options['client_testimonial_placeholder'] . '"';
			}
			$client_testimonial .= '></textarea>
				</div>';
		} else {
			$client_testimonial = '';
		}

		if ( $setting_options['client_rating_field'] ) {
			$client_star_rating = '
				<!-- Rating -->
				<div class="sp-tpro-form-field sp-tpro-rating-field">';
			if ( '' !== $setting_options['client_rating_label'] ) {
				$client_star_rating .= '<label for="tpro_client_rating">' . $setting_options['client_rating_label'] . '</label><br>';
			}
			$client_star_rating .= '<div class="sp-tpro-client-rating">
							<input type="radio" name="tpro_client_rating" id="_tpro_rating_5" value="five_star">
							<label for="_tpro_rating_5" title="' . $setting_options['client_rating_five'] . '"><i class="fa fa-star"></i></label>
							<input type="radio" name="tpro_client_rating" id="_tpro_rating_4" value="four_star">
							<label for="_tpro_rating_4" title="' . $setting_options['client_rating_four'] . '"><i class="fa fa-star"></i></label>
							<input type="radio" name="tpro_client_rating" id="_tpro_rating_3" value="three_star">
							<label for="_tpro_rating_3" title="' . $setting_options['client_rating_three'] . '"><i class="fa fa-star"></i></label>
							<input type="radio" name="tpro_client_rating" id="_tpro_rating_2" value="two_star">
							<label for="_tpro_rating_2" title="' . $setting_options['client_rating_two'] . '"><i class="fa fa-star"></i></label>
							<input type="radio" name="tpro_client_rating" id="_tpro_rating_1" value="one_star">
							<label for="_tpro_rating_1" title="' . $setting_options['client_rating_one'] . '"><i class="fa fa-star"></i></label>
						</div>
				</div>';
		} else {
			$client_star_rating = '';
		}

		if ( $setting_options['client_social_profile_field'] ) {
			$client_social_profile = '
				<!-- Social Profile -->
				<div class="sp-tpro-form-field">';
			if ( '' !== $setting_options['client_social_profile_label'] ) {
				$client_social_profile .= '<label for="tpro_social_profile">' . $setting_options['client_social_profile_label'] . '</label>';
			}
			$client_social_profile .= '<input type="checkbox" class="tpro-social-profile-check" name="tpro_social_profile_check" value="1">
				<div class="tpro-social-profile-links" style="display: none;">';
			if ( $setting_options['client_social_profile_facebook'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_facebook_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_facebook">' . $setting_options['client_social_profile_facebook_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_facebook" id="tpro_social_profile_facebook"
placeholder="' . $setting_options['client_social_profile_facebook_placeholder'] . '"><span><i class="fa fa-facebook"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_twitter'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_twitter_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_twitter">' . $setting_options['client_social_profile_twitter_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_twitter" id="tpro_social_profile_twitter"
placeholder="' . $setting_options['client_social_profile_twitter_placeholder'] . '"><span><i class="fa fa-twitter"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_linkedin'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_linkedin_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_linkedin">' . $setting_options['client_social_profile_linkedin_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_linkedin" id="tpro_social_profile_linkedin" placeholder="' . $setting_options['client_social_profile_linkedin_placeholder'] . '"><span><i class="fa fa-linkedin"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_instagram'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_instagram_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_instagram">' . $setting_options['client_social_profile_instagram_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_instagram" id="tpro_social_profile_instagram" placeholder="' . $setting_options['client_social_profile_instagram_placeholder'] . '"><span><i class="fa fa-instagram"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_youtube'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_youtube_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_youtube">' . $setting_options['client_social_profile_youtube_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_youtube" id="tpro_social_profile_youtube" placeholder="' . $setting_options['client_social_profile_youtube_placeholder'] . '"><span><i class="fa fa-youtube"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_pinterest'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_pinterest_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_pinterest">' . $setting_options['client_social_profile_pinterest_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_pinterest" id="tpro_social_profile_pinterest" placeholder="' . $setting_options['client_social_profile_pinterest_placeholder'] . '"><span><i class="fa fa-pinterest-p"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_skype'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_skype_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_skype">' . $setting_options['client_social_profile_skype_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_skype" id="tpro_social_profile_skype" placeholder="' . $setting_options['client_social_profile_skype_placeholder'] . '"><span><i class="fa fa-skype"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_stumbleupon'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_stumbleupon_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_stumbleupon">' . $setting_options['client_social_profile_stumbleupon_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_stumbleupon" id="tpro_social_profile_stumbleupon" placeholder="' . $setting_options['client_social_profile_stumbleupon_placeholder'] . '"><span><i class="fa fa-stumbleupon"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_reddit'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_reddit_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_reddit">' . $setting_options['client_social_profile_reddit_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_reddit" id="tpro_social_profile_reddit" placeholder="' . $setting_options['client_social_profile_reddit_placeholder'] . '"><span><i class="fa fa-reddit"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_dribbble'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_dribbble_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_dribbble">' . $setting_options['client_social_profile_dribbble_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_dribbble" id="tpro_social_profile_dribbble" placeholder="' . $setting_options['client_social_profile_dribbble_placeholder'] . '"><span><i class="fa fa-dribbble"></i></span>
					</div>';
			}
			if ( $setting_options['client_social_profile_snapchat'] ) {
				$client_social_profile .= '
					<div class="tpro-social-profile-link">';
				if ( '' !== $setting_options['client_social_profile_snapchat_label'] ) {
					$client_social_profile .= '<label for="tpro_social_profile_snapchat">' . $setting_options['client_social_profile_snapchat_label'] . '</label><br>';
				}
				$client_social_profile .= '<input type="text" name="tpro_social_profile_snapchat" id="tpro_social_profile_snapchat" placeholder="' . $setting_options['client_social_profile_snapchat_placeholder'] . '"><span><i class="fa fa-snapchat"></i></span>
					</div>';
			}
			$client_social_profile .= '</div>
				</div>';
		} else {
			$client_social_profile = '';
		}

		$sorter_data = $setting_options['fields_sorter'];
		$enabled     = $sorter_data['enabled'];

		if ( $enabled ) {
			foreach ( $enabled as $key => $value ) {

				switch ( $key ) {

					case 'name':
						$outline .= $client_name;
						break;
					case 'email':
						$outline .= $client_email;
						break;
					case 'designation':
						$outline .= $client_designation;
						break;
					case 'company_name':
						$outline .= $client_company_name;
						break;
					case 'location':
						$outline .= $client_location;
						break;
					case 'phone':
						$outline .= $client_phone;
						break;
					case 'website':
						$outline .= $client_website;
						break;
					case 'video-url':
						$outline .= $client_video_url;
						break;
					case 'image':
						$outline .= $client_image;
						break;
					case 'category':
						$outline .= $client_testimonial_cat;
						break;
					case 'title':
						$outline .= $client_testimonial_title;
						break;
					case 'testimonial':
						$outline .= $client_testimonial;
						break;
					case 'rating':
						$outline .= $client_star_rating;
						break;
					case 'social-profile':
						$outline .= $client_social_profile;
						break;
				}
			}
		}

		if ( '' !== $setting_options['captcha_site_key'] && '' !== $setting_options['captcha_secret_key'] ) {
			$outline .= '<div class="sp-tpro-form-field">
				<div class="g-recaptcha" data-sitekey="' . $setting_options['captcha_site_key'] . '"></div>';
			if ( ! empty( $captcha_error_msg ) ) {
				$outline .= '<span class="sp-tpro-form-error-msg">' . $captcha_error_msg . '</span>';
			}
			$outline .= '</div>';
		}

		$outline .= '<div class="sp-tpro-form-submit-button">
				<input type="submit" value="' . $setting_options['submit_button_text'] . '" id="submit" name="submit"/>
			</div>
			<input type="hidden" name="action" value="old_testimonial_form"/>';
		$outline .= wp_nonce_field( 'testimonial_form', 'testimonial_form_nonce', true, false );

		if ( ! empty( $validation_msg ) && 'bottom' === $setting_options['tpro_message_position'] ) {
			$outline .= '<div class="sp-tpro-form-validation-msg">' . stripslashes( $validation_msg ) . '</div>';
		}

		$outline .= '</form>
	</div> <!-- END tp-fronted-form -->';

		return $outline;

	}
}
