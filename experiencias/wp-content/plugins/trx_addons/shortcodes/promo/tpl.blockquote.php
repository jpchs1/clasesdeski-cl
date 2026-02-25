<?php
/**
 * The style "default" of the Promo block
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_promo');

$args['image'] = trx_addons_get_attachment_url($args['image'], 'full');
if (empty($args['image'])) {
	$args['image_width'] = '0%';
	$text_width = "100%";
} else if (empty($args['title']) && empty($args['subtitle']) && empty($args['description']) && empty($args['content']) && (empty($args['link']) || empty($args['link_text']))) {
	$args['image_width'] = '100%';
	$text_width = 0;
} else {
	$args['gap'] = trim(str_replace('%', '', $args['gap']));
	if (!empty($args['gap']) && strpos($args['image_width'], '%')!==false)
		$args['image_width'] = ((int) str_replace('%', '', $args['image_width']) - $args['gap']/2) . '%';
	$text_width = strpos($args['image_width'], '%')!==false
				? (100 - $args['gap'] - (int) str_replace('%', '', $args['image_width'])).'%'
				: 'calc(100%-'.($args['gap'] ? $args['gap'].'%' : '').trim($args['image_width']).')';
}

$css_image = (!empty($args['image']) ? 'background-image:url(' . esc_url($args['image']) . ');' : '')
			 . (!empty($args['image_width']) ? 'width:'.trim($args['image_width']).';' : '')
			 . (!empty($args['image_position']) ? $args['image_position'].': 0;' : '');

$css_text = 'width: '.esc_attr($text_width).';'
			. (!empty($args['image']) ? 'float: '.($args['image_position']=='left' ? 'right' : 'left').';' : '')
			. (!empty($args['text_margins']) ? ' margin:'.esc_attr($args['text_margins']).';' : '');

?><div <?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?> 
	class="sc_promo sc_promo_blockquote<?php
		if (!empty($args['class'])) echo ' '.esc_attr($args['class']); 
		if (!empty($args['size'])) echo ' sc_promo_size_'.esc_attr($args['size']);
		if (empty($args['text_paddings'])) echo ' sc_promo_no_paddings';
		if (!empty($args['image']) && empty($args['image_cover'])) echo ' sc_promo_image_fit';
		if (!empty($args['image']) && !empty($args['image_position'])) echo ' sc_promo_image_position_'.esc_attr($args['image_position']);
		if (empty($args['image'])) echo ' sc_promo_no_image';
		?>"
	<?php if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"'; ?>
	><?php
	
	// Image
	if (!empty($args['image'])) {
		?><div class="sc_promo_image" style="<?php echo esc_attr($css_image); ?>"></div><?php
	}
	if (!empty($args['title']) || !empty($args['subtitle']) || !empty($args['description']) || !empty($args['content']) || (!empty($args['link']) && !empty($args['link_text'])) || !empty($args['link_image'])) {
		?><blockquote class="trx_addons_blockquote_style_1 sc_promo_text<?php echo !empty($args['text_centered']) ? ' sc_promo_text_centered' : ''; ?> sc_align_<?php echo esc_attr($args['text_align']); ?>" style="<?php echo esc_attr($css_text); ?>"><?php
			if (!empty($args['description'])) {
				?><p><?php echo do_shortcode(trx_addons_prepare_macros($args['description'])); ?></p><?php
			}
			if (!empty($args['content']) && 'tiny' != $args['size']) {
				echo do_shortcode($args['content']);
			}
			if (!empty($args['link']) && !empty($args['link_text'])) {
				?><p><a href="<?php echo esc_url($args['link']); ?>"><?php echo esc_html($args['link_text']); ?></a></p><?php
			}
		?></blockquote><!-- /.sc_promo_text --><?php
	}
	if ( 'tiny' == $args['size'] ) {
		if (!empty($args['link'])) {
			?><a href="<?php echo esc_url($args['link']); ?>" class="sc_promo_link"></a><?php
		}
	}
?></div><!-- /.sc_promo -->