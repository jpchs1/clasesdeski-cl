<?php
use ShapedPlugin\TestimonialPro\Frontend\Helper;
$layout                           = isset( $shortcode_data['layout'] ) ? $shortcode_data['layout'] : 'slider';
$theme_style                      = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme-one';
$section_title                    = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : '';
$slider_mode                      = isset( $shortcode_data['slider_mode'] ) ? $shortcode_data['slider_mode'] : 'standard';
$testimonial_read_more            = isset( $shortcode_data['testimonial_read_more'] ) ? $shortcode_data['testimonial_read_more'] : '';
$show_testimonial_client_location = isset( $shortcode_data['testimonial_client_location'] ) ? $shortcode_data['testimonial_client_location'] : '';
$show_client_image                = isset( $shortcode_data['client_image'] ) ? $shortcode_data['client_image'] : false;
$grid_pagination                  = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
$show_testimonial_client_phone    = isset( $shortcode_data['testimonial_client_phone'] ) ? $shortcode_data['testimonial_client_phone'] : '';
$show_testimonial_client_email    = isset( $shortcode_data['testimonial_client_email'] ) ? $shortcode_data['testimonial_client_email'] : '';
$testimonial_client_date_format   = isset( $shortcode_data['testimonial_client_date_format'] ) ? $shortcode_data['testimonial_client_date_format'] : '';
$show_testimonial_client_date     = isset( $shortcode_data['testimonial_client_date'] ) ? $shortcode_data['testimonial_client_date'] : '';
$show_testimonial_client_website  = isset( $shortcode_data['testimonial_client_website'] ) ? $shortcode_data['testimonial_client_website'] : '';
$show_testimonial_title           = isset( $shortcode_data['testimonial_title'] ) ? $shortcode_data['testimonial_title'] : '';
$show_testimonial_text            = isset( $shortcode_data['testimonial_text'] ) ? $shortcode_data['testimonial_text'] : '';
$testimonial_client_name          = isset( $shortcode_data['testimonial_client_name'] ) ? $shortcode_data['testimonial_client_name'] : '';
$show_testimonial_client_rating   = isset( $shortcode_data['testimonial_client_rating'] ) ? $shortcode_data['testimonial_client_rating'] : '';
$client_image_bg                  = isset( $shortcode_data['client_image_bg'] ) ? $shortcode_data['client_image_bg'] : '#ffffff';
$video_icon_color                 = isset( $shortcode_data['video_icon_color'] ) && is_array( $shortcode_data['video_icon_color'] ) ? $shortcode_data['video_icon_color'] : array(
	'color'       => isset( $shortcode_data['video_icon_color'] ) ? $shortcode_data['video_icon_color'] : '#e2e2e2',
	'hover-color' => '#ffffff',
);
$video_icon_size                  = isset( $shortcode_data['video_icon_size'] ) ? $shortcode_data['video_icon_size'] : 32;
$video_icon_overlay               = isset( $shortcode_data['video_icon_overlay'] ) ? $shortcode_data['video_icon_overlay'] : 'rgba(51, 51, 51, 0.4)';
$client_image_border_shadow       = isset( $shortcode_data['client_image_border_shadow'] ) ? $shortcode_data['client_image_border_shadow'] : 'border';
$testimonial_margin_value         = isset( $shortcode_data['testimonial_margin']['all'] ) && $shortcode_data['testimonial_margin']['all'] ? $shortcode_data['testimonial_margin']['all'] : '0';
$testimonial_margin_unit          = isset( $shortcode_data['testimonial_margin']['unit'] ) ? $shortcode_data['testimonial_margin']['unit'] : 'px';
$testimonial_margin               = $testimonial_margin_value > 0 ? $testimonial_margin_value . $testimonial_margin_unit : '0px';
$horizontal_space                 = isset( $shortcode_data['testimonial_margin']['top'] ) && $shortcode_data['testimonial_margin']['top'] ? $shortcode_data['testimonial_margin']['top'] . $testimonial_margin_unit : $testimonial_margin;
$vertical_space                   = isset( $shortcode_data['testimonial_margin']['right'] ) && $shortcode_data['testimonial_margin']['right'] ? $shortcode_data['testimonial_margin']['right'] . $testimonial_margin_unit : $testimonial_margin;

$testimonial_inner_padding      = isset( $shortcode_data['testimonial_inner_padding'] ) ? $shortcode_data['testimonial_inner_padding'] : array(
	'top'    => '22',
	'right'  => '22',
	'bottom' => '22',
	'left'   => '22',
	'unit'   => 'px',
);
$testimonial_info_inner_padding = isset( $shortcode_data['testimonial_info_inner_padding'] ) ? $shortcode_data['testimonial_info_inner_padding'] : array(
	'top'    => '22',
	'right'  => '22',
	'bottom' => '22',
	'left'   => '22',
	'unit'   => 'px',
);
$show_client_designation        = isset( $shortcode_data['client_designation'] ) ? $shortcode_data['client_designation'] : '';
$show_client_company_name       = isset( $shortcode_data['client_company_name'] ) ? $shortcode_data['client_company_name'] : '';
$show_client_company_logo       = isset( $shortcode_data['client_company_logo'] ) ? $shortcode_data['client_company_logo'] : false;
$client_image_position          = isset( $shortcode_data['client_image_position'] ) ? $shortcode_data['client_image_position'] : 'center';
$client_image_position_two      = isset( $shortcode_data['client_image_position_two'] ) ? $shortcode_data['client_image_position_two'] : 'left';
$testimonial_info_position_two  = isset( $shortcode_data['testimonial_info_position_two'] ) ? $shortcode_data['testimonial_info_position_two'] : '';
$client_image_position_three    = isset( $shortcode_data['client_image_position_three'] ) ? $shortcode_data['client_image_position_three'] : 'left-top';
$image_custom_size              = isset( $shortcode_data['image_custom_size'] ) ? $shortcode_data['image_custom_size'] : '';
$client_image_width             = isset( $image_custom_size['width'] ) ? (int) $image_custom_size['width'] : (int) '120';
$client_image_height            = isset( $image_custom_size['height'] ) ? (int) $image_custom_size['height'] : (int) '120';
$average_rating_top             = isset( $shortcode_data['average_rating_top'] ) ? $shortcode_data['average_rating_top'] : false;
$average_rating_margin          = isset( $shortcode_data['average_rating_margin'] ) ? $shortcode_data['average_rating_margin'] : array(
	'top'    => '0',
	'right'  => '0',
	'bottom' => '25',
	'left'   => '0',
	'unit'   => 'px',
);
$testimonial_live_filter        = isset( $shortcode_data['ajax_live_filter'] ) ? $shortcode_data['ajax_live_filter'] : false;
/**
 * Image Zoom
 */
$image_zoom = isset( $shortcode_data['image_zoom_effect'] ) ? $shortcode_data['image_zoom_effect'] : '';

if ( $section_title ) {
	$section_title_font_load  = isset( $shortcode_data['section_title_font_load'] ) ? $shortcode_data['section_title_font_load'] : '';
	$section_title_typography = isset( $shortcode_data['section_title_typography'] ) ? $shortcode_data['section_title_typography'] : '';
	if ( $section_title_font_load ) {
		$tpro_typography[] = $section_title_typography;
	}
	$outline         .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section-title{
			margin: 0;
			padding: 0;
			margin-bottom: ' . $section_title_typography['margin-bottom'] . 'px;';
			$outline .= Helper::tpro_typography_output( $section_title_typography, $section_title_font_load );
			$outline .= '}';
}
if ( $average_rating_top ) {
	$outline .= '
	#sp-testimonial-pro-wrapper-' . $post_id . ' .average-rating-section {
		margin: ' . $average_rating_margin['top'] . 'px ' . $average_rating_margin['right'] . 'px ' . $average_rating_margin['bottom'] . 'px ' . $average_rating_margin['left'] . 'px;
	}';
}

