<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'mounthood_template_header_6_theme_setup' ) ) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_template_header_6_theme_setup', 1 );
	function mounthood_template_header_6_theme_setup() {
		mounthood_add_template(array(
			'layout' => 'header_6',
			'mode'   => 'header',
			'title'  => esc_html__('Header 1', 'mounthood'),
			'icon'   => mounthood_get_file_url('templates/headers/images/6.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'mounthood_template_header_6_output' ) ) {
	function mounthood_template_header_6_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		$add_button = mounthood_get_theme_option('additional_button');
		$add_button_url = mounthood_get_theme_option('additional_button_url');
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_6 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_6 top_panel_position_<?php echo esc_attr(mounthood_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle" <?php mounthood_show_layout($header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php mounthood_show_logo(true, true); ?>
					</div>
					<div class="menu_main_wrap">
						<nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(mounthood_get_theme_option('menu_hover')); ?>">
							<?php
							$menu_main = mounthood_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = mounthood_get_nav_menu();
							mounthood_show_layout($menu_main);
							?>
						</nav>
						<?php
						if (function_exists('mounthood_exists_woocommerce') && mounthood_exists_woocommerce() && (mounthood_is_woocommerce_page() && mounthood_get_custom_option('show_cart')=='shop' || mounthood_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
							?>
							<div class="menu_main_cart top_panel_icon">
								<?php get_template_part(mounthood_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
							</div>
							<?php
						}
						if (mounthood_get_custom_option('show_search')=='yes' && function_exists('mounthood_sc_search'))
							mounthood_show_layout(mounthood_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed", "style"=>mounthood_get_theme_option('search_style'))));
						?>
						<?php
						if (!empty($add_button) && !empty($add_button_url)) {
                            $alt = basename(mounthood_get_theme_option('additional_button'));
                            $alt = substr($alt,0,strlen($alt) - 4);
							?>
							<div class="menu_main_additional_button top_panel_icon">
								<div class="menu_main_additional_button_container">
									<a href="<?php echo esc_url(mounthood_get_theme_option('additional_button_url')); ?>"><img src="<?php echo esc_url(mounthood_get_theme_option('additional_button')); ?>" alt="<?php esc_html($alt); ?>"></a>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
		mounthood_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => true,
				 'search' => true
			)
		);
	}
}
?>