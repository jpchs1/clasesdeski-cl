<?php
/**
 * The admin-specific setting options of the plugin.
 *
 * @since        2.5.0
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

use ShapedPlugin\TestimonialPro\Admin\Views\Framework\Classes\SPFTESTIMONIAL;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_testimonial_pro_options';

//
// Create a settings page.
//
SPFTESTIMONIAL::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Settings', 'testimonial-pro' ),
		'menu_parent'      => 'edit.php?post_type=spt_testimonial',
		'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
		'menu_slug'        => 'tpro_settings',
		'theme'            => 'light',
		'show_all_options' => false,
		'show_search'      => false,
		'show_footer'      => false,
		'show_reset_all'   => false,
		'framework_title'  => __( 'Settings', 'testimonial-pro' ),
	)
);

//
// License key section.
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'title'  => __( 'License Key', 'testimonial-pro' ),
		'icon'   => 'fa fa-key',

		'fields' => array(
			array(
				'id'   => 'license_key',
				'type' => 'license',
			),
		),
	)
);

//
// Advanced Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'name'   => 'advanced_settings',
		'title'  => __( 'Advanced Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-cogs',

		'fields' => array(
			array(
				'id'      => 'testimonial_data_remove',
				'type'    => 'checkbox',
				'title'   => __( 'Clean up Data on Deletion', 'testimonial-pro' ),
				'help'    => __( 'Delete all Real Testimonials data from the database on plugin deletion.', 'testimonial-pro' ),
				'default' => false,
			),
			array(
				'id'         => 'tpro_dequeue_google_fonts',
				'type'       => 'switcher',
				'title'      => __( 'Google Fonts', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => false,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Enqueue or Dequeue JS', 'testimonial-pro' ),
			),
			array(
				'id'         => 'tpro_dequeue_swiper_js',
				'type'       => 'switcher',
				'title'      => __( 'Swiper JS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'tpro_dequeue_bx_js',
				'type'       => 'switcher',
				'title'      => __( 'bxSlider JS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'tpro_dequeue_isotope_js',
				'type'       => 'switcher',
				'title'      => __( 'Isotope JS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'tpro_dequeue_magnific_popup_js',
				'type'       => 'switcher',
				'title'      => __( 'Magnific Popup JS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),

			array(
				'type'    => 'subheading',
				'content' => __( 'Enqueue or Dequeue CSS', 'testimonial-pro' ),
			),
			array(
				'id'         => 'tpro_dequeue_swiper_css',
				'type'       => 'switcher',
				'title'      => __( 'Swiper CSS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'tpro_dequeue_bx_css',
				'type'       => 'switcher',
				'title'      => __( 'bxSlider CSS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'tpro_dequeue_fa_css',
				'type'       => 'switcher',
				'title'      => __( 'Font Awesome CSS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'tpro_dequeue_magnific_popup_css',
				'type'       => 'switcher',
				'title'      => __( 'Magnific Popup CSS', 'testimonial-pro' ),
				'text_on'    => __( 'Enqueue', 'testimonial-pro' ),
				'text_off'   => __( 'Dequeue', 'testimonial-pro' ),
				'text_width' => 95,
				'default'    => true,
			),

		),
	)
);

//
// Menu Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'name'   => 'menu_settings',
		'title'  => __( 'Menu Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-bars',

		'fields' => array(
			array(
				'id'      => 'tpro_singular_name',
				'type'    => 'text',
				'title'   => __( 'Singular name', 'testimonial-pro' ),
				'default' => 'Testimonial',
			),
			array(
				'id'      => 'tpro_plural_name',
				'type'    => 'text',
				'title'   => __( 'Plural name', 'testimonial-pro' ),
				'default' => 'Testimonials',
			),
			array(
				'id'      => 'tpro_group_singular_name',
				'type'    => 'text',
				'title'   => __( 'Group Singular name', 'testimonial-pro' ),
				'default' => 'Group',
			),
			array(
				'id'      => 'tpro_group_plural_name',
				'type'    => 'text',
				'title'   => __( 'Group Plural name', 'testimonial-pro' ),
				'default' => 'Groups',
			),

		),
	)
);

// Field: reCAPTCHA
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'id'     => 'google_recaptcha',
		'title'  => __( 'reCAPTCHA', 'testimonial-pro' ),
		'icon'   => 'fa fa-shield',
		'fields' => array(

			array(
				'type'    => 'submessage',
				'style'   => 'info',
				'content' => __(
					'<a href="https://www.google.com/recaptcha" target="_blank">reCAPTCHA</a> is a free anti-spam service of Google that protects your website from spam and abuse. <a
href="https://www.google.com/recaptcha/admin#list" target="_blank"> Get your API Keys</a>.',
					'testimonial-pro'
				),
			),
			array(
				'id'      => 'captcha_version',
				'type'    => 'radio',
				'title'   => __( 'reCAPTCHA', 'testimonial-pro' ),
				'options' => array(
					'v2' => __( 'v2', 'testimonial-pro' ),
					'v3' => __( 'v3', 'testimonial-pro' ),
				),
				'default' => 'v2',
				'inline'  => true,
			),
			array(
				'id'         => 'captcha_site_key',
				'type'       => 'text',
				'title'      => __( 'Site key', 'testimonial-pro' ),
				'dependency' => array( 'captcha_version', '==', 'v2' ),
			),
			array(
				'id'         => 'captcha_secret_key',
				'type'       => 'text',
				'title'      => __( 'Secret key', 'testimonial-pro' ),
				'dependency' => array( 'captcha_version', '==', 'v2' ),
			),
			array(
				'id'         => 'captcha_site_key_v3',
				'type'       => 'text',
				'title'      => __( 'Site key', 'testimonial-pro' ),
				'dependency' => array( 'captcha_version', '==', 'v3' ),
			),
			array(
				'id'         => 'captcha_secret_key_v3',
				'type'       => 'text',
				'title'      => __( 'Secret key', 'testimonial-pro' ),
				'dependency' => array( 'captcha_version', '==', 'v3' ),
			),
		),
	)
);

//
// Custom CSS section.
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'name'   => 'custom_css_section',
		'title'  => __( 'Custom CSS', 'testimonial-pro' ),
		'icon'   => 'fa fa-css3',

		'fields' => array(
			array(
				'id'       => 'custom_css',
				'type'     => 'code_editor',
				'sanitize' => 'wp_strip_all_tags',
				'settings' => array(
					'theme' => 'dracula',
					'mode'  => 'css',
				),
				'title'    => __( 'Custom CSS', 'testimonial-pro' ),
			),
		),
	)
);
