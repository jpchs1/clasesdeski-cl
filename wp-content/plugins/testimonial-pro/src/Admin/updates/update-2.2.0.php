<?php
/**
 * Update version.
 */
update_option( 'testimonial_pro_version', '2.2.0' );
update_option( 'testimonial_pro_db_version', '2.2.0' );

$old_option                      = get_option( '_sp_options' );
$tpro_dequeue_google_fonts       = $old_option['tpro_dequeue_google_fonts'];
$testimonial_data_remove         = $old_option['testimonial_data_remove'];
$tpro_dequeue_slick_js           = $old_option['tpro_dequeue_slick_js'];
$tpro_dequeue_isotope_js         = $old_option['tpro_dequeue_isotope_js'];
$tpro_dequeue_magnific_popup_js  = $old_option['tpro_dequeue_magnific_popup_js'];
$tpro_dequeue_slick_css          = $old_option['tpro_dequeue_slick_css'];
$tpro_dequeue_fa_css             = $old_option['tpro_dequeue_fa_css'];
$tpro_dequeue_magnific_popup_css = $old_option['tpro_dequeue_magnific_popup_css'];
$tpro_singular_name              = $old_option['tpro_singular_name'];
$tpro_plural_name                = $old_option['tpro_plural_name'];
$tpro_group_singular_name        = $old_option['tpro_group_singular_name'];
$tpro_group_plural_name          = $old_option['tpro_group_plural_name'];
$captcha_site_key                = $old_option['captcha_site_key'];
$captcha_secret_key              = $old_option['captcha_secret_key'];
$custom_css                      = $old_option['custom_css'];
$old_options                     = array(
	'tpro_dequeue_google_fonts'       => $tpro_dequeue_google_fonts,
	'testimonial_data_remove'         => $testimonial_data_remove,
	'tpro_dequeue_slick_js'           => $tpro_dequeue_slick_js,
	'tpro_dequeue_isotope_js'         => $tpro_dequeue_isotope_js,
	'tpro_dequeue_magnific_popup_js'  => $tpro_dequeue_magnific_popup_js,
	'tpro_dequeue_slick_css'          => $tpro_dequeue_slick_css,
	'tpro_dequeue_fa_css'             => $tpro_dequeue_fa_css,
	'tpro_dequeue_magnific_popup_css' => $tpro_dequeue_magnific_popup_css,
	'tpro_singular_name'              => $tpro_singular_name,
	'tpro_plural_name'                => $tpro_plural_name,
	'tpro_group_singular_name'        => $tpro_group_singular_name,
	'tpro_group_plural_name'          => $tpro_group_plural_name,
	'captcha_site_key'                => $captcha_site_key,
	'captcha_secret_key'              => $captcha_secret_key,
	'custom_css'                      => $custom_css,
);
update_option( 'sp_testimonial_pro_options', $old_options );

/**
 * Shortcode query for id.
 */