if ( 'slider' === $layout && 'ticker' !== $slider_mode ) {
	$outline         .= '
	#sp-testimonial-pro-wrapper-' . $post_id . '.sp-testimonial-pro-wrapper .sp-testimonial-pro-section.sp-tpCarousel {
		padding:0 0 60px 0;
	}
	#sp-testimonial-pro-wrapper-' . $post_id . '.sp-testimonial-pro-wrapper .sp-testimonial-pro-section.sp-testimonial-pro-section-thumb{
		padding:0 0;
	}
	#sp-testimonial-pro-wrapper-' . $post_id . '.sp-testimonial-pro-wrapper .sp-testimonial-pro-section.sp-testimonial-pro-section-content {
		padding:0 0 60px 0;
	}
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section {
			display:none;
	}
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section.swiper-initialized,
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section.swiper-container-initialized {
		display: block;
	}
	';
	$thumbnail_slider = isset( $shortcode_data['thumbnail_slider'] ) ? $shortcode_data['thumbnail_slider'] : false;
	if ( $thumbnail_slider ) {
		$testimonial_border_for_thumbnail        = isset( $shortcode_data['testimonial_border_for_thumbnail'] ) ? $shortcode_data['testimonial_border_for_thumbnail'] : array(
			'all'    => '0',
			'style'  => 'solid',
			'color'  => '#e3e3e3',
			'radius' => '0',
		);
		$testimonial_bg_for_thumbnail            = isset( $shortcode_data['testimonial_bg_for_thumbnail'] ) ? $shortcode_data['testimonial_bg_for_thumbnail'] : 'transparent';
		$border_radius_for_thumbnail             = isset( $shortcode_data['testimonial_border_radius_for_thumbnail'] ) ? $shortcode_data['testimonial_border_radius_for_thumbnail'] : '0';
		$testimonial_border_radius_for_thumbnail = isset( $testimonial_border_for_thumbnail['radius'] ) ? $testimonial_border_for_thumbnail['radius'] : $border_radius_for_thumbnail;
		$testimonial_inner_padding_for_thumbnail = isset( $shortcode_data['testimonial_inner_padding_for_thumbnail'] ) ? $shortcode_data['testimonial_inner_padding_for_thumbnail'] : array(
			'top'    => '0',
			'right'  => '0',
			'bottom' => '0',
			'left'   => '0',
			'unit'   => 'px',
		);
		$outline                                .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section.tpro-style-theme-one .sp-testimonial-pro-item .sp-testimonial-pro{
			border: ' . $testimonial_border_for_thumbnail['all'] . 'px ' . $testimonial_border_for_thumbnail['style'] . ' ' . $testimonial_border_for_thumbnail['color'] . ';
			border-radius: ' . $testimonial_border_radius_for_thumbnail . 'px;
			padding: ' . $testimonial_inner_padding_for_thumbnail['top'] . 'px ' . $testimonial_inner_padding_for_thumbnail['right'] . 'px ' . $testimonial_inner_padding_for_thumbnail['bottom'] . 'px ' . $testimonial_inner_padding_for_thumbnail['left'] . 'px;
			background-color: ' . $testimonial_bg_for_thumbnail . ';
		}';
	}
} elseif ( 'grid' === $layout || 'masonry' === $layout || 'list' === $layout || 'filter' === $layout ) {
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro-item{
		margin-bottom: ' . $vertical_space . ';
	}';
}
$testimonial_info_border_color = isset( $shortcode_data['testimonial_info_border']['color'] ) ? $shortcode_data['testimonial_info_border']['color'] : '#e3e3e3';
if ( $testimonial_live_filter || 'filter' === $layout ) {
	$filter_font_load  = isset( $shortcode_data['filter_font_load'] ) ? $shortcode_data['filter_font_load'] : '';
	$filter_typography = isset( $shortcode_data['filter_typography'] ) ? $shortcode_data['filter_typography'] : '';
	$filter_colors     = isset( $shortcode_data['filter_colors'] ) ? $shortcode_data['filter_colors'] : '';
	$filter_border     = isset( $shortcode_data['filter_border'] ) ? $shortcode_data['filter_border'] : '';
	$filter_margin     = isset( $shortcode_data['filter_margin'] ) ? $shortcode_data['filter_margin'] : array(
		'top'    => '0',
		'right'  => '0',
		'bottom' => '24',
		'left'   => '0',
	);
	if ( $filter_font_load ) {
		$tpro_typography[] = $filter_typography;
	}
	if ( isset( $shortcode_data['filter_border'] ) ) {
		$filter_border        = $filter_border['all'] . 'px ' . $filter_border['style'] . ' ' . $filter_border['color'];
		$filter_active_border = $shortcode_data['filter_border']['hover-color'];
	} else {
		$filter_border        = '2px solid #bbbbbb';
		$filter_active_border = '#1595CE';
	}
	$outline     .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section.sp_testimonial_pro_filter .sp-tpro-filter,#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-live-filter{
		text-align: ' . $shortcode_data['filter_alignment'] . ';
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section.sp_testimonial_pro_filter ul.sp-tpro-items-filter,#sp-testimonial-pro-wrapper-' . $post_id . ' .filters-button-group.testimonial-ajax-live-filter{
		margin: ' . $filter_margin['top'] . 'px ' . $filter_margin['right'] . 'px ' . $filter_margin['bottom'] . 'px ' . $filter_margin['left'] . 'px;
		white-space: normal;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section.sp_testimonial_pro_filter ul.sp-tpro-items-filter li a,
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-live-filter .button-group.filters-button-group button{
		color: ' . $filter_colors['color'] . ';
		border: ' . $filter_border . ';
		background: ' . $filter_colors['background'] . ';';
		$outline .= Helper::tpro_typography_output( $filter_typography, $filter_font_load );
		$outline .= '}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section.sp_testimonial_pro_filter ul.sp-tpro-items-filter li a.active,
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-live-filter .button-group.filters-button-group button.is-checked{
		color: ' . $filter_colors['active-color'] . ';
		background: ' . $filter_colors['active-background'] . ';
		border-color: ' . $filter_active_border . ';
	}
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-live-filter .filterSelect{
		color: ' . $filter_colors['active-color'] . ';
		background-color: ' . $filter_colors['active-background'] . ';
		border: ' . $filter_border . ';
		border-color: ' . $filter_active_border . ';
	}
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-live-filter .filterSelect option {
		color: ' . $filter_colors['color'] . ';
		border: ' . $filter_border . ';
		background-color: ' . $filter_colors['background'] . ';
	}
	';
}

if ( $testimonial_read_more ) {
	$popup_background      = isset( $shortcode_data['popup_background'] ) ? $shortcode_data['popup_background'] : '#ffffff';
	$read_more_colors      = isset( $shortcode_data['testimonial_readmore_color'] ) ? $shortcode_data['testimonial_readmore_color'] : '';
	$read_more_color       = isset( $read_more_colors['color'] ) ? $read_more_colors['color'] : '';
	$read_more_hover_color = isset( $read_more_colors['hover-color'] ) ? $read_more_colors['hover-color'] : '';
	$outline              .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section a.tpro-read-more{
		color: ' . $read_more_color . ';
		text-decoration: none;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section a.tpro-read-more:hover{
		color: ' . $read_more_hover_color . ';
	}
	.sp-tpro-modal-testimonial-' . $post_id . '.remodal.sp-tpro-modal-testimonial{background: ' . $popup_background . '}';
}

if ( 'list' !== $layout ) {
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro-item:not(.swiper-slide){
	padding-right:' . $horizontal_space . '; } #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-tpro-items{ margin-right:-' . $horizontal_space . ';
	width: calc( 100% + ' . $horizontal_space . ' );}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .swiper-slide.sp-testimonial-pro-item{ padding-bottom:' . $vertical_space . '; }
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section:not(.sp-testimonial-fade-carousel)  .swiper-slide:not(.sp-testimonial-pro-item) { margin-bottom: -' . $vertical_space . '; }#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .swiper-slide .sp-testimonial-pro-item:last-child{
		padding-right: 0; }';
}
$image_paddings     = isset( $shortcode_data['image_padding'] ) ? $shortcode_data['image_padding'] : '';
$image_padding      = isset( $image_paddings['all'] ) ? (int) $image_paddings['all'] : '0';
$image_border       = isset( $shortcode_data['image_border'] ) ? $shortcode_data['image_border'] : '';
$image_border_width = isset( $image_border['all'] ) ? (int) $image_border['all'] : 0;
$image_border_style = isset( $image_border['style'] ) ? $image_border['style'] : 'solid';
$image_border_color = isset( $image_border['color'] ) ? $image_border['color'] : '#dddddd';

$client_image_margin     = isset( $shortcode_data['client_image_margin'] ) ? $shortcode_data['client_image_margin'] : array(
	'top'    => '0',
	'right'  => '0',
	'bottom' => '22',
	'left'   => '0',
);
$client_image_margin_tow = isset( $shortcode_data['client_image_margin_tow'] ) ? $shortcode_data['client_image_margin_tow'] : array(
	'top'    => '0',
	'right'  => '22',
	'bottom' => '0',
	'left'   => '0',
);
if ( $show_client_image ) {

	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image .sp-tpro-video i.fa, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image .sp-tpro-img i.fa{
		color: ' . $video_icon_color['color'] . ';font-size: ' . $video_icon_size . 'px; width: ' . $video_icon_size . 'px; height: ' . $video_icon_size . 'px;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image .sp-tpro-video:hover i.fa, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image .sp-tpro-img:hover i.fa{
		color: ' . $video_icon_color['hover-color'] . ';
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image .sp-tpro-video:before,#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image .sp-tpro-img:before{
		background: ' . $video_icon_overlay . ';
	}';
	if ( ! $image_zoom && ( 'theme-one' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
			margin: ' . $client_image_margin['top'] . 'px ' . $client_image_margin['right'] . 'px ' . $client_image_margin['bottom'] . 'px ' . $client_image_margin['left'] . 'px;
			text-align: ' . $client_image_position . ';
		}';
	}
	if ( 'theme-ten' === $theme_style ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
			z-index: 2;
            position: relative;
		}';
	}
	if ( 'theme-nine' === $theme_style ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
			margin: ' . $client_image_margin_tow['top'] . 'px ' . $client_image_margin_tow['right'] . 'px ' . $client_image_margin_tow['bottom'] . 'px ' . $client_image_margin_tow['left'] . 'px;
		}';
	}
	if ( 'theme-four' === $theme_style || 'theme-five' === $theme_style ) {
		if ( 'left' === $client_image_position_two ) {
			$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
				float: left;
				margin-right: 25px;
				margin-bottom: 15px;
			}';
		} elseif ( 'right' === $client_image_position_two ) {
			$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
				float: right;
				margin-left: 25px;
				margin-bottom: 15px;
			}';
		} elseif ( 'top' === $client_image_position_two ) {
			$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
				text-align: center;
				margin-bottom: 22px;
			}
			#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image img{
				display: inline-block;
			}';
		} elseif ( 'bottom' === $client_image_position_two ) {
			$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
				text-align: center;
                margin-top: 22px;
			}
			#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image img{
				display: inline-block;
			}';
		}
	}
	if ( $show_client_image ) {
		if ( 'border' === $client_image_border_shadow ) {
			$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image img,
		.sp-tpro-modal-testimonial-' . $post_id . ' .tpro-client-image img{
			background: ' . $client_image_bg . ';
			border: ' . $image_border_width . 'px ' . $image_border_style . ' ' . $image_border_color . ';
			padding: ' . $image_padding . 'px;

		}';

		} else {
			$client_image_box_shadow_property = isset( $shortcode_data['client_image_box_shadow_property'] ) ? $shortcode_data['client_image_box_shadow_property'] : array(
				'horizontal' => '0',
				'vertical'   => '0',
				'blur'       => '7',
				'spread'     => '0',
				'color'      => '#888888',
			);
			$outline                         .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image img,
		.sp-tpro-modal-testimonial-' . $post_id . ' .tpro-client-image img{
			background: ' . $client_image_bg . ';
			padding: ' . $image_padding . 'px;
			box-shadow: ' . $client_image_box_shadow_property['horizontal'] . 'px ' . $client_image_box_shadow_property['vertical'] . 'px ' . $client_image_box_shadow_property['blur'] . 'px ' . $client_image_box_shadow_property['spread'] . 'px ' . $client_image_box_shadow_property['color'] . ';
            margin: 7px;
		}';
		}
	}
}

