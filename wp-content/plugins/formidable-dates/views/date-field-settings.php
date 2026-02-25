<tr>
	<td style="padding-top:0;"></td>
	<td style="padding-top:0;">
		<p class="<?php echo esc_attr( $hide_min_max ? '' : 'frm_hidden' ); ?>">
			<label>
				<input type="checkbox" value="1" class="frm_date_show" data-show="frm_min_max_date" data-value="1" />
				<?php esc_html_e( 'Set specific minimum and maximum dates', 'frmdates' ); ?>
			</label>
		</p>
		<div class="frm_min_max_date <?php echo esc_attr( $hide_min_max ? 'frm_hidden' : '' ); ?>">
			<?php foreach ( $min_max_dates_labels as $opt_name => $opt_label ) : ?>
				<label for="<?php echo esc_attr( $opt_name ); ?>_cond_<?php echo absint( $field['id'] ); ?>">
					<span class="howto" ><?php echo esc_html( $opt_label ); ?></span>
				</label>
				<select name="field_options[<?php echo esc_attr( $opt_name ); ?>_cond_<?php echo absint( $field['id'] ); ?>]" id="<?php echo esc_attr( $opt_name ); ?>_cond_<?php echo absint( $field['id'] ); ?>" class="frm_date_show" data-hide="frm_set_<?php echo esc_attr( $opt_name ); ?>" data-value="" data-default="date:<?php echo esc_attr( 'i.e. ' . date( 'Y-m-d' ) ); ?>|:i.e. +0, +7 days">
					<option value=""><?php esc_html_e( 'Year set above', 'frmdates' ); ?></option>
					<option value="date" <?php selected( $field[ $opt_name . '_cond' ], 'date' ); ?>><?php esc_html_e( 'Specific Date', 'frmdates' ); ?></option>
					<option value="today" <?php selected( $field[ $opt_name . '_cond' ], 'today' ); ?>><?php esc_html_e( 'Current Date', 'frmdates' ); ?></option>
					<?php if ( ! empty( $date_fields ) ) : ?>
						<optgroup label="<?php esc_attr_e( 'Date Fields' ); ?>" class="frmdates_date_fields_opts">
							<?php foreach ( $date_fields as $date_field_key => $date_field_name ) : ?>
								<option value="field_<?php echo esc_attr( $date_field_key ); ?>" <?php selected( $field[ $opt_name . '_cond' ], 'field_' . $date_field_key ); ?>><?php echo esc_attr( $date_field_name ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endif; ?>
				</select>

				<input type="text" name="field_options[<?php echo esc_attr( $opt_name ); ?>_val_<?php echo absint( $field['id'] ); ?>]" value="<?php echo esc_attr( $field[ $opt_name . '_val' ] ); ?>" class="frm_set_<?php echo esc_attr( $opt_name ); ?> <?php echo esc_attr( '' === $field[ $opt_name . '_cond' ] ? 'frm_hidden' : '' ); ?>" placeholder="<?php echo esc_attr( 'i.e. ' . date( 'Y-m-d' ) ); ?>" />
				<br/>
			<?php endforeach; ?>
		</div>
	</td>
</tr>
<tr>
	<td>
		<label id="<?php echo esc_attr( 'days_of_the_week_' . absint( $field['id'] ) . '_heading' ); ?>">
			<?php esc_html_e( 'Days of the week', 'frmdates' ); ?>
			<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'Check the boxes for the days of the week that should be selectable in the datepicker. Deselecting a week day will disable it in the datepicker.', 'frmdates' ); ?>"></span>
		</label>
	</td>
	<td>
		<div class="frmdates_days_of_the_week_toggle <?php echo esc_attr( $all_days_of_the_week ? '' : 'frm_hidden' ); ?>">
			<input type="checkbox" id="<?php echo esc_attr( 'frmdates_days_of_the_week_toggle_' . absint( $field['id'] ) ); ?>" <?php checked( $all_days_of_the_week ); ?> />
			<label for="<?php echo esc_attr( 'frmdates_days_of_the_week_toggle_' . absint( $field['id'] ) ); ?>">
				<?php esc_html_e( 'All Days', 'frmdates' ); ?>
			</label>
		</div>

		<div role="group" aria-labelledby="<?php echo esc_attr( 'days_of_the_week_' . absint( $field['id'] ) . '_heading' ); ?>" class="frmdates_days_of_the_week <?php echo $all_days_of_the_week ? 'frm_hidden' : ''; ?>" id="<?php echo esc_attr( 'frmdates_days_of_the_week_' . absint( $field['id'] ) ); ?>">
			<?php
			foreach ( FrmDatesAppHelper::get_days_of_the_week() as $day_number => $day_name ) :
				$checked = checked( in_array( $day_number, $field['days_of_the_week'] ), true, false );
				?>
				<label class="frm_inline">
					<input type="checkbox" name="field_options[days_of_the_week_<?php echo absint( $field['id'] ); ?>][]" value="<?php echo absint( $day_number ); ?>" <?php echo $checked; // WPCS: XSS OK. ?>/>
					<?php echo esc_html( $day_name ); ?>
				</label>
				&nbsp;
			<?php endforeach; ?>
		</div>
	</td>
