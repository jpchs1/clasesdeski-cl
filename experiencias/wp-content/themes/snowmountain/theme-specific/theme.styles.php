<?php
/**
 * Generate custom EOT
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

// Return EOT with custom colors and fonts
if (!function_exists('snowmountain_customizer_get_css')) {

	function snowmountain_customizer_get_css($colors=null, $fonts=null, $remove_spaces=true, $only_scheme='') {

		$css = array(
			'fonts' => '',
			'colors' => ''
		);
		
		// Theme fonts
		//---------------------------------------------
		if ($fonts === null) {
			$fonts = snowmountain_get_theme_fonts();
		}
		
		if ($fonts) {

			// Make theme-specific fonts rules
			$fonts = snowmountain_customizer_add_theme_fonts($fonts);

			$rez = array();
			$rez['fonts'] = <<<EOT

body {
	{$fonts['p_font-family']}
	{$fonts['p_font-size']}
	{$fonts['p_font-weight']}
	{$fonts['p_font-style']}
	{$fonts['p_line-height']}
	{$fonts['p_text-decoration']}
	{$fonts['p_text-transform']}
	{$fonts['p_letter-spacing']}
}
p, ul, ol, dl, blockquote, address,
.post_item_single .wp-block-button, 
.post_item_single .wp-block-cover, 
.post_item_single .wp-block-image, 
.post_item_single .wp-block-video, 
.post_item_single .wp-block-search, 
.post_item_single .wp-block-archives, 
.post_item_single .wp-block-categories, 
.post_item_single .wp-block-calendar, 
.post_item_single .wp-block-media-text,
.post_item_single figure.wp-block-gallery,
.post_item_single .wp-block-group.has-background,
.wp-block-group.has-background .wp-block-group__inner-container > * {
	{$fonts['p_margin-top']}
	{$fonts['p_margin-bottom']}
}

h1 {
	{$fonts['h1_font-family']}
	{$fonts['h1_font-size']}
	{$fonts['h1_font-weight']}
	{$fonts['h1_font-style']}
	{$fonts['h1_line-height']}
	{$fonts['h1_text-decoration']}
	{$fonts['h1_text-transform']}
	{$fonts['h1_letter-spacing']}
	{$fonts['h1_margin-top']}
	{$fonts['h1_margin-bottom']}
}
h2 {
	{$fonts['h2_font-family']}
	{$fonts['h2_font-size']}
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_text-transform']}
	{$fonts['h2_letter-spacing']}
	{$fonts['h2_margin-top']}
	{$fonts['h2_margin-bottom']}
}
h3 {
	{$fonts['h3_font-family']}
	{$fonts['h3_font-size']}
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_text-transform']}
	{$fonts['h3_letter-spacing']}
	{$fonts['h3_margin-top']}
	{$fonts['h3_margin-bottom']}
}
h4 {
	{$fonts['h4_font-family']}
	{$fonts['h4_font-size']}
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_text-transform']}
	{$fonts['h4_letter-spacing']}
	{$fonts['h4_margin-top']}
	{$fonts['h4_margin-bottom']}
}
h5 {
	{$fonts['h5_font-family']}
	{$fonts['h5_font-size']}
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
	{$fonts['h5_margin-top']}
	{$fonts['h5_margin-bottom']}
}
h6 {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-weight']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_text-transform']}
	{$fonts['h6_letter-spacing']}
	{$fonts['h6_margin-top']}
	{$fonts['h6_margin-bottom']}
}

.select2-container--default .select2-selection--single,
input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="search"],
input[type="password"],
textarea,
textarea.wp-editor-area,
.select_container,
select,
.select_container select {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}

button,
input[type="button"],
input[type="reset"],
input[type="submit"],
.theme_button,
.gallery_preview_show .post_readmore,
.more-link,
.snowmountain_tabs .snowmountain_tabs_titles li a,
.cq-bgbutton-container a {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
.widget .widget_title, .widget .widgettitle,
.top_panel .slider_engine_revo .slide_title {
	{$fonts['h1_font-family']}
}

blockquote,
mark, ins,
.logo_text,
.post_price.price,
.theme_scroll_down {
	{$fonts['h5_font-family']}
}

.post_meta {
	{$fonts['info_font-family']}
	{$fonts['info_font-size']}
	{$fonts['info_font-weight']}
	{$fonts['info_font-style']}
	{$fonts['info_line-height']}
	{$fonts['info_text-decoration']}
	{$fonts['info_text-transform']}
	{$fonts['info_letter-spacing']}
	{$fonts['info_margin-top']}
	{$fonts['info_margin-bottom']}
}

em, i,
.post-date, .rss-date 
.post_date, .post_meta_item, .post_counters_item,
.comments_list_wrap .comment_date,
.comments_list_wrap .comment_time,
.comments_list_wrap .comment_counters,
.top_panel .slider_engine_revo .slide_subtitle,
.logo_slogan,
fieldset legend,
figure figcaption,
.wp-caption .wp-caption-text,
.wp-caption .wp-caption-dd,
.wp-caption-overlay .wp-caption .wp-caption-text,
.wp-caption-overlay .wp-caption .wp-caption-dd,
.format-audio .post_featured .post_audio_author,
.trx_addons_audio_player .audio_author,
.post_item_single .post_content .post_meta,
.author_bio .author_link,
.comments_list_wrap .comment_posted,
.comments_list_wrap .comment_reply {
	{$fonts['info_font-family']}
}
.search_wrap .search_results .post_meta_item,
.search_wrap .search_results .post_counters_item,
.wp-block-calendar th, .wp-block-calendar td, 
.wp-block-calendar caption, .xdsoft_datetimepicker,
.tooltipster-base.tooltipster-shadow .tooltipster-content .title,
.tooltipster-base.tooltipster-shadow .tooltipster-content .info,
.tooltipster-base.tooltipster-shadow .tooltipster-content .info+p {
	{$fonts['p_font-family']}
}

.logo_text {
	{$fonts['logo_font-family']}
	{$fonts['logo_font-size']}
	{$fonts['logo_font-weight']}
	{$fonts['logo_font-style']}
	{$fonts['logo_line-height']}
	{$fonts['logo_text-decoration']}
	{$fonts['logo_text-transform']}
	{$fonts['logo_letter-spacing']}
}
.logo_footer_text {
	{$fonts['logo_font-family']}
}

.menu_main_nav_area {
	{$fonts['menu_font-size']}
	{$fonts['menu_line-height']}
}
.menu_main_nav > li,
.menu_main_nav > li > a {
	{$fonts['menu_font-family']}
	{$fonts['menu_font-weight']}
	{$fonts['menu_font-style']}
	{$fonts['menu_text-decoration']}
	{$fonts['menu_text-transform']}
	{$fonts['menu_letter-spacing']}
}
.menu_main_nav > li ul,
.menu_main_nav > li ul > li,
.menu_main_nav > li ul > li > a {
	{$fonts['submenu_font-family']}
	{$fonts['submenu_font-size']}
	{$fonts['submenu_font-weight']}
	{$fonts['submenu_font-style']}
	{$fonts['submenu_line-height']}
	{$fonts['submenu_text-decoration']}
	{$fonts['submenu_text-transform']}
	{$fonts['submenu_letter-spacing']}
}
.menu_mobile .menu_mobile_nav_area > ul > li,
.menu_mobile .menu_mobile_nav_area > ul > li > a {
	{$fonts['menu_font-family']}
}
.menu_mobile .menu_mobile_nav_area > ul > li li,
.menu_mobile .menu_mobile_nav_area > ul > li li > a {
	{$fonts['submenu_font-family']}
}



/* Custom Headers */
.sc_layouts_row,
.sc_layouts_row input[type="text"] {
	{$fonts['menu_font-family']}
	{$fonts['menu_font-size']}
	{$fonts['menu_font-weight']}
	{$fonts['menu_font-style']}
	{$fonts['menu_line-height']}
}
.sc_layouts_row .sc_button_wrap .sc_button {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
.sc_layouts_menu_nav > li,
.sc_layouts_menu_nav > li > a {
	{$fonts['menu_font-family']}
	{$fonts['menu_font-weight']}
	{$fonts['menu_font-style']}
	{$fonts['menu_text-decoration']}
	{$fonts['menu_text-transform']}
	{$fonts['menu_letter-spacing']}
}
.sc_layouts_menu_popup .sc_layouts_menu_nav > li,
.sc_layouts_menu_popup .sc_layouts_menu_nav > li > a,
.sc_layouts_menu_nav > li ul,
.sc_layouts_menu_nav > li ul > li,
.sc_layouts_menu_nav > li ul > li > a {
	{$fonts['submenu_font-family']}
	{$fonts['submenu_font-size']}
	{$fonts['submenu_font-weight']}
	{$fonts['submenu_font-style']}
	{$fonts['submenu_line-height']}
	{$fonts['submenu_text-decoration']}
	{$fonts['submenu_text-transform']}
	{$fonts['submenu_letter-spacing']}
}

EOT;
			$rez = apply_filters('snowmountain_filter_get_css', $rez, false, $fonts, '');
			$css['fonts'] = $rez['fonts'];

			
			// Border radius
			//--------------------------------------
			$rad = snowmountain_get_border_radius();
			$rad50 = ' '.$rad != ' 0' ? '50%' : 0;
			$css['fonts'] .= <<<EOT

/* Buttons */
button,
input[type="button"],
input[type="reset"],
input[type="submit"],
.theme_button,
.post_item .more-link,
.gallery_preview_show .post_readmore,

/* Fields */
input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="password"],
input[type="search"],
select,
.select_container,
textarea,
.wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper,
/* Search fields */
.widget_search .search-field,
.woocommerce.widget_product_search .search_field,
.widget_display_search #bbp_search,
#bbpress-forums #bbp-search-form #bbp_search,

