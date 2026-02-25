"use strict";

/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
var wpbc_ajx_availability = function (obj, $) {
  // Secure parameters for Ajax	------------------------------------------------------------------------------------
  var p_secure = obj.security_obj = obj.security_obj || {
    user_id: 0,
    nonce: '',
    locale: ''
  };
  obj.set_secure_param = function (param_key, param_val) {
    p_secure[param_key] = param_val;
  };
  obj.get_secure_param = function (param_key) {
    return p_secure[param_key];
  };

  // Listing Search parameters	------------------------------------------------------------------------------------
  var p_listing = obj.search_request_obj = obj.search_request_obj || {
    // sort            : "booking_id",
    // sort_type       : "DESC",
    // page_num        : 1,
    // page_items_count: 10,
    // create_date     : "",
    // keyword         : "",
    // source          : ""
  };
  obj.search_set_all_params = function (request_param_obj) {
    p_listing = request_param_obj;
  };
  obj.search_get_all_params = function () {
    return p_listing;
  };
  obj.search_get_param = function (param_key) {
    return p_listing[param_key];
  };
  obj.search_set_param = function (param_key, param_val) {
    // if ( Array.isArray( param_val ) ){
    // 	param_val = JSON.stringify( param_val );
    // }
    p_listing[param_key] = param_val;
  };
  obj.search_set_params_arr = function (params_arr) {
    _.each(params_arr, function (p_val, p_key, p_data) {
      // Define different Search  parameters for request
      this.search_set_param(p_key, p_val);
    });
  };

  // Other parameters 			------------------------------------------------------------------------------------
  var p_other = obj.other_obj = obj.other_obj || {};
  obj.set_other_param = function (param_key, param_val) {
    p_other[param_key] = param_val;
  };
  obj.get_other_param = function (param_key) {
    return p_other[param_key];
  };
  return obj;
}(wpbc_ajx_availability || {}, jQuery);
var wpbc_ajx_bookings = [];

/**
 *   Show Content  ---------------------------------------------------------------------------------------------- */

/**
 * Show Content - Calendar and UI elements
 *
 * @param ajx_data_arr
 * @param ajx_search_params
 * @param ajx_cleaned_params
 */
function wpbc_ajx_availability__page_content__show(ajx_data_arr, ajx_search_params, ajx_cleaned_params) {
  var template__availability_main_page_content = wp.template('wpbc_ajx_availability_main_page_content');

  // Content
  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).html(template__availability_main_page_content({
    'ajx_data': ajx_data_arr,
    'ajx_search_params': ajx_search_params,
    // $_REQUEST[ 'search_params' ]
    'ajx_cleaned_params': ajx_cleaned_params
  }));
  jQuery('.wpbc_processing.wpbc_spin').parent().parent().parent().parent('[id^="wpbc_notice_"]').hide();
  // Load calendar
  wpbc_ajx_availability__calendar__show({
    'resource_id': ajx_cleaned_params.resource_id,
    'ajx_nonce_calendar': ajx_data_arr.ajx_nonce_calendar,
    'ajx_data_arr': ajx_data_arr,
    'ajx_cleaned_params': ajx_cleaned_params
  });

  /**
   * Trigger for dates selection in the booking form
   *
   * jQuery( wpbc_ajx_availability.get_other_param( 'listing_container' ) ).on('wpbc_page_content_loaded', function(event, ajx_data_arr, ajx_search_params , ajx_cleaned_params) { ... } );
   */
  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).trigger('wpbc_page_content_loaded', [ajx_data_arr, ajx_search_params, ajx_cleaned_params]);
}

/**
 * Show inline month view calendar              with all predefined CSS (sizes and check in/out,  times containers)
 * @param {obj} calendar_params_arr
			{
				'resource_id'       	: ajx_cleaned_params.resource_id,
				'ajx_nonce_calendar'	: ajx_data_arr.ajx_nonce_calendar,
				'ajx_data_arr'          : ajx_data_arr = { ajx_booking_resources:[], booked_dates: {}, resource_unavailable_dates:[], season_availability:{},.... }
				'ajx_cleaned_params'    : {
											calendar__days_selection_mode: "dynamic"
											calendar__start_week_day: "0"
											calendar__timeslot_day_bg_as_available: ""
											calendar__view__cell_height: ""
											calendar__view__months_in_row: 4
											calendar__view__visible_months: 12
											calendar__view__width: "100%"

											dates_availability: "unavailable"
											dates_selection: "2023-03-14 ~ 2023-03-16"
											do_action: "set_availability"
											resource_id: 1
											ui_clicked_element_id: "wpbc_availability_apply_btn"
											ui_usr__availability_selected_toolbar: "info"
								  		 }
			}
*/
function wpbc_ajx_availability__calendar__show(calendar_params_arr) {
  // Update nonce
  jQuery('#ajx_nonce_calendar_section').html(calendar_params_arr.ajx_nonce_calendar);

  //------------------------------------------------------------------------------------------------------------------
  // Update bookings
  if ('undefined' == typeof wpbc_ajx_bookings[calendar_params_arr.resource_id]) {
    wpbc_ajx_bookings[calendar_params_arr.resource_id] = [];
  }
  wpbc_ajx_bookings[calendar_params_arr.resource_id] = calendar_params_arr['ajx_data_arr']['booked_dates'];

  //------------------------------------------------------------------------------------------------------------------
  /**
   * Define showing mouse over tooltip on unavailable dates
   * It's defined, when calendar REFRESHED (change months or days selection) loaded in jquery.datepick.wpbc.9.0.js :
   * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_refresh', ...		//FixIn: 9.4.4.13
   */
  jQuery('body').on('wpbc_datepick_inline_calendar_refresh', function (event, resource_id, inst) {
    // inst.dpDiv  it's:  <div class="datepick-inline datepick-multi" style="width: 17712px;">....</div>
    inst.dpDiv.find('.season_unavailable,.before_after_unavailable,.weekdays_unavailable').on('mouseover', function (this_event) {
      // also available these vars: 	resource_id, jCalContainer, inst
      var jCell = jQuery(this_event.currentTarget);
      wpbc_avy__show_tooltip__for_element(jCell, calendar_params_arr['ajx_data_arr']['popover_hints']);
    });
  });

  //------------------------------------------------------------------------------------------------------------------
  /**
   * Define height of the calendar  cells, 	and  mouse over tooltips at  some unavailable dates
   * It's defined, when calendar loaded in jquery.datepick.wpbc.9.0.js :
   * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_loaded', ...		//FixIn: 9.4.4.12
   */
  jQuery('body').on('wpbc_datepick_inline_calendar_loaded', function (event, resource_id, jCalContainer, inst) {
    // Remove highlight day for today  date
    jQuery('.datepick-days-cell.datepick-today.datepick-days-cell-over').removeClass('datepick-days-cell-over');

    // Set height of calendar  cells if defined this option
    if ('' !== calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height) {
      jQuery('head').append('<style type="text/css">' + '.hasDatepick .datepick-inline .datepick-title-row th, ' + '.hasDatepick .datepick-inline .datepick-days-cell {' + 'height: ' + calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height + ' !important;' + '}' + '</style>');
    }

    // Define showing mouse over tooltip on unavailable dates
    jCalContainer.find('.season_unavailable,.before_after_unavailable,.weekdays_unavailable').on('mouseover', function (this_event) {
      // also available these vars: 	resource_id, jCalContainer, inst
      var jCell = jQuery(this_event.currentTarget);
      wpbc_avy__show_tooltip__for_element(jCell, calendar_params_arr['ajx_data_arr']['popover_hints']);
    });
  });

  //------------------------------------------------------------------------------------------------------------------
  // Define width of entire calendar
  var width = 'width:' + calendar_params_arr.ajx_cleaned_params.calendar__view__width + ';'; // var width = 'width:100%;max-width:100%;';

  if (undefined != calendar_params_arr.ajx_cleaned_params.calendar__view__max_width && '' != calendar_params_arr.ajx_cleaned_params.calendar__view__max_width) {
    width += 'max-width:' + calendar_params_arr.ajx_cleaned_params.calendar__view__max_width + ';';
  } else {
    width += 'max-width:' + calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row * 341 + 'px;';
  }

  //------------------------------------------------------------------------------------------------------------------
  // Add calendar container: "Calendar is loading..."  and textarea
  jQuery('.wpbc_ajx_avy__calendar').html('<div class="' + ' bk_calendar_frame' + ' months_num_in_row_' + calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row + ' cal_month_num_' + calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months + ' ' + calendar_params_arr.ajx_cleaned_params.calendar__timeslot_day_bg_as_available // 'wpbc_timeslot_day_bg_as_available' || ''
  + '" ' + 'style="' + width + '">' + '<div id="calendar_booking' + calendar_params_arr.resource_id + '">' + 'Calendar is loading...' + '</div>' + '</div>' + '<textarea      id="date_booking' + calendar_params_arr.resource_id + '"' + ' name="date_booking' + calendar_params_arr.resource_id + '"' + ' autocomplete="off"' + ' style="display:none;width:100%;height:10em;margin:2em 0 0;"></textarea>');

  //------------------------------------------------------------------------------------------------------------------
  var cal_param_arr = {
    'html_id': 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
    'text_id': 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
    'calendar__start_week_day': calendar_params_arr.ajx_cleaned_params.calendar__start_week_day,
    'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
    'calendar__days_selection_mode': calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,
    'resource_id': calendar_params_arr.ajx_cleaned_params.resource_id,
    'ajx_nonce_calendar': calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
    'booked_dates': calendar_params_arr.ajx_data_arr.booked_dates,
    'season_availability': calendar_params_arr.ajx_data_arr.season_availability,
    'resource_unavailable_dates': calendar_params_arr.ajx_data_arr.resource_unavailable_dates,
    'popover_hints': calendar_params_arr['ajx_data_arr']['popover_hints'] // {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
  };
  wpbc_show_inline_booking_calendar(cal_param_arr);

  //------------------------------------------------------------------------------------------------------------------
  /**
   * On click AVAILABLE |  UNAVAILABLE button  in widget	-	need to  change help dates text
   */
  jQuery('.wpbc_radio__set_days_availability').on('change', function (event, resource_id, inst) {
    wpbc__inline_booking_calendar__on_days_select(jQuery('#' + cal_param_arr.text_id).val(), cal_param_arr);
  });

  // Show 	'Select days  in calendar then select Available  /  Unavailable status and click Apply availability button.'
  jQuery('#wpbc_toolbar_dates_hint').html('<div class="ui_element"><span class="wpbc_ui_control wpbc_ui_addon wpbc_help_text" >' + cal_param_arr.popover_hints.toolbar_text + '</span></div>');
}

/**
 * 	Load Datepick Inline calendar
 *
 * @param calendar_params_arr		example:{
											'html_id'           : 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
											'text_id'           : 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,

											'calendar__start_week_day': 	  calendar_params_arr.ajx_cleaned_params.calendar__start_week_day,
											'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
											'calendar__days_selection_mode':  calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,

											'resource_id'        : calendar_params_arr.ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar' : calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
											'booked_dates'       : calendar_params_arr.ajx_data_arr.booked_dates,
											'season_availability': calendar_params_arr.ajx_data_arr.season_availability,

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
  // Configure and show calendar
  jQuery('#' + calendar_params_arr.html_id).text('');
  jQuery('#' + calendar_params_arr.html_id).datepick({
    beforeShowDay: function beforeShowDay(date) {
      return wpbc__inline_booking_calendar__apply_css_to_days(date, calendar_params_arr, this);
    },
    onSelect: function onSelect(date) {
      jQuery('#' + calendar_params_arr.text_id).val(date);
      //wpbc_blink_element('.wpbc_widget_available_unavailable', 3, 220);
      return wpbc__inline_booking_calendar__on_days_select(date, calendar_params_arr, this);
    },
    onHover: function onHover(value, date) {
      //wpbc_avy__prepare_tooltip__in_calendar( value, date, calendar_params_arr, this );

      return wpbc__inline_booking_calendar__on_days_hover(value, date, calendar_params_arr, this);
    },
    onChangeMonthYear: null,
    showOn: 'both',
    numberOfMonths: calendar_params_arr.calendar__view__visible_months,
    stepMonths: 1,
    prevText: '&laquo;',
    nextText: '&raquo;',
    dateFormat: 'yy-mm-dd',
    // 'dd.mm.yy',
    changeMonth: false,
    changeYear: false,
    minDate: 0,
    //null,  //Scroll as long as you need
    maxDate: '10y',
    // minDate: new Date(2020, 2, 1), maxDate: new Date(2020, 9, 31), 	// Ability to set any  start and end date in calendar
    showStatus: false,
    closeAtTop: false,
    firstDay: calendar_params_arr.calendar__start_week_day,
    gotoCurrent: false,
    hideIfNoPrevNext: true,
    multiSeparator: ', ',
    multiSelect: 'dynamic' == calendar_params_arr.calendar__days_selection_mode ? 0 : 365,
    // Maximum number of selectable dates:	 Single day = 0,  multi days = 365
    rangeSelect: 'dynamic' == calendar_params_arr.calendar__days_selection_mode,
    rangeSeparator: ' ~ ',
    //' - ',
    // showWeeks: true,
    useThemeRoller: false
  });
  return true;
}

/**
 * Apply CSS to calendar date cells
 *
 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																  "html_id": "calendar_booking4",
																  "text_id": "date_booking4",
																  "calendar__start_week_day": 1,
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
																	'season_availability':{
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
      return [!!false, css_date__standard + ' date_user_unavailable' + ' weekdays_unavailable'];
    }
  }

  // BEFORE_AFTER :: Set unavailable days Before / After the Today date
  if (wpbc_dates__days_between(date, today_date) < parseInt(_wpbc.get_other_param('availability__unavailable_from_today')) || parseInt('0' + parseInt(_wpbc.get_other_param('availability__available_from_today'))) > 0 && wpbc_dates__days_between(date, today_date) > parseInt('0' + parseInt(_wpbc.get_other_param('availability__available_from_today')))) {
    return [!!false, css_date__standard + ' date_user_unavailable' + ' before_after_unavailable'];
  }

  // SEASONS ::  					Booking > Resources > Availability page
  var is_date_available = calendar_params_arr.season_availability[sql_class_day];
  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [!!false, css_date__standard + ' date_user_unavailable' + ' season_unavailable'];
  }

  // RESOURCE_UNAVAILABLE ::   	Booking > Availability page
  if (wpbc_in_array(calendar_params_arr.resource_unavailable_dates, sql_class_day)) {
    is_date_available = false;
  }
  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [!false, css_date__standard + ' date_user_unavailable' + ' resource_unavailable'];
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
      return [!false, css_date__standard + css_date__additional];
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

/**
 * Apply some CSS classes, when we mouse over specific dates in calendar
 * @param value
 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																  "html_id": "calendar_booking4",
																  "text_id": "date_booking4",
																  "calendar__start_week_day": 1,
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
																	'season_availability':{
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

/**
 * On DAYs selection in calendar
 *
 * @param dates_selection		-  string:			 '2023-03-07 ~ 2023-03-07' or '2023-04-10, 2023-04-12, 2023-04-02, 2023-04-04'
 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																  "html_id": "calendar_booking4",
																  "text_id": "date_booking4",
																  "calendar__start_week_day": 1,
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
																	'season_availability':{
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
  if (jQuery('#ui_btn_avy__set_days_availability__available').is(':checked')) {
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
      if ('first_time' == jQuery('.wpbc_ajx_availability_container').attr('wpbc_loaded')) {
        jQuery('.wpbc_ajx_availability_container').attr('wpbc_loaded', 'done');
        wpbc_blink_element('.wpbc_widget_available_unavailable', 3, 220);
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

  //message += ' <div style="margin-left: 1em;">' + ' Click on Apply button to apply availability.' + '</div>';

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
 *   Tooltips  ---------------------------------------------------------------------------------------------- */

/**
 * Define showing tooltip,  when  mouse over on  SELECTABLE (available, pending, approved, resource unavailable),  days
 * Can be called directly  from  datepick init function.
 *
 * @param value
 * @param date
 * @param calendar_params_arr
 * @param datepick_this
 * @returns {boolean}
 */
