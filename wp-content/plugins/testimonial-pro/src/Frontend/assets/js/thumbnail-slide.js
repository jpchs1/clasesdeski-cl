jQuery(document).ready(function ($) {

	$('.sp-testimonial-pro-wrapper.sp-tpro-thumbnail-slider').each(function () {
		var tpro_custom_thumbnail_id = $(this).attr('id'),
			tpro_thumbnail_data = $('#' + tpro_custom_thumbnail_id + ' .sp-testimonial-pro-section').data('thumb_swiper'),
			tpro_navigation_icon = $('#' + tpro_custom_thumbnail_id + ' .sp-testimonial-pro-section').data('arrowicon');
		var contentCarousel = $('#' + tpro_custom_thumbnail_id + ' .sp-testimonial-pro-section-content');
		var item = contentCarousel.find(".swiper-wrapper .swiper-slide").length;
		if (tpro_custom_thumbnail_id != '' && !$('#' + tpro_custom_thumbnail_id + ' .sp-testimonial-pro-section').hasClass('swiper-initialized')) {
			var galleryThumbs = new Swiper('#' + tpro_custom_thumbnail_id + ' .sp-testimonial-pro-section-thumb', {
				speed: tpro_thumbnail_data.speed,
				slidesPerGroup: 1,
				centeredSlides: true,
				spaceBetween: 5,
				slideToClickedSlide: true,
				slidesPerView: tpro_thumbnail_data.slidesPerView.lg_desktop,
				loop: true,
				observer: true,
				loopedSlides: item,
				navigation: false,
				pagination: false,
				simulateTouch: tpro_thumbnail_data.draggable,
				allowTouchMove: tpro_thumbnail_data.swipe,
				mousewheel: tpro_thumbnail_data.swipeToSlide,
				breakpoints: {
					320: {
						slidesPerView: tpro_thumbnail_data.slidesPerView.mobile,

					},
					576: {
						slidesPerView: tpro_thumbnail_data.slidesPerView.tablet,

					},
					736: {
						slidesPerView: tpro_thumbnail_data.slidesPerView.laptop,

					},
					980: {
						slidesPerView: tpro_thumbnail_data.slidesPerView.desktop,

					},
					1200: {
						slidesPerView: tpro_thumbnail_data.slidesPerView.lg_desktop,
					},
				},
			});
			var galleryContent = new Swiper('#' + tpro_custom_thumbnail_id + ' .sp-testimonial-pro-section-content', {
				speed: tpro_thumbnail_data.speed,
				slidesPerView: 1,
				slidesPerGroup: 1,
				loop: true,
				loopedSlides: item,
				spaceBetween: 10,
				grabCursor: true,
				autoHeight: tpro_thumbnail_data.adaptiveHeight,
				effect: tpro_thumbnail_data.effect == true ? 'fade' : 'slide',
				pagination:
					tpro_thumbnail_data.dots == true
						? {
							el: ".swiper-pagination",
							clickable: true,
						}
						: false,
				navigation:
					tpro_thumbnail_data.arrows == true
						? {
							nextEl: ".tpro-button-next",
							prevEl: ".tpro-button-prev",
						}
						: false,
				autoplay: {
					delay: tpro_thumbnail_data.autoplaySpeed
				},
				simulateTouch: tpro_thumbnail_data.draggable,
				allowTouchMove: tpro_thumbnail_data.swipe,
				mousewheel: tpro_thumbnail_data.swipeToSlide,
				fadeEffect: {
					crossFade: true,
				},
			});
			galleryContent.controller.control = galleryThumbs;
			galleryThumbs.controller.control = galleryContent;
			if (tpro_thumbnail_data.autoplay === false) {
				galleryThumbs.autoplay.stop()
				galleryContent.autoplay.stop()
			}
			if (tpro_thumbnail_data.pauseOnHover && tpro_thumbnail_data.autoplay) {
				$(contentCarousel).on({
					mouseenter: function () {
						galleryContent.autoplay.stop()
						galleryThumbs.autoplay.stop()
					},
					mouseleave: function () {
						galleryContent.autoplay.start()
						galleryThumbs.autoplay.start()
					}
				});
			}
		}
	});
});
