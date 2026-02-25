<?php
namespace TestimonialsShowcase;

class Testimonials_Helpers {

    /**
     * Sanitizes and returns a sanitized version of input data.
     *
     * @param mixed $input The input data to sanitize.
     * @param string $type The type of data to sanitize: 'text', 'url', 'email', etc.
     * @return mixed The sanitized input.
     */
    public static function sanitize_input($input, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($input);
            case 'url':
                return esc_url($input);
            case 'text':
            default:
                return sanitize_text_field($input);
        }
    }

    /**
     * Retrieves formatted date for a testimonial.
     *
     * @param int $post_id The ID of the testimonial post.
     * @return string The formatted date.
     */
    public static function get_testimonial_date($post_id) {
        $post_date = get_the_date('', $post_id);
        return apply_filters('testimonials_showcase_date', $post_date, $post_id);
    }

    // Additional helper functions can be added here.
}
