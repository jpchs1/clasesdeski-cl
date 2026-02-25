<?php
class FrmDatesHooksController {

	private static $min_formidable_version = 3.0;

	public static function load_hooks() {
		if ( ! self::is_formidable_compatible() ) {
			add_action( 'admin_notices', array( 'FrmDatesAppController', 'pro_not_installed_notice' ) );
			return;
		}

		self::load_admin_hooks();

		add_filter( 'frm_date_field_options', array( 'FrmDatesAppController', 'date_field_options_js' ), 20, 2 );

		add_filter( 'frm_get_field_type_class', array( 'FrmDatesField', 'get_field_type_class' ), 11, 2 );
		add_filter( 'frm_clean_date_field_options_before_update', array( 'FrmDatesField', 'sanitize_field_options' ) );
		add_action( 'frm_load_ajax_field_scripts', array( 'FrmDatesAppController', 'load_date_js' ), 10 );
	}

	public static function load_admin_hooks() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_init', 'FrmDatesAppController::include_updater' );
		add_action( 'admin_enqueue_scripts', array( 'FrmDatesAppController', 'enqueue_admin_assets' ) );

		add_action( 'frm_date_field_options_form', array( 'FrmDatesAppController', 'add_settings_to_form' ), 10, 3 );
	}

	/**
	 * Check if the current version of Formidable is compatible with Dates add-on
	 *
	 * @since 1.0
	 * @return bool
	 */
	private static function is_formidable_compatible() {
		$frm_version = is_callable( 'FrmAppHelper::plugin_version' ) ? FrmAppHelper::plugin_version() : 0;
		return version_compare( $frm_version, self::$min_formidable_version, '>=' ) && FrmAppHelper::pro_is_installed();
	}
}
