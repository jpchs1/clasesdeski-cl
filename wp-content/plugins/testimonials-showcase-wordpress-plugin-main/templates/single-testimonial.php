<?php

global $post;

// You can access ACF fields or standard WP fields:
$customer_name = get_field('customer_name', $post->ID);
$customer_position = get_field('customer_position', $post->ID);
$customer_photo = get_field('customer_photo', $post->ID);  // Assuming it returns an image URL
?>

<div class="testimonial">
    <?php if ($customer_photo): ?>
        <img src="<?php echo esc_url($customer_photo); ?>" alt="<?php echo esc_attr($customer_name); ?>" class="testimonial-image">
    <?php endif; ?>

    <div class="testimonial-content">
        <?php echo wp_kses_post(wpautop($post->post_content)); ?>
    </div>

    <?php if ($customer_name): ?>
        <h3 class="testimonial-name"><?php echo esc_html($customer_name); ?></h3>
    <?php endif; ?>

    <?php if ($customer_position): ?>
        <p class="testimonial-position"><?php echo esc_html($customer_position); ?></p>
    <?php endif; ?>

    
</div>
