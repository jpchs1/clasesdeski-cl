<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmDatesUpdate extends FrmAddon {

	public $plugin_file;
	public $plugin_name = 'Datepicker Options';
	public $download_id = 20247260;
	public $version = '1.0.04';

	public function __construct() {
		$this->plugin_file = FrmDatesAppHelper::plugin_file();
		parent::__construct();
	}

	public static function load_hooks() {
		add_filter( 'frm_include_addon_page', '__return_true' );
		new FrmDatesUpdate();
	}
}