/* Comment fields */
.comments_wrap .comments_field input,
.comments_wrap .comments_field textarea,

/* Tags cloud */
.widget_product_tag_cloud a,
.widget_tag_cloud a,
.wp-block-tag-cloud a {
	-webkit-border-radius: {$rad};
	    -ms-border-radius: {$rad};
			border-radius: {$rad};
}
.select_container:before {
	-webkit-border-radius: 0 {$rad} {$rad} 0;
	    -ms-border-radius: 0 {$rad} {$rad} 0;
			border-radius: 0 {$rad} {$rad} 0;
}
textarea.wp-editor-area {
	-webkit-border-radius: 0 0 {$rad} {$rad};
	    -ms-border-radius: 0 0 {$rad} {$rad};
			border-radius: 0 0 {$rad} {$rad};
}

/* Radius 50% or 0 */
.widget li a img {
	-webkit-border-radius: {$rad50};
	    -ms-border-radius: {$rad50};
			border-radius: {$rad50};
}

EOT;
		}


		// Theme colors
		//--------------------------------------
		if ($colors !== false) {
			$schemes = empty($only_scheme) ? array_keys(snowmountain_get_list_schemes()) : array($only_scheme);
	
			if (count($schemes) > 0) {
				$rez = array();
				foreach ($schemes as $scheme) {
					// Prepare colors
					if (empty($only_scheme)) $colors = snowmountain_get_scheme_colors($scheme);
	
					// Make theme-specific colors and tints
					$colors = snowmountain_customizer_add_theme_colors($colors);
			
					// Make styles
					$rez['colors'] = <<<EOT

/* Common tags */
body {
	background-color: {$colors['bg_color']};
}
.scheme_self {
	color: {$colors['text']};
}
h1, h2, h3, h4, 
h1 a, h2 a, h3 a, h4 a, 
li a {
	color: {$colors['text_dark']};
}
h5, h6, h5 a, h6 a {
	color: {$colors['text_link']};
}
h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover,
li a:hover {
	color: {$colors['text_hover']};
}

dt, mark, ins {	
	color: {$colors['text_dark']};
}
s, strike, del {	
	color: {$colors['text']};
}

code {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_color']};
}
code a {
	color: {$colors['alter_link']};
}
code a:hover {
	color: {$colors['alter_hover']};
}

a {
	color: {$colors['text_link']};
}
a:hover {
	color: {$colors['text_hover']};
}

blockquote {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
blockquote:before {
	color: {$colors['text_hover']};
}
blockquote a {
	color: {$colors['bg_color']};
}
blockquote a:hover {
	color: {$colors['text_hover']};
}

table th, table th + th, table td + th  {
	border-color: {$colors['bd_color']};
}
.woocommerce div.product form.cart .variations th{
	color: {$colors['text']};
}
table td, table th + td, table td + td {
	color: {$colors['text']};
	border-color: {$colors['bd_color']};
}
table {
	border-color: {$colors['bd_color']};
}
table th {
	color: {$colors['text_link']};
	background-color: {$colors['alter_bg_color']};
}
table > tbody > tr:nth-child(2n+1) > td {
	background-color: {$colors['alter_bg_color_02']};
}
table > tbody > tr:nth-child(2n) > td {
}
table th a:hover {
	color: {$colors['text_hover']};
}

hr {
	border-color: {$colors['bd_color']};
}
figure figcaption,
.wp-caption .wp-caption-text,
.wp-caption .wp-caption-dd,
.wp-caption-overlay .wp-caption .wp-caption-text,
.wp-caption-overlay .wp-caption .wp-caption-dd {
	color: {$colors['bg_color']};
	background-color: {$colors['text_hover_08']};
}
ul > li:before {
	color: {$colors['text_link']};
}
.sidebar .widget .widget_title:after {
	background-color: {$colors['bg_color_02']};
}
.sidebar .widget .widget_title:before {
	color: {$colors['bg_color']};
}
.widget .post_item + .post_item {
	border-color: {$colors['bd_color']};
}

/* Form fields
-------------------------------------------------- */


.woocommerce.widget_product_search form:after,
.widget_display_search form:after,
#bbpress-forums #bbp-search-form:after {
	color: {$colors['bg_color']};
}
.widget_search form:after {
	color: {$colors['text']};
}
.widget_search form:hover:after,
.woocommerce.widget_product_search form:hover:after,
.widget_display_search form:hover:after,
#bbpress-forums #bbp-search-form:hover:after {
	color: {$colors['text_hover']};
}

/* Field set */
fieldset {
	border-color: {$colors['bd_color']};
}
fieldset legend {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color']};
}

/* Text fields */
::-webkit-input-placeholder { color: {$colors['text_light']}; }
::-moz-placeholder          { color: {$colors['text_light']}; }
:-ms-input-placeholder      { color: {$colors['text_light']}; }

input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="search"],
input[type="password"],
.select_container,
.select2-container .select2-choice,
textarea,
textarea.wp-editor-area,
/* BB Press */
#buddypress .dir-search input[type="search"],
#buddypress .dir-search input[type="text"],
#buddypress .groups-members-search input[type="search"],
#buddypress .groups-members-search input[type="text"],
#buddypress .standard-form input[type="color"],
#buddypress .standard-form input[type="date"],
#buddypress .standard-form input[type="datetime-local"],
#buddypress .standard-form input[type="datetime"],
#buddypress .standard-form input[type="email"],
#buddypress .standard-form input[type="month"],
#buddypress .standard-form input[type="number"],
#buddypress .standard-form input[type="password"],
#buddypress .standard-form input[type="range"],
#buddypress .standard-form input[type="search"],
#buddypress .standard-form input[type="tel"],
#buddypress .standard-form input[type="text"],
#buddypress .standard-form input[type="time"],
#buddypress .standard-form input[type="url"],
#buddypress .standard-form input[type="week"],
#buddypress .standard-form select,
#buddypress .standard-form textarea,
#buddypress form#whats-new-form textarea,
/* Booked */
.tribe-events .tribe-events-c-events-bar .tribe-common-form-control-text__input,
#booked-page-form input[type="email"],
#booked-page-form input[type="text"],
#booked-page-form input[type="password"],
#booked-page-form textarea,
.booked-upload-wrap,
.booked-upload-wrap input {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
#trx_addons_login_popup input[type="text"],
#trx_addons_login_popup input[type="password"]{
	border-color: {$colors['bd_color']};
}
input[type="text"]:hover,
input[type="number"]:hover,
input[type="email"]:hover,
input[type="tel"]:hover,
input[type="search"]:hover,
input[type="password"]:hover,
.select_container:hover,
.select2-container .select2-choice:hover,
textarea:hover,
textarea.wp-editor-area:hover {
	border-color: {$colors['text_link']};
}
input[type="text"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="tel"]:focus,
input[type="search"]:focus,
input[type="password"]:focus,
.select_container:hover,
select option:hover,
select option:focus,
.select2-container .select2-choice:hover,
textarea:focus,
textarea.wp-editor-area:focus,
/* BB Press */
#buddypress .dir-search input[type="search"]:focus,
#buddypress .dir-search input[type="text"]:focus,
#buddypress .groups-members-search input[type="search"]:focus,
#buddypress .groups-members-search input[type="text"]:focus,
#buddypress .standard-form input[type="color"]:focus,
#buddypress .standard-form input[type="date"]:focus,
#buddypress .standard-form input[type="datetime-local"]:focus,
#buddypress .standard-form input[type="datetime"]:focus,
#buddypress .standard-form input[type="email"]:focus,
#buddypress .standard-form input[type="month"]:focus,
#buddypress .standard-form input[type="number"]:focus,
#buddypress .standard-form input[type="password"]:focus,
#buddypress .standard-form input[type="range"]:focus,
#buddypress .standard-form input[type="search"]:focus,
#buddypress .standard-form input[type="tel"]:focus,
#buddypress .standard-form input[type="text"]:focus,
#buddypress .standard-form input[type="time"]:focus,
#buddypress .standard-form input[type="url"]:focus,
#buddypress .standard-form input[type="week"]:focus,
#buddypress .standard-form select:focus,
#buddypress .standard-form textarea:focus,
#buddypress form#whats-new-form textarea:focus,
/* Booked */
#booked-page-form input[type="email"]:focus,
#booked-page-form input[type="text"]:focus,
#booked-page-form input[type="password"]:focus,
#booked-page-form textarea:focus,
.booked-upload-wrap:hover,
.booked-upload-wrap input:focus {
	color: {$colors['input_dark']};
	border-color: {$colors['input_bd_hover']};
	background-color: {$colors['input_bg_hover']};
}

/* Select containers */
.select_container:before {
	color: {$colors['input_text']};
	background-color: {$colors['input_bg_color']};
}
.select_container:focus:before,
.select_container:hover:before {
	color: {$colors['input_dark']};
	background-color: {$colors['input_bg_hover']};
}
.select_container:after {
	color: {$colors['input_text']};
}
.select_container:focus:after,
.select_container:hover:after {
	color: {$colors['input_dark']};
}
.select_container select {
	color: {$colors['input_text']};
}
.select_container select:focus {
	color: {$colors['input_dark']};
	border-color: {$colors['input_bd_hover']};
}

.select2-results {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_hover']};
	background: {$colors['input_bg_color']};
}
.select2-results .select2-highlighted {
	color: {$colors['input_dark']};
	background: {$colors['input_bg_hover']};
}

input[type="radio"] + label:before,
input[type="checkbox"] + label:before {
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
input[type="checkbox"]:checked + label:before {
	color: {$colors['text_hover']};
}
button[disabled],
input[type="submit"][disabled],
input[type="button"][disabled] {
    background-color: {$colors['text_light']} !important;
    color: {$colors['text']} !important;
}

/* Simple button */
.sc_button_simple:not(.sc_button_bg_image),
.sc_button_simple:not(.sc_button_bg_image):before,
.sc_button_simple:not(.sc_button_bg_image):after {
	color:{$colors['text_hover']};
}
.sc_promo .sc_button_simple:not(.sc_button_bg_image) {
	color:{$colors['text_hover']};
}
.sc_button_simple:not(.sc_button_bg_image):hover,
.sc_button_simple:not(.sc_button_bg_image):hover:before,
.sc_button_simple:not(.sc_button_bg_image):hover:after {
	color:{$colors['text_link']} !important;
}
.sc_button_simple:not(.sc_button_bg_image):hover {
	color:{$colors['text_link']} !important;
}


/* Bordered button */
.sc_button_bordered:not(.sc_button_bg_image) {
	color:{$colors['text_link']};
	border-color:{$colors['text_link']};
}
.sc_button_bordered:not(.sc_button_bg_image):hover {
	color:{$colors['text_hover']} !important;
	border-color:{$colors['text_hover']} !important;
}

/* Normal button */
button,
input[type="reset"],
input[type="submit"],
input[type="button"],
.comments_wrap .form-submit input[type="submit"],
/* BB & Buddy Press */
#buddypress .comment-reply-link,
#buddypress .generic-button a,
#buddypress a.button,
#buddypress button,
#buddypress input[type="button"],
#buddypress input[type="reset"],
#buddypress input[type="submit"],
#buddypress ul.button-nav li a,
a.bp-title-button,
.wp-block-button:not(.is-style-outline) .wp-block-button__link,
/* Booked */
.booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button,
body #booked-profile-page .booked-profile-appt-list .appt-block .booked-cal-buttons .google-cal-button > a,
body #booked-profile-page input[type="submit"],
body #booked-profile-page button,
body .booked-list-view input[type="submit"],
body .booked-list-view button,
body table.booked-calendar input[type="submit"],
body table.booked-calendar button,
body .booked-modal input[type="submit"],
body .booked-modal button,
/* ThemeREX Addons */
.sc_button_default,
.sc_button:not(.sc_button_simple):not(.sc_button_bordered):not(.sc_button_bg_image),
.sc_price_link,
.socials_share:not(.socials_type_drop) .social_icon,
/* Tribe Events */
#tribe-bar-form .tribe-bar-submit input[type="submit"],
#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"],
#tribe-bar-views li.tribe-bar-views-option a,
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option.tribe-bar-active a,
#tribe-events .tribe-events-button,
.tribe-events-button,
.tribe-events-cal-links a,
.tribe-events-sub-nav li a,
/* WooCommerce */
.woocommerce #respond input#submit,
.woocommerce .button, .woocommerce-page .button,
.woocommerce a.button, .woocommerce-page a.button,
.woocommerce button.button, .woocommerce-page button.button,
.woocommerce input.button, .woocommerce-page input.button,
.woocommerce input[type="button"], .woocommerce-page input[type="button"],
.woocommerce input[type="submit"], .woocommerce-page input[type="submit"],
.woocommerce nav.woocommerce-pagination ul li a,
.woocommerce #respond input#submit.alt,
#btn-buy, #btn-pay,
.woocommerce a.button.alt,
.woocommerce button.button.alt,
.woocommerce input.button.alt,
.cq-bgbutton-container a {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.sc_action_item.with_image .sc_action_item_link {
   	color: {$colors['inverse_text']};
	background-color: {$colors['bg_color_0']};
}
.sc_action_item_link {
   	color: {$colors['text_hover']};
	background-color: {$colors['bg_color_0']};
}
.sc_action_item.with_image .sc_action_item_link:hover {
   	color: {$colors['text_dark']};
	background-color: {$colors['bg_color_0']};
}
.sc_action_item_link:hover {
   	color: {$colors['text_link']};
   	background-color: {$colors['bg_color_0']};
}
.theme_button {
	color: {$colors['inverse_link']} !important;
	background-color: {$colors['text_link']} !important;
}

.mc4wp-form label a,
.mc4wp-form .mc4wp-alert a {
    color: {$colors['bg_color']};
}

.mc4wp-form label a:hover,
.mc4wp-form .mc4wp-alert a:hover{
	color: {$colors['text_dark']};
}

.wp-block-image figure a,
figure.wp-caption figcaption a {
    color: {$colors['alter_bg_hover']};
}
.wp-block-image figure a:hover,
figure.wp-caption figcaption a:hover  {
    color: {$colors['inverse_text']};
}
.wp-block-cover p:not(.has-text-color) a {
	color: {$colors['bg_color']};
}
.wp-block-cover p:not(.has-text-color) a:hover {
	color: {$colors['text_hover']};
}
.wp-block-button.is-style-outline .wp-block-button__link {
	color: {$colors['text_link']};
}
.wp-block-button.is-style-outline:hover .wp-block-button__link {
	color: {$colors['text_hover']};
}

.more-link {
	color: {$colors['text_hover']};
}
button:hover,
button:focus,
input[type="submit"]:hover,
input[type="submit"]:focus,
input[type="reset"]:hover,
input[type="reset"]:focus,
input[type="button"]:hover,
input[type="button"]:focus,
.comments_wrap .form-submit input[type="submit"]:hover,
.comments_wrap .form-submit input[type="submit"]:focus,
/* BB & Buddy Press */
#buddypress .comment-reply-link:hover,
#buddypress .generic-button a:hover,
#buddypress a.button:hover,
#buddypress button:hover,
#buddypress input[type="button"]:hover,
#buddypress input[type="reset"]:hover,
#buddypress input[type="submit"]:hover,
#buddypress ul.button-nav li a:hover,
a.bp-title-button:hover,
/* Booked */
.booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button:hover,
body #booked-profile-page .booked-profile-appt-list .appt-block .booked-cal-buttons .google-cal-button > a:hover,
body #booked-profile-page input[type="submit"]:hover,
body #booked-profile-page button:hover,
body .booked-list-view input[type="submit"]:hover,
body .booked-list-view button:hover,
body table.booked-calendar input[type="submit"]:hover,
body table.booked-calendar button:hover,
body .booked-modal input[type="submit"]:hover,
body .booked-modal button:hover,
.wp-block-button:not(.is-style-outline) .wp-block-button__link:hover,
/* ThemeREX Addons */
.sc_button_default:hover,
.sc_button:not(.sc_button_simple):not(.sc_button_bordered):not(.sc_button_bg_image):hover,
.sc_price_link:hover,
.socials_share:not(.socials_type_drop) .social_icon:hover,
/* Tribe Events */
#tribe-bar-form .tribe-bar-submit input[type="submit"]:hover,
#tribe-bar-form .tribe-bar-submit input[type="submit"]:focus,
#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"]:hover,
#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"]:focus,
#tribe-bar-views li.tribe-bar-views-option a:hover,
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option.tribe-bar-active a:hover,
#tribe-events .tribe-events-button:hover,
.tribe-events-button:hover,
.tribe-events-cal-links a:hover,
.tribe-events-sub-nav li a:hover,
/* WooCommerce */
.woocommerce #respond input#submit:hover,
.woocommerce .button:hover, .woocommerce-page .button:hover,
.woocommerce a.button:hover, .woocommerce-page a.button:hover,
.woocommerce button.button:hover, .woocommerce-page button.button:hover,
.woocommerce input.button:hover, .woocommerce-page input.button:hover,
.woocommerce input[type="button"]:hover, .woocommerce-page input[type="button"]:hover,
.woocommerce input[type="submit"]:hover, .woocommerce-page input[type="submit"]:hover,
#btn-buy:hover, #btn-pay:hover,
.woocommerce nav.woocommerce-pagination ul li a:hover,
.woocommerce nav.woocommerce-pagination ul li span.current {
	color: {$colors['inverse_hover']};
	background-color: {$colors['text_link_blend']};
}
.more-link:hover {
	color: {$colors['text_link']};
}
.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}
.theme_button:hover,
.theme_button:focus {
	color: {$colors['inverse_hover']} !important;
	background-color: {$colors['text_link_blend']} !important;
}

body.mobile_layout .booked-appt-list .timeslot .spots-available {
	color: {$colors['inverse_hover']};
}

/* Buttons in sidebars */

/* MailChimp */
/* WooCommerce */
.woocommerce .woocommerce-message .button,
.woocommerce .woocommerce-error .button,
.woocommerce .woocommerce-info .button,
.widget.woocommerce .button,
.widget.woocommerce a.button,
.widget.woocommerce button.button,
.widget.woocommerce input.button,
.widget.woocommerce input[type="button"],
.widget.woocommerce input[type="submit"],
.widget.WOOCS_CONVERTER .button,
.widget.yith-woocompare-widget a.button,
.widget_product_search .search_button {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_link']};
}
/* MailChimp */
/* WooCommerce */
.woocommerce .woocommerce-message .button:hover,
.woocommerce .woocommerce-error .button:hover,
.woocommerce .woocommerce-info .button:hover,
.widget.woocommerce .button:hover,
.widget.woocommerce a.button:hover,
.widget.woocommerce button.button:hover,
.widget.woocommerce input.button:hover,
.widget.woocommerce input[type="button"]:hover,
.widget.woocommerce input[type="button"]:focus,
.widget.woocommerce input[type="submit"]:hover,
.widget.woocommerce input[type="submit"]:focus,
.widget.WOOCS_CONVERTER .button:hover,
.widget.yith-woocompare-widget a.button:hover,
.widget_product_search .search_button:hover {
	color: {$colors['inverse_hover']};
	background-color: {$colors['alter_hover']};
}

/* Buttons in WP Editor */
.wp-editor-container input[type="button"] {
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_color']};
	color: {$colors['alter_dark']};
	-webkit-box-shadow: 0 1px 0 0 {$colors['alter_bd_hover']};
	    -ms-box-shadow: 0 1px 0 0 {$colors['alter_bd_hover']};
			box-shadow: 0 1px 0 0 {$colors['alter_bd_hover']};	
}
.wp-editor-container input[type="button"]:hover,
.wp-editor-container input[type="button"]:focus {
	background-color: {$colors['alter_bg_hover']};
	border-color: {$colors['alter_bd_hover']};
	color: {$colors['alter_link']};
}



/* WP Standard classes */
.sticky {
	background-color: {$colors['alter_bg_hover']};
}
.sticky .label_sticky {
	border-top-color: {$colors['text_link']};
}
.sticky .post_title a {
	color: {$colors['bg_color']};
}
.sticky .post_title a:hover {
	color: {$colors['text_link']};
}
	

/* Page */
#page_preloader,
.scheme_self.header_position_under .page_content_wrap,
.page_wrap {
	background-color: {$colors['bg_color']};
}
.preloader_wrap > div {
	background-color: {$colors['text_link']};
}

/* Header */
.scheme_self.top_panel.with_bg_image:before {
	background-color: {$colors['bg_color_07']};
}
.scheme_self.top_panel .slider_engine_revo .slide_subtitle,
.top_panel .slider_engine_revo .slide_subtitle {
	color: {$colors['text_link']};
}
.top_panel_default .top_panel_title,
.scheme_self.top_panel_default .top_panel_title {
	color: {$colors['bg_color']};
	background-color: {$colors['alter_bg_hover']};
}


/* Tabs */
.snowmountain_tabs .snowmountain_tabs_titles li a {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_color']};
}
.snowmountain_tabs .snowmountain_tabs_titles li a:hover {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.snowmountain_tabs .snowmountain_tabs_titles li.ui-state-active a {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}

/* Post layouts */
.post_item {
	color: {$colors['text']};
}
.post_meta,
.post_meta_item,
.post_meta_item a,
.post_meta_item:before,
.post_meta_item:after,
.post_meta_item:hover:before,
.post_meta_item:hover:after,
.post_date a,
.post_date:before,
.post_date:after,
.post_info .post_info_item,
.post_info .post_info_item a,
.post_info_counters .post_counters_item,
.post_counters .socials_share .socials_caption:before,
.post_counters .socials_share .socials_caption:hover:before {
	color: {$colors['text']};
}
.post_date a:hover,
a.post_meta_item:hover,
a.post_meta_item:hover:before,
.post_meta_item a:hover,
.post_meta_item a:hover:before,
.post_info .post_info_item a:hover,
.post_info .post_info_item a:hover:before,
.post_info_counters .post_counters_item:hover,
.post_info_counters .post_counters_item:hover:before {
	color: {$colors['text_hover']};
}
.post_item .post_title a:hover {
	color: {$colors['text_link']};
}

.post_meta_item.post_categories {
    color: {$colors['text_dark']};
}
.post_meta_item.post_categories a {
	color: {$colors['text_dark']};
}
.post_meta_item.post_categories a:hover {
	color: {$colors['text_hover']};
}
.sticky .post_meta_item.post_categories a {
	color: {$colors['text_link']};
}
.sticky .post_meta_item.post_categories a:hover {
	color: {$colors['text_hover']};
}

.post_meta_item .socials_share .social_items {
	background-color: {$colors['bg_color']};
}
.post_meta_item .social_items,
.post_meta_item .social_items:before {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
	color: {$colors['text']};
}

.post_layout_excerpt + .post_layout_excerpt {
	border-color: {$colors['bd_color']};
}
.post_layout_classic {
	border-color: {$colors['bd_color']};
}

.scheme_self.gallery_preview:before {
	background-color: {$colors['bg_color']};
}
.scheme_self.gallery_preview {
	color: {$colors['text']};
}


/* Post Formats */

/* Audio */
.trx_addons_audio_player .audio_author,
.format-audio .post_featured .post_audio_author {
	color: {$colors['text_link']};
}
.format-audio .post_featured.without_thumb .post_audio {
	border-color: {$colors['bd_color']};
}
.format-audio .post_featured.without_thumb .post_audio_title,
.without_thumb .mejs-controls .mejs-currenttime,
.without_thumb .mejs-controls .mejs-duration {
	color: {$colors['text_dark']};
}

.trx_addons_audio_player.without_cover {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.trx_addons_audio_player.with_cover .audio_caption {
	color: {$colors['inverse_link']};
}
.trx_addons_audio_player.without_cover .audio_author {
	color: {$colors['alter_link']};
}
.trx_addons_audio_player .mejs-container .mejs-controls .mejs-time {
	color: {$colors['alter_dark']};
}
.trx_addons_audio_player.with_cover .mejs-container .mejs-controls .mejs-time {
	color: {$colors['inverse_link']};
}


.mejs-container,
.mejs-container .mejs-controls,
.mejs-embed,
.mejs-embed body {
	background: {$colors['text_dark_07']};
}

.mejs-controls .mejs-button,
.mejs-controls .mejs-time-rail .mejs-time-current,
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current {
	color: {$colors['inverse_link']};
	background: {$colors['text_hover']};
}
.mejs-controls .mejs-button:hover {
	color: {$colors['inverse_hover']};
	background: {$colors['text_link']};
}
.mejs-controls .mejs-time-rail .mejs-time-total,
.mejs-controls .mejs-time-rail .mejs-time-loaded,
.mejs-container .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total {
	background: {$colors['bg_color_07']};
}

/* Aside */
.format-aside .post_content_inner {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_color']};
}

/* Link and Status */
.format-link .post_content_inner,
.format-status .post_content_inner {
	color: {$colors['text_dark']};
}

/* Chat */
.format-chat p > b,
.format-chat p > strong {
	color: {$colors['text_dark']};
}

/* Video */
.trx_addons_video_player.with_cover .video_hover,
.format-video .post_featured.with_thumb .post_video_hover {
	color: {$colors['inverse_text']};
}
.trx_addons_video_player.with_cover .video_hover:hover,
.format-video .post_featured.with_thumb .post_video_hover:hover {
	color: {$colors['text_hover']};
	background-color: {$colors['bg_color_0']};
}
.sidebar_inner .trx_addons_video_player.with_cover .video_hover {
	color: {$colors['alter_link']};
}
.sidebar_inner .trx_addons_video_player.with_cover .video_hover:hover {
	color: {$colors['inverse_hover']};
	background-color: {$colors['alter_link']};
}

/* Chess */
.post_layout_chess .post_content_inner:after {
	background: linear-gradient(to top, {$colors['bg_color']} 0%, {$colors['bg_color_0']} 100%) no-repeat scroll right top / 100% 100% {$colors['bg_color_0']};
}
.post_layout_chess_1 .post_meta:before {
	background-color: {$colors['bd_color']};
}

/* Pagination */
.nav-links-old {
	color: {$colors['text_dark']};
}
.nav-links-old a:hover {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}

.page_links > a,
.comments_pagination .page-numbers,
.nav-links .page-numbers {
	color: {$colors['text_dark']};
	background-color: {$colors['input_bg_color']};
}
.page_links > a:hover,
.page_links > span:not(.page_links_title),
.comments_pagination a.page-numbers:hover,
.comments_pagination .page-numbers.current,
.nav-links a.page-numbers:hover,
.nav-links .page-numbers.current {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_hover']};
}

/* Single post */
.post_item_single .post_meta_item:not(.post_categories):hover,
.post_item_single .post_meta_item > a:hover,
.post_item_single .post_meta_item .socials_caption:hover,
.post_item_single .post_edit a:hover {
	color: {$colors['text_hover']};
}
.post_item_single .post_content .post_meta_label,
.post_item_single .post_content .post_meta_item:hover .post_meta_label {
	color: {$colors['text_dark']};
}
.post_item_single .post_content .post_tags,
.post_item_single .post_content .post_tags a {
	color: {$colors['text_link']};
}
.post_item_single .post_content .post_tags a:hover {
	color: {$colors['text_hover']};
}
.post_item_single .post_content .post_meta .post_share .social_item .social_icon {
	color: {$colors['inverse_link']} !important;
	background-color: {$colors['text_hover']};
}
.post_item_single .post_content .post_meta .post_share .social_item:hover .social_icon {
	color: {$colors['inverse_link']} !important;
	background-color: {$colors['text_link']};
}

.post-password-form input[type="submit"] {
	border-color: {$colors['text_dark']};
}
.post-password-form input[type="submit"]:hover,
.post-password-form input[type="submit"]:focus {
	color: {$colors['bg_color']};
}

/* Single post navi */
.nav-links-single .nav-links {
	border-color: {$colors['bd_color']};
}
.nav-links-single .nav-links a .meta-nav {
	color: {$colors['text']};
}
.nav-links-single .nav-links a .post_date {
	color: {$colors['text']};
}
.nav-links-single .nav-links a:hover .meta-nav,
.nav-links-single .nav-links a:hover .post_date {
	color: {$colors['text_dark']};
}
.nav-links-single .nav-links a:hover .post-title {
	color: {$colors['text_link']};
}

/* Author info */
.author_info {
	color: {$colors['text']};
	background-color: {$colors['alter_bg_color']};
}
.author_info .author_title {
	color: {$colors['text_link']};
}
.author_info a {
	color: {$colors['text_dark']};
}
.author_info a:hover {
	color: {$colors['text_link']};
}
.author_info .socials_wrap .social_item .social_icon {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.author_info .socials_wrap .social_item:hover .social_icon {
	color: {$colors['inverse_hover']};
	background-color: {$colors['text_hover']};
}
.author_bio .author_link {
	color: {$colors['text_hover']};
}
.author_bio .author_link:hover {
	color: {$colors['text_link']};
}
.author_avatar:before,
.author_avatar:after {
    background-color: {$colors['alter_bg_color']};
}

/* Related posts */
.related_wrap {
	border-color: {$colors['bd_color']};
}
.related_wrap .related_item_style_1 .post_header {
	background-color: {$colors['bg_color_07']};
}
.related_wrap .related_item_style_1:hover .post_header {
	background-color: {$colors['bg_color']};
}
.related_wrap .related_item_style_1 .post_date a {
	color: {$colors['text']};
}
.related_wrap .related_item_style_1:hover .post_date a {
	color: {$colors['text']};
}
.related_wrap .related_item_style_1:hover .post_date a:hover {
	color: {$colors['text_dark']};
}

/* Comments */
.comments_list_wrap,
.comments_list_wrap > ul {
	border-color: {$colors['bd_color']};
}
.comments_list_wrap li + li,
.comments_list_wrap li ul {
	border-color: {$colors['bd_color']};
}
.comments_list_wrap .comment_info .comment_author {
	color: {$colors['text_dark']};
}
.comments_list_wrap .comment_content:after {
    border-right-color: {$colors['bg_color']};
}
.comments_list_wrap .comment_info {
	color: {$colors['text']};
	border-color: {$colors['bd_color']};
}
.comments_list_wrap .comment_counters a {
	color: {$colors['text_link']};
}
.comments_list_wrap .comment_counters a:before {
	color: {$colors['text_link']};
}
.comments_list_wrap .comment_counters a:hover:before,
.comments_list_wrap .comment_counters a:hover {
	color: {$colors['text_hover']};
}
.comments_list_wrap .comment_text {
	color: {$colors['text']};
}
.comments_list_wrap .comment_reply a {
	color: {$colors['text_hover']};
}
.comments_list_wrap .comment_reply a:hover {
	color: {$colors['text_link']};
}
.comments_form_wrap {
	border-color: {$colors['bd_color']};
}
.comments_wrap .comments_notes {
	color: {$colors['text']};
}


/* Page 404 */
.post_item_404 .page_title {
	color: {$colors['text']};
}
.post_item_404 .page_description {
	color: {$colors['text_link']};
}
.post_item_404 .go_home {
	border-color: {$colors['text_dark']};
}

/* Sidebar */
.scheme_self.sidebar .sidebar_inner {
	color: {$colors['alter_text']};
}
.sidebar_inner .widget {
	background-color: {$colors['alter_bg_color']};
}
.sidebar_inner .widget.widget_search {
	background-color: {$colors['bd_color']};
}
.sidebar_inner .widget + .widget {
	border-color: {$colors['bd_color']};
}
.scheme_self.sidebar .widget + .widget {
	border-color: {$colors['alter_bd_color']};
}
.scheme_self.sidebar h1, .scheme_self.sidebar h2, .scheme_self.sidebar h3, .scheme_self.sidebar h4, .scheme_self.sidebar h5, .scheme_self.sidebar h6,
.scheme_self.sidebar h1 a, .scheme_self.sidebar h2 a, .scheme_self.sidebar h3 a, .scheme_self.sidebar h4 a, .scheme_self.sidebar h5 a, .scheme_self.sidebar h6 a {
	color: {$colors['text_dark']};
}
.scheme_self.sidebar h1 a:hover, .scheme_self.sidebar h2 a:hover, .scheme_self.sidebar h3 a:hover, .scheme_self.sidebar h4 a:hover, .scheme_self.sidebar h5 a:hover, .scheme_self.sidebar h6 a:hover {
	color: {$colors['alter_link']};
}


/* Widgets */
.widget ul > li:before {
	background-color: {$colors['text_link']};
}
.scheme_self.sidebar ul > li:before {
	background-color: {$colors['text_hover']};
}
.scheme_self.sidebar a {
	color: {$colors['text_link']};
}
.scheme_self.sidebar a:hover {
	color: {$colors['text_hover']};
}
.scheme_self.sidebar li > a {
	color: {$colors['text']};
}
.scheme_self.sidebar .post_title > a {
	color: {$colors['text_dark']};
}
.scheme_self.sidebar li > a:hover,
.scheme_self.sidebar .post_title > a:hover {
	color: {$colors['text_hover']};
}

.widget input[type="text"],
.widget input[type="number"],
.widget input[type="email"], 
.widget input[type="tel"], 
.widget input[type="password"], 
.widget textarea, 
.widget textarea.wp-editor-area {
	color: {$colors['bg_color']};
	border-color: {$colors['bg_color_0']};
	background-color: {$colors['bg_color_02']};
}
.widget input[type="search"], 
.widget input[type="search"]::placeholder {
	color: {$colors['text']};
}
.widget input[type="text"]::-webkit-input-placeholder,
.widget input[type="number"]::-webkit-input-placeholder,
.widget input[type="email"]::-webkit-input-placeholder, 
.widget input[type="tel"]::-webkit-input-placeholder, 
.widget input[type="password"]::-webkit-input-placeholder, 
.widget textarea::-webkit-input-placeholder, 
.widget textarea.wp-editor-area::-webkit-input-placeholder {
    color: {$colors['bg_color_06']};
}

.widget input[type="text"]::-moz-placeholder,
.widget input[type="number"]::-moz-placeholder,
.widget input[type="email"]::-moz-placeholder, 
.widget input[type="tel"]::-moz-placeholder, 
.widget input[type="password"]::-moz-placeholder, 
.widget input[type="search"]::-moz-placeholder, 
.widget textarea::-moz-placeholder, 
.widget textarea.wp-editor-area::-moz-placeholder {
    color: {$colors['bg_color_06']};
}
.widget input[type="text"]:-ms-input-placeholder,
.widget input[type="number"]:-ms-input-placeholder,
.widget input[type="email"]:-ms-input-placeholder, 
.widget input[type="tel"]:-ms-input-placeholder, 
.widget input[type="password"]:-ms-input-placeholder, 
.widget input[type="search"]:-ms-input-placeholder, 
.widget textarea:-ms-input-placeholder, 
.widget textarea.wp-editor-area:-ms-input-placeholder {
    color: {$colors['bg_color_06']};
}
.sidebar .widget .widget_title {
    color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.sidebar .widget_rss .widget_title a {
    color: {$colors['bg_color']};
}

.sidebar .widget_rss .widget_title a:hover {
    color: {$colors['text_link']};
}

.sidebar .select_container,
.sidebar .select_container:before ,
footer .select_container,
footer .select_container:before {
    background-color: {$colors['text_link']};
}


/* Archive */
.scheme_self.sidebar .widget_archive li {
	color: {$colors['alter_dark']};
}

/* Calendar */
.widget_calendar caption, .widget_calendar tbody td a, .widget_calendar th,
.wp-block-calendar caption, .wp-block-calendar tbody td a, .wp-block-calendar th {
	color: {$colors['text_dark']};
}
.scheme_self.sidebar .widget_calendar caption,
.scheme_self.sidebar .wp-block-calendar caption {
    color: {$colors['text_link']};
}
.scheme_self.sidebar .widget_calendar tbody td a, .scheme_self.sidebar .widget_calendar th,
.scheme_self.sidebar .wp-block-calendar tbody td a, .scheme_self.sidebar .wp-block-calendar th {
	color: {$colors['text_dark']};
}
.widget_calendar tbody td, .wp-block-calendar tbody td {
	color: {$colors['text']} !important;
}
.scheme_self.sidebar .widget_calendar tbody td,
.scheme_self.sidebar .wp-block-calendar tbody td {
	color: {$colors['text']} !important;
}
.widget_calendar tbody td a:hover,
.wp-block-calendar tbody td a:hover {
	color: {$colors['text_link']};
}
.scheme_self.sidebar .widget_calendar tbody td a:hover,
.scheme_self.sidebar .wp-block-calendar tbody td a:hover {
	color: {$colors['alter_link']};
}
.widget_calendar tbody td a:after,
.wp-block-calendar tbody td a:after {
	background-color: {$colors['text_link']};
}
.scheme_self.sidebar .widget_calendar tbody td a:after,
.scheme_self.sidebar .wp-block-calendar tbody td a:after {
	background-color: {$colors['text_hover']};
}
.widget_calendar td#today, .wp-block-calendar td#today {
	color: {$colors['inverse_text']} !important;
}
.widget_calendar td#today a, .wp-block-calendar td#today a {
	color: {$colors['inverse_link']};
}
.widget_calendar td#today a:hover, .wp-block-calendar td#today a:hover {
	color: {$colors['inverse_hover']};
}
.widget_calendar td#today:before,
.wp-block-calendar td#today:before {
	background-color: {$colors['text_hover']};
}
.scheme_self.sidebar .widget_calendar td#today:before,
.scheme_self.sidebar .wp-block-calendar td#today:before {
	background-color: {$colors['text_hover']};
}
.widget_calendar td#today a:after,
.wp-block-calendar td#today a:after {
	background-color: {$colors['inverse_link']};
}
.widget_calendar td#today a:hover:after,
.wp-block-calendar td#today a:hover:after {
	background-color: {$colors['inverse_hover']};
}
.widget_calendar #prev a, .widget_calendar #next a,
.wp-block-calendar #prev a, .wp-block-calendar #next a,
.widget_calendar .wp-calendar-nav-prev a, .widget_calendar .wp-calendar-nav-next a,
.wp-block-calendar .wp-calendar-nav-prev a, .wp-block-calendar .wp-calendar-nav-next a {
	color: {$colors['text_hover']};
}
.scheme_self.sidebar .widget_calendar #prev a, .scheme_self.sidebar .widget_calendar #next a,
.scheme_self.sidebar .wp-block-calendar #prev a, .scheme_self.sidebar .wp-block-calendar #next a,
.scheme_self.sidebar .widget_calendar .wp-calendar-nav-prev a, .scheme_self.sidebar .widget_calendar .wp-calendar-nav-next a,
.scheme_self.sidebar .wp-block-calendar .wp-calendar-nav-prev a, .scheme_self.sidebar .wp-block-calendar .wp-calendar-nav-next a {
	color: {$colors['text_hover']};
}
.widget_calendar #prev a:hover, .widget_calendar #next a:hover,
.wp-block-calendar #prev a:hover, .wp-block-calendar #next a:hover,
.widget_calendar .wp-calendar-nav-prev a:hover, .widget_calendar .wp-calendar-nav-next a:hover,
.wp-block-calendar .wp-calendar-nav-prev a:hover, .wp-block-calendar .wp-calendar-nav-next a:hover {
	color: {$colors['alter_hover']};
}
.scheme_self.sidebar .widget_calendar #prev a:hover, .scheme_self.sidebar .widget_calendar #next a:hover,
.scheme_self.sidebar .wp-block-calendar #prev a:hover, .scheme_self.sidebar .wp-block-calendar #next a:hover,
.scheme_self.sidebar .widget_calendar .wp-calendar-nav-prev a:hover, .scheme_self.sidebar .widget_calendar .wp-calendar-nav-next a:hover,
.scheme_self.sidebar .wp-block-calendar .wp-calendar-nav-prev a:hover, .scheme_self.sidebar .wp-block-calendar .wp-calendar-nav-next a:hover {
	color: {$colors['alter_hover']};
}
.widget_calendar td#prev a:before, .widget_calendar td#next a:before,
.wp-block-calendar td#prev a:before, .wp-block-calendar td#next a:before,
.widget_calendar .wp-calendar-nav-prev a:before, .widget_calendar .wp-calendar-nav-next a:before,
.wp-block-calendar .wp-calendar-nav-prev a:before, .wp-block-calendar .wp-calendar-nav-next a:before {
	background-color: {$colors['bg_color']};
}
.scheme_self.sidebar .widget_calendar td#prev a:before, .scheme_self.sidebar .widget_calendar td#next a:before,
.scheme_self.sidebar .wp-block-calendar td#prev a:before, .scheme_self.sidebar .wp-block-calendar td#next a:before,
.scheme_self.sidebar .widget_calendar .wp-calendar-nav-prev a:before, .scheme_self.sidebar .widget_calendar .wp-calendar-nav-next a:before,
.scheme_self.sidebar .wp-block-calendar .wp-calendar-nav-prev a:before, .scheme_self.sidebar .wp-block-calendar .wp-calendar-nav-next a:before,
.scheme_self.footer_wrap .widget_calendar td#prev a:before, .scheme_self.footer_wrap .widget_calendar td#next a:before,
.scheme_self.footer_wrap .wp-block-calendar td#prev a:before, .scheme_self.footer_wrap .wp-block-calendar td#next a:before,
.scheme_self.footer_wrap .widget_calendar .wp-calendar-nav-prev a:before, .scheme_self.footer_wrap .widget_calendar .wp-calendar-nav-next a:before,
.scheme_self.footer_wrap .wp-block-calendar .wp-calendar-nav-prev a:before, .scheme_self.footer_wrap .wp-block-calendar .wp-calendar-nav-next a:before {
	background-color: {$colors['alter_bg_color']};
}

