<?php
/**
 * Child-Theme functions and definitions
 * Includes performance, SEO, and speed optimizations
 */


// =====================================================================
// 0. PREVENT LITESPEED PAGE CACHING (stale HTML breaks cotizador defaults)
// =====================================================================
add_action( 'send_headers', 'cdski_no_cache_headers' );
function cdski_no_cache_headers() {
    // Only disable caching on pages that contain the cotizador form
    if ( ! is_front_page() && ! is_home() ) {
        return;
    }
    // Tell LiteSpeed server not to cache this page
    header( 'X-LiteSpeed-Cache-Control: no-cache' );
    // Standard no-cache headers as fallback
    header( 'Cache-Control: no-cache, no-store, must-revalidate' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );
}


// =====================================================================
// 0b. HIDE SEO CONTENT PAGES FROM MAIN NAVIGATION MENU
// =====================================================================
add_action( 'wp_head', 'cdski_hide_seo_menu_items' );
function cdski_hide_seo_menu_items() {
    ?>
    <style id="cdski-hide-seo-menu-items">
    /* Hide SEO content pages from main nav (they are in the unified footer links) */
    #menu-item-6937,
    #menu-item-6938,
    #menu-item-6939,
    #menu-item-6940,
    #menu-item-6941,
    #menu-item-6942 {
        display: none !important;
    }
    /* Center the main navigation menu */
    .top_panel_style_6 .top_panel_middle {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .top_panel_style_6 .menu_main_wrap {
        float: none !important;
        flex: 1 !important;
        display: flex !important;
        justify-content: center !important;
    }
    .top_panel_style_6 .menu_main_wrap > nav {
        display: inline-block !important;
        text-align: center !important;
    }
    .top_panel_style_6 .menu_main_nav {
        display: flex !important;
        justify-content: center !important;
    }
    </style>
    <?php
}

// =====================================================================
// 1. ENQUEUE PARENT STYLES
// =====================================================================
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// =====================================================================
// 2. RESOURCE HINTS: DNS-PREFETCH & PRECONNECT
// =====================================================================
add_action( 'wp_head', 'cdski_add_resource_hints', 1 );
function cdski_add_resource_hints() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//api.whatsapp.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//clasesdeski.cl">' . "\n";
}

// =====================================================================
// 3. DISABLE WORDPRESS EMOJI SCRIPTS (performance)
// =====================================================================
add_action( 'init', 'cdski_disable_emojis' );
function cdski_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'cdski_disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'cdski_disable_emojis_remove_dns_prefetch', 10, 2 );
}

function cdski_disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    }
    return array();
}

function cdski_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
        $urls = array_filter( $urls, function( $url ) use ( $emoji_svg_url ) {
            return false === strpos( $url, $emoji_svg_url );
        });
    }
    return $urls;
}

// =====================================================================
// 4. REMOVE QUERY STRINGS FROM STATIC RESOURCES (better caching)
// =====================================================================
add_filter( 'script_loader_src', 'cdski_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'cdski_remove_script_version', 15, 1 );
function cdski_remove_script_version( $src ) {
    // Keep version string on our form-flow JS so cache busts work
    if ( $src && strpos( $src, 'cdski-form-flow' ) !== false ) {
        return $src;
    }
    if ( $src && strpos( $src, 'ver=' ) !== false ) {
        $parts = explode( '?ver', $src );
        return $parts[0];
    }
    return $src;
}

// =====================================================================
// 5. DEFER NON-CRITICAL JAVASCRIPT
// =====================================================================
add_filter( 'script_loader_tag', 'cdski_defer_scripts', 10, 3 );
function cdski_defer_scripts( $tag, $handle, $src ) {
    // Don't defer in admin or for jQuery core
    if ( is_admin() ) {
        return $tag;
    }

    // Scripts to NOT defer (critical for page rendering)
    $no_defer = array( 'jquery-core', 'jquery', 'jquery-migrate', 'wp-polyfill' );

    if ( in_array( $handle, $no_defer, true ) ) {
        return $tag;
    }

    // Add defer attribute if not already present
    if ( strpos( $tag, ' defer' ) === false && strpos( $tag, ' async' ) === false ) {
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }

    return $tag;
}

// =====================================================================
// 6. REMOVE JQUERY MIGRATE (not needed for modern sites)
// =====================================================================
add_action( 'wp_default_scripts', 'cdski_remove_jquery_migrate' );
function cdski_remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}

// =====================================================================
// 7. DISABLE GUTENBERG BLOCK LIBRARY CSS ON FRONTEND (if not using blocks)
// =====================================================================
add_action( 'wp_enqueue_scripts', 'cdski_remove_block_css', 100 );
function cdski_remove_block_css() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
    wp_dequeue_style( 'global-styles' );
}

// =====================================================================
// 8. REMOVE WORDPRESS GENERATOR TAG AND OTHER UNNECESSARY HEAD TAGS
// =====================================================================
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

// =====================================================================
// 9. DISABLE XML-RPC (security + performance)
// =====================================================================
add_filter( 'xmlrpc_enabled', '__return_false' );

// =====================================================================
// 10. OPTIMIZE WOOCOMMERCE: REMOVE SCRIPTS FROM NON-WC PAGES
// =====================================================================
add_action( 'wp_enqueue_scripts', 'cdski_dequeue_woocommerce_scripts', 99 );
function cdski_dequeue_woocommerce_scripts() {
    if ( function_exists( 'is_woocommerce' ) ) {
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() && ! is_product() && ! is_product_category() && ! is_shop() ) {
            // Dequeue WooCommerce styles
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'wc-blocks-vendors-style' );
            wp_dequeue_style( 'wc-blocks-style' );

            // Dequeue WooCommerce scripts
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'wc-add-to-cart' );

            // Remove WC generator tag
            remove_action( 'wp_head', array( 'WC_Frontend_Scripts', 'localize_printed_scripts' ), 5 );
        }
    }
}