if ( $show_testimonial_title ) {
	$testimonial_title_font_load  = isset( $shortcode_data['testimonial_title_font_load'] ) ? $shortcode_data['testimonial_title_font_load'] : '';
	$testimonial_title_typography = isset( $shortcode_data['testimonial_title_typography'] ) ? $shortcode_data['testimonial_title_typography'] : array(
		'font-family'    => '',
		'font-weight'    => '',
		'type'           => 'google',
		'font-size'      => '20',
		'line-height'    => '30',
		'text-align'     => 'center',
		'text-transform' => 'none',
		'letter-spacing' => 0,
		'color'          => '#333333',
		'margin-top'     => '0',
		'margin-right'   => '0',
		'margin-bottom'  => '18',
		'margin-left'    => '0',
	);

	$testimonial_title_typography_two   = isset( $shortcode_data['testimonial_title_typography_two'] ) ? $shortcode_data['testimonial_title_typography_two'] : $testimonial_title_typography;
	$testimonial_title_typography_three = isset( $shortcode_data['testimonial_title_typography_three'] ) ? $shortcode_data['testimonial_title_typography_three'] : $testimonial_title_typography;
	$testimonial_title_typography_four  = isset( $shortcode_data['testimonial_title_typography_four'] ) ? $shortcode_data['testimonial_title_typography_four'] : $testimonial_title_typography;
	if ( $testimonial_title_font_load ) {
		$tpro_typography[] = $testimonial_title_typography_two;
		$tpro_typography[] = $testimonial_title_typography_three;
		$tpro_typography[] = $testimonial_title_typography_four;
		$tpro_typography[] = $testimonial_title_typography;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title{
			margin: ' . $testimonial_title_typography['margin-top'] . 'px ' . $testimonial_title_typography['margin-right'] . 'px ' . $testimonial_title_typography['margin-bottom'] . 'px ' . $testimonial_title_typography['margin-left'] . 'px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_output( $testimonial_title_typography, $testimonial_title_font_load );
		$outline .= 'padding: 0;
					margin: 0;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_title_typography, $testimonial_title_font_load );
		$outline .= '
			padding: 0;
			margin: 0;
		}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title{
			margin: ' . $testimonial_title_typography['margin-top'] . 'px ' . $testimonial_title_typography['margin-right'] . 'px ' . $testimonial_title_typography['margin-bottom'] . 'px ' . $testimonial_title_typography['margin-left'] . 'px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_output( $testimonial_title_typography_two, $testimonial_title_font_load );
		$outline .= '
			padding: 0;
			margin: 0;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_title_typography_two, $testimonial_title_font_load );
		$outline .= '
			padding: 0;
			margin: 0;
		}';
	}
	if ( 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title{
			margin: ' . $testimonial_title_typography['margin-top'] . 'px ' . $testimonial_title_typography['margin-right'] . 'px ' . $testimonial_title_typography['margin-bottom'] . 'px ' . $testimonial_title_typography['margin-left'] . 'px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_output( $testimonial_title_typography_three, $testimonial_title_font_load );
		$outline .= '
			padding: 0;
			margin: 0;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_title_typography_three, $testimonial_title_font_load );
		$outline .= '
			padding: 0;
			margin: 0;
		}';
	}
	if ( 'theme-eight' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title{
			margin: ' . $testimonial_title_typography['margin-top'] . 'px ' . $testimonial_title_typography['margin-right'] . 'px ' . $testimonial_title_typography['margin-bottom'] . 'px ' . $testimonial_title_typography['margin-left'] . 'px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_output( $testimonial_title_typography_four, $testimonial_title_font_load );
		$outline .= '
			padding: 0;
			margin: 0;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-testimonial-title .sp-tpro-testimonial-title{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_title_typography_four, $testimonial_title_font_load );
		$outline .= '
			padding: 0;
			margin: 0;
		}';
	}
}

if ( $show_testimonial_text || $testimonial_read_more ) {
	$testimonial_text_font_load        = isset( $shortcode_data['testimonial_text_font_load'] ) ? $shortcode_data['testimonial_text_font_load'] : '';
	$testimonial_text_typography       = isset( $shortcode_data['testimonial_text_typography'] ) ? $shortcode_data['testimonial_text_typography'] : '';
	$testimonial_text_typography_two   = isset( $shortcode_data['testimonial_text_typography_two'] ) ? $shortcode_data['testimonial_text_typography_two'] : $testimonial_text_typography;
	$testimonial_text_typography_three = isset( $shortcode_data['testimonial_text_typography_three'] ) ? $shortcode_data['testimonial_text_typography_three'] : $testimonial_text_typography;
	$testimonial_text_typography_four  = isset( $shortcode_data['testimonial_text_typography_four'] ) ? $shortcode_data['testimonial_text_typography_four'] : $testimonial_text_typography;
	if ( $testimonial_text_font_load ) {
		$tpro_typography[] = $testimonial_text_typography;
		$tpro_typography[] = $testimonial_text_typography_two;
		$tpro_typography[] = $testimonial_text_typography_three;
		$tpro_typography[] = $testimonial_text_typography_four;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_output( $testimonial_text_typography, $testimonial_text_font_load );
		$outline .= 'margin: ' . $testimonial_text_typography['margin-top'] . 'px ' . $testimonial_text_typography['margin-right'] . 'px ' . $testimonial_text_typography['margin-bottom'] . 'px ' . $testimonial_text_typography['margin-left'] . 'px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial p{';
		$outline .= 'font-size: ' . $testimonial_text_typography['font-size'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_text_typography, $testimonial_text_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_output( $testimonial_text_typography_two, $testimonial_text_font_load );
		$outline .= 'margin: ' . $testimonial_text_typography['margin-top'] . 'px ' . $testimonial_text_typography['margin-right'] . 'px ' . $testimonial_text_typography['margin-bottom'] . 'px ' . $testimonial_text_typography['margin-left'] . 'px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial p{';
		$outline .= 'font-size: ' . $testimonial_text_typography['font-size'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_text_typography_two, $testimonial_text_font_load );
		$outline .= '}';
	}
	if ( 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_output( $testimonial_text_typography_three, $testimonial_text_font_load );
		$outline .= 'margin: ' . $testimonial_text_typography['margin-top'] . 'px ' . $testimonial_text_typography['margin-right'] . 'px ' . $testimonial_text_typography['margin-bottom'] . 'px ' . $testimonial_text_typography['margin-left'] . 'px;
		} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial p{';
		$outline .= 'font-size: ' . $testimonial_text_typography['font-size'] . 'px;
		} .sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_text_typography_three, $testimonial_text_font_load );
		$outline .= '}';
	}
	if ( 'theme-eight' === $theme_style ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_output( $testimonial_text_typography_four, $testimonial_text_font_load );
		$outline .= 'margin: ' . $testimonial_text_typography['margin-top'] . 'px ' . $testimonial_text_typography['margin-right'] . 'px ' . $testimonial_text_typography['margin-bottom'] . 'px ' . $testimonial_text_typography['margin-left'] . 'px;
		} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-testimonial p{';
		$outline .= 'font-size: ' . $testimonial_text_typography['font-size'] . 'px;
		} .sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-testimonial{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_text_typography_four, $testimonial_text_font_load );
		$outline .= '}';
	}
}