.widget ul#recentcomments > li a {
	color: {$colors['text_dark']};
}
.widget ul#recentcomments > li a:hover {
	color: {$colors['text_hover']};
}
.widget ul#recentcomments > li {
	color: {$colors['text']};
}
.widget ul#recentcomments > li + li{
	border-color: {$colors['bd_color']};
}

/* Categories */
.widget_categories li {
	color: {$colors['text_dark']};
}
.scheme_self.sidebar .widget_categories li {
	color: {$colors['text']};
}

/* Tag cloud */
.widget_product_tag_cloud a,
.widget_tag_cloud a,
.wp-block-tag-cloud a {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color_0']};
}
.scheme_self.sidebar .widget_product_tag_cloud a,
.scheme_self.sidebar .widget_tag_cloud a,
.scheme_self.sidebar .wp-block-tag-cloud a {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color_0']};
}
.widget_product_tag_cloud a:hover,
.widget_tag_cloud a:hover,
.wp-block-tag-cloud a:hover {
	color: {$colors['text_hover']} !important;
	background-color: {$colors['bg_color_0']};
}
.scheme_self.sidebar .widget_product_tag_cloud a:hover,
.scheme_self.sidebar .widget_tag_cloud a:hover,
.scheme_self.sidebar .wp-block-tag-cloud a:hover {
	color: {$colors['text_hover']}!important;
	background-color: {$colors['bg_color_0']};
}