$args          = new WP_Query(
	array(
		'post_type'      => 'sp_tpro_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {
		$shortcode_data = get_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', true );

		$layout               = isset( $shortcode_data['layout'] ) ? $shortcode_data['layout'] : '';
		$column_large_desktop = isset( $shortcode_data['number_of_testimonials'] ) ? $shortcode_data['number_of_testimonials'] : '';
		$column_desktop       = isset( $shortcode_data['number_of_testimonials_desktop'] ) ? $shortcode_data['number_of_testimonials_desktop'] : '';
		$column_laptop        = isset( $shortcode_data['number_of_testimonials_small_desktop'] ) ? $shortcode_data['number_of_testimonials_small_desktop'] : '';
		$column_tablet        = isset( $shortcode_data['number_of_testimonials_tablet'] ) ? $shortcode_data['number_of_testimonials_tablet'] : '';
		$column_mobile        = isset( $shortcode_data['number_of_testimonials_mobile'] ) ? $shortcode_data['number_of_testimonials_mobile'] : '';
		$slider_mode          = isset( $shortcode_data['slider_ticker_mode'] ) ? $shortcode_data['slider_ticker_mode'] : '';
		$rtl_mode             = isset( $shortcode_data['rtl_mode'] ) ? $shortcode_data['rtl_mode'] : '';
		$slider_direction     = ( true == $rtl_mode ) ? 'rtl' : 'ltr';
		$client_image_width   = isset( $shortcode_data['client_image_width'] ) ? $shortcode_data['client_image_width'] : '';
		$client_image_height  = isset( $shortcode_data['client_image_height'] ) ? $shortcode_data['client_image_height'] : '';
		$client_image_crop    = isset( $shortcode_data['client_image_crop'] ) ? $shortcode_data['client_image_crop'] : '';
		$image_crop           = $client_image_crop ? 'hard-crop' : 'soft-crop';
		$image_padding        = isset( $shortcode_data['client_image_padding'] ) ? $shortcode_data['client_image_padding'] : '0';
		$image_border_size    = isset( $shortcode_data['client_image_border_size'] ) ? $shortcode_data['client_image_border_size'] : '0';
		$image_border_color   = isset( $shortcode_data['client_image_border_color'] ) ? $shortcode_data['client_image_border_color'] : '#dddddd';

		/**
		 * Testimonial section title.
		 */
		$section_title_margin_bottom = isset( $shortcode_data['section_title_margin_bottom'] ) ? $shortcode_data['section_title_margin_bottom'] : '';
		if ( ! empty( $section_title_margin_bottom ) ) {
			$section_title_margin                       = array(
				'margin-bottom' => $section_title_margin_bottom,
			);
			$section_title_typo                         = isset( $shortcode_data['section_title_typography'] ) ? $shortcode_data['section_title_typography'] : '';
			$section_title_typography                   = array_merge( $section_title_typo, $section_title_margin );
			$shortcode_data['section_title_typography'] = $section_title_typography;
			unset( $shortcode_data['section_title_margin_bottom'] );
		}

		/**
		 * Testimonial title.
		 */
		$testimonial_title_margin = isset( $shortcode_data['testimonial_title_margin'] ) ? $shortcode_data['testimonial_title_margin'] : '';
		if ( ! empty( $testimonial_title_margin ) ) {
			$title_margin                                   = array(
				'margin-top'    => $testimonial_title_margin['top'],
				'margin-right'  => $testimonial_title_margin['right'],
				'margin-bottom' => $testimonial_title_margin['bottom'],
				'margin-left'   => $testimonial_title_margin['left'],
			);
			$testimonial_title_typo                         = isset( $shortcode_data['testimonial_title_typography'] ) ? $shortcode_data['testimonial_title_typography'] : '';
			$testimonial_title_typography                   = array_merge( $testimonial_title_typo, $title_margin );
			$shortcode_data['testimonial_title_typography'] = $testimonial_title_typography;
			unset( $shortcode_data['testimonial_title_margin'] );
		}

		/**
		 * Testimonial content.
		 */
		$testimonial_content_margin = isset( $shortcode_data['testimonial_text_margin'] ) ? $shortcode_data['testimonial_text_margin'] : '';
		if ( ! empty( $testimonial_content_margin ) ) {
			$content_margin                                = array(
				'margin-top'    => $testimonial_content_margin['top'],
				'margin-right'  => $testimonial_content_margin['right'],
				'margin-bottom' => $testimonial_content_margin['bottom'],
				'margin-left'   => $testimonial_content_margin['left'],
			);
			$testimonial_content_typo                      = isset( $shortcode_data['testimonial_text_typography'] ) ? $shortcode_data['testimonial_text_typography'] : '';
			$testimonial_content_typography                = array_merge( $testimonial_content_typo, $content_margin );
			$shortcode_data['testimonial_text_typography'] = $testimonial_content_typography;
			unset( $shortcode_data['testimonial_text_margin'] );
		}

		/**
		 * Full name.
		 */
		$client_name_margin = isset( $shortcode_data['testimonial_client_name_margin'] ) ? $shortcode_data['testimonial_client_name_margin'] : '';
		if ( ! empty( $client_name_margin ) ) {
			$testimonial_client_name_margin           = array(
				'margin-top'    => $client_name_margin['top'],
				'margin-right'  => $client_name_margin['right'],
				'margin-bottom' => $client_name_margin['bottom'],
				'margin-left'   => $client_name_margin['left'],
			);
			$client_name_typo                         = isset( $shortcode_data['client_name_typography'] ) ? $shortcode_data['client_name_typography'] : '';
			$client_name_typography                   = array_merge( $client_name_typo, $testimonial_client_name_margin );
			$shortcode_data['client_name_typography'] = $client_name_typography;
			unset( $shortcode_data['testimonial_client_name_margin'] );
		}

		/**
		 * Designation.
		 */
		$designation_margin = isset( $shortcode_data['client_designation_company_margin'] ) ? $shortcode_data['client_designation_company_margin'] : '';
		if ( ! empty( $designation_margin ) ) {
			$client_designation_margin                               = array(
				'margin-top'    => $designation_margin['top'],
				'margin-right'  => $designation_margin['right'],
				'margin-bottom' => $designation_margin['bottom'],
				'margin-left'   => $designation_margin['left'],
			);
			$designation_typo                                        = isset( $shortcode_data['client_designation_company_typography'] ) ? $shortcode_data['client_designation_company_typography'] : '';
			$designation_typography                                  = array_merge( $designation_typo, $client_designation_margin );
			$shortcode_data['client_designation_company_typography'] = $designation_typography;
			unset( $shortcode_data['client_designation_company_margin'] );
		}

		/**
		 * Location.
		 */
		$location_margin = isset( $shortcode_data['testimonial_client_location_margin'] ) ? $shortcode_data['testimonial_client_location_margin'] : '';
		if ( ! empty( $location_margin ) ) {
			$client_location_margin                       = array(
				'margin-top'    => $location_margin['top'],
				'margin-right'  => $location_margin['right'],
				'margin-bottom' => $location_margin['bottom'],
				'margin-left'   => $location_margin['left'],
			);
			$location_typo                                = isset( $shortcode_data['client_location_typography'] ) ? $shortcode_data['client_location_typography'] : '';
			$location_typography                          = array_merge( $location_typo, $client_location_margin );
			$shortcode_data['client_location_typography'] = $location_typography;
			unset( $shortcode_data['testimonial_client_location_margin'] );
		}

		/**
		 * Mobile.
		 */
		$phone_margin = isset( $shortcode_data['testimonial_client_phone_margin'] ) ? $shortcode_data['testimonial_client_phone_margin'] : '';
		if ( ! empty( $phone_margin ) ) {
			$client_phone_margin                       = array(
				'margin-top'    => $phone_margin['top'],
				'margin-right'  => $phone_margin['right'],
				'margin-bottom' => $phone_margin['bottom'],
				'margin-left'   => $phone_margin['left'],
			);
			$phone_typo                                = isset( $shortcode_data['client_phone_typography'] ) ? $shortcode_data['client_phone_typography'] : '';
			$phone_typography                          = array_merge( $phone_typo, $client_phone_margin );
			$shortcode_data['client_phone_typography'] = $phone_typography;
			unset( $shortcode_data['testimonial_client_phone_margin'] );
		}

		/**
		 * E-mail.
		 */
		$email_margin = isset( $shortcode_data['testimonial_client_email_margin'] ) ? $shortcode_data['testimonial_client_email_margin'] : '';
		if ( ! empty( $email_margin ) ) {
			$client_email_margin                       = array(
				'margin-top'    => $email_margin['top'],
				'margin-right'  => $email_margin['right'],
				'margin-bottom' => $email_margin['bottom'],
				'margin-left'   => $email_margin['left'],
			);
			$email_typo                                = isset( $shortcode_data['client_email_typography'] ) ? $shortcode_data['client_email_typography'] : '';
			$email_typography                          = array_merge( $email_typo, $client_email_margin );
			$shortcode_data['client_email_typography'] = $email_typography;
			unset( $shortcode_data['testimonial_client_email_margin'] );
		}

		/**
		 * Date.
		 */
		$date_margin = isset( $shortcode_data['testimonial_client_date_margin'] ) ? $shortcode_data['testimonial_client_date_margin'] : '';
		if ( ! empty( $date_margin ) ) {
			$client_date_margin                            = array(
				'margin-top'    => $date_margin['top'],
				'margin-right'  => $date_margin['right'],
				'margin-bottom' => $date_margin['bottom'],
				'margin-left'   => $date_margin['left'],
			);
			$date_typo                                     = isset( $shortcode_data['testimonial_date_typography'] ) ? $shortcode_data['testimonial_date_typography'] : '';
			$date_typography                               = array_merge( $date_typo, $client_date_margin );
			$shortcode_data['testimonial_date_typography'] = $date_typography;
			unset( $shortcode_data['testimonial_client_date_margin'] );
		}

		/**
		 * Website.
		 */
		$website_margin = isset( $shortcode_data['testimonial_client_website_margin'] ) ? $shortcode_data['testimonial_client_website_margin'] : '';
		if ( ! empty( $website_margin ) ) {
			$client_website_margin                       = array(
				'margin-top'    => $website_margin['top'],
				'margin-right'  => $website_margin['right'],
				'margin-bottom' => $website_margin['bottom'],
				'margin-left'   => $website_margin['left'],
			);
			$website_typo                                = isset( $shortcode_data['client_website_typography'] ) ? $shortcode_data['client_website_typography'] : '';
			$website_typography                          = array_merge( $website_typo, $client_website_margin );
			$shortcode_data['client_website_typography'] = $website_typography;
			unset( $shortcode_data['testimonial_client_website_margin'] );
		}

		/**
		 * Read More color.
		 */
		$read_more_color       = isset( $shortcode_data['testimonial_read_more_color'] ) ? $shortcode_data['testimonial_read_more_color'] : '';
		$read_more_hover_color = isset( $shortcode_data['testimonial_read_more_hover_color'] ) ? $shortcode_data['testimonial_read_more_hover_color'] : '';
		$shortcode_data['testimonial_readmore_color'] = array(
			'color'       => $read_more_color,
			'hover-color' => $read_more_hover_color,
		);
		if ( ! empty( $read_more_color && $read_more_hover_color ) ) {
			unset( $shortcode_data['testimonial_read_more_color'] );
			unset( $shortcode_data['testimonial_read_more_hover_color'] );
		}

		/**
		 * Slide to scroll.
		 */
		$number_of_slides_to_scroll       = isset( $shortcode_data['number_of_slides_to_scroll'] ) ? $shortcode_data['number_of_slides_to_scroll'] : '';

		if ( ! empty( $number_of_slides_to_scroll ) ) {
			$shortcode_data['slide_to_scroll'] = array(
				'large_desktop' => $number_of_slides_to_scroll,
				'desktop'       => $number_of_slides_to_scroll,
				'laptop'        => $number_of_slides_to_scroll,
				'tablet'        => $number_of_slides_to_scroll,
				'mobile'        => $number_of_slides_to_scroll,
			);
			unset( $shortcode_data['number_of_slides_to_scroll'] );
		}

		/**
		 * Social profile position.
		 */
		$social_profile_position_two = isset( $shortcode_data['social_profile_position_two'] ) ? $shortcode_data['social_profile_position_two'] : '';
		if ( ! empty( $social_profile_position_two ) ) {
			unset( $shortcode_data['social_profile_position_two'] );
		}

		/**
		 * Social icon colors.
		 */
		$social_profile_icon_color       = isset( $shortcode_data['social_profile_icon_color'] ) ? $shortcode_data['social_profile_icon_color'] : '';
		$social_profile_icon_hover_color = isset( $shortcode_data['social_profile_icon_hover_color'] ) ? $shortcode_data['social_profile_icon_hover_color'] : '';
		$social_profile_icon_bg          = isset( $shortcode_data['social_profile_icon_bg'] ) ? $shortcode_data['social_profile_icon_bg'] : '';
		$social_profile_icon_hover_bg    = isset( $shortcode_data['social_profile_icon_hover_bg'] ) ? $shortcode_data['social_profile_icon_hover_bg'] : '';

		$shortcode_data['social_icon_color'] = array(
			'color'            => $social_profile_icon_color,
			'hover-color'      => $social_profile_icon_hover_color,
			'background'       => $social_profile_icon_bg,
			'hover-background' => $social_profile_icon_hover_bg,
		);
		if ( ! empty( $social_profile_icon_color && $social_profile_icon_hover_color && $social_profile_icon_bg && $social_profile_icon_hover_bg ) ) {
			unset( $shortcode_data['social_profile_icon_color'] );
			unset( $shortcode_data['social_profile_icon_hover_color'] );
			unset( $shortcode_data['social_profile_icon_bg'] );
			unset( $shortcode_data['social_profile_icon_hover_bg'] );
		}
		// Social icon border.
		$social_profile_icon_border_color       = isset( $shortcode_data['social_profile_icon_border_color'] ) ? $shortcode_data['social_profile_icon_border_color'] : '';
		$social_profile_icon_hover_border_color = isset( $shortcode_data['social_profile_icon_hover_border_color'] ) ? $shortcode_data['social_profile_icon_hover_border_color'] : '';

		$shortcode_data['social_icon_border'] = array(
			'all'         => '1',
			'style'       => 'solid',
			'color'       => $social_profile_icon_border_color,
			'hover-color' => $social_profile_icon_hover_border_color,
		);
		if ( ! empty( $social_profile_icon_border_color && $social_profile_icon_hover_border_color ) ) {
			unset( $shortcode_data['social_profile_icon_border_color'] );
			unset( $shortcode_data['social_profile_icon_hover_border_color'] );
		}
		// Social icon order radius.
		$social_icon_style = isset( $shortcode_data['social_icon_style'] ) ? $shortcode_data['social_icon_style'] : '';
		if ( ! empty( $social_icon_style ) ) {
			switch ( $social_icon_style ) {
				case 'circle':
					$icon_radius = '50';
					$icon_unit   = '%';
					break;
				case 'round':
					$icon_radius = '4';
					$icon_unit   = 'px';
					break;
				case 'square':
					$icon_radius = '0';
					$icon_unit   = 'px';
					break;
			}
			$shortcode_data['social_icon_border_radius'] = array(
				'all'  => $icon_radius,
				'unit' => $icon_unit,
			);
			unset( $shortcode_data['social_icon_style'] );
		}

		/**
		 * Grid pagination.
		 */
		$grid_pagination_color       = isset( $shortcode_data['grid_pagination_color'] ) ? $shortcode_data['grid_pagination_color'] : '';
		$grid_pagination_hover_color = isset( $shortcode_data['grid_pagination_hover_color'] ) ? $shortcode_data['grid_pagination_hover_color'] : '';
		$grid_pagination_bg          = isset( $shortcode_data['grid_pagination_bg'] ) ? $shortcode_data['grid_pagination_bg'] : '';
		$grid_pagination_hover_bg    = isset( $shortcode_data['grid_pagination_hover_bg'] ) ? $shortcode_data['grid_pagination_hover_bg'] : '';

		$shortcode_data['grid_pagination_colors'] = array(
			'color'            => $grid_pagination_color,
			'hover-color'      => $grid_pagination_hover_color,
			'background'       => $grid_pagination_bg,
			'hover-background' => $grid_pagination_hover_bg,
		);
		if ( ! empty( $grid_pagination_color && $grid_pagination_hover_color && $grid_pagination_bg && $grid_pagination_hover_bg ) ) {
			unset( $shortcode_data['grid_pagination_color'] );
			unset( $shortcode_data['grid_pagination_hover_color'] );
			unset( $shortcode_data['grid_pagination_bg'] );
			unset( $shortcode_data['grid_pagination_hover_bg'] );
		}

		$grid_pagination_border_color = isset( $shortcode_data['grid_pagination_border_color'] ) ? $shortcode_data['grid_pagination_border_color'] : '';

		$shortcode_data['grid_pagination_border'] = array(
			'all'         => '1',
			'style'       => 'solid',
			'color'       => $grid_pagination_border_color,
			'hover-color' => '#1595CE',
		);
		if ( ! empty( $grid_pagination_border_color ) ) {
			unset( $shortcode_data['grid_pagination_border_color'] );
		}

		/**
		 * Filter.
		 */
		$filter_color           = isset( $shortcode_data['filter_color'] ) ? $shortcode_data['filter_color'] : '';
		$filter_bg_color        = isset( $shortcode_data['filter_bg_color'] ) ? $shortcode_data['filter_bg_color'] : '';
		$filter_active_color    = isset( $shortcode_data['filter_active_color'] ) ? $shortcode_data['filter_active_color'] : '';
		$filter_active_bg_color = isset( $shortcode_data['filter_active_bg_color'] ) ? $shortcode_data['filter_active_bg_color'] : '';

		$shortcode_data['filter_colors'] = array(
			'color'             => $filter_color,
			'active-color'      => $filter_active_color,
			'background'        => $filter_bg_color,
			'active-background' => $filter_active_bg_color,
		);
		if ( ! empty( $filter_color && $filter_bg_color && $filter_active_color && $filter_active_bg_color ) ) {
			unset( $shortcode_data['filter_color'] );
			unset( $shortcode_data['filter_active_color'] );
			unset( $shortcode_data['filter_bg_color'] );
			unset( $shortcode_data['filter_active_bg_color'] );
		}

		/**
		 * Testimonial border.
		 */
		$testimonial_border_size  = isset( $shortcode_data['testimonial_border_size'] ) ? $shortcode_data['testimonial_border_size'] : '';
		$testimonial_border_color = isset( $shortcode_data['testimonial_border_color'] ) ? $shortcode_data['testimonial_border_color'] : '';

		$shortcode_data['testimonial_border'] = array(
			'all'   => $testimonial_border_size,
			'style' => 'solid',
			'color' => $testimonial_border_color,
		);
		if ( ! empty( $testimonial_border_size && $testimonial_border_color ) ) {
			unset( $shortcode_data['testimonial_border_size'] );
			unset( $shortcode_data['testimonial_border_size_two'] );
			unset( $shortcode_data['testimonial_border_color'] );
		}

		/**
		 * Info border.
		 */
		$testimonial_info_border_size  = isset( $shortcode_data['testimonial_info_border_size'] ) ? $shortcode_data['testimonial_info_border_size'] : '';
		$testimonial_info_border_color = isset( $shortcode_data['testimonial_info_border_color'] ) ? $shortcode_data['testimonial_info_border_color'] : '';

		$shortcode_data['testimonial_info_border'] = array(
			'all'   => $testimonial_info_border_size,
			'style' => 'solid',
			'color' => $testimonial_info_border_color,
		);
		if ( ! empty( $testimonial_info_border_size && $testimonial_info_border_color ) ) {
			unset( $shortcode_data['testimonial_info_border_size'] );
			unset( $shortcode_data['testimonial_info_border_color'] );
		}

		/**
		 * Navigation.
		 */
		$navigation_style                 = isset( $shortcode_data['navigation_style'] ) ? $shortcode_data['navigation_style'] : '';
		$navigation_arrow_color           = isset( $shortcode_data['navigation_arrow_color'] ) ? $shortcode_data['navigation_arrow_color'] : '';
		$navigation_hover_arrow_color     = isset( $shortcode_data['navigation_hover_arrow_color'] ) ? $shortcode_data['navigation_hover_arrow_color'] : '';
		$navigation_arrow_color_two       = isset( $shortcode_data['navigation_arrow_color_two'] ) ? $shortcode_data['navigation_arrow_color_two'] : '';
		$navigation_hover_arrow_color_two = isset( $shortcode_data['navigation_hover_arrow_color_two'] ) ? $shortcode_data['navigation_hover_arrow_color_two'] : '';
		$navigation_bg_color              = isset( $shortcode_data['navigation_bg_color'] ) ? $shortcode_data['navigation_bg_color'] : '';
		$navigation_hover_bg_color        = isset( $shortcode_data['navigation_hover_bg_color'] ) ? $shortcode_data['navigation_hover_bg_color'] : '';
		$navigation_border_color          = isset( $shortcode_data['navigation_border_color'] ) ? $shortcode_data['navigation_border_color'] : '';
		$navigation_hover_border_color    = isset( $shortcode_data['navigation_hover_border_color'] ) ? $shortcode_data['navigation_hover_border_color'] : '';

		$shortcode_data['navigation_color']         = array(
			'color'            => $navigation_arrow_color,
			'hover-color'      => $navigation_hover_arrow_color,
			'background'       => $navigation_bg_color,
			'hover-background' => $navigation_hover_bg_color,
		);
		$shortcode_data['navigation_border']        = array(
			'all'         => '0',
			'style'       => 'solid',
			'color'       => $navigation_border_color,
			'hover-color' => $navigation_hover_border_color,
		);
		$shortcode_data['navigation_border_radius'] = array(
			'all'  => '0',
			'unit' => 'px',
		);
		if ( ! empty( $navigation_style ) ) {
			unset( $shortcode_data['navigation_style'] );
		}
		if ( ! empty( $navigation_arrow_color ) ) {
			unset( $shortcode_data['navigation_arrow_color'] );
		}
		if ( ! empty( $navigation_hover_arrow_color ) ) {
			unset( $shortcode_data['navigation_hover_arrow_color'] );
		}
		if ( ! empty( $navigation_arrow_color_two ) ) {
			unset( $shortcode_data['navigation_arrow_color_two'] );
		}
		if ( ! empty( $navigation_hover_arrow_color_two ) ) {
			unset( $shortcode_data['navigation_hover_arrow_color_two'] );
		}
		if ( ! empty( $navigation_bg_color ) ) {
			unset( $shortcode_data['navigation_bg_color'] );
		}
		if ( ! empty( $navigation_hover_bg_color ) ) {
			unset( $shortcode_data['navigation_hover_bg_color'] );
		}
		if ( ! empty( $navigation_border_color ) ) {
			unset( $shortcode_data['navigation_border_color'] );
		}
		if ( ! empty( $navigation_hover_border_color ) ) {
			unset( $shortcode_data['navigation_hover_border_color'] );
		}

		/**
		 * Pagination.
		 */
		$pagination_style             = isset( $shortcode_data['pagination_style'] ) ? $shortcode_data['pagination_style'] : '';
		$pagination_text_color        = isset( $shortcode_data['pagination_text_color'] ) ? $shortcode_data['pagination_text_color'] : '';
		$pagination_active_text_color = isset( $shortcode_data['pagination_active_text_color'] ) ? $shortcode_data['pagination_active_text_color'] : '';
		$pagination_color             = isset( $shortcode_data['pagination_color'] ) ? $shortcode_data['pagination_color'] : '';
		$pagination_active_color      = isset( $shortcode_data['pagination_active_color'] ) ? $shortcode_data['pagination_active_color'] : '';

		$shortcode_data['pagination_colors'] = array(
			'color'        => $pagination_color,
			'active-color' => $pagination_active_color,
		);
		if ( ! empty( $pagination_style ) ) {
			unset( $shortcode_data['pagination_style'] );
		}
		if ( ! empty( $pagination_text_color ) ) {
			unset( $shortcode_data['pagination_text_color'] );
		}
		if ( ! empty( $pagination_active_text_color ) ) {
			unset( $shortcode_data['pagination_active_text_color'] );
		}
		if ( ! empty( $pagination_color ) ) {
			unset( $shortcode_data['pagination_color'] );
		}
		if ( ! empty( $pagination_active_color ) ) {
			unset( $shortcode_data['pagination_active_color'] );
		}

		$shortcode_data['testimonial_title_tag'] = 'h3';
		$shortcode_data['popup_background']      = '#ffffff';

		if ( ! empty( $layout ) && 'filter_grid' == $layout ) {
			$shortcode_data['filter_style'] = 'even';
			$shortcode_data['layout']       = 'filter';
		} elseif ( ! empty( $layout ) && 'filter_masonry' == $layout ) {
			$shortcode_data['filter_style'] = 'masonry';
			$shortcode_data['layout']       = 'filter';
		}
		$shortcode_data['columns']           = array(
			'large_desktop' => $column_large_desktop,
			'desktop'       => $column_desktop,
			'laptop'        => $column_laptop,
			'tablet'        => $column_tablet,
			'mobile'        => $column_mobile,
		);
		$shortcode_data['slider_direction']  = $slider_direction;
		$shortcode_data['image_sizes']       = 'custom';
		$shortcode_data['image_custom_size'] = array(
			'width'  => $client_image_width,
			'height' => $client_image_height,
			'crop'   => 'hard-crop',
		);

		if ( ! empty( $image_border_size ) && ! empty( $image_border_color ) ) {
			$shortcode_data['image_border'] = array(
				'all'   => $image_border_size,
				'style' => 'solid',
				'color' => $image_border_color,
			);
			unset( $shortcode_data['client_image_border_size'] );
			unset( $shortcode_data['client_image_border_color'] );
		}
		if ( ! empty( $image_padding ) ) {
			$shortcode_data['image_padding'] = array(
				'all' => $image_padding,
			);
			unset( $shortcode_data['client_image_padding'] );
		}
		if ( ! empty( $slider_mode ) ) {
			$shortcode_data['slider_mode'] = true == $slider_mode ? 'ticker' : 'standard';
			unset( $shortcode_data['slider_ticker_mode'] );
		}
		if ( ! empty( $column_large_desktop ) ) {
			unset( $shortcode_data['number_of_testimonials'] );
		}
		if ( ! empty( $column_desktop ) ) {
			unset( $shortcode_data['number_of_testimonials_desktop'] );
		}
		if ( ! empty( $column_laptop ) ) {
			unset( $shortcode_data['number_of_testimonials_small_desktop'] );
		}
		if ( ! empty( $column_tablet ) ) {
			unset( $shortcode_data['number_of_testimonials_tablet'] );
		}
		if ( ! empty( $column_mobile ) ) {
			unset( $shortcode_data['number_of_testimonials_mobile'] );
		}
		if ( ! empty( $rtl_mode ) ) {
			unset( $shortcode_data['rtl_mode'] );
		}
		if ( ! empty( $client_image_width ) ) {
			unset( $shortcode_data['client_image_width'] );
		}
		if ( ! empty( $client_image_height ) ) {
			unset( $shortcode_data['client_image_height'] );
		}
		if ( ! empty( $client_image_crop ) ) {
			unset( $shortcode_data['client_image_crop'] );
		}

		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $shortcode_data );
	}
}

