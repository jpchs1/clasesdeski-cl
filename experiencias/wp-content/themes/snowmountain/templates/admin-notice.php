<?php
/**
 * The template to display Admin notices
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0.1
 */
?>
<div class="update-nag" id="snowmountain_admin_notice">
	<h3 class="snowmountain_notice_title"><?php echo sprintf(esc_html__('Welcome to %s', 'snowmountain'), wp_get_theme()->name); ?></h3>
	<?php
	if (!snowmountain_exists_trx_addons()) {
		?><p><?php echo wp_kses_data(__('<b>Attention!</b> Plugin "TRX Addons is required! Please, install and activate it!', 'snowmountain')); ?></p><?php
	}
	?><p><?php
		if (snowmountain_get_value_gp('page')!='tgmpa-install-plugins') {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>" class="button-primary"><i class="dashicons dashicons-admin-plugins"></i> <?php esc_html_e('Install plugins', 'snowmountain'); ?></a>
			<?php
		}
		if (function_exists('snowmountain_exists_trx_addons') && snowmountain_exists_trx_addons()) {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=trx_importer'); ?>" class="button-primary"><i class="dashicons dashicons-download"></i> <?php esc_html_e('One Click Demo Data', 'snowmountain'); ?></a>
			<?php
		}
		?>
        <a href="<?php echo esc_url(admin_url().'customize.php'); ?>" class="button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Customizer', 'snowmountain'); ?></a>
        <a href="#" class="button snowmountain_hide_notice"><i class="dashicons dashicons-dismiss"></i> <?php esc_html_e('Hide Notice', 'snowmountain'); ?></a>
	</p>
</div>