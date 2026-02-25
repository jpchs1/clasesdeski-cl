<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('mounthood_contact_form_7_theme_setup')) {
    add_action( 'mounthood_action_before_init_theme', 'mounthood_contact_form_7_theme_setup', 1 );
    function mounthood_contact_form_7_theme_setup() {
        if (is_admin()) {
            add_filter( 'mounthood_filter_required_plugins', 'mounthood_contact_form_7_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'mounthood_exists_contact_form_7' ) ) {
    function mounthood_exists_contact_form_7() {
        return defined( 'Contact Form 7' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'mounthood_contact_form_7_required_plugins' ) ) {
    function mounthood_contact_form_7_required_plugins($list=array()) {
        if (in_array('contact_form_7', (array)mounthood_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Contact Form 7', 'mounthood'),
                'slug'         => 'contact-form-7',
                'required'     => false
            );
        return $list;
    }
}
