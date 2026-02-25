<?php
$testimonial_read_more_text     = isset( $shortcode_data['testimonial_read_more_text'] ) ? $shortcode_data['testimonial_read_more_text'] : 'Read More';
$testimonial_read_less_text     = isset( $shortcode_data['testimonial_read_less_text'] ) ? $shortcode_data['testimonial_read_less_text'] : 'Read Less';
$testimonial_read_more_ellipsis = isset( $shortcode_data['testimonial_read_more_ellipsis'] ) ? $shortcode_data['testimonial_read_more_ellipsis'] : '...';

$columns                         = isset( $shortcode_data['columns'] ) ? $shortcode_data['columns'] : '';
$columns_large_desktop           = isset( $columns['large_desktop'] ) ? $columns['large_desktop'] : '1';
$columns_desktop                 = isset( $columns['desktop'] ) ? $columns['desktop'] : '1';
$columns_laptop                  = isset( $columns['laptop'] ) ? $columns['laptop'] : '1';
$columns_tablet                  = isset( $columns['tablet'] ) ? $columns['tablet'] : '1';
$columns_mobile                  = isset( $columns['mobile'] ) ? $columns['mobile'] : '1';
$slider_animation                = isset( $shortcode_data['slider_animation'] ) ? $shortcode_data['slider_animation'] : '';
$testimonial_content_length_type = isset( $shortcode_data['testimonial_content_length_type'] ) ? $shortcode_data['testimonial_content_length_type'] : 'characters';
$testimonial_word_limit          = isset( $shortcode_data['testimonial_word_limit'] ) ? $shortcode_data['testimonial_word_limit'] : '';
$testimonial_strip_tags          = isset( $shortcode_data['testimonial_strip_tags'] ) ? $shortcode_data['testimonial_strip_tags'] : 'strip_all';

$show_client_image   = isset( $shortcode_data['client_image'] ) ? $shortcode_data['client_image'] : false;
$image_sizes         = isset( $shortcode_data['image_sizes'] ) ? $shortcode_data['image_sizes'] : 'custom';
$image_custom_size   = isset( $shortcode_data['image_custom_size'] ) ? $shortcode_data['image_custom_size'] : '';
$client_image_width  = isset( $image_custom_size['width'] ) ? $image_custom_size['width'] : '120';
$client_image_height = isset( $image_custom_size['height'] ) ? $image_custom_size['height'] : '120';
$image_crop          = isset( $image_custom_size['crop'] ) ? $image_custom_size['crop'] : 'soft-crop';
$client_image_crop   = ( 'hard-crop' === $image_crop ) ? true : false;
$image_grayscale     = isset( $shortcode_data['image_grayscale'] ) ? $shortcode_data['image_grayscale'] : 'none';
$show_2x_image       = isset( $shortcode_data['load_2x_image'] ) ? $shortcode_data['load_2x_image'] : '';

$video_icon   = isset( $shortcode_data['video_icon'] ) ? $shortcode_data['video_icon'] : true;
$img_lightbox = isset( $shortcode_data['img_lightbox'] ) ? $shortcode_data['img_lightbox'] : false;

$star_icon                         = isset( $shortcode_data['tpro_star_icon'] ) ? $shortcode_data['tpro_star_icon'] : 'fa fa-star';
$testimonial_characters_limit      = isset( $shortcode_data['testimonial_characters_limit'] ) ? $shortcode_data['testimonial_characters_limit'] : '100';
$testimonial_content_type          = isset( $shortcode_data['testimonial_content_type'] ) ? $shortcode_data['testimonial_content_type'] : '';
$testimonial_read_more_link_action = isset( $shortcode_data['testimonial_read_more_link_action'] ) ? $shortcode_data['testimonial_read_more_link_action'] : '';
$testimonial_read_more             = isset( $shortcode_data['testimonial_read_more'] ) ? $shortcode_data['testimonial_read_more'] : '';

$show_testimonial_title = isset( $shortcode_data['testimonial_title'] ) ? $shortcode_data['testimonial_title'] : '';
$testimonial_title_tag  = isset( $shortcode_data['testimonial_title_tag'] ) ? $shortcode_data['testimonial_title_tag'] : 'h3';
$show_testimonial_text  = isset( $shortcode_data['testimonial_text'] ) ? $shortcode_data['testimonial_text'] : '';

$website_link_target = isset( $shortcode_data['website_link_target'] ) ? $shortcode_data['website_link_target'] : '_blank';

// Section title limit.
$title_limit = isset( $shortcode_data['testimonial_title_limit'] ) ? $shortcode_data['testimonial_title_limit'] : '';

