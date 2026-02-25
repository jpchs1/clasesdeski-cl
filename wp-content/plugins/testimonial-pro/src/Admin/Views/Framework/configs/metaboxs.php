<?php
/**
 * The admin-specific meta fields of the plugin.
 *
 * @since        2.5.0
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

use ShapedPlugin\TestimonialPro\Admin\Views\Framework\Classes\SPFTESTIMONIAL;

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

/**
 * Sanitize function for text field.
 */
if ( ! function_exists( 'sp_tpro_sanitize_text' ) ) {
	/**
	 * Sp_tpro_sanitize_text
	 *
	 * @param  mixed $value value.
	 * @return statement
	 */
	function sp_tpro_sanitize_text( $value ) {

		$safe_text = filter_var( $value, FILTER_SANITIZE_STRING );
		return $safe_text;

	}
}

//
// Metabox of the testimonial shortcode generator.
// Set a unique slug-like ID.
//
$prefix_shortcode_opts = 'sp_tpro_shortcode_options';
$tpro_edit_tax_link    = admin_url( 'edit-tags.php?taxonomy=testimonial_cat&post_type=spt_testimonial' );

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SPFTESTIMONIAL::createMetabox(
	'sp_tpro_live_preview',
	array(
		'title'             => __( 'Live Preview', 'testimonial-pro' ),
		'post_type'         => 'spt_shortcodes',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
		'context'           => 'normal',
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Testimonial metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_shortcode_opts,
	array(
		'title'           => __( 'Shortcode Options', 'testimonial-pro' ),
		'post_type'       => 'spt_shortcodes',
		'context'         => 'normal',
		'enqueue_webfont' => false,
		'nav'             => 'inline',
	)
);

//
// General Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'General Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-cog',
		'fields' => array(

			array(
				'id'       => 'layout',
				'type'     => 'image_select',
				'title'    => __( 'Layout Preset', 'testimonial-pro' ),
				'subtitle' => __( 'Select a layout to display the testimonials.', 'testimonial-pro' ),
				'class'    => 'tpro-layout-preset',
				'options'  => array(
					'slider'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layout/slider.svg',
						'name'  => __( 'Slider', 'testimonial-pro' ),
					),
					'grid'    => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layout/grid.svg',
						'name'  => __( 'Grid', 'testimonial-pro' ),
					),
					'masonry' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layout/masonry.svg',
						'name'  => __( 'Masonry', 'testimonial-pro' ),
					),
					'list'    => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layout/list.svg',
						'name'  => __( 'List', 'testimonial-pro' ),
					),
					'filter'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layout/filter.svg',
						'name'  => __( 'Isotope', 'testimonial-pro' ),
					),
				),
				'default'  => 'slider',
			),
			array(
				'id'         => 'thumbnail_slider',
				'class'      => 'thumbnail_slider',
				'type'       => 'switcher',
				'title'      => __( 'Enable Thumbnail Slider', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable thumbnails testimonial slider.', 'testimonial-pro' ),
				'help'       => __( 'Thumbnail Slider need to set multi column value to show multiple image.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
				'dependency' => array(
					'layout',
					'==',
					'slider',
					true,
				),
			),
			array(
				'id'         => 'filter_style',
				'class'      => 'filter_style',
				'type'       => 'image_select',
				'title'      => __( 'Isotope Mode', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a mode for isotope.', 'testimonial-pro' ),
				'options'    => array(
					'even'    => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layout/filter-even.svg',
						'name'  => __( 'Even', 'testimonial-pro' ),
					),
					'masonry' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layout/filter-masonry.svg',
						'name'  => __( 'Masonry', 'testimonial-pro' ),
					),
				),
				'default'    => 'even',
				'dependency' => array(
					'layout',
					'==',
					'filter',
					true,
				),
			),


			array(
				'id'       => 'display_testimonials_from',
				'class'    => 'display_testimonials_from',
				'type'     => 'select',
				'title'    => __( 'Filter Testimonials', 'testimonial-pro' ),
				'subtitle' => __( 'Select an option to display the testimonials.', 'testimonial-pro' ),
				'desc'     => __( '<a href="' . $tpro_edit_tax_link . '">Manage Groups</a>', 'testimonial-pro' ),
				'options'  => array(
					'latest'                => __( 'Latest', 'testimonial-pro' ),
					'category'              => __( 'Groups', 'testimonial-pro' ),
					'specific_testimonials' => __( 'Specific', 'testimonial-pro' ),
				),
				'default'  => 'latest',
			),
			array(
				'id'          => 'category_list',
				'type'        => 'select',
				'title'       => __( 'Select Group', 'testimonial-pro' ),
				'subtitle'    => __( 'Choose the group(s) to show the testimonial from.', 'testimonial-pro' ),
				'class'       => 'tpro-group-list',
				'options'     => 'categories',
				'query_args'  => array(
					'type'       => 'spt_testimonial',
					'taxonomy'   => 'testimonial_cat',
					'hide_empty' => 0,
				),
				'sortable'    => true,
				'chosen'      => true,
				'multiple'    => true,
				'placeholder' => __( 'Choose Group(s)', 'testimonial-pro' ),
				'dependency'  => array(
					'display_testimonials_from',
					'==',
					'category',
					true,
				),

			),
			array(
				'id'         => 'category_operator',
				'type'       => 'select',
				'title'      => __( 'Group Relation Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a relation type for group.', 'testimonial-pro' ),
				'class'      => 'tpro-group-operator',
				'options'    => array(
					'IN'     => __( 'IN', 'testimonial-pro' ),
					'AND'    => __( 'AND', 'testimonial-pro' ),
					'NOT IN' => __( 'NOT IN', 'testimonial-pro' ),
				),
				'help'       => __( 'IN - Show testimonials which associate with one or more terms<br>AND - Show testimonials which match all terms<br/>NOT IN - Show testimonials which don\'t match the terms', 'testimonial-pro' ),
				'default'    => 'IN',
				'dependency' => array( 'display_testimonials_from', '==', 'category', true ),
			),
			array(
				'id'          => 'specific_testimonial',
				'type'        => 'select',
				'title'       => __( 'Specific Testimonial(s)', 'testimonial-pro' ),
				'subtitle'    => __( 'Choose the specific testimonial(s) to display.', 'testimonial-pro' ),
				'class'       => 'tpro-specific-testimonial',
				'options'     => 'posts',
				'query_args'  => array(
					'post_type'      => 'spt_testimonial',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				),
				'sortable'    => true,
				'chosen'      => true,
				'multiple'    => true,
				'placeholder' => __( 'Choose Testimonial(s)', 'testimonial-pro' ),
				'dependency'  => array( 'display_testimonials_from', '==', 'specific_testimonials', true ),
			),
			array(
				'id'          => 'exclude_testimonial',
				'type'        => 'select',
				'title'       => __( 'Exclude Testimonial(s)', 'testimonial-pro' ),
				'subtitle'    => __( 'Exclude the testimonial(s).', 'testimonial-pro' ),
				'class'       => 'tpro-exclude-testimonial',
				'options'     => 'posts',
				'query_args'  => array(
					'post_type'      => 'spt_testimonial',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				),
				'chosen'      => true,
				'multiple'    => true,
				'placeholder' => __( 'Exclude Testimonial(s)', 'testimonial-pro' ),
				'dependency'  => array( 'display_testimonials_from', 'any', 'latest,category' ),
			),
			array(
				'id'       => 'number_of_total_testimonials',
				'type'     => 'spinner',
				'title'    => __( 'Limit', 'testimonial-pro' ),
				'subtitle' => __( 'Limit number of testimonials to show. To show all testimonials, leave it empty', 'testimonial-pro' ),
				'default'  => '',
				'min'      => -1,
			),
			array(
				'id'         => 'columns',
				'type'       => 'column',
				'title'      => __( 'Responsive Column(s)', 'testimonial-pro' ),
				'subtitle'   => __( 'Set number of column(s) in different devices for responsive view.', 'testimonial-pro' ),
				'default'    => array(
					'large_desktop' => '1',
					'desktop'       => '1',
					'laptop'        => '1',
					'tablet'        => '1',
					'mobile'        => '1',
				),
				'dependency' => array( 'layout', 'any', 'slider,filter,masonry,grid', true ),
			),
			array(
				'id'         => 'random_order',
				'type'       => 'switcher',
				'title'      => __( 'Random Order', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable to display testimonials in random order.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
			),
			array(
				'id'         => 'testimonial_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'testimonial-pro' ),
				'subtitle'   => __( 'Select an order by option.', 'testimonial-pro' ),
				'options'    => array(
					'ID'         => __( 'Testimonial ID', 'testimonial-pro' ),
					'date'       => __( 'Date', 'testimonial-pro' ),
					'title'      => __( 'Title', 'testimonial-pro' ),
					'modified'   => __( 'Modified', 'testimonial-pro' ),
					'menu_order' => __( 'Drag & Drop', 'testimonial-pro' ),
				),
				'default'    => 'menu_order',
				'dependency' => array( 'random_order', '==', 'false' ),
			),
			array(
				'id'         => 'testimonial_order',
				'type'       => 'select',
				'title'      => __( 'Order Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Select an order option.', 'testimonial-pro' ),
				'options'    => array(
					'ASC'  => __( 'Ascending', 'testimonial-pro' ),
					'DESC' => __( 'Descending', 'testimonial-pro' ),
				),
				'default'    => 'DESC',
				'dependency' => array( 'random_order', '==', 'false' ),
			),
			array(
				'id'         => 'ajax_live_filter',
				'type'       => 'switcher',
				'title'      => __( 'Ajax Live Filter', 'testimonial-pro' ),
				'class'      => 'testimonial-live-filter',
				'subtitle'   => __( 'Enable/Disable ajax live filtering for testimonial groups.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
				'dependency' => array( 'thumbnail_slider|layout', '!=|!=', 'true|filter', true ),
			),
			array(
				'id'         => 'ajax_search',
				'type'       => 'switcher',
				'title'      => __( 'Ajax Testimonial Search', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable ajax search for testimonial.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
				'dependency' => array( 'thumbnail_slider', '!=', 'true', true ),
			),
			array(
				'id'         => 'testimonial_search_text',
				'type'       => 'text',
				'title'      => __( 'Search Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Change search label. Leave it empty to hide label.', 'testimonial-pro' ),
				'default'    => '',
				'dependency' => array( 'ajax_search|thumbnail_slider', '==|!=', 'true|true', true ),
			),
			array(
				'id'         => 'tpro_schema_markup',
				'type'       => 'switcher',
				'title'      => __( 'Schema Markup', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable schema markup.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
			),
			array(
				'id'         => 'tpro_global_item_name',
				'type'       => 'text',
				'title'      => __( 'Item Global Name', 'testimonial-pro' ),
				'subtitle'   => __( 'Type item global name', 'testimonial-pro' ),
				'class'      => 'tpro-item-global-name',
				'help'       => __( 'The item (company or product/service) name that is being reviewed or rated (not visible on your website, used for search engines). If nothing is set on the individual testimonial, this will be used as the item reviewed value for the testimonial. Let them know what your Testimonials are all about!', 'testimonial-pro' ),
				'dependency' => array( 'tpro_schema_markup', '==', 'true' ),
				'sanitize'   => 'sp_tpro_sanitize_text',
			),
			array(
				'id'         => 'preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable preloader.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
			),
		),
	)
);

SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Theme Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-magic',
		'fields' => array(
			array(
				'id'         => 'theme_style',
				'class'      => 'theme_style',
				'type'       => 'image_select',
				'title'      => __( 'Select Your Theme', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a theme which you want to display. <b>Please note:</b> To get perfect view for some themes, you need to customize few settings below.', 'testimonial-pro' ),
				'options'    => array(
					'theme-one'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/1.svg',
						'name'  => __( 'Theme One', 'testimonial-pro' ),
					),
					'theme-two'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/2.svg',
						'name'  => __( 'Theme Two', 'testimonial-pro' ),
					),
					'theme-three' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/3.svg',
						'name'  => __( 'Theme Three', 'testimonial-pro' ),
					),
					'theme-four'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/4.svg',
						'name'  => __( 'Theme Four', 'testimonial-pro' ),
					),
					'theme-five'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/5.svg',
						'name'  => __( 'Theme Five', 'testimonial-pro' ),
					),
					'theme-six'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/6.svg',
						'name'  => __( 'Theme Six', 'testimonial-pro' ),
					),
					'theme-seven' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/7.svg',
						'name'  => __( 'Theme Seven', 'testimonial-pro' ),
					),
					'theme-eight' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/8.svg',
						'name'  => __( 'Theme Eight', 'testimonial-pro' ),
					),
					'theme-nine'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/9.svg',
						'name'  => __( 'Theme Nine', 'testimonial-pro' ),
					),
					'theme-ten'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/10.svg',
						'name'  => __( 'Theme Ten', 'testimonial-pro' ),
					),
				),
				'default'    => 'theme-one',
				'dependency' => array( 'thumbnail_slider', '!=', 'true', true ),
			),
			array(
				'id'         => 'theme_style_thumbnail',
				'class'      => 'theme_style',
				'type'       => 'image_select',
				'title'      => __( 'Select Your Theme', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a theme which you want to display. <b>Please note:</b> To get perfect view for some themes, you need to customize few settings below.', 'testimonial-pro' ),
				'options'    => array(
					'theme-one' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/1.svg',
						'name'  => __( 'Theme One', 'testimonial-pro' ),
					),
				),
				'default'    => 'theme-one',
				'dependency' => array( 'thumbnail_slider', '==', 'true', true ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Customize Theme', 'testimonial-pro' ),
			),
			array(
				'id'                => 'testimonial_margin',
				'class'             => 'testimonial-slide-margin',
				'type'              => 'spacing',
				'title'             => __( 'Space Between Testimonials', 'testimonial-pro' ),
				'subtitle'          => __( 'Set margin between the testimonials.', 'testimonial-pro' ),
				'right'             => true,
				'top'               => true,
				'left'              => false,
				'bottom'            => false,
				'top_placeholder'   => __( 'gap', 'testimonial-pro' ),
				'right_placeholder' => __( 'gap', 'testimonial-pro' ),
				'right_text'        => __( 'Vertical Gap', 'testimonial-pro' ),
				'top_text'          => __( 'Gap', 'testimonial-pro' ),
				'right_icon'        => '<i class="fa fa-arrows-v"></i>',
				'top_icon'          => '<i class="fa fa-arrows-h"></i>',
				'unit'              => true,
				'units'             => array( 'px', '%' ),
				'default'           => array(
					'top'   => '20',
					'right' => '20',
				),
				'dependency'        => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-one,theme-two,theme-three,theme-four,theme-five,theme-six,theme-seven,theme-eight,theme-nine,theme-ten|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border_for_one',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '0',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '0',
				),
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'==|!=',
					'theme-one|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border_for_thumbnail',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '0',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '0',
				),
				'dependency' => array(
					'thumbnail_slider',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_inner_padding_for_thumbnail',
				'type'       => 'spacing',
				'title'      => __( 'Inner Padding', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial inner padding.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'thumbnail_slider',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_for_thumbnail',
				'type'       => 'color',
				'title'      => __( 'Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => 'transparent',
				'dependency' => array(
					'thumbnail_slider',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_inner_padding_for_one',
				'type'       => 'spacing',
				'title'      => __( 'Inner Padding', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial inner padding.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'==|!=',
					'theme-one|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_for_one',
				'type'       => 'color',
				'title'      => __( 'Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => 'transparent',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'==|!=',
					'theme-one|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '1',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '0',
				),
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-two,theme-three,theme-four,theme-five,theme-six,theme-seven,theme-eight,theme-nine|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border_theme_ten',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '1',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '10',
				),
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'==|!=',
					'theme-ten|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_box_shadow_property',
				'type'       => 'box_shadow',
				'title'      => __( 'Box-Shadow Values', 'testimonial-pro' ),
				'subtitle'   => __( 'Set box-shadow property values for the item.', 'testimonial-pro' ),
				'default'    => array(
					'horizontal' => '0',
					'vertical'   => '0',
					'blur'       => '10',
					'spread'     => '0',
					'color'      => '#dddddd',
				),
				'dependency' => array(
					'theme_style',
					'any',
					'theme-ten',
					true,
				),
			),
			array(
				'id'         => 'testimonial_top_bg',
				'type'       => 'color',
				'title'      => __( 'Top Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set top background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#ff8a00',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-ten|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg',
				'type'       => 'color',
				'title'      => __( 'Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#ffffff',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-two,theme-three,theme-five,theme-six,theme-ten|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_two',
				'type'       => 'color',
				'title'      => __( 'Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#f5f5f5',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-four|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_three',
				'type'       => 'color',
				'title'      => __( 'Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#e57373',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-seven,theme-eight,theme-nine|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_arrow_position_seven',
				'type'       => 'spinner',
				'title'      => __( 'Arrow Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Set arrow position to move from left to right.', 'testimonial-pro' ),
				'default'    => '30',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'==|!=',
					'theme-seven|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_arrow_position_nine',
				'type'       => 'spinner',
				'title'      => __( 'Arrow Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Set arrow position to move from left to right.', 'testimonial-pro' ),
				'default'    => '70',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'==|!=',
					'theme-nine|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_inner_padding',
				'type'       => 'spacing',
				'title'      => __( 'Inner Padding', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial inner padding.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '22',
					'right'  => '22',
					'bottom' => '22',
					'left'   => '22',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-two,theme-three,theme-four,theme-five,theme-six,theme-seven,theme-eight,theme-nine,theme-ten|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_position',
				'type'       => 'button_set',
				'title'      => __( 'Info Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select testimonial info position.', 'testimonial-pro' ),
				'options'    => array(
					'top'    => __( 'Top', 'testimonial-pro' ),
					'bottom' => __( 'Bottom', 'testimonial-pro' ),
					'left'   => __( 'Left', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
				),
				'default'    => 'bottom',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-eight|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_position_two',
				'type'       => 'button_set',
				'title'      => __( 'Info Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select testimonial info position.', 'testimonial-pro' ),
				'options'    => array(
					'top_left'     => __( 'Top Left', 'testimonial-pro' ),
					'top_right'    => __( 'Top Right', 'testimonial-pro' ),
					'bottom_left'  => __( 'Bottom Left', 'testimonial-pro' ),
					'bottom_right' => __( 'Bottom Right', 'testimonial-pro' ),
				),
				'default'    => 'bottom_left',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-nine|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_border',
				'type'       => 'border',
				'title'      => __( 'Info Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial info border.', 'testimonial-pro' ),
				'all'        => true,
				'default'    => array(
					'all'   => '0',
					'style' => 'solid',
					'color' => '#e3e3e3',
				),
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-eight,theme-nine|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_bg',
				'type'       => 'color',
				'title'      => __( 'Testimonial Info Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial information.', 'testimonial-pro' ),
				'default'    => '#f1e9e0',
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-eight,theme-nine|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_inner_padding',
				'type'       => 'spacing',
				'title'      => __( 'Testimonial Info Inner Padding', 'testimonial-pro' ),
				'subtitle'   => __( 'Set inner padding for testimonial information.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '22',
					'right'  => '22',
					'bottom' => '22',
					'left'   => '22',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'theme_style|thumbnail_slider',
					'any|!=',
					'theme-eight,theme-nine|true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_same_height',
				'type'       => 'checkbox',
				'title'      => __( 'Equalize Testimonial Height', 'testimonial-pro' ),
				'subtitle'   => __( 'Check to make all testimonial height equal.', 'testimonial-pro' ),
				'default'    => false,
				'dependency' => array( 'layout', 'any', 'slider,grid', true ),
			),
		),
	)
);

//
// Display Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Display Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-th-large',
		'fields' => array(

			array(
				'id'         => 'section_title',
				'type'       => 'switcher',
				'title'      => __( 'Section Title', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide testimonial section title (shortcode name).', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'id'         => 'average_rating_top',
				'type'       => 'switcher',
				'title'      => __( 'Average Rating', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide average rating.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'id'         => 'average_rating_margin',
				'type'       => 'spacing',
				'title'      => __( 'Average Rating Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set margin for average Rating.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '25',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'average_rating_top',
					'==',
					'true',
					true,
				),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Testimonial Content', 'testimonial-pro' ),
			),
			array(
				'id'         => 'testimonial_title',
				'type'       => 'switcher',
				'title'      => __( 'Testimonial Title', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide testimonial tagline or title.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'testimonial_title_limit',
				'type'       => 'spinner',
				'title'      => __( 'Length', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial title characters length.', 'testimonial-pro' ),
				'unit'       => __( 'chars', 'testimonial-pro' ),
				'dependency' => array(
					'testimonial_title',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_title_tag',
				'type'       => 'select',
				'title'      => __( 'HTML Tag', 'testimonial-pro' ),
				'subtitle'   => __( 'Select testimonial title HTML tag.', 'testimonial-pro' ),
				'options'    => array(
					'h1'   => 'h1',
					'h2'   => 'h2',
					'h3'   => 'h3',
					'h4'   => 'h4',
					'h5'   => 'h5',
					'h6'   => 'h6',
					'p'    => 'p',
					'span' => 'span',
					'div'  => 'div',
				),
				'default'    => 'h3',
				'dependency' => array(
					'testimonial_title',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_text',
				'type'       => 'switcher',
				'title'      => __( 'Testimonial Content', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide testimonial content.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'testimonial_content_type',
				'type'       => 'radio',
				'title'      => __( 'Content Display Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose content display type.', 'testimonial-pro' ),
				'options'    => array(
					'full_content'       => __( 'Full Content', 'testimonial-pro' ),
					'content_with_limit' => __( 'Content with Limit', 'testimonial-pro' ),
				),
				'default'    => 'content_with_limit',
				'dependency' => array(
					'testimonial_text',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_content_length_type',
				'type'       => 'button_set',
				'title'      => __( 'Content Length Type', 'testimonial-pro' ),
				'options'    => array(
					'characters' => __( 'Characters', 'testimonial-pro' ),
					'words'      => __( 'Words', 'testimonial-pro' ),
				),
				'default'    => 'characters',
				'dependency' => array(
					'testimonial_text|testimonial_content_type',
					'==|==',
					'true|content_with_limit',
					true,
				),
			),
			array(
				'id'         => 'testimonial_characters_limit',
				'type'       => 'spinner',
				'title'      => __( 'Length', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial characters length.', 'testimonial-pro' ),
				'unit'       => __( 'chars', 'testimonial-pro' ),
				'default'    => '300',
				'dependency' => array(
					'testimonial_text|testimonial_content_type|testimonial_content_length_type',
					'==|==|==',
					'true|content_with_limit|characters',
					true,
				),
			),
			array(
				'id'         => 'testimonial_word_limit',
				'type'       => 'spinner',
				'title'      => __( 'Length', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial words length.', 'testimonial-pro' ),
				'unit'       => __( 'word', 'testimonial-pro' ),
				'default'    => '300',
				'dependency' => array(
					'testimonial_text|testimonial_content_type|testimonial_content_length_type',
					'==|==|==',
					'true|content_with_limit|words',
					true,
				),
			),
			array(
				'id'         => 'testimonial_strip_tags',
				'type'       => 'radio',
				'title'      => __( 'HTML Tags', 'testimonial-pro' ),
				'options'    => array(
					'strip_all' => __( 'Strip', 'testimonial-pro' ),
					'allow_all' => __( 'Allow', 'testimonial-pro' ),
				),
				'default'    => 'strip_all',
				'dependency' => array(
					'testimonial_text|testimonial_content_type',
					'==|==',
					'true|content_with_limit',
					true,
				),
			),
			array(
				'id'         => 'testimonial_read_more_ellipsis',
				'type'       => 'text',
				'title'      => __( 'Ellipsis', 'testimonial-pro' ),
				'subtitle'   => __( 'Type ellipsis.', 'testimonial-pro' ),
				'default'    => '...',
				'dependency' => array(
					'testimonial_text|testimonial_content_type',
					'==|==',
					'true|content_with_limit',
					true,
				),
				'sanitize'   => 'sp_tpro_sanitize_text',
			),
			array(
				'id'         => 'testimonial_read_more',
				'type'       => 'switcher',
				'title'      => __( 'Read More', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide testimonial read more button.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'id'         => 'testimonial_read_more_link_action',
				'type'       => 'button_set',
				'title'      => __( 'Read More Action Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Select read more link action type.', 'testimonial-pro' ),
				'options'    => array(
					'expand' => __( 'Expand', 'testimonial-pro' ),
					'popup'  => __( 'Popup', 'testimonial-pro' ),
				),
				'default'    => 'expand',
				'dependency' => array(
					'testimonial_text|testimonial_read_more|testimonial_content_type',
					'==|==|==',
					'true|true|content_with_limit',
					true,
				),
			),
			array(
				'id'         => 'popup_background',
				'type'       => 'color',
				'title'      => __( 'Popup Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set popup background color.', 'testimonial-pro' ),
				'default'    => '#ffffff',
				'dependency' => array(
					'testimonial_read_more|testimonial_read_more_link_action',
					'==|==',
					'true|popup',
					true,
				),
			),
			array(
				'id'         => 'testimonial_read_more_text',
				'type'       => 'text',
				'title'      => __( 'Read More Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Type read more label.', 'testimonial-pro' ),
				'default'    => 'Read More',
				'dependency' => array(
					'testimonial_read_more',
					'==',
					'true',
					true,
				),
				'sanitize'   => 'sp_tpro_sanitize_text',
			),
			array(
				'id'         => 'testimonial_read_less_text',
				'type'       => 'text',
				'title'      => __( 'Read Less Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Type read less label.', 'testimonial-pro' ),
				'default'    => 'Read Less',
				'dependency' => array(
					'testimonial_text|testimonial_read_more|testimonial_read_more_link_action|testimonial_content_type',
					'==|==|==|==',
					'true|true|expand|content_with_limit',
					true,
				),
				'sanitize'   => 'sp_tpro_sanitize_text',
			),
			array(
				'id'         => 'testimonial_readmore_color',
				'type'       => 'color_group',
				'title'      => __( 'Read More Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set read more color.', 'testimonial-pro' ),
				'options'    => array(
					'color'       => __( 'Color', 'testimonial-pro' ),
					'hover-color' => __( 'Hover Color', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'       => '#1595CE',
					'hover-color' => '#2684a6',
				),
				'dependency' => array(
					'testimonial_read_more',
					'==',
					'true',
					true,
				),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Reviewer Information', 'testimonial-pro' ),
			),
			array(
				'id'         => 'testimonial_client_name',
				'type'       => 'switcher',
				'title'      => __( 'Full Name', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide reviewer full name.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'testimonial_client_name_tag',
				'type'       => 'select',
				'title'      => __( 'HTML Tag', 'testimonial-pro' ),
				'subtitle'   => __( 'Select reviewer full name HTML tag.', 'testimonial-pro' ),
				'options'    => array(
					'h1'   => 'h1',
					'h2'   => 'h2',
					'h3'   => 'h3',
					'h4'   => 'h4',
					'h5'   => 'h5',
					'h6'   => 'h6',
					'p'    => 'p',
					'span' => 'span',
					'div'  => 'div',
				),
				'default'    => 'h4',
				'dependency' => array(
					'testimonial_client_name',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_client_rating',
				'type'       => 'switcher',
				'title'      => __( 'Rating', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide rating.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'tpro_star_icon',
				'type'       => 'icon_select',
				'title'      => __( 'Rating Icon', 'woo-quick-view-pro' ),
				'subtitle'   => __( 'Choose a rating icon.', 'woo-quick-view-pro' ),
				'options'    => array(
					'fa fa-star'      => 'fa fa-star',
					'fa fa-heart'     => 'fa fa-heart',
					'fa fa-thumbs-up' => 'fa fa-thumbs-up',
					'fa fa-hourglass' => 'fa fa-hourglass',
					'fa fa-circle'    => 'fa fa-circle',
					'fa fa-square'    => 'fa fa-square',
					'fa fa-flag'      => 'fa fa-flag',
					'fa fa-smile-o'   => 'fa fa-smile-o',
				),
				'default'    => 'fa fa-star',
				'dependency' => array( 'testimonial_client_rating', '==', 'true', true ),
			),
			array(
				'id'         => 'testimonial_client_rating_color',
				'type'       => 'color',
				'title'      => __( 'Rating Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set color for rating.', 'testimonial-pro' ),
				'default'    => '#ffb900',
				'dependency' => array( 'testimonial_client_rating', '==', 'true', true ),
			),
			array(
				'id'         => 'testimonial_client_rating_alignment',
				'type'       => 'button_set',
				'title'      => __( 'Rating Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Set alignment of rating.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => __( 'Left', 'testimonial-pro' ),
					'center' => __( 'Center', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
				),
				'default'    => 'center',
				'dependency' => array(
					'testimonial_client_rating',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'testimonial_client_rating_margin',
				'type'       => 'spacing',
				'title'      => __( 'Rating Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set margin for rating.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '6',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'testimonial_client_rating',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'client_designation',
				'type'       => 'switcher',
				'title'      => __( 'Identity or Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide identity or position.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'client_company_name',
				'type'       => 'switcher',
				'title'      => __( 'Company Name', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide company name.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'client_company_logo',
				'type'       => 'switcher',
				'title'      => __( 'Company Logo', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide company Logo.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'id'         => 'company_image_sizes',
				'type'       => 'image_sizes',
				'title'      => __( 'Company Logo Size', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a size for the company logo.', 'testimonial-pro' ),
				'default'    => 'full',
				'dependency' => array(
					'client_company_logo',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'company_image_custom_size',
				'type'       => 'custom_size',
				'title'      => __( 'Custom Size', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a custom width and height of the image.', 'testimonial-pro' ),
				'default'    => array(
					'width'  => '120',
					'height' => '120',
					'crop'   => 'hard-crop',
					'unit'   => 'px',
				),
				'attributes' => array(
					'min' => 0,
				),
				'dependency' => array(
					'client_company_logo|company_image_sizes',
					'==|==',
					'true|custom',
					true,
				),
			),
			array(
				'id'         => 'testimonial_client_location',
				'type'       => 'switcher',
				'title'      => __( 'Location', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide Reviewer location.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'testimonial_client_phone',
				'type'       => 'switcher',
				'title'      => __( 'Phone or Mobile', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide phone or mobile number.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'testimonial_client_email',
				'type'       => 'switcher',
				'title'      => __( 'E-mail Address', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide e-mail address.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'testimonial_client_date',
				'type'       => 'switcher',
				'title'      => __( 'Date', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide testimonial date.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'testimonial_client_date_format',
				'type'       => 'text',
				'title'      => __( 'Date Format', 'testimonial-pro' ),
				'subtitle'   => __( 'Set date format.', 'testimonial-pro' ),
				'default'    => 'M j, Y',
				'after'      => '<br><a target="_blank" href="https://wordpress.org/support/article/formatting-date-and-time/">Documentation on date formatting.</a>',
				'dependency' => array(
					'testimonial_client_date',
					'==',
					'true',
					true,
				),
				'sanitize'   => 'sp_tpro_sanitize_text',
			),
			array(
				'id'         => 'testimonial_client_website',
				'type'       => 'switcher',
				'title'      => __( 'Website', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide website.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'       => 'identity_linking_website',
				'type'     => 'checkbox',
				'title'    => __( 'Identity & Company linking via Website URL', 'testimonial-pro' ),
				'subtitle' => __( 'Check to link identity & company name via website URL.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'       => 'website_link_target',
				'type'     => 'select',
				'title'    => __( 'Link Target', 'testimonial-pro' ),
				'subtitle' => __( 'Set a target to open the website URL.', 'testimonial-pro' ),
				'options'  => array(
					'_blank' => __( 'New Tab', 'testimonial-pro' ),
					'_self'  => __( 'Same Tab', 'testimonial-pro' ),
				),
				'default'  => '_blank',
			),
			array(
				'id'         => 'testimonial_client_addition_info',
				'type'       => 'switcher',
				'title'      => __( 'Additional Custom Fields', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide additional custom fields.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Social Media', 'testimonial-pro' ),
			),
			array(
				'id'         => 'social_profile',
				'type'       => 'switcher',
				'title'      => __( 'Social Profiles', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide social profiles.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'id'         => 'social_profile_position',
				'type'       => 'button_set',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Social profiles alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => __( 'Left', 'testimonial-pro' ),
					'center' => __( 'Center', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
				),
				'default'    => 'center',
				'dependency' => array(
					'social_profile',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'social_profile_margin',
				'type'       => 'spacing',
				'title'      => __( 'Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set margin for social profiles.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '15',
					'right'  => '0',
					'bottom' => '6',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'social_profile',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'social_icon_custom_color',
				'type'       => 'checkbox',
				'title'      => __( 'Custom Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set social icon custom color.', 'testimonial-pro' ),
				'default'    => false,
				'dependency' => array(
					'social_profile',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'social_icon_color',
				'type'       => 'color_group',
				'title'      => __( 'Icon Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set social icon color.', 'testimonial-pro' ),
				'options'    => array(
					'color'            => __( 'Color', 'testimonial-pro' ),
					'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
					'background'       => __( 'Background', 'testimonial-pro' ),
					'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'            => '#aaaaaa',
					'hover-color'      => '#ffffff',
					'background'       => 'transparent',
					'hover-background' => '#1595CE',
				),
				'dependency' => array(
					'social_profile|social_icon_custom_color',
					'==|==',
					'true|true',
					true,
				),
			),
			array(
				'id'          => 'social_icon_border',
				'type'        => 'border',
				'title'       => __( 'Icon Border', 'testimonial-pro' ),
				'subtitle'    => __( 'Set social icon border.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '1',
					'style'       => 'solid',
					'color'       => '#dddddd',
					'hover-color' => '#1595CE',
				),
				'dependency'  => array(
					'social_profile|social_icon_custom_color',
					'==|==',
					'true|true',
					true,
				),
			),
			array(
				'id'         => 'social_icon_border_radius',
				'type'       => 'spacing',
				'title'      => __( 'Icon Border Radius', 'testimonial-pro' ),
				'subtitle'   => __( 'Set social icon border radius.', 'testimonial-pro' ),
				'all'        => true,
				'units'      => array(
					'px',
					'%',
				),
				'default'    => array(
					'all'  => '50',
					'unit' => '%',
				),
				'dependency' => array(
					'social_profile',
					'==',
					'true',
					true,
				),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'Pagination', 'testimonial-pro' ),
				'dependency' => array(
					'layout',
					'any',
					'grid,masonry,list,filter',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination',
				'type'       => 'switcher',
				'title'      => __( 'Pagination', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable pagination.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
				'dependency' => array(
					'layout',
					'==',
					'filter',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a pagination type.', 'testimonial-pro' ),
				'options'    => array(
					'ajax_load_more'  => __( 'Load More Button (Ajax)', 'testimonial-pro' ),
					'infinite_scroll' => __( 'Infinite Scroll (Ajax)', 'testimonial-pro' ),
				),
				'default'    => 'infinite_scroll',
				'dependency' => array(
					'layout|filter_pagination',
					'==|==',
					'filter|true',
					true,
				),
			),
			array(
				'id'         => 'filter_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Testimonial(s) To Show Per Page', 'testimonial-pro' ),
				'subtitle'   => __( 'Set number of testimonial(s) to show per page/click.', 'testimonial-pro' ),
				'default'    => 12,
				'dependency' => array(
					'layout|filter_pagination',
					'==|==',
					'filter|true',
					true,
				),
			),
			array(
				'id'         => 'filter_load_more_label',
				'type'       => 'text',
				'title'      => __( 'Load More Button Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Change load more button label text.', 'testimonial-pro' ),
				'default'    => 'Load More',
				'dependency' => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|==|==',
					'true|filter|ajax_load_more',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination_alignment',
				'type'       => 'button_set',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Select pagination alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => __( 'Left', 'testimonial-pro' ),
					'center' => __( 'Center', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
				),
				'default'    => 'center',
				'dependency' => array(
					'filter_pagination|layout',
					'==|==',
					'true|filter',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination_margin',
				'type'       => 'spacing',
				'title'      => __( 'Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set pagination margin.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '20',
					'right'  => '0',
					'bottom' => '20',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|==|!=',
					'true|filter|infinite_scroll',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination_colors',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set color for pagination.', 'testimonial-pro' ),
				'options'    => array(
					'color'            => __( 'Color', 'testimonial-pro' ),
					'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
					'background'       => __( 'Background', 'testimonial-pro' ),
					'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'            => '#5e5e5e',
					'hover-color'      => '#ffffff',
					'background'       => '#ffffff',
					'hover-background' => '#1595CE',
				),
				'dependency' => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|any|!=',
					'true|filter|infinite_scroll',
					true,
				),
			),
			array(
				'id'          => 'filter_pagination_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'testimonial-pro' ),
				'subtitle'    => __( 'Set pagination border.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '2',
					'style'       => 'solid',
					'color'       => '#bbbbbb',
					'hover-color' => '#1595CE',
				),
				'dependency'  => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|filter|!=',
					'true|filter|infinite_scroll',
					true,
				),
			),
			array(
				'id'         => 'grid_pagination',
				'type'       => 'switcher',
				'title'      => __( 'Pagination', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable pagination.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
				'dependency' => array(
					'layout',
					'any',
					'grid,masonry,list',
					true,
				),
			),
			array(
				'id'         => 'tp_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a pagination type.', 'testimonial-pro' ),
				'options'    => array(
					'ajax_load_more'  => __( 'Load More Button (Ajax)', 'testimonial-pro' ),
					'ajax_pagination' => __( 'Ajax Number Pagination', 'testimonial-pro' ),
					'infinite_scroll' => __( 'Infinite Scroll (Ajax)', 'testimonial-pro' ),
					'no_ajax'         => __( 'No Ajax (Normal Pagination)', 'testimonial-pro' ),
				),
				'default'    => 'no_ajax',
				'dependency' => array(
					'layout|grid_pagination',
					'any|==',
					'grid,masonry,list|true',
					true,
				),
			),
			array(
				'id'         => 'tp_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Testimonial(s) To Show Per Page', 'testimonial-pro' ),
				'subtitle'   => __( 'Set number of testimonial(s) to show per page/click.', 'testimonial-pro' ),
				'default'    => 12,
				'dependency' => array(
					'layout|grid_pagination',
					'any|==',
					'grid,masonry,list|true',
					true,
				),
			),
			array(
				'id'         => 'load_more_label',
				'type'       => 'text',
				'title'      => __( 'Load More Button Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Change load more button label text.', 'testimonial-pro' ),
				'default'    => 'Load More',
				'dependency' => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|==',
					'true|grid,masonry,list|ajax_load_more',
					true,
				),
			),
			array(
				'id'         => 'grid_pagination_alignment',
				'type'       => 'button_set',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Select pagination alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => __( 'Left', 'testimonial-pro' ),
					'center' => __( 'Center', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
				),
				'default'    => 'center',
				'dependency' => array(
					'grid_pagination|layout',
					'==|any',
					'true|grid,masonry,list',
					true,
				),
			),
			array(
				'id'         => 'grid_pagination_margin',
				'type'       => 'spacing',
				'title'      => __( 'Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set pagination margin.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '20',
					'right'  => '0',
					'bottom' => '20',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|!=',
					'true|grid,masonry,list|infinite_scroll',
					true,
				),
			),
			array(
				'id'         => 'grid_pagination_colors',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set color for pagination.', 'testimonial-pro' ),
				'options'    => array(
					'color'            => __( 'Color', 'testimonial-pro' ),
					'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
					'background'       => __( 'Background', 'testimonial-pro' ),
					'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'            => '#5e5e5e',
					'hover-color'      => '#ffffff',
					'background'       => '#ffffff',
					'hover-background' => '#1595CE',
				),
				'dependency' => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|!=',
					'true|grid,masonry,list|infinite_scroll',
					true,
				),
			),
			array(
				'id'          => 'grid_pagination_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'testimonial-pro' ),
				'subtitle'    => __( 'Set pagination border.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '2',
					'style'       => 'solid',
					'color'       => '#bbbbbb',
					'hover-color' => '#1595CE',
				),
				'dependency'  => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|!=',
					'true|grid,masonry,list|infinite_scroll',
					true,
				),
			),
		),
	)
);

//
// Image Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Image Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-image',
		'fields' => array(

			array(
				'id'         => 'client_image',
				'type'       => 'switcher',
				'title'      => __( 'Testimonial/Reviewer Image', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide testimonial/reviewer image.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'client_image_position',
				'type'       => 'select',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Select image alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => __( 'Left', 'testimonial-pro' ),
					'center' => __( 'Center', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
				),
				'default'    => 'center',
				'dependency' => array(
					'client_image|theme_style|thumbnail_slider',
					'==|any|!=',
					'true|theme-one,theme-eight,theme-ten|true',
					true,
				),
			),
			array(
				'id'         => 'client_image_position_two',
				'type'       => 'select',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Select image alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => __( 'Left', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
					'top'    => __( 'Top', 'testimonial-pro' ),
					'bottom' => __( 'Bottom', 'testimonial-pro' ),
				),
				'default'    => 'left',
				'dependency' => array(
					'client_image|theme_style',
					'==|any',
					'true|theme-two,theme-four,theme-five',
					true,
				),
			),
			array(
				'id'         => 'client_image_position_three',
				'type'       => 'select',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Select image alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left-top'     => __( 'Left Top', 'testimonial-pro' ),
					'left-bottom'  => __( 'Left Bottom', 'testimonial-pro' ),
					'right-top'    => __( 'Right Top', 'testimonial-pro' ),
					'right-bottom' => __( 'Right Bottom', 'testimonial-pro' ),
					'top-left'     => __( 'Top Left', 'testimonial-pro' ),
					'top-right'    => __( 'Top Right', 'testimonial-pro' ),
					'bottom-left'  => __( 'Bottom Left', 'testimonial-pro' ),
					'bottom-right' => __( 'Bottom Right', 'testimonial-pro' ),
				),
				'default'    => 'left-top',
				'dependency' => array(
					'client_image|theme_style',
					'==|any',
					'true|theme-three,theme-six',
					true,
				),
			),
			array(
				'id'         => 'client_image_margin',
				'type'       => 'spacing',
				'title'      => __( 'Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set margin for testimonial image.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '22',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'client_image|theme_style',
					'==|any',
					'true|theme-one,theme-eight,theme-ten',
					true,
				),
			),
			array(
				'id'         => 'client_image_margin_tow',
				'type'       => 'spacing',
				'title'      => __( 'Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set margin for testimonial image.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '22',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array(
					'client_image|theme_style',
					'==|==',
					'true|theme-nine',
					true,
				),
			),
			array(
				'id'         => 'client_image_style',
				'class'      => 'client_image_style',
				'type'       => 'image_select',
				'title'      => __( 'Image Shape', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a image shape.', 'testimonial-pro' ),
				'options'    => array(
					'three' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/image-shape/circle.svg',
						'name'  => __( 'Circle', 'testimonial-pro' ),
					),
					'two'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/image-shape/rounded.svg',
						'name'  => __( 'Rounded', 'testimonial-pro' ),
					),
					'one'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/image-shape/square.svg',
						'name'  => __( 'Square', 'testimonial-pro' ),
					),
				),
				'default'    => 'three',
				'dependency' => array(
					'client_image',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'client_image_border_shadow',
				'type'       => 'radio',
				'title'      => __( 'Border or Box-Shadow', 'testimonial-pro' ),
				'subtitle'   => __( 'Select image border or box-shadow option.', 'testimonial-pro' ),
				'options'    => array(
					'border'     => __( 'Border', 'testimonial-pro' ),
					'box_shadow' => __( 'Box-Shadow', 'testimonial-pro' ),
				),
				'default'    => 'border',
				'dependency' => array( 'client_image', '==', 'true', true ),
			),
			array(
				'id'         => 'client_image_box_shadow_property',
				'type'       => 'box_shadow',
				'title'      => __( 'Box-Shadow Values', 'testimonial-pro' ),
				'subtitle'   => __( 'Set image box-shadow property values for the item.', 'testimonial-pro' ),
				'default'    => array(
					'horizontal' => '0',
					'vertical'   => '0',
					'blur'       => '7',
					'spread'     => '0',
					'color'      => '#888888',
				),
				'dependency' => array(
					'client_image|client_image_border_shadow',
					'==|==',
					'true|box_shadow',
					true,
				),
			),
			array(
				'id'         => 'image_border',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set image border.', 'testimonial-pro' ),
				'all'        => true,
				'default'    => array(
					'all'   => '0',
					'style' => 'solid',
					'color' => '#dddddd',
					'unit'  => 'px',
				),
				'dependency' => array(
					'client_image|client_image_border_shadow',
					'==|==',
					'true|border',
					true,
				),
			),
			array(
				'id'         => 'client_image_bg',
				'type'       => 'color',
				'title'      => __( 'Image Background', 'testimonial-pro' ),
				'subtitle'   => __( 'Set image background color.', 'testimonial-pro' ),
				'default'    => '#ffffff',
				'dependency' => array( 'client_image', '==', 'true', true ),
			),
			array(
				'id'         => 'image_padding',
				'type'       => 'spacing',
				'title'      => __( 'Padding', 'testimonial-pro' ),
				'subtitle'   => __( 'Set padding for testimonial image.', 'testimonial-pro' ),
				'all'        => true,
				'units'      => array( 'px' ),
				'default'    => array(
					'all'  => '0',
					'unit' => 'px',
				),
				'dependency' => array(
					'client_image',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'image_sizes',
				'type'       => 'image_sizes',
				'title'      => __( 'Testimonial/Reviewer Image Size', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a size for testimonial/reviewer image.', 'testimonial-pro' ),
				'default'    => 'custom',
				'dependency' => array(
					'client_image',
					'==',
					'true',
					true,
				),
			),
			array(
				'id'         => 'image_custom_size',
				'type'       => 'custom_size',
				'title'      => __( 'Custom Size', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a custom width and height of the image.', 'testimonial-pro' ),
				'default'    => array(
					'width'  => '120',
					'height' => '120',
					'crop'   => 'hard-crop',
					'unit'   => 'px',
				),
				'attributes' => array(
					'min' => 0,
				),
				'dependency' => array(
					'client_image|image_sizes',
					'==|==',
					'true|custom',
					true,
				),
			),
			array(
				'id'         => 'load_2x_image',
				'type'       => 'switcher',
				'title'      => __( 'Load 2x Resolution Image in Retina Display', 'testimonial-pro' ),
				'subtitle'   => __( 'You should upload 2x sized images for the retina display.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 94,
				'default'    => false,
				'dependency' => array( 'client_image|image_sizes', '==|==', 'true|custom', true ),
			),
			array(
				'id'         => 'img_lightbox',
				'type'       => 'switcher',
				'title'      => __( 'Lightbox ', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable testimonial image lightbox.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
				'dependency' => array(
					'client_image|thumbnail_slider',
					'==|!=',
					'true|true',
					true,
				),
			),
			array(
				'id'         => 'image_zoom_effect',
				'type'       => 'select',
				'title'      => __( 'Zoom', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a zoom effect on hover for the testimonial image.', 'testimonial-pro' ),
				'options'    => array(
					''         => __( 'None', 'testimonial-pro' ),
					'zoom_in'  => __( 'Zoom In', 'testimonial-pro' ),
					'zoom_out' => __( 'Zoom Out', 'testimonial-pro' ),
				),
				'default'    => '',
				'class'      => 'chosen',
				'dependency' => array(
					'client_image|thumbnail_slider',
					'==|!=',
					'true|true',
					true,
				),
			),
			array(
				'id'         => 'image_grayscale',
				'type'       => 'select',
				'title'      => __( 'Image Mode', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a image mode.', 'testimonial-pro' ),
				'options'    => array(
					'none'            => __( 'Normal', 'testimonial-pro' ),
					'normal_on_hover' => __( 'Grayscale and normal on hover', 'testimonial-pro' ),
					'on_hover'        => __( 'Grayscale on hover', 'testimonial-pro' ),
					'always'          => __( 'Always grayscale', 'testimonial-pro' ),
				),
				'default'    => 'none',
				'dependency' => array(
					'client_image|thumbnail_slider',
					'==|!=',
					'true|true',
					true,
				),
			),
			array(
				'id'         => 'video_icon',
				'type'       => 'switcher',
				'title'      => __( 'Video Testimonial', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide video testimonial.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array(
					'client_image|thumbnail_slider',
					'==|!=',
					'true|true',
					true,
				),
			),
			array(
				'id'         => 'video_play_place',
				'type'       => 'button_set',
				'title'      => __( 'Video Play on', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a video play.', 'testimonial-pro' ),
				'options'    => array(
					'default' => __( 'Default', 'testimonial-pro' ),
					'popup'   => __( 'Popup', 'testimonial-pro' ),
				),
				'default'    => 'popup',
				'dependency' => array(
					'client_image|thumbnail_slider',
					'==|!=',
					'true|true',
					true,
				),
			),
			array(
				'id'         => 'video_icon_size',
				'type'       => 'spinner',
				'title'      => __( 'Icon Size', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial icon size for video and lightbox.', 'testimonial-pro' ),
				'unit'       => __( 'px', 'testimonial-pro' ),
				'default'    => '32',
				'dependency' => array(
					'client_image|thumbnail_slider|video_play_place',
					'==|!=|==',
					'true|true|popup',
					true,
				),
			),
			array(
				'id'         => 'video_icon_color',
				'type'       => 'color_group',
				'title'      => __( 'Icon Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial icon color for video and lightbox.', 'testimonial-pro' ),
				'options'    => array(
					'color'       => __( 'Color', 'testimonial-pro' ),
					'hover-color' => __( 'Hover Color', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'       => '#e2e2e2',
					'hover-color' => '#ffffff',
				),
				'dependency' => array(
					'client_image|thumbnail_slider|video_play_place',
					'==|!=|==',
					'true|true|popup',
					true,
				),
			),
			array(
				'id'         => 'video_icon_overlay',
				'type'       => 'color',
				'title'      => __( 'Icon Overlay Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial icon overlay color for video and lightbox.', 'testimonial-pro' ),
				'default'    => 'rgba(51, 51, 51, 0.4)',
				'dependency' => array(
					'client_image|thumbnail_slider|video_play_place',
					'==|!=|==',
					'true|true|popup',
					true,
				),
			),

		),
	)
);
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Filter Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-filter',
		'fields' => array(
			array(
				'id'         => 'live_filter_type',
				'type'       => 'button_set',
				'title'      => __( 'Filter Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a filter type.', 'testimonial-pro' ),
				'options'    => array(
					'filter_button'   => __( 'Button', 'testimonial-pro' ),
					'filter_dropdown' => __( 'Drop Down', 'testimonial-pro' ),
				),
				'default'    => 'filter_button',
				'dependency' => array( 'ajax_live_filter', '==', 'true', true ),
			),
			array(
				'id'         => 'all_tab',
				'type'       => 'switcher',
				'title'      => __( '"All" Tab', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide "All" tab.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'all_tab_text',
				'type'       => 'text',
				'title'      => __( '"All" Tab Text', 'testimonial-pro' ),
				'subtitle'   => __( 'Set "All" tab text.', 'testimonial-pro' ),
				'default'    => 'All',
				'dependency' => array( 'all_tab', '==', 'true', true ),
			),
			array(
				'id'       => 'filter_alignment',
				'type'     => 'button_set',
				'title'    => __( 'Button Alignment', 'testimonial-pro' ),
				'subtitle' => __( 'Set alignment for filter button.', 'testimonial-pro' ),
				'options'  => array(
					'left'   => '<i class="fa fa-align-left" title="left"></i>',
					'center' => '<i class="fa fa-align-center" title="center"></i>',
					'right'  => '<i class="fa fa-align-right" title="right"></i>',
				),
				'default'  => 'center',
			),
			array(
				'id'       => 'filter_margin',
				'type'     => 'spacing',
				'title'    => __( 'Margin', 'testimonial-pro' ),
				'subtitle' => __( 'Set margin for filter.', 'testimonial-pro' ),
				'default'  => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '24',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'    => array( 'px' ),
			),
			array(
				'id'       => 'filter_colors',
				'type'     => 'color_group',
				'title'    => __( 'Filter Button Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for filter button.', 'testimonial-pro' ),
				'options'  => array(
					'color'             => __( 'Color', 'testimonial-pro' ),
					'active-color'      => __( 'Active Color', 'testimonial-pro' ),
					'background'        => __( 'Background', 'testimonial-pro' ),
					'active-background' => __( 'Active Background', 'testimonial-pro' ),
				),
				'default'  => array(
					'color'             => '#7e7e7e',
					'active-color'      => '#ffffff',
					'background'        => '#ffffff',
					'active-background' => '#1595CE',
				),
			),
			array(
				'id'          => 'filter_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'testimonial-pro' ),
				'subtitle'    => __( 'Set border for the filter button.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '2',
					'style'       => 'solid',
					'color'       => '#aeaeae',
					'hover-color' => '#1595CE',
				),
			),
		),
	)
);
//
// Slider Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Slider Controls', 'testimonial-pro' ),
		'icon'   => 'fa fa-sliders',
		'fields' => array(

			array(
				'id'         => 'slider_mode',
				'type'       => 'button_set',
				'title'      => __( 'Slider Mode', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a slider mode. Slider Settings are disabled in the ticker mode. ', 'testimonial-pro' ),
				'options'    => array(
					'standard' => __( 'Standard', 'testimonial-pro' ),
					'ticker'   => __( 'Ticker', 'testimonial-pro' ),
				),
				'default'    => 'standard',
				'dependency' => array( 'thumbnail_slider', '!=', 'true', true ),
			),
			array(
				'id'         => 'slider_auto_play',
				'type'       => 'button_set',
				'title'      => __( 'AutoPlay', 'testimonial-pro' ),
				'subtitle'   => __( 'On/Off auto play.', 'testimonial-pro' ),
				'options'    => array(
					'true'          => __( 'On', 'testimonial-pro' ),
					'false'         => __( 'Off', 'testimonial-pro' ),
					'off_on_mobile' => __( 'Off on Mobile', 'testimonial-pro' ),
				),
				'default'    => 'true',
				'dependency' => array( 'slider_mode', '==', 'standard', true ),
			),
			array(
				'id'         => 'slider_auto_play_speed',
				'type'       => 'spinner',
				'title'      => __( 'AutoPlay Speed', 'testimonial-pro' ),
				'subtitle'   => __( 'Set auto play speed in a millisecond. Default value 3000ms.', 'testimonial-pro' ),
				'max'        => 30000,
				'min'        => 100,
				'default'    => 3000,
				'unit'       => 'ms',
				'step'       => 100,
				'unit'       => __( 'ms', 'testimonial-pro' ),
				'dependency' => array(
					'slider_auto_play|slider_mode',
					'any|==',
					'true,off_on_mobile|standard',
					true,
				),
			),
			array(
				'id'       => 'slider_scroll_speed',
				'type'     => 'spinner',
				'title'    => __( 'Pagination Speed', 'testimonial-pro' ),
				'subtitle' => __( 'Set pagination speed in a millisecond. Default value 600ms.', 'testimonial-pro' ),
				'unit'     => __( 'ms', 'testimonial-pro' ),
				'max'      => 10000,
				'min'      => 10,
				'default'  => 600,
				'unit'     => 'ms',
				'step'     => 10,
			),
			array(
				'id'         => 'slide_to_scroll',
				'type'       => 'column',
				'title'      => __( 'Slide To Scroll', 'testimonial-pro' ),
				'subtitle'   => __( 'Number of testimonial(s) to scroll at a time.', 'testimonial-pro' ),
				'default'    => array(
					'large_desktop' => '1',
					'desktop'       => '1',
					'laptop'        => '1',
					'tablet'        => '1',
					'mobile'        => '1',
				),
				'dependency' => array( 'slider_mode|thumbnail_slider', '==|!=', 'standard|true', true ),
			),
			array(
				'id'         => 'slider_pause_on_hover',
				'type'       => 'switcher',
				'title'      => __( 'Pause on Hover', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable slider pause on hover.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
				'dependency' => array(
					'slider_auto_play|slider_mode',
					'any|==',
					'true,off_on_mobile|standard',
					true,
				),
			),
			array(
				'id'         => 'slider_infinite',
				'type'       => 'switcher',
				'title'      => __( 'Infinite Loop', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
				'dependency' => array( 'slider_mode|thumbnail_slider', '==|!=', 'standard|true', true ),
			),
			array(
				'id'         => 'slider_animation',
				'type'       => 'select',
				'title'      => __( 'Slider Animation', 'testimonial-pro' ),
				'subtitle'   => __( 'Fade effect works only on single column view.', 'testimonial-pro' ),
				'options'    => array(
					'slide' => __( 'Slide', 'testimonial-pro' ),
					'fade'  => __( 'Fade', 'testimonial-pro' ),
				),
				'default'    => 'slide',
				'dependency' => array( 'slider_mode', '==', 'standard', true ),
			),
			array(
				'id'       => 'slider_direction',
				'type'     => 'button_set',
				'title'    => __( 'Direction', 'testimonial-pro' ),
				'subtitle' => __( 'Slider direction.', 'testimonial-pro' ),
				'options'  => array(
					'ltr' => __( 'Right to Left', 'testimonial-pro' ),
					'rtl' => __( 'Left to Right', 'testimonial-pro' ),
				),
				'default'  => 'ltr',
			),
			array(
				'id'         => 'slider_row',
				'type'       => 'column',
				'title'      => __( 'Row', 'testimonial-pro' ),
				'subtitle'   => __( 'Set number of row in different devices for responsive view.', 'testimonial-pro' ),
				'default'    => array(
					'large_desktop' => '1',
					'desktop'       => '1',
					'laptop'        => '1',
					'tablet'        => '1',
					'mobile'        => '1',
				),
				'dependency' => array( 'slider_mode|thumbnail_slider', '==|!=', 'standard|true', true ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'Navigation', 'testimonial-pro' ),
				'dependency' => array( 'slider_mode', '==', 'standard' ),
			),
			array(
				'id'         => 'navigation',
				'type'       => 'button_set',
				'title'      => __( 'Navigation', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide slider navigation.', 'testimonial-pro' ),
				'options'    => array(
					'true'           => __( 'Show', 'testimonial-pro' ),
					'false'          => __( 'Hide', 'testimonial-pro' ),
					'hide_on_mobile' => __( 'Hide on Mobile', 'testimonial-pro' ),
				),
				'default'    => 'true',
				'dependency' => array( 'slider_mode', '==', 'standard', true ),
			),
			array(
				'id'         => 'navigation_position',
				'type'       => 'select',
				'title'      => __( 'Select Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a position for the navigation arrows.', 'testimonial-pro' ),
				'options'    => array(
					'top_right'                   => __( 'Top right', 'testimonial-pro' ),
					'top_center'                  => __( 'Top center', 'testimonial-pro' ),
					'top_left'                    => __( 'Top left', 'testimonial-pro' ),
					'bottom_left'                 => __( 'Bottom left', 'testimonial-pro' ),
					'bottom_center'               => __( 'Bottom center', 'testimonial-pro' ),
					'bottom_right'                => __( 'Bottom right', 'testimonial-pro' ),
					'vertical_center'             => __( 'Vertically center', 'testimonial-pro' ),
					'vertical_center_inner'       => __( 'Vertically center inner', 'testimonial-pro' ),
					'vertical_center_inner_hover' => __( 'Vertically center inner on hover', 'testimonial-pro' ),
					'vertical_center_outer_hover' => __( 'Vertically center outer on hover', 'testimonial-pro' ),
				),
				'default'    => 'vertical_center',
				'dependency' => array( 'navigation|slider_mode', 'any|==', 'true,hide_on_mobile|standard', true ),
			),
			array(
				'id'         => 'navigation_icons',
				'type'       => 'icon_select',
				'title'      => __( 'Choose an Icon', 'testimonial-pro' ),
				'subtitle'   => __( 'choose a slider navigation icon.', 'testimonial-pro' ),
				'options'    => array(
					'angle'        => 'fa fa-angle-right',
					'chevron'      => 'fa fa-chevron-right',
					'angle-double' => 'fa fa-angle-double-right',
					'arrow'        => 'fa fa-arrow-right',
					'long-arrow'   => 'fa fa-long-arrow-right',
					'caret'        => 'fa fa-caret-right',
				),
				'default'    => 'angle',
				'dependency' => array(
					'navigation|slider_mode',
					'any|==',
					'true,hide_on_mobile|standard',
					true,
				),
			),
			array(
				'id'         => 'navigation_icon_size',
				'type'       => 'spinner',
				'title'      => __( 'Navigation Icon Size', 'testimonial-pro' ),
				'subtitle'   => __( 'Change navigation icon size.', 'testimonial-pro' ),
				'default'    => '20',
				'dependency' => array(
					'navigation|slider_mode',
					'any|==',
					'true,hide_on_mobile|standard',
					true,
				),
			),
			array(
				'id'         => 'navigation_color',
				'type'       => 'color_group',
				'title'      => __( 'Navigation Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set the navigation color.', 'testimonial-pro' ),
				'options'    => array(
					'color'            => __( 'Color', 'testimonial-pro' ),
					'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
					'background'       => __( 'Background', 'testimonial-pro' ),
					'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'            => '#777777',
					'hover-color'      => '#ffffff',
					'background'       => 'transparent',
					'hover-background' => '#1595CE',
				),
				'dependency' => array(
					'navigation|slider_mode',
					'any|==',
					'true,hide_on_mobile|standard',
					true,
				),
			),
			array(
				'id'          => 'navigation_border',
				'type'        => 'border',
				'title'       => __( 'Navigation Border', 'testimonial-pro' ),
				'subtitle'    => __( 'Set the navigation border.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '1',
					'style'       => 'solid',
					'color'       => '#777777',
					'hover-color' => '#1595CE',
				),
				'dependency'  => array(
					'navigation|slider_mode',
					'any|==',
					'true,hide_on_mobile|standard',
					true,
				),
			),
			array(
				'id'         => 'navigation_border_radius',
				'type'       => 'spacing',
				'title'      => __( 'Border Radius', 'testimonial-pro' ),
				'subtitle'   => __( 'Set the navigation border radius.', 'testimonial-pro' ),
				'all'        => true,
				'units'      => array(
					'px',
					'%',
				),
				'default'    => array(
					'all'  => '50',
					'unit' => '%',
				),
				'dependency' => array(
					'navigation|slider_mode',
					'any|==',
					'true,hide_on_mobile|standard',
					true,
				),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'Pagination', 'testimonial-pro' ),
				'dependency' => array( 'slider_mode', '==', 'standard' ),
			),
			array(
				'id'         => 'pagination',
				'type'       => 'button_set',
				'title'      => __( 'Pagination', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide pagination.', 'testimonial-pro' ),
				'options'    => array(
					'true'           => __( 'Show', 'testimonial-pro' ),
					'false'          => __( 'Hide', 'testimonial-pro' ),
					'hide_on_mobile' => __( 'Hide on Mobile', 'testimonial-pro' ),
				),
				'default'    => 'true',
				'dependency' => array( 'slider_mode', '==', 'standard', true ),
			),
			array(
				'id'         => 'pagination_margin',
				'type'       => 'spacing',
				'title'      => __( 'Margin', 'testimonial-pro' ),
				'subtitle'   => __( 'Set pagination margin.', 'testimonial-pro' ),
				'default'    => array(
					'top'    => '21',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
				'units'      => array( 'px' ),
				'dependency' => array( 'pagination|slider_mode', 'any|==', 'true,hide_on_mobile|standard', true ),
			),
			array(
				'id'         => 'pagination_colors',
				'type'       => 'color_group',
				'title'      => __( 'Pagination Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set the pagination color.', 'testimonial-pro' ),
				'options'    => array(
					'color'        => __( 'Color', 'testimonial-pro' ),
					'active-color' => __( 'Active Color', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'        => '#cccccc',
					'active-color' => '#1595CE',
				),
				'dependency' => array(
					'pagination|slider_mode',
					'any|==',
					'true,hide_on_mobile|standard',
					true,
				),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'Miscellaneous', 'testimonial-pro' ),
				'dependency' => array( 'slider_mode', '==', 'standard' ),
			),
			array(
				'id'         => 'adaptive_height',
				'type'       => 'switcher',
				'title'      => __( 'Adaptive Slider Height', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable adaptive slider height. This dynamically adjusts slider height based on each slide\'s height.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
				'dependency' => array( 'slider_mode', '==', 'standard', true ),
			),
			array(
				'id'         => 'slider_swipe',
				'type'       => 'switcher',
				'title'      => __( 'Touch Swipe', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable swipe mode.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
				'dependency' => array( 'slider_mode', '==', 'standard', true ),
			),
			array(
				'id'         => 'slider_draggable',
				'type'       => 'switcher',
				'title'      => __( 'Mouse Draggable', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
				'dependency' => array( 'slider_swipe|slider_mode', '==|==', 'true|standard', true ),
			),
			array(
				'id'         => 'swipe_to_slide',
				'type'       => 'switcher',
				'title'      => __( 'Swipe To Slide', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable swipe to slide.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
				'dependency' => array( 'slider_swipe|slider_mode', '==|==', 'true|standard', true ),
			),

		),
	)
);

//
// Typography section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Typography', 'testimonial-pro' ),
		'icon'   => 'fa fa-font',
		'fields' => array(

			array(
				'id'       => 'section_title_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Section Title Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the testimonial section title.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'section_title_typography',
				'type'          => 'typography',
				'title'         => __( 'Section Title', 'testimonial-pro' ),
				'subtitle'      => __( 'Set testimonial section title font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '22',
					'line-height'    => '22',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
					'margin-bottom'  => '60',
				),
				'margin_bottom' => true,
				'preview'       => true,
				'preview_text'  => 'What Our Customers Saying', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'testimonial_title_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Testimonial Title Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the testimonial tagline or title.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'testimonial_title_typography',
				'type'          => 'typography',
				'title'         => __( 'Testimonial Title', 'testimonial-pro' ),
				'subtitle'      => __( 'Set testimonial tagline or title font properties.', 'testimonial-pro' ),
				'default'       => array(
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
				),
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview'       => true,
				'preview_text'  => 'The Testimonial Title', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'testimonial_text_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Testimonial Content Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the testimonial content.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'testimonial_text_typography',
				'type'          => 'typography',
				'title'         => __( 'Testimonial Content', 'testimonial-pro' ),
				'subtitle'      => __( 'Set testimonial content font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '16',
					'line-height'    => '26',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#333333',
					'margin-top'     => '0',
					'margin-right'   => '0',
					'margin-bottom'  => '20',
					'margin-left'    => '0',
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
			),
			array(
				'id'       => 'client_name_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Full Name Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the full name.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'client_name_typography',
				'type'          => 'typography',
				'title'         => __( 'Full Name', 'testimonial-pro' ),
				'subtitle'      => __( 'Set full name font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '16',
					'line-height'    => '24',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#333333',
					'margin-top'     => '0',
					'margin-right'   => '0',
					'margin-bottom'  => '8',
					'margin-left'    => '0',
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => 'Jacob Firebird', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'designation_company_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Identity or Position & Company Name Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the identity or position & company name.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'client_designation_company_typography',
				'type'          => 'typography',
				'title'         => __( 'Identity or Position & Company Name', 'testimonial-pro' ),
				'subtitle'      => __( 'Set identity or position & company name font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '16',
					'line-height'    => '24',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
					'margin-top'     => '0',
					'margin-right'   => '0',
					'margin-bottom'  => '8',
					'margin-left'    => '0',
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => 'CEO - Firebird Media Inc.', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'location_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Location Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the location.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'client_location_typography',
				'type'          => 'typography',
				'title'         => __( 'Location', 'testimonial-pro' ),
				'subtitle'      => __( 'Set location font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
					'margin-top'     => '0',
					'margin-right'   => '0',
					'margin-bottom'  => '5',
					'margin-left'    => '0',
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => 'Los Angeles', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'phone_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Phone or Mobile Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the phone or mobile.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'client_phone_typography',
				'type'          => 'typography',
				'title'         => __( 'Phone or Mobile', 'testimonial-pro' ),
				'subtitle'      => __( 'Set phone or mobile font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
					'margin-top'     => '0',
					'margin-right'   => '0',
					'margin-bottom'  => '3',
					'margin-left'    => '0',
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => '+1 234567890', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'email_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load E-mail Address Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the email address.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'client_email_typography',
				'type'          => 'typography',
				'title'         => __( 'E-mail Address', 'testimonial-pro' ),
				'subtitle'      => __( 'Set e-mail address font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
					'margin-top'     => '0',
					'margin-right'   => '0',
					'margin-bottom'  => '5',
					'margin-left'    => '0',
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => 'mail@yourwebsite.com', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'date_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Date Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the date.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'testimonial_date_typography',
				'type'          => 'typography',
				'title'         => __( 'Date', 'testimonial-pro' ),
				'subtitle'      => __( 'Set date font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '20',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
					'color'          => '#444444',
					'margin-top'     => '0',
					'margin-right'   => '0',
					'margin-bottom'  => '6',
					'margin-left'    => '0',
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => 'February 21, 2018', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'website_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Website Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the website.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'            => 'client_website_typography',
				'type'          => 'typography',
				'title'         => __( 'Website', 'testimonial-pro' ),
				'subtitle'      => __( 'Set website font properties.', 'testimonial-pro' ),
				'default'       => array(
					'font-family'    => '',
					'font-weight'    => '',
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
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => 'www.example.com', // Replace preview text with any text you like.
			),
			array(
				'id'         => 'extra_fields_typography_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load Additional Custom Fields Font', 'testimonial-pro' ),
				'subtitle'   => __( 'On/Off google font for the additional information button.', 'testimonial-pro' ),
				'default'    => false,
				'dependency' => array( 'testimonial_client_addition_info', '==', 'true', true ),
			),
			array(
				'id'            => 'client_extra_fields_typography',
				'type'          => 'typography',
				'title'         => __( 'Additional Custom Fields', 'testimonial-pro' ),
				'subtitle'      => __( 'Set reviewer additional information font properties.', 'testimonial-pro' ),
				'default'       => array(
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
				),
				'color'         => true,
				'preview'       => true,
				'margin_top'    => true,
				'margin_right'  => true,
				'margin_bottom' => true,
				'margin_left'   => true,
				'preview_text'  => 'This is reviewer additional custom fields', // Replace preview text with any text you like.
			),
			array(
				'id'       => 'filter_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Filter button Font', 'testimonial-pro' ),
				'subtitle' => __( 'On/Off google font for the isotope filter button.', 'testimonial-pro' ),
				'default'  => false,
			),
			array(
				'id'           => 'filter_typography',
				'type'         => 'typography',
				'title'        => __( 'Filter Button', 'testimonial-pro' ),
				'subtitle'     => __( 'Set isotope filter button font properties.', 'testimonial-pro' ),
				'default'      => array(
					'font-family'    => '',
					'font-weight'    => '',
					'type'           => 'google',
					'font-size'      => '15',
					'line-height'    => '24',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => 0,
				),
				'color'        => false,
				'preview'      => true,
				'preview_text' => 'All', // Replace preview text with any text you like.
			),
		),
	)
);

//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_elements_opts = 'sp_tpro_form_elements_options';

//
// Form metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_elements_opts,
	array(
		'title'           => __( 'Form Fields', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'side',
		'enqueue_webfont' => false,
	)
);

//
// Form Editor section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_elements_opts,
	array(
		'fields' => array(

			array(
				'id'      => 'form_elements',
				'type'    => 'checkbox',
				'options' => array(
					'name'              => __( 'Full Name', 'testimonial-pro' ),
					'email'             => __( 'E-mail Address', 'testimonial-pro' ),
					'position'          => __( 'Identity or Position', 'testimonial-pro' ),
					'company'           => __( 'Company Name', 'testimonial-pro' ),
					'testimonial_title' => __( 'Testimonial Title', 'testimonial-pro' ),
					'testimonial'       => __( 'Testimonial', 'testimonial-pro' ),
					'groups'            => __( 'Groups', 'testimonial-pro' ),
					'image'             => __( 'Image', 'testimonial-pro' ),
					'location'          => __( 'Location', 'testimonial-pro' ),
					'phone_mobile'      => __( 'Phone or Mobile', 'testimonial-pro' ),
					'website'           => __( 'Website', 'testimonial-pro' ),
					'video_url'         => __( 'Video URL', 'testimonial-pro' ),
					'rating'            => __( 'Rating', 'testimonial-pro' ),
					'social_profile'    => __( 'Social Profile', 'testimonial-pro' ),
					'agree_checkbox'    => __( 'Checkbox', 'testimonial-pro' ),
					'recaptcha'         => __( 'reCAPTCHA', 'testimonial-pro' ),
				),
				'default' => array( 'name', 'email', 'position', 'company', 'website', 'image', 'testimonial_title', 'testimonial', 'rating' ),
			),

		),
	)
);

//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_code_opts = 'sp_tpro_form_code_options';

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SPFTESTIMONIAL::createMetabox(
	'sp_tpro_form_live_preview',
	array(
		'title'             => __( 'Live Preview', 'testimonial-pro' ),
		'post_type'         => 'spt_testimonial_form',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
		'context'           => 'normal',
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_form_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Form shortcode.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_code_opts,
	array(
		'title'           => __( 'How To Use', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'side',
		'enqueue_webfont' => false,
	)
);

//
// Testimonial Form Code section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_code_opts,
	array(
		'fields' => array(

			array(
				'id'   => 'form_shortcode',
				'type' => 'shortcode',
			),

		),
	)
);

//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_opts = 'sp_tpro_form_options';

//
// Form metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_opts,
	array(
		'title'           => __( 'Form Options', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'normal',
		'enqueue_webfont' => false,
	)
);

//
// Form Editor section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Form Editor', 'testimonial-pro' ),
		'icon'   => 'fa fa-align-justify',
		'fields' => array(

			array(
				'id'     => 'form_fields',
				'class'  => 'form_fields',
				'type'   => 'sortable',
				'fields' => array(
					array(
						'id'         => 'full_name',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Full Name', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Full Name', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What is your full name?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'name', true ),
					),
					array(
						'id'         => 'email_address',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'E-mail Address', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'E-mail Address', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What is your e-mail address?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'email', true ),
					),
					array(
						'id'         => 'identity_position',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Identity or Position', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Identity or Position', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What is your identity or position?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'position', true ),
					),
					array(
						'id'         => 'company_name',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Company Name', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Company Name', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What is your company name?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'company', true ),
					),
					array(
						'id'         => 'testimonial_title',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Testimonial Title', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Testimonial Title', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'A headline for your testimonial.', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'testimonial_title', true ),
					),
					array(
						'id'         => 'testimonial',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Testimonial', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Testimonial', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What do you think about us?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'testimonial', true ),
					),
					array(
						'id'         => 'groups',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Groups', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Groups', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'Select groups name.', 'testimonial-pro' ),
									),
									array(
										'id'      => 'multiple_selection',
										'type'    => 'checkbox',
										'title'   => __( 'Multiple Group Selection?', 'testimonial-pro' ),
										'default' => false,
									),
									array(
										'id'          => 'groups_list',
										'type'        => 'select',
										'title'       => __( 'Select Group(s)', 'testimonial-pro' ),
										'desc'        => __( 'Select groups for the frontend form. Leave blank for the all groups.', 'testimonial-pro' ),
										'options'     => 'categories',
										'query_args'  => array(
											'type'       => 'spt_testimonial',
											'taxonomy'   => 'testimonial_cat',
											'hide_empty' => 0,
										),
										'placeholder' => __( 'Select Groups', 'testimonial-pro' ),
										'chosen'      => true,
										'multiple'    => true,
										'sortable'    => true,
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),

								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'groups', true ),
					),
					array(
						'id'         => 'featured_image',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Image', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Photo', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'image', true ),
					),
					array(
						'id'         => 'location',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Location', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Location', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What is your location?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'location', true ),
					),
					array(
						'id'         => 'phone_mobile',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Phone or Mobile', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Phone or Mobile', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What is your phone number?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'phone_mobile', true ),
					),
					array(
						'id'         => 'website',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Website', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Website', 'testimonial-pro' ),
									),
									array(
										'id'      => 'placeholder',
										'type'    => 'text',
										'title'   => __( 'Placeholder', 'testimonial-pro' ),
										'default' => __( 'What is your website?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'website', true ),
					),
					array(
						'id'         => 'video_url',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Video URL', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'         => 'video_by_url',
										'type'       => 'switcher',
										'title'      => __( 'Video by URL', 'testimonial-pro' ),
										'text_on'    => __( 'Show', 'testimonial-pro' ),
										'text_off'   => __( 'Hide', 'testimonial-pro' ),
										'text_width' => 80,
										'default'    => true,
									),
									array(
										'id'         => 'label',
										'type'       => 'text',
										'title'      => __( 'Label', 'testimonial-pro' ),
										'desc'       => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default'    => __( 'Video URL', 'testimonial-pro' ),
										'dependency' => array( 'video_by_url', '==', 'true' ),
									),
									array(
										'id'         => 'placeholder',
										'type'       => 'text',
										'title'      => __( 'Placeholder', 'testimonial-pro' ),
										'default'    => __( 'Type video URL.', 'testimonial-pro' ),
										'dependency' => array( 'video_by_url', '==', 'true' ),
									),
									array(
										'id'         => 'record_video',
										'type'       => 'switcher',
										'text_on'    => __( 'Show', 'testimonial-pro' ),
										'text_off'   => __( 'Hide', 'testimonial-pro' ),
										'text_width' => 80,
										'title'      => __( 'Add Record Video Button', 'testimonial-pro' ),
										'default'    => false,
									),
									array(
										'id'         => 'record_label',
										'type'       => 'text',
										'title'      => __( 'Label', 'testimonial-pro' ),
										'desc'       => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default'    => __( 'Leave A Video Review', 'testimonial-pro' ),
										'dependency' => array( 'record_video', '==', 'true' ),
									),
									array(
										'id'         => 'record_btn_text',
										'type'       => 'text',
										'title'      => __( 'Record button text', 'testimonial-pro' ),
										'default'    => 'Record video',
										'dependency' => array( 'record_video', '==', 'true' ),
									),
									array(
										'id'         => 'recording_time',
										'type'       => 'spinner',
										'title'      => __( 'Maximum Recording Time', 'testimonial-pro' ),
										'default'    => 2,
										'unit'       => 'Mins',
										'min'        => '0',
										'max'        => '5',
										'dependency' => array( 'record_video', '==', 'true' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'video_url', true ),
					),
					array(
						'id'         => 'agree_checkbox',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Checkbox', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'I agree to publish the testimonial on the website.', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'agree_checkbox', true ),
					),
					array(
						'id'         => 'rating',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Rating', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Rating', 'testimonial-pro' ),
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'rating', true ),
					),
					array(
						'id'         => 'social_profile',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Social Profile', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Social Profile', 'testimonial-pro' ),
									),
									array(
										'id'          => 'social_profile_list',
										'type'        => 'select',
										'title'       => __( 'Select Social Profile(s)', 'testimonial-pro' ),
										'desc'        => __( 'Select social profile for the frontend form. Leave blank for the all socials.', 'testimonial-pro' ),
										'options'     => 'social_profile_name_list',
										'placeholder' => __( 'Select Social Profile(s)', 'testimonial-pro' ),
										'chosen'      => true,
										'multiple'    => true,
										'sortable'    => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'social_profile', true ),
					),
					array(
						'id'         => 'recaptcha',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'reCAPTCHA', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'    => 'label',
										'type'  => 'text',
										'title' => __( 'Label', 'testimonial-pro' ),
										'desc'  => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'recaptcha', true ),
					),
					array(
						'id'         => 'submit_btn',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Submit Button', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'Type submit button label.', 'testimonial-pro' ),
										'default' => __( 'Submit Testimonial', 'testimonial-pro' ),
									),
								),
							),
						),
					),

				),
			),

		),
	)
);

//
// Messages section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Messages', 'testimonial-pro' ),
		'icon'   => 'fa fa-exclamation-triangle',
		'fields' => array(

			array(
				'id'       => 'tpro_redirect',
				'type'     => 'select',
				'title'    => __( 'Redirect', 'testimonial-pro' ),
				'subtitle' => __( 'After successful submit, where the page will redirect to.', 'testimonial-pro' ),
				'options'  => array(
					'same_page'  => __( 'Same Page', 'testimonial-pro' ),
					'to_a_page'  => __( 'To a page', 'testimonial-pro' ),
					'custom_url' => __( 'To a custom URL', 'testimonial-pro' ),
				),
				'default'  => 'same_page',
			),
			array(
				'id'         => 'tpro_message_position',
				'type'       => 'button_set',
				'title'      => __( 'Form Submission Message Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a form submission message position.', 'testimonial-pro' ),
				'radio'      => true,
				'options'    => array(
					'top'    => __( 'Top', 'testimonial-pro' ),
					'bottom' => __( 'Bottom', 'testimonial-pro' ),
				),
				'default'    => 'bottom',
				'dependency' => array( 'tpro_redirect', '==', 'same_page' ),
			),
			array(
				'id'         => 'successful_message',
				'type'       => 'textarea',
				'title'      => __( 'Successful Message', 'testimonial-pro' ),
				'subtitle'   => __( 'Notification for successful submission.', 'testimonial-pro' ),
				'default'    => 'Thank you for submitting a new testimonial!',
				'dependency' => array( 'tpro_redirect', '==', 'same_page' ),
			),
			array(
				'id'         => 'tpro_redirect_to_page',
				'type'       => 'select',
				'title'      => __( 'Page', 'testimonial-pro' ),
				'subtitle'   => __( 'Select redirect page.', 'testimonial-pro' ),
				'options'    => 'pages',
				'dependency' => array( 'tpro_redirect', '==', 'to_a_page' ),
			),
			array(
				'id'         => 'tpro_redirect_custom_url',
				'type'       => 'text',
				'title'      => __( 'Custom URL', 'testimonial-pro' ),
				'subtitle'   => __( 'Type redirect custom url.', 'testimonial-pro' ),
				'dependency' => array( 'tpro_redirect', '==', 'custom_url' ),
			),
		),
	)
);

//
// Notifications section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Notifications', 'testimonial-pro' ),
		'icon'   => 'fa fa-bell',
		'fields' => array(

			array(
				'id'       => 'testimonial_approval_status',
				'type'     => 'select',
				'title'    => __( 'Testimonial Status', 'testimonial-pro' ),
				'subtitle' => __( 'Select testimonial approval status for the front-end form submission.', 'testimonial-pro' ),
				'options'  => array(
					'publish' => esc_html__( 'Auto Publish', 'testimonial-pro' ),
					'pending' => esc_html__( 'Pending Review', 'testimonial-pro' ),
					'private' => esc_html__( 'Private', 'testimonial-pro' ),
					'draft'   => esc_html__( 'Draft', 'testimonial-pro' ),
				),
				'default'  => 'pending',
			),
			array(
				'id'         => 'submission_email_notification',
				'type'       => 'switcher',
				'title'      => __( 'Email Notification', 'testimonial-pro' ),
				'subtitle'   => __( 'Email notification for a new testimonial.', 'testimonial-pro' ),
				'text_on'    => esc_html__( 'Enabled', 'testimonial-pro' ),
				'text_off'   => esc_html__( 'Disabled', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'submission_email_subject',
				'type'       => 'text',
				'title'      => __( 'Email Notification Subject', 'testimonial-pro' ),
				'subtitle'   => __( 'Type subject for the email notification.', 'testimonial-pro' ),
				'default'    => 'A New Testimonial is Pending!',
				'dependency' => array( 'submission_email_notification', '==', 'true' ),
			),
			array(
				'id'         => 'submission_email_heading',
				'type'       => 'text',
				'title'      => __( 'Email Notification Heading', 'testimonial-pro' ),
				'subtitle'   => __( 'Type heading for the email notification.', 'testimonial-pro' ),
				'default'    => 'New Testimonial!',
				'dependency' => array( 'submission_email_notification', '==', 'true' ),
			),
			array(
				'id'         => 'submission_email_body',
				'type'       => 'wp_editor',
				'title'      => __( 'Email Notification Body', 'testimonial-pro' ),
				'default'    => 'Hey There,
A new testimonial has been submitted to your website. Following are the reviewer information.

Name: {name}
Email: {email}
Testimonial Content: {testimonial_text}
Rating: {rating}

Please go admin dashboard to review it and publish.

Thank you!',
				'desc'       => '
			Enter the text that will be sent as notification email for pending testimonial. HTML is accepted. Available template tags are:<br>
			{name} - The reviewer\'s full name.<br>
			{email} - The reviewer\'s email address.<br>
			{position} - The reviewer\'s position.<br>
			{company_name} - The reviewer\'s company name.<br>
			{location} - The reviewer\'s location address.<br>
			{phone} - The reviewer\'s phone number.<br>
			{website} - The reviewer\'s company website URL.<br>
			{video_url} - The reviewer\'s video URL.<br>
			{testimonial_title} - Testimonial title.<br>
			{testimonial_text} - Testimonial content.<br>
			{groups} - Testimonial groups.<br>
			{rating} - Testimonial rating.',
				'dependency' => array( 'submission_email_notification', '==', 'true' ),
			),
			array(
				'id'         => 'submission_email_notification_to',
				'type'       => 'textarea',
				'title'      => __( 'Email(s) to Notify', 'testimonial-pro' ),
				'desc'       => __( 'Enter the email address(es) that will receive a notification for each pending testimonial. For multiple emails, use comma between these.', 'testimonial-pro' ),
				'default'    => get_option( 'admin_email' ),
				'dependency' => array( 'submission_email_notification', '==', 'true' ),
			),
		),
	)
);

//
// Stylization section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Stylization', 'testimonial-pro' ),
		'icon'   => 'fa fa-paint-brush',
		'fields' => array(

			array(
				'id'       => 'label_color',
				'type'     => 'color',
				'title'    => __( 'Label Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for the field label.', 'testimonial-pro' ),
				'default'  => '#444444',
			),
			array(
				'id'       => 'record_button_color',
				'type'     => 'color_group',
				'title'    => __( 'Record Button Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for the record button.', 'testimonial-pro' ),
				'options'  => array(
					'color'            => esc_html__( 'Color', 'testimonial-pro' ),
					'hover-color'      => esc_html__( 'Hover Color', 'testimonial-pro' ),
					'background'       => esc_html__( 'Background', 'testimonial-pro' ),
					'hover-background' => esc_html__( 'Hover Background', 'testimonial-pro' ),
				),
				'default'  => array(
					'color'            => '#005BDF',
					'hover-color'      => '#005BDF',
					'background'       => 'rgba(0, 91, 223, 0.12)',
					'hover-background' => 'rgba(0, 91, 223, 0.18)',
				),

			),
			// 005BDF1F
			array(
				'id'       => 'submit_button_color',
				'type'     => 'color_group',
				'title'    => __( 'Submit Button Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for the submit button.', 'testimonial-pro' ),
				'options'  => array(
					'color'            => esc_html__( 'Color', 'testimonial-pro' ),
					'hover-color'      => esc_html__( 'Hover Color', 'testimonial-pro' ),
					'background'       => esc_html__( 'Background', 'testimonial-pro' ),
					'hover-background' => esc_html__( 'Hover Background', 'testimonial-pro' ),
				),
				'default'  => array(
					'color'            => '#ffffff',
					'hover-color'      => '#ffffff',
					'background'       => '#005BDF',
					'hover-background' => '#005BDF',
				),
			),
		),
	)
);

//
// Metabox of the Testimonial.
// Set a unique slug-like ID.
//
$prefix_testimonial_opts = 'sp_tpro_meta_options';

//
// Testimonial metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_testimonial_opts,
	array(
		'title'           => __( 'Testimonial Options', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial',
		'context'         => 'normal',
		'enqueue_webfont' => false,
	)
);

//
// Reviewer Information section.
//
SPFTESTIMONIAL::createSection(
	$prefix_testimonial_opts,
	array(
		'title'  => __( 'Reviewer Information', 'testimonial-pro' ),
		'fields' => array(

			array(
				'id'       => 'tpro_name',
				'type'     => 'text',
				'title'    => __( 'Full Name', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_email',
				'type'     => 'text',
				'title'    => __( 'E-mail Address', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_designation',
				'type'     => 'text',
				'title'    => __( 'Identity or Position', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_company_name',
				'type'     => 'text',
				'title'    => __( 'Company Name', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),

			array(
				'id'    => 'tpro_company_logo',
				'type'  => 'media',
				'url'   => false,
				'title' => __( 'Company Logo', 'testimonial-pro' ),
				// 'sanitize' => 'sp_tpro_sanitize_text',
			),

			array(
				'id'       => 'tpro_location',
				'type'     => 'text',
				'title'    => __( 'Location', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_phone',
				'type'     => 'text',
				'title'    => __( 'Phone or Mobile', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_website',
				'type'     => 'text',
				'title'    => __( 'Website', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),

			array(
				'id'       => 'tpro_video_url',
				'type'     => 'text',
				'title'    => __( 'Video Testimonial URL', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'      => 'tpro_rating',
				'type'    => 'rating',
				'title'   => __( 'Rating', 'testimonial-pro' ),
				'options' => array(
					'five_star'  => __( '5 Stars', 'testimonial-pro' ),
					'four_star'  => __( '4 Stars', 'testimonial-pro' ),
					'three_star' => __( '3 Stars', 'testimonial-pro' ),
					'two_star'   => __( '2 Stars', 'testimonial-pro' ),
					'one_star'   => __( '1 Star', 'testimonial-pro' ),
				),
				'default' => '',
			),
			array(
				'id'    => 'tpro_client_checkbox',
				'class' => 'tpro_client_checkbox',
				'type'  => 'checkbox',
				'title' => __( 'Checkbox', 'testimonial-pro' ),
			),
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'ADDITIONAL CUSTOM FIELDS', 'testimonial-pro' ),
			),
			array(
				'id'           => 'testimonial_extra_fields',
				'type'         => 'repeater',
				'title'        => 'Reviewer Custom Information',
				'class'        => 'social-profile-repeater testimonial_extra_info',
				'button_title' => esc_html__( 'Add Field', 'testimonial-pro' ),
				'sort'         => true,
				'clone'        => false,
				'remove'       => true,
				'fields'       => array(
					array(
						'id'           => 'testimonial_extra_fields_icon',
						'type'         => 'icon',
						'button_title' => esc_html__( 'Add Icon', 'testimonial-pro' ),
						'remove_title' => '<i class="fa fa-trash"></i>',
					),
					array(
						'id'          => 'testimonial_extra_fields_type',
						'type'        => 'select',
						'options'     => array(
							'text'   => esc_html__( 'Text', 'testimonial-pro' ),
							'number' => esc_html__( 'Number', 'testimonial-pro' ),
							'email'  => esc_html__( 'Email', 'testimonial-pro' ),
							'link'   => esc_html__( 'Link', 'testimonial-pro' ),
							'date'   => esc_html__( 'Date', 'testimonial-pro' ),
						),
						'placeholder' => 'Field type',
						'class'       => 'repeater-select',
					),
					array(
						'id'    => 'testimonial_extra_fields_value',
						'type'  => 'text',
						'class' => 'repeater-text',
					),
				),
				'default'      => array(
					array(),
				),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Social Media', 'testimonial-pro' ),
			),
			array(
				'id'      => 'tpro_social_profiles',
				'type'    => 'repeater',
				'title'   => esc_html__( 'Social Profiles', 'testimonial-pro' ),
				'class'   => 'social-profile-repeater',
				'clone'   => false,
				'fields'  => array(
					array(
						'id'          => 'social_name',
						'type'        => 'select',
						'options'     => 'social_profile_name_list',
						'placeholder' => esc_html__( 'Select', 'testimonial-pro' ),
					),
					array(
						'id'    => 'social_url',
						'type'  => 'text',
						'class' => 'social-url',
					),
				),
				'default' => array(
					array(),
				),
			),

		),
	)
);
