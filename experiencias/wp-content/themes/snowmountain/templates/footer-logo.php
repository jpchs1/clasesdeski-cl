<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.10
 */

// Logo
if (snowmountain_is_on(snowmountain_get_theme_option('logo_in_footer'))) {
	$snowmountain_logo_image = '';
	if (snowmountain_get_retina_multiplier(2) > 1)
		$snowmountain_logo_image = snowmountain_get_theme_option( 'logo_footer_retina' );
	if (empty($snowmountain_logo_image)) 
		$snowmountain_logo_image = snowmountain_get_theme_option( 'logo_footer' );
	$snowmountain_logo_text   = get_bloginfo( 'name' );
	if (!empty($snowmountain_logo_image) || !empty($snowmountain_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($snowmountain_logo_image)) {
					$snowmountain_attr = snowmountain_getimagesize($snowmountain_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($snowmountain_logo_image).'" class="logo_footer_image" alt="'.esc_attr__('Logo', 'snowmountain').'"></a>' ;
				} else if (!empty($snowmountain_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($snowmountain_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>