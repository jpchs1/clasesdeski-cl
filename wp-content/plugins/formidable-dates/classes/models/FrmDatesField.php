<?php
class FrmDatesField extends FrmProFieldDate {

	public static function get_field_type_class( $classname, $field_type ) {
		if ( 'date' == $field_type ) {
			$classname = __CLASS__;
		}

		return $classname;
	}

	public static function sanitize_field_options( $values ) {
		if ( ! empty( $values['field_options']['days_of_the_week'] ) ) {
			$values['field_options']['days_of_the_week'] = array_unique( array_map( 'absint', $values['field_options']['days_of_the_week'] ) );
		} else {
			$values['field_options']['days_of_the_week'] = array( 0, 1, 2, 3, 4, 5, 6 );
		}

		if ( ! empty( $values['field_options']['blackout_dates'] ) ) {
			asort( $values['field_options']['blackout_dates'] );
		}

		if ( 7 == count( $values['field_options']['days_of_the_week'] ) ) {
			$values['field_options']['excepted_dates'] = array();
		} elseif ( ! empty( $values['field_options']['excepted_dates'] ) ) {
			asort( $values['field_options']['excepted_dates'] );
		}

		// Validate threshold conditions and values.
		foreach ( array( 'minimum_date', 'maximum_date' ) as $date_type ) {
			if ( empty( $values['field_options'][ $date_type . '_cond' ] ) ) {
				continue;
			}

			$condition = $values['field_options'][ $date_type . '_cond' ];
			$value     = $values['field_options'][ $date_type . '_val' ];

			if ( ! in_array( $condition, array( '', 'date', 'today' ) ) && substr( $condition, 0, 6 ) != 'field_' ) {
				$values['field_options'][ $date_type . '_cond' ] = '';
				$values['field_options'][ $date_type . '_val' ]  = '';
			}
		}

		return $values;
	}

	public static function field_has_custom_opts( $field ) {
		$display_inline    = FrmField::get_option( $field, 'display_inline' );
		$days              = FrmField::get_option( $field, 'days_of_the_week' );
		$blackout_dates    = FrmField::get_option( $field, 'blackout_dates' );
		$minimum_date_cond = FrmField::get_option( $field, 'minimum_date_cond' );
		$maximum_date_cond = FrmField::get_option( $field, 'maximum_date_cond' );

		return ( 7 !== count( $days ) || $display_inline || $blackout_dates || $minimum_date_cond || $maximum_date_cond );
	}

	public function extra_field_opts() {
		$new_options = array(
			'days_of_the_week'  => array( 0, 1, 2, 3, 4, 5, 6 ),
			'blackout_dates'    => array(),
			'excepted_dates'    => array(),
			'display_inline'    => false,
			'minimum_date_cond' => '',
			'minimum_date_val'  => '',
			'maximum_date_cond' => '',
			'maximum_date_val'  => '',
		);

		return array_merge( parent::extra_field_opts(), $new_options );
	}

	public function front_field_input( $args, $shortcode_atts ) {
		FrmDatesAppController::enqueue_scripts();

		if ( ! self::field_has_custom_opts( $this->field ) ) {
			return parent::front_field_input( $args, $shortcode_atts );
		}

		$display_inline = FrmField::get_option( $this->field, 'display_inline' );
		if ( $display_inline ) {
			$html = '<input type="hidden" name="' . esc_attr( $args['field_name'] ) . '" value="' . esc_attr( $this->field['value'] ) . '" id="' . esc_attr( $args['html_id'] ) . '_alt" />';
			$html .= '<div id="' . esc_attr( $args['html_id'] ) . '" class="frm_date_inline"></div>';
		} else {
			$html = parent::front_field_input( $args, $shortcode_atts );
		}

		return $html;
	}
}
