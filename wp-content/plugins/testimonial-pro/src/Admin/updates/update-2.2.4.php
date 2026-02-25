<?php
/**
 * Update version.
 */
update_option( 'testimonial_pro_version', '2.2.4' );
update_option( 'testimonial_pro_db_version', '2.2.4' );

$old_license             = get_option( 'sp_tpro_license_key' );
$settings                = get_option( 'sp_testimonial_pro_options' );
$settings['license_key'] = $old_license;

update_option( 'sp_testimonial_pro_options', $settings );
delete_option( 'sp_tpro_license_key' );
delete_option( 'sp_tpro_license_status' );

/**
 * Update license status.
 */
$manage_license       = new Testimonial_Pro_License( SP_TESTIMONIAL_PRO_FILE, SP_TPRO_VERSION, 'ShapedPlugin', SP_TPRO_STORE_URL, SP_TPRO_ITEM_ID, SP_TPRO_ITEM_SLUG );
$manage_license->check_license_status();
