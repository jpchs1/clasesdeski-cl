<?php
/**
 * The template to show mobile menu
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr(snowmountain_get_theme_option('menu_mobile_fullscreen') > 0 ? 'fullscreen' : 'narrow'); ?>">
	<div class="menu_mobile_inner">
		<a class="menu_mobile_close icon-cancel"></a><?php

        do_action('snowmountain_action_before_header_mobile_before_logo');
        // Logo
        if (snowmountain_is_off(snowmountain_get_theme_option('header_mobile_hide_logo'))) {
            ?><div class="sc_layouts_item"><?php
            set_query_var('snowmountain_logo_args', array('type' => 'mobile_header'));
            get_template_part( 'templates/header-logo' );
            set_query_var('snowmountain_logo_args', array());
            ?></div><?php
        }
        do_action('snowmountain_action_before_header_mobile_after_logo');

		// Mobile menu
		$snowmountain_menu_mobile = snowmountain_get_nav_menu('menu_mobile');
		if (empty($snowmountain_menu_mobile)) {
			$snowmountain_menu_mobile = apply_filters('snowmountain_filter_get_mobile_menu', '');
			if (empty($snowmountain_menu_mobile)) $snowmountain_menu_mobile = snowmountain_get_nav_menu('menu_main');
			if (empty($snowmountain_menu_mobile)) $snowmountain_menu_mobile = snowmountain_get_nav_menu();
		}
		if (!empty($snowmountain_menu_mobile)) {
			if (!empty($snowmountain_menu_mobile))
				$snowmountain_menu_mobile = str_replace(
					array('menu_main', 'id="menu-', 'sc_layouts_menu_nav', 'sc_layouts_hide_on_mobile', 'hide_on_mobile'),
					array('menu_mobile', 'id="menu_mobile-', '', '', ''),
					$snowmountain_menu_mobile
					);
			if (strpos($snowmountain_menu_mobile, '<nav ')===false)
				$snowmountain_menu_mobile = sprintf('<nav class="menu_mobile_nav_area">%s</nav>', $snowmountain_menu_mobile);
			snowmountain_show_layout(apply_filters('snowmountain_filter_menu_mobile_layout', $snowmountain_menu_mobile));
		}

		// Search field
		do_action('snowmountain_action_search', 'normal', 'search_mobile', false);

		// Social icons
		snowmountain_show_layout(snowmountain_get_socials_links(), '<div class="socials_mobile">', '</div>');
		?>
	</div>
</div>
