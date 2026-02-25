<?php
// Get template args
extract(mounthood_template_last_args('single-footer'));

if (mounthood_get_custom_option("show_post_author") == 'yes') {
	$post_author_name = $post_author_descr = $post_author_socials = '';
	$show_post_author_socials = true;
	if ($post_data['post_type']=='post') {
		$post_author_descr = mounthood_do_shortcode(nl2br(get_the_author_meta('description', $post_data['post_author_id'])));
		if (!empty($post_author_descr)) {
			$post_author_title = esc_html__('About', 'mounthood');
			$post_author_name = $post_data['post_author'];
			$post_author_url = $post_data['post_author_url'];
			$post_author_email = get_the_author_meta('user_email', $post_data['post_author_id']);
			$mult = mounthood_get_retina_multiplier();
			$post_author_avatar = get_avatar($post_author_email, 228*$mult);
			if ($show_post_author_socials) 
				$post_author_socials = mounthood_show_user_socials( array(
					'author_id' => $post_data['post_author_id'],
					'size' => 'tiny',
					'shape' => 'round',
					'echo' => false
					)
				);
		}
	}
	if (!empty($post_author_name) && !empty($post_author_descr)) {
		?>
		<div class="post_line_over_author"></div>
		<section class="post_author author vcard" itemprop="author" itemscope itemtype="http://schema.org/Person">
			<div class="post_author_avatar"><a href="<?php echo esc_url($post_data['post_author_url']); ?>" itemprop="image"><?php mounthood_show_layout($post_author_avatar); ?></a></div>
			<div class="post_author_content">
				<h6 class="post_author_title"><?php echo esc_html($post_author_title); ?> <span itemprop="name"><a href="<?php echo esc_url($post_author_url); ?>" class="fn"><?php mounthood_show_layout($post_author_name); ?></a></span></h6>
				<div class="post_author_info" itemprop="description">
				<?php mounthood_show_layout($post_author_descr); ?>
				<?php if ($post_author_socials!='') mounthood_show_layout($post_author_socials); ?>
				</div>
			</div>
		</section>
		<div class="post_line_under_author"></div>
		<?php
	}
}
?>