/* RSS */
.widget_rss .widget_title a:first-child {
	color: {$colors['text_link']};
}
.scheme_self.sidebar .widget_rss .widget_title a:first-child {
	color: {$colors['alter_link']};
}
.widget_rss .widget_title a:first-child:hover {
	color: {$colors['text_hover']};
}
.scheme_self.sidebar .widget_rss .widget_title a:first-child:hover {
	color: {$colors['alter_hover']};
}
.widget_rss .rss-date {
	color: {$colors['text']};
}
.scheme_self.sidebar .widget_rss .rss-date {
	color: {$colors['alter_light']};
}

/* Footer */
.scheme_self.footer_wrap,
.footer_wrap .scheme_self.vc_row {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['alter_text']};
}
.scheme_self.footer_wrap .widget,
.scheme_self.footer_wrap .sc_content .wpb_column,
.footer_wrap .scheme_self.vc_row .widget,
.footer_wrap .scheme_self.vc_row .sc_content .wpb_column {
	border-color: {$colors['alter_bd_color']};
}
.scheme_self.footer_wrap h1, .scheme_self.footer_wrap h2, .scheme_self.footer_wrap h3,
.scheme_self.footer_wrap h4, .scheme_self.footer_wrap h5, .scheme_self.footer_wrap h6,
.scheme_self.footer_wrap h1 a, .scheme_self.footer_wrap h2 a, .scheme_self.footer_wrap h3 a,
.scheme_self.footer_wrap h4 a, .scheme_self.footer_wrap h5 a, .scheme_self.footer_wrap h6 a,
.footer_wrap .scheme_self.vc_row h1, .footer_wrap .scheme_self.vc_row h2, .footer_wrap .scheme_self.vc_row h3,
.footer_wrap .scheme_self.vc_row h4, .footer_wrap .scheme_self.vc_row h5, .footer_wrap .scheme_self.vc_row h6,
.footer_wrap .scheme_self.vc_row h1 a, .footer_wrap .scheme_self.vc_row h2 a, .footer_wrap .scheme_self.vc_row h3 a,
.footer_wrap .scheme_self.vc_row h4 a, .footer_wrap .scheme_self.vc_row h5 a, .footer_wrap .scheme_self.vc_row h6 a {
	color: {$colors['alter_dark']};
}
.scheme_self.footer_wrap h1 a:hover, .scheme_self.footer_wrap h2 a:hover, .scheme_self.footer_wrap h3 a:hover,
.scheme_self.footer_wrap h4 a:hover, .scheme_self.footer_wrap h5 a:hover, .scheme_self.footer_wrap h6 a:hover,
.footer_wrap .scheme_self.vc_row h1 a:hover, .footer_wrap .scheme_self.vc_row h2 a:hover, .footer_wrap .scheme_self.vc_row h3 a:hover,
.footer_wrap .scheme_self.vc_row h4 a:hover, .footer_wrap .scheme_self.vc_row h5 a:hover, .footer_wrap .scheme_self.vc_row h6 a:hover {
	color: {$colors['alter_link']};
}
.scheme_self.footer_wrap .widget li:before,
.footer_wrap .scheme_self.vc_row .widget li:before {
	background-color: {$colors['alter_link']};
}
.scheme_self.footer_wrap a,
.footer_wrap .scheme_self.vc_row a {
	color: {$colors['alter_light']};
}
.scheme_self.footer_wrap a:hover,
.footer_wrap .scheme_self.vc_row a:hover {
	color: {$colors['text_hover']};
}
.scheme_self.footer_wrap .sc_layouts_row,
.footer_wrap .scheme_self .sc_layouts_row {
	color: {$colors['alter_dark']};
}

