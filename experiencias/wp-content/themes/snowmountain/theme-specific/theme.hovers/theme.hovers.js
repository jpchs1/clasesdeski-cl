// Buttons decoration (add 'hover' class)
// Attention! Not use cont.find('selector')! Use jQuery('selector') instead!

jQuery(document).on('action.init_hidden_elements', function(e, cont) {
	"use strict";
	if (SNOWMOUNTAIN_STORAGE['button_hover'] && SNOWMOUNTAIN_STORAGE['button_hover']!='default') {
		jQuery('button:not(.search_submit):not([class*="sc_button_hover_"]):not([class*="xdsoft_"]):not([class*="datepicker-button"]),\
				.theme_button:not([class*="sc_button_hover_"]),\
				.sc_button:not([class*="sc_button_simple"]):not([class*="sc_button_hover_"]),\
				.sc_form_field button:not([class*="sc_button_hover_"]),\
				.sc_price_link:not([class*="sc_button_hover_"]),\
				.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_"]),\
				.snowmountain_tabs .snowmountain_tabs_titles li a:not([class*="sc_button_hover_"]),\
				.sc_action_slider .sc_action_item_link,\
				.hover_shop_buttons .icons a:not([class*="sc_button_hover_style_"]),\
				.woocommerce #respond input#submit:not([class*="sc_button_hover_"]),\
				.woocommerce .button:not([class*="shop_"]):not([class*="sc_button_hover_"]),\
				.woocommerce-page .button:not([class*="shop_"]):not([class*="sc_button_hover_"]),\
				.wp-block-button:not(.is-style-outline) .wp-block-button__link,\
				#btn-buy,\
				#btn-pay,\
				#buddypress a.button:not([class*="sc_button_hover_"]),\
				.cq-bgbutton-container a\
				').addClass('sc_button_hover_just_init sc_button_hover_'+SNOWMOUNTAIN_STORAGE['button_hover']);
		if (SNOWMOUNTAIN_STORAGE['button_hover']!='arrow') {
			jQuery('input[type="submit"]:not([class*="sc_button_hover_"]),\
					input[type="button"]:not([class*="sc_button_hover_"]),\
					.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:not([class*="sc_button_hover_"]),\
					.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a,\
					.woocommerce nav.woocommerce-pagination ul li a:not([class*="sc_button_hover_"]),\
					.tribe-events-button:not([class*="sc_button_hover_"]),\
					#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a:not([class*="sc_button_hover_"]),\
					.tribe-bar-mini #tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a:not([class*="sc_button_hover_"]),\
					.tribe-events-cal-links a:not([class*="sc_button_hover_"]),\
					.tribe-events-sub-nav li a:not([class*="sc_button_hover_"]),\
					.isotope_filters_button:not([class*="sc_button_hover_"]),\
					.trx_addons_scroll_to_top:not([class*="sc_button_hover_"]),\
					.sc_promo_modern .sc_promo_link2:not([class*="sc_button_hover_"]),\
					.cq-bgbutton-container a\
					').addClass('sc_button_hover_just_init sc_button_hover_'+SNOWMOUNTAIN_STORAGE['button_hover']);
		}
		// Add alter styles of buttons
		jQuery('.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_style_"]),\
				.wpcf7 input[type="submit"],\
				.mc4wp-form .mc4wp-form-fields input[type="submit"],\
				.single-product ul.products li.product .post_data .button:not([class*="sc_button_hover_style_"])\
				').addClass('sc_button_hover_just_init sc_button_hover_style_inverse');
		jQuery('.woocommerce #respond input#submit.alt:not([class*="sc_button_hover_style_"])\
				').addClass('sc_button_hover_just_init sc_button_hover_style_hover');
		jQuery('.sidebar .trx_addons_tabs .trx_addons_tabs_titles li a:not([class*="sc_button_hover_style_"]),\
				.snowmountain_tabs .snowmountain_tabs_titles li a:not([class*="sc_button_hover_style_"])\
				').addClass('sc_button_hover_just_init sc_button_hover_style_alterbd');
		jQuery('.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:not([class*="sc_button_hover_style_"]),\
				.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a:not([class*="sc_button_hover_style_"]),\
				.sc_action_item_event .sc_action_item_link:not([class*="sc_button_hover_style_"]),\
				.trx_addons_video_player.with_cover .video_hover:not([class*="sc_button_hover_style_"]),\
				.trx_addons_tabs .trx_addons_tabs_titles li a:not([class*="sc_button_hover_style_"])\
				').addClass('sc_button_hover_just_init sc_button_hover_style_dark');
		// Remove just init hover class
		setTimeout(function() {
			jQuery('.sc_button_hover_just_init').removeClass('sc_button_hover_just_init');
			}, 500);
		// Remove hover class
		jQuery('.mejs-controls button,\
				.mfp-close,\
				.sc_button_bg_image,\
				.hover_shop_buttons a,\
				.pswp__button,\
				.tribe-events-c-subscribe-dropdown__button-text,\
				.sc_layouts_row_type_narrow .sc_button\
				').removeClass('sc_button_hover_'+SNOWMOUNTAIN_STORAGE['button_hover']);

	}
	
});