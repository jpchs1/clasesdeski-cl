=== Custom Post Carousels with Owl ===
Contributors: thehowarde
Donate link: https://www.duckdiverllc.com
Tags: owl carousel 2, post slider, product slider, image carousel, post carousel, reviews carousel
Requires at least: 4.5
Tested up to: 6.0
Requires PHP: 7.0
Stable tag: 1.4.6
WC tested up to: 6.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Easily add post carousels to your website. Works with any custom post type or regular posts. Controls allow for insertion of multiple carousels on a single page.  You can specify margins, number of items per page at multiple breakpoints. Choose options by category, tag, other custom taxonomy or by post ID.

== Description ==

This plugin uses the Owl-Carousel-2 jQuery plugin to create carousels (sliders) from any built in or (public) custom post type in WordPress. You can use this plugin to make:

* Image Carousel from Media Library
* Product Carousels (Choose WooCommerce Products)
* Featured Product Carousels
* Carousels from Posts or Products by ID
* Taxonomy (Category/Tag) Carousels
* Carousel from *Any Custom Post Type*
* Latest Posts
* WooCommerce Reviews
* More

Easy to use controls allow for customization of each carousel with options to show or hide Titles, Featured Image, Call to Action buttons (links) and more.

This plugin is simple and without on screen nags, promotions or upsells.

Please note. There is not a lot of stylesheet attached to the layout. If you don't have basic skills, this might not be the plugin for you.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `owl-carousel-2.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Click on the menu item called `Carousels`
1. Create your carousel.
1. Copy the Shortcode and place in your page or post, or place `<?php echo do_shortcode('[dd-owl-carousel id="1" title="Carousel Title"]'); ?>` in your templates

== Frequently Asked Questions ==

= I made the carousel, but now I can't see it. =

Make sure you insert the shortcode created by the plugin.

= Can I use multiple carousels on a single page? =

Yes, you can use as many as you want. Each one will have it's own CSS ID so you can target them in your custom CSS files if you need to.

= Are there programming Hooks? =

Yes, there are 2 hooks. One is before the carousel contents, and the other is after the contents.  There will be more enhancement to this at a later date.

1. dd-carousel-before-content
1. dd-carousel-after-content

These hooks pass 1 parameter which is $carousel_ID if needed.

Example to add pricing for WooCommerce Carousels - Add to your theme functions.php :

`<?php
	function add_wc_price_to_carousel(){
    global $post, $woocommerce;
    $product = wc_get_product( $post->ID );
    if ($product) {
        echo '<p class="price">$' . $product->get_price() . '</p>' ;
        echo '<a href="'.get_permalink( $post->ID ).'" class="btn btn-primary ">Shop Now</a>';
		}
	}
	add_action('dd-carousel-after-content', 'add_wc_price_to_carousel', 10);
`
= Can I Filter the Query Arguments? =

Yes, there is currently one filter.

* dd_carousel_filter_query_args

This filter passes 2 variables. `$args` the current arguments of the WP_Query and `$carousel_id` which is the ID of the carousel you want to filter.

Example to create your own custom Query - Add to your theme functions.php :

`<?php
add_filter('dd_carousel_filter_query_args', 'filter_carousel', 10, 2);
function filter_carousel($args, $carouselID){
	if ($carouselID == 9803){
	$args = array(
		'post_type'              => array( 'post' ),
		'meta_query'             => array(
				'relation' => 'AND',
				array(
					'key'     => '_chosen_store',
					'value'   => '1',
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
			),
		);
	return $args;
	}
}`

= Are there other filters available? =

There are currently several filters available to you. You can apply these in your theme's functions.php

1. `dd_carousel_filter_excerpt` ($excerpt, $carousel_id)
	* `$excerpt` : string the excerpt
	* `$carousel_id` int the post ID of the carousel.
1. `dd_filter_owl_carousel_script` ($owl_script, $carousel_id)
	* `$owl_script` string the jQuery function that invokes the Owl Carousel
	* `$carousel_id` int the post ID of the carousel.
1. `dd_carousel_filter_title_heading` ($heading)
	* `$heading` string - use any additional valid HTML tag to wrap the title that isn't already present.
1. `dd_carousel_filter_prev` and `dd_carousel_filter_next`
	* This might be helpful to include a fontawesome or other icon set as the Prev/Next buttons
1. `dd_carousel_filter_caption` ($the_caption, $caption)
	* `$the_caption` - The HTML wrapper and caption for an image carousel
	* `$caption` The `wp_get_attachment_caption` caption for the image

Example of script filter:

`<?php
apply_filters('dd_filter_owl_carousel_script', 'my_filter_owl_carousel_script', 10 , 2);
function my_filter_owl_carousel_script($script, $carousel_id){
	// Do stuff
	return $script;
}
?>`

== Screenshots ==

1. Admin View of a Featured Product Carousel
2. Admin View of choosing by post ID.
3. Admin View of Chosen Category
4. Public Large Desktop View. With Featured Image and CTA Link to item.

== Changelog ==

= 1.4.6 =
Various fixes for sanitiziation filters.

= 1.4.4 =
Allow SVG in Prev / Next buttons

= 1.4.2 =
Security fixes

= 1.4.1 =
Add image alt text (if added to image in media library)

= 1.4 =
Added Lazy Loading as an option to all carousels (enabled by default). It is recommended you review your carousels to ensure settings are as you expect. Combined and minified front end styles.

= 1.3.1 =
Fix image issue on Taxonomy Carousel

= 1.3 =
Add "Media/Image" Carousel with choose media items. Additional filters added for convenience.

= 1.2.6 =
Add two additional filters `dd_filter_owl_carousel_script` and `dd_carousel_filter_excerpt`
Fix for CPT adding meta to all posts.

= 1.2.5 =
Fix Random

= 1.2.4 =
Fix for Elementor

= 1.2.2 =
Fix missing param in filter

= 1.2.1 =
Fix - Admin enqueue stylesheets only on carousel pages/edit.

= 1.2 =
Add Parameter to action hooks for carousel ID.

= 1.1.1 =
Minor fix on admin switching between Product choices.

= 1.1 =
Added Taxonomy Carousel and filter for main Query.

= 1.0.8 =
Fix issue with multiple WC Categories

= 1.0.7 =
Add multi selection for Taxonomy Terms.

= 1.0.6 =

Add Placeholder image for no-image

= 1.0.4 =

Fix Error with AQ Resize

= 1.0.3 =
Add Thumbnail Image Sizes

= 1.0.2 =
Allow for empty excerpt under title.

= 1.0.1 =
Change admin script to only enqueu on carousel custom post type.

= 1.0 =
Initial Release
