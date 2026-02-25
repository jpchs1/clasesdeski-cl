<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.10
 */

// Copyright area
$snowmountain_footer_scheme =  snowmountain_is_inherit(snowmountain_get_theme_option('footer_scheme')) ? snowmountain_get_theme_option('color_scheme') : snowmountain_get_theme_option('footer_scheme');
$snowmountain_copyright_scheme = snowmountain_is_inherit(snowmountain_get_theme_option('copyright_scheme')) ? $snowmountain_footer_scheme : snowmountain_get_theme_option('copyright_scheme');
?> 
<div class="footer_copyright_wrap scheme_<?php echo esc_attr($snowmountain_copyright_scheme); ?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				// Replace {{...}} and [[...]] on the <i>...</i> and <b>...</b>
				$snowmountain_copyright = snowmountain_prepare_macros(snowmountain_get_theme_option('copyright'));
				if (!empty($snowmountain_copyright)) {
					// Replace {date_format} on the current date in the specified format
					if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $snowmountain_copyright, $snowmountain_matches)) {
						$snowmountain_copyright = str_replace($snowmountain_matches[1], date(str_replace(array('{', '}'), '', $snowmountain_matches[1])), $snowmountain_copyright);
					}
					// Display copyright
					echo wp_kses_data(nl2br($snowmountain_copyright));
				}
			?></div>
		</div>
	</div>
</div>
