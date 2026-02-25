<?php
/*
Plugin Name: Testimonials Showcase
Description: A simple plugin to display customer testimonials.
Version: 1.0
Author: Majaveli
*/

require_once plugin_dir_path( __FILE__ ) . 'class.testimonials-showcase.php';

$testimonials_showcase = new TestimonialsShowcase\Testimonials_Showcase();


/*use TestimonialsShowcase\Testimonials_Helpers;

$email = Testimonials_Helpers::sanitize_input($_POST['email'], 'email');
$date = Testimonials_Helpers::get_testimonial_date($post_id);
*/


