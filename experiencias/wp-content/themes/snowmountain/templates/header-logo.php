<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */

$snowmountain_args = get_query_var('snowmountain_logo_args');

// Site logo
$snowmountain_logo_image  = snowmountain_get_logo_image(isset($snowmountain_args['type']) ? $snowmountain_args['type'] : '');
$snowmountain_logo_text   = snowmountain_is_on(snowmountain_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$snowmountain_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($snowmountain_logo_image) || !empty($snowmountain_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo is_front_page() ? '#' : esc_url(home_url('/')); ?>"><?php
		if (!empty($snowmountain_logo_image)) {
			$snowmountain_attr = snowmountain_getimagesize($snowmountain_logo_image);
			echo '<img src="'.esc_url($snowmountain_logo_image).'" alt="'.esc_attr__('Image', 'snowmountain').'"'.(!empty($snowmountain_attr[3]) ? sprintf(' %s', $snowmountain_attr[3]) : '').'>' ;
		} else {
			snowmountain_show_layout(snowmountain_prepare_macros($snowmountain_logo_text), '<span class="logo_text">', '</span>');
			snowmountain_show_layout(snowmountain_prepare_macros($snowmountain_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>