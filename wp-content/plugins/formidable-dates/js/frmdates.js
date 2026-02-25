jQuery(function( $ ) {

	var frmdates = {
		normalizeSettings: function( fieldSettings ) {
			return $.extend(
				{},
				{ triggerID: fieldSettings.triggerID, repeating: -1 !== fieldSettings.triggerID.indexOf( '^' ), locale: fieldSettings.locale },
				{ datepickerOptions: fieldSettings.options },
				fieldSettings.formidable_dates
			);
		},

		getTargets: function( fieldConfig ) {
			var targets = [];

			$( fieldConfig.triggerID ).each( function() {
				if ( fieldConfig.repeating && fieldConfig.inline ) {
					targets.push( $( this ).siblings( '.frm_date_inline' ) );
				} else {
					targets.push( $( this ) );
				}
			} );

			return targets;
		},

		setupFields: function() {
			$.each( window.__frmDatepicker, function( index ) {
				var fieldConfig;

				if ( 'undefined' === typeof this.formidable_dates || ! this.formidable_dates ) {
					return;
				}

				fieldConfig = frmdates.normalizeSettings( this );

				if ( 0 === $( fieldConfig.triggerID ).length ) {
					return;
				}

				fieldConfig.datepickerOptions.beforeShowDay = $.proxy( frmdates.callbacks.beforeShowDay, fieldConfig );
				fieldConfig.datepickerOptions.onSelect      = $.proxy( frmdates.callbacks.onSelect, fieldConfig );
				fieldConfig.datepickerOptions.minDate       = ! fieldConfig.repeating ? frmdates.getMinOrMaxDate( 'minimum_date', fieldConfig ) : null;
				fieldConfig.datepickerOptions.maxDate       = ! fieldConfig.repeating ? frmdates.getMinOrMaxDate( 'maximum_date', fieldConfig ) : null;

				// Hijack global settings so our functions are called.
				window.__frmDatepicker[ index ].options = fieldConfig.datepickerOptions;

				$.each( frmdates.getTargets( fieldConfig ), function() {
					var altField;
					var localConfig;

					localConfig = fieldConfig.datepickerOptions;

					if ( fieldConfig.inline ) {
						altField = document.getElementById( this.attr( 'id' ) + '_alt' );
						if ( null !== altField ) {
							localConfig.defaultDate = altField.value;
						}

						//Calculating default date based on offset
						frmdates.defaultDateOffset( fieldConfig, localConfig );

					}

					if ( fieldConfig.repeating ) {

						// Min. or max. date might need to be computed based on the repeating container.
						localConfig = $.extend(
							localConfig,
							{
								minDate: frmdates.getMinOrMaxDate( 'minimum_date', fieldConfig, this ),
								maxDate: frmdates.getMinOrMaxDate( 'maximum_date', fieldConfig, this )
							}
						);
					}

					// Handle localization.
					localConfig = $.extend( {}, $.datepicker.regional[ fieldConfig.locale ], localConfig );

					if ( this.data( 'frmdates_configured' ) || this.hasClass( 'hasDatepicker' ) ) {
						this.datepicker( 'option', localConfig );
					} else {
						this.datepicker( localConfig );
					}

					this.data( 'frmdates_configured', true );

					if ( fieldConfig.repeating && fieldConfig.inline ) {
						altField = this.closest( '.frm_repeat_sec, .frm_repeat_inline, .frm_repeat_grid' ).find( 'input[id^="' + this.attr( 'id' ) + '"]' );
						if ( altField.length > 0 ) {
							this.datepicker( 'option', 'altField', altField );
						}
					}
				} );
			} );
		},

		getMinOrMaxDate: function( limit, field, $instance ) {
			var condition, val, result = null;
			var $container, $sourceField;

			condition = field[ limit + '_cond' ];
			if ( ! condition ) {
				return null;
			}

			val = field[ limit + '_val' ];

			// Specific date.
			if ( 'date' === condition ) {
				return $.datepicker.parseDate( 'yy-mm-dd', val );
			}

			// Relative dates.
			if ( 'today' === condition ) {
				result = new Date();
			} else if ( 'field_' === condition.substr( 0, 6 ) ) {

				// First search for the condition field inside the same repeating container.
				if ( field.repeating && $instance ) {
					$container   = $instance.closest( '.frm_repeat_sec, .frm_repeat_inline, .frm_repeat_grid' );
					$sourceField = $container.find( '[id^="' + condition + '"].frm_date_inline' );
					$sourceField = ( 0 === $sourceField.length ) ? $container.find( 'input[id^="' + condition + '"]' ) : $sourceField;
				}

				$sourceField = ( ! $sourceField || 0 === $sourceField.length ) ? $( '#' + condition ) : $sourceField;

				if ( $sourceField && 1 === $sourceField.length ) {

					// The field might be on a different page and it's hidden now.
					if ( $sourceField.is( 'input[type="hidden"]' ) ) {

						// All date fields use the same dateFormat value, so we can re-use the one from `field`.
						result = $.datepicker.parseDate( field.datepickerOptions.dateFormat, $sourceField.val() );
					} else {
						result = $sourceField.datepicker( 'getDate' );
					}
				}

				if ( ! result ) {
					return null;
				}
			}

			result = this.applyDateOffset( result, val );
			return result;
		},

		applyDateOffset: function( date, offset_ ) {
			var pattern = /([+\-]?[0-9]+)\s*(d|day|days|w|week|weeks|m|month|months|y|year|years)?/g;
			var offset, matches;
			var year, month, day;

			if ( ! offset_ ) {
				return date;
			}

			offset  = offset_.toLowerCase();
			matches = pattern.exec( offset );

			year = date.getFullYear();
			month = date.getMonth();
			day = date.getDate();

			while ( matches ) {
				switch ( matches[2] ) {
					case 'd':
					case 'day':
					case 'days':
						day += parseInt( matches[1], 10 );
						break;
					case 'w':
					case 'week':
					case 'weeks':
						day += parseInt( matches[1], 10 ) * 7;
						break;
					case 'm':
					case 'month':
					case 'months':
						month += parseInt( matches[1], 10 ); // TODO: Restrict to days in month.
						break;
					case 'y':
					case 'year':
					case 'years':
						year += parseInt( matches[1], 10 ); // TODO: Restrict accordingly.
						break;
				}

				matches = pattern.exec( offset );
			}

			date = new Date( year, month, day );
			date.setHours( 0 );
			date.setMinutes( 0 );
			date.setSeconds( 0 );
			date.setMilliseconds( 0 );

			return date;
		},

		init: function() {
			if ( 'undefined' === typeof window.__frmDatepicker || ! window.__frmDatepicker ) {
				return;
			}

			frmdates.setupFields();

			$( document ).on( 'frmPageChanged', frmdates.setupFields );
			$( document ).on( 'frmAfterAddRow frmAfterRemoveRow', frmdates.setupFields );
			$( document ).on( 'frmdates_date_changed', frmdates.callbacks.dateChanged );
		},

		defaultDateOffset: function( fieldConfig, localConfig ) {
			var DefaultDate = fieldConfig.datepickerOptions.minDate;
			var IsAllowed = fieldConfig.datepickerOptions.beforeShowDay( DefaultDate );

			if ( DefaultDate ) {
				if ( false === IsAllowed[0] ) {

					do {
						DefaultDate = frmdates.defaultDate( DefaultDate );

						IsAllowed = fieldConfig.datepickerOptions.beforeShowDay( DefaultDate );
						IsAllowed = IsAllowed[0];
					}
					while ( false === IsAllowed );

				} else {
					localConfig.defaultDate = DefaultDate;
				}
			}

		},

		defaultDate: function( _date ) {
			_date.setDate( _date.getDate() + 1 );
			return _date;
		},

		callbacks: {
			beforeShowDay: function( date ) {
				var day     = date.getDay();
				var year    = date.getFullYear();
				var month   = ( '0' + ( date.getMonth() + 1 ) ).slice( -2 );
				var day_    = ( '0' + date.getDate() ).slice( -2 );
				var dateISO = year + '-' + month + '-' + day_;

				var isAllowed = false;

				if ( -1 !== $.inArray( dateISO, this.datesEnabled ) ) {
					isAllowed = true;
				} else if ( -1 !== $.inArray( dateISO, this.datesDisabled ) ) {
					isAllowed = false;
				} else if ( -1 !== $.inArray( day, this.daysEnabled ) ) {
					isAllowed = true;
				}

				return [ isAllowed, '' ];
			},

			onSelect: function( dateText, instance ) {
				$( document ).trigger( 'frmdates_date_changed', [this, dateText, instance] );
				instance.input.trigger( 'change' );
			},

			dateChanged: function() {
				frmdates.setupFields(); // TODO: For now, we refresh everything, but we should be more clever here.
			}
		}
	};

    frmdates.init();

} );