// =====================================================================
// 11. ADD NATIVE LAZY LOADING TO IMAGES (for older WP or custom imgs)
// =====================================================================
add_filter( 'wp_get_attachment_image_attributes', 'cdski_add_lazy_loading', 10, 2 );
function cdski_add_lazy_loading( $attr, $attachment ) {
    if ( ! isset( $attr['loading'] ) ) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}

// =====================================================================
// 12. ADD FETCHPRIORITY TO LCP IMAGE (above-the-fold hero)
// =====================================================================
add_action( 'wp_head', 'cdski_preload_hero_image', 2 );
function cdski_preload_hero_image() {
    if ( is_front_page() || is_home() ) {
        // Preload the Revolution Slider hero image for faster LCP
        echo '<link rel="preload" as="image" href="https://clasesdeski.cl/wp-content/uploads/revslider/slider_1/ski-cdski.png" fetchpriority="high">' . "\n";
    }
}

// =====================================================================
// 13. SEO: STRUCTURED DATA (LocalBusiness Schema)
// =====================================================================
add_action( 'wp_head', 'cdski_add_structured_data', 5 );
function cdski_add_structured_data() {
    if ( is_front_page() || is_home() ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'CDSKI - Clases de Ski y Snowboard',
            'alternateName' => 'Clases de Ski Chile',
            'description' => 'Experiencia de Guia y Clases de Ski y Snowboard en Valle Nevado, Colorado, La Parva, Santiago, Chile. Clases grupales e individuales para todas las edades.',
            'url' => 'https://www.clasesdeski.cl',
            'telephone' => '+56940211459',
            'email' => 'info@clasesdeski.cl',
            'image' => 'https://clasesdeski.cl/wp-content/uploads/revslider/slider_1/ski-cdski.png',
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => 'Mall Sport, Las Condes',
                'addressLocality' => 'Santiago',
                'addressRegion' => 'Region Metropolitana',
                'addressCountry' => 'CL'
            ),
            'geo' => array(
                '@type' => 'GeoCoordinates',
                'latitude' => '-33.4028',
                'longitude' => '-70.5756'
            ),
            'openingHours' => 'Mo-Su 08:00-22:00',
            'priceRange' => '$$',
            'sameAs' => array(
                'https://www.facebook.com/clasesdeski',
                'https://www.instagram.com/clasesdeski'
            ),
            'hasOfferCatalog' => array(
                '@type' => 'OfferCatalog',
                'name' => 'Clases de Ski y Snowboard',
                'itemListElement' => array(
                    array(
                        '@type' => 'Offer',
                        'itemOffered' => array(
                            '@type' => 'Service',
                            'name' => 'Clases de Ski Grupales',
                            'description' => 'Clases de ski para grupos familiares en Valle Nevado, Colorado y La Parva'
                        )
                    ),
                    array(
                        '@type' => 'Offer',
                        'itemOffered' => array(
                            '@type' => 'Service',
                            'name' => 'Clases de Snowboard',
                            'description' => 'Clases de snowboard individuales y grupales para todos los niveles'
                        )
                    ),
                    array(
                        '@type' => 'Offer',
                        'itemOffered' => array(
                            '@type' => 'Service',
                            'name' => 'Experiencia de Guia en la Nieve',
                            'description' => 'Servicio de guia personalizado en centros de ski de Chile'
                        )
                    )
                )
            )
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
    }
}

// =====================================================================
// 14. SEO: OPEN GRAPH & TWITTER CARD META TAGS
// =====================================================================
add_action( 'wp_head', 'cdski_add_open_graph_meta', 5 );
function cdski_add_open_graph_meta() {
    $site_name = 'CDSKI - Clases de Ski y Snowboard en Chile';
    $default_image = 'https://clasesdeski.cl/wp-content/uploads/revslider/slider_1/ski-cdski.png';
    $default_description = 'Experiencia de Guia y Clases de Ski y Snowboard en Valle Nevado, Colorado, La Parva, Santiago, Chile. Clases grupales e individuales para todas las edades y niveles.';

    if ( is_front_page() || is_home() ) {
        $title = $site_name;
        $description = $default_description;
        $url = home_url( '/' );
        $image = $default_image;
    } elseif ( is_singular() ) {
        $title = get_the_title() . ' | ' . $site_name;
        $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30, '...' );
        $url = get_permalink();
        $image = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : $default_image;
    } else {
        $title = wp_title( '|', false, 'right' ) . $site_name;
        $description = $default_description;
        $url = home_url( $_SERVER['REQUEST_URI'] );
        $image = $default_image;
    }

    $description = esc_attr( wp_strip_all_tags( $description ) );

    // Open Graph
    echo '<meta property="og:locale" content="es_CL">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . $description . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
    echo '<meta property="og:image:width" content="1200">' . "\n";
    echo '<meta property="og:image:height" content="630">' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
}

// =====================================================================
// 15. SEO: ADD META DESCRIPTION IF YOAST/OTHER SEO PLUGIN DOESN'T
// =====================================================================
add_action( 'wp_head', 'cdski_add_meta_description', 3 );
function cdski_add_meta_description() {
    // Only add if Yoast SEO is not handling it (check for wpseo)
    if ( defined( 'WPSEO_VERSION' ) ) {
        return; // Yoast is active, let it handle meta description
    }

    $description = 'Experiencia de Guia y Clases de Ski y Snowboard en Valle Nevado, Colorado, La Parva, Santiago, Chile. Clases grupales e individuales para todas las edades.';

    if ( is_singular() && has_excerpt() ) {
        $description = get_the_excerpt();
    }

    echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '">' . "\n";
}

