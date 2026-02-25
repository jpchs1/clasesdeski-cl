<?php
// Ensure that $testimonials_query is passed to this template or globally defined
// $testimonials_query should be a WP_Query object containing the testimonial posts

if ($testimonials_query->have_posts()) {
    
    echo '<div class="testimonial-slider">';

    while ($testimonials_query->have_posts()) {
        $testimonials_query->the_post();
        // Include the single testimonial template for each testimonial
        include 'single-testimonial.php'; // Adjust the path as needed
        
        $testimonial_count++;
    }

    echo '</div>'; // Close .testimonial-slider

    // Generate bullet indicators
    echo '<div class="testimonial-bullets">';
    for ($i = 0; $i < $testimonial_count; $i++) {
        echo '<span class="bullet" data-slide="' . $i . '"></span>';
    }
    echo '</div>';
    
    // Reset post data to avoid conflicts with other queries or loops
    wp_reset_postdata();
} else {
    echo '<p>' . __('No testimonials found.', 'text_domain') . '</p>';
}