function wpbc_avy__prepare_tooltip__in_calendar(value, date, calendar_params_arr, datepick_this) {
  if (null == date) {
    return false;
  }
  var td_class = date.getMonth() + 1 + '-' + date.getDate() + '-' + date.getFullYear();
  var jCell = jQuery('#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class);
  wpbc_avy__show_tooltip__for_element(jCell, calendar_params_arr['popover_hints']);
  return true;
}

/**
 * Define tooltip  for showing on UNAVAILABLE days (season, weekday, today_depends unavailable)
 *
 * @param jCell					jQuery of specific day cell
 * @param popover_hints		    Array with tooltip hint texts	 : {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
 */
function wpbc_avy__show_tooltip__for_element(jCell, popover_hints) {
  var tooltip_time = '';
  if (jCell.hasClass('season_unavailable')) {
    tooltip_time = popover_hints['season_unavailable'];
  } else if (jCell.hasClass('weekdays_unavailable')) {
    tooltip_time = popover_hints['weekdays_unavailable'];
  } else if (jCell.hasClass('before_after_unavailable')) {
    tooltip_time = popover_hints['before_after_unavailable'];
  } else if (jCell.hasClass('date2approve')) {} else if (jCell.hasClass('date_approved')) {} else {}
  jCell.attr('data-content', tooltip_time);
  var td_el = jCell.get(0); //jQuery( '#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class ).get(0);

  if (undefined == td_el._tippy && '' != tooltip_time) {
    wpbc_tippy(td_el, {
      content: function content(reference) {
        var popover_content = reference.getAttribute('data-content');
        return '<div class="popover popover_tippy">' + '<div class="popover-content">' + popover_content + '</div>' + '</div>';
      },
      allowHTML: true,
      trigger: 'mouseenter focus',
      interactive: !true,
      hideOnClick: true,
      interactiveBorder: 10,
      maxWidth: 550,
      theme: 'wpbc-tippy-times',
      placement: 'top',
      delay: [400, 0],
      //FixIn: 9.4.2.2
      ignoreAttributes: true,
      touch: true,
      //['hold', 500], // 500ms delay			//FixIn: 9.2.1.5
      appendTo: function appendTo() {
        return document.body;
      }
    });
  }
}

/**
 *   Ajax  ------------------------------------------------------------------------------------------------------ */

/**
 * Send Ajax show request
 */
function wpbc_ajx_availability__ajax_request() {
  console.groupCollapsed('WPBC_AJX_AVAILABILITY');
  console.log(' == Before Ajax Send - search_get_all_params() == ', wpbc_ajx_availability.search_get_all_params());
  wpbc_availability_reload_button__spin_start();

  // Start Ajax
  jQuery.post(wpbc_url_ajax, {
    action: 'WPBC_AJX_AVAILABILITY',
    wpbc_ajx_user_id: wpbc_ajx_availability.get_secure_param('user_id'),
    nonce: wpbc_ajx_availability.get_secure_param('nonce'),
    wpbc_ajx_locale: wpbc_ajx_availability.get_secure_param('locale'),
    search_params: wpbc_ajx_availability.search_get_all_params()
  },
  /**
   * S u c c e s s
   *
   * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
   * @param textStatus		-	'success'
   * @param jqXHR				-	Object
   */
  function (response_data, textStatus, jqXHR) {
    console.log(' == Response WPBC_AJX_AVAILABILITY == ', response_data);
    console.groupEnd();

    // Probably Error
    if (_typeof(response_data) !== 'object' || response_data === null) {
      wpbc_ajx_availability__show_message(response_data);
      return;
    }

    // Reload page, after filter toolbar has been reset
    if (undefined != response_data['ajx_cleaned_params'] && 'reset_done' === response_data['ajx_cleaned_params']['do_action']) {
      location.reload();
      return;
    }

    // Show listing
    wpbc_ajx_availability__page_content__show(response_data['ajx_data'], response_data['ajx_search_params'], response_data['ajx_cleaned_params']);

    //wpbc_ajx_availability__define_ui_hooks();						// Redefine Hooks, because we show new DOM elements
    if ('' != response_data['ajx_data']['ajx_after_action_message'].replace(/\n/g, "<br />")) {
      wpbc_admin_show_message(response_data['ajx_data']['ajx_after_action_message'].replace(/\n/g, "<br />"), '1' == response_data['ajx_data']['ajx_after_action_result'] ? 'success' : 'error', 10000);
    }
    wpbc_availability_reload_button__spin_pause();
    // Remove spin icon from  button and Enable this button.
    wpbc_button__remove_spin(response_data['ajx_cleaned_params']['ui_clicked_element_id']);
    jQuery('#ajax_respond').html(response_data); // For ability to show response, add such DIV element to page
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (window.console && window.console.log) {
      console.log('Ajax_Error', jqXHR, textStatus, errorThrown);
    }
    var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown;
    if (jqXHR.status) {
      error_message += ' (<b>' + jqXHR.status + '</b>)';
      if (403 == jqXHR.status) {
        error_message += ' Probably nonce for this page has been expired. Please <a href="javascript:void(0)" onclick="javascript:location.reload();">reload the page</a>.';
      }
    }
    if (jqXHR.responseText) {
      error_message += ' ' + jqXHR.responseText;
    }
    error_message = error_message.replace(/\n/g, "<br />");
    wpbc_ajx_availability__show_message(error_message);
  })
  // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
  ; // End Ajax
}

/**
 *   H o o k s  -  its Action/Times when need to re-Render Views  ----------------------------------------------- */

/**
 * Send Ajax Search Request after Updating search request parameters
 *
 * @param params_arr
 */
function wpbc_ajx_availability__send_request_with_params(params_arr) {
  // Define different Search  parameters for request
  _.each(params_arr, function (p_val, p_key, p_data) {
    //console.log( 'Request for: ', p_key, p_val );
    wpbc_ajx_availability.search_set_param(p_key, p_val);
  });

  // Send Ajax Request
  wpbc_ajx_availability__ajax_request();
}

/**
 * Search request for "Page Number"
 * @param page_number	int
 */
function wpbc_ajx_availability__pagination_click(page_number) {
  wpbc_ajx_availability__send_request_with_params({
    'page_num': page_number
  });
}

/**
 *   Show / Hide Content  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Content 	- 	Sending Ajax Request	-	with parameters that  we early  defined
 */
function wpbc_ajx_availability__actual_content__show() {
  wpbc_ajx_availability__ajax_request(); // Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}

/**
 * Hide Listing Content
 */
function wpbc_ajx_availability__actual_content__hide() {
  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).html('');
}

/**
 *   M e s s a g e  --------------------------------------------------------------------------------------------- */

/**
 * Show just message instead of content
 */
function wpbc_ajx_availability__show_message(message) {
  wpbc_ajx_availability__actual_content__hide();
  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + message + '</div>');
}

/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */
function wpbc_availability_reload_button__spin_start() {
  jQuery('#wpbc_availability_reload_button .menu_icon.wpbc_spin').removeClass('wpbc_animation_pause');
}

/**
 * Spin button in Filter toolbar  -  Pause
 */
function wpbc_availability_reload_button__spin_pause() {
  jQuery('#wpbc_availability_reload_button .menu_icon.wpbc_spin').addClass('wpbc_animation_pause');
}

