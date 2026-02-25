<?php
class FrmDatesAppController {

	public static function pro_not_installed_notice() {
		?>
	<div class="error">
		<p><?php esc_html_e( 'Formidable Dates requires Formidable Forms Pro to be installed.', 'formidable-dates' ); ?></p>
	</div>
		<?php
	}

	public static function enqueue_scripts() {
		$suffix = FrmAppHelper::js_suffix();
		wp_enqueue_script( 'frmdates', FrmDatesAppHelper::get_url( '/js/frmdates' . $suffix . '.js' ), array( 'formidable' ), '1.0.03' );
	}

	public static function load_date_js( $args ) {
		if ( 'date' === $args['field']->type ) {
			global $frm_vars;

			if ( isset( $frm_vars['datepicker_loaded'] ) && ! empty( $frm_vars['datepicker_loaded'] ) ) {
				self::enqueue_scripts();
			}
		}
	}

	public static function enqueue_admin_assets() {
		if ( ! FrmAppHelper::is_admin_page( 'formidable' ) ) {
			return;
		}

		wp_enqueue_style( 'jquery-theme', FrmProStylesController::jquery_css_url( '' ), array(), FrmAppHelper::plugin_version() );
		wp_enqueue_style( 'formidable-pro-fields', admin_url( 'admin-ajax.php?action=pro_fields_css' ), array(), FrmAppHelper::plugin_version() );
		wp_enqueue_style( 'frmdates_admin', FrmDatesAppHelper::get_url( '/css/admin.css' ), array(), '1.0' );

		wp_register_script( 'frmdates_admin', FrmDatesAppHelper::get_url( '/js/admin.js' ), array( 'jquery-ui-datepicker', 'jquery-effects-highlight' ), '1.0' );

		$frmpro_settings = FrmProAppHelper::get_settings();
		$script_strings = array(
			'itemTemplate' => FrmDatesTemplatesHelper::settings_render_dates_list_item(
				array(
					'date'           => '%DATE%',
					'formatted_date' => '%DATE_WITH_FORMAT%',
					'input_name'     => '%DATE_TYPE%_%FIELD_ID%',
				)
			),
			'dateFormat'   => $frmpro_settings->cal_date_format,
		);
		wp_localize_script( 'frmdates_admin', 'frmdates_admin_js', $script_strings );

		wp_enqueue_script( 'frmdates_admin' );
	}

	public static function add_settings_to_form( $field, $display, $values ) {
		$field_id = absint( $field['id'] );
		$form_id = absint( $field['form_id'] );

		$date_fields = array();
		foreach ( FrmField::get_all_types_in_form( $form_id, 'date', '', 'include' ) as $date_field ) {
			if ( $date_field->id == $field_id ) {
				continue;
			}

			$date_fields[ $date_field->field_key ] = $date_field->name;
		}

		$min_max_dates_labels = array(
			'minimum_date' => __( 'Minimum', 'frmdates' ),
			'maximum_date' => __( 'Maximum', 'frmdates' ),
		);

		$hide_min_max = empty( $field['minimum_date_cond'] ) && empty( $field['maximum_date_cond'] );
		$all_days_of_the_week = ( empty( $field['days_of_the_week'] ) || 7 == count( $field['days_of_the_week'] ) );

		include( FrmDatesAppHelper::get_path( '/views/date-field-settings.php' ) );
	}

	public static function date_field_options_js( $js_options, $extra ) {
		$field_key = str_replace( array( 'field_', '^' ), '', $extra['field_id'] );
		$field     = FrmField::getOne( $field_key );
		if ( empty( $field ) || ! FrmDatesField::field_has_custom_opts( $field ) ) {
			return $js_options;
		}

		$inline = FrmField::get_option( $field, 'display_inline' );
		if ( $inline ) {
			// TODO: Adjust for repeating sections.
			$js_options['options']['altField'] = '#field_' . $field->field_key . '_alt';
		}

		$constraints = self::get_constraints_for_field( $field );
		$js_options['formidable_dates'] = array(
			'inline'            => (bool) $inline,
			'daysEnabled'       => $constraints['days'],
			'datesEnabled'      => $constraints['exceptions'],
			'datesDisabled'     => $constraints['blackout_dates'],
			'minimum_date_cond' => FrmField::get_option( $field, 'minimum_date_cond' ),
			'minimum_date_val'  => FrmField::get_option( $field, 'minimum_date_val' ),
			'maximum_date_cond' => FrmField::get_option( $field, 'maximum_date_cond' ),
			'maximum_date_val'  => FrmField::get_option( $field, 'maximum_date_val' ),
		);

		return $js_options;
	}

	private static function get_constraints_for_field( $field ) {
		$days           = FrmField::get_option( $field, 'days_of_the_week' );
		$blackout_dates = FrmField::get_option( $field, 'blackout_dates' );
		$exceptions     = FrmField::get_option( $field, 'excepted_dates' );

		if ( empty( $days ) ) {
			$days = range( 0, 6 );
		} else {
			$days = array_map( 'absint', $days );
		}

		return compact( 'days', 'blackout_dates', 'exceptions' );
	}

	public static function include_updater() {
		if ( class_exists( 'FrmAddon' ) ) {
			include( FrmDatesAppHelper::get_path( '/classes/models/FrmDatesUpdate.php' ) );
			FrmDatesUpdate::load_hooks();
		}
	}
}