// =====================================================================
// 16. PRELOAD CRITICAL FONTS
// =====================================================================
add_action( 'wp_head', 'cdski_preload_fonts', 2 );
function cdski_preload_fonts() {
    // Preload the main icon font used by the theme
    $theme_uri = get_template_directory_uri();
    echo '<link rel="preload" href="' . esc_url( $theme_uri . '/css/fontello/font/fontello.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
}

// =====================================================================
// 17. DISABLE SELF-PINGBACKS
// =====================================================================
add_action( 'pre_ping', 'cdski_disable_self_pingbacks' );
function cdski_disable_self_pingbacks( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link ) {
        if ( 0 === strpos( $link, $home ) ) {
            unset( $links[$l] );
        }
    }
}

// =====================================================================
// 18. LIMIT POST REVISIONS (performance)
// =====================================================================
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
    define( 'WP_POST_REVISIONS', 5 );
}

// =====================================================================
// 19. ADD WEBP SUPPORT TO MEDIA UPLOADS
// =====================================================================
add_filter( 'upload_mimes', 'cdski_allow_webp_upload' );
function cdski_allow_webp_upload( $mimes ) {
    $mimes['webp'] = 'image/webp';
    $mimes['avif'] = 'image/avif';
    return $mimes;
}

// =====================================================================
// 20. OPTIMIZE HEARTBEAT API (reduce server load)
// =====================================================================
add_action( 'init', 'cdski_optimize_heartbeat', 1 );
function cdski_optimize_heartbeat() {
    // Disable heartbeat on frontend entirely
    if ( ! is_admin() ) {
        wp_deregister_script( 'heartbeat' );
    }
}

// =====================================================================
// 21. REMOVE DASHICONS FROM FRONTEND (for non-logged-in users)
// =====================================================================
add_action( 'wp_enqueue_scripts', 'cdski_remove_dashicons', 99 );
function cdski_remove_dashicons() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}

// =====================================================================
// 22. ADD CANONICAL URL (if not handled by SEO plugin)
// =====================================================================
add_action( 'wp_head', 'cdski_add_canonical_url', 4 );
function cdski_add_canonical_url() {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return; // Yoast handles canonical
    }

    if ( is_front_page() || is_home() ) {
        echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
    } elseif ( is_singular() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
    }
}

// =====================================================================
// 23. DISABLE EMBEDS (removes wp-embed.min.js)
// =====================================================================
add_action( 'init', 'cdski_disable_embeds_code_init', 9999 );
function cdski_disable_embeds_code_init() {
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'cdski_disable_embeds_tiny_mce_plugin' );
    add_filter( 'rewrite_rules_array', 'cdski_disable_embeds_rewrites' );
    remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

function cdski_disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}

function cdski_disable_embeds_rewrites( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( false !== strpos( $rewrite, 'embed=true' ) ) {
            unset( $rules[$rule] );
        }
    }
    return $rules;
}

// =====================================================================
// 24. ADD LANGUAGE HREFLANG TAGS (for bilingual CL/BR content)
// =====================================================================
add_action( 'wp_head', 'cdski_add_hreflang_tags', 5 );
function cdski_add_hreflang_tags() {
    if ( is_front_page() || is_home() ) {
        echo '<link rel="alternate" hreflang="es-CL" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
    }
}

// =====================================================================
// 25. MINIFY HTML OUTPUT (remove whitespace for faster page load)
// =====================================================================
add_action( 'template_redirect', 'cdski_start_html_minify' );
function cdski_start_html_minify() {
    if ( ! is_admin() && ! is_feed() && ! is_robots() && ! defined( 'DOING_AJAX' ) && ! defined( 'XMLRPC_REQUEST' ) ) {
        ob_start( 'cdski_minify_html' );
    }
}

function cdski_minify_html( $html ) {
    if ( empty( $html ) ) {
        return $html;
    }
    // Remove HTML comments (except IE conditionals and script/style blocks)
    $html = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|noindex|\/noindex)).*?-->/s', '', $html );
    // Remove whitespace between tags
    $html = preg_replace( '/>\s+</', '> <', $html );
    // Remove leading/trailing whitespace on lines
    $html = preg_replace( '/^\s+/m', '', $html );
    return $html;
}

// =====================================================================
// 26. ENQUEUE FORM FLOW SCRIPT (calculator -> booking summary)
// =====================================================================
add_action( 'wp_enqueue_scripts', 'cdski_enqueue_form_flow_scripts' );
function cdski_enqueue_form_flow_scripts() {
    // Load on all pages - the JS itself checks for #form_calculadora1
    // before executing, so it's safe to load globally.
    // Previously restricted to is_front_page()/is_home() but that
    // condition fails on some WordPress setups.
    wp_enqueue_script(
        'cdski-form-flow',
        get_stylesheet_directory_uri() . '/js/cdski-form-flow-v9.js',
        array( 'jquery' ),
        '9.2.1',
        true
    );
}