/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */
function wpbc_availability_reload_button__is_spin() {
  if (jQuery('#wpbc_availability_reload_button .menu_icon.wpbc_spin').hasClass('wpbc_animation_pause')) {
    return true;
  } else {
    return false;
  }
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaW5jbHVkZXMvcGFnZS1hdmFpbGFiaWxpdHkvX291dC9hdmFpbGFiaWxpdHlfcGFnZS5qcyIsIm5hbWVzIjpbIl90eXBlb2YiLCJvYmoiLCJTeW1ib2wiLCJpdGVyYXRvciIsImNvbnN0cnVjdG9yIiwicHJvdG90eXBlIiwid3BiY19hanhfYXZhaWxhYmlsaXR5IiwiJCIsInBfc2VjdXJlIiwic2VjdXJpdHlfb2JqIiwidXNlcl9pZCIsIm5vbmNlIiwibG9jYWxlIiwic2V0X3NlY3VyZV9wYXJhbSIsInBhcmFtX2tleSIsInBhcmFtX3ZhbCIsImdldF9zZWN1cmVfcGFyYW0iLCJwX2xpc3RpbmciLCJzZWFyY2hfcmVxdWVzdF9vYmoiLCJzZWFyY2hfc2V0X2FsbF9wYXJhbXMiLCJyZXF1ZXN0X3BhcmFtX29iaiIsInNlYXJjaF9nZXRfYWxsX3BhcmFtcyIsInNlYXJjaF9nZXRfcGFyYW0iLCJzZWFyY2hfc2V0X3BhcmFtIiwic2VhcmNoX3NldF9wYXJhbXNfYXJyIiwicGFyYW1zX2FyciIsIl8iLCJlYWNoIiwicF92YWwiLCJwX2tleSIsInBfZGF0YSIsInBfb3RoZXIiLCJvdGhlcl9vYmoiLCJzZXRfb3RoZXJfcGFyYW0iLCJnZXRfb3RoZXJfcGFyYW0iLCJqUXVlcnkiLCJ3cGJjX2FqeF9ib29raW5ncyIsIndwYmNfYWp4X2F2YWlsYWJpbGl0eV9fcGFnZV9jb250ZW50X19zaG93IiwiYWp4X2RhdGFfYXJyIiwiYWp4X3NlYXJjaF9wYXJhbXMiLCJhanhfY2xlYW5lZF9wYXJhbXMiLCJ0ZW1wbGF0ZV9fYXZhaWxhYmlsaXR5X21haW5fcGFnZV9jb250ZW50Iiwid3AiLCJ0ZW1wbGF0ZSIsImh0bWwiLCJwYXJlbnQiLCJoaWRlIiwid3BiY19hanhfYXZhaWxhYmlsaXR5X19jYWxlbmRhcl9fc2hvdyIsInJlc291cmNlX2lkIiwiYWp4X25vbmNlX2NhbGVuZGFyIiwidHJpZ2dlciIsImNhbGVuZGFyX3BhcmFtc19hcnIiLCJvbiIsImV2ZW50IiwiaW5zdCIsImRwRGl2IiwiZmluZCIsInRoaXNfZXZlbnQiLCJqQ2VsbCIsImN1cnJlbnRUYXJnZXQiLCJ3cGJjX2F2eV9fc2hvd190b29sdGlwX19mb3JfZWxlbWVudCIsImpDYWxDb250YWluZXIiLCJyZW1vdmVDbGFzcyIsImNhbGVuZGFyX192aWV3X19jZWxsX2hlaWdodCIsImFwcGVuZCIsIndpZHRoIiwiY2FsZW5kYXJfX3ZpZXdfX3dpZHRoIiwidW5kZWZpbmVkIiwiY2FsZW5kYXJfX3ZpZXdfX21heF93aWR0aCIsImNhbGVuZGFyX192aWV3X19tb250aHNfaW5fcm93IiwiY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzIiwiY2FsZW5kYXJfX3RpbWVzbG90X2RheV9iZ19hc19hdmFpbGFibGUiLCJjYWxfcGFyYW1fYXJyIiwiY2FsZW5kYXJfX3N0YXJ0X3dlZWtfZGF5IiwiY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUiLCJib29rZWRfZGF0ZXMiLCJzZWFzb25fYXZhaWxhYmlsaXR5IiwicmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXMiLCJ3cGJjX3Nob3dfaW5saW5lX2Jvb2tpbmdfY2FsZW5kYXIiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19zZWxlY3QiLCJ0ZXh0X2lkIiwidmFsIiwicG9wb3Zlcl9oaW50cyIsInRvb2xiYXJfdGV4dCIsImh0bWxfaWQiLCJsZW5ndGgiLCJoYXNDbGFzcyIsInRleHQiLCJkYXRlcGljayIsImJlZm9yZVNob3dEYXkiLCJkYXRlIiwid3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2FwcGx5X2Nzc190b19kYXlzIiwib25TZWxlY3QiLCJvbkhvdmVyIiwidmFsdWUiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19ob3ZlciIsIm9uQ2hhbmdlTW9udGhZZWFyIiwic2hvd09uIiwibnVtYmVyT2ZNb250aHMiLCJzdGVwTW9udGhzIiwicHJldlRleHQiLCJuZXh0VGV4dCIsImRhdGVGb3JtYXQiLCJjaGFuZ2VNb250aCIsImNoYW5nZVllYXIiLCJtaW5EYXRlIiwibWF4RGF0ZSIsInNob3dTdGF0dXMiLCJjbG9zZUF0VG9wIiwiZmlyc3REYXkiLCJnb3RvQ3VycmVudCIsImhpZGVJZk5vUHJldk5leHQiLCJtdWx0aVNlcGFyYXRvciIsIm11bHRpU2VsZWN0IiwicmFuZ2VTZWxlY3QiLCJyYW5nZVNlcGFyYXRvciIsInVzZVRoZW1lUm9sbGVyIiwiZGF0ZXBpY2tfdGhpcyIsInRvZGF5X2RhdGUiLCJEYXRlIiwiX3dwYmMiLCJwYXJzZUludCIsImNsYXNzX2RheSIsImdldE1vbnRoIiwiZ2V0RGF0ZSIsImdldEZ1bGxZZWFyIiwic3FsX2NsYXNzX2RheSIsIndwYmNfX2dldF9fc3FsX2NsYXNzX2RhdGUiLCJjc3NfZGF0ZV9fc3RhbmRhcmQiLCJjc3NfZGF0ZV9fYWRkaXRpb25hbCIsImdldERheSIsImkiLCJ3cGJjX2RhdGVzX19kYXlzX2JldHdlZW4iLCJpc19kYXRlX2F2YWlsYWJsZSIsIndwYmNfaW5fYXJyYXkiLCJib29raW5nc19pbl9kYXRlIiwiYXBwcm92ZWQiLCJPYmplY3QiLCJrZXlzIiwiaXNfYXBwcm92ZWQiLCJ0cyIsImJvb2tpbmdfZGF0ZSIsInN1YnN0cmluZyIsIl9nZXRJbnN0IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImRhdGVzIiwidGRfY2xhc3MiLCJ0ZF9vdmVycyIsImlzX2NoZWNrIiwic2VsY2V0ZWRfZmlyc3RfZGF5Iiwic2V0RnVsbFllYXIiLCJhZGRDbGFzcyIsImRhdGVzX3NlbGVjdGlvbiIsImFyZ3VtZW50cyIsImRhdGVzX2FyciIsImluZGV4T2YiLCJ3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMiLCJ3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfY29tbWFfc2VwYXJhdGVkX2pzIiwid3BiY19hdnlfYWZ0ZXJfZGF5c19zZWxlY3Rpb25fX3Nob3dfaGVscF9pbmZvIiwicGFyYW1zIiwibWVzc2FnZSIsImNvbG9yIiwiaXMiLCJ0b29sYmFyX3RleHRfYXZhaWxhYmxlIiwidG9vbGJhcl90ZXh0X3VuYXZhaWxhYmxlIiwiZmlyc3RfZGF0ZSIsImxhc3RfZGF0ZSIsImZvcm1hdERhdGUiLCJkYXRlc19jbGlja19udW0iLCJhdHRyIiwid3BiY19ibGlua19lbGVtZW50IiwicmVwbGFjZSIsInB1c2giLCJqb2luIiwic3BsaXQiLCJzb3J0IiwiY2hlY2tfaW5fZGF0ZV95bWQiLCJjaGVja19vdXRfZGF0ZV95bWQiLCJ3cGJjX2dldF9kYXRlc19hcnJheV9mcm9tX3N0YXJ0X2VuZF9kYXlzX2pzIiwic1N0YXJ0RGF0ZSIsInNFbmREYXRlIiwiYURheXMiLCJnZXRUaW1lIiwic0N1cnJlbnREYXRlIiwib25lX2RheV9kdXJhdGlvbiIsInNldFRpbWUiLCJ3cGJjX2F2eV9fcHJlcGFyZV90b29sdGlwX19pbl9jYWxlbmRhciIsInRvb2x0aXBfdGltZSIsInRkX2VsIiwiZ2V0IiwiX3RpcHB5Iiwid3BiY190aXBweSIsImNvbnRlbnQiLCJyZWZlcmVuY2UiLCJwb3BvdmVyX2NvbnRlbnQiLCJnZXRBdHRyaWJ1dGUiLCJhbGxvd0hUTUwiLCJpbnRlcmFjdGl2ZSIsImhpZGVPbkNsaWNrIiwiaW50ZXJhY3RpdmVCb3JkZXIiLCJtYXhXaWR0aCIsInRoZW1lIiwicGxhY2VtZW50IiwiZGVsYXkiLCJpZ25vcmVBdHRyaWJ1dGVzIiwidG91Y2giLCJhcHBlbmRUbyIsImJvZHkiLCJ3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FqYXhfcmVxdWVzdCIsImNvbnNvbGUiLCJncm91cENvbGxhcHNlZCIsImxvZyIsIndwYmNfYXZhaWxhYmlsaXR5X3JlbG9hZF9idXR0b25fX3NwaW5fc3RhcnQiLCJwb3N0Iiwid3BiY191cmxfYWpheCIsImFjdGlvbiIsIndwYmNfYWp4X3VzZXJfaWQiLCJ3cGJjX2FqeF9sb2NhbGUiLCJzZWFyY2hfcGFyYW1zIiwicmVzcG9uc2VfZGF0YSIsInRleHRTdGF0dXMiLCJqcVhIUiIsImdyb3VwRW5kIiwid3BiY19hanhfYXZhaWxhYmlsaXR5X19zaG93X21lc3NhZ2UiLCJsb2NhdGlvbiIsInJlbG9hZCIsIndwYmNfYWRtaW5fc2hvd19tZXNzYWdlIiwid3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSIsIndwYmNfYnV0dG9uX19yZW1vdmVfc3BpbiIsImZhaWwiLCJlcnJvclRocm93biIsIndpbmRvdyIsImVycm9yX21lc3NhZ2UiLCJzdGF0dXMiLCJyZXNwb25zZVRleHQiLCJ3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX3NlbmRfcmVxdWVzdF93aXRoX3BhcmFtcyIsIndwYmNfYWp4X2F2YWlsYWJpbGl0eV9fcGFnaW5hdGlvbl9jbGljayIsInBhZ2VfbnVtYmVyIiwid3BiY19hanhfYXZhaWxhYmlsaXR5X19hY3R1YWxfY29udGVudF9fc2hvdyIsIndwYmNfYWp4X2F2YWlsYWJpbGl0eV9fYWN0dWFsX2NvbnRlbnRfX2hpZGUiLCJ3cGJjX2F2YWlsYWJpbGl0eV9yZWxvYWRfYnV0dG9uX19pc19zcGluIl0sInNvdXJjZXMiOlsiaW5jbHVkZXMvcGFnZS1hdmFpbGFiaWxpdHkvX3NyYy9hdmFpbGFiaWxpdHlfcGFnZS5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJcInVzZSBzdHJpY3RcIjtcclxuXHJcbi8qKlxyXG4gKiBSZXF1ZXN0IE9iamVjdFxyXG4gKiBIZXJlIHdlIGNhbiAgZGVmaW5lIFNlYXJjaCBwYXJhbWV0ZXJzIGFuZCBVcGRhdGUgaXQgbGF0ZXIsICB3aGVuICBzb21lIHBhcmFtZXRlciB3YXMgY2hhbmdlZFxyXG4gKlxyXG4gKi9cclxuXHJcbnZhciB3cGJjX2FqeF9hdmFpbGFiaWxpdHkgPSAoZnVuY3Rpb24gKCBvYmosICQpIHtcclxuXHJcblx0Ly8gU2VjdXJlIHBhcmFtZXRlcnMgZm9yIEFqYXhcdC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBwX3NlY3VyZSA9IG9iai5zZWN1cml0eV9vYmogPSBvYmouc2VjdXJpdHlfb2JqIHx8IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0dXNlcl9pZDogMCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0bm9uY2UgIDogJycsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGxvY2FsZSA6ICcnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH07XHJcblxyXG5cdG9iai5zZXRfc2VjdXJlX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXksIHBhcmFtX3ZhbCApIHtcclxuXHRcdHBfc2VjdXJlWyBwYXJhbV9rZXkgXSA9IHBhcmFtX3ZhbDtcclxuXHR9O1xyXG5cclxuXHRvYmouZ2V0X3NlY3VyZV9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5ICkge1xyXG5cdFx0cmV0dXJuIHBfc2VjdXJlWyBwYXJhbV9rZXkgXTtcclxuXHR9O1xyXG5cclxuXHJcblx0Ly8gTGlzdGluZyBTZWFyY2ggcGFyYW1ldGVyc1x0LS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHBfbGlzdGluZyA9IG9iai5zZWFyY2hfcmVxdWVzdF9vYmogPSBvYmouc2VhcmNoX3JlcXVlc3Rfb2JqIHx8IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gc29ydCAgICAgICAgICAgIDogXCJib29raW5nX2lkXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIHNvcnRfdHlwZSAgICAgICA6IFwiREVTQ1wiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBwYWdlX251bSAgICAgICAgOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBwYWdlX2l0ZW1zX2NvdW50OiAxMCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gY3JlYXRlX2RhdGUgICAgIDogXCJcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8ga2V5d29yZCAgICAgICAgIDogXCJcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gc291cmNlICAgICAgICAgIDogXCJcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9zZXRfYWxsX3BhcmFtcyA9IGZ1bmN0aW9uICggcmVxdWVzdF9wYXJhbV9vYmogKSB7XHJcblx0XHRwX2xpc3RpbmcgPSByZXF1ZXN0X3BhcmFtX29iajtcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX2dldF9hbGxfcGFyYW1zID0gZnVuY3Rpb24gKCkge1xyXG5cdFx0cmV0dXJuIHBfbGlzdGluZztcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX2dldF9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5ICkge1xyXG5cdFx0cmV0dXJuIHBfbGlzdGluZ1sgcGFyYW1fa2V5IF07XHJcblx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9zZXRfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSwgcGFyYW1fdmFsICkge1xyXG5cdFx0Ly8gaWYgKCBBcnJheS5pc0FycmF5KCBwYXJhbV92YWwgKSApe1xyXG5cdFx0Ly8gXHRwYXJhbV92YWwgPSBKU09OLnN0cmluZ2lmeSggcGFyYW1fdmFsICk7XHJcblx0XHQvLyB9XHJcblx0XHRwX2xpc3RpbmdbIHBhcmFtX2tleSBdID0gcGFyYW1fdmFsO1xyXG5cdH07XHJcblxyXG5cdG9iai5zZWFyY2hfc2V0X3BhcmFtc19hcnIgPSBmdW5jdGlvbiggcGFyYW1zX2FyciApe1xyXG5cdFx0Xy5lYWNoKCBwYXJhbXNfYXJyLCBmdW5jdGlvbiAoIHBfdmFsLCBwX2tleSwgcF9kYXRhICl7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gRGVmaW5lIGRpZmZlcmVudCBTZWFyY2ggIHBhcmFtZXRlcnMgZm9yIHJlcXVlc3RcclxuXHRcdFx0dGhpcy5zZWFyY2hfc2V0X3BhcmFtKCBwX2tleSwgcF92YWwgKTtcclxuXHRcdH0gKTtcclxuXHR9XHJcblxyXG5cclxuXHQvLyBPdGhlciBwYXJhbWV0ZXJzIFx0XHRcdC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBwX290aGVyID0gb2JqLm90aGVyX29iaiA9IG9iai5vdGhlcl9vYmogfHwgeyB9O1xyXG5cclxuXHRvYmouc2V0X290aGVyX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXksIHBhcmFtX3ZhbCApIHtcclxuXHRcdHBfb3RoZXJbIHBhcmFtX2tleSBdID0gcGFyYW1fdmFsO1xyXG5cdH07XHJcblxyXG5cdG9iai5nZXRfb3RoZXJfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSApIHtcclxuXHRcdHJldHVybiBwX290aGVyWyBwYXJhbV9rZXkgXTtcclxuXHR9O1xyXG5cclxuXHJcblx0cmV0dXJuIG9iajtcclxufSggd3BiY19hanhfYXZhaWxhYmlsaXR5IHx8IHt9LCBqUXVlcnkgKSk7XHJcblxyXG52YXIgd3BiY19hanhfYm9va2luZ3MgPSBbXTtcclxuXHJcbi8qKlxyXG4gKiAgIFNob3cgQ29udGVudCAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIFNob3cgQ29udGVudCAtIENhbGVuZGFyIGFuZCBVSSBlbGVtZW50c1xyXG4gKlxyXG4gKiBAcGFyYW0gYWp4X2RhdGFfYXJyXHJcbiAqIEBwYXJhbSBhanhfc2VhcmNoX3BhcmFtc1xyXG4gKiBAcGFyYW0gYWp4X2NsZWFuZWRfcGFyYW1zXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX3BhZ2VfY29udGVudF9fc2hvdyggYWp4X2RhdGFfYXJyLCBhanhfc2VhcmNoX3BhcmFtcyAsIGFqeF9jbGVhbmVkX3BhcmFtcyApe1xyXG5cclxuXHR2YXIgdGVtcGxhdGVfX2F2YWlsYWJpbGl0eV9tYWluX3BhZ2VfY29udGVudCA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfYXZhaWxhYmlsaXR5X21haW5fcGFnZV9jb250ZW50JyApO1xyXG5cclxuXHQvLyBDb250ZW50XHJcblx0alF1ZXJ5KCB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICkgKS5odG1sKCB0ZW1wbGF0ZV9fYXZhaWxhYmlsaXR5X21haW5fcGFnZV9jb250ZW50KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9kYXRhJyAgICAgICAgICAgICAgOiBhanhfZGF0YV9hcnIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9zZWFyY2hfcGFyYW1zJyAgICAgOiBhanhfc2VhcmNoX3BhcmFtcyxcdFx0XHRcdFx0XHRcdFx0Ly8gJF9SRVFVRVNUWyAnc2VhcmNoX3BhcmFtcycgXVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfY2xlYW5lZF9wYXJhbXMnICAgIDogYWp4X2NsZWFuZWRfcGFyYW1zXHJcblx0XHRcdFx0XHRcdFx0XHRcdH0gKSApO1xyXG5cclxuXHRqUXVlcnkoICcud3BiY19wcm9jZXNzaW5nLndwYmNfc3BpbicpLnBhcmVudCgpLnBhcmVudCgpLnBhcmVudCgpLnBhcmVudCggJ1tpZF49XCJ3cGJjX25vdGljZV9cIl0nICkuaGlkZSgpO1xyXG5cdC8vIExvYWQgY2FsZW5kYXJcclxuXHR3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2NhbGVuZGFyX19zaG93KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncmVzb3VyY2VfaWQnICAgICAgIDogYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9ub25jZV9jYWxlbmRhcic6IGFqeF9kYXRhX2Fyci5hanhfbm9uY2VfY2FsZW5kYXIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X2RhdGFfYXJyJyAgICAgICAgICA6IGFqeF9kYXRhX2FycixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfY2xlYW5lZF9wYXJhbXMnICAgIDogYWp4X2NsZWFuZWRfcGFyYW1zXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cclxuXHJcblx0LyoqXHJcblx0ICogVHJpZ2dlciBmb3IgZGF0ZXMgc2VsZWN0aW9uIGluIHRoZSBib29raW5nIGZvcm1cclxuXHQgKlxyXG5cdCAqIGpRdWVyeSggd3BiY19hanhfYXZhaWxhYmlsaXR5LmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkub24oJ3dwYmNfcGFnZV9jb250ZW50X2xvYWRlZCcsIGZ1bmN0aW9uKGV2ZW50LCBhanhfZGF0YV9hcnIsIGFqeF9zZWFyY2hfcGFyYW1zICwgYWp4X2NsZWFuZWRfcGFyYW1zKSB7IC4uLiB9ICk7XHJcblx0ICovXHJcblx0alF1ZXJ5KCB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICkgKS50cmlnZ2VyKCAnd3BiY19wYWdlX2NvbnRlbnRfbG9hZGVkJywgWyBhanhfZGF0YV9hcnIsIGFqeF9zZWFyY2hfcGFyYW1zICwgYWp4X2NsZWFuZWRfcGFyYW1zIF0gKTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiBTaG93IGlubGluZSBtb250aCB2aWV3IGNhbGVuZGFyICAgICAgICAgICAgICB3aXRoIGFsbCBwcmVkZWZpbmVkIENTUyAoc2l6ZXMgYW5kIGNoZWNrIGluL291dCwgIHRpbWVzIGNvbnRhaW5lcnMpXHJcbiAqIEBwYXJhbSB7b2JqfSBjYWxlbmRhcl9wYXJhbXNfYXJyXHJcblx0XHRcdHtcclxuXHRcdFx0XHQncmVzb3VyY2VfaWQnICAgICAgIFx0OiBhanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0J2FqeF9ub25jZV9jYWxlbmRhcidcdDogYWp4X2RhdGFfYXJyLmFqeF9ub25jZV9jYWxlbmRhcixcclxuXHRcdFx0XHQnYWp4X2RhdGFfYXJyJyAgICAgICAgICA6IGFqeF9kYXRhX2FyciA9IHsgYWp4X2Jvb2tpbmdfcmVzb3VyY2VzOltdLCBib29rZWRfZGF0ZXM6IHt9LCByZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlczpbXSwgc2Vhc29uX2F2YWlsYWJpbGl0eTp7fSwuLi4uIH1cclxuXHRcdFx0XHQnYWp4X2NsZWFuZWRfcGFyYW1zJyAgICA6IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlOiBcImR5bmFtaWNcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX3N0YXJ0X3dlZWtfZGF5OiBcIjBcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX3RpbWVzbG90X2RheV9iZ19hc19hdmFpbGFibGU6IFwiXCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX192aWV3X19jZWxsX2hlaWdodDogXCJcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX3ZpZXdfX21vbnRoc19pbl9yb3c6IDRcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRoczogMTJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX192aWV3X193aWR0aDogXCIxMDAlXCJcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRkYXRlc19hdmFpbGFiaWxpdHk6IFwidW5hdmFpbGFibGVcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfc2VsZWN0aW9uOiBcIjIwMjMtMDMtMTQgfiAyMDIzLTAzLTE2XCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRvX2FjdGlvbjogXCJzZXRfYXZhaWxhYmlsaXR5XCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdHJlc291cmNlX2lkOiAxXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHR1aV9jbGlja2VkX2VsZW1lbnRfaWQ6IFwid3BiY19hdmFpbGFiaWxpdHlfYXBwbHlfYnRuXCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdHVpX3Vzcl9fYXZhaWxhYmlsaXR5X3NlbGVjdGVkX3Rvb2xiYXI6IFwiaW5mb1wiXHJcblx0XHRcdFx0XHRcdFx0XHQgIFx0XHQgfVxyXG5cdFx0XHR9XHJcbiovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fY2FsZW5kYXJfX3Nob3coIGNhbGVuZGFyX3BhcmFtc19hcnIgKXtcclxuXHJcblx0Ly8gVXBkYXRlIG5vbmNlXHJcblx0alF1ZXJ5KCAnI2FqeF9ub25jZV9jYWxlbmRhcl9zZWN0aW9uJyApLmh0bWwoIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X25vbmNlX2NhbGVuZGFyICk7XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gVXBkYXRlIGJvb2tpbmdzXHJcblx0aWYgKCAndW5kZWZpbmVkJyA9PSB0eXBlb2YgKHdwYmNfYWp4X2Jvb2tpbmdzWyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkIF0pICl7IHdwYmNfYWp4X2Jvb2tpbmdzWyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkIF0gPSBbXTsgfVxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdzWyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkIF0gPSBjYWxlbmRhcl9wYXJhbXNfYXJyWyAnYWp4X2RhdGFfYXJyJyBdWyAnYm9va2VkX2RhdGVzJyBdO1xyXG5cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvKipcclxuXHQgKiBEZWZpbmUgc2hvd2luZyBtb3VzZSBvdmVyIHRvb2x0aXAgb24gdW5hdmFpbGFibGUgZGF0ZXNcclxuXHQgKiBJdCdzIGRlZmluZWQsIHdoZW4gY2FsZW5kYXIgUkVGUkVTSEVEIChjaGFuZ2UgbW9udGhzIG9yIGRheXMgc2VsZWN0aW9uKSBsb2FkZWQgaW4ganF1ZXJ5LmRhdGVwaWNrLndwYmMuOS4wLmpzIDpcclxuXHQgKiBcdFx0JCggJ2JvZHknICkudHJpZ2dlciggJ3dwYmNfZGF0ZXBpY2tfaW5saW5lX2NhbGVuZGFyX3JlZnJlc2gnLCAuLi5cdFx0Ly9GaXhJbjogOS40LjQuMTNcclxuXHQgKi9cclxuXHRqUXVlcnkoICdib2R5JyApLm9uKCAnd3BiY19kYXRlcGlja19pbmxpbmVfY2FsZW5kYXJfcmVmcmVzaCcsIGZ1bmN0aW9uICggZXZlbnQsIHJlc291cmNlX2lkLCBpbnN0ICl7XHJcblx0XHQvLyBpbnN0LmRwRGl2ICBpdCdzOiAgPGRpdiBjbGFzcz1cImRhdGVwaWNrLWlubGluZSBkYXRlcGljay1tdWx0aVwiIHN0eWxlPVwid2lkdGg6IDE3NzEycHg7XCI+Li4uLjwvZGl2PlxyXG5cdFx0aW5zdC5kcERpdi5maW5kKCAnLnNlYXNvbl91bmF2YWlsYWJsZSwuYmVmb3JlX2FmdGVyX3VuYXZhaWxhYmxlLC53ZWVrZGF5c191bmF2YWlsYWJsZScgKS5vbiggJ21vdXNlb3ZlcicsIGZ1bmN0aW9uICggdGhpc19ldmVudCApe1xyXG5cdFx0XHQvLyBhbHNvIGF2YWlsYWJsZSB0aGVzZSB2YXJzOiBcdHJlc291cmNlX2lkLCBqQ2FsQ29udGFpbmVyLCBpbnN0XHJcblx0XHRcdHZhciBqQ2VsbCA9IGpRdWVyeSggdGhpc19ldmVudC5jdXJyZW50VGFyZ2V0ICk7XHJcblx0XHRcdHdwYmNfYXZ5X19zaG93X3Rvb2x0aXBfX2Zvcl9lbGVtZW50KCBqQ2VsbCwgY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2FqeF9kYXRhX2FycicgXVsncG9wb3Zlcl9oaW50cyddICk7XHJcblx0XHR9KTtcclxuXHJcblx0fVx0KTtcclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvKipcclxuXHQgKiBEZWZpbmUgaGVpZ2h0IG9mIHRoZSBjYWxlbmRhciAgY2VsbHMsIFx0YW5kICBtb3VzZSBvdmVyIHRvb2x0aXBzIGF0ICBzb21lIHVuYXZhaWxhYmxlIGRhdGVzXHJcblx0ICogSXQncyBkZWZpbmVkLCB3aGVuIGNhbGVuZGFyIGxvYWRlZCBpbiBqcXVlcnkuZGF0ZXBpY2sud3BiYy45LjAuanMgOlxyXG5cdCAqIFx0XHQkKCAnYm9keScgKS50cmlnZ2VyKCAnd3BiY19kYXRlcGlja19pbmxpbmVfY2FsZW5kYXJfbG9hZGVkJywgLi4uXHRcdC8vRml4SW46IDkuNC40LjEyXHJcblx0ICovXHJcblx0alF1ZXJ5KCAnYm9keScgKS5vbiggJ3dwYmNfZGF0ZXBpY2tfaW5saW5lX2NhbGVuZGFyX2xvYWRlZCcsIGZ1bmN0aW9uICggZXZlbnQsIHJlc291cmNlX2lkLCBqQ2FsQ29udGFpbmVyLCBpbnN0ICl7XHJcblxyXG5cdFx0Ly8gUmVtb3ZlIGhpZ2hsaWdodCBkYXkgZm9yIHRvZGF5ICBkYXRlXHJcblx0XHRqUXVlcnkoICcuZGF0ZXBpY2stZGF5cy1jZWxsLmRhdGVwaWNrLXRvZGF5LmRhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyApLnJlbW92ZUNsYXNzKCAnZGF0ZXBpY2stZGF5cy1jZWxsLW92ZXInICk7XHJcblxyXG5cdFx0Ly8gU2V0IGhlaWdodCBvZiBjYWxlbmRhciAgY2VsbHMgaWYgZGVmaW5lZCB0aGlzIG9wdGlvblxyXG5cdFx0aWYgKCAnJyAhPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX2NlbGxfaGVpZ2h0ICl7XHJcblx0XHRcdGpRdWVyeSggJ2hlYWQnICkuYXBwZW5kKCAnPHN0eWxlIHR5cGU9XCJ0ZXh0L2Nzc1wiPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQrICcuaGFzRGF0ZXBpY2sgLmRhdGVwaWNrLWlubGluZSAuZGF0ZXBpY2stdGl0bGUtcm93IHRoLCAnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnLmhhc0RhdGVwaWNrIC5kYXRlcGljay1pbmxpbmUgLmRhdGVwaWNrLWRheXMtY2VsbCB7J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnaGVpZ2h0OiAnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX2NlbGxfaGVpZ2h0ICsgJyAhaW1wb3J0YW50OydcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQrICd9J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHQrJzwvc3R5bGU+JyApO1xyXG5cdFx0fVxyXG5cclxuXHRcdC8vIERlZmluZSBzaG93aW5nIG1vdXNlIG92ZXIgdG9vbHRpcCBvbiB1bmF2YWlsYWJsZSBkYXRlc1xyXG5cdFx0akNhbENvbnRhaW5lci5maW5kKCAnLnNlYXNvbl91bmF2YWlsYWJsZSwuYmVmb3JlX2FmdGVyX3VuYXZhaWxhYmxlLC53ZWVrZGF5c191bmF2YWlsYWJsZScgKS5vbiggJ21vdXNlb3ZlcicsIGZ1bmN0aW9uICggdGhpc19ldmVudCApe1xyXG5cdFx0XHQvLyBhbHNvIGF2YWlsYWJsZSB0aGVzZSB2YXJzOiBcdHJlc291cmNlX2lkLCBqQ2FsQ29udGFpbmVyLCBpbnN0XHJcblx0XHRcdHZhciBqQ2VsbCA9IGpRdWVyeSggdGhpc19ldmVudC5jdXJyZW50VGFyZ2V0ICk7XHJcblx0XHRcdHdwYmNfYXZ5X19zaG93X3Rvb2x0aXBfX2Zvcl9lbGVtZW50KCBqQ2VsbCwgY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2FqeF9kYXRhX2FycicgXVsncG9wb3Zlcl9oaW50cyddICk7XHJcblx0XHR9KTtcclxuXHR9ICk7XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gRGVmaW5lIHdpZHRoIG9mIGVudGlyZSBjYWxlbmRhclxyXG5cdHZhciB3aWR0aCA9ICAgJ3dpZHRoOidcdFx0KyAgIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X193aWR0aCArICc7JztcdFx0XHRcdFx0Ly8gdmFyIHdpZHRoID0gJ3dpZHRoOjEwMCU7bWF4LXdpZHRoOjEwMCU7JztcclxuXHJcblx0aWYgKCAgICggdW5kZWZpbmVkICE9IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X19tYXhfd2lkdGggKVxyXG5cdFx0JiYgKCAnJyAhPSBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fbWF4X3dpZHRoIClcclxuXHQpe1xyXG5cdFx0d2lkdGggKz0gJ21heC13aWR0aDonIFx0KyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fbWF4X3dpZHRoICsgJzsnO1xyXG5cdH0gZWxzZSB7XHJcblx0XHR3aWR0aCArPSAnbWF4LXdpZHRoOicgXHQrICggY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX21vbnRoc19pbl9yb3cgKiAzNDEgKSArICdweDsnO1xyXG5cdH1cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBBZGQgY2FsZW5kYXIgY29udGFpbmVyOiBcIkNhbGVuZGFyIGlzIGxvYWRpbmcuLi5cIiAgYW5kIHRleHRhcmVhXHJcblx0alF1ZXJ5KCAnLndwYmNfYWp4X2F2eV9fY2FsZW5kYXInICkuaHRtbChcclxuXHJcblx0XHQnPGRpdiBjbGFzcz1cIidcdCsgJyBia19jYWxlbmRhcl9mcmFtZSdcclxuXHRcdFx0XHRcdFx0KyAnIG1vbnRoc19udW1faW5fcm93XycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fbW9udGhzX2luX3Jvd1xyXG5cdFx0XHRcdFx0XHQrICcgY2FsX21vbnRoX251bV8nIFx0KyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHNcclxuXHRcdFx0XHRcdFx0KyAnICcgXHRcdFx0XHRcdCsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3RpbWVzbG90X2RheV9iZ19hc19hdmFpbGFibGUgXHRcdFx0XHQvLyAnd3BiY190aW1lc2xvdF9kYXlfYmdfYXNfYXZhaWxhYmxlJyB8fCAnJ1xyXG5cdFx0XHRcdCsgJ1wiICdcclxuXHRcdFx0KyAnc3R5bGU9XCInICsgd2lkdGggKyAnXCI+J1xyXG5cclxuXHRcdFx0XHQrICc8ZGl2IGlkPVwiY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJ1wiPicgKyAnQ2FsZW5kYXIgaXMgbG9hZGluZy4uLicgKyAnPC9kaXY+J1xyXG5cclxuXHRcdCsgJzwvZGl2PidcclxuXHJcblx0XHQrICc8dGV4dGFyZWEgICAgICBpZD1cImRhdGVfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJ1wiJ1xyXG5cdFx0XHRcdFx0KyAnIG5hbWU9XCJkYXRlX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCArICdcIidcclxuXHRcdFx0XHRcdCsgJyBhdXRvY29tcGxldGU9XCJvZmZcIidcclxuXHRcdFx0XHRcdCsgJyBzdHlsZT1cImRpc3BsYXk6bm9uZTt3aWR0aDoxMDAlO2hlaWdodDoxMGVtO21hcmdpbjoyZW0gMCAwO1wiPjwvdGV4dGFyZWE+J1xyXG5cdCk7XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIGNhbF9wYXJhbV9hcnIgPSB7XHJcblx0XHRcdFx0XHRcdFx0J2h0bWxfaWQnICAgICAgICAgICA6ICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cdFx0XHRcdFx0XHRcdCd0ZXh0X2lkJyAgICAgICAgICAgOiAnZGF0ZV9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cclxuXHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX3N0YXJ0X3dlZWtfZGF5JzogXHQgIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX19zdGFydF93ZWVrX2RheSxcclxuXHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzLFxyXG5cdFx0XHRcdFx0XHRcdCdjYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSc6ICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSxcclxuXHJcblx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX2lkJyAgICAgICAgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHRcdFx0XHRcdFx0XHQnYWp4X25vbmNlX2NhbGVuZGFyJyA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLmFqeF9ub25jZV9jYWxlbmRhcixcclxuXHRcdFx0XHRcdFx0XHQnYm9va2VkX2RhdGVzJyAgICAgICA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLmJvb2tlZF9kYXRlcyxcclxuXHRcdFx0XHRcdFx0XHQnc2Vhc29uX2F2YWlsYWJpbGl0eSc6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLnNlYXNvbl9hdmFpbGFiaWxpdHksXHJcblxyXG5cdFx0XHRcdFx0XHRcdCdyZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlcycgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5yZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlcyxcclxuXHJcblx0XHRcdFx0XHRcdFx0J3BvcG92ZXJfaGludHMnOiBjYWxlbmRhcl9wYXJhbXNfYXJyWyAnYWp4X2RhdGFfYXJyJyBdWydwb3BvdmVyX2hpbnRzJ11cdFx0Ly8geydzZWFzb25fdW5hdmFpbGFibGUnOicuLi4nLCd3ZWVrZGF5c191bmF2YWlsYWJsZSc6Jy4uLicsJ2JlZm9yZV9hZnRlcl91bmF2YWlsYWJsZSc6Jy4uLicsfVxyXG5cdFx0XHRcdFx0XHR9O1xyXG5cdHdwYmNfc2hvd19pbmxpbmVfYm9va2luZ19jYWxlbmRhciggY2FsX3BhcmFtX2FyciApO1xyXG5cclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8qKlxyXG5cdCAqIE9uIGNsaWNrIEFWQUlMQUJMRSB8ICBVTkFWQUlMQUJMRSBidXR0b24gIGluIHdpZGdldFx0LVx0bmVlZCB0byAgY2hhbmdlIGhlbHAgZGF0ZXMgdGV4dFxyXG5cdCAqL1xyXG5cdGpRdWVyeSggJy53cGJjX3JhZGlvX19zZXRfZGF5c19hdmFpbGFiaWxpdHknICkub24oJ2NoYW5nZScsIGZ1bmN0aW9uICggZXZlbnQsIHJlc291cmNlX2lkLCBpbnN0ICl7XHJcblx0XHR3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19zZWxlY3QoIGpRdWVyeSggJyMnICsgY2FsX3BhcmFtX2Fyci50ZXh0X2lkICkudmFsKCkgLCBjYWxfcGFyYW1fYXJyICk7XHJcblx0fSk7XHJcblxyXG5cdC8vIFNob3cgXHQnU2VsZWN0IGRheXMgIGluIGNhbGVuZGFyIHRoZW4gc2VsZWN0IEF2YWlsYWJsZSAgLyAgVW5hdmFpbGFibGUgc3RhdHVzIGFuZCBjbGljayBBcHBseSBhdmFpbGFiaWxpdHkgYnV0dG9uLidcclxuXHRqUXVlcnkoICcjd3BiY190b29sYmFyX2RhdGVzX2hpbnQnKS5odG1sKCAgICAgJzxkaXYgY2xhc3M9XCJ1aV9lbGVtZW50XCI+PHNwYW4gY2xhc3M9XCJ3cGJjX3VpX2NvbnRyb2wgd3BiY191aV9hZGRvbiB3cGJjX2hlbHBfdGV4dFwiID4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyBjYWxfcGFyYW1fYXJyLnBvcG92ZXJfaGludHMudG9vbGJhcl90ZXh0XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgJzwvc3Bhbj48L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqIFx0TG9hZCBEYXRlcGljayBJbmxpbmUgY2FsZW5kYXJcclxuICpcclxuICogQHBhcmFtIGNhbGVuZGFyX3BhcmFtc19hcnJcdFx0ZXhhbXBsZTp7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnaHRtbF9pZCcgICAgICAgICAgIDogJ2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQndGV4dF9pZCcgICAgICAgICAgIDogJ2RhdGVfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX3N0YXJ0X3dlZWtfZGF5JzogXHQgIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX19zdGFydF93ZWVrX2RheSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdjYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHMnOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHMsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUnOiAgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUsXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX2lkJyAgICAgICAgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfbm9uY2VfY2FsZW5kYXInIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuYWp4X25vbmNlX2NhbGVuZGFyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tlZF9kYXRlcycgICAgICAgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5ib29rZWRfZGF0ZXMsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2Vhc29uX2F2YWlsYWJpbGl0eSc6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLnNlYXNvbl9hdmFpbGFiaWxpdHksXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzJyA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLnJlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG4gKiBAcmV0dXJucyB7Ym9vbGVhbn1cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfc2hvd19pbmxpbmVfYm9va2luZ19jYWxlbmRhciggY2FsZW5kYXJfcGFyYW1zX2FyciApe1xyXG5cclxuXHRpZiAoXHJcblx0XHQgICAoIDAgPT09IGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5odG1sX2lkICkubGVuZ3RoIClcdFx0XHRcdFx0XHRcdC8vIElmIGNhbGVuZGFyIERPTSBlbGVtZW50IG5vdCBleGlzdCB0aGVuIGV4aXN0XHJcblx0XHR8fCAoIHRydWUgPT09IGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5odG1sX2lkICkuaGFzQ2xhc3MoICdoYXNEYXRlcGljaycgKSApXHQvLyBJZiB0aGUgY2FsZW5kYXIgd2l0aCB0aGUgc2FtZSBCb29raW5nIHJlc291cmNlIGFscmVhZHkgIGhhcyBiZWVuIGFjdGl2YXRlZCwgdGhlbiBleGlzdC5cclxuXHQpe1xyXG5cdCAgIHJldHVybiBmYWxzZTtcclxuXHR9XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gQ29uZmlndXJlIGFuZCBzaG93IGNhbGVuZGFyXHJcblx0alF1ZXJ5KCAnIycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmh0bWxfaWQgKS50ZXh0KCAnJyApO1xyXG5cdGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5odG1sX2lkICkuZGF0ZXBpY2soe1xyXG5cdFx0XHRcdFx0YmVmb3JlU2hvd0RheTogXHRmdW5jdGlvbiAoIGRhdGUgKXtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRyZXR1cm4gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2FwcGx5X2Nzc190b19kYXlzKCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCB0aGlzICk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdH0sXHJcbiAgICAgICAgICAgICAgICAgICAgb25TZWxlY3Q6IFx0ICBcdGZ1bmN0aW9uICggZGF0ZSApe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci50ZXh0X2lkICkudmFsKCBkYXRlICk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0Ly93cGJjX2JsaW5rX2VsZW1lbnQoJy53cGJjX3dpZGdldF9hdmFpbGFibGVfdW5hdmFpbGFibGUnLCAzLCAyMjApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHJldHVybiB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19zZWxlY3QoIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIHRoaXMgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcclxuICAgICAgICAgICAgICAgICAgICBvbkhvdmVyOiBcdFx0ZnVuY3Rpb24gKCB2YWx1ZSwgZGF0ZSApe1xyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQvL3dwYmNfYXZ5X19wcmVwYXJlX3Rvb2x0aXBfX2luX2NhbGVuZGFyKCB2YWx1ZSwgZGF0ZSwgY2FsZW5kYXJfcGFyYW1zX2FyciwgdGhpcyApO1xyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRyZXR1cm4gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfaG92ZXIoIHZhbHVlLCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCB0aGlzICk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdH0sXHJcbiAgICAgICAgICAgICAgICAgICAgb25DaGFuZ2VNb250aFllYXI6XHRudWxsLFxyXG4gICAgICAgICAgICAgICAgICAgIHNob3dPbjogXHRcdFx0J2JvdGgnLFxyXG4gICAgICAgICAgICAgICAgICAgIG51bWJlck9mTW9udGhzOiBcdGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzLFxyXG4gICAgICAgICAgICAgICAgICAgIHN0ZXBNb250aHM6XHRcdFx0MSxcclxuICAgICAgICAgICAgICAgICAgICBwcmV2VGV4dDogXHRcdFx0JyZsYXF1bzsnLFxyXG4gICAgICAgICAgICAgICAgICAgIG5leHRUZXh0OiBcdFx0XHQnJnJhcXVvOycsXHJcbiAgICAgICAgICAgICAgICAgICAgZGF0ZUZvcm1hdDogXHRcdCd5eS1tbS1kZCcsLy8gJ2RkLm1tLnl5JyxcclxuICAgICAgICAgICAgICAgICAgICBjaGFuZ2VNb250aDogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGNoYW5nZVllYXI6IFx0XHRmYWxzZSxcclxuICAgICAgICAgICAgICAgICAgICBtaW5EYXRlOiBcdFx0XHRcdFx0IDAsXHRcdC8vbnVsbCwgIC8vU2Nyb2xsIGFzIGxvbmcgYXMgeW91IG5lZWRcclxuXHRcdFx0XHRcdG1heERhdGU6IFx0XHRcdFx0XHQnMTB5JyxcdC8vIG1pbkRhdGU6IG5ldyBEYXRlKDIwMjAsIDIsIDEpLCBtYXhEYXRlOiBuZXcgRGF0ZSgyMDIwLCA5LCAzMSksIFx0Ly8gQWJpbGl0eSB0byBzZXQgYW55ICBzdGFydCBhbmQgZW5kIGRhdGUgaW4gY2FsZW5kYXJcclxuICAgICAgICAgICAgICAgICAgICBzaG93U3RhdHVzOiBcdFx0ZmFsc2UsXHJcbiAgICAgICAgICAgICAgICAgICAgY2xvc2VBdFRvcDogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGZpcnN0RGF5Olx0XHRcdGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX3N0YXJ0X3dlZWtfZGF5LFxyXG4gICAgICAgICAgICAgICAgICAgIGdvdG9DdXJyZW50OiBcdFx0ZmFsc2UsXHJcbiAgICAgICAgICAgICAgICAgICAgaGlkZUlmTm9QcmV2TmV4dDpcdHRydWUsXHJcbiAgICAgICAgICAgICAgICAgICAgbXVsdGlTZXBhcmF0b3I6IFx0JywgJyxcclxuXHRcdFx0XHRcdG11bHRpU2VsZWN0OiAoKCdkeW5hbWljJyA9PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlKSA/IDAgOiAzNjUpLFx0XHRcdC8vIE1heGltdW0gbnVtYmVyIG9mIHNlbGVjdGFibGUgZGF0ZXM6XHQgU2luZ2xlIGRheSA9IDAsICBtdWx0aSBkYXlzID0gMzY1XHJcblx0XHRcdFx0XHRyYW5nZVNlbGVjdDogICgnZHluYW1pYycgPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSksXHJcblx0XHRcdFx0XHRyYW5nZVNlcGFyYXRvcjogXHQnIH4gJyxcdFx0XHRcdFx0Ly8nIC0gJyxcclxuICAgICAgICAgICAgICAgICAgICAvLyBzaG93V2Vla3M6IHRydWUsXHJcbiAgICAgICAgICAgICAgICAgICAgdXNlVGhlbWVSb2xsZXI6XHRcdGZhbHNlXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgKTtcclxuXHJcblx0cmV0dXJuICB0cnVlO1xyXG59XHJcblxyXG5cclxuXHQvKipcclxuXHQgKiBBcHBseSBDU1MgdG8gY2FsZW5kYXIgZGF0ZSBjZWxsc1xyXG5cdCAqXHJcblx0ICogQHBhcmFtIGRhdGVcdFx0XHRcdFx0LSAgSmF2YVNjcmlwdCBEYXRlIE9iajogIFx0XHRNb24gRGVjIDExIDIwMjMgMDA6MDA6MDAgR01UKzAyMDAgKEVhc3Rlcm4gRXVyb3BlYW4gU3RhbmRhcmQgVGltZSlcclxuXHQgKiBAcGFyYW0gY2FsZW5kYXJfcGFyYW1zX2Fyclx0LSAgQ2FsZW5kYXIgU2V0dGluZ3MgT2JqZWN0OiAgXHR7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiaHRtbF9pZFwiOiBcImNhbGVuZGFyX2Jvb2tpbmc0XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwidGV4dF9pZFwiOiBcImRhdGVfYm9va2luZzRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJjYWxlbmRhcl9fc3RhcnRfd2Vla19kYXlcIjogMSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJjYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHNcIjogMTIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwicmVzb3VyY2VfaWRcIjogNCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJhanhfbm9uY2VfY2FsZW5kYXJcIjogXCI8aW5wdXQgdHlwZT1cXFwiaGlkZGVuXFxcIiAuLi4gLz5cIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJib29rZWRfZGF0ZXNcIjoge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjEyLTI4LTIwMjJcIjogW1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImJvb2tpbmdfZGF0ZVwiOiBcIjIwMjItMTItMjggMDA6MDA6MDBcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImFwcHJvdmVkXCI6IFwiMVwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYm9va2luZ19pZFwiOiBcIjI2XCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdF0sIC4uLlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdzZWFzb25fYXZhaWxhYmlsaXR5Jzp7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIyMDIzLTAxLTA5XCI6IHRydWUsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIyMDIzLTAxLTEwXCI6IHRydWUsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIyMDIzLTAxLTExXCI6IHRydWUsIC4uLlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHQgKiBAcGFyYW0gZGF0ZXBpY2tfdGhpc1x0XHRcdC0gdGhpcyBvZiBkYXRlcGljayBPYmpcclxuXHQgKlxyXG5cdCAqIEByZXR1cm5zIFtib29sZWFuLHN0cmluZ11cdC0gWyB7dHJ1ZSAtYXZhaWxhYmxlIHwgZmFsc2UgLSB1bmF2YWlsYWJsZX0sICdDU1MgY2xhc3NlcyBmb3IgY2FsZW5kYXIgZGF5IGNlbGwnIF1cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fYXBwbHlfY3NzX3RvX2RheXMoIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIGRhdGVwaWNrX3RoaXMgKXtcclxuXHJcblx0XHR2YXIgdG9kYXlfZGF0ZSA9IG5ldyBEYXRlKCBfd3BiYy5nZXRfb3RoZXJfcGFyYW0oICd0b2RheV9hcnInIClbIDAgXSwgKHBhcnNlSW50KCBfd3BiYy5nZXRfb3RoZXJfcGFyYW0oICd0b2RheV9hcnInIClbIDEgXSApIC0gMSksIF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ3RvZGF5X2FycicgKVsgMiBdLCAwLCAwLCAwICk7XHJcblxyXG5cdFx0dmFyIGNsYXNzX2RheSAgPSAoIGRhdGUuZ2V0TW9udGgoKSArIDEgKSArICctJyArIGRhdGUuZ2V0RGF0ZSgpICsgJy0nICsgZGF0ZS5nZXRGdWxsWWVhcigpO1x0XHRcdFx0XHRcdC8vICcxLTktMjAyMydcclxuXHRcdHZhciBzcWxfY2xhc3NfZGF5ID0gd3BiY19fZ2V0X19zcWxfY2xhc3NfZGF0ZSggZGF0ZSApO1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gJzIwMjMtMDEtMDknXHJcblxyXG5cdFx0dmFyIGNzc19kYXRlX19zdGFuZGFyZCAgID0gICdjYWw0ZGF0ZS0nICsgY2xhc3NfZGF5O1xyXG5cdFx0dmFyIGNzc19kYXRlX19hZGRpdGlvbmFsID0gJyB3cGJjX3dlZWtkYXlfJyArIGRhdGUuZ2V0RGF5KCkgKyAnICc7XHJcblxyXG5cdFx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuXHRcdC8vIFdFRUtEQVlTIDo6IFNldCB1bmF2YWlsYWJsZSB3ZWVrIGRheXMgZnJvbSAtIFNldHRpbmdzIEdlbmVyYWwgcGFnZSBpbiBcIkF2YWlsYWJpbGl0eVwiIHNlY3Rpb25cclxuXHRcdGZvciAoIHZhciBpID0gMDsgaSA8IF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ2F2YWlsYWJpbGl0eV9fd2Vla19kYXlzX3VuYXZhaWxhYmxlJyApLmxlbmd0aDsgaSsrICl7XHJcblx0XHRcdGlmICggZGF0ZS5nZXREYXkoKSA9PSBfd3BiYy5nZXRfb3RoZXJfcGFyYW0oICdhdmFpbGFiaWxpdHlfX3dlZWtfZGF5c191bmF2YWlsYWJsZScgKVsgaSBdICkge1xyXG5cdFx0XHRcdHJldHVybiBbICEhZmFsc2UsIGNzc19kYXRlX19zdGFuZGFyZCArICcgZGF0ZV91c2VyX3VuYXZhaWxhYmxlJyBcdCsgJyB3ZWVrZGF5c191bmF2YWlsYWJsZScgXTtcclxuXHRcdFx0fVxyXG5cdFx0fVxyXG5cclxuXHRcdC8vIEJFRk9SRV9BRlRFUiA6OiBTZXQgdW5hdmFpbGFibGUgZGF5cyBCZWZvcmUgLyBBZnRlciB0aGUgVG9kYXkgZGF0ZVxyXG5cdFx0aWYgKCBcdCggKHdwYmNfZGF0ZXNfX2RheXNfYmV0d2VlbiggZGF0ZSwgdG9kYXlfZGF0ZSApKSA8IHBhcnNlSW50KF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ2F2YWlsYWJpbGl0eV9fdW5hdmFpbGFibGVfZnJvbV90b2RheScgKSkgKVxyXG5cdFx0XHQgfHwgKFxyXG5cdFx0XHRcdCAgICggcGFyc2VJbnQoICcwJyArIHBhcnNlSW50KCBfd3BiYy5nZXRfb3RoZXJfcGFyYW0oICdhdmFpbGFiaWxpdHlfX2F2YWlsYWJsZV9mcm9tX3RvZGF5JyApICkgKSA+IDAgKVxyXG5cdFx0XHRcdCYmICggd3BiY19kYXRlc19fZGF5c19iZXR3ZWVuKCBkYXRlLCB0b2RheV9kYXRlICkgPiBwYXJzZUludCggJzAnICsgcGFyc2VJbnQoIF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ2F2YWlsYWJpbGl0eV9fYXZhaWxhYmxlX2Zyb21fdG9kYXknICkgKSApIClcclxuXHRcdFx0XHQpXHJcblx0XHQpe1xyXG5cdFx0XHRyZXR1cm4gWyAhIWZhbHNlLCBjc3NfZGF0ZV9fc3RhbmRhcmQgKyAnIGRhdGVfdXNlcl91bmF2YWlsYWJsZScgXHRcdCsgJyBiZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUnIF07XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8gU0VBU09OUyA6OiAgXHRcdFx0XHRcdEJvb2tpbmcgPiBSZXNvdXJjZXMgPiBBdmFpbGFiaWxpdHkgcGFnZVxyXG5cdFx0dmFyICAgIGlzX2RhdGVfYXZhaWxhYmxlID0gY2FsZW5kYXJfcGFyYW1zX2Fyci5zZWFzb25fYXZhaWxhYmlsaXR5WyBzcWxfY2xhc3NfZGF5IF07XHJcblx0XHRpZiAoIGZhbHNlID09PSBpc19kYXRlX2F2YWlsYWJsZSApe1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL0ZpeEluOiA5LjUuNC40XHJcblx0XHRcdHJldHVybiBbICEhZmFsc2UsIGNzc19kYXRlX19zdGFuZGFyZCArICcgZGF0ZV91c2VyX3VuYXZhaWxhYmxlJ1x0XHQrICcgc2Vhc29uX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0fVxyXG5cclxuXHRcdC8vIFJFU09VUkNFX1VOQVZBSUxBQkxFIDo6ICAgXHRCb29raW5nID4gQXZhaWxhYmlsaXR5IHBhZ2VcclxuXHRcdGlmICggd3BiY19pbl9hcnJheShjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzLCBzcWxfY2xhc3NfZGF5ICkgKXtcclxuXHRcdFx0aXNfZGF0ZV9hdmFpbGFibGUgPSBmYWxzZTtcclxuXHRcdH1cclxuXHRcdGlmICggIGZhbHNlID09PSBpc19kYXRlX2F2YWlsYWJsZSApe1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS41LjQuNFxyXG5cdFx0XHRyZXR1cm4gWyAhZmFsc2UsIGNzc19kYXRlX19zdGFuZGFyZCArICcgZGF0ZV91c2VyX3VuYXZhaWxhYmxlJ1x0XHQrICcgcmVzb3VyY2VfdW5hdmFpbGFibGUnIF07XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuXHRcdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHJcblxyXG5cdFx0Ly8gSXMgYW55IGJvb2tpbmdzIGluIHRoaXMgZGF0ZSA/XHJcblx0XHRpZiAoICd1bmRlZmluZWQnICE9PSB0eXBlb2YoIGNhbGVuZGFyX3BhcmFtc19hcnIuYm9va2VkX2RhdGVzWyBjbGFzc19kYXkgXSApICkge1xyXG5cclxuXHRcdFx0dmFyIGJvb2tpbmdzX2luX2RhdGUgPSBjYWxlbmRhcl9wYXJhbXNfYXJyLmJvb2tlZF9kYXRlc1sgY2xhc3NfZGF5IF07XHJcblxyXG5cclxuXHRcdFx0aWYgKCAndW5kZWZpbmVkJyAhPT0gdHlwZW9mKCBib29raW5nc19pbl9kYXRlWyAnc2VjXzAnIF0gKSApIHtcdFx0XHQvLyBcIkZ1bGwgZGF5XCIgYm9va2luZyAgLT4gKHNlY29uZHMgPT0gMClcclxuXHJcblx0XHRcdFx0Y3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gKCAnMCcgPT09IGJvb2tpbmdzX2luX2RhdGVbICdzZWNfMCcgXS5hcHByb3ZlZCApID8gJyBkYXRlMmFwcHJvdmUgJyA6ICcgZGF0ZV9hcHByb3ZlZCAnO1x0XHRcdFx0Ly8gUGVuZGluZyA9ICcwJyB8ICBBcHByb3ZlZCA9ICcxJ1xyXG5cdFx0XHRcdGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgZnVsbF9kYXlfYm9va2luZyc7XHJcblxyXG5cdFx0XHRcdHJldHVybiBbICFmYWxzZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgY3NzX2RhdGVfX2FkZGl0aW9uYWwgXTtcclxuXHJcblx0XHRcdH0gZWxzZSBpZiAoIE9iamVjdC5rZXlzKCBib29raW5nc19pbl9kYXRlICkubGVuZ3RoID4gMCApe1x0XHRcdFx0Ly8gXCJUaW1lIHNsb3RzXCIgQm9va2luZ3NcclxuXHJcblx0XHRcdFx0dmFyIGlzX2FwcHJvdmVkID0gdHJ1ZTtcclxuXHJcblx0XHRcdFx0Xy5lYWNoKCBib29raW5nc19pbl9kYXRlLCBmdW5jdGlvbiAoIHBfdmFsLCBwX2tleSwgcF9kYXRhICkge1xyXG5cdFx0XHRcdFx0aWYgKCAhcGFyc2VJbnQoIHBfdmFsLmFwcHJvdmVkICkgKXtcclxuXHRcdFx0XHRcdFx0aXNfYXBwcm92ZWQgPSBmYWxzZTtcclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdHZhciB0cyA9IHBfdmFsLmJvb2tpbmdfZGF0ZS5zdWJzdHJpbmcoIHBfdmFsLmJvb2tpbmdfZGF0ZS5sZW5ndGggLSAxICk7XHJcblx0XHRcdFx0XHRpZiAoIHRydWUgPT09IF93cGJjLmdldF9vdGhlcl9wYXJhbSggJ2lzX2VuYWJsZWRfY2hhbmdlX292ZXInICkgKXtcclxuXHRcdFx0XHRcdFx0aWYgKCB0cyA9PSAnMScgKSB7IGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgY2hlY2tfaW5fdGltZScgKyAoKHBhcnNlSW50KHBfdmFsLmFwcHJvdmVkKSkgPyAnIGNoZWNrX2luX3RpbWVfZGF0ZV9hcHByb3ZlZCcgOiAnIGNoZWNrX2luX3RpbWVfZGF0ZTJhcHByb3ZlJyk7IH1cclxuXHRcdFx0XHRcdFx0aWYgKCB0cyA9PSAnMicgKSB7IGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgY2hlY2tfb3V0X3RpbWUnICsgKChwYXJzZUludChwX3ZhbC5hcHByb3ZlZCkpID8gJyBjaGVja19vdXRfdGltZV9kYXRlX2FwcHJvdmVkJyA6ICcgY2hlY2tfb3V0X3RpbWVfZGF0ZTJhcHByb3ZlJyk7IH1cclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0fSk7XHJcblxyXG5cdFx0XHRcdGlmICggISBpc19hcHByb3ZlZCApe1xyXG5cdFx0XHRcdFx0Y3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gJyBkYXRlMmFwcHJvdmUgdGltZXNwYXJ0bHknXHJcblx0XHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRcdGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgZGF0ZV9hcHByb3ZlZCB0aW1lc3BhcnRseSdcclxuXHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdGlmICggISBfd3BiYy5nZXRfb3RoZXJfcGFyYW0oICdpc19lbmFibGVkX2NoYW5nZV9vdmVyJyApICl7XHJcblx0XHRcdFx0XHRjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAnIHRpbWVzX2Nsb2NrJ1xyXG5cdFx0XHRcdH1cclxuXHJcblx0XHRcdH1cclxuXHJcblx0XHR9XHJcblxyXG5cdFx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuXHRcdHJldHVybiBbIHRydWUsIGNzc19kYXRlX19zdGFuZGFyZCArIGNzc19kYXRlX19hZGRpdGlvbmFsICsgJyBkYXRlX2F2YWlsYWJsZScgXTtcclxuXHR9XHJcblxyXG5cclxuXHQvKipcclxuXHQgKiBBcHBseSBzb21lIENTUyBjbGFzc2VzLCB3aGVuIHdlIG1vdXNlIG92ZXIgc3BlY2lmaWMgZGF0ZXMgaW4gY2FsZW5kYXJcclxuXHQgKiBAcGFyYW0gdmFsdWVcclxuXHQgKiBAcGFyYW0gZGF0ZVx0XHRcdFx0XHQtICBKYXZhU2NyaXB0IERhdGUgT2JqOiAgXHRcdE1vbiBEZWMgMTEgMjAyMyAwMDowMDowMCBHTVQrMDIwMCAoRWFzdGVybiBFdXJvcGVhbiBTdGFuZGFyZCBUaW1lKVxyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHQtICBDYWxlbmRhciBTZXR0aW5ncyBPYmplY3Q6ICBcdHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJodG1sX2lkXCI6IFwiY2FsZW5kYXJfYm9va2luZzRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJ0ZXh0X2lkXCI6IFwiZGF0ZV9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX19zdGFydF93ZWVrX2RheVwiOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRoc1wiOiAxMixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJyZXNvdXJjZV9pZFwiOiA0LFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImFqeF9ub25jZV9jYWxlbmRhclwiOiBcIjxpbnB1dCB0eXBlPVxcXCJoaWRkZW5cXFwiIC4uLiAvPlwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImJvb2tlZF9kYXRlc1wiOiB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMTItMjgtMjAyMlwiOiBbXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYm9va2luZ19kYXRlXCI6IFwiMjAyMi0xMi0yOCAwMDowMDowMFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYXBwcm92ZWRcIjogXCIxXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2lkXCI6IFwiMjZcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9hdmFpbGFiaWxpdHknOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMge2Jvb2xlYW59XHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfaG92ZXIoIHZhbHVlLCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzICl7XHJcblxyXG5cdFx0aWYgKCBudWxsID09PSBkYXRlICl7XHJcblx0XHRcdGpRdWVyeSggJy5kYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKS5yZW1vdmVDbGFzcyggJ2RhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyApOyAgIFx0ICAgICAgICAgICAgICAgICAgICAgICAgLy8gY2xlYXIgYWxsIGhpZ2hsaWdodCBkYXlzIHNlbGVjdGlvbnNcclxuXHRcdFx0cmV0dXJuIGZhbHNlO1xyXG5cdFx0fVxyXG5cclxuXHRcdHZhciBpbnN0ID0galF1ZXJ5LmRhdGVwaWNrLl9nZXRJbnN0KCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCApICk7XHJcblxyXG5cdFx0aWYgKFxyXG5cdFx0XHQgICAoIDEgPT0gaW5zdC5kYXRlcy5sZW5ndGgpXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gSWYgd2UgaGF2ZSBvbmUgc2VsZWN0ZWQgZGF0ZVxyXG5cdFx0XHQmJiAoJ2R5bmFtaWMnID09PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlKSBcdFx0XHRcdFx0Ly8gd2hpbGUgaGF2ZSByYW5nZSBkYXlzIHNlbGVjdGlvbiBtb2RlXHJcblx0XHQpe1xyXG5cclxuXHRcdFx0dmFyIHRkX2NsYXNzO1xyXG5cdFx0XHR2YXIgdGRfb3ZlcnMgPSBbXTtcclxuXHRcdFx0dmFyIGlzX2NoZWNrID0gdHJ1ZTtcclxuICAgICAgICAgICAgdmFyIHNlbGNldGVkX2ZpcnN0X2RheSA9IG5ldyBEYXRlKCk7XHJcbiAgICAgICAgICAgIHNlbGNldGVkX2ZpcnN0X2RheS5zZXRGdWxsWWVhcihpbnN0LmRhdGVzWzBdLmdldEZ1bGxZZWFyKCksKGluc3QuZGF0ZXNbMF0uZ2V0TW9udGgoKSksIChpbnN0LmRhdGVzWzBdLmdldERhdGUoKSApICk7IC8vR2V0IGZpcnN0IERhdGVcclxuXHJcbiAgICAgICAgICAgIHdoaWxlKCAgaXNfY2hlY2sgKXtcclxuXHJcblx0XHRcdFx0dGRfY2xhc3MgPSAoc2VsY2V0ZWRfZmlyc3RfZGF5LmdldE1vbnRoKCkgKyAxKSArICctJyArIHNlbGNldGVkX2ZpcnN0X2RheS5nZXREYXRlKCkgKyAnLScgKyBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RnVsbFllYXIoKTtcclxuXHJcblx0XHRcdFx0dGRfb3ZlcnNbIHRkX292ZXJzLmxlbmd0aCBdID0gJyNjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgKyAnIC5jYWw0ZGF0ZS0nICsgdGRfY2xhc3M7ICAgICAgICAgICAgICAvLyBhZGQgdG8gYXJyYXkgZm9yIGxhdGVyIG1ha2Ugc2VsZWN0aW9uIGJ5IGNsYXNzXHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKFxyXG5cdFx0XHRcdFx0KCAgKCBkYXRlLmdldE1vbnRoKCkgPT0gc2VsY2V0ZWRfZmlyc3RfZGF5LmdldE1vbnRoKCkgKSAgJiZcclxuICAgICAgICAgICAgICAgICAgICAgICAoIGRhdGUuZ2V0RGF0ZSgpID09IHNlbGNldGVkX2ZpcnN0X2RheS5nZXREYXRlKCkgKSAgJiZcclxuICAgICAgICAgICAgICAgICAgICAgICAoIGRhdGUuZ2V0RnVsbFllYXIoKSA9PSBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RnVsbFllYXIoKSApXHJcblx0XHRcdFx0XHQpIHx8ICggc2VsY2V0ZWRfZmlyc3RfZGF5ID4gZGF0ZSApXHJcblx0XHRcdFx0KXtcclxuXHRcdFx0XHRcdGlzX2NoZWNrID0gIGZhbHNlO1xyXG5cdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0c2VsY2V0ZWRfZmlyc3RfZGF5LnNldEZ1bGxZZWFyKCBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RnVsbFllYXIoKSwgKHNlbGNldGVkX2ZpcnN0X2RheS5nZXRNb250aCgpKSwgKHNlbGNldGVkX2ZpcnN0X2RheS5nZXREYXRlKCkgKyAxKSApO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHQvLyBIaWdobGlnaHQgRGF5c1xyXG5cdFx0XHRmb3IgKCB2YXIgaT0wOyBpIDwgdGRfb3ZlcnMubGVuZ3RoIDsgaSsrKSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIGFkZCBjbGFzcyB0byBhbGwgZWxlbWVudHNcclxuXHRcdFx0XHRqUXVlcnkoIHRkX292ZXJzW2ldICkuYWRkQ2xhc3MoJ2RhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyk7XHJcblx0XHRcdH1cclxuXHRcdFx0cmV0dXJuIHRydWU7XHJcblxyXG5cdFx0fVxyXG5cclxuXHQgICAgcmV0dXJuIHRydWU7XHJcblx0fVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogT24gREFZcyBzZWxlY3Rpb24gaW4gY2FsZW5kYXJcclxuXHQgKlxyXG5cdCAqIEBwYXJhbSBkYXRlc19zZWxlY3Rpb25cdFx0LSAgc3RyaW5nOlx0XHRcdCAnMjAyMy0wMy0wNyB+IDIwMjMtMDMtMDcnIG9yICcyMDIzLTA0LTEwLCAyMDIzLTA0LTEyLCAyMDIzLTA0LTAyLCAyMDIzLTA0LTA0J1xyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHQtICBDYWxlbmRhciBTZXR0aW5ncyBPYmplY3Q6ICBcdHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJodG1sX2lkXCI6IFwiY2FsZW5kYXJfYm9va2luZzRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJ0ZXh0X2lkXCI6IFwiZGF0ZV9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX19zdGFydF93ZWVrX2RheVwiOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRoc1wiOiAxMixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJyZXNvdXJjZV9pZFwiOiA0LFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImFqeF9ub25jZV9jYWxlbmRhclwiOiBcIjxpbnB1dCB0eXBlPVxcXCJoaWRkZW5cXFwiIC4uLiAvPlwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImJvb2tlZF9kYXRlc1wiOiB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMTItMjgtMjAyMlwiOiBbXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYm9va2luZ19kYXRlXCI6IFwiMjAyMi0xMi0yOCAwMDowMDowMFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYXBwcm92ZWRcIjogXCIxXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2lkXCI6IFwiMjZcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9hdmFpbGFiaWxpdHknOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMgYm9vbGVhblxyXG5cdCAqL1xyXG5cdGZ1bmN0aW9uIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9kYXlzX3NlbGVjdCggZGF0ZXNfc2VsZWN0aW9uLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzID0gbnVsbCApe1xyXG5cclxuXHRcdHZhciBpbnN0ID0galF1ZXJ5LmRhdGVwaWNrLl9nZXRJbnN0KCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCApICk7XHJcblxyXG5cdFx0dmFyIGRhdGVzX2FyciA9IFtdO1x0Ly8gIFsgXCIyMDIzLTA0LTA5XCIsIFwiMjAyMy0wNC0xMFwiLCBcIjIwMjMtMDQtMTFcIiBdXHJcblxyXG5cdFx0aWYgKCAtMSAhPT0gZGF0ZXNfc2VsZWN0aW9uLmluZGV4T2YoICd+JyApICkgeyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBSYW5nZSBEYXlzXHJcblxyXG5cdFx0XHRkYXRlc19hcnIgPSB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19zZXBhcmF0b3InIDogJyB+ICcsICAgICAgICAgICAgICAgICAgICAgICAgIC8vICAnIH4gJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2RhdGVzJyAgICAgICAgICAgOiBkYXRlc19zZWxlY3Rpb24sICAgIFx0XHQgICAvLyAnMjAyMy0wNC0wNCB+IDIwMjMtMDQtMDcnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cclxuXHRcdH0gZWxzZSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBNdWx0aXBsZSBEYXlzXHJcblx0XHRcdGRhdGVzX2FyciA9IHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19jb21tYV9zZXBhcmF0ZWRfanMoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19zZXBhcmF0b3InIDogJywgJywgICAgICAgICAgICAgICAgICAgICAgICAgXHQvLyAgJywgJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2RhdGVzJyAgICAgICAgICAgOiBkYXRlc19zZWxlY3Rpb24sICAgIFx0XHRcdC8vICcyMDIzLTA0LTEwLCAyMDIzLTA0LTEyLCAyMDIzLTA0LTAyLCAyMDIzLTA0LTA0J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHRcdH1cclxuXHJcblx0XHR3cGJjX2F2eV9hZnRlcl9kYXlzX3NlbGVjdGlvbl9fc2hvd19oZWxwX2luZm8oe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19hcnInICAgICAgICAgICAgICAgICAgICA6IGRhdGVzX2FycixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19jbGlja19udW0nICAgICAgICAgICAgICA6IGluc3QuZGF0ZXMubGVuZ3RoLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3BvcG92ZXJfaGludHMnXHRcdFx0XHRcdDogY2FsZW5kYXJfcGFyYW1zX2Fyci5wb3BvdmVyX2hpbnRzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cdFx0cmV0dXJuIHRydWU7XHJcblx0fVxyXG5cclxuXHRcdC8qKlxyXG5cdFx0ICogU2hvdyBoZWxwIGluZm8gYXQgdGhlIHRvcCAgdG9vbGJhciBhYm91dCBzZWxlY3RlZCBkYXRlcyBhbmQgZnV0dXJlIGFjdGlvbnNcclxuXHRcdCAqXHJcblx0XHQgKiBAcGFyYW0gcGFyYW1zXHJcblx0XHQgKiBcdFx0XHRcdFx0RXhhbXBsZSAxOiAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGU6IFwiZHluYW1pY1wiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfYXJyOiAgWyBcIjIwMjMtMDQtMDNcIiBdLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfY2xpY2tfbnVtOiAxXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncG9wb3Zlcl9oaW50cydcdFx0XHRcdFx0OiBjYWxlbmRhcl9wYXJhbXNfYXJyLnBvcG92ZXJfaGludHNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHQgKiBcdFx0XHRcdFx0RXhhbXBsZSAyOiAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGU6IFwiZHluYW1pY1wiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRkYXRlc19hcnI6IEFycmF5KDEwKSBbIFwiMjAyMy0wNC0wM1wiLCBcIjIwMjMtMDQtMDRcIiwgXCIyMDIzLTA0LTA1XCIsIOKApiBdXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRkYXRlc19jbGlja19udW06IDJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdwb3BvdmVyX2hpbnRzJ1x0XHRcdFx0XHQ6IGNhbGVuZGFyX3BhcmFtc19hcnIucG9wb3Zlcl9oaW50c1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdCAqL1xyXG5cdFx0ZnVuY3Rpb24gd3BiY19hdnlfYWZ0ZXJfZGF5c19zZWxlY3Rpb25fX3Nob3dfaGVscF9pbmZvKCBwYXJhbXMgKXtcclxuLy8gY29uc29sZS5sb2coIHBhcmFtcyApO1x0Ly9cdFx0WyBcIjIwMjMtMDQtMDlcIiwgXCIyMDIzLTA0LTEwXCIsIFwiMjAyMy0wNC0xMVwiIF1cclxuXHJcblx0XHRcdHZhciBtZXNzYWdlLCBjb2xvcjtcclxuXHRcdFx0aWYgKGpRdWVyeSggJyN1aV9idG5fYXZ5X19zZXRfZGF5c19hdmFpbGFiaWxpdHlfX2F2YWlsYWJsZScpLmlzKCc6Y2hlY2tlZCcpKXtcclxuXHRcdFx0XHQgbWVzc2FnZSA9IHBhcmFtcy5wb3BvdmVyX2hpbnRzLnRvb2xiYXJfdGV4dF9hdmFpbGFibGU7Ly8nU2V0IGRhdGVzIF9EQVRFU18gYXMgX0hUTUxfIGF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdCBjb2xvciA9ICcjMTFiZTRjJztcclxuXHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRtZXNzYWdlID0gcGFyYW1zLnBvcG92ZXJfaGludHMudG9vbGJhcl90ZXh0X3VuYXZhaWxhYmxlOy8vJ1NldCBkYXRlcyBfREFURVNfIGFzIF9IVE1MXyB1bmF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdGNvbG9yID0gJyNlNDM5MzknO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHRtZXNzYWdlID0gJzxzcGFuPicgKyBtZXNzYWdlICsgJzwvc3Bhbj4nO1xyXG5cclxuXHRcdFx0dmFyIGZpcnN0X2RhdGUgPSBwYXJhbXNbICdkYXRlc19hcnInIF1bIDAgXTtcclxuXHRcdFx0dmFyIGxhc3RfZGF0ZSAgPSAoICdkeW5hbWljJyA9PSBwYXJhbXMuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUgKVxyXG5cdFx0XHRcdFx0XHRcdD8gcGFyYW1zWyAnZGF0ZXNfYXJyJyBdWyAocGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCAtIDEpIF1cclxuXHRcdFx0XHRcdFx0XHQ6ICggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCA+IDEgKSA/IHBhcmFtc1sgJ2RhdGVzX2FycicgXVsgMSBdIDogJyc7XHJcblxyXG5cdFx0XHRmaXJzdF9kYXRlID0galF1ZXJ5LmRhdGVwaWNrLmZvcm1hdERhdGUoICdkZCBNLCB5eScsIG5ldyBEYXRlKCBmaXJzdF9kYXRlICsgJ1QwMDowMDowMCcgKSApO1xyXG5cdFx0XHRsYXN0X2RhdGUgPSBqUXVlcnkuZGF0ZXBpY2suZm9ybWF0RGF0ZSggJ2RkIE0sIHl5JywgIG5ldyBEYXRlKCBsYXN0X2RhdGUgKyAnVDAwOjAwOjAwJyApICk7XHJcblxyXG5cclxuXHRcdFx0aWYgKCAnZHluYW1pYycgPT0gcGFyYW1zLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlICl7XHJcblx0XHRcdFx0aWYgKCAxID09IHBhcmFtcy5kYXRlc19jbGlja19udW0gKXtcclxuXHRcdFx0XHRcdGxhc3RfZGF0ZSA9ICdfX19fX19fX19fXydcclxuXHRcdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdFx0aWYgKCAnZmlyc3RfdGltZScgPT0galF1ZXJ5KCAnLndwYmNfYWp4X2F2YWlsYWJpbGl0eV9jb250YWluZXInICkuYXR0ciggJ3dwYmNfbG9hZGVkJyApICl7XHJcblx0XHRcdFx0XHRcdGpRdWVyeSggJy53cGJjX2FqeF9hdmFpbGFiaWxpdHlfY29udGFpbmVyJyApLmF0dHIoICd3cGJjX2xvYWRlZCcsICdkb25lJyApXHJcblx0XHRcdFx0XHRcdHdwYmNfYmxpbmtfZWxlbWVudCggJy53cGJjX3dpZGdldF9hdmFpbGFibGVfdW5hdmFpbGFibGUnLCAzLCAyMjAgKTtcclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHR9XHJcblx0XHRcdFx0bWVzc2FnZSA9IG1lc3NhZ2UucmVwbGFjZSggJ19EQVRFU18nLCAgICAnPC9zcGFuPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vKyAnPGRpdj4nICsgJ2Zyb20nICsgJzwvZGl2PidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgJzxzcGFuIGNsYXNzPVwid3BiY19iaWdfZGF0ZVwiPicgKyBmaXJzdF9kYXRlICsgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3Bhbj4nICsgJy0nICsgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3BhbiBjbGFzcz1cIndwYmNfYmlnX2RhdGVcIj4nICsgbGFzdF9kYXRlICsgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3Bhbj4nICk7XHJcblx0XHRcdH0gZWxzZSB7XHJcblx0XHRcdFx0Ly8gaWYgKCBwYXJhbXNbICdkYXRlc19hcnInIF0ubGVuZ3RoID4gMSApe1xyXG5cdFx0XHRcdC8vIFx0bGFzdF9kYXRlID0gJywgJyArIGxhc3RfZGF0ZTtcclxuXHRcdFx0XHQvLyBcdGxhc3RfZGF0ZSArPSAoIHBhcmFtc1sgJ2RhdGVzX2FycicgXS5sZW5ndGggPiAyICkgPyAnLCAuLi4nIDogJyc7XHJcblx0XHRcdFx0Ly8gfSBlbHNlIHtcclxuXHRcdFx0XHQvLyBcdGxhc3RfZGF0ZT0nJztcclxuXHRcdFx0XHQvLyB9XHJcblx0XHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1xyXG5cdFx0XHRcdGZvciggdmFyIGkgPSAwOyBpIDwgcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aDsgaSsrICl7XHJcblx0XHRcdFx0XHRkYXRlc19hcnIucHVzaCggIGpRdWVyeS5kYXRlcGljay5mb3JtYXREYXRlKCAnZGQgTSB5eScsICBuZXcgRGF0ZSggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdWyBpIF0gKyAnVDAwOjAwOjAwJyApICkgICk7XHJcblx0XHRcdFx0fVxyXG5cdFx0XHRcdGZpcnN0X2RhdGUgPSBkYXRlc19hcnIuam9pbiggJywgJyApO1xyXG5cdFx0XHRcdG1lc3NhZ2UgPSBtZXNzYWdlLnJlcGxhY2UoICdfREFURVNfJywgICAgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3BhbiBjbGFzcz1cIndwYmNfYmlnX2RhdGVcIj4nICsgZmlyc3RfZGF0ZSArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4+JyApO1xyXG5cdFx0XHR9XHJcblx0XHRcdG1lc3NhZ2UgPSBtZXNzYWdlLnJlcGxhY2UoICdfSFRNTF8nICwgJzwvc3Bhbj48c3BhbiBjbGFzcz1cIndwYmNfYmlnX3RleHRcIiBzdHlsZT1cImNvbG9yOicrY29sb3IrJztcIj4nKSArICc8c3Bhbj4nO1xyXG5cclxuXHRcdFx0Ly9tZXNzYWdlICs9ICcgPGRpdiBzdHlsZT1cIm1hcmdpbi1sZWZ0OiAxZW07XCI+JyArICcgQ2xpY2sgb24gQXBwbHkgYnV0dG9uIHRvIGFwcGx5IGF2YWlsYWJpbGl0eS4nICsgJzwvZGl2Pic7XHJcblxyXG5cdFx0XHRtZXNzYWdlID0gJzxkaXYgY2xhc3M9XCJ3cGJjX3Rvb2xiYXJfZGF0ZXNfaGludHNcIj4nICsgbWVzc2FnZSArICc8L2Rpdj4nO1xyXG5cclxuXHRcdFx0alF1ZXJ5KCAnLndwYmNfaGVscF90ZXh0JyApLmh0bWwoXHRtZXNzYWdlICk7XHJcblx0XHR9XHJcblxyXG5cdC8qKlxyXG5cdCAqICAgUGFyc2UgZGF0ZXMgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcblx0XHQvKipcclxuXHRcdCAqIEdldCBkYXRlcyBhcnJheSwgIGZyb20gY29tbWEgc2VwYXJhdGVkIGRhdGVzXHJcblx0XHQgKlxyXG5cdFx0ICogQHBhcmFtIHBhcmFtcyAgICAgICA9IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogJ2RhdGVzX3NlcGFyYXRvcicgPT4gJywgJywgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gRGF0ZXMgc2VwYXJhdG9yXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqICdkYXRlcycgICAgICAgICAgID0+ICcyMDIzLTA0LTA0LCAyMDIzLTA0LTA3LCAyMDIzLTA0LTA1JyAgICAgICAgIC8vIERhdGVzIGluICdZLW0tZCcgZm9ybWF0OiAnMjAyMy0wMS0zMSdcclxuXHRcdFx0XHRcdFx0XHRcdCB9XHJcblx0XHQgKlxyXG5cdFx0ICogQHJldHVybiBhcnJheSAgICAgID0gW1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMF0gPT4gMjAyMy0wNC0wNFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMV0gPT4gMjAyMy0wNC0wNVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMl0gPT4gMjAyMy0wNC0wNlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbM10gPT4gMjAyMy0wNC0wN1xyXG5cdFx0XHRcdFx0XHRcdFx0XVxyXG5cdFx0ICpcclxuXHRcdCAqIEV4YW1wbGUgIzE6ICB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfY29tbWFfc2VwYXJhdGVkX2pzKCAgeyAgJ2RhdGVzX3NlcGFyYXRvcicgOiAnLCAnLCAnZGF0ZXMnIDogJzIwMjMtMDQtMDQsIDIwMjMtMDQtMDcsIDIwMjMtMDQtMDUnICB9ICApO1xyXG5cdFx0ICovXHJcblx0XHRmdW5jdGlvbiB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfY29tbWFfc2VwYXJhdGVkX2pzKCBwYXJhbXMgKXtcclxuXHJcblx0XHRcdHZhciBkYXRlc19hcnIgPSBbXTtcclxuXHJcblx0XHRcdGlmICggJycgIT09IHBhcmFtc1sgJ2RhdGVzJyBdICl7XHJcblxyXG5cdFx0XHRcdGRhdGVzX2FyciA9IHBhcmFtc1sgJ2RhdGVzJyBdLnNwbGl0KCBwYXJhbXNbICdkYXRlc19zZXBhcmF0b3InIF0gKTtcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyLnNvcnQoKTtcclxuXHRcdFx0fVxyXG5cdFx0XHRyZXR1cm4gZGF0ZXNfYXJyO1xyXG5cdFx0fVxyXG5cclxuXHRcdC8qKlxyXG5cdFx0ICogR2V0IGRhdGVzIGFycmF5LCAgZnJvbSByYW5nZSBkYXlzIHNlbGVjdGlvblxyXG5cdFx0ICpcclxuXHRcdCAqIEBwYXJhbSBwYXJhbXMgICAgICAgPSAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiAnZGF0ZXNfc2VwYXJhdG9yJyA9PiAnIH4gJywgICAgICAgICAgICAgICAgICAgICAgICAgLy8gRGF0ZXMgc2VwYXJhdG9yXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqICdkYXRlcycgICAgICAgICAgID0+ICcyMDIzLTA0LTA0IH4gMjAyMy0wNC0wNycgICAgICAvLyBEYXRlcyBpbiAnWS1tLWQnIGZvcm1hdDogJzIwMjMtMDEtMzEnXHJcblx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdCAqXHJcblx0XHQgKiBAcmV0dXJuIGFycmF5ICAgICAgICA9IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzBdID0+IDIwMjMtMDQtMDRcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzFdID0+IDIwMjMtMDQtMDVcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzJdID0+IDIwMjMtMDQtMDZcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzNdID0+IDIwMjMtMDQtMDdcclxuXHRcdFx0XHRcdFx0XHRcdCAgXVxyXG5cdFx0ICpcclxuXHRcdCAqIEV4YW1wbGUgIzE6ICB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoICB7ICAnZGF0ZXNfc2VwYXJhdG9yJyA6ICcgfiAnLCAnZGF0ZXMnIDogJzIwMjMtMDQtMDQgfiAyMDIzLTA0LTA3JyAgfSAgKTtcclxuXHRcdCAqIEV4YW1wbGUgIzI6ICB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoICB7ICAnZGF0ZXNfc2VwYXJhdG9yJyA6ICcgLSAnLCAnZGF0ZXMnIDogJzIwMjMtMDQtMDQgLSAyMDIzLTA0LTA3JyAgfSAgKTtcclxuXHRcdCAqL1xyXG5cdFx0ZnVuY3Rpb24gd3BiY19nZXRfZGF0ZXNfYXJyX19mcm9tX2RhdGVzX3JhbmdlX2pzKCBwYXJhbXMgKXtcclxuXHJcblx0XHRcdHZhciBkYXRlc19hcnIgPSBbXTtcclxuXHJcblx0XHRcdGlmICggJycgIT09IHBhcmFtc1snZGF0ZXMnXSApIHtcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gcGFyYW1zWyAnZGF0ZXMnIF0uc3BsaXQoIHBhcmFtc1sgJ2RhdGVzX3NlcGFyYXRvcicgXSApO1xyXG5cdFx0XHRcdHZhciBjaGVja19pbl9kYXRlX3ltZCAgPSBkYXRlc19hcnJbMF07XHJcblx0XHRcdFx0dmFyIGNoZWNrX291dF9kYXRlX3ltZCA9IGRhdGVzX2FyclsxXTtcclxuXHJcblx0XHRcdFx0aWYgKCAoJycgIT09IGNoZWNrX2luX2RhdGVfeW1kKSAmJiAoJycgIT09IGNoZWNrX291dF9kYXRlX3ltZCkgKXtcclxuXHJcblx0XHRcdFx0XHRkYXRlc19hcnIgPSB3cGJjX2dldF9kYXRlc19hcnJheV9mcm9tX3N0YXJ0X2VuZF9kYXlzX2pzKCBjaGVja19pbl9kYXRlX3ltZCwgY2hlY2tfb3V0X2RhdGVfeW1kICk7XHJcblx0XHRcdFx0fVxyXG5cdFx0XHR9XHJcblx0XHRcdHJldHVybiBkYXRlc19hcnI7XHJcblx0XHR9XHJcblxyXG5cdFx0XHQvKipcclxuXHRcdFx0ICogR2V0IGRhdGVzIGFycmF5IGJhc2VkIG9uIHN0YXJ0IGFuZCBlbmQgZGF0ZXMuXHJcblx0XHRcdCAqXHJcblx0XHRcdCAqIEBwYXJhbSBzdHJpbmcgc1N0YXJ0RGF0ZSAtIHN0YXJ0IGRhdGU6IDIwMjMtMDQtMDlcclxuXHRcdFx0ICogQHBhcmFtIHN0cmluZyBzRW5kRGF0ZSAgIC0gZW5kIGRhdGU6ICAgMjAyMy0wNC0xMVxyXG5cdFx0XHQgKiBAcmV0dXJuIGFycmF5ICAgICAgICAgICAgIC0gWyBcIjIwMjMtMDQtMDlcIiwgXCIyMDIzLTA0LTEwXCIsIFwiMjAyMy0wNC0xMVwiIF1cclxuXHRcdFx0ICovXHJcblx0XHRcdGZ1bmN0aW9uIHdwYmNfZ2V0X2RhdGVzX2FycmF5X2Zyb21fc3RhcnRfZW5kX2RheXNfanMoIHNTdGFydERhdGUsIHNFbmREYXRlICl7XHJcblxyXG5cdFx0XHRcdHNTdGFydERhdGUgPSBuZXcgRGF0ZSggc1N0YXJ0RGF0ZSArICdUMDA6MDA6MDAnICk7XHJcblx0XHRcdFx0c0VuZERhdGUgPSBuZXcgRGF0ZSggc0VuZERhdGUgKyAnVDAwOjAwOjAwJyApO1xyXG5cclxuXHRcdFx0XHR2YXIgYURheXM9W107XHJcblxyXG5cdFx0XHRcdC8vIFN0YXJ0IHRoZSB2YXJpYWJsZSBvZmYgd2l0aCB0aGUgc3RhcnQgZGF0ZVxyXG5cdFx0XHRcdGFEYXlzLnB1c2goIHNTdGFydERhdGUuZ2V0VGltZSgpICk7XHJcblxyXG5cdFx0XHRcdC8vIFNldCBhICd0ZW1wJyB2YXJpYWJsZSwgc0N1cnJlbnREYXRlLCB3aXRoIHRoZSBzdGFydCBkYXRlIC0gYmVmb3JlIGJlZ2lubmluZyB0aGUgbG9vcFxyXG5cdFx0XHRcdHZhciBzQ3VycmVudERhdGUgPSBuZXcgRGF0ZSggc1N0YXJ0RGF0ZS5nZXRUaW1lKCkgKTtcclxuXHRcdFx0XHR2YXIgb25lX2RheV9kdXJhdGlvbiA9IDI0KjYwKjYwKjEwMDA7XHJcblxyXG5cdFx0XHRcdC8vIFdoaWxlIHRoZSBjdXJyZW50IGRhdGUgaXMgbGVzcyB0aGFuIHRoZSBlbmQgZGF0ZVxyXG5cdFx0XHRcdHdoaWxlKHNDdXJyZW50RGF0ZSA8IHNFbmREYXRlKXtcclxuXHRcdFx0XHRcdC8vIEFkZCBhIGRheSB0byB0aGUgY3VycmVudCBkYXRlIFwiKzEgZGF5XCJcclxuXHRcdFx0XHRcdHNDdXJyZW50RGF0ZS5zZXRUaW1lKCBzQ3VycmVudERhdGUuZ2V0VGltZSgpICsgb25lX2RheV9kdXJhdGlvbiApO1xyXG5cclxuXHRcdFx0XHRcdC8vIEFkZCB0aGlzIG5ldyBkYXkgdG8gdGhlIGFEYXlzIGFycmF5XHJcblx0XHRcdFx0XHRhRGF5cy5wdXNoKCBzQ3VycmVudERhdGUuZ2V0VGltZSgpICk7XHJcblx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRmb3IgKGxldCBpID0gMDsgaSA8IGFEYXlzLmxlbmd0aDsgaSsrKSB7XHJcblx0XHRcdFx0XHRhRGF5c1sgaSBdID0gbmV3IERhdGUoIGFEYXlzW2ldICk7XHJcblx0XHRcdFx0XHRhRGF5c1sgaSBdID0gYURheXNbIGkgXS5nZXRGdWxsWWVhcigpXHJcblx0XHRcdFx0XHRcdFx0XHQrICctJyArICgoIChhRGF5c1sgaSBdLmdldE1vbnRoKCkgKyAxKSA8IDEwKSA/ICcwJyA6ICcnKSArIChhRGF5c1sgaSBdLmdldE1vbnRoKCkgKyAxKVxyXG5cdFx0XHRcdFx0XHRcdFx0KyAnLScgKyAoKCAgICAgICAgYURheXNbIGkgXS5nZXREYXRlKCkgPCAxMCkgPyAnMCcgOiAnJykgKyAgYURheXNbIGkgXS5nZXREYXRlKCk7XHJcblx0XHRcdFx0fVxyXG5cdFx0XHRcdC8vIE9uY2UgdGhlIGxvb3AgaGFzIGZpbmlzaGVkLCByZXR1cm4gdGhlIGFycmF5IG9mIGRheXMuXHJcblx0XHRcdFx0cmV0dXJuIGFEYXlzO1xyXG5cdFx0XHR9XHJcblxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogICBUb29sdGlwcyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuXHQvKipcclxuXHQgKiBEZWZpbmUgc2hvd2luZyB0b29sdGlwLCAgd2hlbiAgbW91c2Ugb3ZlciBvbiAgU0VMRUNUQUJMRSAoYXZhaWxhYmxlLCBwZW5kaW5nLCBhcHByb3ZlZCwgcmVzb3VyY2UgdW5hdmFpbGFibGUpLCAgZGF5c1xyXG5cdCAqIENhbiBiZSBjYWxsZWQgZGlyZWN0bHkgIGZyb20gIGRhdGVwaWNrIGluaXQgZnVuY3Rpb24uXHJcblx0ICpcclxuXHQgKiBAcGFyYW0gdmFsdWVcclxuXHQgKiBAcGFyYW0gZGF0ZVxyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHJcblx0ICogQHBhcmFtIGRhdGVwaWNrX3RoaXNcclxuXHQgKiBAcmV0dXJucyB7Ym9vbGVhbn1cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2F2eV9fcHJlcGFyZV90b29sdGlwX19pbl9jYWxlbmRhciggdmFsdWUsIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIGRhdGVwaWNrX3RoaXMgKXtcclxuXHJcblx0XHRpZiAoIG51bGwgPT0gZGF0ZSApeyAgcmV0dXJuIGZhbHNlOyAgfVxyXG5cclxuXHRcdHZhciB0ZF9jbGFzcyA9ICggZGF0ZS5nZXRNb250aCgpICsgMSApICsgJy0nICsgZGF0ZS5nZXREYXRlKCkgKyAnLScgKyBkYXRlLmdldEZ1bGxZZWFyKCk7XHJcblxyXG5cdFx0dmFyIGpDZWxsID0galF1ZXJ5KCAnI2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCArICcgdGQuY2FsNGRhdGUtJyArIHRkX2NsYXNzICk7XHJcblxyXG5cdFx0d3BiY19hdnlfX3Nob3dfdG9vbHRpcF9fZm9yX2VsZW1lbnQoIGpDZWxsLCBjYWxlbmRhcl9wYXJhbXNfYXJyWyAncG9wb3Zlcl9oaW50cycgXSApO1xyXG5cdFx0cmV0dXJuIHRydWU7XHJcblx0fVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogRGVmaW5lIHRvb2x0aXAgIGZvciBzaG93aW5nIG9uIFVOQVZBSUxBQkxFIGRheXMgKHNlYXNvbiwgd2Vla2RheSwgdG9kYXlfZGVwZW5kcyB1bmF2YWlsYWJsZSlcclxuXHQgKlxyXG5cdCAqIEBwYXJhbSBqQ2VsbFx0XHRcdFx0XHRqUXVlcnkgb2Ygc3BlY2lmaWMgZGF5IGNlbGxcclxuXHQgKiBAcGFyYW0gcG9wb3Zlcl9oaW50c1x0XHQgICAgQXJyYXkgd2l0aCB0b29sdGlwIGhpbnQgdGV4dHNcdCA6IHsnc2Vhc29uX3VuYXZhaWxhYmxlJzonLi4uJywnd2Vla2RheXNfdW5hdmFpbGFibGUnOicuLi4nLCdiZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUnOicuLi4nLH1cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2F2eV9fc2hvd190b29sdGlwX19mb3JfZWxlbWVudCggakNlbGwsIHBvcG92ZXJfaGludHMgKXtcclxuXHJcblx0XHR2YXIgdG9vbHRpcF90aW1lID0gJyc7XHJcblxyXG5cdFx0aWYgKCBqQ2VsbC5oYXNDbGFzcyggJ3NlYXNvbl91bmF2YWlsYWJsZScgKSApe1xyXG5cdFx0XHR0b29sdGlwX3RpbWUgPSBwb3BvdmVyX2hpbnRzWyAnc2Vhc29uX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0fSBlbHNlIGlmICggakNlbGwuaGFzQ2xhc3MoICd3ZWVrZGF5c191bmF2YWlsYWJsZScgKSApe1xyXG5cdFx0XHR0b29sdGlwX3RpbWUgPSBwb3BvdmVyX2hpbnRzWyAnd2Vla2RheXNfdW5hdmFpbGFibGUnIF07XHJcblx0XHR9IGVsc2UgaWYgKCBqQ2VsbC5oYXNDbGFzcyggJ2JlZm9yZV9hZnRlcl91bmF2YWlsYWJsZScgKSApe1xyXG5cdFx0XHR0b29sdGlwX3RpbWUgPSBwb3BvdmVyX2hpbnRzWyAnYmVmb3JlX2FmdGVyX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0fSBlbHNlIGlmICggakNlbGwuaGFzQ2xhc3MoICdkYXRlMmFwcHJvdmUnICkgKXtcclxuXHJcblx0XHR9IGVsc2UgaWYgKCBqQ2VsbC5oYXNDbGFzcyggJ2RhdGVfYXBwcm92ZWQnICkgKXtcclxuXHJcblx0XHR9IGVsc2Uge1xyXG5cclxuXHRcdH1cclxuXHJcblx0XHRqQ2VsbC5hdHRyKCAnZGF0YS1jb250ZW50JywgdG9vbHRpcF90aW1lICk7XHJcblxyXG5cdFx0dmFyIHRkX2VsID0gakNlbGwuZ2V0KDApO1x0Ly9qUXVlcnkoICcjY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJyB0ZC5jYWw0ZGF0ZS0nICsgdGRfY2xhc3MgKS5nZXQoMCk7XHJcblxyXG5cdFx0aWYgKCAoIHVuZGVmaW5lZCA9PSB0ZF9lbC5fdGlwcHkgKSAmJiAoICcnICE9IHRvb2x0aXBfdGltZSApICl7XHJcblxyXG5cdFx0XHRcdHdwYmNfdGlwcHkoIHRkX2VsICwge1xyXG5cdFx0XHRcdFx0Y29udGVudCggcmVmZXJlbmNlICl7XHJcblxyXG5cdFx0XHRcdFx0XHR2YXIgcG9wb3Zlcl9jb250ZW50ID0gcmVmZXJlbmNlLmdldEF0dHJpYnV0ZSggJ2RhdGEtY29udGVudCcgKTtcclxuXHJcblx0XHRcdFx0XHRcdHJldHVybiAnPGRpdiBjbGFzcz1cInBvcG92ZXIgcG9wb3Zlcl90aXBweVwiPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0KyAnPGRpdiBjbGFzcz1cInBvcG92ZXItY29udGVudFwiPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQrIHBvcG92ZXJfY29udGVudFxyXG5cdFx0XHRcdFx0XHRcdFx0XHQrICc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0ICsgJzwvZGl2Pic7XHJcblx0XHRcdFx0XHR9LFxyXG5cdFx0XHRcdFx0YWxsb3dIVE1MICAgICAgICA6IHRydWUsXHJcblx0XHRcdFx0XHR0cmlnZ2VyXHRcdFx0IDogJ21vdXNlZW50ZXIgZm9jdXMnLFxyXG5cdFx0XHRcdFx0aW50ZXJhY3RpdmUgICAgICA6ICEgdHJ1ZSxcclxuXHRcdFx0XHRcdGhpZGVPbkNsaWNrICAgICAgOiB0cnVlLFxyXG5cdFx0XHRcdFx0aW50ZXJhY3RpdmVCb3JkZXI6IDEwLFxyXG5cdFx0XHRcdFx0bWF4V2lkdGggICAgICAgICA6IDU1MCxcclxuXHRcdFx0XHRcdHRoZW1lICAgICAgICAgICAgOiAnd3BiYy10aXBweS10aW1lcycsXHJcblx0XHRcdFx0XHRwbGFjZW1lbnQgICAgICAgIDogJ3RvcCcsXHJcblx0XHRcdFx0XHRkZWxheVx0XHRcdCA6IFs0MDAsIDBdLFx0XHRcdC8vRml4SW46IDkuNC4yLjJcclxuXHRcdFx0XHRcdGlnbm9yZUF0dHJpYnV0ZXMgOiB0cnVlLFxyXG5cdFx0XHRcdFx0dG91Y2hcdFx0XHQgOiB0cnVlLFx0XHRcdFx0Ly9bJ2hvbGQnLCA1MDBdLCAvLyA1MDBtcyBkZWxheVx0XHRcdC8vRml4SW46IDkuMi4xLjVcclxuXHRcdFx0XHRcdGFwcGVuZFRvOiAoKSA9PiBkb2N1bWVudC5ib2R5LFxyXG5cdFx0XHRcdH0pO1xyXG5cdFx0fVxyXG5cdH1cclxuXHJcblxyXG5cclxuXHJcblxyXG4vKipcclxuICogICBBamF4ICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBTZW5kIEFqYXggc2hvdyByZXF1ZXN0XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FqYXhfcmVxdWVzdCgpe1xyXG5cclxuY29uc29sZS5ncm91cENvbGxhcHNlZCggJ1dQQkNfQUpYX0FWQUlMQUJJTElUWScgKTsgY29uc29sZS5sb2coICcgPT0gQmVmb3JlIEFqYXggU2VuZCAtIHNlYXJjaF9nZXRfYWxsX3BhcmFtcygpID09ICcgLCB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuc2VhcmNoX2dldF9hbGxfcGFyYW1zKCkgKTtcclxuXHJcblx0d3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCgpO1xyXG5cclxuXHQvLyBTdGFydCBBamF4XHJcblx0alF1ZXJ5LnBvc3QoIHdwYmNfdXJsX2FqYXgsXHJcblx0XHRcdFx0e1xyXG5cdFx0XHRcdFx0YWN0aW9uICAgICAgICAgIDogJ1dQQkNfQUpYX0FWQUlMQUJJTElUWScsXHJcblx0XHRcdFx0XHR3cGJjX2FqeF91c2VyX2lkOiB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuZ2V0X3NlY3VyZV9wYXJhbSggJ3VzZXJfaWQnICksXHJcblx0XHRcdFx0XHRub25jZSAgICAgICAgICAgOiB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuZ2V0X3NlY3VyZV9wYXJhbSggJ25vbmNlJyApLFxyXG5cdFx0XHRcdFx0d3BiY19hanhfbG9jYWxlIDogd3BiY19hanhfYXZhaWxhYmlsaXR5LmdldF9zZWN1cmVfcGFyYW0oICdsb2NhbGUnICksXHJcblxyXG5cdFx0XHRcdFx0c2VhcmNoX3BhcmFtc1x0OiB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuc2VhcmNoX2dldF9hbGxfcGFyYW1zKClcclxuXHRcdFx0XHR9LFxyXG5cdFx0XHRcdC8qKlxyXG5cdFx0XHRcdCAqIFMgdSBjIGMgZSBzIHNcclxuXHRcdFx0XHQgKlxyXG5cdFx0XHRcdCAqIEBwYXJhbSByZXNwb25zZV9kYXRhXHRcdC1cdGl0cyBvYmplY3QgcmV0dXJuZWQgZnJvbSAgQWpheCAtIGNsYXNzLWxpdmUtc2VhcmNnLnBocFxyXG5cdFx0XHRcdCAqIEBwYXJhbSB0ZXh0U3RhdHVzXHRcdC1cdCdzdWNjZXNzJ1xyXG5cdFx0XHRcdCAqIEBwYXJhbSBqcVhIUlx0XHRcdFx0LVx0T2JqZWN0XHJcblx0XHRcdFx0ICovXHJcblx0XHRcdFx0ZnVuY3Rpb24gKCByZXNwb25zZV9kYXRhLCB0ZXh0U3RhdHVzLCBqcVhIUiApIHtcclxuXHJcbmNvbnNvbGUubG9nKCAnID09IFJlc3BvbnNlIFdQQkNfQUpYX0FWQUlMQUJJTElUWSA9PSAnLCByZXNwb25zZV9kYXRhICk7IGNvbnNvbGUuZ3JvdXBFbmQoKTtcclxuXHJcblx0XHRcdFx0XHQvLyBQcm9iYWJseSBFcnJvclxyXG5cdFx0XHRcdFx0aWYgKCAodHlwZW9mIHJlc3BvbnNlX2RhdGEgIT09ICdvYmplY3QnKSB8fCAocmVzcG9uc2VfZGF0YSA9PT0gbnVsbCkgKXtcclxuXHJcblx0XHRcdFx0XHRcdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fc2hvd19tZXNzYWdlKCByZXNwb25zZV9kYXRhICk7XHJcblxyXG5cdFx0XHRcdFx0XHRyZXR1cm47XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gUmVsb2FkIHBhZ2UsIGFmdGVyIGZpbHRlciB0b29sYmFyIGhhcyBiZWVuIHJlc2V0XHJcblx0XHRcdFx0XHRpZiAoICAgICAgICggICAgIHVuZGVmaW5lZCAhPSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdKVxyXG5cdFx0XHRcdFx0XHRcdCYmICggJ3Jlc2V0X2RvbmUnID09PSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdWyAnZG9fYWN0aW9uJyBdKVxyXG5cdFx0XHRcdFx0KXtcclxuXHRcdFx0XHRcdFx0bG9jYXRpb24ucmVsb2FkKCk7XHJcblx0XHRcdFx0XHRcdHJldHVybjtcclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHQvLyBTaG93IGxpc3RpbmdcclxuXHRcdFx0XHRcdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fcGFnZV9jb250ZW50X19zaG93KCByZXNwb25zZV9kYXRhWyAnYWp4X2RhdGEnIF0sIHJlc3BvbnNlX2RhdGFbICdhanhfc2VhcmNoX3BhcmFtcycgXSAsIHJlc3BvbnNlX2RhdGFbICdhanhfY2xlYW5lZF9wYXJhbXMnIF0gKTtcclxuXHJcblx0XHRcdFx0XHQvL3dwYmNfYWp4X2F2YWlsYWJpbGl0eV9fZGVmaW5lX3VpX2hvb2tzKCk7XHRcdFx0XHRcdFx0Ly8gUmVkZWZpbmUgSG9va3MsIGJlY2F1c2Ugd2Ugc2hvdyBuZXcgRE9NIGVsZW1lbnRzXHJcblx0XHRcdFx0XHRpZiAoICcnICE9IHJlc3BvbnNlX2RhdGFbICdhanhfZGF0YScgXVsgJ2FqeF9hZnRlcl9hY3Rpb25fbWVzc2FnZScgXS5yZXBsYWNlKCAvXFxuL2csIFwiPGJyIC8+XCIgKSApe1xyXG5cdFx0XHRcdFx0XHR3cGJjX2FkbWluX3Nob3dfbWVzc2FnZShcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIHJlc3BvbnNlX2RhdGFbICdhanhfZGF0YScgXVsgJ2FqeF9hZnRlcl9hY3Rpb25fbWVzc2FnZScgXS5yZXBsYWNlKCAvXFxuL2csIFwiPGJyIC8+XCIgKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgKCAnMScgPT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9kYXRhJyBdWyAnYWp4X2FmdGVyX2FjdGlvbl9yZXN1bHQnIF0gKSA/ICdzdWNjZXNzJyA6ICdlcnJvcidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsIDEwMDAwXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0d3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSgpO1xyXG5cdFx0XHRcdFx0Ly8gUmVtb3ZlIHNwaW4gaWNvbiBmcm9tICBidXR0b24gYW5kIEVuYWJsZSB0aGlzIGJ1dHRvbi5cclxuXHRcdFx0XHRcdHdwYmNfYnV0dG9uX19yZW1vdmVfc3BpbiggcmVzcG9uc2VfZGF0YVsgJ2FqeF9jbGVhbmVkX3BhcmFtcycgXVsgJ3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgXSApXHJcblxyXG5cdFx0XHRcdFx0alF1ZXJ5KCAnI2FqYXhfcmVzcG9uZCcgKS5odG1sKCByZXNwb25zZV9kYXRhICk7XHRcdC8vIEZvciBhYmlsaXR5IHRvIHNob3cgcmVzcG9uc2UsIGFkZCBzdWNoIERJViBlbGVtZW50IHRvIHBhZ2VcclxuXHRcdFx0XHR9XHJcblx0XHRcdCAgKS5mYWlsKCBmdW5jdGlvbiAoIGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93biApIHsgICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdBamF4X0Vycm9yJywganFYSFIsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duICk7IH1cclxuXHJcblx0XHRcdFx0XHR2YXIgZXJyb3JfbWVzc2FnZSA9ICc8c3Ryb25nPicgKyAnRXJyb3IhJyArICc8L3N0cm9uZz4gJyArIGVycm9yVGhyb3duIDtcclxuXHRcdFx0XHRcdGlmICgganFYSFIuc3RhdHVzICl7XHJcblx0XHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgKz0gJyAoPGI+JyArIGpxWEhSLnN0YXR1cyArICc8L2I+KSc7XHJcblx0XHRcdFx0XHRcdGlmICg0MDMgPT0ganFYSFIuc3RhdHVzICl7XHJcblx0XHRcdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSArPSAnIFByb2JhYmx5IG5vbmNlIGZvciB0aGlzIHBhZ2UgaGFzIGJlZW4gZXhwaXJlZC4gUGxlYXNlIDxhIGhyZWY9XCJqYXZhc2NyaXB0OnZvaWQoMClcIiBvbmNsaWNrPVwiamF2YXNjcmlwdDpsb2NhdGlvbi5yZWxvYWQoKTtcIj5yZWxvYWQgdGhlIHBhZ2U8L2E+Lic7XHJcblx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdGlmICgganFYSFIucmVzcG9uc2VUZXh0ICl7XHJcblx0XHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgKz0gJyAnICsganFYSFIucmVzcG9uc2VUZXh0O1xyXG5cdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSA9IGVycm9yX21lc3NhZ2UucmVwbGFjZSggL1xcbi9nLCBcIjxiciAvPlwiICk7XHJcblxyXG5cdFx0XHRcdFx0d3BiY19hanhfYXZhaWxhYmlsaXR5X19zaG93X21lc3NhZ2UoIGVycm9yX21lc3NhZ2UgKTtcclxuXHRcdFx0ICB9KVxyXG5cdCAgICAgICAgICAvLyAuZG9uZSggICBmdW5jdGlvbiAoIGRhdGEsIHRleHRTdGF0dXMsIGpxWEhSICkgeyAgIGlmICggd2luZG93LmNvbnNvbGUgJiYgd2luZG93LmNvbnNvbGUubG9nICl7IGNvbnNvbGUubG9nKCAnc2Vjb25kIHN1Y2Nlc3MnLCBkYXRhLCB0ZXh0U3RhdHVzLCBqcVhIUiApOyB9ICAgIH0pXHJcblx0XHRcdCAgLy8gLmFsd2F5cyggZnVuY3Rpb24gKCBkYXRhX2pxWEhSLCB0ZXh0U3RhdHVzLCBqcVhIUl9lcnJvclRocm93biApIHsgICBpZiAoIHdpbmRvdy5jb25zb2xlICYmIHdpbmRvdy5jb25zb2xlLmxvZyApeyBjb25zb2xlLmxvZyggJ2Fsd2F5cyBmaW5pc2hlZCcsIGRhdGFfanFYSFIsIHRleHRTdGF0dXMsIGpxWEhSX2Vycm9yVGhyb3duICk7IH0gICAgIH0pXHJcblx0XHRcdCAgOyAgLy8gRW5kIEFqYXhcclxuXHJcbn1cclxuXHJcblxyXG5cclxuLyoqXHJcbiAqICAgSCBvIG8gayBzICAtICBpdHMgQWN0aW9uL1RpbWVzIHdoZW4gbmVlZCB0byByZS1SZW5kZXIgVmlld3MgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU2VuZCBBamF4IFNlYXJjaCBSZXF1ZXN0IGFmdGVyIFVwZGF0aW5nIHNlYXJjaCByZXF1ZXN0IHBhcmFtZXRlcnNcclxuICpcclxuICogQHBhcmFtIHBhcmFtc19hcnJcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fc2VuZF9yZXF1ZXN0X3dpdGhfcGFyYW1zICggcGFyYW1zX2FyciApe1xyXG5cclxuXHQvLyBEZWZpbmUgZGlmZmVyZW50IFNlYXJjaCAgcGFyYW1ldGVycyBmb3IgcmVxdWVzdFxyXG5cdF8uZWFjaCggcGFyYW1zX2FyciwgZnVuY3Rpb24gKCBwX3ZhbCwgcF9rZXksIHBfZGF0YSApIHtcclxuXHRcdC8vY29uc29sZS5sb2coICdSZXF1ZXN0IGZvcjogJywgcF9rZXksIHBfdmFsICk7XHJcblx0XHR3cGJjX2FqeF9hdmFpbGFiaWxpdHkuc2VhcmNoX3NldF9wYXJhbSggcF9rZXksIHBfdmFsICk7XHJcblx0fSk7XHJcblxyXG5cdC8vIFNlbmQgQWpheCBSZXF1ZXN0XHJcblx0d3BiY19hanhfYXZhaWxhYmlsaXR5X19hamF4X3JlcXVlc3QoKTtcclxufVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogU2VhcmNoIHJlcXVlc3QgZm9yIFwiUGFnZSBOdW1iZXJcIlxyXG5cdCAqIEBwYXJhbSBwYWdlX251bWJlclx0aW50XHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19hanhfYXZhaWxhYmlsaXR5X19wYWdpbmF0aW9uX2NsaWNrKCBwYWdlX251bWJlciApe1xyXG5cclxuXHRcdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fc2VuZF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncGFnZV9udW0nOiBwYWdlX251bWJlclxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHR9XHJcblxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIFNob3cgLyBIaWRlIENvbnRlbnQgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqICBTaG93IExpc3RpbmcgQ29udGVudCBcdC0gXHRTZW5kaW5nIEFqYXggUmVxdWVzdFx0LVx0d2l0aCBwYXJhbWV0ZXJzIHRoYXQgIHdlIGVhcmx5ICBkZWZpbmVkXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FjdHVhbF9jb250ZW50X19zaG93KCl7XHJcblxyXG5cdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fYWpheF9yZXF1ZXN0KCk7XHRcdFx0Ly8gU2VuZCBBamF4IFJlcXVlc3RcdC1cdHdpdGggcGFyYW1ldGVycyB0aGF0ICB3ZSBlYXJseSAgZGVmaW5lZCBpbiBcIndwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZ1wiIE9iai5cclxufVxyXG5cclxuLyoqXHJcbiAqIEhpZGUgTGlzdGluZyBDb250ZW50XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FjdHVhbF9jb250ZW50X19oaWRlKCl7XHJcblxyXG5cdGpRdWVyeSggIHdwYmNfYWp4X2F2YWlsYWJpbGl0eS5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSAgKS5odG1sKCAnJyApO1xyXG59XHJcblxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIE0gZSBzIHMgYSBnIGUgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIFNob3cganVzdCBtZXNzYWdlIGluc3RlYWQgb2YgY29udGVudFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYXZhaWxhYmlsaXR5X19zaG93X21lc3NhZ2UoIG1lc3NhZ2UgKXtcclxuXHJcblx0d3BiY19hanhfYXZhaWxhYmlsaXR5X19hY3R1YWxfY29udGVudF9faGlkZSgpO1xyXG5cclxuXHRqUXVlcnkoIHdwYmNfYWp4X2F2YWlsYWJpbGl0eS5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSApLmh0bWwoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8ZGl2IGNsYXNzPVwid3BiYy1zZXR0aW5ncy1ub3RpY2Ugbm90aWNlLXdhcm5pbmdcIiBzdHlsZT1cInRleHQtYWxpZ246bGVmdFwiPicgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdG1lc3NhZ2UgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPC9kaXY+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcbn1cclxuXHJcblxyXG5cclxuLyoqXHJcbiAqICAgU3VwcG9ydCBGdW5jdGlvbnMgLSBTcGluIEljb24gaW4gQnV0dG9ucyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU3BpbiBidXR0b24gaW4gRmlsdGVyIHRvb2xiYXIgIC0gIFN0YXJ0XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2F2YWlsYWJpbGl0eV9yZWxvYWRfYnV0dG9uX19zcGluX3N0YXJ0KCl7XHJcblx0alF1ZXJ5KCAnI3dwYmNfYXZhaWxhYmlsaXR5X3JlbG9hZF9idXR0b24gLm1lbnVfaWNvbi53cGJjX3NwaW4nKS5yZW1vdmVDbGFzcyggJ3dwYmNfYW5pbWF0aW9uX3BhdXNlJyApO1xyXG59XHJcblxyXG4vKipcclxuICogU3BpbiBidXR0b24gaW4gRmlsdGVyIHRvb2xiYXIgIC0gIFBhdXNlXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2F2YWlsYWJpbGl0eV9yZWxvYWRfYnV0dG9uX19zcGluX3BhdXNlKCl7XHJcblx0alF1ZXJ5KCAnI3dwYmNfYXZhaWxhYmlsaXR5X3JlbG9hZF9idXR0b24gLm1lbnVfaWNvbi53cGJjX3NwaW4nICkuYWRkQ2xhc3MoICd3cGJjX2FuaW1hdGlvbl9wYXVzZScgKTtcclxufVxyXG5cclxuLyoqXHJcbiAqIFNwaW4gYnV0dG9uIGluIEZpbHRlciB0b29sYmFyICAtICBpcyBTcGlubmluZyA/XHJcbiAqXHJcbiAqIEByZXR1cm5zIHtib29sZWFufVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbl9faXNfc3Bpbigpe1xyXG4gICAgaWYgKCBqUXVlcnkoICcjd3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbiAubWVudV9pY29uLndwYmNfc3BpbicgKS5oYXNDbGFzcyggJ3dwYmNfYW5pbWF0aW9uX3BhdXNlJyApICl7XHJcblx0XHRyZXR1cm4gdHJ1ZTtcclxuXHR9IGVsc2Uge1xyXG5cdFx0cmV0dXJuIGZhbHNlO1xyXG5cdH1cclxufVxyXG4iXSwibWFwcGluZ3MiOiJBQUFBLFlBQVk7O0FBRVo7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUpBLFNBQUFBLFFBQUFDLEdBQUEsc0NBQUFELE9BQUEsd0JBQUFFLE1BQUEsdUJBQUFBLE1BQUEsQ0FBQUMsUUFBQSxhQUFBRixHQUFBLGtCQUFBQSxHQUFBLGdCQUFBQSxHQUFBLFdBQUFBLEdBQUEseUJBQUFDLE1BQUEsSUFBQUQsR0FBQSxDQUFBRyxXQUFBLEtBQUFGLE1BQUEsSUFBQUQsR0FBQSxLQUFBQyxNQUFBLENBQUFHLFNBQUEscUJBQUFKLEdBQUEsS0FBQUQsT0FBQSxDQUFBQyxHQUFBO0FBTUEsSUFBSUsscUJBQXFCLEdBQUksVUFBV0wsR0FBRyxFQUFFTSxDQUFDLEVBQUU7RUFFL0M7RUFDQSxJQUFJQyxRQUFRLEdBQUdQLEdBQUcsQ0FBQ1EsWUFBWSxHQUFHUixHQUFHLENBQUNRLFlBQVksSUFBSTtJQUN4Q0MsT0FBTyxFQUFFLENBQUM7SUFDVkMsS0FBSyxFQUFJLEVBQUU7SUFDWEMsTUFBTSxFQUFHO0VBQ1IsQ0FBQztFQUVoQlgsR0FBRyxDQUFDWSxnQkFBZ0IsR0FBRyxVQUFXQyxTQUFTLEVBQUVDLFNBQVMsRUFBRztJQUN4RFAsUUFBUSxDQUFFTSxTQUFTLENBQUUsR0FBR0Ms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
