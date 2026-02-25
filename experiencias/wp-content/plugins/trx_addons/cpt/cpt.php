<?php
/**
 * ThemeREX Addons Custom post types
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.1
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Include files with CPT
if (!function_exists('trx_addons_cpt_load')) {
	add_action( 'after_setup_theme', 'trx_addons_cpt_load', 2 );
	add_action( 'trx_addons_action_save_options', 'trx_addons_cpt_load', 2 );
	function trx_addons_cpt_load() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		global $TRX_ADDONS_STORAGE;
		$TRX_ADDONS_STORAGE['cpt_resume_types'] = apply_filters('trx_addons_cpt_resume_types', array(
			'skills' => esc_html__('Skills', 'trx_addons'),
			'work' => esc_html__('Work experience', 'trx_addons'),
			'education' => esc_html__('Education', 'trx_addons'),
			'services' => esc_html__('Services', 'trx_addons')
		) );
		$TRX_ADDONS_STORAGE['cpt_list'] = apply_filters('trx_addons_cpt_list', array(
			'certificates' => array(
				'title' => esc_html__('Certificates', 'trx_addons'),
				'post_type' => 'cpt_certificates',
				'post_type_slug' => 'certificates',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
				),
			'courses' => array(
				'title' => esc_html__('Courses', 'trx_addons'),
				'post_type' => 'cpt_courses',
				'post_type_slug' => 'courses',
				'taxonomy' => 'cpt_courses_group',
				'taxonomy_slug' => 'courses_group',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
				),
			'dishes' => array(
				'title' => esc_html__('Dishes', 'trx_addons'),
				'post_type' => 'cpt_dishes',
				'post_type_slug' => 'dishes',
				'taxonomy' => 'cpt_dishes_group',
				'taxonomy_slug' => 'dishes_group',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
				),
			'layouts' => array(
				'title' => esc_html__('Layouts', 'trx_addons'),
				'post_type' => 'cpt_layouts',
				'post_type_slug' => 'layouts',
				'taxonomy' => 'cpt_layouts_group',
				'taxonomy_slug' => 'layouts_group',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail')
				),
			'portfolio' => array(
				'title' => esc_html__('Portfolio', 'trx_addons'),
				'post_type' => 'cpt_portfolio',
				'post_type_slug' => 'portfolio',
				'taxonomy' => 'cpt_portfolio_group',
				'taxonomy_slug' => 'portfolio_group',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
				),
			'post' => array(
				'title' => esc_html__('Post', 'trx_addons')
				),
			'properties' => array(
				'title' => esc_html__('Properties', 'trx_addons'),
				'post_type' => 'cpt_properties',
				'post_type_slug' => 'properties',
				'taxonomy_type' => 'cpt_properties_type',
				'taxonomy_type_slug' => 'properties_type',
				'taxonomy_status' => 'cpt_properties_status',
				'taxonomy_status_slug' => 'properties_status',
				'taxonomy_features' => 'cpt_properties_features',
				'taxonomy_features_slug' => 'properties_features',
				'taxonomy_labels' => 'cpt_properties_labels',
				'taxonomy_labels_slug' => 'properties_labels',
				'taxonomy_country' => 'cpt_properties_country',
				'taxonomy_country_slug' => 'properties_country',
				'taxonomy_state' => 'cpt_properties_state',
				'taxonomy_state_slug' => 'properties_state',
				'taxonomy_city' => 'cpt_properties_city',
				'taxonomy_city_slug' => 'properties_city',
				'taxonomy_neighborhood' => 'cpt_properties_neighborhood',
				'taxonomy_neighborhood_slug' => 'properties_neighborhood',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
				),
				'agents' => array(
					'slave' => true,	// Additional post type for the 'properties'
					'title' => esc_html__('Agents', 'trx_addons'),
					'post_type' => 'cpt_agents',
					'post_type_slug' => 'agents',
					'taxonomy' => 'cpt_agency',
					'taxonomy_slug' => 'agency',
					'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
					),
			'resume' => array(
				'title' => esc_html__('Resume', 'trx_addons'),
				'post_type' => 'cpt_resume',
				'post_type_slug' => 'resume',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
				),
			'services' => array(
				'title' => esc_html__('Services', 'trx_addons'),
				'post_type' => 'cpt_services',
				'post_type_slug' => 'services',
				'taxonomy' => 'cpt_services_group',
				'taxonomy_slug' => 'services_group',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
				),
			'sport' => array(		// Plugin's internal tables structure
				'title' => esc_html__('Sport Reviews', 'trx_addons'),
				),
				'competitions' => array(
					'slave' => true,	// Additional post type for the 'sport'
					'title' => esc_html__('Competitions', 'trx_addons'),
					'post_type' => 'cpt_competitions',
					'post_type_slug' => 'competitions',
					'taxonomy' => 'cpt_competitions_sports',
					'taxonomy_slug' => 'sports',
					'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
					),
				'rounds' => array(
					'slave' => true,	// Additional post type for the 'sport'
					'title' => esc_html__('Rounds', 'trx_addons'),
					'post_type' => 'cpt_rounds',
					'post_type_slug' => 'rounds',
					'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
					),
				'matches' => array(
					'slave' => true,	// Additional post type for the 'sport'
					'title' => esc_html__('Matches', 'trx_addons'),
					'post_type' => 'cpt_matches',
					'post_type_slug' => 'matches',
					'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
					),
				'players' => array(
					'slave' => true,	// Additional post type for the 'sport'
					'title' => esc_html__('Players', 'trx_addons'),
					'post_type' => 'cpt_players',
					'post_type_slug' => 'players',
					'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields')
					),
			'team' => array(
				'title' => esc_html__('Team', 'trx_addons'),
				'post_type' => 'cpt_team',
				'post_type_slug' => 'team',
				'taxonomy' => 'cpt_team_group',
				'taxonomy_slug' => 'team_group',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
				),
			'testimonials' => array(
				'title' => esc_html__('Testimonials', 'trx_addons'),
				'post_type' => 'cpt_testimonials',
				'post_type_slug' => 'testimonials',
				'taxonomy' => 'cpt_testimonials_group',
				'taxonomy_slug' => 'testimonials_group',
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
				)
			)
		);
		if (is_array($TRX_ADDONS_STORAGE['cpt_list']) && count($TRX_ADDONS_STORAGE['cpt_list']) > 0) {
			foreach ($TRX_ADDONS_STORAGE['cpt_list'] as $cpt => $params) {
				if ( empty($params['slave']) && ($fdir = trx_addons_get_file_dir("cpt/{$cpt}/{$cpt}.php")) != '') { include_once $fdir; }
			}
		}
	}
}

// Add 'CPT' section in the ThemeREX Addons Options
if (!function_exists('trx_addons_cpt_options')) {
	add_action( 'trx_addons_filter_options', 'trx_addons_cpt_options');
	function trx_addons_cpt_options($options) {

		trx_addons_array_insert_before($options, 'api_section', array(
			// Section 'CPT' - main options
			'cpt_section' => array(
				"title" => esc_html__('CPT', 'trx_addons'),
				"desc" => wp_kses_data( __('CPT (Custom Post Types) options', 'trx_addons') ),
				"type" => "section"
			)
		));
		return $options;
	}
}

// Return list of the allowed CPT
if (!function_exists('trx_addons_get_cpt_list')) {
	function trx_addons_get_cpt_list() {
		global $TRX_ADDONS_STORAGE;
		$list = array();
		if (is_array($TRX_ADDONS_STORAGE['cpt_list']) && count($TRX_ADDONS_STORAGE['cpt_list']) > 0) {
			foreach ($TRX_ADDONS_STORAGE['cpt_list'] as $cpt => $params) {
				if (!empty($params['post_type'])) $list[$params['post_type']] = $params['title'];
			}
		}
		return $list;
	}
}

// Return slug of the CPT
if (!function_exists('trx_addons_cpt_param')) {
	function trx_addons_cpt_param($cpt='', $param='') {
		global $TRX_ADDONS_STORAGE;
		$rez = '';
		if (!empty($TRX_ADDONS_STORAGE['cpt_list'][$cpt]))
			$rez = $TRX_ADDONS_STORAGE['cpt_list'][$cpt][$param];
		else {
			foreach ($TRX_ADDONS_STORAGE['cpt_list'] as $slug => $params) {
				if (!empty($params['post_type']) && $params['post_type'] == $cpt) {
					$rez = $params[$param];
					break;
				}
			}
		}
		return $rez;
	}
}
?>