.footer_logo_inner {
	border-color: {$colors['alter_bd_color']};
}
.footer_logo_inner:after {
	background-color: {$colors['alter_text']};
}
.footer_socials_inner .social_item .social_icon {
	color: {$colors['alter_text']};
}
.footer_socials_inner .social_item:hover .social_icon {
	color: {$colors['alter_dark']};
}
.menu_footer_nav_area ul li a {
	color: {$colors['alter_dark']};
}
.menu_footer_nav_area ul li a:hover {
	color: {$colors['alter_link']};
}
.menu_footer_nav_area ul li+li:before {
	border-color: {$colors['alter_light']};
}

.footer_copyright_inner {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
	color: {$colors['text_dark']};
}
.footer_copyright_inner a {
	color: {$colors['text_dark']};
}
.footer_copyright_inner a:hover {
	color: {$colors['text_link']};
}
.footer_copyright_inner .copyright_text {
	color: {$colors['text']};
}
.footer_wrap .vc_row-has-fill .widget_title, .footer_wrap .vc_row-has-fill .widgettitle {
	color: {$colors['inverse_text']};
}
.footer_wrap .widget ul > li:before {
	color: {$colors['text_hover']};
}

.footer_wrap .widget input[type="search"] {
	background-color: {$colors['text_dark']};
}


