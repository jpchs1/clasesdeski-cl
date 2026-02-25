<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.14
 */
$snowmountain_header_video = snowmountain_get_header_video();
$snowmountain_embed_video = '';
if (!empty($snowmountain_header_video) && !snowmountain_is_from_uploads($snowmountain_header_video)) {
	if (snowmountain_is_youtube_url($snowmountain_header_video) && preg_match('/[=\/]([^=\/]*)$/', $snowmountain_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$snowmountain_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($snowmountain_header_video) . '[/embed]' ));
			$snowmountain_embed_video = snowmountain_make_video_autoplay($snowmountain_embed_video);
		} else {
			$snowmountain_header_video = str_replace('/watch?v=', '/embed/', $snowmountain_header_video);
			$snowmountain_header_video = snowmountain_add_to_url($snowmountain_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$snowmountain_embed_video = '<iframe src="' . esc_url($snowmountain_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php snowmountain_show_layout($snowmountain_embed_video); ?></div><?php
	}
}
?>