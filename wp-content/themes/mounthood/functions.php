<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'mounthood_theme_setup' ) ) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_theme_setup', 1 );
	function mounthood_theme_setup() {

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );

        // Custom header setup
        add_theme_support( 'custom-header', array('header-text'=>false));

        // Custom backgrounds setup
        add_theme_support( 'custom-background');

        // Supported posts formats
        add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

        // Autogenerate title tag
        add_theme_support('title-tag');

        // Add user menu
        add_theme_support('nav-menus');

        // WooCommerce Support
        add_theme_support( 'woocommerce' );

        // Add wide and full blocks support
        add_theme_support( 'align-wide' );

		// Register theme menus
		add_filter( 'mounthood_filter_add_theme_menus',		'mounthood_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'mounthood_filter_add_theme_sidebars',	'mounthood_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'mounthood_filter_importer_options',		'mounthood_set_importer_options' );

		// Add theme required plugins
		add_filter( 'mounthood_filter_required_plugins',		'mounthood_add_required_plugins' );
		
		// Add preloader styles
		add_filter('mounthood_filter_add_styles_inline',		'mounthood_head_add_page_preloader_styles');

		// Init theme after WP is created
		add_action( 'wp',									'mounthood_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'mounthood_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'mounthood_head_add_page_meta', 1);
		add_action('before',								'mounthood_body_add_gtm');
		add_action('before',								'mounthood_body_add_toc');
		add_action('before',								'mounthood_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'mounthood_footer_add_views_counter', 1);
		add_action('wp_footer',								'mounthood_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'mounthood_footer_add_scroll_to_top', 1);
		add_action('wp_footer',								'mounthood_footer_add_custom_html', 1);
		add_action('wp_footer',								'mounthood_footer_add_gtm2', 1);

		// Set list of the theme required plugins
		mounthood_storage_set('required_plugins', array(
			'booked',
			'wpcloudy',
			'revslider',
			'trx_utils',
			'essgrids',
			'visual_composer',
			'mailchimp',
			'woocommerce',
            'wp_gdpr_compliance',
            'contact_form_7'
            )
		);

		// Set list of the theme required custom fonts from folder /css/font-faces
		// Attention! Font's folder must have name equal to the font's name
		mounthood_storage_set('required_custom_fonts', array(
			'Amadeus'
			)
		);
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'mounthood_add_theme_menus' ) ) {
	//Handler of add_filter( 'mounthood_filter_add_theme_menus', 'mounthood_add_theme_menus' );
	function mounthood_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'mounthood_add_theme_sidebars' ) ) {
	//Handler of add_filter( 'mounthood_filter_add_theme_sidebars',	'mounthood_add_theme_sidebars' );
	function mounthood_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'mounthood' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'mounthood' )
			);
			if (function_exists('mounthood_exists_woocommerce') && mounthood_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'mounthood' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'mounthood_add_required_plugins' ) ) {
	//Handler of add_filter( 'mounthood_filter_required_plugins',		'mounthood_add_required_plugins' );
	function mounthood_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('ThemeRex Utilities', 'mounthood'),
			'version'	=> '3.2',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> mounthood_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}