// =====================================================================
// 27. INLINE CSS FOR BOOKING SUMMARY CARD
// =====================================================================
// =====================================================================
// 27b. INLINE CSS FOR USD PRICE DISPLAY
// =====================================================================
add_action( 'wp_head', 'cdski_usd_price_styles' );
function cdski_usd_price_styles() {
    ?>
    <style id="cdski-usd-price-css">
    .cdski-usd-price {
        display: block;
        font-size: 12px;
        color: #94a3b8;
        font-weight: 400;
        margin-top: 2px;
    }
    .cdski-summary-total .cdski-usd-price {
        color: #fbbf24;
        font-size: 13px;
    }
    .cdski-price-strike .cdski-usd-price {
        text-decoration: line-through;
    }
    </style>
    <?php
}

add_action( 'wp_head', 'cdski_booking_summary_styles' );
function cdski_booking_summary_styles() {
    // Load on all pages - CSS is lightweight and scoped to #cdski-booking-summary
    // Previously restricted to is_front_page()/is_home() but that condition
    // fails on some WordPress setups (e.g. ?page_id=813 static front page).
    ?>
    <style id="cdski-booking-summary-css">
    #cdski-booking-summary {
        background: linear-gradient(135deg, #1a2332 0%, #2d3e50 100%);
        border-radius: 16px;
        padding: 0;
        margin: 0 0 28px 0;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        color: #fff;
    }
    .cdski-summary-header {
        background: linear-gradient(135deg, #f7941d 0%, #f15a22 100%);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 17px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .cdski-summary-header svg {
        flex-shrink: 0;
    }
    .cdski-summary-body {
        padding: 20px 24px 24px;
    }
    .cdski-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }
    .cdski-summary-label {
        color: #94a3b8;
        font-size: 14px;
        font-weight: 500;
    }
    .cdski-summary-value {
        font-size: 15px;
        font-weight: 600;
        color: #e2e8f0;
    }
    .cdski-summary-divider {
        height: 1px;
        background: rgba(255,255,255,0.1);
        margin: 10px 0;
    }
    .cdski-price-strike {
        text-decoration: line-through;
        color: #94a3b8 !important;
        font-size: 14px !important;
    }
    .cdski-summary-total {
        padding-top: 4px;
    }
    .cdski-price-final {
        font-size: 22px !important;
        font-weight: 800 !important;
        color: #f7941d !important;
    }
    /* Hide Quantity and Product (WooCommerce) fields on page 2 */
    #form_calculadora1 .frm_form_field input[name="item_meta[60]"],
    #form_calculadora1 .frm_form_field select[name="item_meta[61]"],
    #form_calculadora1 .frm_form_field select[name="item_meta[62]"] {
        display: none !important;
    }
    #frm_field_60_container,
    #frm_field_61_container,
    #frm_field_62_container {
        display: none !important;
    }
    </style>
    <?php
}

// =====================================================================
// 28. FORMIDABLE FORMS: CUSTOM EMAIL ON SUBMISSION (form_id = 2)
//     Sends modern HTML email to both client and admin
// =====================================================================
add_action( 'frm_after_create_entry', 'cdski_send_booking_emails', 30, 2 );
function cdski_send_booking_emails( $entry_id, $form_id ) {
    // Only for form id 2 (calculadora1)
    if ( (int) $form_id !== 2 ) {
        return;
    }

    // Get the entry data - ensure Formidable Pro classes are available
    if ( ! class_exists( 'FrmEntry' ) ) {
        return;
    }

    $entry = FrmEntry::getOne( $entry_id, true );
    if ( ! $entry ) {
        return;
    }

    $metas = $entry->metas;

    // Field mappings (from form analysis)
    $personas     = isset( $metas[17] ) ? $metas[17] : '';
    $plan         = isset( $metas[16] ) ? $metas[16] : '';

    // The form has conditional price field pairs per persona count.
    // Each pair only stores a value when the matching persona is selected.
    // Precio / Precio con Descuento field IDs per persona:
    //   Dos:    8/18,  86/87
    //   Tres:   19/20, 88/89
    //   Cuatro: 28/30, 90/91
    //   Cinco:  31/29, 92/93
    $price_pairs = array(
        array( 8, 18 ),
        array( 19, 20 ),
        array( 28, 30 ),
        array( 31, 29 ),
        array( 86, 87 ),
        array( 88, 89 ),
        array( 90, 91 ),
        array( 92, 93 ),
    );
    $precio      = '';
    $precio_desc = '';
    foreach ( $price_pairs as $pair ) {
        $p  = isset( $metas[ $pair[0] ] ) ? trim( $metas[ $pair[0] ] ) : '';
        $pd = isset( $metas[ $pair[1] ] ) ? trim( $metas[ $pair[1] ] ) : '';
        if ( $p !== '' && floatval( str_replace( '.', '', $p ) ) > 0 ) {
            $precio      = $p;
            $precio_desc = $pd;
            break;
        }
    }

    // Page 2 fields - contact / booking info (verified from live site)
    // 24=Fecha, 27=Tomo conocimiento, 32=Nombre, 33=Apellido,
    // 36=Email, 37=Telefono, 60=Quantity(WC), 61=Product(WC), 62=Product(WC)
    $fecha        = isset( $metas[24] ) ? $metas[24] : '';
    $nombre       = isset( $metas[32] ) ? $metas[32] : '';
    $apellido     = isset( $metas[33] ) ? $metas[33] : '';
    $email_client = isset( $metas[36] ) ? $metas[36] : '';
    $telefono     = isset( $metas[37] ) ? $metas[37] : '';
    $comentarios  = '';
    $centro_ski   = '';
    $nivel        = '';
    $disciplina   = '';

    // Combine nombre + apellido
    if ( ! empty( $apellido ) ) {
        $nombre = trim( $nombre . ' ' . $apellido );
    }

    // If email_client is empty, try other fields that might hold email
    if ( empty( $email_client ) ) {
        // Check all metas for something that looks like an email
        foreach ( $metas as $key => $val ) {
            if ( is_string( $val ) && is_email( $val ) ) {
                $email_client = $val;
                break;
            }
        }
    }

    // If nombre is empty, try to build from available fields
    if ( empty( $nombre ) ) {
        foreach ( $metas as $key => $val ) {
            if ( is_string( $val ) && strlen( $val ) > 2 && strlen( $val ) < 100 && ! is_email( $val ) && ! is_numeric( $val ) && $key > 20 ) {
                $nombre = $val;
                break;
            }
        }
    }

    // Format prices
    $precio_fmt      = '$' . number_format( (float) $precio, 0, ',', '.' );
    $precio_desc_fmt = '$' . number_format( (float) $precio_desc, 0, ',', '.' );

    // Map personas to number
    $personas_map = array( 'Dos' => '2', 'Tres' => '3', 'Cuatro' => '4', 'Cinco' => '5' );
    $personas_num = isset( $personas_map[ $personas ] ) ? $personas_map[ $personas ] : $personas;

    // Build the modern HTML email
    $html = cdski_build_email_html( array(
        'nombre'       => $nombre,
        'personas'     => $personas,
        'personas_num' => $personas_num,
        'plan'         => $plan,
        'precio'       => $precio_fmt,
        'precio_desc'  => $precio_desc_fmt,
        'email'        => $email_client,
        'telefono'     => $telefono,
        'fecha'        => $fecha,
        'centro_ski'   => $centro_ski,
        'nivel'        => $nivel,
        'comentarios'  => $comentarios,
        'disciplina'   => $disciplina,
        'entry_id'     => $entry_id,
        'all_metas'    => $metas,
    ) );

    $subject_admin  = 'Nueva Reserva de Clase #' . $entry_id . ' - ' . esc_html( $nombre ?: 'Cliente' );
    $subject_client = 'Tu Reserva de Clase con CDSKI - Confirmacion #' . $entry_id;

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: CDSKI Clases de Ski <info@clasesdeski.cl>',
        'Reply-To: info@clasesdeski.cl',
    );

    // Send to admin
    wp_mail( 'info@clasesdeski.cl', $subject_admin, $html, $headers );

    // Send to client (if we have their email)
    if ( ! empty( $email_client ) && is_email( $email_client ) ) {
        wp_mail( $email_client, $subject_client, $html, $headers );
    }
}

