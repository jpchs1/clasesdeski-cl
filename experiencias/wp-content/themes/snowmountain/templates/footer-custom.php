<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.10
 */

$snowmountain_footer_scheme =  snowmountain_is_inherit(snowmountain_get_theme_option('footer_scheme')) ? snowmountain_get_theme_option('color_scheme') : snowmountain_get_theme_option('footer_scheme');
$snowmountain_footer_id = str_replace('footer-custom-', '', snowmountain_get_theme_option("footer_style"));

if ((int) $snowmountain_footer_id == 0) {
	$snowmountain_footer_id = snowmountain_get_post_id(array(
			'name' => $snowmountain_footer_id,
			'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
		)
	);
} else {
	$snowmountain_footer_id = apply_filters('trx_addons_filter_get_translated_layout', $snowmountain_footer_id);
}

$snowmountain_footer_meta = get_post_meta($snowmountain_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($snowmountain_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($snowmountain_footer_id))); 
						if (!empty($snowmountain_footer_meta['margin']) != '') 
							echo ' '.esc_attr(snowmountain_add_inline_css_class('margin-top: '.esc_attr(snowmountain_prepare_css_value($snowmountain_footer_meta['margin'])).';'));
						?> scheme_<?php echo esc_attr($snowmountain_footer_scheme); 
						?>">
	<?php
    // Custom footer's layout
    do_action('snowmountain_action_show_layout', $snowmountain_footer_id);
	?>
</footer><!-- /.footer_wrap -->