//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'mounthood_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'mounthood_importer_set_options', 9 );
    function mounthood_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( MOUNTHOOD_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = MOUNTHOOD_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( mounthood_get_protocol().'://demofiles.axiomthemes.com/mounthood/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'booked',
                'wpcloudy',
                'revslider',
                'trx_utils',
                'essential-grid',
                'js_composer',
                'mailchimp-for-wp',
                'woocommerce',
                'contact-form-7'
            );

            $options['theme_slug'] = 'mounthood';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Education Demo', 'mounthood' );
            $options['files']['default']['domain_dev'] = esc_url(mounthood_get_protocol().'://mounthood.axiomthemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(mounthood_get_protocol().'://mounthood.axiomthemes.com'); // Demo-site domain

        }
        return $options;
    }
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('mounthood_body_classes') ) {
	//Handler of add_filter( 'body_class', 'mounthood_body_classes' );
	function mounthood_body_classes( $classes ) {

		$classes[] = 'mounthood_body';
		$classes[] = 'body_style_' . trim(mounthood_get_custom_option('body_style'));
		$classes[] = 'body_' . (mounthood_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(mounthood_get_custom_option('article_style'));
		
		$blog_style = mounthood_get_custom_option(is_singular() && !mounthood_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(mounthood_get_template_name($blog_style));
		
		$body_scheme = mounthood_get_custom_option('body_scheme');
		if (empty($body_scheme)  || mounthood_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = mounthood_get_custom_option('top_panel_position');
		if (!mounthood_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = mounthood_get_sidebar_class();

		if (mounthood_get_custom_option('show_video_bg')=='yes' && (mounthood_get_custom_option('video_bg_youtube_code')!='' || mounthood_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!mounthood_param_is_off(mounthood_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('mounthood_head_add_page_meta')) {
	//Handler of add_action('wp_head', 'mounthood_head_add_page_meta', 1);
	function mounthood_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (mounthood_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('mounthood_head_add_page_preloader_styles')) {
	//Handler of add_filter('mounthood_filter_add_styles_inline', 'mounthood_head_add_page_preloader_styles');
	function mounthood_head_add_page_preloader_styles($css) {
		if (($preloader=mounthood_get_theme_option('page_preloader'))!='none') {
			$image = mounthood_get_theme_option('page_preloader_image');
			$bg_clr = mounthood_get_scheme_color('bg_color');
			$link_clr = mounthood_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('mounthood_body_add_gtm')) {
	//Handler of add_action('before', 'mounthood_body_add_gtm');
	function mounthood_body_add_gtm() {
		mounthood_show_layout(mounthood_get_custom_option('gtm_code'));
	}
}

// Add TOC anchors to the beginning of the body 
if (!function_exists('mounthood_body_add_toc')) {
	//Handler of add_action('before', 'mounthood_body_add_toc');
	function mounthood_body_add_toc() {
		// Add TOC items 'Home' and "To top"
		if (mounthood_get_custom_option('menu_toc_home')=='yes')
			mounthood_show_layout(mounthood_sc_anchor(array(
				'id' => "toc_home",
				'title' => esc_html__('Home', 'mounthood'),
				'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'mounthood'),
				'icon' => "icon-home",
				'separator' => "yes",
				'url' => esc_url(home_url('/'))
				)
			)); 
		if (mounthood_get_custom_option('menu_toc_top')=='yes')
			mounthood_show_layout(mounthood_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'mounthood'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'mounthood'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('mounthood_body_add_page_preloader')) {
	//Handler of add_action('before', 'mounthood_body_add_page_preloader');
	function mounthood_body_add_page_preloader() {
		if ( ($preloader=mounthood_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=mounthood_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('mounthood_footer_add_views_counter')) {
	//Handler of add_action('wp_footer', 'mounthood_footer_add_views_counter');
	function mounthood_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(mounthood_get_file_slug('templates/_parts/views-counter.php'));
	}
}

// Add theme customizer
if (!function_exists('mounthood_footer_add_theme_customizer')) {
	//Handler of add_action('wp_footer', 'mounthood_footer_add_theme_customizer');
	function mounthood_footer_add_theme_customizer() {
		// Front customizer
		if (mounthood_get_custom_option('show_theme_customizer')=='yes') {
			require_once MOUNTHOOD_FW_PATH . 'core/core.customizer/front.customizer.php';
		}
	}
}

// Add scroll to top button
if (!function_exists('mounthood_footer_add_scroll_to_top')) {
	//Handler of add_action('wp_footer', 'mounthood_footer_add_scroll_to_top');
	function mounthood_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up-small" title="<?php esc_attr_e('Scroll to top', 'mounthood'); ?>"></a><?php
	}
}

// Add custom html
if (!function_exists('mounthood_footer_add_custom_html')) {
	//Handler of add_action('wp_footer', 'mounthood_footer_add_custom_html');
	function mounthood_footer_add_custom_html() {
		?><div class="custom_html_section"><?php
			mounthood_show_layout(mounthood_get_custom_option('custom_code'));
		?></div><?php
	}
}

// Add gtm code
if (!function_exists('mounthood_footer_add_gtm2')) {
	//Handler of add_action('wp_footer', 'mounthood_footer_add_gtm2');
	function mounthood_footer_add_gtm2() {
		mounthood_show_layout(mounthood_get_custom_option('gtm_code2'));
	}
}


// Add theme required plugins
if ( !function_exists( 'mounthood_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'mounthood_add_trx_utils' );
    function mounthood_add_trx_utils($enable=true) {
        return true;
    }
}

// Return text for the Privacy Policy checkbox
if ( ! function_exists('mounthood_get_privacy_text' ) ) {
    function mounthood_get_privacy_text() {
        $page = get_option( 'wp_page_for_privacy_policy' );
        $privacy_text = mounthood_get_theme_option( 'privacy_text' );
        return apply_filters( 'mounthood_filter_privacy_text', wp_kses_post(
                $privacy_text
                . ( ! empty( $page ) && ! empty( $privacy_text )
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf( __( 'For further details on handling user data, see our %s', 'mounthood' ),
                        '<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">'
                        . __( 'Privacy Policy', 'mounthood' )
                        . '</a>' )
                    : ''
                )
            )
        );
    }
}


// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>