if ( $testimonial_client_name ) {
	$client_name_font_load      = isset( $shortcode_data['client_name_font_load'] ) ? $shortcode_data['client_name_font_load'] : '';
	$client_name_typography     = isset( $shortcode_data['client_name_typography'] ) ? $shortcode_data['client_name_typography'] : '';
	$client_name_typography_two = isset( $shortcode_data['client_name_typography_two'] ) ? $shortcode_data['client_name_typography_two'] : $client_name_typography;
	if ( $client_name_font_load ) {
		$tpro_typography[] = $client_name_typography;
		$tpro_typography[] = $client_name_typography_two;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-name{';
		$outline .= Helper::tpro_typography_output( $client_name_typography, $client_name_font_load );
		$outline .= 'margin: ' . $client_name_typography['margin-top'] . 'px ' . $client_name_typography['margin-right'] . 'px ' . $client_name_typography['margin-bottom'] . 'px ' . $client_name_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-name{';
		$outline .= Helper::tpro_typography_modal_output( $client_name_typography, $client_name_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-name{';
		$outline .= Helper::tpro_typography_output( $client_name_typography_two, $client_name_font_load );
		$outline .= 'margin: ' . $client_name_typography['margin-top'] . 'px ' . $client_name_typography['margin-right'] . 'px ' . $client_name_typography['margin-bottom'] . 'px ' . $client_name_typography['margin-left'] . 'px;
		}.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-name{';
		$outline .= Helper::tpro_typography_modal_output( $client_name_typography_two, $client_name_font_load );
		$outline .= '}';
	}
}

if ( $show_testimonial_client_rating ) {
	$testimonial_client_rating_alignment     = isset( $shortcode_data['testimonial_client_rating_alignment'] ) ? $shortcode_data['testimonial_client_rating_alignment'] : 'center';
	$testimonial_client_rating_alignment_two = isset( $shortcode_data['testimonial_client_rating_alignment_two'] ) ? $shortcode_data['testimonial_client_rating_alignment_two'] : $testimonial_client_rating_alignment;
	$testimonial_client_rating_margin        = isset( $shortcode_data['testimonial_client_rating_margin'] ) ? $shortcode_data['testimonial_client_rating_margin'] : array(
		'top'    => '0',
		'right'  => '0',
		'bottom' => '6',
		'left'   => '0',
	);
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-rating{
			margin: ' . $testimonial_client_rating_margin['top'] . 'px ' . $testimonial_client_rating_margin['right'] . 'px ' . $testimonial_client_rating_margin['bottom'] . 'px ' . $testimonial_client_rating_margin['left'] . 'px;
			text-align: ' . $testimonial_client_rating_alignment . ';
			color: ' . $shortcode_data['testimonial_client_rating_color'] . ';
		}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style
	) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-rating{
			margin: ' . $testimonial_client_rating_margin['top'] . 'px ' . $testimonial_client_rating_margin['right'] . 'px ' . $testimonial_client_rating_margin['bottom'] . 'px ' . $testimonial_client_rating_margin['left'] . 'px;
			text-align: ' . $testimonial_client_rating_alignment_two . ';
			color: ' . $shortcode_data['testimonial_client_rating_color'] . ';
		}';
	}
}

if ( $show_client_designation || $show_client_company_name || $show_client_company_logo ) {
	$designation_company_font_load             = isset( $shortcode_data['designation_company_font_load'] ) ? $shortcode_data['designation_company_font_load'] : '';
	$client_designation_company_typography     = isset( $shortcode_data['client_designation_company_typography'] ) ? $shortcode_data['client_designation_company_typography'] : '';
	$client_designation_company_typography_two = isset( $shortcode_data['client_designation_company_typography_two'] ) ? $shortcode_data['client_designation_company_typography_two'] : $client_designation_company_typography;
	if ( $designation_company_font_load ) {
		$tpro_typography[] = $client_designation_company_typography;
		$tpro_typography[] = $client_designation_company_typography_two;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-designation-company{';
		$outline .= Helper::tpro_typography_output( $client_designation_company_typography, $designation_company_font_load );
		$outline .= 'margin: ' . $client_designation_company_typography['margin-top'] . 'px ' . $client_designation_company_typography['margin-right'] . 'px ' . $client_designation_company_typography['margin-bottom'] . 'px ' . $client_designation_company_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-designation-company{';
		$outline .= Helper::tpro_typography_modal_output( $client_designation_company_typography, $designation_company_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style
	) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-designation-company{';
		$outline .= Helper::tpro_typography_output( $client_designation_company_typography_two, $designation_company_font_load );
		$outline .= 'margin: ' . $client_designation_company_typography['margin-top'] . 'px ' . $client_designation_company_typography['margin-right'] . 'px ' . $client_designation_company_typography['margin-bottom'] . 'px ' . $client_designation_company_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-designation-company{';
		$outline .= Helper::tpro_typography_modal_output( $client_designation_company_typography_two, $designation_company_font_load );
		$outline .= '}';
	}
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-company-image{
		text-align: ' . $client_designation_company_typography['text-align'] . '
	}';
}
if ( $show_testimonial_client_location ) {
	$location_font_load             = isset( $shortcode_data['location_font_load'] ) ? $shortcode_data['location_font_load'] : '';
	$client_location_typography     = isset( $shortcode_data['client_location_typography'] ) ? $shortcode_data['client_location_typography'] : '';
	$client_location_typography_two = isset( $shortcode_data['client_location_typography_two'] ) ? $shortcode_data['client_location_typography_two'] : $client_location_typography;
	if ( $location_font_load ) {
		$tpro_typography[] = $client_location_typography;
		$tpro_typography[] = $client_location_typography_two;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-location{';
		$outline .= Helper::tpro_typography_output( $client_location_typography, $location_font_load );
		$outline .= 'margin: ' . $client_location_typography['margin-top'] . 'px ' . $client_location_typography['margin-right'] . 'px ' . $client_location_typography['margin-bottom'] . 'px ' . $client_location_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-location{';
		$outline .= Helper::tpro_typography_modal_output( $client_location_typography, $location_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-location{';
		$outline .= Helper::tpro_typography_output( $client_location_typography_two, $location_font_load );
		$outline .= 'margin: ' . $client_location_typography['margin-top'] . 'px ' . $client_location_typography['margin-right'] . 'px ' . $client_location_typography['margin-bottom'] . 'px ' . $client_location_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-location{';
		$outline .= Helper::tpro_typography_modal_output( $client_location_typography_two, $location_font_load );
		$outline .= '}';
	}
}
if ( $show_testimonial_client_phone ) {
	$phone_font_load             = isset( $shortcode_data['phone_font_load'] ) ? $shortcode_data['phone_font_load'] : '';
	$client_phone_typography     = isset( $shortcode_data['client_phone_typography'] ) ? $shortcode_data['client_phone_typography'] : '';
	$client_phone_typography_two = isset( $shortcode_data['client_phone_typography_two'] ) ? $shortcode_data['client_phone_typography_two'] : $client_phone_typography;
	if ( $phone_font_load ) {
		$tpro_typography[] = $client_phone_typography;
		$tpro_typography[] = $client_phone_typography_two;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-phone{';
		$outline .= Helper::tpro_typography_output( $client_phone_typography, $phone_font_load );
		$outline .= 'margin: ' . $client_phone_typography['margin-top'] . 'px ' . $client_phone_typography['margin-right'] . 'px ' . $client_phone_typography['margin-bottom'] . 'px ' . $client_phone_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-phone{';
		$outline .= Helper::tpro_typography_modal_output( $client_phone_typography, $phone_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-phone{';
		$outline .= Helper::tpro_typography_output( $client_phone_typography_two, $phone_font_load );
		$outline .= 'margin: ' . $client_phone_typography['margin-top'] . 'px ' . $client_phone_typography['margin-right'] . 'px ' . $client_phone_typography['margin-bottom'] . 'px ' . $client_phone_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-phone{';
		$outline .= Helper::tpro_typography_modal_output( $client_phone_typography_two, $phone_font_load );
		$outline .= '}';
	}
}
if ( $show_testimonial_client_email ) {
	$email_font_load             = isset( $shortcode_data['email_font_load'] ) ? $shortcode_data['email_font_load'] : '';
	$client_email_typography     = isset( $shortcode_data['client_email_typography'] ) ? $shortcode_data['client_email_typography'] : '';
	$client_email_typography_two = isset( $shortcode_data['client_email_typography_two'] ) ? $shortcode_data['client_email_typography_two'] : $client_email_typography;
	if ( $email_font_load ) {
		$tpro_typography[] = $client_email_typography;
		$tpro_typography[] = $client_email_typography_two;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-email{';
		$outline .= Helper::tpro_typography_output( $client_email_typography, $email_font_load );
		$outline .= 'margin: ' . $client_email_typography['margin-top'] . 'px ' . $client_email_typography['margin-right'] . 'px ' . $client_email_typography['margin-bottom'] . 'px ' . $client_email_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-email{';
		$outline .= Helper::tpro_typography_modal_output( $client_email_typography, $email_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-email{';
		$outline .= Helper::tpro_typography_output( $client_email_typography_two, $email_font_load );
		$outline .= 'margin: ' . $client_email_typography['margin-top'] . 'px ' . $client_email_typography['margin-right'] . 'px ' . $client_email_typography['margin-bottom'] . 'px ' . $client_email_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-email{';
		$outline .= Helper::tpro_typography_modal_output( $client_email_typography_two, $email_font_load );
		$outline .= '}';
	}
}
if ( $show_testimonial_client_date ) {
	$date_font_load                  = isset( $shortcode_data['date_font_load'] ) ? $shortcode_data['date_font_load'] : '';
	$testimonial_date_typography     = isset( $shortcode_data['testimonial_date_typography'] ) ? $shortcode_data['testimonial_date_typography'] : '';
	$testimonial_date_typography_two = isset( $shortcode_data['testimonial_date_typography_two'] ) ? $shortcode_data['testimonial_date_typography_two'] : $testimonial_date_typography;
	if ( $date_font_load ) {
		$tpro_typography[] = $testimonial_date_typography;
		$tpro_typography[] = $testimonial_date_typography_two;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-date{';
		$outline .= Helper::tpro_typography_output( $testimonial_date_typography, $date_font_load );
		$outline .= 'margin: ' . $testimonial_date_typography['margin-top'] . 'px ' . $testimonial_date_typography['margin-right'] . 'px ' . $testimonial_date_typography['margin-bottom'] . 'px ' . $testimonial_date_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-testimonial-date{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_date_typography, $date_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-testimonial-date{';
		$outline .= Helper::tpro_typography_output( $testimonial_date_typography_two, $date_font_load );
		$outline .= 'margin: ' . $testimonial_date_typography['margin-top'] . 'px ' . $testimonial_date_typography['margin-right'] . 'px ' . $testimonial_date_typography['margin-bottom'] . 'px ' . $testimonial_date_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-testimonial-date{';
		$outline .= Helper::tpro_typography_modal_output( $testimonial_date_typography_two, $date_font_load );
		$outline .= '}';
	}
}
if ( $show_testimonial_client_website ) {
	$website_font_load              = isset( $shortcode_data['website_font_load'] ) ? $shortcode_data['website_font_load'] : '';
	$client_extra_fields_typography = isset( $shortcode_data['client_extra_fields_typography'] ) ? $shortcode_data['client_extra_fields_typography'] : array(
		'font-family'    => '',
		'font-weight'    => '',
		'type'           => 'google',
		'font-size'      => '14',
		'line-height'    => '20',
		'text-align'     => 'center',
		'text-transform' => 'uppercase',
		'letter-spacing' => 0,
		'color'          => '#444444',
		'margin-top'     => '0',
		'margin-right'   => '0',
		'margin-bottom'  => '6',
		'margin-left'    => '0',
	);
	$client_website_typography      = isset( $shortcode_data['client_website_typography'] ) ? $shortcode_data['client_website_typography'] : '';
	$client_website_typography_two  = isset( $shortcode_data['client_website_typography_two'] ) ? $shortcode_data['client_website_typography_two'] : $client_website_typography;
	if ( $website_font_load ) {
		$tpro_typography[] = $client_website_typography;
		$tpro_typography[] = $client_website_typography_two;
	}
	if ( 'theme-one' === $theme_style || 'theme-two' === $theme_style || 'theme-four' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-website{';
		$outline .= Helper::tpro_typography_output( $client_website_typography, $website_font_load );
		$outline .= 'margin: ' . $client_website_typography['margin-top'] . 'px ' . $client_website_typography['margin-right'] . 'px ' . $client_website_typography['margin-bottom'] . 'px ' . $client_website_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-website{';
		$outline .= Helper::tpro_typography_modal_output( $client_website_typography, $website_font_load );
		$outline .= '}';
	}
	if ( 'theme-three' === $theme_style || 'theme-five' === $theme_style || 'theme-six' === $theme_style || 'theme-seven' === $theme_style || 'theme-nine' === $theme_style ) {
		$outline .= '
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-website{';
		$outline .= Helper::tpro_typography_output( $client_website_typography_two, $website_font_load );
		$outline .= 'margin: ' . $client_website_typography['margin-top'] . 'px ' . $client_website_typography['margin-right'] . 'px ' . $client_website_typography['margin-bottom'] . 'px ' . $client_website_typography['margin-left'] . 'px;
		}
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-client-website{';
		$outline .= Helper::tpro_typography_modal_output( $client_website_typography, $website_font_load );
		$outline .= '}';
	}
}



$testimonial_client_addition_info = isset( $shortcode_data['testimonial_client_addition_info'] ) ? $shortcode_data['testimonial_client_addition_info'] : false;
if ( $testimonial_client_addition_info ) {
	$extra_fields_typography_font_load = isset( $shortcode_data['extra_fields_typography_font_load'] ) ? $shortcode_data['extra_fields_typography_font_load'] : false;
	$client_extra_fields_typography    = isset( $shortcode_data['client_extra_fields_typography'] ) ? $shortcode_data['client_extra_fields_typography'] : array(
		'font-family'    => '',
		'font-weight'    => '',
		'font-style'     => '',
		'type'           => 'google',
		'font-size'      => '14',
		'line-height'    => '20',
		'text-align'     => 'center',
		'text-transform' => 'none',
		'letter-spacing' => 0,
		'color'          => '#444444',
		'margin-top'     => '0',
		'margin-right'   => '0',
		'margin-bottom'  => '6',
		'margin-left'    => '0',
	);
	if ( $extra_fields_typography_font_load ) {
		$tpro_typography[] = $client_extra_fields_typography;
	}
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sptp-testimonial-extra-fields .sptp-testimonial-extra-info, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sptp-testimonial-extra-fields .sptp-testimonial-extra-info a{';
	$outline .= Helper::tpro_typography_output( $client_extra_fields_typography, $extra_fields_typography_font_load );
	$outline .= 'margin: ' . $client_extra_fields_typography['margin-top'] . 'px ' . $client_extra_fields_typography['margin-right'] . 'px ' . $client_extra_fields_typography['margin-bottom'] . 'px ' . $client_extra_fields_typography['margin-left'] . 'px;
	}';

}
$testimonial_border_radius_for_one = isset( $shortcode_data['testimonial_border_radius_for_one'] ) ? $shortcode_data['testimonial_border_radius_for_one'] : '0';
$testimonial_border                = isset( $shortcode_data['testimonial_border'] ) ? $shortcode_data['testimonial_border'] : '';
$testimonial_border_radius         = isset( $testimonial_border['radius'] ) ? $testimonial_border['radius'] : $testimonial_border_radius_for_one;

$testimonial_info_border   = isset( $shortcode_data['testimonial_info_border'] ) ? $shortcode_data['testimonial_info_border'] : '';
$testimonial_info_position = isset( $shortcode_data['testimonial_info_position'] ) ? $shortcode_data['testimonial_info_position'] : 'bottom';
if ( 'theme-one' === $theme_style ) {
	$testimonial_border_for_one = isset( $shortcode_data['testimonial_border_for_one'] ) ? $shortcode_data['testimonial_border_for_one'] : array(
		'all'    => '0',
		'style'  => 'solid',
		'color'  => '#e3e3e3',
		'radius' => '0',
	);
	$testimonial_border_radius  = isset( $testimonial_border_for_one['radius'] ) ? $testimonial_border_for_one['radius'] : $testimonial_border_radius_for_one;
	$testimonial_bg_for_one     = isset( $shortcode_data['testimonial_bg_for_one'] ) ? $shortcode_data['testimonial_bg_for_one'] : 'transparent';

	$testimonial_inner_padding_for_one = isset( $shortcode_data['testimonial_inner_padding_for_one'] ) ? $shortcode_data['testimonial_inner_padding_for_one'] : array(
		'top'    => '0',
		'right'  => '0',
		'bottom' => '0',
		'left'   => '0',
		'unit'   => 'px',
	);
	$outline                          .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro-item .sp-testimonial-pro{
		border: ' . $testimonial_border_for_one['all'] . 'px ' . $testimonial_border_for_one['style'] . ' ' . $testimonial_border_for_one['color'] . ';
		border-radius: ' . $testimonial_border_radius . 'px;
		padding: ' . $testimonial_inner_padding_for_one['top'] . 'px ' . $testimonial_inner_padding_for_one['right'] . 'px ' . $testimonial_inner_padding_for_one['bottom'] . 'px ' . $testimonial_inner_padding_for_one['left'] . 'px;
		background-color: ' . $testimonial_bg_for_one . ';
	}';
}
if ( 'theme-two' === $theme_style || 'theme-six' === $theme_style ) {
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
	    background: ' . $shortcode_data['testimonial_bg'] . ';
		border-radius: ' . $testimonial_border_radius . 'px;
	}';
	if ( ! $show_client_image ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
			padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
		}';
	}
} elseif ( 'theme-three' === $theme_style ) {
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		background: ' . $shortcode_data['testimonial_bg'] . ';
		border-radius: ' . $testimonial_border_radius . 'px;
	}';
	if ( ! $show_client_image ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
			padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
		}';
	}
} elseif ( 'theme-four' === $theme_style ) {
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		background: ' . $shortcode_data['testimonial_bg_two'] . ';
		border-radius: ' . $testimonial_border_radius . 'px;
		padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
	}';
} elseif ( 'theme-five' === $theme_style ) {
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		background: ' . $shortcode_data['testimonial_bg'] . ';
		padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
		border-radius: ' . $testimonial_border_radius . 'px;
	}';
} elseif ( 'theme-seven' === $theme_style ) {
	$tpro_border_width                = (int) $testimonial_border['all'] + 13;
	$tpro_border_height               = (int) $testimonial_border['all'] + 17;
	$tpro_margin_top                  = (int) $tpro_border_height + 10;
	$testimonial_arrow_position_seven = isset( $shortcode_data['testimonial_arrow_position_seven'] ) ? $shortcode_data['testimonial_arrow_position_seven'] : '30';
	$outline                         .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
		border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		background: ' . $shortcode_data['testimonial_bg_three'] . ';
		margin-top: ' . $tpro_margin_top . 'px;
		padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
		border-radius: ' . $testimonial_border_radius . 'px;
	}';
	$outline                         .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		bottom: 100%;
	    left: ' . $testimonial_arrow_position_seven . 'px;
	    border: solid transparent;
	    content: "";
	    height: 0;
	    width: 0;
	    position: absolute;
	    pointer-events: none;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
	    border-color: transparent;
	    border-bottom-color: ' . $shortcode_data['testimonial_bg_three'] . ';
	    border-width: 0 13px 17px 13px;
	    margin-left: 0;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
	    border-color: transparent;
	    border-bottom-color: ' . $testimonial_border['color'] . ';
	    border-width: 0 ' . $tpro_border_width . 'px ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px;
	    margin-left: -' . $testimonial_border['all'] . 'px;
	}';

	if ( ! $show_client_image ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
			padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
			position: relative;
		}';
	}
} elseif ( 'theme-eight' === $theme_style ) {
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		border-radius: ' . $testimonial_border_radius . 'px;
		overflow: hidden;
	}';

	if ( 'bottom' === $testimonial_info_position ) {
		$tpro_border_width  = (int) $testimonial_border['all'] + 13;
		$tpro_border_height = (int) $testimonial_border['all'] + 17;

		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
			border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
			background: ' . $shortcode_data['testimonial_bg_three'] . ';
			padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			position: relative;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			border-top: 0 solid;
			border-bottom: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-left: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-right: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-color: ' . $testimonial_info_border_color . ';
			background: ' . $shortcode_data['testimonial_info_bg'] . ';
			padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			top: 100%;
			right: 50%;
			border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-top-color: ' . $shortcode_data['testimonial_bg_three'] . ';
		    border-width: 17px 13px 0 13px;
		    margin-right: -13px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-top-color: ' . $testimonial_border['color'] . ';
		    border-width: ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px 0 ' . $tpro_border_width . 'px;
		    margin-right: -' . $tpro_border_width . 'px;
		}';
	} elseif ( 'top' === $testimonial_info_position ) {
		$tpro_border_width  = (int) $testimonial_border['all'] + 12;
		$tpro_border_height = (int) $testimonial_border['all'] + 15;

		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
			border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		    background: ' . $shortcode_data['testimonial_bg_three'] . ';
		    padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			position: relative;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			border-top: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-bottom: 0 solid;
			border-left: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-right: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-color: ' . $testimonial_info_border_color . ';
		    background: ' . $shortcode_data['testimonial_info_bg'] . ';
		    padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			bottom: 100%;
		    right: 50%;
		    border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-bottom-color: ' . $shortcode_data['testimonial_bg_three'] . ';
			border-width: 0 12px 15px 12px;
		    margin-right: -13px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-bottom-color: ' . $testimonial_border['color'] . ';
		    border-width: 0 ' . $tpro_border_width . 'px ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px;
		    margin-right: -' . $tpro_border_width . 'px;
		}';
	} elseif ( 'right' === $testimonial_info_position ) {
		$tpro_border_width  = (int) $testimonial_border['all'] + 13;
		$tpro_border_height = (int) $testimonial_border['all'] + 17;

		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
			border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		    background: ' . $shortcode_data['testimonial_bg_three'] . ';
		    padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			position: relative;
			display: table-cell;
			vertical-align: top;
			width: 100%;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			border-top: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-bottom: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-left: 0 solid;
			border-right: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-color: ' . $testimonial_info_border_color . ';
		    background: ' . $shortcode_data['testimonial_info_bg'] . ';
		    padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		    display: table-cell;
            vertical-align: top;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			left: 100%;
		    top: 50px;
		    border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-left-color: ' . $shortcode_data['testimonial_bg_three'] . ';
		    border-width: 13px 0 13px 17px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-left-color: ' . $testimonial_border['color'] . ';
		    border-width: ' . $tpro_border_width . 'px 0 ' . $tpro_border_width . 'px ' . $tpro_border_height . 'px;
		    margin-top: -' . $testimonial_border['all'] . 'px;
		}';
	} elseif ( 'left' === $testimonial_info_position ) {
		$tpro_border_width  = (int) $testimonial_border['all'] + 13;
		$tpro_border_height = (int) $testimonial_border['all'] + 17;

		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
			border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		    background: ' . $shortcode_data['testimonial_bg_three'] . ';
		    padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			position: relative;
			display: table-cell;
			vertical-align: top;
			width: 100%;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			border-top: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-bottom: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-left: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-right: 0 solid;
			border-color: ' . $testimonial_info_border_color . ';
		    background: ' . $shortcode_data['testimonial_info_bg'] . ';
		    padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		    display: table-cell;
            vertical-align: top;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			right: 100%;
		    top: 50px;
		    border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-right-color: ' . $shortcode_data['testimonial_bg_three'] . ';
		    border-width: 13px 17px 13px 0;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-right-color: ' . $testimonial_border['color'] . ';
		    border-width: ' . $tpro_border_width . 'px ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px 0;
		    margin-top: -' . $testimonial_border['all'] . 'px;
		}';
	}
} elseif ( 'theme-nine' === $theme_style ) {
	$tpro_border_width               = (int) $testimonial_border['all'] + 12;
	$tpro_border_height              = (int) $testimonial_border['all'] + 14;
	$testimonial_arrow_position_nine = isset( $shortcode_data['testimonial_arrow_position_nine'] ) ? $shortcode_data['testimonial_arrow_position_nine'] : '70';
	$outline                        .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		border-radius: ' . $testimonial_border_radius . 'px;
		overflow: hidden;
	}';
	$outline                        .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
			border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
		    background: ' . $shortcode_data['testimonial_bg_three'] . ';
		    padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			position: relative;
		}';

	if ( 'bottom_left' === $testimonial_info_position_two ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			display: flex;
			border-top: 0 solid;
			border-bottom: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-left: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-right: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-color: ' . $testimonial_info_border_color . ';
		    background: ' . $shortcode_data['testimonial_info_bg'] . ';
		    padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-text{
			flex: 1;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			top: 100%;
		    left: ' . $testimonial_arrow_position_nine . 'px;
		    border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-top-color: ' . $shortcode_data['testimonial_bg_three'] . ';
			border-width: 14px 12px 0 12px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-top-color: ' . $testimonial_border['color'] . ';
		    border-width: ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px 0 ' . $tpro_border_width . 'px;
		    margin-left: -' . $testimonial_border['all'] . 'px;
		}';
	} elseif ( 'bottom_right' === $testimonial_info_position_two ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			display: flex;
			border-top: 0 solid;
			border-bottom: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-left: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-right: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-color: ' . $testimonial_info_border_color . ';
		    background: ' . $shortcode_data['testimonial_info_bg'] . ';
		    padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-text{
			flex: 1;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			top: 100%;
		    right: ' . $testimonial_arrow_position_nine . 'px;
		    border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-top-color: ' . $shortcode_data['testimonial_bg_three'] . ';
		    border-width: 17px 13px 0 13px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-top-color: ' . $testimonial_border['color'] . ';
		    border-width: ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px 0 ' . $tpro_border_width . 'px;
		    margin-right: -' . $testimonial_border['all'] . 'px;
		}';
	} elseif ( 'top_left' === $testimonial_info_position_two ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			display: flex;
			border-top: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-bottom: 0 solid;
			border-left: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-right: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-color: ' . $testimonial_info_border_color . ';
		    background: ' . $shortcode_data['testimonial_info_bg'] . ';
		    padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-text{
			flex: 1;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			bottom: 100%;
		    left: 70px;
		    border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-bottom-color: ' . $shortcode_data['testimonial_bg_three'] . ';
		    border-width: 0 13px 17px 13px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-bottom-color: ' . $testimonial_border['color'] . ';
		    border-width: 0 ' . $tpro_border_width . 'px ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px;
		    margin-left: -' . $testimonial_border['all'] . 'px;
		}';
	} elseif ( 'top_right' === $testimonial_info_position_two ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-area{
			display: flex;
			border-top: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-bottom: 0 solid;
			border-left: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-right: ' . $testimonial_info_border['all'] . 'px ' . $testimonial_info_border['style'] . ';
			border-color: ' . $testimonial_info_border_color . ';
		    background: ' . $shortcode_data['testimonial_info_bg'] . ';
		    padding: ' . $testimonial_info_inner_padding['top'] . 'px ' . $testimonial_info_inner_padding['right'] . 'px ' . $testimonial_info_inner_padding['bottom'] . 'px ' . $testimonial_info_inner_padding['left'] . 'px;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-meta-text{
			flex: 1;
		}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after, #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
			bottom: 100%;
		    right: 70px;
		    border: solid transparent;
		    content: "";
		    height: 0;
		    width: 0;
		    position: absolute;
		    pointer-events: none;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:after {
		    border-color: transparent;
		    border-bottom-color: ' . $shortcode_data['testimonial_bg_three'] . ';
		    border-width: 0 13px 17px 13px;
		}
		#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area:before {
		    border-color: transparent;
		    border-bottom-color: ' . $testimonial_border['color'] . ';
		    border-width: 0 ' . $tpro_border_width . 'px ' . $tpro_border_height . 'px ' . $tpro_border_width . 'px;
		    margin-right: -' . $testimonial_border['all'] . 'px;
		}';
	}
} elseif ( 'theme-ten' === $theme_style ) {
	$border = isset( $shortcode_data['testimonial_border_theme_ten'] ) ? $shortcode_data['testimonial_border_theme_ten'] : array(
		'all'    => '1',
		'style'  => 'solid',
		'color'  => '#e3e3e3',
		'radius' => '10',
	);
	if ( 'border' === $client_image_border_shadow ) {
		$client_image_total_height_size = $client_image_height + $image_border_width + $image_border_width + $image_padding + $image_padding;
	} elseif ( 'box_shadow' === $client_image_border_shadow ) {
		$client_image_total_height_size = $client_image_height + 14 + $image_padding + $image_padding;
	}

	$client_image_height_size_two    = $client_image_total_height_size / 2;
	$client_image_height_size        = $client_image_height_size_two + $testimonial_inner_padding['top'];
	$testimonial_box_shadow_property = isset( $shortcode_data['testimonial_box_shadow_property'] ) ? $shortcode_data['testimonial_box_shadow_property'] : array(
		'horizontal' => '0',
		'vertical'   => '0',
		'blur'       => '10',
		'spread'     => '0',
		'color'      => '#dddddd',
	);

	$outline                          .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		border: ' . $border['all'] . 'px ' . $border['style'] . ' ' . $border['color'] . ';
	    background: ' . $shortcode_data['testimonial_bg'] . ';
	    -webkit-border-radius: ' . $border['radius'] . 'px;
	    -moz-border-radius: ' . $border['radius'] . 'px;
	    border-radius: ' . $border['radius'] . 'px;
	    box-shadow: ' . $testimonial_box_shadow_property['horizontal'] . 'px ' . $testimonial_box_shadow_property['vertical'] . 'px ' . $testimonial_box_shadow_property['blur'] . 'px ' . $testimonial_box_shadow_property['spread'] . 'px ' . $testimonial_box_shadow_property['color'] . ';
        margin: 10px;
        position: relative;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .sp-tpro-top-background {
	    background: ' . $shortcode_data['testimonial_top_bg'] . ';
	    height: ' . $client_image_height_size . 'px;
	    width: 100%;
	    position: absolute;
	    top: 0;
	    left: 0;
	    z-index: 1;
	    -webkit-border-radius: ' . $border['radius'] . 'px ' . $border['radius'] . 'px 0 0;
	    -moz-border-radius: ' . $border['radius'] . 'px ' . $border['radius'] . 'px 0 0;
	    border-radius: ' . $border['radius'] . 'px ' . $border['radius'] . 'px 0 0;
	}';
	$outline                          .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
		padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
	}';
	$client_name_typography_margin_top = isset( $shortcode_data['client_name_typography']['margin-top'] ) ? $shortcode_data['client_name_typography']['margin-top'] : 0;
	$outline                          .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .sp-tpro-top-background + .tpro-client-name {
			margin-top: calc( ' . $client_image_height_size . 'px + ' . $client_name_typography_margin_top . 'px  );
		}';
}
if ( $show_client_image && 'theme-two' === $theme_style ) {
	if ( 'border' === $client_image_border_shadow ) {
		$client_image_total_width_size  = $client_image_width + $image_border_width + $image_border_width + $image_padding + $image_padding;
		$client_image_total_height_size = $client_image_height + $image_border_width + $image_border_width + $image_padding + $image_padding;
	} elseif ( 'box_shadow' === $client_image_border_shadow ) {
		$client_image_total_width_size  = $client_image_width + 14 + $image_padding + $image_padding;
		$client_image_total_height_size = $client_image_height + 14 + $image_padding + $image_padding;
	}
	$client_image_width_size       = $client_image_total_width_size / 2;
	$client_image_width_size_right = $client_image_width_size + (int) $testimonial_margin_value;
	$client_image_height_size      = $client_image_total_height_size / 2;

	$testimonial_inner_padding_left   = $testimonial_inner_padding['left'] + $client_image_width_size;
	$testimonial_inner_padding_right  = $testimonial_inner_padding['right'] + $client_image_width_size;
	$testimonial_inner_padding_top    = $testimonial_inner_padding['top'] + $client_image_width_size;
	$testimonial_inner_padding_bottom = $testimonial_inner_padding['bottom'] + $client_image_width_size;

	// Left site image.
	if ( 'left' === $client_image_position_two ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-left:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding_left . 'px;
			}#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    top: 50%;
			    left: -' . $client_image_width_size . 'px;
			    margin-top: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'right' === $client_image_position_two ) {
		// Right site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-right:' . $client_image_width_size_right . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding_right . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    top: 50%;
			    right: -' . $client_image_width_size . 'px;
			    margin-top: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'top' === $client_image_position_two ) {
		// Top site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-top:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding_top . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    left: 50%;
			    top: -' . $client_image_height_size . 'px;
			    margin-left: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'bottom' === $client_image_position_two ) {
		// Bottom site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-bottom:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding_bottom . 'px ' . $testimonial_inner_padding['left'] . 'px;
			}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    left: 50%;
			    bottom: -' . $client_image_height_size . 'px;
			    margin-left: -' . $client_image_width_size . 'px;
			}';
	}
}

if ( $show_client_image && 'theme-three' === $theme_style ) {

	if ( 'border' === $client_image_border_shadow ) {
		$client_image_total_width_size  = $client_image_width + $image_border_width + $image_border_width + $image_padding + $image_padding;
		$client_image_total_height_size = $client_image_height + $image_border_width + $image_border_width + $image_padding + $image_padding;
	} elseif ( 'box_shadow' === $client_image_border_shadow ) {
		$client_image_total_width_size  = $client_image_width + 14 + $image_padding + $image_padding;
		$client_image_total_height_size = $client_image_height + 14 + $image_padding + $image_padding;
	}

	$client_image_width_size       = $client_image_total_width_size / 2;
	$client_image_width_size_right = $client_image_width_size + (int) $testimonial_margin_value;
	$client_image_height_size      = $client_image_total_height_size / 2;

	$testimonial_inner_padding_left   = $testimonial_inner_padding['left'] + $client_image_width_size;
	$testimonial_inner_padding_right  = $testimonial_inner_padding['right'] + $client_image_width_size;
	$testimonial_inner_padding_top    = $testimonial_inner_padding['top'] + $client_image_width_size;
	$testimonial_inner_padding_bottom = $testimonial_inner_padding['bottom'] + $client_image_width_size;

	// Left-Top site image.
	if ( 'left-top' === $client_image_position_three ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-left:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding_left . 'px;
			}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    top: 45px;
			    left: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'left-bottom' === $client_image_position_three ) {
		// Left-Bottom site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-left:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding_left . 'px;
			}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    bottom: 45px;
			    left: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'right-top' === $client_image_position_three ) {
		// Right-Top site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-right:' . $client_image_width_size_right . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding_right . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			}';
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    top: 45px;
			    right: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'right-bottom' === $client_image_position_three ) {
		// Right-Bottom site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-right:' . $client_image_width_size_right . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding_right . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    bottom: 45px;
			    right: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'top-left' === $client_image_position_three ) {
		// Top-Left site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-top:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding_top . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    left: 45px;
			    top: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'top-right' === $client_image_position_three ) {
		// Top-Left site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-top:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding_top . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    right: 45px;
			    top: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'bottom-left' === $client_image_position_three ) {
		// Bottom-Left site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-bottom:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding_bottom . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    left: 45px;
			    bottom: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'bottom-right' === $client_image_position_three ) {
		// Bottom-Right site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-bottom:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding_bottom . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    right: 45px;
			    bottom: -' . $client_image_height_size . 'px;
			}';
	}
}

if ( $show_client_image && 'theme-six' === $theme_style ) {

	if ( 'border' === $client_image_border_shadow && $show_client_image ) {
		$client_image_total_width_size  = $client_image_width + $image_border_width + $image_border_width + $image_padding + $image_padding;
		$client_image_total_height_size = $client_image_height + $image_border_width + $image_border_width + $image_padding + $image_padding;
	} elseif ( 'box_shadow' === $client_image_border_shadow ) {
		$client_image_total_width_size  = $client_image_width + 14 + $image_padding + $image_padding;
		$client_image_total_height_size = $client_image_height + 14 + $image_padding + $image_padding;
	}

	$client_image_width_size       = $client_image_total_width_size / 2;
	$client_image_width_size_right = $client_image_width_size + (int) $testimonial_margin_value;
	$client_image_height_size      = $client_image_total_height_size / 2;

	$testimonial_inner_padding_left   = $testimonial_inner_padding['left'] + $client_image_width_size;
	$testimonial_inner_padding_right  = $testimonial_inner_padding['right'] + $client_image_width_size;
	$testimonial_inner_padding_top    = $testimonial_inner_padding['top'] + $client_image_width_size;
	$testimonial_inner_padding_bottom = $testimonial_inner_padding['bottom'] + $client_image_width_size;

	// Left-Top site image.
	if ( 'left-top' === $client_image_position_three ) {
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-left:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding_left . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    top: 30px;
			    left: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'left-bottom' === $client_image_position_three ) {
		// Left-Bottom site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-left:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding_left . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    bottom: 30px;
			    left: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'right-top' === $client_image_position_three ) {
		// Right-Top site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-right:' . $client_image_width_size_right . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding_right . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    top: 30px;
			    right: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'right-bottom' === $client_image_position_three ) {
		// Right-Bottom site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-right:' . $client_image_width_size_right . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding_right . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    bottom: 30px;
			    right: -' . $client_image_width_size . 'px;
			}';
	} elseif ( 'top-left' === $client_image_position_three ) {
		// Top-Left site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-top:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding_top . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    left: 30px;
			    top: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'top-right' === $client_image_position_three ) {
		// Top-Left site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-top:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding_top . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    right: 30px;
			    top: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'bottom-left' === $client_image_position_three ) {
		// Bottom-Left site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-bottom:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding_bottom . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    left: 30px;
			    bottom: -' . $client_image_height_size . 'px;
			}';
	} elseif ( 'bottom-right' === $client_image_position_three ) {
		// Bottom-Right site image.
		$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro{
				margin-bottom:' . $client_image_width_size . 'px;
				position: relative;
				padding: ' . $testimonial_inner_padding['top'] . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding_bottom . 'px ' . $testimonial_inner_padding['left'] . 'px;
			} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
				position: absolute;
			    right: 30px;
			    bottom: -' . $client_image_height_size . 'px;
			}';
	}
}
if ( $show_client_image && 'theme-seven' === $theme_style ) {
	if ( 'border' === $client_image_border_shadow ) {
		$client_image_total_height_size = $client_image_height + $image_border_width + $image_border_width + $image_padding + $image_padding;
	} elseif ( 'box_shadow' === $client_image_border_shadow ) {
		$client_image_total_height_size = $client_image_height + 14 + $image_padding + $image_padding;
	}

	$client_image_height_size        = $client_image_total_height_size / 100;
	$client_image_height_size_top    = $client_image_height_size * 75;
	$client_image_height_size_bottom = $client_image_height_size * 25;

	$testimonial_inner_padding_top = $testimonial_inner_padding['top'] + $client_image_height_size_bottom;

	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-testimonial-content-area{
		position: relative;
		padding: ' . $testimonial_inner_padding_top . 'px ' . $testimonial_inner_padding['right'] . 'px ' . $testimonial_inner_padding['bottom'] . 'px ' . $testimonial_inner_padding['left'] . 'px;
	} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pro .tpro-client-image{
		position: absolute;
	    right: 30px;
	    top: -' . $client_image_height_size_top . 'px;
	}';
}

// $pagination_dots
$pagination_data = isset( $shortcode_data['pagination'] ) ? $shortcode_data['pagination'] : 'true';
switch ( $pagination_data ) {
	case 'true':
		$pagination_dots   = 'true';
		$pagination_mobile = 'true';
		break;
	case 'hide_on_mobile':
		$pagination_dots   = 'true';
		$pagination_mobile = 'false';
		break;
	case 'false':
		$pagination_dots   = 'false';
		$pagination_mobile = 'false';
		break;
}
if ( 'true' === $pagination_dots && 'slider' === $layout ) {
	$pagination_margin       = isset( $shortcode_data['pagination_margin'] ) ? $shortcode_data['pagination_margin'] : array(
		'top'    => '21',
		'right'  => '0',
		'bottom' => '0',
		'left'   => '0',
	);
	$pagination_colors       = isset( $shortcode_data['pagination_colors'] ) ? $shortcode_data['pagination_colors'] : '';
	$pagination_color        = isset( $pagination_colors['color'] ) ? $pagination_colors['color'] : '';
	$pagination_active_color = isset( $pagination_colors['active-color'] ) ? $pagination_colors['active-color'] : '';
	$outline                .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pagination{
		margin: ' . $pagination_margin['top'] . 'px ' . $pagination_margin['right'] . 'px ' . $pagination_margin['bottom'] . 'px ' . $pagination_margin['left'] . 'px;
	} #sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pagination .swiper-pagination-bullet{
		background: ' . $pagination_color . ';
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .sp-testimonial-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active {
		background: ' . $pagination_active_color . ';
	}';
}
// Navigation.
$navigation_data = ( isset( $shortcode_data['navigation'] ) ? $shortcode_data['navigation'] : 'true' );
switch ( $navigation_data ) {
	case 'true':
		$navigation_arrows = 'true';
		$navigation_mobile = 'true';
		break;
	case 'hide_on_mobile':
		$navigation_arrows = 'true';
		$navigation_mobile = 'false';
		break;
	case 'false':
		$navigation_arrows = 'false';
		$navigation_mobile = 'false';
		break;
}
	$navigation_position = isset( $shortcode_data['navigation_position'] ) ? $shortcode_data['navigation_position'] : 'vertical_center';
if ( 'true' === $navigation_arrows && 'slider' === $layout || $navigation_mobile && 'slider' === $layout ) {
	$navigation_colors             = isset( $shortcode_data['navigation_color'] ) ? $shortcode_data['navigation_color'] : '';
	$navigation_icon_size          = isset( $shortcode_data['navigation_icon_size'] ) ? $shortcode_data['navigation_icon_size'] : '20';
	$navigation_bg                 = isset( $navigation_colors['background'] ) ? $navigation_colors['background'] : '';
	$navigation_hover_bg           = isset( $navigation_colors['hover-background'] ) ? $navigation_colors['hover-background'] : '';
	$navigation_color              = isset( $navigation_colors['color'] ) ? $navigation_colors['color'] : '';
	$navigation_hover_color        = isset( $navigation_colors['hover-color'] ) ? $navigation_colors['hover-color'] : '';
	$navigation_border             = isset( $shortcode_data['navigation_border'] ) ? $shortcode_data['navigation_border'] : array(
		'all'         => '1',
		'style'       => 'solid',
		'color'       => '#777777',
		'hover-color' => '#1595CE',
	);
	$line_height                   = 32 - ( (int) $navigation_border['all'] * 2 );
	$navigation_border_radius      = isset( $shortcode_data['navigation_border_radius'] ) ? $shortcode_data['navigation_border_radius'] : '';
	$navigation_border_radius_size = isset( $navigation_border_radius['all'] ) ? $navigation_border_radius['all'] : '0';
	$navigation_border_radius_unit = isset( $navigation_border_radius['unit'] ) ? $navigation_border_radius['unit'] : 'px';
	$outline                      .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-button-prev,
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-button-next{
		background: ' . $navigation_bg . ';
		color: ' . $navigation_color . ';
		border: ' . $navigation_border['all'] . 'px ' . $navigation_border['style'] . ' ' . $navigation_border['color'] . ';
		border-radius: ' . $navigation_border_radius_size . '' . $navigation_border_radius_unit . ';
		line-height: ' . $line_height . 'px;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-button-prev i:before,
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-button-next i:before{
		font-size: ' . $navigation_icon_size . 'px;
	}
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-button-prev:hover,
	#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-button-next:hover{
		background: ' . $navigation_hover_bg . ';
		color: ' . $navigation_hover_color . ';
		border-color: ' . $navigation_border['hover-color'] . ';
	}';

	if ( 'slider' === $layout && $navigation_arrows || 'slider' === $layout && $navigation_mobile ) {

		if ( 'top_right' === $navigation_position || 'top_center' === $navigation_position || 'top_left' === $navigation_position ) {
			if ( $section_title ) {
				$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_right,
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_center,
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_left {
					padding-top: 4px;
				}';
			} else {
				$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_right,
					#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_center,
					#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_left {
					    padding-top: 46px;
					}';
			}
			$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_right .sp-testimonial-pro-section.sp-tpCarousel ,
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_center .sp-testimonial-pro-section.sp-tpCarousel ,
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_left .sp-testimonial-pro-section.sp-tpCarousel  {
					padding-top: 55px;
				}
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_right .sp-testimonial-pro-section.sp-tpCarousel .tpro-arrow ,
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_center .sp-testimonial-pro-section.sp-tpCarousel .tpro-arrow ,
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_left .sp-testimonial-pro-section.sp-tpCarousel .tpro-arrow {
					top:30px;
				}';
		}


		if ( 'bottom_right' === $navigation_position || 'bottom_center' === $navigation_position || 'bottom_left' === $navigation_position
		) {
			$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_bottom_right,
					#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_bottom_center,
					#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_bottom_left {
					    padding-bottom: 46px;
					}';
		}
		if ( 'top_right' === $navigation_position ) {
			$nav_margin_right = (int) $testimonial_margin_value + '38';
			$outline         .= '
			#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_right .tpro-button-next {
				right: 0;
			}
			#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_top_right .tpro-button-prev {
				right: 40px;
			}
			';
		}
		if ( 'bottom_right' === $navigation_position ) {
			$nav_margin_right = (int) $testimonial_margin_value + '38';
			$outline         .= '
				#sp-testimonial-pro-wrapper-' . $post_id . ' .tpro-button-next {
				    right: 0;
				}
				#sp-testimonial-pro-wrapper-' . $post_id . ' .tpro-button-prev {
				    right: 40px;
				}
				';
		}

		if ( ( 'vertical_center' === $navigation_position || 'vertical_center_outer_hover' === $navigation_position ) && 'ticker' !== $slider_mode ) {
			$outline .= '
				#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_vertical_center .sp-testimonial-pro-section:not(.sp-testimonial-pro-section-thumb),#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_vertical_center_outer_hover .sp-testimonial-pro-section:not(.sp-testimonial-pro-section-thumb){
				    margin: 0 50px;
				}
				#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section{
				    padding: 0 60px;
				}
				#sp-testimonial-pro-wrapper-' . $post_id . ' .tpro-button-next {
				    right: 0;
					left:auto;
				}
				/* xs */
				@media (max-width: 600px) {';
			if ( 'false' === $navigation_mobile ) {
				$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_vertical_center .sp-testimonial-pro-section:not(.sp-testimonial-pro-section-thumb),#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_vertical_center_outer_hover .sp-testimonial-pro-section:not(.sp-testimonial-pro-section-thumb) {
					margin: 0 5px;
				}';
			} else {
				$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_vertical_center .sp-testimonial-pro-section:not(.sp-testimonial-pro-section-thumb),#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_vertical_center_outer_hover .sp-testimonial-pro-section:not(.sp-testimonial-pro-section-thumb) {
					margin: 0 35px;
				}';
			}
				$outline .= '}
				';
		}
		if ( 'vertical_center_inner' === $navigation_position ) {

			$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .tpro-button-next {
				    right: 10px;
				}';
		}
		if ( 'vertical_center_inner_hover' === $navigation_position ) {
			$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . ':hover .tpro-button-next {
				right: 10px;
			}
			#sp-testimonial-pro-wrapper-' . $post_id . ':hover .tpro-button-prev {
				left: 10px;
			}';
		}
		if ( 'vertical_center_outer_hover' === $navigation_position ) {
			$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .tpro-button-next {
				right: 50px;
			}
			#sp-testimonial-pro-wrapper-' . $post_id . ' .tpro-button-prev {
				left: 50px;
			}
			#sp-testimonial-pro-wrapper-' . $post_id . ':hover .tpro-button-next {
				right: 0;
			}
			#sp-testimonial-pro-wrapper-' . $post_id . ':hover .tpro-button-prev {
				left: 0;
			}';
		}
		if ( 'hide_on_mobile' === $navigation_data ) {
			$outline .= '@media only screen and (max-width: 576px){
		#sp-testimonial-pro-wrapper-' . $post_id . '.sp_tpro_nav_position_vertical_center .sp-testimonial-pro-section{
			margin:0px;
		}
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-arrow{ display: none; }
	}';
		}
	}
}//$navigation