/* Date & Time Picker Field */
.xdsoft_datetimepicker.xdsoft_default {
	background: {$colors['bg_color']};
}
.xdsoft_datetimepicker.xdsoft_default .xdsoft_label {
	background: {$colors['bg_color']};
}
.xdsoft_datetimepicker.xdsoft_default .xdsoft_calendar td, 
.xdsoft_datetimepicker.xdsoft_default .xdsoft_calendar th {
	background: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
}
.xdsoft_datetimepicker.xdsoft_default .xdsoft_timepicker .xdsoft_time_box > div > div {
	background: {$colors['bg_color']};
}
.xdsoft_datetimepicker.xdsoft_default .xdsoft_calendar td:hover, 
.xdsoft_datetimepicker.xdsoft_default .xdsoft_timepicker .xdsoft_time_box > div > div:hover,
.xdsoft_datetimepicker.xdsoft_default .xdsoft_label > .xdsoft_select > div > .xdsoft_option:hover,
.xdsoft_datetimepicker.xdsoft_default .xdsoft_label > .xdsoft_select > div > .xdsoft_option:hover {
	background: {$colors['text_hover']} !important;
}
.xdsoft_datetimepicker.xdsoft_default .xdsoft_label > .xdsoft_select > div > .xdsoft_option.xdsoft_current {
	background: {$colors['text_link']};
	box-shadow: {$colors['bd_color']} 0 1px 0 0 inset;
}
.xdsoft_datetimepicker.xdsoft_default .xdsoft_calendar td.xdsoft_default, 
.xdsoft_datetimepicker.xdsoft_default .xdsoft_calendar td.xdsoft_current, 
.xdsoft_datetimepicker.xdsoft_default .xdsoft_timepicker .xdsoft_time_box > div > div.xdsoft_current {
	background: {$colors['text_link']};
	box-shadow: {$colors['bd_color']} 0 1px 0 0 inset;
}