$identity_linking_website  = isset( $shortcode_data['identity_linking_website'] ) ? $shortcode_data['identity_linking_website'] : '';
$show_social_profile       = isset( $shortcode_data['social_profile'] ) ? $shortcode_data['social_profile'] : '';
$show_client_designation   = isset( $shortcode_data['client_designation'] ) ? $shortcode_data['client_designation'] : '';
$show_client_company_name  = isset( $shortcode_data['client_company_name'] ) ? $shortcode_data['client_company_name'] : '';
$show_client_company_logo  = isset( $shortcode_data['client_company_logo'] ) ? $shortcode_data['client_company_logo'] : false;
$company_image_sizes       = isset( $shortcode_data['company_image_sizes'] ) ? $shortcode_data['company_image_sizes'] : '';
$company_image_custom_size = isset( $shortcode_data['company_image_custom_size'] ) ? $shortcode_data['company_image_custom_size'] : '';
$company_image_width       = isset( $company_image_custom_size['width'] ) ? $company_image_custom_size['width'] : '120';
$company_image_height      = isset( $company_image_custom_size['height'] ) ? $company_image_custom_size['height'] : '120';
$company_image_crop        = ( isset( $company_image_custom_size['crop'] ) && 'hard-crop' === $company_image_custom_size['crop'] ) ? true : false;

$testimonial_client_name                 = isset( $shortcode_data['testimonial_client_name'] ) ? $shortcode_data['testimonial_client_name'] : '';
$testimonial_client_name_tag             = isset( $shortcode_data['testimonial_client_name_tag'] ) ? $shortcode_data['testimonial_client_name_tag'] : 'h4';
$show_testimonial_client_rating          = isset( $shortcode_data['testimonial_client_rating'] ) ? $shortcode_data['testimonial_client_rating'] : '';
$testimonial_client_rating_alignment     = isset( $shortcode_data['testimonial_client_rating_alignment'] ) ? $shortcode_data['testimonial_client_rating_alignment'] : 'center';
$testimonial_client_rating_alignment_two = isset( $shortcode_data['testimonial_client_rating_alignment_two'] ) ? $shortcode_data['testimonial_client_rating_alignment_two'] : $testimonial_client_rating_alignment;
$theme_style                             = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme-one';

// Schema settings.
$tpro_schema_markup    = isset( $shortcode_data['tpro_schema_markup'] ) ? $shortcode_data['tpro_schema_markup'] : '';
$tpro_global_item_name = isset( $shortcode_data['tpro_global_item_name'] ) && ! empty( $shortcode_data['tpro_global_item_name'] ) ? $shortcode_data['tpro_global_item_name'] : get_the_title( $post_id );

$show_testimonial_client_location = isset( $shortcode_data['testimonial_client_location'] ) ? $shortcode_data['testimonial_client_location'] : '';
$show_testimonial_client_phone    = isset( $shortcode_data['testimonial_client_phone'] ) ? $shortcode_data['testimonial_client_phone'] : '';
$show_testimonial_client_email    = isset( $shortcode_data['testimonial_client_email'] ) ? $shortcode_data['testimonial_client_email'] : '';
$testimonial_client_date_format   = isset( $shortcode_data['testimonial_client_date_format'] ) ? $shortcode_data['testimonial_client_date_format'] : '';
$show_testimonial_client_date     = isset( $shortcode_data['testimonial_client_date'] ) ? $shortcode_data['testimonial_client_date'] : '';
$show_testimonial_client_website  = isset( $shortcode_data['testimonial_client_website'] ) ? $shortcode_data['testimonial_client_website'] : '';
$client_image_style               = isset( $shortcode_data['client_image_style'] ) ? $shortcode_data['client_image_style'] : '';
$thumbnail_slider                 = isset( $shortcode_data['thumbnail_slider'] ) ? $shortcode_data['thumbnail_slider'] : '';
$tpro_schema_markup               = isset( $shortcode_data['tpro_schema_markup'] ) ? $shortcode_data['tpro_schema_markup'] : '';
$client_image_position            = isset( $shortcode_data['client_image_position'] ) ? $shortcode_data['client_image_position'] : 'center';
$client_image_position_two        = isset( $shortcode_data['client_image_position_two'] ) ? $shortcode_data['client_image_position_two'] : 'left';
$testimonial_info_position_two    = isset( $shortcode_data['testimonial_info_position_two'] ) ? $shortcode_data['testimonial_info_position_two'] : '';
$client_image_position_three      = isset( $shortcode_data['client_image_position_three'] ) ? $shortcode_data['client_image_position_three'] : 'left-top';
$show_client_addition_info        = isset( $shortcode_data['testimonial_client_addition_info'] ) ? $shortcode_data['testimonial_client_addition_info'] : false;

$schema_html                     = '';
$thumbnail_slider_image_markup   = '';
$thumbnail_slider_content_markup = '';
