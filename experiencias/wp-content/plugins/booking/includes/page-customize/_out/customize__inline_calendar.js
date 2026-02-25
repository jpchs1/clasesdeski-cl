"use strict";

/**
 * Define JavaScript variables for front-end calendar for backward compatibility
 *
 * @param calendar_params_arr example:{
											'html_id'           : 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
											'text_id'           : 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,

											'calendar__booking_start_day_weeek': 	  calendar_params_arr.ajx_cleaned_params.calendar__booking_start_day_weeek,
											'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
											'calendar__days_selection_mode':  calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,

											'resource_id'        : calendar_params_arr.ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar' : calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
											'booked_dates'       : calendar_params_arr.ajx_data_arr.booked_dates,
											'season_customize_plugin': calendar_params_arr.ajx_data_arr.season_customize_plugin,

											'resource_unavailable_dates' : calendar_params_arr.ajx_data_arr.resource_unavailable_dates
										}
 */
function wpbc_assign_global_js_for_calendar(calendar_params_arr) {
  //TODO: need to  test it before remove
}

/**
 * 	Load Datepick Inline calendar
 *
 * @param calendar_params_arr		example:{
											'html_id'           : 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
											'text_id'           : 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,

											'calendar__booking_start_day_weeek': 	  calendar_params_arr.ajx_cleaned_params.calendar__booking_start_day_weeek,
											'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
											'calendar__days_selection_mode':  calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,

											'resource_id'        : calendar_params_arr.ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar' : calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
											'booked_dates'       : calendar_params_arr.ajx_data_arr.calendar_settings.booked_dates,
											'season_customize_plugin': calendar_params_arr.ajx_data_arr.season_customize_plugin,

											'resource_unavailable_dates' : calendar_params_arr.ajx_data_arr.resource_unavailable_dates
										}
 * @returns {boolean}
 */
function wpbc_show_inline_booking_calendar(calendar_params_arr) {
  if (0 === jQuery('#' + calendar_params_arr.html_id).length // If calendar DOM element not exist then exist
  || true === jQuery('#' + calendar_params_arr.html_id).hasClass('hasDatepick') // If the calendar with the same Booking resource already  has been activated, then exist.
  ) {
    return false;
  }

  //------------------------------------------------------------------------------------------------------------------
  //  JavaScript variables for front-end calendar
  //------------------------------------------------------------------------------------------------------------------
  wpbc_assign_global_js_for_calendar(calendar_params_arr);

  //------------------------------------------------------------------------------------------------------------------
  // Configure and show calendar
  //------------------------------------------------------------------------------------------------------------------
  jQuery('#' + calendar_params_arr.html_id).text('');
  jQuery('#' + calendar_params_arr.html_id).datepick({
    beforeShowDay: function beforeShowDay(date) {
      return wpbc__inline_booking_calendar__apply_css_to_days(date, calendar_params_arr, this);
    },
    onSelect: function onSelect(date) {
      jQuery('#' + calendar_params_arr.text_id).val(date);
      //wpbc_blink_element('.wpbc_widget_change_calendar_skin', 3, 220);
      return wpbc__inline_booking_calendar__on_days_select(date, calendar_params_arr, this);
    },
    onHover: function onHover(value, date) {
      //wpbc_cstm__prepare_tooltip__in_calendar( value, date, calendar_params_arr, this );
      return wpbc__inline_booking_calendar__on_days_hover(value, date, calendar_params_arr, this);
    },
    onChangeMonthYear:
    //null,
    function onChangeMonthYear(year, month) {
      return wpbc__inline_booking_calendar__on_change_year_month(year, month, calendar_params_arr, this);
    },
    showOn: 'both',
    numberOfMonths: calendar_params_arr.calendar__view__visible_months,
    stepMonths: 1,
    prevText: '&laquo;',
    nextText: '&raquo;',
    dateFormat: 'dd.mm.yy',
    // 'yy-mm-dd',
    changeMonth: false,
    changeYear: false,
    minDate: 0,
    //null,  	// Scroll as long as you need
    maxDate: calendar_params_arr.calendar__booking_max_monthes_in_calendar,
    // minDate: new Date(2020, 2, 1), maxDate: new Date(2020, 9, 31), 	// Ability to set any  start and end date in calendar
    showStatus: false,
    closeAtTop: false,
    firstDay: calendar_params_arr.calendar__booking_start_day_weeek,
    gotoCurrent: false,
    hideIfNoPrevNext: true,
    multiSeparator: ', ',
    /*  'multiSelect' can  be 0   for 'single', 'dynamic'
      			  and can  be 365 for 'multiple', 'fixed'
      			  																						// Maximum number of selectable dates:	 Single day = 0,  multi days = 365
     */
    multiSelect: 'single' == calendar_params_arr.calendar__days_selection_mode || 'dynamic' == calendar_params_arr.calendar__days_selection_mode ? 0 : 365,
    /*  'rangeSelect' true  for 'dynamic'
    				  false for 'single', 'multiple', 'fixed'
     */
    rangeSelect: 'dynamic' == calendar_params_arr.calendar__days_selection_mode,
    rangeSeparator: ' - ',
    //	' ~ ',	//' - ',
    // showWeeks: true,
    useThemeRoller: false
  });
  return true;
}

/**
 * When  we scroll  month in calendar  then  trigger specific event
 * @param year
 * @param month
 * @param calendar_params_arr
 * @param datepick_this
 */
function wpbc__inline_booking_calendar__on_change_year_month(year, month, calendar_params_arr, datepick_this) {
  /**
   *   We need to use inst.drawMonth  instead of month variable.
   *   It is because,  each  time,  when we use dynamic arnge selection,  the month here are different
   */

  var inst = jQuery.datepick._getInst(datepick_this);
  jQuery('body').trigger('wpbc__inline_booking_calendar__changed_year_month' // event name
  , [inst.drawYear, inst.drawMonth + 1, calendar_params_arr, datepick_this]);
  // To catch this event: jQuery( 'body' ).on('wpbc__inline_booking_calendar__changed_year_month', function( event, year, month, calendar_params_arr, datepick_this ) { ... } );
}

/**
 * Apply CSS to calendar date cells
 *
 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																  "html_id": "calendar_booking4",
																  "text_id": "date_booking4",
																  "calendar__booking_start_day_weeek": 1,
																  "calendar__view__visible_months": 12,
																  "resource_id": 4,
																  "ajx_nonce_calendar": "<input type=\"hidden\" ... />",
																  "booked_dates": {
																	"12-28-2022": [
																	  {
																		"booking_date": "2022-12-28 00:00:00",
																		"approved": "1",
																		"booking_id": "26"
																	  }
																	], ...
																	}
																	'season_customize_plugin':{
																		"2023-01-09": true,
																		"2023-01-10": true,
																		"2023-01-11": true, ...
																	}
																  }
																}
 * @param datepick_this			- this of datepick Obj
 *
 * @returns [boolean,string]	- [ {true -available | false - unavailable}, 'CSS classes for calendar day cell' ]
 */
