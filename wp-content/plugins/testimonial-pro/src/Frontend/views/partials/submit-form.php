<?php
// Testimonial submit form.
if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && 'testimonial_form' . $form_id === $_POST['action'] ) {
	$pid   = false;
	$nonce = isset( $_POST['testimonial_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['testimonial_form_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'testimonial_form' ) ) {
		$tpro_client_name            = isset( $_POST['tpro_client_name'] ) ? wp_strip_all_tags( wp_unslash( $_POST['tpro_client_name'] ) ) : '';
		$tpro_client_email           = isset( $_POST['tpro_client_email'] ) ? sanitize_email( wp_unslash( $_POST['tpro_client_email'] ) ) : '';
		$tpro_client_designation     = isset( $_POST['tpro_client_designation'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_designation'] ) ) : '';
		$tpro_company_name           = isset( $_POST['tpro_client_company_name'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_company_name'] ) ) : '';
		$tpro_location               = isset( $_POST['tpro_client_location'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_location'] ) ) : '';
		$tpro_phone                  = isset( $_POST['tpro_client_phone'] ) ? preg_replace( '/[^0-9+-]/', '', sanitize_text_field( wp_unslash( $_POST['tpro_client_phone'] ) ) ) : '';
		$tpro_website                = isset( $_POST['tpro_client_website'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_website'] ) ) ) : '';
		$tpro_video_url              = isset( $_POST['tpro_client_video_url'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_video_url'] ) ) ) : '';
		$tpro_client_testimonial_cat = isset( $_POST['tpro_client_testimonial_cat'] ) ? wp_unslash( $_POST['tpro_client_testimonial_cat'] ) : '';
		$tpro_testimonial_title      = isset( $_POST['tpro_testimonial_title'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_testimonial_title'] ) ) : '';
		$tpro_testimonial_text       = isset( $_POST['tpro_client_testimonial'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tpro_client_testimonial'] ) ) : '';
		$tpro_rating_star            = isset( $_POST['tpro_client_rating'] ) ? sanitize_key( $_POST['tpro_client_rating'] ) : '';
		$tpro_client_video_upload    = isset( $_POST['tpro_client_video_upload'] ) ? sanitize_key( $_POST['tpro_client_video_upload'] ) : '';
		$tpro_social_profiles        = isset( $_POST['tpro_social_profiles'] ) ? wp_unslash( $_POST['tpro_social_profiles'] ) : '';

		$tpro_client_checkbox = isset( $_POST['tpro_client_checkbox'] ) && $_POST['tpro_client_checkbox'] ? '1' : '0';
		// ADD THE FORM INPUT TO $testimonial_form ARRAY.
		$testimonial_form = array(
			'post_title'   => $tpro_testimonial_title,
			'post_content' => $tpro_testimonial_text,
			'post_status'  => $form_data['testimonial_approval_status'],
			'post_type'    => 'spt_testimonial',
			'meta_input'   => array(
				'sp_tpro_meta_options' => array(
					'tpro_name'            => $tpro_client_name,
					'tpro_email'           => $tpro_client_email,
					'tpro_designation'     => $tpro_client_designation,
					'tpro_company_name'    => $tpro_company_name,
					'tpro_location'        => $tpro_location,
					'tpro_phone'           => $tpro_phone,
					'tpro_website'         => $tpro_website,
					'tpro_video_url'       => $tpro_video_url,
					'tpro_rating'          => $tpro_rating_star,
					'tpro_social_profiles' => $tpro_social_profiles,
					'tpro_client_checkbox' => $tpro_client_checkbox,
				),
			),
		);

		$tpro_redirect = $form_data['tpro_redirect'];
		if ( in_array( 'recaptcha', $form_element ) && ( '' !== $setting_options['captcha_site_key'] || '' !== $captcha_site_key_v3 ) && ( '' !== $setting_options['captcha_secret_key'] || '' !== $captcha_secret_key_v3 ) ) {
			// Empty MSG.
			$captcha_error_msg = '';
			$validation_msg    = '';
			$response_data2    = false;
			if ( isset( $_POST['submit'] ) && ! empty( $_POST['submit'] ) ) {
				// Recaptcha v3.
				if ( 'v3' === $captcha_version ) {
					$response_token = isset( $_POST['token'] ) ? $_POST['token'] : '';
					$secret         = $captcha_secret_key_v3;
				} else {
					$response_token = isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '';
					$secret         = $setting_options['captcha_secret_key'];
				}
				if ( ! empty( $response_token ) ) {
					$pid = wp_insert_post( $testimonial_form );
					// Get verify response data.
					$verify_response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $response_token );
					$response_data   = json_decode( $verify_response['body'], true );
					$response_data2  = json_decode( $response_data['success'], true );
					if ( $response_data2 ) {
						// Save The Testimonial.
						if ( $pid ) {
							wp_set_post_terms( $pid, $tpro_client_testimonial_cat, 'testimonial_cat' );

							// Thanks message.
							switch ( $tpro_redirect ) {
								case 'to_a_page':
									self::tpro_redirect( get_page_link( $form_data['tpro_redirect_to_page'] ) );
									break;
								case 'custom_url':
									self::tpro_redirect( esc_url( $form_data['tpro_redirect_custom_url'] ) );
									break;
								default:
									$validation_msg .= $form_data['successful_message'];
									break;
							}
						}
					} else {
						$captcha_error_msg .= esc_html__( 'Robot verification failed, please try again.', 'testimonial-pro' );
					}
				} else {
					$captcha_error_msg .= esc_html__( 'Please click on the reCAPTCHA box.', 'testimonial-pro' );
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
						self::tpro_redirect( get_page_link( $form_data['tpro_redirect_to_page'] ) );
						break;
					case 'custom_url':
						self::tpro_redirect( esc_url( $form_data['tpro_redirect_custom_url'] ) );
						break;
					default:
						$validation_msg .= $form_data['successful_message'];
						self::tpro_redirect( get_page_link() . '#submit' );
						break;
				}
			}
		}
		// Client Image and video.
		if ( ! function_exists( 'wp_generate_attachment_metadata' ) || class_exists( 'Astra_Sites_Importer' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}

		if ( $pid ) {
			if ( $_FILES ) {
				foreach ( $_FILES as $file => $array ) {
					if ( UPLOAD_ERR_OK !== $_FILES[ $file ]['error'] ) {
					} else {
						$attach_id = media_handle_upload( $file, $pid );
						if ( ! is_wp_error( $attach_id ) && $attach_id > 0 ) {
							// Set post image.
							if ( 'video/mp4' === $array['type'] || 'video/webm' === $array['type'] || 'video/x-matroska' === $array['type'] ) {
								$testimonial_data                   = get_post_meta( $pid, 'sp_tpro_meta_options', true );
								$testimonial_data['tpro_video_url'] = wp_get_attachment_url( $attach_id );
								update_post_meta( $pid, 'sp_tpro_meta_options', $testimonial_data );
							} else {
								update_post_meta( $pid, '_thumbnail_id', $attach_id );
							}
						}
					}
				}
			}
		}

		/**
		 * Email notification.
		 */
		if ( $form_data['submission_email_notification'] && ( ( in_array( 'recaptcha', $form_element ) && $response_data2 ) || ! in_array( 'recaptcha', $form_element ) ) ) {

			$tpro_category = '';
			if ( $tpro_client_testimonial_cat ) {
				foreach ( $tpro_client_testimonial_cat as $testimonial_cat_term ) {
					$tpro_category_name[] = get_the_category_by_ID( $testimonial_cat_term );
				}
				$tpro_category = implode( ', ', $tpro_category_name );
			}
			$tpro_empty_star = '<span style="color: #d4d4d4;font-size: 17px;">&#x2605;</span>';
			$tpro_fil_star   = '<span style="color: #FF9800;font-size: 17px;">&#x2605;</span>';
			switch ( $tpro_rating_star ) {
				case 'one_star':
					$tpro_rating = $tpro_fil_star . $tpro_empty_star . $tpro_empty_star . $tpro_empty_star . $tpro_empty_star;
					break;
				case 'two_star':
					$tpro_rating = $tpro_fil_star . $tpro_fil_star . $tpro_empty_star . $tpro_empty_star . $tpro_empty_star;
					break;
				case 'three_star':
					$tpro_rating = $tpro_fil_star . $tpro_fil_star . $tpro_fil_star . $tpro_empty_star . $tpro_empty_star;
					break;
				case 'four_star':
					$tpro_rating = $tpro_fil_star . $tpro_fil_star . $tpro_fil_star . $tpro_fil_star . $tpro_empty_star;
					break;
				case 'five_star':
					$tpro_rating = $tpro_fil_star . $tpro_fil_star . $tpro_fil_star . $tpro_fil_star . $tpro_fil_star;
					break;
				default:
					$tpro_rating = '';
					break;
			}

			$subject = $form_data['submission_email_subject'];
			$heading = $form_data['submission_email_heading'];

			$email_to_list    = $form_data['submission_email_notification_to'];
			$email_to         = explode( ',', $email_to_list );
			$admin_email      = get_option( 'admin_email' );
			$site_name        = get_bloginfo( 'name' );
			$site_description = '- ' . get_bloginfo( 'description' );

			$message  = '';
			$message .= '<div style="background-color: #f6f6f6;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;">';
			$message .= '<div style="width: 100%;margin: 0;padding: 70px 0 70px 0;">';
			$message .= '<div style="background-color: #ffffff;border: 1px solid #e9e9e9;border-radius: 2px!important;padding: 20px 20px 10px 20px;width: 520px;margin: 0 auto;">';
			$message .= '<h1 style="color: #000000;margin: 0;padding: 28px 24px;font-size: 32px;font-weight: 500;text-align: center;">' . $heading . '</h1>';
			$message .= '<div style="padding: 30px 20px 40px;">';

			$message_content = $form_data['submission_email_body'];

			$message_content = str_replace( '{name}', $tpro_client_name, $message_content );
			$message_content = str_replace( '{email}', $tpro_client_email, $message_content );
			$message_content = str_replace( '{position}', $tpro_client_designation, $message_content );
			$message_content = str_replace( '{company_name}', $tpro_company_name, $message_content );
			$message_content = str_replace( '{location}', $tpro_location, $message_content );
			$message_content = str_replace( '{phone}', $tpro_phone, $message_content );
			$message_content = str_replace( '{website}', $tpro_website, $message_content );
			$message_content = str_replace( '{video_url}', $tpro_video_url, $message_content );
			$message_content = str_replace( '{testimonial_title}', $tpro_testimonial_title, $message_content );
			$message_content = str_replace( '{testimonial_text}', $tpro_testimonial_text, $message_content );
			$message_content = str_replace( '{groups}', $tpro_category, $message_content );
			$message_content = str_replace( '{rating}', $tpro_rating, $message_content );

			$message_content = apply_filters( 'testimonial_pro_email_preview_template_tags', $message_content );
			$message        .= wpautop( $message_content );
			$message        .= '</div>';

			$message .= '<div style="border-top:1px solid #efefef;padding:20px 0;clear:both;text-align:center"><small style="font-size:11px">' . $site_name . ' ' . $site_description . '</small></div>';

			$message .= '</div>';
			$message .= '</div>';
			$message .= '</div>';

			if ( ! empty( $tpro_client_email ) ) {
				$email_header = array(
					'Content-Type: text/html; charset=UTF-8',
					'From: "' . $tpro_client_name . '" < ' . $tpro_client_email . ' >',
				);
			} else {
				$email_header = array(
					'Content-Type: text/html; charset=UTF-8',
					'From: "' . $tpro_client_name . '" < ' . $admin_email . ' >',
				);
			}

			// Send email to.
			wp_mail( $email_to, $subject, $message, $email_header );
		}
	} else {
		wp_die( esc_html__( 'Our site is protected!', 'testimonial-pro' ) );
	}
	$_POST = array();
} // END THE IF STATEMENT THAT STARTED THE WHOLE FORM.
