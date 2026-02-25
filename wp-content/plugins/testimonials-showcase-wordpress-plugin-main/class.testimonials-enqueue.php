<?php
namespace TestimonialsShowcase;

class Testimonials_Enqueue {

    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles_and_scripts'));
    }

    public function enqueue_styles_and_scripts() {
        // Check if we are on a page that has our shortcode or is a testimonial CPT
        global $post;
        if (is_singular('testimonial') || (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'testimonials'))) {
            // Enqueue CSS
            wp_enqueue_style('testimonials-showcase-style', plugins_url('/testimonials-showcase/assets/css/style.css', dirname(__FILE__)), array(), '1.0.0', 'all');

            // Enqueue JavaScript
            wp_register_script('testimonial-slider-script', plugins_url('/testimonials-showcase/assets/js/testimonial-slider.js', dirname( __FILE__)), array('jquery'), '1.0.0', true);
            // For either a plugin or a theme, you can then enqueue the script:
            wp_enqueue_script('testimonial-slider-script');
        
            wp_enqueue_style('roboto-font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
 
        }
    }

    //FUTURE Update: Implement Slick
    /*
    public function testimonials_enqueue_slick() {
        // Enqueue Slick CSS
        wp_enqueue_style('slick-css', plugins_url('/slick/slick.css', __FILE__));
        wp_enqueue_style('slick-theme-css', plugins_url('/slick/slick-theme.css', __FILE__));

        // Enqueue Slick JS
        wp_enqueue_script('slick-js', plugins_url('/slick/slick.min.js', __FILE__), array('jquery'), '', true);

        // Enqueue your custom JS to initialize Slick
        wp_enqueue_script('testimonial-slick-initialize', plugins_url('/js/testimonial-slick-initialize.js', __FILE__), array('slick-js'), '', true);
    }
    add_action('wp_enqueue_scripts', 'testimonials_enqueue_slick');
    */
}