/**
 * Build the modern HTML email template
 */
function cdski_build_email_html( $data ) {
    $nombre       = esc_html( $data['nombre'] ?: 'Estimado/a Cliente' );
    $personas     = esc_html( $data['personas'] );
    $personas_num = esc_html( $data['personas_num'] );
    $plan         = esc_html( $data['plan'] );
    $precio       = esc_html( $data['precio'] );
    $precio_desc  = esc_html( $data['precio_desc'] );
    $email        = esc_html( $data['email'] );
    $telefono     = esc_html( $data['telefono'] );
    $fecha        = esc_html( $data['fecha'] );
    $centro_ski   = esc_html( $data['centro_ski'] );
    $nivel        = esc_html( $data['nivel'] );
    $comentarios  = esc_html( $data['comentarios'] );
    $disciplina   = esc_html( $data['disciplina'] );
    $entry_id     = intval( $data['entry_id'] );
    $all_metas    = $data['all_metas'];
    $date_now     = wp_date( 'j \d\e F, Y - H:i', null, new DateTimeZone( 'America/Santiago' ) );

    // Build extra fields row if they have values
    $extra_rows = '';
    if ( ! empty( $telefono ) ) {
        $extra_rows .= cdski_email_detail_row( 'Telefono', $telefono );
    }
    if ( ! empty( $email ) ) {
        $extra_rows .= cdski_email_detail_row( 'Email', $email );
    }
    if ( ! empty( $fecha ) ) {
        $extra_rows .= cdski_email_detail_row( 'Fecha Preferida', $fecha );
    }
    if ( ! empty( $centro_ski ) ) {
        $extra_rows .= cdski_email_detail_row( 'Centro de Ski', $centro_ski );
    }
    if ( ! empty( $nivel ) ) {
        $extra_rows .= cdski_email_detail_row( 'Nivel', $nivel );
    }
    if ( ! empty( $disciplina ) && $disciplina !== '1' ) {
        $extra_rows .= cdski_email_detail_row( 'Disciplina', $disciplina );
    }
    if ( ! empty( $comentarios ) ) {
        $extra_rows .= cdski_email_detail_row( 'Comentarios', $comentarios );
    }

    // Include any remaining filled non-system meta fields
    $known_fields = array( 0, 16, 17, 19, 20, 24, 27, 32, 33, 34, 35, 36, 37, 60, 61, 62, 8, 18, 28, 29, 30, 31, 86, 87, 88, 89, 90, 91, 92, 93 );
    $additional = '';
    foreach ( $all_metas as $key => $val ) {
        if ( in_array( (int) $key, $known_fields, true ) ) {
            continue;
        }
        if ( ! empty( $val ) && is_string( $val ) && strlen( $val ) > 0 && strlen( $val ) < 500 ) {
            $additional .= cdski_email_detail_row( 'Campo #' . $key, esc_html( $val ) );
        }
    }

    $html = '<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reserva CDSKI</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f2f5;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen,Ubuntu,Cantarell,sans-serif;">

<!-- Preheader (hidden preview text) -->
<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">
    Reserva de Clase #' . $entry_id . ' - ' . $personas_num . ' personas, ' . $plan . ' - ' . $precio_desc . '
</div>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f0f2f5;">
<tr><td align="center" style="padding:32px 16px;">

<!-- Main Container -->
<table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

<!-- Header with gradient -->
<tr>
<td style="background:linear-gradient(135deg,#1a2332 0%,#0f4c75 50%,#1a2332 100%);padding:40px 40px 32px;text-align:center;">
    <img src="https://clasesdeski.cl/wp-content/uploads/2021/10/cdski.svg" alt="CDSKI" width="120" style="max-width:120px;margin-bottom:16px;" />
    <h1 style="color:#ffffff;font-size:26px;font-weight:800;margin:0 0 8px;letter-spacing:-0.5px;">Reserva de Clase</h1>
    <p style="color:#94a3b8;font-size:14px;margin:0;">Confirmacion #' . $entry_id . ' &bull; ' . $date_now . '</p>
</td>
</tr>

<!-- Greeting -->
<tr>
<td style="padding:32px 40px 0;">
    <p style="color:#1a2332;font-size:17px;margin:0 0 4px;">Hola <strong>' . $nombre . '</strong>,</p>
    <p style="color:#64748b;font-size:15px;margin:0;line-height:1.6;">
        Hemos recibido tu solicitud de reserva de clase. A continuacion el detalle de tu pedido:
    </p>
</td>
</tr>

<!-- Class Details Card -->
<tr>
<td style="padding:24px 40px;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:linear-gradient(135deg,#1a2332 0%,#2d3e50 100%);border-radius:16px;overflow:hidden;">
    <tr>
    <td style="background:linear-gradient(135deg,#f7941d 0%,#f15a22 100%);padding:14px 24px;">
        <p style="color:#ffffff;font-size:15px;font-weight:700;margin:0;">Detalle de la Clase</p>
    </td>
    </tr>
    <tr>
    <td style="padding:20px 24px 24px;">
        ' . cdski_email_detail_row( 'Personas', $personas_num . ' (' . $personas . ')' ) . '
        ' . cdski_email_detail_row( 'Modalidad', $plan ) . '
        <!-- Divider -->
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr><td style="padding:8px 0;"><div style="height:1px;background:rgba(255,255,255,0.1);"></div></td></tr>
        </table>
        ' . cdski_email_detail_row( 'Precio Regular', '<span style="text-decoration:line-through;color:#94a3b8;">' . $precio . '</span>' ) . '
        <!-- Final Price -->
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td style="padding:6px 0;color:#94a3b8;font-size:14px;">Precio con Descuento</td>
            <td style="padding:6px 0;text-align:right;">
                <span style="color:#f7941d;font-size:24px;font-weight:800;">' . $precio_desc . '</span>
            </td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>';

    // Contact / Additional Info section (if we have any data)
    if ( ! empty( $extra_rows ) || ! empty( $additional ) ) {
        $html .= '
<!-- Contact / Additional Info -->
<tr>
<td style="padding:0 40px 24px;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
    <tr>
    <td style="padding:16px 20px 4px;">
        <p style="color:#1a2332;font-size:14px;font-weight:700;margin:0 0 12px;text-transform:uppercase;letter-spacing:0.5px;">Informacion de Contacto</p>
    </td>
    </tr>
    <tr>
    <td style="padding:0 20px 16px;">
        ' . $extra_rows . $additional . '
    </td>
    </tr>
    </table>
</td>
</tr>';
    }

    // Included items
    $html .= '
<!-- Whats Included -->
<tr>
<td style="padding:0 40px 24px;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr><td style="padding-bottom:12px;"><p style="color:#1a2332;font-size:14px;font-weight:700;margin:0;text-transform:uppercase;letter-spacing:0.5px;">Incluido en tu Clase</p></td></tr>
    <tr><td style="padding:0 0 6px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
        <td style="padding-right:10px;vertical-align:top;color:#22c55e;font-size:16px;">&#10003;</td>
        <td style="color:#475569;font-size:14px;line-height:1.5;">Ticket y Equipo directamente en el Centro de Ski</td>
        </tr></table>
    </td></tr>
    <tr><td style="padding:0 0 6px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
        <td style="padding-right:10px;vertical-align:top;color:#22c55e;font-size:16px;">&#10003;</td>
        <td style="color:#475569;font-size:14px;line-height:1.5;">Comienzo a las 11:00 hrs por seguridad / condicion de las pistas</td>
        </tr></table>
    </td></tr>
    <tr><td style="padding:0 0 6px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
        <td style="padding-right:10px;vertical-align:top;color:#22c55e;font-size:16px;">&#10003;</td>
        <td style="color:#475569;font-size:14px;line-height:1.5;">Fotos y Videos de experiencia incluidos</td>
        </tr></table>
    </td></tr>
    </table>
</td>
</tr>

<!-- CTA Button -->
<tr>
<td style="padding:0 40px 32px;" align="center">
    <a href="https://wa.me/56940211459?text=%C2%A1Hola%20CDSKI!%20Tengo%20la%20reserva%20%23' . $entry_id . '" style="display:inline-block;background:linear-gradient(135deg,#f7941d 0%,#f15a22 100%);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;font-size:15px;font-weight:700;letter-spacing:0.3px;">
        Contactar por WhatsApp
    </a>
</td>
</tr>

<!-- Footer -->
<tr>
<td style="background:#f8fafc;padding:24px 40px;border-top:1px solid #e2e8f0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
    <td style="text-align:center;">
        <p style="color:#64748b;font-size:13px;margin:0 0 4px;">CDSKI - Experiencia de Guia &amp; Clases de Ski y Snowboard</p>
        <p style="color:#94a3b8;font-size:12px;margin:0 0 4px;">Mall Sport, Las Condes, Santiago, Chile</p>
        <p style="color:#94a3b8;font-size:12px;margin:0 0 12px;">
            <a href="mailto:info@clasesdeski.cl" style="color:#f7941d;text-decoration:none;">info@clasesdeski.cl</a> &bull;
            <a href="https://clasesdeski.cl" style="color:#f7941d;text-decoration:none;">clasesdeski.cl</a>
        </p>
        <p style="color:#cbd5e1;font-size:11px;margin:0;">&copy; ' . date( 'Y' ) . ' CDSKI. Todos los derechos reservados.</p>
    </td>
    </tr>
    </table>
</td>
</tr>

</table>
<!-- End Main Container -->

</td></tr>
</table>

</body>
</html>';

    return $html;
}

