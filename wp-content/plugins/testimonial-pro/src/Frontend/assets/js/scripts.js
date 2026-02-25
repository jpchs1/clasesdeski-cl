jQuery(document).ready(function ($) {
	/**
	 * Preloader.
	 */
	$('body').find('.sp-testimonial-pro-section').each(function () {
		var _this = $(this),
			custom_id = $(this).attr('id'),
			preloader = _this.data('preloader');

		if ('1' == preloader) {
			var parents_class = $('#' + custom_id).parents('.sp-testimonial-pro-wrapper'),
				parents_siblings_id = parents_class.find('.tpro-preloader').attr('id');
			setTimeout(function () {
				$('#' + parents_siblings_id).css({ 'opacity': 0, 'display': 'none' });
			}, 600);
			$('#' + custom_id).animate({ opacity: 1 }, 600);
		}
	});

	$('.sp-testimonial-pro-wrapper').each(function () {
		var sliderID = $(this).attr('id'),
			testimonial_wrapper_id = '#' + sliderID,
			testimonailData = $("#" + sliderID + " .sp-testimonial-pro-section").data("testimonial"),
			stpmsnry,
			$container,
			tpSwiper,
			tpTicker;

		function tpro_read_more_init() {
			if ($('#' + sliderID + ' .sp-testimonial-pro-read-more.tpro-readmore-expand-true').length > 0) {
				$('#' + sliderID + ' .sp-testimonial-pro-read-more.tpro-readmore-expand-true').each(function (index) {
					var tpro_custom_read_id = $(this).attr('id');
					var sp_tpro_rm_config_text = $(this).parents('.sp-testimonial-pro-wrapper').find('.sp-tpro-rm-config').data('read_more');
					if (sp_tpro_rm_config_text) {
						var tpro_read_more_config = sp_tpro_rm_config_text;
						$('#' + tpro_custom_read_id + ' .tpro-client-testimonial .tpro-read-more:contains(' + tpro_read_more_config.testimonial_read_more_text + ')').trigger('click');
						if (tpro_custom_read_id != '') {
							$(document).find('#' + tpro_custom_read_id + ' .tpro-client-testimonial').curtail({
								limit: parseInt(tpro_read_more_config.testimonial_characters_limit),
								ellipsis: (tpro_read_more_config.testimonial_read_more_ellipsis),
								toggle: true,
								text: [(tpro_read_more_config.testimonial_read_less_text), (tpro_read_more_config.testimonial_read_more_text)]
							});
							// Expand message
							$('#' + tpro_custom_read_id + ' .tpro-client-testimonial .tpro-read-more').on('click', function (e) {
								e.preventDefault();
								$(this).parent().toggleClass('tpro-testimonial-expanded');

								// Check if there is masonry grid on the page and reload it
								if ($('#' + sliderID + ' .sp_testimonial_pro_masonry').length) {
									$('#' + sliderID + ' .sp_testimonial_pro_masonry .sp-tpro-items').masonry();
								}
								// Check if there is slider on the page and reload it
								if ($(".sp-testimonial-pro-section.tpro-layout-slider-standard").length) {
									$(this).closest(".sp-testimonial-pro-section.tpro-layout-slider-standard").trigger('resize');
								}
								// Check if there is isotope on the page and reload it
								if ($(".sp_testimonial_pro_filter").length) {
									$(this).closest(".sp-tpro-isotope-items, .isotope").trigger("resize");
								}

							});

						}
					}
				});
			}
		}

		function testimonial_carousel_int() {
			if ($('#' + sliderID + '.sp-testimonial-carousel.tpro-layout-slider-standard').length > 0) {
				var tpCarousel = $('#' + sliderID + ' .sp-tpCarousel'),
					tpCarouselData = tpCarousel.data('swiper_slider');
				if (!$(tpCarousel).hasClass('swiper-initialized')) {
					if (tpCarouselData.effect == true) {

						var slidePerView = tpCarouselData.slidesPerView.lg_desktop
						if ($(window).width() > 1200) {
							slidePerView = tpCarouselData.slidesPerView.lg_desktop
						} else if ($(window).width() > 980) {
							slidePerView = tpCarouselData.slidesPerView.desktop
						} else if ($(window).width() > 736) {
							slidePerView = tpCarouselData.slidesPerView.laptop
						} else if ($(window).width() > 576) {
							slidePerView = tpCarouselData.slidesPerView.tablet
						} else if ($(window).width() > 320) {
							slidePerView = tpCarouselData.slidesPerView.mobile
						}
						$('#' + sliderID + '.sp-testimonial-carousel.tpro-layout-slider-standard .sp-testimonial-pro-item').css('width', 100 / slidePerView + '%');

						var fade_items = $('#' + sliderID + '.sp-testimonial-carousel.tpro-layout-slider-standard  .swiper-wrapper>.sp-testimonial-pro-item');
						for (var i = 0; i < fade_items.length; i += slidePerView) {
							fade_items.slice(i, i + slidePerView).wrapAll('<div class="swiper-slide"></div>');
						}


						tpSwiper = new Swiper('#' + sliderID + ' .sp-tpCarousel', {
							speed: tpCarouselData.speed,
							slidesPerView: tpCarouselData.slidesPerView.lg_desktop,
							slidesPerGroup: tpCarouselData.slideToScroll.lg_desktop,
							slidesPerColumn: tpCarouselData.slidesRow.lg_desktop,
							spaceBetween: tpCarouselData.spaceBetween,
							loop: tpCarouselData.slidesRow.lg_desktop > "1" ||
								tpCarouselData.slidesRow.desktop > "1" ||
								tpCarouselData.slidesRow.laptop > "1" ||
								tpCarouselData.slidesRow.tablet > "1" ||
								tpCarouselData.slidesRow.mobile > "1"
								? false : tpCarouselData.infinite,
							loopFillGroupWithBlank: true,
							autoHeight: tpCarouselData.slidesRow.lg_desktop > "1" ||
								tpCarouselData.slidesRow.desktop > "1" ||
								tpCarouselData.slidesRow.laptop > "1" ||
								tpCarouselData.slidesRow.tablet > "1" ||
								tpCarouselData.slidesRow.mobile > "1"
								? false : tpCarouselData.adaptiveHeight,
							simulateTouch: tpCarouselData.draggable,
							allowTouchMove: tpCarouselData.swipe,
							effect: 'fade',
							mousewheel: tpCarouselData.swipeToSlide,
							grabCursor: true,
							pagination:
								tpCarouselData.dots == true
									? {
										el: ".swiper-pagination",
										clickable: true,
									}
									: false,
							autoplay: {
								delay: tpCarouselData.autoplaySpeed
							},
							navigation:
								tpCarouselData.arrows == true
									? {
										nextEl: ".tpro-button-next",
										prevEl: ".tpro-button-prev",
									}
									: false,
							fadeEffect: {
								crossFade: true,
							},
							keyboard: {
								enabled: true
							},
						})
					} else {
						tpSwiper = new Swiper('#' + sliderID + ' .sp-tpCarousel', {
							speed: tpCarouselData.speed,
							slidesPerView: tpCarouselData.slidesPerView.lg_desktop,
							slidesPerGroup: tpCarouselData.slideToScroll.lg_desktop,
							slidesPerColumn: tpCarouselData.slidesRow.lg_desktop,
							spaceBetween: tpCarouselData.spaceBetween,
							loop: tpCarouselData.slidesRow.lg_desktop > "1" ||
								tpCarouselData.slidesRow.desktop > "1" ||
								tpCarouselData.slidesRow.laptop > "1" ||
								tpCarouselData.slidesRow.tablet > "1" ||
								tpCarouselData.slidesRow.mobile > "1"
								? false : tpCarouselData.infinite,
							loopFillGroupWithBlank: false,
							autoHeight: tpCarouselData.slidesRow.lg_desktop > "1" ||
								tpCarouselData.slidesRow.desktop > "1" ||
								tpCarouselData.slidesRow.laptop > "1" ||
								tpCarouselData.slidesRow.tablet > "1" ||
								tpCarouselData.slidesRow.mobile > "1"
								? false : tpCarouselData.adaptiveHeight,
							simulateTouch: tpCarouselData.draggable,
							allowTouchMove: tpCarouselData.swipe,
							effect: tpCarouselData.effect == true ? 'fade' : 'slide',
							mousewheel: tpCarouselData.swipeToSlide,
							grabCursor: true,
							pagination:
								tpCarouselData.dots == true
									? {
										el: ".swiper-pagination",
										clickable: true,
									}
									: false,
							autoplay: {
								delay: tpCarouselData.autoplaySpeed
							},
							navigation:
								tpCarouselData.arrows == true
									? {
										nextEl: ".tpro-button-next",
										prevEl: ".tpro-button-prev",
									}
									: false,
							breakpoints: {
								320: {
									slidesPerView: tpCarouselData.slidesPerView.mobile,
									slidesPerGroup: tpCarouselData.slideToScroll.mobile,
									grid: {
										rows: tpCarouselData.slidesRow.mobile,
									},
									navigation:
										tpCarouselData.arrows == true
											? {
												nextEl: ".tpro-button-next",
												prevEl: ".tpro-button-prev",
											}
											: false,
									pagination:
										tpCarouselData.pagination_mobile == true
											? {
												el: ".swiper-pagination",
												clickable: true,
											}
											: false,
								},
								576: {
									slidesPerView: tpCarouselData.slidesPerView.tablet,
									slidesPerGroup: tpCarouselData.slideToScroll.tablet,
									grid: {
										rows: tpCarouselData.slidesRow.tablet,
									}
								},
								736: {
									slidesPerView: tpCarouselData.slidesPerView.laptop,
									slidesPerGroup: tpCarouselData.slideToScroll.laptop,
									grid: {
										rows: tpCarouselData.slidesRow.laptop,
									}
								},
								980: {
									slidesPerView: tpCarouselData.slidesPerView.desktop,
									slidesPerGroup: tpCarouselData.slideToScroll.desktop,
									grid: {
										rows: tpCarouselData.slidesRow.desktop,
									}
								},
								1200: {
									slidesPerView: tpCarouselData.slidesPerView.lg_desktop,
									slidesPerGroup: tpCarouselData.slideToScroll.lg_desktop,
									grid: {
										rows: tpCarouselData.slidesRow.lg_desktop,
									}
								},
							},
							fadeEffect: {
								crossFade: true,
							},
							keyboard: {
								enabled: true
							},
							grid: {
								rows: tpCarouselData.slidesRow.mobile,
							},
						})
					}
					if (tpCarouselData.autoplay === false || (($(window).width() < 576) && tpCarouselData.autoplay_mobile == false)) {
						tpSwiper.autoplay.stop();
					}
					if (tpCarouselData.pauseOnHover && tpCarouselData.autoplay) {
						$(tpCarousel).on({
							mouseenter: function () {
								tpSwiper.autoplay.stop();
							},
							mouseleave: function () {
								tpSwiper.autoplay.start();
							}
						});
					}
				}
			}
			if ($('#' + sliderID + '.sp-testimonial-carousel.tpro-layout-slider-ticker').length > 0) {
				var tpCarouselData = $('#' + sliderID + ' .tpro-layout-slider-ticker').data('bx_ticker'),
					tickerDirection = tpCarouselData.rtl == true ? 'prev' : 'next',
					ticker_carousel_items = $('#' + sliderID + ' .swiper-wrapper').find('.sp-testimonial-pro-item:not(.bx-clone)').length,
					tickerSpeed = 480 * ticker_carousel_items * tpCarouselData.speed / 1000;
				// Ticker Direction class add.
				if (tpCarouselData.rtl == true) {
					$('#' + sliderID + ' .swiper-container').addClass('sp-testimonial-dir');
				}
				var maxSlides,
					width = $(window).width();

				if (width < 480) {
					maxSlides = tpCarouselData.slidesPerView.mobile;
				} else {
					maxSlides = tpCarouselData.slidesPerView.lg_desktop;
				}
				tpTicker = jQuery('#' + sliderID + ' .swiper-wrapper').bxSlider({
					slideWidth: 480,
					slideMargin: 10,
					minSlides: maxSlides,
					maxSlides: maxSlides,
					moveSlides: 1,
					easing: 'linear',
					ticker: true,
					speed: tickerSpeed,
					tickerHover: true,
					autoDirection: tickerDirection,
				});
			}
		}
		testimonial_carousel_int();
		tpro_read_more_init();
		/* === Masonry === */
		function trpo_masonry_init() {
			var masonry = $("#" + sliderID + " .sp_testimonial_pro_masonry .sp-tpro-items");
			if (masonry.length > 0) {
				masonry.masonry(); // Masonry
				masonry.imagesLoaded(function () {
					masonry.masonry(); // Masonry
				});
				stpmsnry = masonry.data('masonry');
			}
		};
		trpo_masonry_init();
		/* == MagnificPopup == */
		function tpro_popup_init() {
			if (testimonailData.thumbnailSlider == false && testimonailData.videoIcon == 1) {
				$("#" + sliderID + ' .sp-tpro-video').magnificPopup({
					type: 'iframe',
					mainClass: 'mfp-fade sp-testimonial-lightbox',
					preloader: false,
					fixedContentPos: false
				});
			}
			if (testimonailData.thumbnailSlider == false && testimonailData.lightboxIcon == 1) {
				$("#" + sliderID + ' .sp-tpro-img').magnificPopup({
					type: 'image',
					mainClass: 'mfp-fade sp-testimonial-lightbox',
				});
			}
		}
		tpro_popup_init();
		// ----------------------------------------------
		//  Isotope Filter
		// ----------------------------------------------
		if ($("#" + sliderID + " .sp-testimonial-pro-section.sp_testimonial_pro_filter").length > 0) {
			var filter_id = jQuery(this).find('.sp_testimonial_pro_filter').attr('id');
			var tpro_filter_config = jQuery(this).find('.sp_testimonial_pro_filter').data('filter_mode');
			var winDow = jQuery(window);
			var $filter = jQuery('#' + filter_id + ' .sp-tpro-filter');
			$container = jQuery('#' + filter_id + ' .sp-tpro-isotope-items');
			try {
				$container.imagesLoaded(function () {
					$container.show();
					$container.isotope({
						filter: '*',
						layoutMode: (tpro_filter_config),
						animationOptions: {
							duration: 750,
							easing: 'linear'
						}
					});
				});
			} catch (err) { }

			winDow.on('resize', function () {
				var selector = $filter.find('a.active').attr('data-filter');
				try {
					$container.isotope({
						filter: selector,
						animationOptions: {
							duration: 750,
							easing: 'linear',
							queue: false,
						}
					});
				} catch (err) { }
				return false;
			});

			$filter.find('a').on('click', function () {
				var selector = jQuery(this).attr('data-filter');
				try {
					$container.isotope({
						filter: selector,
						animationOptions: {
							duration: 750,
							easing: 'linear',
							queue: false
						}
					});
				} catch (err) {

				}
				return false;
			});
			var filterItemA = jQuery('#' + filter_id + ' .sp-tpro-filter a');
			if (!filterItemA.hasClass('active')) {
				setTimeout(function () {
					jQuery('#' + filter_id + ' .sp-tpro-filter li:nth-child(1) a').addClass('active').trigger('click');
				}, 100)
			}
			filterItemA.on('click', function () {
				var $this = jQuery(this);
				if (!$this.hasClass('active')) {
					filterItemA.removeClass('active');
					$this.addClass('active');
				}
			});
		}

		// Same height option.
		function setSameHeight(testimonials) {
			var maxHeight = 0;
			// Loop all Testimonial and check height, if the height bigger than max then save it.
			for (var i = 0; i < testimonials.length; i++) {
				if (maxHeight < $(testimonials[i]).outerHeight()) {
					maxHeight = $(testimonials[i]).outerHeight();
				}
			}
			// Set ALL Testimonial to this height.
			for (var i = 0; i < testimonials.length; i++) {
				$(testimonials[i]).outerHeight(maxHeight);
			}
		}
		function same_height_item() {
			var parent_attr = $('#' + sliderID).data('same-height');
			if (parent_attr) {
				var testimonial_meta = $('#' + sliderID + ' .sp-testimonial-pro-item .tpro-testimonial-meta-area');
				var testimonial_content = $('#' + sliderID + ' .sp-testimonial-pro-item .tpro-testimonial-content-area');
				var testimonial_item = $('#' + sliderID + ' .sp-testimonial-pro-item .sp-testimonial-pro');
				setTimeout(function () {
					setSameHeight(testimonial_meta);
					setSameHeight(testimonial_content);
					setSameHeight(testimonial_item);
				}, 100)
			}
		}
		same_height_item();

		// Ajax search testimonial scripts.
		var search_value = '';
		var filter_slug = '';
		var shortcode_id = $('#' + sliderID).data('testimonial-id');
		function testimonial_search_actions() {
			var EndingMessage = $(testimonial_wrapper_id).find('.infinite-scroll-last');
			$.ajax({
				type: 'POST',
				url: testimonial_vars.ajax_url,
				data: {
					value: search_value,
					slug: filter_slug,
					generator_id: shortcode_id,
					action: 'search_testimonial',
					page: 1,
					nonce: testimonial_vars.nonce,
				},
				success: function (response) {
					var $data = response ? $(response) : `<h4 style="text-align:center;width:100%">${testimonial_vars.not_found}</h4>`;
					var testimonial_data = $(testimonial_wrapper_id + ' .sp-tpro-items,' + testimonial_wrapper_id + ' .sp-tpCarousel .swiper-wrapper').css('opacity', 0);

					var layout = $(testimonial_wrapper_id).data('layout');
					// Carousel destroy.
					if ($('#' + sliderID + ".sp-testimonial-carousel").length > 0) {
						if ($('#' + sliderID + '.tpro-layout-slider-ticker').length > 0) {
							tpTicker.destroySlider();
						} else {
							tpSwiper.destroy();
						}
					}

					if ('filter' === layout) {
						$("#" + sliderID + " .sp-testimonial-pro-section.sp_testimonial_pro_filter")
						if ($("#" + sliderID + " .sp-testimonial-pro-section.sp_testimonial_pro_filter").length > 0) {
							$container.isotope('remove', $container.isotope('getItemElements'));
							$container.html($data).imagesLoaded(function () {
								$container.isotope('insert', $data);
								$container.isotope('layout');
							})
						}
					} else {
						testimonial_data.html($data);
						if ("slider" === layout) {
							testimonial_carousel_int();
						}
						// Masonry layout.
						if ("masonry" === layout) {
							var $post_wrapper = $("#" + sliderID + " .sp_testimonial_pro_masonry .sp-tpro-items");
							$post_wrapper.masonry("destroy");
							$post_wrapper.html($data).imagesLoaded(function () {
								$post_wrapper.masonry();
							});
							trpo_masonry_init();
						}
					}
					if ($('[data-remodal-id]').length > 0) {
						$('[data-remodal-id]').remodal();
					}
					var video = $("#" + sliderID + " .sp-testimonial-pro-section").find('.sptestimonial-lazy');
					for (var i = 0, length = video.length; i < length; i++) {
						lazyLoad(video[i]);
					}
					tpro_popup_init();
					same_height_item();
					tpro_read_more_init();
					image_zoom_effects();
					if (search_value) {
						$(testimonial_wrapper_id).find('.tpro-items-load-more,.sp-tpro-pagination').css("display", "none");
					}
					if ('' == search_value) {
						$(testimonial_wrapper_id).find('.tpro-items-load-more,.sp-tpro-pagination').css("display", "inline-block");
					}
					// Update ajax pagination html.
					var pagination_wrapper = $(document).find(testimonial_wrapper_id + ' .sp-tpro-items .filter-pagination');
					var pagination_html = pagination_wrapper.html();
					$(testimonial_wrapper_id + ' .sp-testimonial-ajax-pagination ').html(pagination_html);
					var totalPage = $(document).find('.sp-testimonial-ajax-pagination a:not(.next, .prev)', testimonial_wrapper_id).length;
					var buttontext = $(document).find('.tpro-items-load-more', testimonial_wrapper_id).data('buttontext');
					if (totalPage > 0) {
						$('.tpro-items-load-more', testimonial_wrapper_id).html('<span>' + buttontext + '</span>');
						EndingMessage.hide();
					} else {
						$('.tpro-items-load-more span', testimonial_wrapper_id).hide();
						EndingMessage.show();
					}

					pagination_wrapper.remove();
					testimonial_data.animate({
						opacity: '1',
					}, "slow");
				}
			});
		}
		var LoadMoreButton_text;
		if ($("#" + sliderID + " .ajax_load_more").length > 0 || $("#" + sliderID + " .infinite_scroll").length > 0) {
			var EndingMessage = $(testimonial_wrapper_id).find('.infinite-scroll-last');
			var ErrorMessage = $(testimonial_wrapper_id).find('.infinite-scroll-error');
			var PageLoadStatus = $(testimonial_wrapper_id).find('.page-load-status .loader-ellips.infinite-scroll-request');

			LoadMoreButton_text = $(testimonial_wrapper_id).find('.tpro-items-load-more').data('buttontext')
			$('.tpro-items-load-more', testimonial_wrapper_id).attr('data-processing', 0);
			$(testimonial_wrapper_id + " .ajax_load_more, " + testimonial_wrapper_id + " .infinite_scroll").on('click', '.tpro-items-load-more span', function (e) {
				e.preventDefault();
				e.stopPropagation();
				e.stopImmediatePropagation();
				PageLoadStatus.show();
				var that = $(this);
				var totalPage = $('.sp-testimonial-ajax-pagination a:not(.next, .prev)', testimonial_wrapper_id).length;
				var currentPage = parseInt($('.sp-testimonial-ajax-pagination .current:not(.next, .prev)', testimonial_wrapper_id).data('page'));
				var page = currentPage + 1;
				$('.tpro-items-load-more', testimonial_wrapper_id + ' .infinite_scroll').attr('data-processing', 1);
				$.ajax({
					type: 'POST',
					url: testimonial_vars.ajax_url,
					data: {
						value: search_value,
						slug: filter_slug,
						generator_id: shortcode_id,
						action: 'search_testimonial',
						page: page,
						nonce: testimonial_vars.nonce,
					},
					success: function (response) {
						var $data = $(response);
						var testimonial_data = $(testimonial_wrapper_id + ' .sp-tpro-items,' + testimonial_wrapper_id + ' .sp-tpCarousel .swiper-wrapper');
						var layout = $(testimonial_wrapper_id).data('layout');
						if ('filter' === layout) {
							$("#" + sliderID + " .sp-testimonial-pro-section.sp_testimonial_pro_filter")
							if ($("#" + sliderID + " .sp-testimonial-pro-section.sp_testimonial_pro_filter").length > 0) {
								$container.append($data).imagesLoaded(function () {
									$container.isotope('insert', $data);
									$container.isotope('layout');
								})
							}
						} else {
							testimonial_data.append($data);
							if ("slider" === layout) {
								testimonial_carousel_int();
							}
							// Masonry layout.
							if ("masonry" === layout) {
								var $post_wrapper = $("#" + sliderID + " .sp_testimonial_pro_masonry .sp-tpro-items");
								$post_wrapper.masonry("destroy");
								$post_wrapper.append($data).imagesLoaded(function () {
									$post_wrapper.masonry();
								});
								trpo_masonry_init();
							}
						}
						if ($('[data-remodal-id]').length > 0) {
							$('[data-remodal-id]').remodal();
						}
						var video = $("#" + sliderID + " .sp-testimonial-pro-section").find('.sptestimonial-lazy');
						for (var i = 0, length = video.length; i < length; i++) {
							lazyLoad(video[i]);
						}
						tpro_popup_init();
						same_height_item();
						tpro_read_more_init();
						image_zoom_effects();

						var pagination_wrapper = $(document).find(testimonial_wrapper_id + ' .sp-tpro-items .filter-pagination');
						var pagination_html = pagination_wrapper.html();
						$(testimonial_wrapper_id + ' .sp-testimonial-ajax-pagination ').html(pagination_html);
						pagination_wrapper.remove();

						$('.page-numbers', testimonial_wrapper_id).removeClass('current');
						$('.page-numbers', testimonial_wrapper_id).each(function () {
							$('.sp-testimonial-ajax-pagination  a[data-page=' + page + ']', testimonial_wrapper_id).addClass(
								'current'
							)
						});

						$('.next', testimonial_wrapper_id).removeClass('current')
						$('.prev', testimonial_wrapper_id).removeClass('current')
						$('.sp-testimonial-ajax-pagination a.current', testimonial_wrapper_id).each(function () {

							if (parseInt($(this).data('page')) === totalPage) {
								$('.next', testimonial_wrapper_id).addClass('current');
							}
							if (parseInt($(this).data('page')) === 1) {
								$('.prev', testimonial_wrapper_id).addClass('current');
							}
						})

						var noFoundTestimonial = $('.sp-not-found-any-testimonial', testimonial_wrapper_id).length;
						if (totalPage == page || noFoundTestimonial) {
							$('.tpro-items-load-more span', testimonial_wrapper_id).remove();
							$('.sp-not-found-any-testimonial', testimonial_wrapper_id).remove();
							EndingMessage.show();
						}
						PageLoadStatus.hide();
						$('.tpro-items-load-more', testimonial_wrapper_id).attr('data-processing', 0);

					}
				});
			});
		}
		if ($(testimonial_wrapper_id + " .infinite_scroll").length > 0) {
			var bufferBefore = Math.abs(10);
			var row = $(testimonial_wrapper_id + ' .sp-tpro-items,' + testimonial_wrapper_id + ' .sp-tpCarousel .swiper-wrapper,' + testimonial_wrapper_id + ' .sp-tpro-isotope-items');
			if (row.length) {
				$(window).on('scroll', function (e) {
					e.preventDefault();
					var TopAndContent = row.offset().top + row.outerHeight();
					var totalPage = $('.sp-testimonial-ajax-pagination a:not(.next, .prev)', testimonial_wrapper_id).length;
					var areaLeft = TopAndContent - $(window).scrollTop();
					totalPage = totalPage + 1;
					if (areaLeft - bufferBefore < $(window).height()) {
						if ($('.tpro-items-load-more', testimonial_wrapper_id).attr('data-processing') == 0) {
							$('.tpro-items-load-more span', testimonial_wrapper_id).trigger('click');
						}
					}

				})
			}

		}

		// Ajax number pagination.
		if ($(testimonial_wrapper_id + " .ajax_pagination").length > 0) {
			$(testimonial_wrapper_id + " .ajax_pagination").on('click', '.sp-testimonial-ajax-pagination a', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var that = $(this);
				var totalPage = $('.sp-testimonial-ajax-pagination a:not(.next, .prev)', testimonial_wrapper_id)
					.length,
					currentPage = parseInt(
						$('.sp-testimonial-ajax-pagination .current:not(.next, .prev)', testimonial_wrapper_id).data('page')),
					page = parseInt(that.data('page'));
				if (that.hasClass('next')) {
					if (totalPage > currentPage) {
						var page = currentPage + 1
					} else {
						return
					}
				}
				if (that.hasClass('prev')) {
					if (currentPage > 1) {
						var page = currentPage - 1
					} else {
						return
					}
				}
				$.ajax({
					type: 'POST',
					url: testimonial_vars.ajax_url,
					data: {
						value: search_value,
						slug: filter_slug,
						generator_id: shortcode_id,
						action: 'search_testimonial',
						page: page,
						nonce: testimonial_vars.nonce,
					},
					success: function (response) {
						var $data = $(response);
						var testimonial_data = $(testimonial_wrapper_id + ' .sp-tpro-items,' + testimonial_wrapper_id + ' .sp-tpCarousel .swiper-wrapper').css('opacity', 0);

						var layout = $(testimonial_wrapper_id).data('layout');
						// Carousel destroy.
						if ('filter' === layout) {
							$("#" + sliderID + " .sp-testimonial-pro-section.sp_testimonial_pro_filter")
							if ($("#" + sliderID + " .sp-testimonial-pro-section.sp_testimonial_pro_filter").length > 0) {
								$container.isotope('remove', $container.isotope('getItemElements'));
								$container.html($data).imagesLoaded(function () {
									$container.isotope('insert', $data);
									$container.isotope('layout');
								})
							}
						} else {
							testimonial_data.html($data);
							if ("slider" === layout) {
								testimonial_carousel_int();
							}
							// Masonry layout.
							if ("masonry" === layout) {
								var $post_wrapper = $("#" + sliderID + " .sp_testimonial_pro_masonry .sp-tpro-items");
								$post_wrapper.masonry("destroy");
								$post_wrapper.html($data).imagesLoaded(function () {
									$post_wrapper.masonry();
								});
								trpo_masonry_init();
							}
						}
						if ($('[data-remodal-id]').length > 0) {
							$('[data-remodal-id]').remodal();
						}
						var video = $("#" + sliderID + " .sp-testimonial-pro-section").find('.sptestimonial-lazy');
						for (var i = 0, length = video.length; i < length; i++) {
							lazyLoad(video[i]);
						}
						tpro_popup_init();
						same_height_item();
						tpro_read_more_init();
						image_zoom_effects();

						var pagination_wrapper = $(document).find(testimonial_wrapper_id + ' .sp-tpro-items .filter-pagination');
						var pagination_html = pagination_wrapper.html();
						$(testimonial_wrapper_id + ' .sp-testimonial-ajax-pagination ').html(pagination_html);
						pagination_wrapper.remove();

						$('.page-numbers', testimonial_wrapper_id).removeClass('current');
						$('.page-numbers', testimonial_wrapper_id).each(function () {
							$('.sp-testimonial-ajax-pagination  a[data-page=' + page + ']', testimonial_wrapper_id).addClass(
								'current'
							)
						});

						$('.next', testimonial_wrapper_id).removeClass('current')
						$('.prev', testimonial_wrapper_id).removeClass('current')
						$('.sp-testimonial-ajax-pagination a.current', testimonial_wrapper_id).each(function () {

							if (parseInt($(this).data('page')) === totalPage) {
								$('.next', testimonial_wrapper_id).addClass('current');
							}
							if (parseInt($(this).data('page')) === 1) {
								$('.prev', testimonial_wrapper_id).addClass('current');
							}
						})
						testimonial_data.css('opacity', 1);
					}
				});
			});
		}

		// Ajax filter first button click when all button is hidden.
		var $buttonGroup = $("#" + sliderID + ' .testimonial-ajax-live-filter .fltr-controls:nth-child(1).is-checked');
		setTimeout(function () {
			$buttonGroup.trigger('click');
		}, 300);
		// Ajax filter with button style.
		$("#" + sliderID + ' .testimonial-ajax-live-filter.filters-button-group').on('click', 'button', function (event) {
			event.preventDefault();
			event.stopPropagation();
			var $buttonGroup = $("#" + sliderID + ' .filters-button-group');
			$buttonGroup.find('.is-checked').removeClass('is-checked');
			$(this).addClass('is-checked');
			filter_slug = $(this).data('slug');
			testimonial_search_actions();
		});
		// Live filter with dropdown style.
		$("#" + sliderID + " .testimonial-ajax-live-filter .filterSelect").on(
			'change',
			function (e) {
				e.preventDefault();
				e.stopPropagation();
				filter_slug = $(this).val();
				testimonial_search_actions();
			});
		// The `makeDelay` function takes two arguments:
		// - `fn`: a function that will be executed after a delay.
		// - `ms`: the delay time in milliseconds.
		function makeDelay(fn, ms) {
			let timer = 0
			// Return a new function that can be called with any number of arguments.
			return function (...args) {
				clearTimeout(timer)
				timer = setTimeout(fn.bind(this, ...args), ms || 0)
			}
		}
		// Bind a 'keyup' event to the document, with a selector that targets an element
		// with an ID of 'testimonial_search-' followed by a shortcode ID.
		$(document).on('keyup', '#testimonial_search-' + sliderID, makeDelay(function () {
			search_value = $(this).val();
			// Call the `testimonial_search_actions` function, passing in the `search_value`.
			testimonial_search_actions();
			image_zoom_effects();
		}, 500));
		function image_zoom_effects() {
			$is_zoom_active = $("#" + sliderID).is('.zoom_in,.zoom_out');
			if ($is_zoom_active) {
				$("#" + sliderID + " .tpro-client-image img").each(function () {
					var height = $(this).attr('height');
					var width = $(this).attr('width');
					$(this).parents('.tpro-client-image').css({
						'height': height,
						'width': width,
						'overflow': 'hidden'
					});
					$(this).parents('.tpro-client-image.tpro-image-style-three').css('border-radius', '50%');
				});
			}
		}
		image_zoom_effects();

	});


	// lazyLoad scripts for the Video Testimonial.
	function lazyLoad(element) {
		var embed = element.dataset.embed;
		var type = element.dataset.type;
		if ('width' in element.dataset) {
			var width = element.dataset.width;
			element.style.maxWidth = width + 'px';
		}
		var button = document.createElement('button');
		var iframe = document.createElement('iframe');
		element.appendChild(button);
		element.addEventListener('click', function () {
			iframe.setAttribute('class', 'sp-testimonial-iframe');
			iframe.setAttribute('frameborder', '0');
			iframe.setAttribute('playsinline', '1');
			iframe.setAttribute('allowFullScreen', '1');
			iframe.setAttribute('allow', 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture');
			if ('vimeo' == type) {
				iframe.setAttribute('src', 'https://player.vimeo.com/video/' + embed);
			} else {
				iframe.setAttribute('src', 'https://www.youtube.com/embed/' + embed + '?autoplay=1');
			}
			element.appendChild(iframe);
			element.querySelector('img').classList.add('fadeout');
			button.remove();
		});
	}
	var video = document.getElementsByClassName('sptestimonial-lazy');
	for (var i = 0, length = video.length; i < length; i++) {
		lazyLoad(video[i]);
	}
});