/* Third-party plugins */

.mfp-bg {
	background-color: {$colors['bg_color_07']};
}
.mfp-image-holder .mfp-close,
.mfp-iframe-holder .mfp-close,
.mfp-close-btn-in .mfp-close {
	color: {$colors['text_dark']};
	background-color: transparent;
}
.mfp-image-holder .mfp-close:hover,
.mfp-iframe-holder .mfp-close:hover,
.mfp-close-btn-in .mfp-close:hover {
	color: {$colors['text_link']};
}
.wpcf7 {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}
.wpcf7 input[type="submit"] {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color']};
}
.wpcf7-form-control-wrap > input[type="date"]:after {
	background-color: {$colors['bg_color_0']};
}
.wpcf7 .title .phone {
	color: {$colors['bg_color_06']};
}
.wpcf7 .phone a {
	color: {$colors['inverse_link']};
}
.wpcf7 .phone a:hover {
	color: {$colors['text_dark']};
}
.content .search_wrap .search_submit {
	color: {$colors['text_dark']};
}

.tooltipster-base.tooltipster-shadow .tooltipster-content .info {
	color: {$colors['text_dark_07']};
}
.tooltipster-base.tooltipster-shadow .tooltipster-content .info+p a {
	color: {$colors['text_hover']};
}
.tooltipster-base.tooltipster-shadow .tooltipster-content .info+p a:hover {
	color: {$colors['text_link']};
}