/**
 * Testimonial query for id.
 */
$args            = new WP_Query(
	array(
		'post_type'      => 'spt_testimonial',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$testimonial_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $testimonial_ids ) > 0 ) {
	foreach ( $testimonial_ids as $testimonial_key => $testimonial_id ) {
		$testimonial_data             = get_post_meta( $testimonial_id, 'sp_tpro_meta_options', true );
		$tpro_social_facebook_url     = isset( $testimonial_data['tpro_social_facebook_url'] ) ? $testimonial_data['tpro_social_facebook_url'] : '';
		$tpro_social_twitter_url      = isset( $testimonial_data['tpro_social_twitter_url'] ) ? $testimonial_data['tpro_social_twitter_url'] : '';
		$tpro_social_linked_in_url    = isset( $testimonial_data['tpro_social_linked_in_url'] ) ? $testimonial_data['tpro_social_linked_in_url'] : '';
		$tpro_social_instagram_url    = isset( $testimonial_data['tpro_social_instagram_url'] ) ? $testimonial_data['tpro_social_instagram_url'] : '';
		$tpro_social_youtube_url      = isset( $testimonial_data['tpro_social_youtube_url'] ) ? $testimonial_data['tpro_social_youtube_url'] : '';
		$tpro_social_pinterest_url    = isset( $testimonial_data['tpro_social_pinterest_url'] ) ? $testimonial_data['tpro_social_pinterest_url'] : '';
		$tpro_social_skype_url        = isset( $testimonial_data['tpro_social_skype_url'] ) ? $testimonial_data['tpro_social_skype_url'] : '';
		$tpro_social_stumble_upon_url = isset( $testimonial_data['tpro_social_stumble_upon_url'] ) ? $testimonial_data['tpro_social_stumble_upon_url'] : '';
		$tpro_social_reddit_url       = isset( $testimonial_data['tpro_social_reddit_url'] ) ? $testimonial_data['tpro_social_reddit_url'] : '';
		$tpro_social_dribbble_url     = isset( $testimonial_data['tpro_social_dribbble_url'] ) ? $testimonial_data['tpro_social_dribbble_url'] : '';
		$tpro_social_snapchat_url     = isset( $testimonial_data['tpro_social_snapchat_url'] ) ? $testimonial_data['tpro_social_snapchat_url'] : '';

		$testimonial_data['tpro_social_profiles'] = array(
			array(
				'social_name' => 'facebook',
				'social_url'  => $tpro_social_facebook_url,
			),
			array(
				'social_name' => 'twitter',
				'social_url'  => $tpro_social_twitter_url,
			),
			array(
				'social_name' => 'linkedin',
				'social_url'  => $tpro_social_linked_in_url,
			),
			array(
				'social_name' => 'instagram',
				'social_url'  => $tpro_social_instagram_url,
			),
			array(
				'social_name' => 'youtube',
				'social_url'  => $tpro_social_youtube_url,
			),
			array(
				'social_name' => 'pinterest',
				'social_url'  => $tpro_social_pinterest_url,
			),
			array(
				'social_name' => 'skype',
				'social_url'  => $tpro_social_skype_url,
			),
			array(
				'social_name' => 'stumbleupon',
				'social_url'  => $tpro_social_stumble_upon_url,
			),
			array(
				'social_name' => 'reddit',
				'social_url'  => $tpro_social_reddit_url,
			),
			array(
				'social_name' => 'dribbble',
				'social_url'  => $tpro_social_dribbble_url,
			),
			array(
				'social_name' => 'snapchat',
				'social_url'  => $tpro_social_snapchat_url,
			),
		);

		if ( ! empty( $tpro_social_facebook_url ) ) {
			unset( $testimonial_data['tpro_social_facebook_url'] );
		}
		if ( ! empty( $tpro_social_twitter_url ) ) {
			unset( $testimonial_data['tpro_social_twitter_url'] );
		}
		if ( ! empty( $tpro_social_linked_in_url ) ) {
			unset( $testimonial_data['tpro_social_linked_in_url'] );
		}
		if ( ! empty( $tpro_social_instagram_url ) ) {
			unset( $testimonial_data['tpro_social_instagram_url'] );
		}
		if ( ! empty( $tpro_social_youtube_url ) ) {
			unset( $testimonial_data['tpro_social_youtube_url'] );
		}
		if ( ! empty( $tpro_social_pinterest_url ) ) {
			unset( $testimonial_data['tpro_social_pinterest_url'] );
		}
		if ( ! empty( $tpro_social_skype_url ) ) {
			unset( $testimonial_data['tpro_social_skype_url'] );
		}
		if ( ! empty( $tpro_social_stumble_upon_url ) ) {
			unset( $testimonial_data['tpro_social_stumble_upon_url'] );
		}
		if ( ! empty( $tpro_social_reddit_url ) ) {
			unset( $testimonial_data['tpro_social_reddit_url'] );
		}
		if ( ! empty( $tpro_social_dribbble_url ) ) {
			unset( $testimonial_data['tpro_social_dribbble_url'] );
		}
		if ( ! empty( $tpro_social_snapchat_url ) ) {
			unset( $testimonial_data['tpro_social_snapchat_url'] );
		}

		update_post_meta( $testimonial_id, 'sp_tpro_meta_options', $testimonial_data );
	}
}