if ( 'grid' === $layout || 'masonry' === $layout || 'list' === $layout || 'filter' === $layout ) {
	if ( 'filter' === $layout ) {
		$grid_pagination = isset( $shortcode_data['filter_pagination'] ) ? $shortcode_data['filter_pagination'] : false;
		$pagination_type = isset( $shortcode_data['filter_pagination_type'] ) ? $shortcode_data['filter_pagination_type'] : 'infinite_scroll';
	}

	if ( $grid_pagination ) {
		$grid_pagination_margin = isset( $shortcode_data['grid_pagination_margin'] ) ? $shortcode_data['grid_pagination_margin'] : array(
			'top'    => '20',
			'right'  => '0',
			'bottom' => '20',
			'left'   => '0',
		);
		$grid_pagination_colors = isset( $shortcode_data['grid_pagination_colors'] ) ? $shortcode_data['grid_pagination_colors'] : '';
		$grid_pagination_border = isset( $shortcode_data['grid_pagination_border'] ) ? $shortcode_data['grid_pagination_border'] : '';
		$pagination_alignment   = isset( $shortcode_data['grid_pagination_alignment'] ) ? $shortcode_data['grid_pagination_alignment'] : 'center';
		if ( 'filter' === $layout ) {
			$grid_pagination_margin = isset( $shortcode_data['filter_pagination_margin'] ) ? $shortcode_data['grid_pagination_margin'] : array(
				'top'    => '20',
				'right'  => '0',
				'bottom' => '20',
				'left'   => '0',
			);
			$grid_pagination_colors = isset( $shortcode_data['filter_pagination_colors'] ) ? $shortcode_data['filter_pagination_colors'] : array(
				'color'            => '#5e5e5e',
				'hover-color'      => '#ffffff',
				'background'       => '#ffffff',
				'hover-background' => '#1595CE',
			);
			$grid_pagination_border = isset( $shortcode_data['filter_pagination_border'] ) ? $shortcode_data['filter_pagination_border'] : array(
				'all'         => '2',
				'style'       => 'solid',
				'color'       => '#bbbbbb',
				'hover-color' => '#1595CE',
			);
			$pagination_alignment   = isset( $shortcode_data['filter_pagination_alignment'] ) ? $shortcode_data['filter_pagination_alignment'] : 'center';
		}
		$outline .= '
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .page-load-status,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .testimonial_page_load_status_alignment,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-items-load-more,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .sp-testimonial-ajax-pagination,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .sp-tpro-pagination-area {
		     text-align: ' . $pagination_alignment . ';
		}
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-items-load-more,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .sp-testimonial-ajax-pagination,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section ul.sp-tpro-pagination {
		     margin: ' . $grid_pagination_margin['top'] . 'px ' . $grid_pagination_margin['right'] . 'px ' . $grid_pagination_margin['bottom'] . 'px ' . $grid_pagination_margin['left'] . 'px;
		}
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-items-load-more span,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section ul.sp-tpro-pagination li a,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .sp-testimonial-ajax-pagination a,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section ul.sp-tpro-pagination li span {
		     color: ' . $grid_pagination_colors['color'] . ';
		     background: ' . $grid_pagination_colors['background'] . ';
             border: ' . $grid_pagination_border['all'] . 'px ' . $grid_pagination_border['style'] . ' ' . $grid_pagination_border['color'] . ';
		}
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-items-load-more span:hover ,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section ul.sp-tpro-pagination li span.current,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .sp-testimonial-ajax-pagination a.current,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .sp-testimonial-ajax-pagination a:hover,
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section ul.sp-tpro-pagination li a:hover{
		    background: ' . $grid_pagination_colors['hover-background'] . ';
            color: ' . $grid_pagination_colors['hover-color'] . ';
            border-color: ' . $grid_pagination_border['hover-color'] . ';
		}
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .testimonial_page_load_status_alignment .loader-ellips__dot{
			background: ' . $grid_pagination_colors['hover-background'] . ';
		}
		';
		if ( 'right' === $shortcode_data['grid_pagination_alignment'] ) {
			$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section ul.sp-tpro-pagination,#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section ul.sp-testimonial-ajax-pagination{
				padding-right:' . $horizontal_space . ';
			}';
		}
	}
}
$show_social_profile = isset( $shortcode_data['social_profile'] ) ? $shortcode_data['social_profile'] : '';
if ( $show_social_profile ) {
	$social_profile_position   = isset( $shortcode_data['social_profile_position'] ) ? $shortcode_data['social_profile_position'] : '';
	$social_icon_color         = isset( $shortcode_data['social_icon_color'] ) ? $shortcode_data['social_icon_color'] : '';
	$social_icon_custom_color  = isset( $shortcode_data['social_icon_custom_color'] ) ? $shortcode_data['social_icon_custom_color'] : '';
	$social_icon_border        = isset( $shortcode_data['social_icon_border'] ) ? $shortcode_data['social_icon_border'] : '';
	$social_icon_border_radius = isset( $shortcode_data['social_icon_border_radius'] ) ? $shortcode_data['social_icon_border_radius'] : '';
	$social_profile_margin     = isset( $shortcode_data['social_profile_margin'] ) ? $shortcode_data['social_profile_margin'] : array(
		'top'    => '15',
		'right'  => '0',
		'bottom' => '6',
		'left'   => '0',
	);
	$outline                  .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-social-profile{
			text-align: ' . $social_profile_position . ';
		} #sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-social-profile,
	.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-social-profile{
		margin: ' . $social_profile_margin['top'] . 'px ' . $social_profile_margin['right'] . 'px ' . $social_profile_margin['bottom'] . 'px ' . $social_profile_margin['left'] . 'px;
	}
	#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-social-profile a,
	.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-social-profile a{';
	if ( $social_icon_custom_color ) {
		$outline .= 'color: ' . $social_icon_color['color'] . ';
			background: ' . $social_icon_color['background'] . ';
			border: ' . $social_icon_border['all'] . 'px ' . $social_icon_border['style'] . ' ' . $social_icon_border['color'] . ';';
	}
	$outline .= '-webkit-border-radius: ' . $social_icon_border_radius['all'] . '' . $social_icon_border_radius['unit'] . ';
		-moz-border-radius: ' . $social_icon_border_radius['all'] . '' . $social_icon_border_radius['unit'] . ';
		border-radius: ' . $social_icon_border_radius['all'] . '' . $social_icon_border_radius['unit'] . ';
	}';
	if ( $social_icon_custom_color ) {
		$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro-section .tpro-social-profile a:hover,
		.sp-tpro-modal-testimonial-' . $post_id . '.sp-tpro-modal-testimonial .tpro-social-profile a:hover{
			color: ' . $social_icon_color['hover-color'] . ';
			background: ' . $social_icon_color['hover-background'] . ';
			border-color: ' . $social_icon_border['hover-color'] . ';
		}';
	}
}
// Image zoom css.
switch ( $image_zoom ) {
	case 'zoom_in':
		$outline .= '
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image:hover img:not(.iframe-preview),#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image:hover .sp-tpro-img:before,#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image:hover .sp-tpro-video:before{
			-webkit-transform: scale(1.1);
			-moz-transform: scale(1.1);
			transform: scale(1.1);
		}';
		$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image img:not(.iframe-preview),#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image .sp-tpro-img:before,#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image .sp-tpro-video:before{
			-webkit-transform: scale(1);
			-moz-transform: scale(1);
			transform: scale(1);
			 transition: transform 0.5s ease;
		}';
		break;
	case 'zoom_out':
		$outline .= '
		#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image img:not(.iframe-preview),#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image .sp-tpro-img:before,#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image .sp-tpro-video:before{
			-webkit-transform: scale(1.1);
			-moz-transform: scale(1.1);
			transform: scale(1.1);
			 transition: transform 0.5s ease;
		}';
		$outline .= '#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image:hover img:not(.iframe-preview),#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image:hover .sp-tpro-img:before,#sp-testimonial-pro-wrapper-' . $post_id . ' .sp-testimonial-pro .tpro-client-image:hover .sp-tpro-video:before{
			-webkit-transform: scale(1);
			-moz-transform: scale(1);
			transform: scale(1);
		}';
		break;
}
if ( $image_zoom && ( 'theme-one' === $theme_style || 'theme-eight' === $theme_style || 'theme-ten' === $theme_style ) ) {
	$center_alignment = '';
	if ( 'center' === $client_image_position ) {
		$center_alignment = 'margin:auto';
	} elseif ( 'right' === $client_image_position ) {
		$center_alignment = 'margin-left: auto';
	}
	$outline .= '#sp-testimonial-pro-' . $post_id . '.sp-testimonial-pro-section .tpro-client-image{
		' . $center_alignment . ';
		margin-top : ' . $client_image_margin['top'] . 'px;
		margin-bottom: ' . $client_image_margin['bottom'] . 'px;
		
	}';
}

