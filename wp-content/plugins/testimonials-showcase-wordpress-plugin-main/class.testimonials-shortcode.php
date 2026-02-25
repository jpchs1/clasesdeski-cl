<?php
namespace TestimonialsShowcase;

class Testimonials_Shortcode {

    public function init() {
        //add_shortcode('testimonial_slider', array($this, 'render_shortcode'));
        add_shortcode('testimonials', array($this, 'render_shortcode'));
    
    }

    public function render_shortcode($atts) {
        ob_start();
        $this->render_testimonials($atts);
        return ob_get_clean();
    }

    private function render_testimonials($atts) {

        // Parse attributes
        $atts = shortcode_atts(array(
            'count' => 3, // Default number of testimonials to display
            // other default attributes
        ), $atts, 'testimonial_slider');

        // Parse shortcode attributes and set up query arguments
        // ...

        // Query to get testimonials
        $testimonials_query = new \WP_Query(array(
            'post_type' => 'testimonial',
            // other query parameters
        ));

        $testimonial_count = 0;

        // Include the testimonial list template
        include plugin_dir_path(__FILE__) . 'templates/testimonial-list.php';
        
        // Reset post data
        wp_reset_postdata();
    }

}
