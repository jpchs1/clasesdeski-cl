<?php
/**
 * ThemeREX Widgets
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.1
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Include files with widgets
if (!function_exists('trx_addons_widgets_load')) {
	add_action( 'after_setup_theme', 'trx_addons_widgets_load', 6 );
	add_action( 'trx_addons_action_save_options', 'trx_addons_widgets_load', 6 );
	function trx_addons_widgets_load() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		// Get theme-specific widget's args (if need)
		global $TRX_ADDONS_STORAGE;
		$TRX_ADDONS_STORAGE['widgets_args'] = apply_filters('trx_addons_widgets_args', $TRX_ADDONS_STORAGE['widgets_args']);
		// Include widgets
		$trx_addons_widgets = apply_filters('trx_addons_widgets_list', array(
			'aboutme' => 1,
			'audio' => 1,
			'banner' => 1,
			'calendar' => 1,
			'categories_list' => 1,
			'contacts' => 1,
			'flickr' => 1,
			'popular_posts' => 1,
			'recent_news' => 1,
			'recent_posts' => 1,
			'slider' => 1,
			'socials' => 1,
			'twitter' => 1,
			'video' => 1
			)
		);
		if (is_array($trx_addons_widgets) && count($trx_addons_widgets) > 0) {
			foreach ($trx_addons_widgets as $w=>$need) {
				if ($need && ($fdir = trx_addons_get_file_dir("widgets/{$w}/{$w}.php")) != '') { include_once $fdir; }
			}
		}
	}
}

// Disable a new Widgets block editor
if (!function_exists('trx_addons_widgets_disable_block_editor')) {
    add_action( 'after_setup_theme', 'trx_addons_widgets_disable_block_editor' );
    function trx_addons_widgets_disable_block_editor() {
        if ( (int) trx_addons_get_option( 'disable_widgets_block_editor' ) > 0 ) {
            remove_theme_support( 'widgets-block-editor' );
        }
    }
}

// Custom Widgets areas
//--------------------------------------------------------------------

// Add Form to register a new custom widgets area
if (!function_exists('trx_addons_widgets_add_form')) {
	add_action('widgets_admin_page', 'trx_addons_widgets_add_form');
	function trx_addons_widgets_add_form() {
		?><div class="trx_addons_widgets_form_wrap">
			<h2 class="trx_addons_widgets_form_title"><?php esc_html_e('Add custom widgets area', 'trx_addons'); ?></h2>
			<form class="trx_addons_widgets_form" method="post">
				<?php wp_nonce_field( 'trx_addons_action_create_widgets_area', 'trx_addons_widgets_wpnonce' ); ?>
				<div class="trx_addons_widgets_area_name">
					<div class="trx_addons_widgets_area_label"><?php esc_html_e('Name (required):', 'trx_addons'); ?></div>
					<div class="trx_addons_widgets_area_field"><input name="trx_addons_widgets_area_name" value="" type="text"></div>
				</div>
				<div class="trx_addons_widgets_area_description">
					<div class="trx_addons_widgets_area_label"><?php esc_html_e('Description:', 'trx_addons'); ?></div>
					<div class="trx_addons_widgets_area_field"><input name="trx_addons_widgets_area_description" value="" type="text"></div>
				</div>
				<div class="trx_addons_widgets_area_submit">
					<div class="trx_addons_widgets_area_field">
						<input value="<?php esc_html_e('Add', 'trx_addons'); ?>" class="trx_addons_widgets_area_button trx_addons_widgets_area_add button-primary" type="submit" title="<?php esc_html_e('To create new widgets area specify it name (required) and description (optional) and press this button', 'trx_addons'); ?>">
						<input value="<?php esc_html_e('Delete', 'trx_addons'); ?>" class="trx_addons_widgets_area_button trx_addons_widgets_area_delete button" name="trx_addons_widgets_area_delete" type="submit" title="<?php esc_html_e('To delete custom widgets area specify it name (required) and press this button', 'trx_addons'); ?>">
					</div>
				</div>
			</form>
		</div><?php
	}
}

// Create a new custom widgets area
if (!function_exists('trx_addons_widgets_create_sidebar')) {
	add_action('widgets_init', 'trx_addons_widgets_create_sidebar', 2);
	function trx_addons_widgets_create_sidebar() {
		// If get data from the form
		if ( !empty($_POST['trx_addons_widgets_area_name'])) {
			if (check_admin_referer( 'trx_addons_action_create_widgets_area', 'trx_addons_widgets_wpnonce' ) ) {
				$name = trim(trx_addons_get_value_gp('trx_addons_widgets_area_name'));
				$sidebars = get_option('trx_addons_widgets_areas', false);
				if ($sidebars === false) $sidebars = array();
				if ( !empty($_POST['trx_addons_widgets_area_delete'])) {
					foreach ($sidebars as $i=>$sb) {
						if ($sidebars[$i]['name'] == $name) {
							unset($sidebars[$i]);
							break;
						}
					}
				} else {
					$id = count($sidebars) > 0 ? $sidebars[count($sidebars)-1]['id']+1 : 1;
					$sidebars[] = array(
									'id' => $id,
									'name' => $name,
									'description' => trim(trx_addons_get_value_gp('trx_addons_widgets_area_description'))
									);
				}
				update_option('trx_addons_widgets_areas', $sidebars);
			}
		}
	}
}

// Register custom widgets areas after the theme's areas
if (!function_exists('trx_addons_widgets_register_sidebars')) {
	add_action('widgets_init', 'trx_addons_widgets_register_sidebars', 11);
	function trx_addons_widgets_register_sidebars() {
		global $TRX_ADDONS_STORAGE;
		// Load previously created sidebars
		$sidebars = get_option('trx_addons_widgets_areas', false);
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $sb) {
				register_sidebar( array(
										'name'          => $sb['name'],
										'description'   => $sb['description'],
										'id'            => 'custom_widgets_'.intval($sb['id']),
										'before_widget' => $TRX_ADDONS_STORAGE['widgets_args']['before_widget'],
										'after_widget'  => $TRX_ADDONS_STORAGE['widgets_args']['after_widget'],
										'before_title'  => $TRX_ADDONS_STORAGE['widgets_args']['before_title'],
										'after_title'   => $TRX_ADDONS_STORAGE['widgets_args']['after_title']
										)
								);
			}
		}
	}
}
?>