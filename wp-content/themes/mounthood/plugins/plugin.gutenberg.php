<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('mounthood_gutenberg_theme_setup')) {
    add_action( 'mounthood_action_before_init_theme', 'mounthood_gutenberg_theme_setup', 1 );
    function mounthood_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'mounthood_filter_required_plugins', 'mounthood_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'mounthood_exists_gutenberg' ) ) {
    function mounthood_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'mounthood_gutenberg_required_plugins' ) ) {
    function mounthood_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)mounthood_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'mounthood'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}