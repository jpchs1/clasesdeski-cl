jQuery(document).ready(function($) {
    // Navigation bullet click event
    $('.bullet').on('click', function() {
        var slideIndex = $(this).index();
        var testimonialWidth = $('.testimonial').outerWidth(true); // Width including margin
        $('.testimonial-slider').animate({
            scrollLeft: testimonialWidth * slideIndex
        }, 300);

        // Update active bullet
        $('.bullet').removeClass('active');
        $(this).addClass('active');
    });

    // Optional: Auto-update active bullet on scroll
    $('.testimonial-slider').on('scroll', function() {
        var scrollLeft = $(this).scrollLeft();
        var testimonialWidth = $('.testimonial').outerWidth(true);
        var activeIndex = Math.round(scrollLeft / testimonialWidth);
        
        $('.bullet').removeClass('active');
        $('.bullet').eq(activeIndex).addClass('active');
    });
})