</tr>
<tr id="frmdates_excepted_dates_row_<?php echo absint( $field['id'] ); ?>" class="<?php echo count( $field['days_of_the_week'] ) == 7 ? 'frm_hidden' : ''; ?>">
	<td>
		<label for="frmdates_excepted_dates_<?php echo absint( $field['id'] ); ?>">
			<?php esc_html_e( 'Exceptions', 'frmdates' ); ?>
			<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'When a weekday is disabled in the datepicker, you may select specific dates to enable.', 'frmdates' ); ?>"></span>
		</label>
	</td>
	<td>
		<div id="frmdates_excepted_dates_<?php echo absint( $field['id'] ); ?>">
			<input type="hidden" value="" class="frmdates_datepicker" />
			<a href="#" class="frmdates_add_exception_link" data-field-id="<?php echo absint( $field['id'] ); ?>">
				<i class="frm_add_tag frm_icon_font"></i>
				<?php esc_html_e( 'Add Exceptions', 'frmdates' ); ?>
			</a>
			<?php
			echo FrmDatesTemplatesHelper::settings_render_dates_list(
				array(
					'items'     => $field['excepted_dates'],
					'field_id'  => $field['id'],
					'date_type' => 'excepted_dates',
				)
			); // XSS ok.
			?>
		</div>
	</td>
</tr>
<tr>
	<td>
		<label><?php esc_html_e( 'Blackout Dates', 'frmdates' ); ?>
			<span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'Disable specific dates in the datepicker. These dates are disabled in addition to any weekdays you uncheck.', 'frmdates' ); ?>"></span>
		</label>
	</td>
	<td>
		<div id="frmdates_blackout_dates_<?php echo absint( $field['id'] ); ?>">
			<input type="hidden" value="" class="frmdates_datepicker" />
			<a href="#" class="frmdates_add_blackout_date_link" data-field-id="<?php echo absint( $field['id'] ); ?>">
				<i class="frm_add_tag frm_icon_font"></i>
				<?php esc_html_e( 'Add Blackout Date', 'frmdates' ); ?>
			</a>
			<?php
			echo FrmDatesTemplatesHelper::settings_render_dates_list(
				array(
					'items'     => $field['blackout_dates'],
					'field_id'  => $field['id'],
					'date_type' => 'blackout_dates',
				)
			); // XSS ok.
			?>
		</div>
	</td>
</tr>
<tr>
	<td colspan="2">
		<input type="checkbox" value="1" name="field_options[display_inline_<?php echo absint( $field['id'] ); ?>]" id="frmdates_display_inline_<?php echo absint( $field['id'] ); ?>" <?php checked( $field['display_inline'] ); ?>/>
		<label for="frmdates_display_inline_<?php echo absint( $field['id'] ); ?>"><?php esc_html_e( 'Display Inline Date Picker', 'frmdates' ); ?></label>
	</td>
</tr>