function wpbc__inline_booking_calendar__apply_css_to_days(date, calendar_params_arr, datepick_this) {
  var today_date = new Date(_wpbc.get_other_param('today_arr')[0], parseInt(_wpbc.get_other_param('today_arr')[1]) - 1, _wpbc.get_other_param('today_arr')[2], 0, 0, 0);
  var class_day = date.getMonth() + 1 + '-' + date.getDate() + '-' + date.getFullYear(); // '1-9-2023'
  var sql_class_day = wpbc__get__sql_class_date(date); // '2023-01-09'

  var css_date__standard = 'cal4date-' + class_day;
  var css_date__additional = ' wpbc_weekday_' + date.getDay() + ' ';

  //--------------------------------------------------------------------------------------------------------------

  // WEEKDAYS :: Set unavailable week days from - Settings General page in "Availability" section
  for (var i = 0; i < _wpbc.get_other_param('availability__week_days_unavailable').length; i++) {
    if (date.getDay() == _wpbc.get_other_param('availability__week_days_unavailable')[i]) {
      return [false, css_date__standard + ' date_user_unavailable' + ' weekdays_unavailable'];
    }
  }

  // BEFORE_AFTER :: Set unavailable days Before / After the Today date
  if (wpbc_dates__days_between(date, today_date) < parseInt(_wpbc.get_other_param('availability__unavailable_from_today')) || parseInt('0' + parseInt(_wpbc.get_other_param('availability__available_from_today'))) > 0 && wpbc_dates__days_between(date, today_date) > parseInt('0' + parseInt(_wpbc.get_other_param('availability__available_from_today')))) {
    return [false, css_date__standard + ' date_user_unavailable' + ' before_after_unavailable'];
  }

  // SEASONS ::  					Booking > Resources > Availability page
  var is_date_available = calendar_params_arr.season_customize_plugin[sql_class_day];
  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [false, css_date__standard + ' date_user_unavailable' + ' season_unavailable'];
  }

  // RESOURCE_UNAVAILABLE ::   	Booking > Customize page
  if (wpbc_in_array(calendar_params_arr.resource_unavailable_dates, sql_class_day)) {
    is_date_available = false;
  }
  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [false, css_date__standard + ' date_user_unavailable' + ' resource_unavailable'];
  }

  //--------------------------------------------------------------------------------------------------------------

  //--------------------------------------------------------------------------------------------------------------

  // Is any bookings in this date ?
  if ('undefined' !== typeof calendar_params_arr.booked_dates[class_day]) {
    var bookings_in_date = calendar_params_arr.booked_dates[class_day];
    if ('undefined' !== typeof bookings_in_date['sec_0']) {
      // "Full day" booking  -> (seconds == 0)

      css_date__additional += '0' === bookings_in_date['sec_0'].approved ? ' date2approve ' : ' date_approved '; // Pending = '0' |  Approved = '1'
      css_date__additional += ' full_day_booking';
      return [false, css_date__standard + css_date__additional];
    } else if (Object.keys(bookings_in_date).length > 0) {
      // "Time slots" Bookings

      var is_approved = true;
      _.each(bookings_in_date, function (p_val, p_key, p_data) {
        if (!parseInt(p_val.approved)) {
          is_approved = false;
        }
        var ts = p_val.booking_date.substring(p_val.booking_date.length - 1);
        if (true === _wpbc.get_other_param('is_enabled_change_over')) {
          if (ts == '1') {
            css_date__additional += ' check_in_time' + (parseInt(p_val.approved) ? ' check_in_time_date_approved' : ' check_in_time_date2approve');
          }
          if (ts == '2') {
            css_date__additional += ' check_out_time' + (parseInt(p_val.approved) ? ' check_out_time_date_approved' : ' check_out_time_date2approve');
          }
        }
      });
      if (!is_approved) {
        css_date__additional += ' date2approve timespartly';
      } else {
        css_date__additional += ' date_approved timespartly';
      }
      if (!_wpbc.get_other_param('is_enabled_change_over')) {
        css_date__additional += ' times_clock';
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------

  return [true, css_date__standard + css_date__additional + ' date_available'];
}

//TODO: need to  use wpbc_calendar script,  instead of this one
/**
 * Apply some CSS classes, when we mouse over specific dates in calendar
 * @param value
 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																  "html_id": "calendar_booking4",
																  "text_id": "date_booking4",
																  "calendar__booking_start_day_weeek": 1,
																  "calendar__view__visible_months": 12,
																  "resource_id": 4,
																  "ajx_nonce_calendar": "<input type=\"hidden\" ... />",
																  "booked_dates": {
																	"12-28-2022": [
																	  {
																		"booking_date": "2022-12-28 00:00:00",
																		"approved": "1",
																		"booking_id": "26"
																	  }
																	], ...
																	}
																	'season_customize_plugin':{
																		"2023-01-09": true,
																		"2023-01-10": true,
																		"2023-01-11": true, ...
																	}
																  }
																}
 * @param datepick_this			- this of datepick Obj
 *
 * @returns {boolean}
 */
function wpbc__inline_booking_calendar__on_days_hover(value, date, calendar_params_arr, datepick_this) {
  if (null === date) {
    return;
  }

  // The same functions as in client.css *************************************************************
  //TODO: 2023-06-30 17:22
  if (true) {
    var bk_type = calendar_params_arr.resource_id;
    var is_calendar_booking_unselectable = jQuery('#calendar_booking_unselectable' + bk_type); //FixIn: 8.0.1.2
    var is_booking_form_also = jQuery('#booking_form_div' + bk_type);
    // Set unselectable,  if only Availability Calendar  here (and we do not insert Booking form by mistake).
    if (is_calendar_booking_unselectable.length == 1 && is_booking_form_also.length != 1) {
      jQuery('#calendar_booking' + bk_type + ' .datepick-days-cell-over').removeClass('datepick-days-cell-over'); // clear all highlight days selections
      jQuery('.wpbc_only_calendar #calendar_booking' + bk_type + ' .datepick-days-cell, ' + '.wpbc_only_calendar #calendar_booking' + bk_type + ' .datepick-days-cell a').css('cursor', 'default');
      return false;
    } //FixIn: 8.0.1.2	end

    return true;
  }
  // *************************************************************************************************

  if (null === date) {
    jQuery('.datepick-days-cell-over').removeClass('datepick-days-cell-over'); // clear all highlight days selections
    return false;
  }
  var inst = jQuery.datepick._getInst(document.getElementById('calendar_booking' + calendar_params_arr.resource_id));
  if (1 == inst.dates.length // If we have one selected date
  && 'dynamic' === calendar_params_arr.calendar__days_selection_mode // while have range days selection mode
  ) {
    var td_class;
    var td_overs = [];
    var is_check = true;
    var selceted_first_day = new Date();
    selceted_first_day.setFullYear(inst.dates[0].getFullYear(), inst.dates[0].getMonth(), inst.dates[0].getDate()); //Get first Date

    while (is_check) {
      td_class = selceted_first_day.getMonth() + 1 + '-' + selceted_first_day.getDate() + '-' + selceted_first_day.getFullYear();
      td_overs[td_overs.length] = '#calendar_booking' + calendar_params_arr.resource_id + ' .cal4date-' + td_class; // add to array for later make selection by class

      if (date.getMonth() == selceted_first_day.getMonth() && date.getDate() == selceted_first_day.getDate() && date.getFullYear() == selceted_first_day.getFullYear() || selceted_first_day > date) {
        is_check = false;
      }
      selceted_first_day.setFullYear(selceted_first_day.getFullYear(), selceted_first_day.getMonth(), selceted_first_day.getDate() + 1);
    }

    // Highlight Days
    for (var i = 0; i < td_overs.length; i++) {
      // add class to all elements
      jQuery(td_overs[i]).addClass('datepick-days-cell-over');
    }
    return true;
  }
  return true;
}

//TODO: need to  use wpbc_calendar script,  instead of this one

/**
 * On DAYs selection in calendar
 *
 * @param dates_selection		-  string:			 '2023-03-07 ~ 2023-03-07' or '2023-04-10, 2023-04-12, 2023-04-02, 2023-04-04'
 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																  "html_id": "calendar_booking4",
																  "text_id": "date_booking4",
																  "calendar__booking_start_day_weeek": 1,
																  "calendar__view__visible_months": 12,
																  "resource_id": 4,
																  "ajx_nonce_calendar": "<input type=\"hidden\" ... />",
																  "booked_dates": {
																	"12-28-2022": [
																	  {
																		"booking_date": "2022-12-28 00:00:00",
																		"approved": "1",
																		"booking_id": "26"
																	  }
																	], ...
																	}
																	'season_customize_plugin':{
																		"2023-01-09": true,
																		"2023-01-10": true,
																		"2023-01-11": true, ...
																	}
																  }
																}
 * @param datepick_this			- this of datepick Obj
 *
 * @returns boolean
 */
function wpbc__inline_booking_calendar__on_days_select(dates_selection, calendar_params_arr) {
  var datepick_this = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  // The same functions as in client.css			//TODO: 2023-06-30 17:22
  if (true) {
    var bk_type = calendar_params_arr.resource_id;
    var date = dates_selection;

    // Set unselectable,  if only Availability Calendar  here (and we do not insert Booking form by mistake).
    var is_calendar_booking_unselectable = jQuery('#calendar_booking_unselectable' + bk_type); //FixIn: 8.0.1.2
    var is_booking_form_also = jQuery('#booking_form_div' + bk_type);
    if (is_calendar_booking_unselectable.length > 0 && is_booking_form_also.length <= 0) {
      wpbc_calendar__unselect_all_dates(bk_type);
      jQuery('.wpbc_only_calendar .popover_calendar_hover').remove(); //Hide all opened popovers
      return false;
    } //FixIn: 8.0.1.2 end

    jQuery('#date_booking' + bk_type).val(date);
    jQuery(".booking_form_div").trigger("date_selected", [bk_type, date]);
  } else {
    // Functionality  from  Booking > Availability page

    var inst = jQuery.datepick._getInst(document.getElementById('calendar_booking' + calendar_params_arr.resource_id));
    var dates_arr = []; //  [ "2023-04-09", "2023-04-10", "2023-04-11" ]

    if (-1 !== dates_selection.indexOf('~')) {
      // Range Days

      dates_arr = wpbc_get_dates_arr__from_dates_range_js({
        'dates_separator': ' ~ ',
        //  ' ~ '
        'dates': dates_selection // '2023-04-04 ~ 2023-04-07'
      });
    } else {
      // Multiple Days
      dates_arr = wpbc_get_dates_arr__from_dates_comma_separated_js({
        'dates_separator': ', ',
        //  ', '
        'dates': dates_selection // '2023-04-10, 2023-04-12, 2023-04-02, 2023-04-04'
      });
    }
    wpbc_avy_after_days_selection__show_help_info({
      'calendar__days_selection_mode': calendar_params_arr.calendar__days_selection_mode,
      'dates_arr': dates_arr,
      'dates_click_num': inst.dates.length,
      'popover_hints': calendar_params_arr.popover_hints
    });
  }
  return true;
}

/**
 * Show help info at the top  toolbar about selected dates and future actions
 *
 * @param params
 * 					Example 1:  {
									calendar__days_selection_mode: "dynamic",
									dates_arr:  [ "2023-04-03" ],
									dates_click_num: 1
									'popover_hints'					: calendar_params_arr.popover_hints
								}
 * 					Example 2:  {
									calendar__days_selection_mode: "dynamic"
									dates_arr: Array(10) [ "2023-04-03", "2023-04-04", "2023-04-05", … ]
									dates_click_num: 2
									'popover_hints'					: calendar_params_arr.popover_hints
								}
 */
function wpbc_avy_after_days_selection__show_help_info(params) {
  // console.log( params );	//		[ "2023-04-09", "2023-04-10", "2023-04-11" ]

  var message, color;
  if (jQuery('#ui_btn_cstm__set_days_customize_plugin__available').is(':checked')) {
    message = params.popover_hints.toolbar_text_available; //'Set dates _DATES_ as _HTML_ available.';
    color = '#11be4c';
  } else {
    message = params.popover_hints.toolbar_text_unavailable; //'Set dates _DATES_ as _HTML_ unavailable.';
    color = '#e43939';
  }
  message = '<span>' + message + '</span>';
  var first_date = params['dates_arr'][0];
  var last_date = 'dynamic' == params.calendar__days_selection_mode ? params['dates_arr'][params['dates_arr'].length - 1] : params['dates_arr'].length > 1 ? params['dates_arr'][1] : '';
  first_date = jQuery.datepick.formatDate('dd M, yy', new Date(first_date + 'T00:00:00'));
  last_date = jQuery.datepick.formatDate('dd M, yy', new Date(last_date + 'T00:00:00'));
  if ('dynamic' == params.calendar__days_selection_mode) {
    if (1 == params.dates_click_num) {
      last_date = '___________';
    } else {
      if ('first_time' == jQuery('.wpbc_ajx_customize_plugin_container').attr('wpbc_loaded')) {
        jQuery('.wpbc_ajx_customize_plugin_container').attr('wpbc_loaded', 'done');
        wpbc_blink_element('.wpbc_widget_change_calendar_skin', 3, 220);
      }
    }
    message = message.replace('_DATES_', '</span>'
    //+ '<div>' + 'from' + '</div>'
    + '<span class="wpbc_big_date">' + first_date + '</span>' + '<span>' + '-' + '</span>' + '<span class="wpbc_big_date">' + last_date + '</span>' + '<span>');
  } else {
    // if ( params[ 'dates_arr' ].length > 1 ){
    // 	last_date = ', ' + last_date;
    // 	last_date += ( params[ 'dates_arr' ].length > 2 ) ? ', ...' : '';
    // } else {
    // 	last_date='';
    // }
    var dates_arr = [];
    for (var i = 0; i < params['dates_arr'].length; i++) {
      dates_arr.push(jQuery.datepick.formatDate('dd M yy', new Date(params['dates_arr'][i] + 'T00:00:00')));
    }
    first_date = dates_arr.join(', ');
    message = message.replace('_DATES_', '</span>' + '<span class="wpbc_big_date">' + first_date + '</span>' + '<span>');
  }
  message = message.replace('_HTML_', '</span><span class="wpbc_big_text" style="color:' + color + ';">') + '<span>';

  //message += ' <div style="margin-left: 1em;">' + ' Click on Apply button to apply customize_plugin.' + '</div>';

  message = '<div class="wpbc_toolbar_dates_hints">' + message + '</div>';
  jQuery('.wpbc_help_text').html(message);
}

/**
 *   Parse dates  ------------------------------------------------------------------------------------------- */

/**
 * Get dates array,  from comma separated dates
 *
 * @param params       = {
									* 'dates_separator' => ', ',                                        // Dates separator
									* 'dates'           => '2023-04-04, 2023-04-07, 2023-04-05'         // Dates in 'Y-m-d' format: '2023-01-31'
						 }
 *
 * @return array      = [
									* [0] => 2023-04-04
									* [1] => 2023-04-05
									* [2] => 2023-04-06
									* [3] => 2023-04-07
						]
 *
 * Example #1:  wpbc_get_dates_arr__from_dates_comma_separated_js(  {  'dates_separator' : ', ', 'dates' : '2023-04-04, 2023-04-07, 2023-04-05'  }  );
 */
function wpbc_get_dates_arr__from_dates_comma_separated_js(params) {
  var dates_arr = [];
  if ('' !== params['dates']) {
    dates_arr = params['dates'].split(params['dates_separator']);
    dates_arr.sort();
  }
  return dates_arr;
}

/**
 * Get dates array,  from range days selection
 *
 * @param params       =  {
									* 'dates_separator' => ' ~ ',                         // Dates separator
									* 'dates'           => '2023-04-04 ~ 2023-04-07'      // Dates in 'Y-m-d' format: '2023-01-31'
						  }
 *
 * @return array        = [
									* [0] => 2023-04-04
									* [1] => 2023-04-05
									* [2] => 2023-04-06
									* [3] => 2023-04-07
						  ]
 *
 * Example #1:  wpbc_get_dates_arr__from_dates_range_js(  {  'dates_separator' : ' ~ ', 'dates' : '2023-04-04 ~ 2023-04-07'  }  );
 * Example #2:  wpbc_get_dates_arr__from_dates_range_js(  {  'dates_separator' : ' - ', 'dates' : '2023-04-04 - 2023-04-07'  }  );
 */
function wpbc_get_dates_arr__from_dates_range_js(params) {
  var dates_arr = [];
  if ('' !== params['dates']) {
    dates_arr = params['dates'].split(params['dates_separator']);
    var check_in_date_ymd = dates_arr[0];
    var check_out_date_ymd = dates_arr[1];
    if ('' !== check_in_date_ymd && '' !== check_out_date_ymd) {
      dates_arr = wpbc_get_dates_array_from_start_end_days_js(check_in_date_ymd, check_out_date_ymd);
    }
  }
  return dates_arr;
}

/**
 * Get dates array based on start and end dates.
 *
 * @param string sStartDate - start date: 2023-04-09
 * @param string sEndDate   - end date:   2023-04-11
 * @return array             - [ "2023-04-09", "2023-04-10", "2023-04-11" ]
 */
function wpbc_get_dates_array_from_start_end_days_js(sStartDate, sEndDate) {
  sStartDate = new Date(sStartDate + 'T00:00:00');
  sEndDate = new Date(sEndDate + 'T00:00:00');
  var aDays = [];

  // Start the variable off with the start date
  aDays.push(sStartDate.getTime());

  // Set a 'temp' variable, sCurrentDate, with the start date - before beginning the loop
  var sCurrentDate = new Date(sStartDate.getTime());
  var one_day_duration = 24 * 60 * 60 * 1000;

  // While the current date is less than the end date
  while (sCurrentDate < sEndDate) {
    // Add a day to the current date "+1 day"
    sCurrentDate.setTime(sCurrentDate.getTime() + one_day_duration);

    // Add this new day to the aDays array
    aDays.push(sCurrentDate.getTime());
  }
  for (var i = 0; i < aDays.length; i++) {
    aDays[i] = new Date(aDays[i]);
    aDays[i] = aDays[i].getFullYear() + '-' + (aDays[i].getMonth() + 1 < 10 ? '0' : '') + (aDays[i].getMonth() + 1) + '-' + (aDays[i].getDate() < 10 ? '0' : '') + aDays[i].getDate();
  }
  // Once the loop has finished, return the array of days.
  return aDays;
}

/**
 * Scroll to  specific "Year & Month" 	in Inline Booking Calendar
 *
 * @param {number} resource_id		1
 * @param {number} year				2023
 * @param {number} month			12			(from 1 to  12)
 *
 * @returns {boolean}			// changed or not
 */
function wpbc__inline_booking_calendar__change_year_month(resource_id, year, month) {
  var inst = jQuery.datepick._getInst(document.getElementById('calendar_booking' + resource_id));
  if (false != inst) {
    year = parseInt(year);
    month = parseInt(month) - 1;
    inst.cursorDate = new Date();
    inst.cursorDate.setFullYear(year, month, 1);
    inst.cursorDate.setMonth(month); // In some cases,  the setFullYear can  set  only Year,  and not the Month and day      //FixIn:6.2.3.5
    inst.cursorDate.setDate(1);
    inst.drawMonth = inst.cursorDate.getMonth();
    inst.drawYear = inst.cursorDate.getFullYear();
    jQuery.datepick._notifyChange(inst);
    jQuery.datepick._adjustInstDate(inst);
    jQuery.datepick._showDate(inst);
    jQuery.datepick._updateDatepick(inst);
    return true;
  }
  return false;
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaW5jbHVkZXMvcGFnZS1jdXN0b21pemUvX291dC9jdXN0b21pemVfX2lubGluZV9jYWxlbmRhci5qcyIsIm5hbWVzIjpbIndwYmNfYXNzaWduX2dsb2JhbF9qc19mb3JfY2FsZW5kYXIiLCJjYWxlbmRhcl9wYXJhbXNfYXJyIiwid3BiY19zaG93X2lubGluZV9ib29raW5nX2NhbGVuZGFyIiwialF1ZXJ5IiwiaHRtbF9pZCIsImxlbmd0aCIsImhhc0NsYXNzIiwidGV4dCIsImRhdGVwaWNrIiwiYmVmb3JlU2hvd0RheSIsImRhdGUiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fYXBwbHlfY3NzX3RvX2RheXMiLCJvblNlbGVjdCIsInRleHRfaWQiLCJ2YWwiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19zZWxlY3QiLCJvbkhvdmVyIiwidmFsdWUiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19ob3ZlciIsIm9uQ2hhbmdlTW9udGhZZWFyIiwieWVhciIsIm1vbnRoIiwid3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2NoYW5nZV95ZWFyX21vbnRoIiwic2hvd09uIiwibnVtYmVyT2ZNb250aHMiLCJjYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHMiLCJzdGVwTW9udGhzIiwicHJldlRleHQiLCJuZXh0VGV4dCIsImRhdGVGb3JtYXQiLCJjaGFuZ2VNb250aCIsImNoYW5nZVllYXIiLCJtaW5EYXRlIiwibWF4RGF0ZSIsImNhbGVuZGFyX19ib29raW5nX21heF9tb250aGVzX2luX2NhbGVuZGFyIiwic2hvd1N0YXR1cyIsImNsb3NlQXRUb3AiLCJmaXJzdERheSIsImNhbGVuZGFyX19ib29raW5nX3N0YXJ0X2RheV93ZWVlayIsImdvdG9DdXJyZW50IiwiaGlkZUlmTm9QcmV2TmV4dCIsIm11bHRpU2VwYXJhdG9yIiwibXVsdGlTZWxlY3QiLCJjYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSIsInJhbmdlU2VsZWN0IiwicmFuZ2VTZXBhcmF0b3IiLCJ1c2VUaGVtZVJvbGxlciIsImRhdGVwaWNrX3RoaXMiLCJpbnN0IiwiX2dldEluc3QiLCJ0cmlnZ2VyIiwiZHJhd1llYXIiLCJkcmF3TW9udGgiLCJ0b2RheV9kYXRlIiwiRGF0ZSIsIl93cGJjIiwiZ2V0X290aGVyX3BhcmFtIiwicGFyc2VJbnQiLCJjbGFzc19kYXkiLCJnZXRNb250aCIsImdldERhdGUiLCJnZXRGdWxsWWVhciIsInNxbF9jbGFzc19kYXkiLCJ3cGJjX19nZXRfX3NxbF9jbGFzc19kYXRlIiwiY3NzX2RhdGVfX3N0YW5kYXJkIiwiY3NzX2RhdGVfX2FkZGl0aW9uYWwiLCJnZXREYXkiLCJpIiwid3BiY19kYXRlc19fZGF5c19iZXR3ZWVuIiwiaXNfZGF0ZV9hdmFpbGFibGUiLCJzZWFzb25fY3VzdG9taXplX3BsdWdpbiIsIndwYmNfaW5fYXJyYXkiLCJyZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlcyIsImJvb2tlZF9kYXRlcyIsImJvb2tpbmdzX2luX2RhdGUiLCJhcHByb3ZlZCIsIk9iamVjdCIsImtleXMiLCJpc19hcHByb3ZlZCIsIl8iLCJlYWNoIiwicF92YWwiLCJwX2tleSIsInBfZGF0YSIsInRzIiwiYm9va2luZ19kYXRlIiwic3Vic3RyaW5nIiwiYmtfdHlwZSIsInJlc291cmNlX2lkIiwiaXNfY2FsZW5kYXJfYm9va2luZ191bnNlbGVjdGFibGUiLCJpc19ib29raW5nX2Zvcm1fYWxzbyIsInJlbW92ZUNsYXNzIiwiY3NzIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImRhdGVzIiwidGRfY2xhc3MiLCJ0ZF9vdmVycyIsImlzX2NoZWNrIiwic2VsY2V0ZWRfZmlyc3RfZGF5Iiwic2V0RnVsbFllYXIiLCJhZGRDbGFzcyIsImRhdGVzX3NlbGVjdGlvbiIsImFyZ3VtZW50cyIsInVuZGVmaW5lZCIsIndwYmNfY2FsZW5kYXJfX3Vuc2VsZWN0X2FsbF9kYXRlcyIsInJlbW92ZSIsImRhdGVzX2FyciIsImluZGV4T2YiLCJ3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMiLCJ3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfY29tbWFfc2VwYXJhdGVkX2pzIiwid3BiY19hdnlfYWZ0ZXJfZGF5c19zZWxlY3Rpb25fX3Nob3dfaGVscF9pbmZvIiwicG9wb3Zlcl9oaW50cyIsInBhcmFtcyIsIm1lc3NhZ2UiLCJjb2xvciIsImlzIiwidG9vbGJhcl90ZXh0X2F2YWlsYWJsZSIsInRvb2xiYXJfdGV4dF91bmF2YWlsYWJsZSIsImZpcnN0X2RhdGUiLCJsYXN0X2RhdGUiLCJmb3JtYXREYXRlIiwiZGF0ZXNfY2xpY2tfbnVtIiwiYXR0ciIsIndwYmNfYmxpbmtfZWxlbWVudCIsInJlcGxhY2UiLCJwdXNoIiwiam9pbiIsImh0bWwiLCJzcGxpdCIsInNvcnQiLCJjaGVja19pbl9kYXRlX3ltZCIsImNoZWNrX291dF9kYXRlX3ltZCIsIndwYmNfZ2V0X2RhdGVzX2FycmF5X2Zyb21fc3RhcnRfZW5kX2RheXNfanMiLCJzU3RhcnREYXRlIiwic0VuZERhdGUiLCJhRGF5cyIsImdldFRpbWUiLCJzQ3VycmVudERhdGUiLCJvbmVfZGF5X2R1cmF0aW9uIiwic2V0VGltZSIsIndwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19jaGFuZ2VfeWVhcl9tb250aCIsImN1cnNvckRhdGUiLCJzZXRNb250aCIsInNldERhdGUiLCJfbm90aWZ5Q2hhbmdlIiwiX2FkanVzdEluc3REYXRlIiwiX3Nob3dEYXRlIiwiX3VwZGF0ZURhdGVwaWNrIl0sInNvdXJjZXMiOlsiaW5jbHVkZXMvcGFnZS1jdXN0b21pemUvX3NyYy9jdXN0b21pemVfX2lubGluZV9jYWxlbmRhci5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJcInVzZSBzdHJpY3RcIjtcclxuXHJcbi8qKlxyXG4gKiBEZWZpbmUgSmF2YVNjcmlwdCB2YXJpYWJsZXMgZm9yIGZyb250LWVuZCBjYWxlbmRhciBmb3IgYmFja3dhcmQgY29tcGF0aWJpbGl0eVxyXG4gKlxyXG4gKiBAcGFyYW0gY2FsZW5kYXJfcGFyYW1zX2FyciBleGFtcGxlOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdodG1sX2lkJyAgICAgICAgICAgOiAnY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCd0ZXh0X2lkJyAgICAgICAgICAgOiAnZGF0ZV9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdjYWxlbmRhcl9fYm9va2luZ19zdGFydF9kYXlfd2VlZWsnOiBcdCAgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRocyc6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRocyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdjYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSc6ICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSxcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncmVzb3VyY2VfaWQnICAgICAgICA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9ub25jZV9jYWxlbmRhcicgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5hanhfbm9uY2VfY2FsZW5kYXIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2VkX2RhdGVzJyAgICAgICA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLmJvb2tlZF9kYXRlcyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdzZWFzb25fY3VzdG9taXplX3BsdWdpbic6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLnNlYXNvbl9jdXN0b21pemVfcGx1Z2luLFxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdyZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlcycgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5yZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlc1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYXNzaWduX2dsb2JhbF9qc19mb3JfY2FsZW5kYXIoIGNhbGVuZGFyX3BhcmFtc19hcnIgKXtcclxuLy9UT0RPOiBuZWVkIHRvICB0ZXN0IGl0IGJlZm9yZSByZW1vdmVcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiBcdExvYWQgRGF0ZXBpY2sgSW5saW5lIGNhbGVuZGFyXHJcbiAqXHJcbiAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHRcdGV4YW1wbGU6e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2h0bWxfaWQnICAgICAgICAgICA6ICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3RleHRfaWQnICAgICAgICAgICA6ICdkYXRlX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19ib29raW5nX3N0YXJ0X2RheV93ZWVlayc6IFx0ICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fYm9va2luZ19zdGFydF9kYXlfd2VlZWssXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlJzogIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlLFxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdyZXNvdXJjZV9pZCcgICAgICAgIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X25vbmNlX2NhbGVuZGFyJyA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLmFqeF9ub25jZV9jYWxlbmRhcixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29rZWRfZGF0ZXMnICAgICAgIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuY2FsZW5kYXJfc2V0dGluZ3MuYm9va2VkX2RhdGVzLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9jdXN0b21pemVfcGx1Z2luJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4sXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzJyA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLnJlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG4gKiBAcmV0dXJucyB7Ym9vbGVhbn1cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfc2hvd19pbmxpbmVfYm9va2luZ19jYWxlbmRhciggY2FsZW5kYXJfcGFyYW1zX2FyciApe1xyXG5cclxuXHRpZiAoXHJcblx0XHQgICAoIDAgPT09IGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5odG1sX2lkICkubGVuZ3RoIClcdFx0XHRcdFx0XHRcdC8vIElmIGNhbGVuZGFyIERPTSBlbGVtZW50IG5vdCBleGlzdCB0aGVuIGV4aXN0XHJcblx0XHR8fCAoIHRydWUgPT09IGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5odG1sX2lkICkuaGFzQ2xhc3MoICdoYXNEYXRlcGljaycgKSApXHQvLyBJZiB0aGUgY2FsZW5kYXIgd2l0aCB0aGUgc2FtZSBCb29raW5nIHJlc291cmNlIGFscmVhZHkgIGhhcyBiZWVuIGFjdGl2YXRlZCwgdGhlbiBleGlzdC5cclxuXHQpe1xyXG5cdCAgIHJldHVybiBmYWxzZTtcclxuXHR9XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gIEphdmFTY3JpcHQgdmFyaWFibGVzIGZvciBmcm9udC1lbmQgY2FsZW5kYXJcclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHdwYmNfYXNzaWduX2dsb2JhbF9qc19mb3JfY2FsZW5kYXIoIGNhbGVuZGFyX3BhcmFtc19hcnIgKTtcclxuXHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gQ29uZmlndXJlIGFuZCBzaG93IGNhbGVuZGFyXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRqUXVlcnkoICcjJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuaHRtbF9pZCApLnRleHQoICcnICk7XHJcblx0alF1ZXJ5KCAnIycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmh0bWxfaWQgKS5kYXRlcGljayh7XHJcblx0XHRcdFx0XHRiZWZvcmVTaG93RGF5OiBcdGZ1bmN0aW9uICggZGF0ZSApe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHJldHVybiB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fYXBwbHlfY3NzX3RvX2RheXMoIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIHRoaXMgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcclxuICAgICAgICAgICAgICAgICAgICBvblNlbGVjdDogXHQgIFx0ZnVuY3Rpb24gKCBkYXRlICl7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCAnIycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnRleHRfaWQgKS52YWwoIGRhdGUgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQvL3dwYmNfYmxpbmtfZWxlbWVudCgnLndwYmNfd2lkZ2V0X2NoYW5nZV9jYWxlbmRhcl9za2luJywgMywgMjIwKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRyZXR1cm4gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfc2VsZWN0KCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCB0aGlzICk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdH0sXHJcbiAgICAgICAgICAgICAgICAgICAgb25Ib3ZlcjogXHRcdGZ1bmN0aW9uICggdmFsdWUsIGRhdGUgKXtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQvL3dwYmNfY3N0bV9fcHJlcGFyZV90b29sdGlwX19pbl9jYWxlbmRhciggdmFsdWUsIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIHRoaXMgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRyZXR1cm4gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfaG92ZXIoIHZhbHVlLCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCB0aGlzICk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdH0sXHJcbiAgICAgICAgICAgICAgICAgICAgb25DaGFuZ2VNb250aFllYXI6XHQvL251bGwsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0ZnVuY3Rpb24gKCB5ZWFyLCBtb250aCApe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0cmV0dXJuIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9jaGFuZ2VfeWVhcl9tb250aCggeWVhciwgbW9udGgsIGNhbGVuZGFyX3BhcmFtc19hcnIsIHRoaXMgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9LFxyXG4gICAgICAgICAgICAgICAgICAgIHNob3dPbjogXHRcdFx0J2JvdGgnLFxyXG4gICAgICAgICAgICAgICAgICAgIG51bWJlck9mTW9udGhzOiBcdGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzLFxyXG4gICAgICAgICAgICAgICAgICAgIHN0ZXBNb250aHM6XHRcdFx0MSxcclxuICAgICAgICAgICAgICAgICAgICBwcmV2VGV4dDogXHRcdFx0JyZsYXF1bzsnLFxyXG4gICAgICAgICAgICAgICAgICAgIG5leHRUZXh0OiBcdFx0XHQnJnJhcXVvOycsXHJcbiAgICAgICAgICAgICAgICAgICAgZGF0ZUZvcm1hdDogXHRcdCdkZC5tbS55eScsXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gJ3l5LW1tLWRkJyxcclxuICAgICAgICAgICAgICAgICAgICBjaGFuZ2VNb250aDogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGNoYW5nZVllYXI6IFx0XHRmYWxzZSxcclxuICAgICAgICAgICAgICAgICAgICBtaW5EYXRlOiBcdFx0XHQwLFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL251bGwsICBcdC8vIFNjcm9sbCBhcyBsb25nIGFzIHlvdSBuZWVkXHJcblx0XHRcdFx0XHRtYXhEYXRlOiBcdFx0XHRjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19ib29raW5nX21heF9tb250aGVzX2luX2NhbGVuZGFyLFx0XHRcdFx0XHQvLyBtaW5EYXRlOiBuZXcgRGF0ZSgyMDIwLCAyLCAxKSwgbWF4RGF0ZTogbmV3IERhdGUoMjAyMCwgOSwgMzEpLCBcdC8vIEFiaWxpdHkgdG8gc2V0IGFueSAgc3RhcnQgYW5kIGVuZCBkYXRlIGluIGNhbGVuZGFyXHJcbiAgICAgICAgICAgICAgICAgICAgc2hvd1N0YXR1czogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGNsb3NlQXRUb3A6IFx0XHRmYWxzZSxcclxuICAgICAgICAgICAgICAgICAgICBmaXJzdERheTpcdFx0XHRjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19ib29raW5nX3N0YXJ0X2RheV93ZWVlayxcclxuICAgICAgICAgICAgICAgICAgICBnb3RvQ3VycmVudDogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGhpZGVJZk5vUHJldk5leHQ6XHR0cnVlLFxyXG4gICAgICAgICAgICAgICAgICAgIG11bHRpU2VwYXJhdG9yOiBcdCcsICcsXHJcblx0XHRcdFx0XHQvKiAgJ211bHRpU2VsZWN0JyBjYW4gIGJlIDAgICBmb3IgJ3NpbmdsZScsICdkeW5hbWljJ1xyXG5cdFx0XHRcdFx0ICBcdFx0XHQgIGFuZCBjYW4gIGJlIDM2NSBmb3IgJ211bHRpcGxlJywgJ2ZpeGVkJ1xyXG5cdFx0XHRcdFx0ICBcdFx0XHQgIFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gTWF4aW11bSBudW1iZXIgb2Ygc2VsZWN0YWJsZSBkYXRlczpcdCBTaW5nbGUgZGF5ID0gMCwgIG11bHRpIGRheXMgPSAzNjVcclxuXHRcdFx0XHRcdCAqL1xyXG5cdFx0XHRcdFx0bXVsdGlTZWxlY3Q6ICAoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0ICAgKCAnc2luZ2xlJyAgPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSApXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fHwgKCAnZHluYW1pYycgPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSApXHJcblx0XHRcdFx0XHRcdFx0XHRcdCAgID8gMFxyXG5cdFx0XHRcdFx0XHRcdFx0XHQgICA6IDM2NVxyXG5cdFx0XHRcdFx0XHRcdFx0ICApLFxyXG5cdFx0XHRcdFx0LyogICdyYW5nZVNlbGVjdCcgdHJ1ZSAgZm9yICdkeW5hbWljJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHQgIGZhbHNlIGZvciAnc2luZ2xlJywgJ211bHRpcGxlJywgJ2ZpeGVkJ1xyXG5cdFx0XHRcdFx0ICovXHJcblx0XHRcdFx0XHRyYW5nZVNlbGVjdDogICgnZHluYW1pYycgPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSksXHJcblx0XHRcdFx0XHRyYW5nZVNlcGFyYXRvcjogJyAtICcsIFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL1x0JyB+ICcsXHQvLycgLSAnLFxyXG4gICAgICAgICAgICAgICAgICAgIC8vIHNob3dXZWVrczogdHJ1ZSxcclxuICAgICAgICAgICAgICAgICAgICB1c2VUaGVtZVJvbGxlcjpcdFx0ZmFsc2VcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICApO1xyXG5cclxuXHRyZXR1cm4gIHRydWU7XHJcbn1cclxuXHJcblxyXG5cclxuXHQvKipcclxuXHQgKiBXaGVuICB3ZSBzY3JvbGwgIG1vbnRoIGluIGNhbGVuZGFyICB0aGVuICB0cmlnZ2VyIHNwZWNpZmljIGV2ZW50XHJcblx0ICogQHBhcmFtIHllYXJcclxuXHQgKiBAcGFyYW0gbW9udGhcclxuXHQgKiBAcGFyYW0gY2FsZW5kYXJfcGFyYW1zX2FyclxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2NoYW5nZV95ZWFyX21vbnRoKCB5ZWFyLCBtb250aCwgY2FsZW5kYXJfcGFyYW1zX2FyciwgZGF0ZXBpY2tfdGhpcyApe1xyXG5cclxuXHRcdC8qKlxyXG5cdFx0ICogICBXZSBuZWVkIHRvIHVzZSBpbnN0LmRyYXdNb250aCAgaW5zdGVhZCBvZiBtb250aCB2YXJpYWJsZS5cclxuXHRcdCAqICAgSXQgaXMgYmVjYXVzZSwgIGVhY2ggIHRpbWUsICB3aGVuIHdlIHVzZSBkeW5hbWljIGFybmdlIHNlbGVjdGlvbiwgIHRoZSBtb250aCBoZXJlIGFyZSBkaWZmZXJlbnRcclxuXHRcdCAqL1xyXG5cclxuXHRcdHZhciBpbnN0ID0galF1ZXJ5LmRhdGVwaWNrLl9nZXRJbnN0KCBkYXRlcGlja190aGlzICk7XHJcblxyXG5cdFx0alF1ZXJ5KCAnYm9keScgKS50cmlnZ2VyKCBcdCAgJ3dwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19jaGFuZ2VkX3llYXJfbW9udGgnXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBldmVudCBuYW1lXHJcblx0XHRcdFx0XHRcdFx0XHQgXHQsIFtpbnN0LmRyYXdZZWFyLCAoaW5zdC5kcmF3TW9udGgrMSksIGNhbGVuZGFyX3BhcmFtc19hcnIsIGRhdGVwaWNrX3RoaXNdXHJcblx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cdFx0Ly8gVG8gY2F0Y2ggdGhpcyBldmVudDogalF1ZXJ5KCAnYm9keScgKS5vbignd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2NoYW5nZWRfeWVhcl9tb250aCcsIGZ1bmN0aW9uKCBldmVudCwgeWVhciwgbW9udGgsIGNhbGVuZGFyX3BhcmFtc19hcnIsIGRhdGVwaWNrX3RoaXMgKSB7IC4uLiB9ICk7XHJcblx0fVxyXG5cclxuXHQvKipcclxuXHQgKiBBcHBseSBDU1MgdG8gY2FsZW5kYXIgZGF0ZSBjZWxsc1xyXG5cdCAqXHJcblx0ICogQHBhcmFtIGRhdGVcdFx0XHRcdFx0LSAgSmF2YVNjcmlwdCBEYXRlIE9iajogIFx0XHRNb24gRGVjIDExIDIwMjMgMDA6MDA6MDAgR01UKzAyMDAgKEVhc3Rlcm4gRXVyb3BlYW4gU3RhbmRhcmQgVGltZSlcclxuXHQgKiBAcGFyYW0gY2FsZW5kYXJfcGFyYW1zX2Fyclx0LSAgQ2FsZW5kYXIgU2V0dGluZ3MgT2JqZWN0OiAgXHR7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiaHRtbF9pZFwiOiBcImNhbGVuZGFyX2Jvb2tpbmc0XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwidGV4dF9pZFwiOiBcImRhdGVfYm9va2luZzRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJjYWxlbmRhcl9fYm9va2luZ19zdGFydF9kYXlfd2VlZWtcIjogMSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJjYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHNcIjogMTIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwicmVzb3VyY2VfaWRcIjogNCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJhanhfbm9uY2VfY2FsZW5kYXJcIjogXCI8aW5wdXQgdHlwZT1cXFwiaGlkZGVuXFxcIiAuLi4gLz5cIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJib29rZWRfZGF0ZXNcIjoge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjEyLTI4LTIwMjJcIjogW1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImJvb2tpbmdfZGF0ZVwiOiBcIjIwMjItMTItMjggMDA6MDA6MDBcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImFwcHJvdmVkXCI6IFwiMVwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYm9va2luZ19pZFwiOiBcIjI2XCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdF0sIC4uLlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdzZWFzb25fY3VzdG9taXplX3BsdWdpbic6e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMjAyMy0wMS0wOVwiOiB0cnVlLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMjAyMy0wMS0xMFwiOiB0cnVlLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMjAyMy0wMS0xMVwiOiB0cnVlLCAuLi5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0ICogQHBhcmFtIGRhdGVwaWNrX3RoaXNcdFx0XHQtIHRoaXMgb2YgZGF0ZXBpY2sgT2JqXHJcblx0ICpcclxuXHQgKiBAcmV0dXJucyBbYm9vbGVhbixzdHJpbmddXHQtIFsge3RydWUgLWF2YWlsYWJsZSB8IGZhbHNlIC0gdW5hdmFpbGFibGV9LCAnQ1NTIGNsYXNzZXMgZm9yIGNhbGVuZGFyIGRheSBjZWxsJyBdXHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2FwcGx5X2Nzc190b19kYXlzKCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzICl7XHJcblxyXG5cdFx0dmFyIHRvZGF5X2RhdGUgPSBuZXcgRGF0ZSggX3dwYmMuZ2V0X290aGVyX3BhcmFtKCAndG9kYXlfYXJyJyApWyAwIF0sIChwYXJzZUludCggX3dwYmMuZ2V0X290aGVyX3BhcmFtKCAndG9kYXlfYXJyJyApWyAxIF0gKSAtIDEpLCBfd3BiYy5nZXRfb3RoZXJfcGFyYW0oICd0b2RheV9hcnInIClbIDIgXSwgMCwgMCwgMCApO1xyXG5cclxuXHRcdHZhciBjbGFzc19kYXkgID0gKCBkYXRlLmdldE1vbnRoKCkgKyAxICkgKyAnLScgKyBkYXRlLmdldERhdGUoKSArICctJyArIGRhdGUuZ2V0RnVsbFllYXIoKTtcdFx0XHRcdFx0XHQvLyAnMS05LTIwMjMnXHJcblx0XHR2YXIgc3FsX2NsYXNzX2RheSA9IHdwYmNfX2dldF9fc3FsX2NsYXNzX2RhdGUoIGRhdGUgKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyAnMjAyMy0wMS0wOSdcclxuXHJcblx0XHR2YXIgY3NzX2RhdGVfX3N0YW5kYXJkICAgPSAgJ2NhbDRkYXRlLScgKyBjbGFzc19kYXk7XHJcblx0XHR2YXIgY3NzX2RhdGVfX2FkZGl0aW9uYWwgPSAnIHdwYmNfd2Vla2RheV8nICsgZGF0ZS5nZXREYXkoKSArICcgJztcclxuXHJcblx0XHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblxyXG5cdFx0Ly8gV0VFS0RBWVMgOjogU2V0IHVuYXZhaWxhYmxlIHdlZWsgZGF5cyBmcm9tIC0gU2V0dGluZ3MgR2VuZXJhbCBwYWdlIGluIFwiQXZhaWxhYmlsaXR5XCIgc2VjdGlvblxyXG5cdFx0Zm9yICggdmFyIGkgPSAwOyBpIDwgX3dwYmMuZ2V0X290aGVyX3BhcmFtKCAnYXZhaWxhYmlsaXR5X193ZWVrX2RheXNfdW5hdmFpbGFibGUnICkubGVuZ3RoOyBpKysgKXtcclxuXHRcdFx0aWYgKCBkYXRlLmdldERheSgpID09IF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ2F2YWlsYWJpbGl0eV9fd2Vla19kYXlzX3VuYXZhaWxhYmxlJyApWyBpIF0gKSB7XHJcblx0XHRcdFx0cmV0dXJuIFsgZmFsc2UsIGNzc19kYXRlX19zdGFuZGFyZCArICcgZGF0ZV91c2VyX3VuYXZhaWxhYmxlJyBcdCsgJyB3ZWVrZGF5c191bmF2YWlsYWJsZScgXTtcclxuXHRcdFx0fVxyXG5cdFx0fVxyXG5cclxuXHRcdC8vIEJFRk9SRV9BRlRFUiA6OiBTZXQgdW5hdmFpbGFibGUgZGF5cyBCZWZvcmUgLyBBZnRlciB0aGUgVG9kYXkgZGF0ZVxyXG5cdFx0aWYgKCBcdCggKHdwYmNfZGF0ZXNfX2RheXNfYmV0d2VlbiggZGF0ZSwgdG9kYXlfZGF0ZSApKSA8IHBhcnNlSW50KF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ2F2YWlsYWJpbGl0eV9fdW5hdmFpbGFibGVfZnJvbV90b2RheScgKSkgKVxyXG5cdFx0XHQgfHwgKFxyXG5cclxuXHRcdFx0XHQgICAoIHBhcnNlSW50KCAnMCcgKyBwYXJzZUludCggX3dwYmMuZ2V0X290aGVyX3BhcmFtKCAnYXZhaWxhYmlsaXR5X19hdmFpbGFibGVfZnJvbV90b2RheScgKSApICkgPiAwIClcclxuXHRcdFx0XHQmJiAoIHdwYmNfZGF0ZXNfX2RheXNfYmV0d2VlbiggZGF0ZSwgdG9kYXlfZGF0ZSApID4gcGFyc2VJbnQoICcwJyArIHBhcnNlSW50KCBfd3BiYy5nZXRfb3RoZXJfcGFyYW0oICdhdmFpbGFiaWxpdHlfX2F2YWlsYWJsZV9mcm9tX3RvZGF5JyApICkgKSApXHJcblx0XHRcdFx0KVxyXG5cdFx0KXtcclxuXHRcdFx0cmV0dXJuIFsgZmFsc2UsIGNzc19kYXRlX19zdGFuZGFyZCArICcgZGF0ZV91c2VyX3VuYXZhaWxhYmxlJyBcdFx0KyAnIGJlZm9yZV9hZnRlcl91bmF2YWlsYWJsZScgXTtcclxuXHRcdH1cclxuXHJcblx0XHQvLyBTRUFTT05TIDo6ICBcdFx0XHRcdFx0Qm9va2luZyA+IFJlc291cmNlcyA+IEF2YWlsYWJpbGl0eSBwYWdlXHJcblx0XHR2YXIgICAgaXNfZGF0ZV9hdmFpbGFibGUgPSBjYWxlbmRhcl9wYXJhbXNfYXJyLnNlYXNvbl9jdXN0b21pemVfcGx1Z2luWyBzcWxfY2xhc3NfZGF5IF07XHJcblx0XHRpZiAoIGZhbHNlID09PSBpc19kYXRlX2F2YWlsYWJsZSApe1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL0ZpeEluOiA5LjUuNC40XHJcblx0XHRcdHJldHVybiBbIGZhbHNlLCBjc3NfZGF0ZV9fc3RhbmRhcmQgKyAnIGRhdGVfdXNlcl91bmF2YWlsYWJsZSdcdFx0KyAnIHNlYXNvbl91bmF2YWlsYWJsZScgXTtcclxuXHRcdH1cclxuXHJcblx0XHQvLyBSRVNPVVJDRV9VTkFWQUlMQUJMRSA6OiAgIFx0Qm9va2luZyA+IEN1c3RvbWl6ZSBwYWdlXHJcblx0XHRpZiAoIHdwYmNfaW5fYXJyYXkoY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlcywgc3FsX2NsYXNzX2RheSApICl7XHJcblx0XHRcdGlzX2RhdGVfYXZhaWxhYmxlID0gZmFsc2U7XHJcblx0XHR9XHJcblx0XHRpZiAoICBmYWxzZSA9PT0gaXNfZGF0ZV9hdmFpbGFibGUgKXtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vRml4SW46IDkuNS40LjRcclxuXHRcdFx0cmV0dXJuIFsgZmFsc2UsIGNzc19kYXRlX19zdGFuZGFyZCArICcgZGF0ZV91c2VyX3VuYXZhaWxhYmxlJ1x0XHQrICcgcmVzb3VyY2VfdW5hdmFpbGFibGUnIF07XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuXHJcblxyXG5cclxuXHRcdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHJcblxyXG5cdFx0Ly8gSXMgYW55IGJvb2tpbmdzIGluIHRoaXMgZGF0ZSA/XHJcblx0XHRpZiAoICd1bmRlZmluZWQnICE9PSB0eXBlb2YoIGNhbGVuZGFyX3BhcmFtc19hcnIuYm9va2VkX2RhdGVzWyBjbGFzc19kYXkgXSApICkge1xyXG5cclxuXHRcdFx0dmFyIGJvb2tpbmdzX2luX2RhdGUgPSBjYWxlbmRhcl9wYXJhbXNfYXJyLmJvb2tlZF9kYXRlc1sgY2xhc3NfZGF5IF07XHJcblxyXG5cclxuXHRcdFx0aWYgKCAndW5kZWZpbmVkJyAhPT0gdHlwZW9mKCBib29raW5nc19pbl9kYXRlWyAnc2VjXzAnIF0gKSApIHtcdFx0XHQvLyBcIkZ1bGwgZGF5XCIgYm9va2luZyAgLT4gKHNlY29uZHMgPT0gMClcclxuXHJcblx0XHRcdFx0Y3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gKCAnMCcgPT09IGJvb2tpbmdzX2luX2RhdGVbICdzZWNfMCcgXS5hcHByb3ZlZCApID8gJyBkYXRlMmFwcHJvdmUgJyA6ICcgZGF0ZV9hcHByb3ZlZCAnO1x0XHRcdFx0Ly8gUGVuZGluZyA9ICcwJyB8ICBBcHByb3ZlZCA9ICcxJ1xyXG5cdFx0XHRcdGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgZnVsbF9kYXlfYm9va2luZyc7XHJcblxyXG5cdFx0XHRcdHJldHVybiBbIGZhbHNlLCBjc3NfZGF0ZV9fc3RhbmRhcmQgKyBjc3NfZGF0ZV9fYWRkaXRpb25hbCBdO1xyXG5cclxuXHRcdFx0fSBlbHNlIGlmICggT2JqZWN0LmtleXMoIGJvb2tpbmdzX2luX2RhdGUgKS5sZW5ndGggPiAwICl7XHRcdFx0XHQvLyBcIlRpbWUgc2xvdHNcIiBCb29raW5nc1xyXG5cclxuXHRcdFx0XHR2YXIgaXNfYXBwcm92ZWQgPSB0cnVlO1xyXG5cclxuXHRcdFx0XHRfLmVhY2goIGJvb2tpbmdzX2luX2RhdGUsIGZ1bmN0aW9uICggcF92YWwsIHBfa2V5LCBwX2RhdGEgKSB7XHJcblx0XHRcdFx0XHRpZiAoICFwYXJzZUludCggcF92YWwuYXBwcm92ZWQgKSApe1xyXG5cdFx0XHRcdFx0XHRpc19hcHByb3ZlZCA9IGZhbHNlO1xyXG5cdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0dmFyIHRzID0gcF92YWwuYm9va2luZ19kYXRlLnN1YnN0cmluZyggcF92YWwuYm9va2luZ19kYXRlLmxlbmd0aCAtIDEgKTtcclxuXHRcdFx0XHRcdGlmICggdHJ1ZSA9PT0gX3dwYmMuZ2V0X290aGVyX3BhcmFtKCAnaXNfZW5hYmxlZF9jaGFuZ2Vfb3ZlcicgKSApe1xyXG5cdFx0XHRcdFx0XHRpZiAoIHRzID09ICcxJyApIHsgY3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gJyBjaGVja19pbl90aW1lJyArICgocGFyc2VJbnQocF92YWwuYXBwcm92ZWQpKSA/ICcgY2hlY2tfaW5fdGltZV9kYXRlX2FwcHJvdmVkJyA6ICcgY2hlY2tfaW5fdGltZV9kYXRlMmFwcHJvdmUnKTsgfVxyXG5cdFx0XHRcdFx0XHRpZiAoIHRzID09ICcyJyApIHsgY3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gJyBjaGVja19vdXRfdGltZScgKyAoKHBhcnNlSW50KHBfdmFsLmFwcHJvdmVkKSkgPyAnIGNoZWNrX291dF90aW1lX2RhdGVfYXBwcm92ZWQnIDogJyBjaGVja19vdXRfdGltZV9kYXRlMmFwcHJvdmUnKTsgfVxyXG5cdFx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHR9KTtcclxuXHJcblx0XHRcdFx0aWYgKCAhIGlzX2FwcHJvdmVkICl7XHJcblx0XHRcdFx0XHRjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAnIGRhdGUyYXBwcm92ZSB0aW1lc3BhcnRseSdcclxuXHRcdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdFx0Y3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gJyBkYXRlX2FwcHJvdmVkIHRpbWVzcGFydGx5J1xyXG5cdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0aWYgKCAhIF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ2lzX2VuYWJsZWRfY2hhbmdlX292ZXInICkgKXtcclxuXHRcdFx0XHRcdGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgdGltZXNfY2xvY2snXHJcblx0XHRcdFx0fVxyXG5cclxuXHRcdFx0fVxyXG5cclxuXHRcdH1cclxuXHJcblx0XHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblxyXG5cdFx0cmV0dXJuIFsgdHJ1ZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgY3NzX2RhdGVfX2FkZGl0aW9uYWwgKyAnIGRhdGVfYXZhaWxhYmxlJyBdO1xyXG5cdH1cclxuXHJcbi8vVE9ETzogbmVlZCB0byAgdXNlIHdwYmNfY2FsZW5kYXIgc2NyaXB0LCAgaW5zdGVhZCBvZiB0aGlzIG9uZVxyXG5cdC8qKlxyXG5cdCAqIEFwcGx5IHNvbWUgQ1NTIGNsYXNzZXMsIHdoZW4gd2UgbW91c2Ugb3ZlciBzcGVjaWZpYyBkYXRlcyBpbiBjYWxlbmRhclxyXG5cdCAqIEBwYXJhbSB2YWx1ZVxyXG5cdCAqIEBwYXJhbSBkYXRlXHRcdFx0XHRcdC0gIEphdmFTY3JpcHQgRGF0ZSBPYmo6ICBcdFx0TW9uIERlYyAxMSAyMDIzIDAwOjAwOjAwIEdNVCswMjAwIChFYXN0ZXJuIEV1cm9wZWFuIFN0YW5kYXJkIFRpbWUpXHJcblx0ICogQHBhcmFtIGNhbGVuZGFyX3BhcmFtc19hcnJcdC0gIENhbGVuZGFyIFNldHRpbmdzIE9iamVjdDogIFx0e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImh0bWxfaWRcIjogXCJjYWxlbmRhcl9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInRleHRfaWRcIjogXCJkYXRlX2Jvb2tpbmc0XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrXCI6IDEsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzXCI6IDEyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInJlc291cmNlX2lkXCI6IDQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYWp4X25vbmNlX2NhbGVuZGFyXCI6IFwiPGlucHV0IHR5cGU9XFxcImhpZGRlblxcXCIgLi4uIC8+XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYm9va2VkX2RhdGVzXCI6IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIxMi0yOC0yMDIyXCI6IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2RhdGVcIjogXCIyMDIyLTEyLTI4IDAwOjAwOjAwXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJhcHByb3ZlZFwiOiBcIjFcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImJvb2tpbmdfaWRcIjogXCIyNlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRdLCAuLi5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4nOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMge2Jvb2xlYW59XHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfaG92ZXIoIHZhbHVlLCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzICl7XHJcblxyXG5cdFx0XHRcdFx0aWYoIG51bGwgPT09IGRhdGUgKXtcclxuXHRcdFx0XHRcdFx0cmV0dXJuO1xyXG5cdFx0XHRcdFx0fVxyXG5cclxuXHJcblxyXG5cdFx0XHRcdFx0Ly8gVGhlIHNhbWUgZnVuY3Rpb25zIGFzIGluIGNsaWVudC5jc3MgKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKlxyXG5cdFx0XHRcdFx0Ly9UT0RPOiAyMDIzLTA2LTMwIDE3OjIyXHJcblx0XHRcdFx0XHRpZiAoIHRydWUgKXtcclxuXHJcblx0XHRcdFx0XHRcdHZhciBia190eXBlID0gY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZFxyXG5cclxuXHJcblxyXG5cdFx0XHRcdFx0XHR2YXIgaXNfY2FsZW5kYXJfYm9va2luZ191bnNlbGVjdGFibGUgPSBqUXVlcnkoICcjY2FsZW5kYXJfYm9va2luZ191bnNlbGVjdGFibGUnICsgYmtfdHlwZSApO1x0XHRcdFx0Ly9GaXhJbjogOC4wLjEuMlxyXG5cdFx0XHRcdFx0XHR2YXIgaXNfYm9va2luZ19mb3JtX2Fsc28gPSBqUXVlcnkoICcjYm9va2luZ19mb3JtX2RpdicgKyBia190eXBlICk7XHJcblx0XHRcdFx0XHRcdC8vIFNldCB1bnNlbGVjdGFibGUsICBpZiBvbmx5IEF2YWlsYWJpbGl0eSBDYWxlbmRhciAgaGVyZSAoYW5kIHdlIGRvIG5vdCBpbnNlcnQgQm9va2luZyBmb3JtIGJ5IG1pc3Rha2UpLlxyXG5cdFx0XHRcdFx0XHRpZiAoIChpc19jYWxlbmRhcl9ib29raW5nX3Vuc2VsZWN0YWJsZS5sZW5ndGggPT0gMSkgJiYgKGlzX2Jvb2tpbmdfZm9ybV9hbHNvLmxlbmd0aCAhPSAxKSApe1xyXG5cdFx0XHRcdFx0XHRcdGpRdWVyeSggJyNjYWxlbmRhcl9ib29raW5nJyArIGJrX3R5cGUgKyAnIC5kYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKS5yZW1vdmVDbGFzcyggJ2RhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyApOyAgICAgICAgLy8gY2xlYXIgYWxsIGhpZ2hsaWdodCBkYXlzIHNlbGVjdGlvbnNcclxuXHRcdFx0XHRcdFx0XHRqUXVlcnkoICcud3BiY19vbmx5X2NhbGVuZGFyICNjYWxlbmRhcl9ib29raW5nJyArIGJrX3R5cGUgKyAnIC5kYXRlcGljay1kYXlzLWNlbGwsICcgK1xyXG5cdFx0XHRcdFx0XHRcdFx0Jy53cGJjX29ubHlfY2FsZW5kYXIgI2NhbGVuZGFyX2Jvb2tpbmcnICsgYmtfdHlwZSArICcgLmRhdGVwaWNrLWRheXMtY2VsbCBhJyApLmNzcyggJ2N1cnNvcicsICdkZWZhdWx0JyApO1xyXG5cdFx0XHRcdFx0XHRcdHJldHVybiBmYWxzZTtcclxuXHRcdFx0XHRcdFx0fVx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vRml4SW46IDguMC4xLjJcdGVuZFxyXG5cclxuXHRcdFx0XHRcdFx0cmV0dXJuIHRydWU7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHQvLyAqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqXHJcblxyXG5cclxuXHJcblxyXG5cclxuXHRcdGlmICggbnVsbCA9PT0gZGF0ZSApe1xyXG5cdFx0XHRqUXVlcnkoICcuZGF0ZXBpY2stZGF5cy1jZWxsLW92ZXInICkucmVtb3ZlQ2xhc3MoICdkYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKTsgICBcdCAgICAgICAgICAgICAgICAgICAgICAgIC8vIGNsZWFyIGFsbCBoaWdobGlnaHQgZGF5cyBzZWxlY3Rpb25zXHJcblx0XHRcdHJldHVybiBmYWxzZTtcclxuXHRcdH1cclxuXHJcblx0XHR2YXIgaW5zdCA9IGpRdWVyeS5kYXRlcGljay5fZ2V0SW5zdCggZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgKSApO1xyXG5cclxuXHRcdGlmIChcclxuXHRcdFx0ICAgKCAxID09IGluc3QuZGF0ZXMubGVuZ3RoKVx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIElmIHdlIGhhdmUgb25lIHNlbGVjdGVkIGRhdGVcclxuXHRcdFx0JiYgKCdkeW5hbWljJyA9PT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSkgXHRcdFx0XHRcdC8vIHdoaWxlIGhhdmUgcmFuZ2UgZGF5cyBzZWxlY3Rpb24gbW9kZVxyXG5cdFx0KXtcclxuXHJcblx0XHRcdHZhciB0ZF9jbGFzcztcclxuXHRcdFx0dmFyIHRkX292ZXJzID0gW107XHJcblx0XHRcdHZhciBpc19jaGVjayA9IHRydWU7XHJcbiAgICAgICAgICAgIHZhciBzZWxjZXRlZF9maXJzdF9kYXkgPSBuZXcgRGF0ZSgpO1xyXG4gICAgICAgICAgICBzZWxjZXRlZF9maXJzdF9kYXkuc2V0RnVsbFllYXIoaW5zdC5kYXRlc1swXS5nZXRGdWxsWWVhcigpLChpbnN0LmRhdGVzWzBdLmdldE1vbnRoKCkpLCAoaW5zdC5kYXRlc1swXS5nZXREYXRlKCkgKSApOyAvL0dldCBmaXJzdCBEYXRlXHJcblxyXG4gICAgICAgICAgICB3aGlsZSggIGlzX2NoZWNrICl7XHJcblxyXG5cdFx0XHRcdHRkX2NsYXNzID0gKHNlbGNldGVkX2ZpcnN0X2RheS5nZXRNb250aCgpICsgMSkgKyAnLScgKyBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RGF0ZSgpICsgJy0nICsgc2VsY2V0ZWRfZmlyc3RfZGF5LmdldEZ1bGxZZWFyKCk7XHJcblxyXG5cdFx0XHRcdHRkX292ZXJzWyB0ZF9vdmVycy5sZW5ndGggXSA9ICcjY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJyAuY2FsNGRhdGUtJyArIHRkX2NsYXNzOyAgICAgICAgICAgICAgLy8gYWRkIHRvIGFycmF5IGZvciBsYXRlciBtYWtlIHNlbGVjdGlvbiBieSBjbGFzc1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChcclxuXHRcdFx0XHRcdCggICggZGF0ZS5nZXRNb250aCgpID09IHNlbGNldGVkX2ZpcnN0X2RheS5nZXRNb250aCgpICkgICYmXHJcbiAgICAgICAgICAgICAgICAgICAgICAgKCBkYXRlLmdldERhdGUoKSA9PSBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RGF0ZSgpICkgICYmXHJcbiAgICAgICAgICAgICAgICAgICAgICAgKCBkYXRlLmdldEZ1bGxZZWFyKCkgPT0gc2VsY2V0ZWRfZmlyc3RfZGF5LmdldEZ1bGxZZWFyKCkgKVxyXG5cdFx0XHRcdFx0KSB8fCAoIHNlbGNldGVkX2ZpcnN0X2RheSA+IGRhdGUgKVxyXG5cdFx0XHRcdCl7XHJcblx0XHRcdFx0XHRpc19jaGVjayA9ICBmYWxzZTtcclxuXHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdHNlbGNldGVkX2ZpcnN0X2RheS5zZXRGdWxsWWVhciggc2VsY2V0ZWRfZmlyc3RfZGF5LmdldEZ1bGxZZWFyKCksIChzZWxjZXRlZF9maXJzdF9kYXkuZ2V0TW9udGgoKSksIChzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RGF0ZSgpICsgMSkgKTtcclxuXHRcdFx0fVxyXG5cclxuXHRcdFx0Ly8gSGlnaGxpZ2h0IERheXNcclxuXHRcdFx0Zm9yICggdmFyIGk9MDsgaSA8IHRkX292ZXJzLmxlbmd0aCA7IGkrKykgeyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBhZGQgY2xhc3MgdG8gYWxsIGVsZW1lbnRzXHJcblx0XHRcdFx0alF1ZXJ5KCB0ZF9vdmVyc1tpXSApLmFkZENsYXNzKCdkYXRlcGljay1kYXlzLWNlbGwtb3ZlcicpO1xyXG5cdFx0XHR9XHJcblx0XHRcdHJldHVybiB0cnVlO1xyXG5cclxuXHRcdH1cclxuXHJcblx0ICAgIHJldHVybiB0cnVlO1xyXG5cdH1cclxuXHJcbi8vVE9ETzogbmVlZCB0byAgdXNlIHdwYmNfY2FsZW5kYXIgc2NyaXB0LCAgaW5zdGVhZCBvZiB0aGlzIG9uZVxyXG5cclxuXHQvKipcclxuXHQgKiBPbiBEQVlzIHNlbGVjdGlvbiBpbiBjYWxlbmRhclxyXG5cdCAqXHJcblx0ICogQHBhcmFtIGRhdGVzX3NlbGVjdGlvblx0XHQtICBzdHJpbmc6XHRcdFx0ICcyMDIzLTAzLTA3IH4gMjAyMy0wMy0wNycgb3IgJzIwMjMtMDQtMTAsIDIwMjMtMDQtMTIsIDIwMjMtMDQtMDIsIDIwMjMtMDQtMDQnXHJcblx0ICogQHBhcmFtIGNhbGVuZGFyX3BhcmFtc19hcnJcdC0gIENhbGVuZGFyIFNldHRpbmdzIE9iamVjdDogIFx0e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImh0bWxfaWRcIjogXCJjYWxlbmRhcl9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInRleHRfaWRcIjogXCJkYXRlX2Jvb2tpbmc0XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrXCI6IDEsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzXCI6IDEyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInJlc291cmNlX2lkXCI6IDQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYWp4X25vbmNlX2NhbGVuZGFyXCI6IFwiPGlucHV0IHR5cGU9XFxcImhpZGRlblxcXCIgLi4uIC8+XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYm9va2VkX2RhdGVzXCI6IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIxMi0yOC0yMDIyXCI6IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2RhdGVcIjogXCIyMDIyLTEyLTI4IDAwOjAwOjAwXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJhcHByb3ZlZFwiOiBcIjFcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImJvb2tpbmdfaWRcIjogXCIyNlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRdLCAuLi5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4nOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMgYm9vbGVhblxyXG5cdCAqL1xyXG5cdGZ1bmN0aW9uIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9kYXlzX3NlbGVjdCggZGF0ZXNfc2VsZWN0aW9uLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzID0gbnVsbCApe1xyXG5cclxuXHJcblx0XHQvLyBUaGUgc2FtZSBmdW5jdGlvbnMgYXMgaW4gY2xpZW50LmNzc1x0XHRcdC8vVE9ETzogMjAyMy0wNi0zMCAxNzoyMlxyXG5cdFx0aWYgKCB0cnVlICl7XHJcblxyXG5cdFx0XHR2YXIgYmtfdHlwZSA9IGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWRcclxuXHRcdFx0dmFyIGRhdGUgPSBkYXRlc19zZWxlY3Rpb247XHJcblxyXG5cdFx0XHQvLyBTZXQgdW5zZWxlY3RhYmxlLCAgaWYgb25seSBBdmFpbGFiaWxpdHkgQ2FsZW5kYXIgIGhlcmUgKGFuZCB3ZSBkbyBub3QgaW5zZXJ0IEJvb2tpbmcgZm9ybSBieSBtaXN0YWtlKS5cclxuXHRcdFx0dmFyIGlzX2NhbGVuZGFyX2Jvb2tpbmdfdW5zZWxlY3RhYmxlID0galF1ZXJ5KCAnI2NhbGVuZGFyX2Jvb2tpbmdfdW5zZWxlY3RhYmxlJyArIGJrX3R5cGUgKTtcdFx0XHRcdC8vRml4SW46IDguMC4xLjJcclxuXHRcdFx0dmFyIGlzX2Jvb2tpbmdfZm9ybV9hbHNvID0galF1ZXJ5KCAnI2Jvb2tpbmdfZm9ybV9kaXYnICsgYmtfdHlwZSApO1xyXG5cclxuXHRcdFx0aWYgKCAoaXNfY2FsZW5kYXJfYm9va2luZ191bnNlbGVjdGFibGUubGVuZ3RoID4gMCkgJiYgKGlzX2Jvb2tpbmdfZm9ybV9hbHNvLmxlbmd0aCA8PSAwKSApe1xyXG5cclxuXHRcdFx0XHR3cGJjX2NhbGVuZGFyX191bnNlbGVjdF9hbGxfZGF0ZXMoIGJrX3R5cGUgKTtcclxuXHRcdFx0XHRqUXVlcnkoICcud3BiY19vbmx5X2NhbGVuZGFyIC5wb3BvdmVyX2NhbGVuZGFyX2hvdmVyJyApLnJlbW92ZSgpOyAgICAgICAgICAgICAgICAgICAgICBcdFx0XHRcdFx0Ly9IaWRlIGFsbCBvcGVuZWQgcG9wb3ZlcnNcclxuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XHJcblx0XHRcdH1cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL0ZpeEluOiA4LjAuMS4yIGVuZFxyXG5cclxuXHRcdFx0alF1ZXJ5KCAnI2RhdGVfYm9va2luZycgKyBia190eXBlICkudmFsKCBkYXRlICk7XHJcblxyXG5cclxuXHJcblxyXG5cdFx0XHRqUXVlcnkoIFwiLmJvb2tpbmdfZm9ybV9kaXZcIiApLnRyaWdnZXIoIFwiZGF0ZV9zZWxlY3RlZFwiLCBbYmtfdHlwZSwgZGF0ZV0gKTtcclxuXHJcblx0XHR9IGVsc2Uge1xyXG5cclxuXHRcdFx0Ly8gRnVuY3Rpb25hbGl0eSAgZnJvbSAgQm9va2luZyA+IEF2YWlsYWJpbGl0eSBwYWdlXHJcblxyXG5cdFx0XHR2YXIgaW5zdCA9IGpRdWVyeS5kYXRlcGljay5fZ2V0SW5zdCggZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgKSApO1xyXG5cclxuXHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1x0Ly8gIFsgXCIyMDIzLTA0LTA5XCIsIFwiMjAyMy0wNC0xMFwiLCBcIjIwMjMtMDQtMTFcIiBdXHJcblxyXG5cdFx0XHRpZiAoIC0xICE9PSBkYXRlc19zZWxlY3Rpb24uaW5kZXhPZiggJ34nICkgKSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFJhbmdlIERheXNcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gd3BiY19nZXRfZGF0ZXNfYXJyX19mcm9tX2RhdGVzX3JhbmdlX2pzKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19zZXBhcmF0b3InIDogJyB+ICcsICAgICAgICAgICAgICAgICAgICAgICAgIC8vICAnIH4gJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnZGF0ZXMnICAgICAgICAgICA6IGRhdGVzX3NlbGVjdGlvbiwgICAgXHRcdCAgIC8vICcyMDIzLTA0LTA0IH4gMjAyMy0wNC0wNydcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHJcblx0XHRcdH0gZWxzZSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBNdWx0aXBsZSBEYXlzXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gd3BiY19nZXRfZGF0ZXNfYXJyX19mcm9tX2RhdGVzX2NvbW1hX3NlcGFyYXRlZF9qcygge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnZGF0ZXNfc2VwYXJhdG9yJyA6ICcsICcsICAgICAgICAgICAgICAgICAgICAgICAgIFx0Ly8gICcsICdcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2RhdGVzJyAgICAgICAgICAgOiBkYXRlc19zZWxlY3Rpb24sICAgIFx0XHRcdC8vICcyMDIzLTA0LTEwLCAyMDIzLTA0LTEyLCAyMDIzLTA0LTAyLCAyMDIzLTA0LTA0J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHR3cGJjX2F2eV9hZnRlcl9kYXlzX3NlbGVjdGlvbl9fc2hvd19oZWxwX2luZm8oe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUnOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnZGF0ZXNfYXJyJyAgICAgICAgICAgICAgICAgICAgOiBkYXRlc19hcnIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19jbGlja19udW0nICAgICAgICAgICAgICA6IGluc3QuZGF0ZXMubGVuZ3RoLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncG9wb3Zlcl9oaW50cydcdFx0XHRcdFx0OiBjYWxlbmRhcl9wYXJhbXNfYXJyLnBvcG92ZXJfaGludHNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHRcdH1cclxuXHJcblx0XHRyZXR1cm4gdHJ1ZTtcclxuXHJcblx0fVxyXG5cclxuXHJcblx0XHQvKipcclxuXHRcdCAqIFNob3cgaGVscCBpbmZvIGF0IHRoZSB0b3AgIHRvb2xiYXIgYWJvdXQgc2VsZWN0ZWQgZGF0ZXMgYW5kIGZ1dHVyZSBhY3Rpb25zXHJcblx0XHQgKlxyXG5cdFx0ICogQHBhcmFtIHBhcmFtc1xyXG5cdFx0ICogXHRcdFx0XHRcdEV4YW1wbGUgMTogIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlOiBcImR5bmFtaWNcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRhdGVzX2FycjogIFsgXCIyMDIzLTA0LTAzXCIgXSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRhdGVzX2NsaWNrX251bTogMVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3BvcG92ZXJfaGludHMnXHRcdFx0XHRcdDogY2FsZW5kYXJfcGFyYW1zX2Fyci5wb3BvdmVyX2hpbnRzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0ICogXHRcdFx0XHRcdEV4YW1wbGUgMjogIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlOiBcImR5bmFtaWNcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfYXJyOiBBcnJheSgxMCkgWyBcIjIwMjMtMDQtMDNcIiwgXCIyMDIzLTA0LTA0XCIsIFwiMjAyMy0wNC0wNVwiLCDigKYgXVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfY2xpY2tfbnVtOiAyXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncG9wb3Zlcl9oaW50cydcdFx0XHRcdFx0OiBjYWxlbmRhcl9wYXJhbXNfYXJyLnBvcG92ZXJfaGludHNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHQgKi9cclxuXHRcdGZ1bmN0aW9uIHdwYmNfYXZ5X2FmdGVyX2RheXNfc2VsZWN0aW9uX19zaG93X2hlbHBfaW5mbyggcGFyYW1zICl7XHJcbi8vIGNvbnNvbGUubG9nKCBwYXJhbXMgKTtcdC8vXHRcdFsgXCIyMDIzLTA0LTA5XCIsIFwiMjAyMy0wNC0xMFwiLCBcIjIwMjMtMDQtMTFcIiBdXHJcblxyXG5cdFx0XHR2YXIgbWVzc2FnZSwgY29sb3I7XHJcblx0XHRcdGlmIChqUXVlcnkoICcjdWlfYnRuX2NzdG1fX3NldF9kYXlzX2N1c3RvbWl6ZV9wbHVnaW5fX2F2YWlsYWJsZScpLmlzKCc6Y2hlY2tlZCcpKXtcclxuXHRcdFx0XHQgbWVzc2FnZSA9IHBhcmFtcy5wb3BvdmVyX2hpbnRzLnRvb2xiYXJfdGV4dF9hdmFpbGFibGU7Ly8nU2V0IGRhdGVzIF9EQVRFU18gYXMgX0hUTUxfIGF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdCBjb2xvciA9ICcjMTFiZTRjJztcclxuXHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRtZXNzYWdlID0gcGFyYW1zLnBvcG92ZXJfaGludHMudG9vbGJhcl90ZXh0X3VuYXZhaWxhYmxlOy8vJ1NldCBkYXRlcyBfREFURVNfIGFzIF9IVE1MXyB1bmF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdGNvbG9yID0gJyNlNDM5MzknO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHRtZXNzYWdlID0gJzxzcGFuPicgKyBtZXNzYWdlICsgJzwvc3Bhbj4nO1xyXG5cclxuXHRcdFx0dmFyIGZpcnN0X2RhdGUgPSBwYXJhbXNbICdkYXRlc19hcnInIF1bIDAgXTtcclxuXHRcdFx0dmFyIGxhc3RfZGF0ZSAgPSAoICdkeW5hbWljJyA9PSBwYXJhbXMuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUgKVxyXG5cdFx0XHRcdFx0XHRcdD8gcGFyYW1zWyAnZGF0ZXNfYXJyJyBdWyAocGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCAtIDEpIF1cclxuXHRcdFx0XHRcdFx0XHQ6ICggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCA+IDEgKSA/IHBhcmFtc1sgJ2RhdGVzX2FycicgXVsgMSBdIDogJyc7XHJcblxyXG5cdFx0XHRmaXJzdF9kYXRlID0galF1ZXJ5LmRhdGVwaWNrLmZvcm1hdERhdGUoICdkZCBNLCB5eScsIG5ldyBEYXRlKCBmaXJzdF9kYXRlICsgJ1QwMDowMDowMCcgKSApO1xyXG5cdFx0XHRsYXN0X2RhdGUgPSBqUXVlcnkuZGF0ZXBpY2suZm9ybWF0RGF0ZSggJ2RkIE0sIHl5JywgIG5ldyBEYXRlKCBsYXN0X2RhdGUgKyAnVDAwOjAwOjAwJyApICk7XHJcblxyXG5cclxuXHRcdFx0aWYgKCAnZHluYW1pYycgPT0gcGFyYW1zLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlICl7XHJcblx0XHRcdFx0aWYgKCAxID09IHBhcmFtcy5kYXRlc19jbGlja19udW0gKXtcclxuXHRcdFx0XHRcdGxhc3RfZGF0ZSA9ICdfX19fX19fX19fXydcclxuXHRcdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdFx0aWYgKCAnZmlyc3RfdGltZScgPT0galF1ZXJ5KCAnLndwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fY29udGFpbmVyJyApLmF0dHIoICd3cGJjX2xvYWRlZCcgKSApe1xyXG5cdFx0XHRcdFx0XHRqUXVlcnkoICcud3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9jb250YWluZXInICkuYXR0ciggJ3dwYmNfbG9hZGVkJywgJ2RvbmUnIClcclxuXHRcdFx0XHRcdFx0d3BiY19ibGlua19lbGVtZW50KCAnLndwYmNfd2lkZ2V0X2NoYW5nZV9jYWxlbmRhcl9za2luJywgMywgMjIwICk7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0fVxyXG5cdFx0XHRcdG1lc3NhZ2UgPSBtZXNzYWdlLnJlcGxhY2UoICdfREFURVNfJywgICAgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLysgJzxkaXY+JyArICdmcm9tJyArICc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3BhbiBjbGFzcz1cIndwYmNfYmlnX2RhdGVcIj4nICsgZmlyc3RfZGF0ZSArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4+JyArICctJyArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4gY2xhc3M9XCJ3cGJjX2JpZ19kYXRlXCI+JyArIGxhc3RfZGF0ZSArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4+JyApO1xyXG5cdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdC8vIGlmICggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCA+IDEgKXtcclxuXHRcdFx0XHQvLyBcdGxhc3RfZGF0ZSA9ICcsICcgKyBsYXN0X2RhdGU7XHJcblx0XHRcdFx0Ly8gXHRsYXN0X2RhdGUgKz0gKCBwYXJhbXNbICdkYXRlc19hcnInIF0ubGVuZ3RoID4gMiApID8gJywgLi4uJyA6ICcnO1xyXG5cdFx0XHRcdC8vIH0gZWxzZSB7XHJcblx0XHRcdFx0Ly8gXHRsYXN0X2RhdGU9Jyc7XHJcblx0XHRcdFx0Ly8gfVxyXG5cdFx0XHRcdHZhciBkYXRlc19hcnIgPSBbXTtcclxuXHRcdFx0XHRmb3IoIHZhciBpID0gMDsgaSA8IHBhcmFtc1sgJ2RhdGVzX2FycicgXS5sZW5ndGg7IGkrKyApe1xyXG5cdFx0XHRcdFx0ZGF0ZXNfYXJyLnB1c2goICBqUXVlcnkuZGF0ZXBpY2suZm9ybWF0RGF0ZSggJ2RkIE0geXknLCAgbmV3IERhdGUoIHBhcmFtc1sgJ2RhdGVzX2FycicgXVsgaSBdICsgJ1QwMDowMDowMCcgKSApICApO1xyXG5cdFx0XHRcdH1cclxuXHRcdFx0XHRmaXJzdF9kYXRlID0gZGF0ZXNfYXJyLmpvaW4oICcsICcgKTtcclxuXHRcdFx0XHRtZXNzYWdlID0gbWVzc2FnZS5yZXBsYWNlKCAnX0RBVEVTXycsICAgICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4gY2xhc3M9XCJ3cGJjX2JpZ19kYXRlXCI+JyArIGZpcnN0X2RhdGUgKyAnPC9zcGFuPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgJzxzcGFuPicgKTtcclxuXHRcdFx0fVxyXG5cdFx0XHRtZXNzYWdlID0gbWVzc2FnZS5yZXBsYWNlKCAnX0hUTUxfJyAsICc8L3NwYW4+PHNwYW4gY2xhc3M9XCJ3cGJjX2JpZ190ZXh0XCIgc3R5bGU9XCJjb2xvcjonK2NvbG9yKyc7XCI+JykgKyAnPHNwYW4+JztcclxuXHJcblx0XHRcdC8vbWVzc2FnZSArPSAnIDxkaXYgc3R5bGU9XCJtYXJnaW4tbGVmdDogMWVtO1wiPicgKyAnIENsaWNrIG9uIEFwcGx5IGJ1dHRvbiB0byBhcHBseSBjdXN0b21pemVfcGx1Z2luLicgKyAnPC9kaXY+JztcclxuXHJcblx0XHRcdG1lc3NhZ2UgPSAnPGRpdiBjbGFzcz1cIndwYmNfdG9vbGJhcl9kYXRlc19oaW50c1wiPicgKyBtZXNzYWdlICsgJzwvZGl2Pic7XHJcblxyXG5cdFx0XHRqUXVlcnkoICcud3BiY19oZWxwX3RleHQnICkuaHRtbChcdG1lc3NhZ2UgKTtcclxuXHRcdH1cclxuXHJcblx0LyoqXHJcblx0ICogICBQYXJzZSBkYXRlcyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuXHRcdC8qKlxyXG5cdFx0ICogR2V0IGRhdGVzIGFycmF5LCAgZnJvbSBjb21tYSBzZXBhcmF0ZWQgZGF0ZXNcclxuXHRcdCAqXHJcblx0XHQgKiBAcGFyYW0gcGFyYW1zICAgICAgID0ge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiAnZGF0ZXNfc2VwYXJhdG9yJyA9PiAnLCAnLCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBEYXRlcyBzZXBhcmF0b3JcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogJ2RhdGVzJyAgICAgICAgICAgPT4gJzIwMjMtMDQtMDQsIDIwMjMtMDQtMDcsIDIwMjMtMDQtMDUnICAgICAgICAgLy8gRGF0ZXMgaW4gJ1ktbS1kJyBmb3JtYXQ6ICcyMDIzLTAxLTMxJ1xyXG5cdFx0XHRcdFx0XHRcdFx0IH1cclxuXHRcdCAqXHJcblx0XHQgKiBAcmV0dXJuIGFycmF5ICAgICAgPSBbXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFswXSA9PiAyMDIzLTA0LTA0XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFsxXSA9PiAyMDIzLTA0LTA1XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFsyXSA9PiAyMDIzLTA0LTA2XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFszXSA9PiAyMDIzLTA0LTA3XHJcblx0XHRcdFx0XHRcdFx0XHRdXHJcblx0XHQgKlxyXG5cdFx0ICogRXhhbXBsZSAjMTogIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19jb21tYV9zZXBhcmF0ZWRfanMoICB7ICAnZGF0ZXNfc2VwYXJhdG9yJyA6ICcsICcsICdkYXRlcycgOiAnMjAyMy0wNC0wNCwgMjAyMy0wNC0wNywgMjAyMy0wNC0wNScgIH0gICk7XHJcblx0XHQgKi9cclxuXHRcdGZ1bmN0aW9uIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19jb21tYV9zZXBhcmF0ZWRfanMoIHBhcmFtcyApe1xyXG5cclxuXHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1xyXG5cclxuXHRcdFx0aWYgKCAnJyAhPT0gcGFyYW1zWyAnZGF0ZXMnIF0gKXtcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gcGFyYW1zWyAnZGF0ZXMnIF0uc3BsaXQoIHBhcmFtc1sgJ2RhdGVzX3NlcGFyYXRvcicgXSApO1xyXG5cclxuXHRcdFx0XHRkYXRlc19hcnIuc29ydCgpO1xyXG5cdFx0XHR9XHJcblx0XHRcdHJldHVybiBkYXRlc19hcnI7XHJcblx0XHR9XHJcblxyXG5cdFx0LyoqXHJcblx0XHQgKiBHZXQgZGF0ZXMgYXJyYXksICBmcm9tIHJhbmdlIGRheXMgc2VsZWN0aW9uXHJcblx0XHQgKlxyXG5cdFx0ICogQHBhcmFtIHBhcmFtcyAgICAgICA9ICB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqICdkYXRlc19zZXBhcmF0b3InID0+ICcgfiAnLCAgICAgICAgICAgICAgICAgICAgICAgICAvLyBEYXRlcyBzZXBhcmF0b3JcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogJ2RhdGVzJyAgICAgICAgICAgPT4gJzIwMjMtMDQtMDQgfiAyMDIzLTA0LTA3JyAgICAgIC8vIERhdGVzIGluICdZLW0tZCcgZm9ybWF0OiAnMjAyMy0wMS0zMSdcclxuXHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0ICpcclxuXHRcdCAqIEByZXR1cm4gYXJyYXkgICAgICAgID0gW1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMF0gPT4gMjAyMy0wNC0wNFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMV0gPT4gMjAyMy0wNC0wNVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMl0gPT4gMjAyMy0wNC0wNlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbM10gPT4gMjAyMy0wNC0wN1xyXG5cdFx0XHRcdFx0XHRcdFx0ICBdXHJcblx0XHQgKlxyXG5cdFx0ICogRXhhbXBsZSAjMTogIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19yYW5nZV9qcyggIHsgICdkYXRlc19zZXBhcmF0b3InIDogJyB+ICcsICdkYXRlcycgOiAnMjAyMy0wNC0wNCB+IDIwMjMtMDQtMDcnICB9ICApO1xyXG5cdFx0ICogRXhhbXBsZSAjMjogIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19yYW5nZV9qcyggIHsgICdkYXRlc19zZXBhcmF0b3InIDogJyAtICcsICdkYXRlcycgOiAnMjAyMy0wNC0wNCAtIDIwMjMtMDQtMDcnICB9ICApO1xyXG5cdFx0ICovXHJcblx0XHRmdW5jdGlvbiB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoIHBhcmFtcyApe1xyXG5cclxuXHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1xyXG5cclxuXHRcdFx0aWYgKCAnJyAhPT0gcGFyYW1zWydkYXRlcyddICkge1xyXG5cclxuXHRcdFx0XHRkYXRlc19hcnIgPSBwYXJhbXNbICdkYXRlcycgXS5zcGxpdCggcGFyYW1zWyAnZGF0ZXNfc2VwYXJhdG9yJyBdICk7XHJcblx0XHRcdFx0dmFyIGNoZWNrX2luX2RhdGVfeW1kICA9IGRhdGVzX2FyclswXTtcclxuXHRcdFx0XHR2YXIgY2hlY2tfb3V0X2RhdGVfeW1kID0gZGF0ZXNfYXJyWzFdO1xyXG5cclxuXHRcdFx0XHRpZiAoICgnJyAhPT0gY2hlY2tfaW5fZGF0ZV95bWQpICYmICgnJyAhPT0gY2hlY2tfb3V0X2RhdGVfeW1kKSApe1xyXG5cclxuXHRcdFx0XHRcdGRhdGVzX2FyciA9IHdwYmNfZ2V0X2RhdGVzX2FycmF5X2Zyb21fc3RhcnRfZW5kX2RheXNfanMoIGNoZWNrX2luX2RhdGVfeW1kLCBjaGVja19vdXRfZGF0ZV95bWQgKTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdH1cclxuXHRcdFx0cmV0dXJuIGRhdGVzX2FycjtcclxuXHRcdH1cclxuXHJcblx0XHRcdC8qKlxyXG5cdFx0XHQgKiBHZXQgZGF0ZXMgYXJyYXkgYmFzZWQgb24gc3RhcnQgYW5kIGVuZCBkYXRlcy5cclxuXHRcdFx0ICpcclxuXHRcdFx0ICogQHBhcmFtIHN0cmluZyBzU3RhcnREYXRlIC0gc3RhcnQgZGF0ZTogMjAyMy0wNC0wOVxyXG5cdFx0XHQgKiBAcGFyYW0gc3RyaW5nIHNFbmREYXRlICAgLSBlbmQgZGF0ZTogICAyMDIzLTA0LTExXHJcblx0XHRcdCAqIEByZXR1cm4gYXJyYXkgICAgICAgICAgICAgLSBbIFwiMjAyMy0wNC0wOVwiLCBcIjIwMjMtMDQtMTBcIiwgXCIyMDIzLTA0LTExXCIgXVxyXG5cdFx0XHQgKi9cclxuXHRcdFx0ZnVuY3Rpb24gd3BiY19nZXRfZGF0ZXNfYXJyYXlfZnJvbV9zdGFydF9lbmRfZGF5c19qcyggc1N0YXJ0RGF0ZSwgc0VuZERhdGUgKXtcclxuXHJcblx0XHRcdFx0c1N0YXJ0RGF0ZSA9IG5ldyBEYXRlKCBzU3RhcnREYXRlICsgJ1QwMDowMDowMCcgKTtcclxuXHRcdFx0XHRzRW5kRGF0ZSA9IG5ldyBEYXRlKCBzRW5kRGF0ZSArICdUMDA6MDA6MDAnICk7XHJcblxyXG5cdFx0XHRcdHZhciBhRGF5cz1bXTtcclxuXHJcblx0XHRcdFx0Ly8gU3RhcnQgdGhlIHZhcmlhYmxlIG9mZiB3aXRoIHRoZSBzdGFydCBkYXRlXHJcblx0XHRcdFx0YURheXMucHVzaCggc1N0YXJ0RGF0ZS5nZXRUaW1lKCkgKTtcclxuXHJcblx0XHRcdFx0Ly8gU2V0IGEgJ3RlbXAnIHZhcmlhYmxlLCBzQ3VycmVudERhdGUsIHdpdGggdGhlIHN0YXJ0IGRhdGUgLSBiZWZvcmUgYmVnaW5uaW5nIHRoZSBsb29wXHJcblx0XHRcdFx0dmFyIHNDdXJyZW50RGF0ZSA9IG5ldyBEYXRlKCBzU3RhcnREYXRlLmdldFRpbWUoKSApO1xyXG5cdFx0XHRcdHZhciBvbmVfZGF5X2R1cmF0aW9uID0gMjQqNjAqNjAqMTAwMDtcclxuXHJcblx0XHRcdFx0Ly8gV2hpbGUgdGhlIGN1cnJlbnQgZGF0ZSBpcyBsZXNzIHRoYW4gdGhlIGVuZCBkYXRlXHJcblx0XHRcdFx0d2hpbGUoc0N1cnJlbnREYXRlIDwgc0VuZERhdGUpe1xyXG5cdFx0XHRcdFx0Ly8gQWRkIGEgZGF5IHRvIHRoZSBjdXJyZW50IGRhdGUgXCIrMSBkYXlcIlxyXG5cdFx0XHRcdFx0c0N1cnJlbnREYXRlLnNldFRpbWUoIHNDdXJyZW50RGF0ZS5nZXRUaW1lKCkgKyBvbmVfZGF5X2R1cmF0aW9uICk7XHJcblxyXG5cdFx0XHRcdFx0Ly8gQWRkIHRoaXMgbmV3IGRheSB0byB0aGUgYURheXMgYXJyYXlcclxuXHRcdFx0XHRcdGFEYXlzLnB1c2goIHNDdXJyZW50RGF0ZS5nZXRUaW1lKCkgKTtcclxuXHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdGZvciAobGV0IGkgPSAwOyBpIDwgYURheXMubGVuZ3RoOyBpKyspIHtcclxuXHRcdFx0XHRcdGFEYXlzWyBpIF0gPSBuZXcgRGF0ZSggYURheXNbaV0gKTtcclxuXHRcdFx0XHRcdGFEYXlzWyBpIF0gPSBhRGF5c1sgaSBdLmdldEZ1bGxZZWFyKClcclxuXHRcdFx0XHRcdFx0XHRcdCsgJy0nICsgKCggKGFEYXlzWyBpIF0uZ2V0TW9udGgoKSArIDEpIDwgMTApID8gJzAnIDogJycpICsgKGFEYXlzWyBpIF0uZ2V0TW9udGgoKSArIDEpXHJcblx0XHRcdFx0XHRcdFx0XHQrICctJyArICgoICAgICAgICBhRGF5c1sgaSBdLmdldERhdGUoKSA8IDEwKSA/ICcwJyA6ICcnKSArICBhRGF5c1sgaSBdLmdldERhdGUoKTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdFx0Ly8gT25jZSB0aGUgbG9vcCBoYXMgZmluaXNoZWQsIHJldHVybiB0aGUgYXJyYXkgb2YgZGF5cy5cclxuXHRcdFx0XHRyZXR1cm4gYURheXM7XHJcblx0XHRcdH1cclxuXHJcblxyXG4vKipcclxuICogU2Nyb2xsIHRvICBzcGVjaWZpYyBcIlllYXIgJiBNb250aFwiIFx0aW4gSW5saW5lIEJvb2tpbmcgQ2FsZW5kYXJcclxuICpcclxuICogQHBhcmFtIHtudW1iZXJ9IHJlc291cmNlX2lkXHRcdDFcclxuICogQHBhcmFtIHtudW1iZXJ9IHllYXJcdFx0XHRcdDIwMjNcclxuICogQHBhcmFtIHtudW1iZXJ9IG1vbnRoXHRcdFx0MTJcdFx0XHQoZnJvbSAxIHRvICAxMilcclxuICpcclxuICogQHJldHVybnMge2Jvb2xlYW59XHRcdFx0Ly8gY2hhbmdlZCBvciBub3RcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19jaGFuZ2VfeWVhcl9tb250aCggcmVzb3VyY2VfaWQsIHllYXIsIG1vbnRoICl7XHJcblxyXG5cdHZhciBpbnN0ID0galF1ZXJ5LmRhdGVwaWNrLl9nZXRJbnN0KCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ2NhbGVuZGFyX2Jvb2tpbmcnICsgcmVzb3VyY2VfaWQpICk7XHJcblxyXG5cdGlmICggZmFsc2UgIT0gaW5zdCApe1xyXG5cclxuXHRcdHllYXIgPSBwYXJzZUludCggeWVhciApO1xyXG5cdFx0bW9udGggPSBwYXJzZUludCggbW9udGggKSAtIDE7XHJcblxyXG5cdFx0aW5zdC5jdXJzb3JEYXRlID0gbmV3IERhdGUoKTtcclxuXHRcdGluc3QuY3Vyc29yRGF0ZS5zZXRGdWxsWWVhciggeWVhciwgbW9udGgsIDEgKTtcclxuXHRcdGluc3QuY3Vyc29yRGF0ZS5zZXRNb250aCggbW9udGggKTtcdFx0XHRcdFx0XHQvLyBJbiBzb21lIGNhc2VzLCAgdGhlIHNldEZ1bGxZZWFyIGNhbiAgc2V0ICBvbmx5IFllYXIsICBhbmQgbm90IHRoZSBNb250aCBhbmQgZGF5ICAgICAgLy9GaXhJbjo2LjIuMy41XHJcblx0XHRpbnN0LmN1cnNvckRhdGUuc2V0RGF0ZSggMSApO1xyXG5cclxuXHRcdGluc3QuZHJhd01vbnRoID0gaW5zdC5jdXJzb3JEYXRlLmdldE1vbnRoKCk7XHJcblx0XHRpbnN0LmRyYXdZZWFyICA9IGluc3QuY3Vyc29yRGF0ZS5nZXRGdWxsWWVhcigpO1xyXG5cclxuXHRcdGpRdWVyeS5kYXRlcGljay5fbm90aWZ5Q2hhbmdlKCBpbnN0ICk7XHJcblx0XHRqUXVlcnkuZGF0ZXBpY2suX2FkanVzdEluc3REYXRlKCBpbnN0ICk7XHJcblx0XHRqUXVlcnkuZGF0ZXBpY2suX3Nob3dEYXRlKCBpbnN0ICk7XHJcblx0XHRqUXVlcnkuZGF0ZXBpY2suX3VwZGF0ZURhdGVwaWNrKCBpbnN0ICk7XHJcblxyXG5cdFx0cmV0dXJuICB0cnVlO1xyXG5cdH1cclxuXHRyZXR1cm4gIGZhbHNlO1xyXG59Il0sIm1hcHBpbmdzIjoiQUFBQSxZQUFZOztBQUVaO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBU0Esa0NBQWtDQSxDQUFFQyxtQkFBbUIsRUFBRTtFQUNsRTtBQUFBOztBQUlBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTQyxpQ0FBaUNBLENBQUVELG1CQUFtQixFQUFFO0VBRWhFLElBQ00sQ0FBQyxLQUFLRSxNQUFNLENBQUUsR0FBRyxHQUFHRixtQkFBbUIsQ0FBQ0csT0FBUSxDQUFDLENBQUNDLE1BQU0sQ0FBUztFQUFBLEdBQ2pFLElBQUksS0FBS0YsTUFBTSxDQUFFLEdBQUcsR0FBR0YsbUJBQW1CLENBQUNHLE9BQVEsQ0FBQyxDQUFDRSxRQUFRLENBQUUsYUFBYyxDQUFHLENBQUM7RUFBQSxFQUN0RjtJQUNFLE9BQU8sS0FBSztFQUNmOztFQUVBO0VBQ0E7RUFDQTtFQUNBTixrQ0FBa0MsQ0FBRUMsbUJBQW9CLENBQUM7O0VBR3pEO0VBQ0E7RUFDQTtFQUNBRSxNQUFNLENBQUUsR0FBRyxHQUFHRixtQkFBbUIsQ0FBQ0csT0FBUSxDQUFDLENBQUNHLElBQUksQ0FBRSxFQUFHLENBQUM7RUFDdERKLE1BQU0sQ0FBRSxHQUFHLEdBQUdGLG1CQUFtQixDQUFDRyxPQUFRLENBQUMsQ0FBQ0ksUUFBUSxDQUFDO0lBQ2pEQyxhQUFhLEVBQUcsU0FBQUEsY0FBV0MsSUFBSSxFQUFFO01BQzVCLE9BQU9DLGdE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
