<?php
/**
 * Child-Theme functions and definitions
 * Includes performance, SEO, and speed optimizations
 */

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
