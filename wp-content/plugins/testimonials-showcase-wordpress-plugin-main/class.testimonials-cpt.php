<?php
namespace TestimonialsShowcase;

class Testimonials_CPT {

    public function init() {
        add_action('init', array($this, 'register_testimonial_cpt'));
    }

    public function register_testimonial_cpt() {
        $labels = array(
            'name'                  => _x('Testimonials', 'Post Type General Name', 'text_domain'),
            'singular_name'         => _x('Testimonial', 'Post Type Singular Name', 'text_domain'),
            'menu_name'             => __('Testimonials', 'text_domain'),
            'name_admin_bar'        => __('Testimonial', 'text_domain'),
            'archives'              => __('Testimonial Archives', 'text_domain'),
            'attributes'            => __('Testimonial Attributes', 'text_domain'),
            'parent_item_colon'     => __('Parent Testimonial:', 'text_domain'),
            'all_items'             => __('All Testimonials', 'text_domain'),
            'add_new_item'          => __('Add New Testimonial', 'text_domain'),
            'add_new'               => __('Add New', 'text_domain'),
            'new_item'              => __('New Testimonial', 'text_domain'),
            'edit_item'             => __('Edit Testimonial', 'text_domain'),
            'update_item'           => __('Update Testimonial', 'text_domain'),
            'view_item'             => __('View Testimonial', 'text_domain'),
            'view_items'            => __('View Testimonials', 'text_domain'),
            'search_items'          => __('Search Testimonial', 'text_domain'),
            'not_found'             => __('Not found', 'text_domain'),
            'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
            'featured_image'        => __('Featured Image', 'text_domain'),
            'set_featured_image'    => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image'    => __('Use as featured image', 'text_domain'),
            'insert_into_item'      => __('Insert into testimonial', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this testimonial', 'text_domain'),
            'items_list'            => __('Testimonials list', 'text_domain'),
            'items_list_navigation' => __('Testimonials list navigation', 'text_domain'),
            'filter_items_list'     => __('Filter testimonials list', 'text_domain'),
        );
        $args = array(
            'label'                 => __('Testimonials', 'text_domain'),
            'description'           => __('Post Type Description', 'text_domain'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'taxonomies'            => array('category', 'post_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );

        
        register_post_type('testimonial', $args);
    }
}