/**
 * Helper: build a detail row for the email (dark background version)
 */
function cdski_email_detail_row( $label, $value ) {
    return '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td style="padding:6px 0;color:#94a3b8;font-size:14px;width:45%;">' . esc_html( $label ) . '</td>
        <td style="padding:6px 0;text-align:right;color:#e2e8f0;font-size:15px;font-weight:600;">' . $value . '</td>
    </tr>
    </table>';
}

// =====================================================================
// 32. HOMEPAGE UNIFIED FOOTER: Brand info, links, weather, contact
// =====================================================================
add_action( 'wp_footer', 'cdski_homepage_seo_content', 5 );
function cdski_homepage_seo_content() {
    if ( ! is_front_page() && ! is_home() ) {
        return;
    }
    ?>
    <style>
    /* Inline critical CSS for unified footer (bypasses LiteSpeed CSS cache) */
    .cdski-unified-footer{background:#111!important;padding:60px 0 40px!important;color:#c0c8d0!important;font-size:15px!important;line-height:1.7!important}
    .cdski-uf-grid{display:grid!important;grid-template-columns:1.4fr 1fr 1fr 1.2fr!important;gap:40px!important;align-items:start!important}
    .cdski-uf-title{font-size:22px!important;font-weight:800!important;color:#fff!important;margin:0 0 14px!important;letter-spacing:.5px!important}
    .cdski-uf-col-title{font-size:17px!important;font-weight:700!important;color:#c8a951!important;text-transform:uppercase!important;letter-spacing:1.5px!important;margin:0 0 18px!important;padding-bottom:10px!important;border-bottom:2px solid rgba(200,169,81,.3)!important}
    .cdski-uf-motto{font-style:italic!important;color:#c8a951!important;font-size:14px!important;margin:10px 0 18px!important}
    .cdski-uf-links ul{list-style:none!important;margin:0!important;padding:0!important}
    .cdski-uf-links li{margin-bottom:10px!important}
    .cdski-uf-links a{color:#c0c8d0!important;text-decoration:none!important;transition:color .2s,padding-left .2s!important;font-size:14px!important}
    .cdski-uf-links a:hover{color:#c8a951!important;padding-left:6px!important}
    .cdski-uf-contact ul{list-style:none!important;margin:0!important;padding:0!important}
    .cdski-uf-contact li{margin-bottom:12px!important;display:flex!important;align-items:flex-start!important;gap:10px!important}
    .cdski-uf-contact a{color:#c8a951!important;text-decoration:none!important}
    .cdski-uf-btn{display:inline-block!important;padding:10px 22px!important;border-radius:6px!important;font-size:13px!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:1px!important;text-decoration:none!important;transition:all .25s!important;margin-right:10px!important;margin-bottom:8px!important}
    .cdski-uf-btn-primary{background:#c8a951!important;color:#111!important}
    .cdski-uf-btn-primary:hover{background:#d4b96a!important;transform:translateY(-2px)!important}
    .cdski-uf-btn-outline{border:2px solid rgba(200,169,81,.5)!important;color:#c8a951!important;background:transparent!important}
    .cdski-uf-btn-outline:hover{border-color:#c8a951!important;background:rgba(200,169,81,.1)!important}
    .cdski-uf-seo{margin-top:30px!important;padding-top:20px!important;border-top:1px solid rgba(255,255,255,.08)!important;font-size:13px!important;color:#8a9aaa!important;text-align:center!important}
    .cdski-uf-seo a{color:#b8942e!important;text-decoration:none!important}
    .cdski-uf-social .share_wrap{display:flex!important;gap:12px!important;margin-top:14px!important}
    .cdski-uf-social .share_wrap a{color:#c0c8d0!important;font-size:18px!important;transition:color .2s!important}
    .cdski-uf-social .share_wrap a:hover{color:#c8a951!important}
    @media(max-width:960px){.cdski-uf-grid{grid-template-columns:1fr 1fr!important;gap:30px!important}}
    @media(max-width:600px){.cdski-uf-grid{grid-template-columns:1fr!important;gap:24px!important}.cdski-unified-footer{padding:40px 0 30px!important}}
    </style>
    <footer class="cdski-unified-footer" aria-label="CDSKI Chile Information">
        <div class="content_wrap">
            <div class="cdski-uf-grid">

                <!-- Column 1: Brand -->
                <div class="cdski-uf-col cdski-uf-brand">
                    <h3 class="cdski-uf-title">CDSKI Chile</h3>
                    <p>Experiencia de Gu&iacute;a &amp; Clases de Ski y Snowboard en Valle Nevado, Colorado y La Parva.</p>
                    <p class="cdski-uf-motto">&ldquo;Nos divertimos y entretenemos mientras aprendemos.&rdquo;</p>
                    <div class="cdski-uf-buttons">
                        <a href="/clases-ski-snowboard/" class="cdski-uf-btn cdski-uf-btn-primary">Ver Clases</a>
                        <a href="/experiencia-cdski/" class="cdski-uf-btn cdski-uf-btn-outline">La Experiencia</a>
                    </div>
                    <div class="cdski-uf-social" id="cdski-uf-social-target"></div>
                </div>

                <!-- Column 2: Explore links -->
                <div class="cdski-uf-col cdski-uf-links">
                    <h4 class="cdski-uf-col-title">Explora CDSKI</h4>
                    <ul>
                        <li><a href="/about-cdski/">Sobre CDSKI Chile</a></li>
                        <li><a href="/nuestro-metodo/">Nuestro M&eacute;todo</a></li>
                        <li><a href="/niveles/">Niveles de Progreso</a></li>
                        <li><a href="/experiencia-cdski/">La Experiencia CDSKI</a></li>
                        <li><a href="/clases-ski-snowboard/">Clases de Ski y Snowboard</a></li>
                        <li><a href="/seguridad-montana/">Seguridad y Monta&ntilde;a</a></li>
                    </ul>
                </div>

                <!-- Column 3: Weather (moved from widget footer via JS) -->
                <div class="cdski-uf-col cdski-uf-weather" id="cdski-uf-weather-target">
                    <h4 class="cdski-uf-col-title">El Tiempo Hoy</h4>
                    <!-- Weather widget content will be moved here via JS -->
                </div>

                <!-- Column 4: Contact -->
                <div class="cdski-uf-col cdski-uf-contact">
                    <h4 class="cdski-uf-col-title">Cont&aacute;ctanos</h4>
                    <ul>
                        <li><span class="cdski-uf-icon">&#x1F4CD;</span> Mallsport, Las Condes</li>
                        <li><span class="cdski-uf-icon">&#x2709;</span> <a href="mailto:info@clasesdeski.cl">info@clasesdeski.cl</a></li>
                        <li><span class="cdski-uf-icon">&#x1F552;</span> 8:00 am &ndash; 10:00 pm, Lunes a Domingo</li>
                    </ul>
                </div>

            </div>

            <!-- SEO keywords block (visually hidden but crawlable) -->
            <div class="cdski-uf-seo">
                <p>CDSKI ofrece <a href="/clases-ski-snowboard/">clases de ski en Chile</a>, <a href="/clases-ski-snowboard/">clases de snowboard en Chile</a>, <a href="/clases-ski-snowboard/">clases de ski en Valle Nevado</a>, y <a href="/experiencia-cdski/">experiencias guiadas de ski</a> en los principales centros de ski de la Regi&oacute;n Metropolitana.</p>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move weather widget content into unified footer
        var weatherWidget = document.querySelector('#text-2 .textwidget');
        var weatherTarget = document.getElementById('cdski-uf-weather-target');
        if (weatherWidget && weatherTarget) {
            var weatherContent = weatherWidget.cloneNode(true);
            weatherTarget.appendChild(weatherContent);
        }

        // Move social icons into unified footer
        var socialWidget = document.querySelector('#text-2 .widget_socials .share_wrap, #widget_socials .share_wrap');
        var socialTarget = document.getElementById('cdski-uf-social-target');
        if (socialWidget && socialTarget) {
            var socialContent = socialWidget.cloneNode(true);
            socialTarget.appendChild(socialContent);
        }

        // Hide the original widget footer on homepage (now merged into unified footer)
        var widgetFooter = document.querySelector('.footer_wrap.widget_area');
        if (widgetFooter) {
            widgetFooter.style.display = 'none';
        }
    });
    </script>
    <?php
}

// =====================================================================
// 31. ENHANCED HREFLANG TAGS (Spanish primary + English secondary)
// =====================================================================
remove_action( 'wp_head', 'cdski_add_hreflang_tags', 5 );
add_action( 'wp_head', 'cdski_add_enhanced_hreflang_tags', 5 );
function cdski_add_enhanced_hreflang_tags() {
    $url = is_front_page() || is_home() ? home_url( '/' ) : get_permalink();
    echo '<link rel="alternate" hreflang="es-CL" href="' . esc_url( $url ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="es" href="' . esc_url( $url ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="en" href="' . esc_url( $url ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $url ) . '">' . "\n";
}

// =====================================================================
// 33. INTERNAL LINKING: Navigation for new content pages
// =====================================================================
add_action( 'wp_footer', 'cdski_internal_nav_footer', 10 );
function cdski_internal_nav_footer() {
    // Only show on new content pages
    if ( ! is_page( array( 'about-cdski', 'nuestro-metodo', 'niveles', 'experiencia-cdski', 'clases-ski-snowboard', 'seguridad-montana' ) ) ) {
        return;
    }
    ?>
    <nav class="cdski-internal-nav" aria-label="Páginas CDSKI">
        <div class="content_wrap">
            <h3>Explora CDSKI</h3>
            <div class="cdski-internal-nav-grid">
                <a href="/about-cdski/" class="cdski-nav-link">Sobre CDSKI</a>
                <a href="/nuestro-metodo/" class="cdski-nav-link">Nuestro Método</a>
                <a href="/niveles/" class="cdski-nav-link">Niveles de Progreso</a>
                <a href="/experiencia-cdski/" class="cdski-nav-link">La Experiencia CDSKI</a>
                <a href="/clases-ski-snowboard/" class="cdski-nav-link">Clases de Ski y Snowboard</a>
                <a href="/seguridad-montana/" class="cdski-nav-link">Seguridad y Montaña</a>
            </div>
        </div>
    </nav>
    <?php
}