.wp-block-cover-image .wp-block-subhead:not(.has-text-color),
.wp-block-cover-image h1:not(.has-text-color), .wp-block-cover-image h2:not(.has-text-color),
.wp-block-cover-image h3:not(.has-text-color), .wp-block-cover-image h4:not(.has-text-color),
.wp-block-cover-image h5:not(.has-text-color), .wp-block-cover-image h6:not(.has-text-color),
.wp-block-cover-image p:not(.has-text-color), .wp-block-cover .wp-block-subhead:not(.has-text-color),
.wp-block-cover h1:not(.has-text-color), .wp-block-cover h2:not(.has-text-color),
.wp-block-cover h3:not(.has-text-color), .wp-block-cover h4:not(.has-text-color),
.wp-block-cover h5:not(.has-text-color), .wp-block-cover h6:not(.has-text-color),
.wp-block-cover p:not(.has-text-color) {
	color: {$colors['inverse_text']};
}

EOT;
				
					$rez = apply_filters('snowmountain_filter_get_css', $rez, $colors, false, $scheme);
					$css['colors'] .= $rez['colors'];
				}
			}
		}
				
		$css_str = (!empty($css['fonts']) ? $css['fonts'] : '')
				. (!empty($css['colors']) ? $css['colors'] : '');
		return apply_filters( 'snowmountain_filter_prepare_css', $css_str, $remove_spaces );
	}
}
?>