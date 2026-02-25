<?php /**
 * @version 1.0
 * @package Booking Calendar 
 * @category Booking Form Settings
 * @author wpdevelop
 *
 * @web-site https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com 
 * 
 * @modified 2016-03-23
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

require_once( WPBC_PLUGIN_DIR . '/includes/page-form-simple/generator-timeslots.php' );         // Timeslots Generator


/**
	 * Show Content
 *  Update Content
 *  Define Slug
 *  Define where to show
 */
class WPBC_Page_SettingsFormFieldsFree extends WPBC_Page_Structure {
    
    /** Need define some filters */
    public function __construct() {

        // Get booking form  in real  HTML
        add_bk_filter('wpbc_get_free_booking_form',         array( $this, 'get_form_in__html' ) );        
        
        // Get content of booking form show in shortcodes
        add_bk_filter('wpbc_get_free_booking_show_form',        array( $this, 'get_form_show_in__shortcodes' ) );
        // Get booking form  in Shortcodes 
        add_bk_filter('wpbc_get_free_booking_form_shortcodes',  array( $this, 'get_form_in__shortcodes' ) );
        
        
        /**
	 	 * We need to  update these fields after  usual update process :
         * 'booking_form'
         * 'booking_form_show'
         * 'booking_form_visual'
        */
        add_bk_action( 'wpbc_other_versions_activation',   array( $this, 'activate'   ) );      // Activate
        add_bk_action( 'wpbc_other_versions_deactivation', array( $this, 'deactivate' ) );      // Deactivate

        parent::__construct();
    }

    public function in_page() {
        return 'wpbc-settings';
    }

    public function tabs() {
        
        $tabs = array();
                
        $tabs[ 'form' ] = array(
                              'title'     => __( 'Booking Form', 'booking')             // Title of TAB
                            , 'page_title'=> __( 'Fields Settings', 'booking')      // Title of Page    
                            , 'hint'      => __( 'Customize fields in booking form', 'booking')               // Hint
                            //, 'link'      => ''                                 // Can be skiped,  then generated link based on Page and Tab tags. Or can  be extenral link
                            //, 'position'  => ''                                 // 'left'  ||  'right'  ||  ''
                            //, 'css_classes'=> ''                                // CSS class(es)
                            //, 'icon'      => ''                                 // Icon - link to the real PNG img
                            , 'font_icon' => 'wpbc_icn_dashboard _customize dashboard rtt draw'         // CSS definition  of forn Icon
                            //, 'default'   => false                               // Is this tab activated by default or not: true || false. 
                            //, 'disabled'  => false                              // Is this tab disbaled: true || false. 
                            //, 'hided'     => false                              // Is this tab hided: true || false. 
                            , 'subtabs'   => array()   
                    );

        if ( ! class_exists( 'wpdev_bk_personal' ) )																	//FixIn: 8.1.1.12
        	$tabs[ 'upgrade-link' ] = array(
                              'title' => __('Free vs Pro','booking')                // Title of TAB
                            , 'hint'  => __('Upgrade to higher versions', 'booking')              // Hint    
                            //, 'page_title' => __('Upgrade', 'booking')        // Title of Page    
                            , 'link' => 'https://wpbookingcalendar.com/features/'                    // Can be skiped,  then generated link based on Page and Tab tags. Or can  be extenral link
                            , 'position' => 'right'                             // 'left'  ||  'right'  ||  ''
                            //, 'css_classes' => ''                             // CSS class(es)
                            //, 'icon' => ''                                    // Icon - link to the real PNG img
                            , 'font_icon' => 'wpbc_icn_redeem'// CSS definition  of forn Icon
                            //, 'default' => false                              // Is this tab activated by default or not: true || false. 
                            //, 'subtabs' => array()            
        );
        
        return $tabs;
    }

    public function content() {

		$this->css();

		// Define Notices Section and show some static messages, if needed
        do_action( 'wpbc_hook_settings_page_header', 'form_field_free_settings');
        
        if ( ! wpbc_is_mu_user_can_be_here('activated_user') ) return false;    	// Check if MU user activated, otherwise show Warning message.
        //if ( ! wpbc_is_mu_user_can_be_here('only_super_admin') ) return false;  	// User is not Super admin, so exit.  Basically its was already checked at the bottom of the PHP file, just in case.

        // $this->settings_api();                                               // Define all fields and get values from DB

		// -------------------------------------------------------------------------------------------------------------
        //  Submit
        // -------------------------------------------------------------------------------------------------------------
        $submit_form_name = 'wpbc_form_field_free';                             // Define form name
        if ( isset( $_POST['is_form_sbmitted_'. $submit_form_name ] ) ) {
            // Nonce checking    {Return false if invalid, 1 if generated between, 0-12 hours ago, 2 if generated between 12-24 hours ago. }
            $nonce_gen_time = check_admin_referer( 'wpbc_settings_page_' . $submit_form_name  );  // Its stop show anything on submitting, if it's not refear to the original page

            // Save Changes 
            $this->update();
        }                


        echo '<span class="wpdevelop">';
        	wpbc_js_for_bookings_page();		// JavaScript: Tooltips, Popover, Datepick (js & css) //////////////////
        echo '</span>';


		?><form  name="<?php echo $submit_form_name; ?>" id="<?php echo $submit_form_name; ?>" action="" method="post"><?php

		   // N o n c e   field, and key for checking   S u b m i t
		   wp_nonce_field( 'wpbc_settings_page_' . $submit_form_name );

			?><input type="hidden" name="is_form_sbmitted_<?php echo $submit_form_name; ?>" id="is_form_sbmitted_<?php echo $submit_form_name; ?>" value="1" /><?php
			?><input type="hidden" name="form_visible_section" id="form_visible_section" value="" /><?php

			?><input type="hidden" name="reset_to_default_form" id="reset_to_default_form" value="" /><?php
			?><input type="hidden" name="booking_form_structure_type" id="booking_form_structure_type" value="<?php echo get_bk_option( 'booking_form_structure_type' ); ?>" /><?php

		// -------------------------------------------------------------------------------------------------------------
        //  Content
		// -------------------------------------------------------------------------------------------------------------
        ?>
        <div class="clear"></div>
		<div class="wpbc_settings_flex_container">

			<div class="wpbc_settings_flex_container_left">

				<div class="wpbc_settings_navigation_column">
					<div id="wpbc_settings__form_fields_tab" class="wpbc_settings_navigation_item  wpbc_settings_navigation_item_active">
						<a onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_settings__form_fields_metabox', '.wpbc_container_hide__on_left_nav_click' );" href="javascript:void(0);">
							<span><?php _e( 'Form Fields', 'booking' ) ?></span>
							<i class="wpbc_set_nav__right_icon menu_icon icon-1x wpbc_icn_format_align_left"></i>
						</a>
					</div>
					<div id="wpbc_settings__form_layout_tab" class="wpbc_settings_navigation_item">
						<a onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_settings__form_layout_metabox', '.wpbc_container_hide__on_left_nav_click' );" href="javascript:void(0);">
							<span><?php _e( 'Form Layout', 'booking' ) ?></span>
							<i class="wpbc_set_nav__right_icon menu_icon icon-1x wpbc_icn_dashboard"></i>
						</a>
					</div><?php

					$is_can = apply_bk_filter( 'multiuser_is_user_can_be_here', true, 'only_super_admin' );
					if ( $is_can ) {

					?><div id="wpbc_settings__form_theme_tab" class="wpbc_settings_navigation_item">
						<a onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_settings__form_theme_metabox', '.wpbc_container_hide__on_left_nav_click' );" href="javascript:void(0);">
							<span><?php _e( 'Color Theme', 'booking' ) ?></span>
							<i class="wpbc_set_nav__right_icon menu_icon icon-1x wpbc_icn_format_color_text 00wpbc-bi-pencil"></i>
						</a>
					</div>
					<div id="wpbc_settings__form_captcha_tab" class="wpbc_settings_navigation_item wpbc_navigation_top_border">
						<a onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_settings__form_captcha_metabox', '.wpbc_container_hide__on_left_nav_click' );" href="javascript:void(0);">
							<span><?php _e( 'CAPTCHA', 'booking' ) ?></span>
							<i class="wpbc_set_nav__right_icon menu_icon icon-1x wpbc-bi-toggle2-off <?php echo ( get_bk_option( 'booking_is_use_captcha' ) === 'On' ) ? 'wpbc_set_nav__icon_on' : ''; ?>"></i>
						</a>
					</div>
					<div id="wpbc_settings__form_options_tab" class="wpbc_settings_navigation_item">
						<a onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_settings__form_options_metabox', '.wpbc_container_hide__on_left_nav_click' );" href="javascript:void(0);">
							<span><?php _e( 'Options', 'booking' ) ?></span>
							<i class="wpbc_set_nav__right_icon menu_icon icon-1x wpbc_icn_tune"></i>
						</a>
					</div><?php

					}
					?><div id="wpbc_settings__form_options_tab" class="wpbc_settings_navigation_item wpbc_navigation_top_border">
						<a onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_settings__form_preview_metabox', '.wpbc_container_hide__on_left_nav_click000' );" href="javascript:void(0);">
							<span><?php _e( 'Preview', 'booking' ) ?></span>
							<i class="wpbc_set_nav__right_icon menu_icon icon-1x wpbc-bi-eye"></i>
						</a>
					</div>
				</div>
			</div>
			<div class="wpbc_settings_flex_container_right">
				<span class="metabox-holder"><?php

					?><div id="wpbc_settings__form_fields_metabox" class="wpbc_container_hide__on_left_nav_click"><?php

						$this->content_section__form_fields();

					?></div><?php

					wpbc_open_meta_box_section( 'wpbc_settings__form_layout', __('Form Layout', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false, 'css_class'=>'postbox wpbc_container_hide__on_left_nav_click' ) );
						$this->content_section__form_layout();
					wpbc_close_meta_box_section();

					$is_can = apply_bk_filter( 'multiuser_is_user_can_be_here', true, 'only_super_admin' );
					if ( $is_can ) {
						wpbc_open_meta_box_section( 'wpbc_settings__form_theme', __('Color Theme', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false, 'css_class'=>'postbox wpbc_container_hide__on_left_nav_click' ) );

							$this->content_section__form_color_theme();

							?><table class="form-table"><tbody><tr valign="top" class="wpbc_tr_booking_form_skins"><th scope="row"></th><td><fieldset><?php

								?><div class="wpbc_widget wpbc_widget_color_skins">
									<div class="wpbc_widget_content wpbc_ajx_toolbar wpbc_no_background" style="margin:0 0 20px;">
										<div class="ui_container" ><?php

											$this->content_section__calendar_skin();

											$this->content_section__time_picker_skin();

								?></div></div></div><?php

							?></fieldset></td></tr></tbody></table><?php

						wpbc_close_meta_box_section();

						wpbc_open_meta_box_section( 'wpbc_settings__form_captcha', __('CAPTCHA', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false, 'css_class'=>'postbox wpbc_container_hide__on_left_nav_click' ) );
							$this->content_section__form_captcha();
						wpbc_close_meta_box_section();

						wpbc_open_meta_box_section( 'wpbc_settings__form_options', __('Form Options', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false, 'css_class'=>'postbox wpbc_container_hide__on_left_nav_click' ) );
							$this->content_section__form_options();
						wpbc_close_meta_box_section();
					}
				?>
				</span>
			</div>
		</div>
    	<?php

		// -------------------------------------------------------------------------------------------------------------
		// Save button
		// -------------------------------------------------------------------------------------------------------------
		?>
		<div class="clear" style="height:5px;"></div>
		<div class="wpbc_settings_flex_container">
			<div class="wpbc_settings_flex_container_left"></div>
			<div class="wpbc_settings_flex_container_right">
				<input type="submit" value="<?php _e('Save Changes','booking'); ?>" class="button button-primary wpbc_submit_button wpbc_submit_button_trigger" />
			</div>
		</div><?php


		?></form><?php

		wpbc_show_preview__form();

		// Define templates and write  JavaScript for Timeslots in ../generator-timeslots.php
        do_action( 'wpbc_hook_settings_page_footer', 'form_field_free_settings' );

    }


		public function content_section__form_fields(){

			if(1){

				$default_options_values = wpbc_get_default_options();

				// Get Form  Fields ////////////////////////////////////////////////////
				$booking_form_structure = $this->get_booking_form_structure_for_visual();   // Get saved or Import form  fields from  OLD Free version
				$booking_form_structure = serialize( $booking_form_structure );


				////////////////////////////////////////////////////////////////////////
				// Toolbar /////////////////////////////////////////////////////////////
				?><div id="wpbc_settings__form_fields__toolbar"><?php
				wpbc_bs_toolbar_sub_html_container_start();

				?><span class="wpdevelop"><div class="visibility_container clearfix-height" style="display:block;"><?php

					$this->toolbar_select_field();                                      // Select Field Type

					$this->toolbar_reset_booking_form();                                // Reset button

					if ( function_exists( 'toolbar_use_simple_booking_form' ) ) {
						toolbar_use_simple_booking_form();
					}

					//$save_button = array( 'title' => __('Save Changes', 'booking'), 'form' => 'wpbc_form_field_free' );
					//$this->toolbar_save_button( $save_button );                         // Save Button
				?></div></span><?php
				?></div><?php
				wpbc_bs_toolbar_sub_html_container_end();

				if(1){

					////////////////////////////////////////////////////////////////////////
					// Fields Generator ////////////////////////////////////////////////////
					?>
					<span class="metabox_wpbc_form_field_free_generator" style="display:none;">
						<div class="clear"></div>
						<span class="metabox-holder">

							<div class="wpbc_settings_row " >
								<?php
								wpbc_open_meta_box_section( 'wpbc_form_field_free_generator', __('Form Field Configuration', 'booking') );

								$this->fields_generator_section();

								wpbc_close_meta_box_section();
								?>
							</div>
						</span>
					</span>
					<?php
				}


				////////////////////////////////////////////////////////////////////////
				// Content  ////////////////////////////////////////////////////////////
				?>
				<div class="clear" style="margin-bottom:10px;"></div>
				<div class="metabox-holder"><?php
//FixIn: 10.1.2.2		<div class="metabox-holder" style="display: flex;flex-flow: row wrap;justify-content: space-between;align-items: flex-start;">
						$this->show_booking_form_fields_table( $booking_form_structure );
						// $this->show_booking_form_fields_table( $booking_form_structure );	 //FixIn: 10.1.2.2

						?><div class="clear" style="height:10px;"></div><?php

						if ( 1 ) {        //FixIn: 8.8.1.14

						?>
						<div class="wpdevelop" style="display:flex;flex-flow:row wrap;justify-content: flex-start;align-items: flex-start;">
							<table class="form-table" style="flex:1 1 50%; margin:0;"><?php

							$field_name = 'booking_send_button_title';
							$form_title_value = ( empty( get_bk_option( 'booking_send_button_title' ) ) ? $default_options_values['booking_send_button_title'] : get_bk_option( 'booking_send_button_title' ) );

							WPBC_Settings_API::field_text_row_static(   $field_name . '_name'
																		, array(
																				'type'              => 'text'
																				, 'title'             => __( 'Title of send button' ,'booking' )
																				, 'disabled'          => false
																				, 'class'             => ''
																				, 'css'               => 'width:100%'
																				, 'placeholder'       =>  __( 'Send', 'booking' )
																				, 'description'       => sprintf(__('Enter %stitle of submit button%s in the booking form' ,'booking'),'<b>','</b>')
																				, 'group'             => 'form'
																				, 'tr_class'          => 'wpbc_send_button_title'
																				, 'only_field'        => false
																				, 'description_tag'   => 'p'
																				, 'value' 			  => $form_title_value             // 'Send'
																				, 'attr'              => array()
																		)
																		, true
																	);
							?></table>

									<?php $my_close_open_alert_id = 'bk_alert_timessettings_form_in_free'; ?>
									<div    class="wpbc-settings-notice notice-info  <?php if ( '1' == get_user_option( 'booking_win_' . $my_close_open_alert_id ) ) echo 'hide'; ?>"
											id="<?php echo $my_close_open_alert_id; ?>"
											style="padding: 5px 1em; flex:0 1 48%; margin:5px 0 0 2%;">
										<a  data-original-title="Don't show the message anymore"
											class="close tooltip_left"
											style="margin-top: 4px;" rel="tooltip" data-dismiss="alert"
											href="javascript:void(0)"
											onclick="javascript:wpbc_verify_window_opening(<?php echo wpbc_get_current_user_id(); ?>, '<?php echo $my_close_open_alert_id; ?>');wpbc_hide_window('<?php echo $my_close_open_alert_id; ?>' );"
										>&times;</a>
										<strong class="alert-heading"><?php _e( 'Note', 'booking' ); ?>!</strong>
											<?php printf( __( 'You can add %sTime Slots%s to booking form, by activating and configure %sTime Slots%s field in booking form or by adding this field on toolbar.', 'booking' ),
												'<strong>', '</strong>',
												'<strong>', '</strong>'
											); ?>
									</div>

						</div><?php

						}

						?>
				</div>
				<?php

			}

		}


		public function content_section__form_layout(){

			$default_options_values = wpbc_get_default_options();
			?><table class="form-table"><?php

			$field_value_or_default = ( empty( get_bk_option( 'booking_form_structure_type' ) ) ? $default_options_values['booking_form_structure_type'] : get_bk_option( 'booking_form_structure_type' ) );

			$field_options = array(                       // Associated array  of titles and values
                                                          'optgroup_sf_s' => array(
                                                                        'optgroup' => true
                                                                        , 'close'  => false
                                                                        , 'title'  => '&nbsp;' . __('Standard Forms' ,'booking')
                                                                    )
                                                        , 'vertical' => array(
                                                                        'title' => __('Form under calendar', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
                                                        , 'form_right' => array(
                                                                        'title' => __('Form at right side of calendar', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
                                                        , 'form_center' => array(
                                                                        'title' => __('Form and calendar are centered', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )

                                                        , 'optgroup_sf_e' => array( 'optgroup' => true, 'close'  => true )
//														, 'optgroup_help_tips_s' => array(
//                                                                        'optgroup' => true
//                                                                        , 'close'  => false
//                                                                        , 'title'  => '&nbsp;' . __('Advanced Forms' ,'booking')
//                                                                    )
//                                                        , 'form_2_columns' => array(
//                                                                        'title' => __('2 Columns', 'booking')
//                                                                        , 'id' => ''
//                                                                        , 'name' => ''
//                                                                        , 'style' => ''
//                                                                        , 'class' => ''
//                                                                        , 'disabled' => true
//                                                                        , 'selected' => false
//                                                                        , 'attr' => array()
//                                                                    )
//														, 'optgroup_help_tips_e' => array( 'optgroup' => true, 'close'  => true )

                                                    );

			WPBC_Settings_API::field_select_row_static( 'booking_form_structure_type__select'
									, array(
											  'type'              => 'select'
											, 'title'             =>  __('Form Layout', 'booking')
											, 'label'             => ''
											, 'disabled'          => false
											, 'disabled_options'  => array()
											, 'multiple'          => false

											, 'description'       => ''
											, 'description_tag'   => 'span'

											, 'group'             => 'form_layout'
											, 'tr_class'          => ''
											, 'class'             => ''
											, 'css'               => 'width:auto;max-width:100%;'
											, 'only_field'        => false
											, 'attr'              => array()

											, 'value'             => $field_value_or_default
											, 'options'           => $field_options
										)
							);


				$id = 'booking_form_layout';
				?><tr class="wpbc_tr_<?php echo $id; ?>_size__select">
					<th scope="row" style="vertical-align: middle;">
						<label for="wpbc_booking_width" class="wpbc-form-text"><?php  _e('Form Width:', 'booking'); ?></label>
					</th>
					<td class=""><fieldset style="display: flex;flex-flow: row wrap;justify-content: flex-start;align-items: center;"><?php

						$field_name = 	$id . '_width';			// 100
						WPBC_Settings_API::field_text_row_static( $field_name
															, array(
																	  'type'              => 'text'
																	, 'placeholder'       => '100'
																	, 'class'             => ''
																	, 'css'               => 'width:5em;'
																	, 'only_field'        => true
																	, 'attr'              => array()
																	, 'value' => ( empty( get_bk_option( $field_name ) ) ? $default_options_values[ $field_name ] : get_bk_option( $field_name ) )
																)
											);
						$field_name = 	$id . '_width_px_pr';	// %
						WPBC_Settings_API::field_select_row_static(   $field_name
																	, array(
																			  'type'              => 'select'
																			, 'multiple'          => false
																			, 'class'             => ''
																			, 'css'               => 'width:4em;'
																			, 'only_field'        => true
																			, 'attr'              => array()
																			, 'options'           => array( 'px' => 'px', '%'  => '%' )
																			, 'value' => ( empty( get_bk_option( $field_name ) ) ? $default_options_values[ $field_name ] : get_bk_option( $field_name ) )
																		)
										);
						?><span class="description"> <?php _e('Set width of calendar' ,'booking'); ?></span></fieldset></td>
				</tr><?php


			?></table>
			<script type="text/javascript">
					jQuery('select[name=\"booking_form_structure_type__select\"]').on( 'change', function(){
						var selected_val = jQuery('#booking_form_structure_type__select').val();
						jQuery('#booking_form_structure_type').val( selected_val );
					} );
			</script><?php
		}


		public function content_section__form_color_theme(){

			$default_options_values = wpbc_get_default_options();

			?><table class="form-table"><?php

				$field_name = 'booking_form_theme';
				$field_params = array(
										  'type'          => 'select'
										, 'default'     => $default_options_values[ $field_name ]   					//'Off'
										, 'title'       => __('Color Theme' ,'booking')
										, 'description' => __('Select a color theme for your booking form that matches the look of your website.' ,'booking')
//														   . '<div class="wpbc-general-settings-notice wpbc-settings-notice notice-info">'
//														   .    __('When you select a color theme, it also change the calendar and time-slot picker skins to match your choice. Customize these options separately as needed.' ,'booking')
//														   .'</div>'
										, 'options' => array(
																''                  => __( 'Light', 'booking' ),
																'wpbc_theme_dark_1' => __( 'Dark', 'booking' )
															)
										, 'group'       => 'form'
										, 'value'       => ( empty( get_bk_option( $field_name ) ) ? $default_options_values[ $field_name ] : get_bk_option( $field_name ) ),
										'attr' => array( 'onchange' => "javascript: wpbc_on_change__form_color_theme( this );" )

				);

				WPBC_Settings_API::field_select_row_static( $field_name, $field_params );

 			?></table><?php
			?><script type="text/javascript">
				function wpbc_on_change__form_color_theme( _this ){
					var wpbc_cal_dark_skin_path;
					if ( 'wpbc_theme_dark_1' == jQuery( _this ).val() ){
						jQuery( '.wpbc_center_preview,.wpbc_container.wpbc_container_booking_form' ).addClass( 'wpbc_theme_dark_1' );
						wpbc_cal_dark_skin_path = '<?php echo WPBC_PLUGIN_URL; ?>/css/skins/black-2.css';
						jQuery( '#ui_btn_cstm__set_calendar_skin' ).find( 'option' ).prop( 'selected', false );
						jQuery( '#ui_btn_cstm__set_calendar_skin' ).find( 'option[value="' + wpbc_cal_dark_skin_path + '"]' ).prop( 'selected', true ).trigger( 'change' );
						wpbc_cal_dark_skin_path = '<?php echo WPBC_PLUGIN_URL; ?>/css/time_picker_skins/black.css';
						jQuery( '#ui_btn_cstm__set_time_picker_skin' ).find( 'option' ).prop( 'selected', false );
						jQuery( '#ui_btn_cstm__set_time_picker_skin' ).find( 'option[value="' + wpbc_cal_dark_skin_path + '"]' ).prop( 'selected', true ).trigger( 'change' );
					} else {
						jQuery( '.wpbc_center_preview,.wpbc_container.wpbc_container_booking_form' ).removeClass( 'wpbc_theme_dark_1' );
						wpbc_cal_dark_skin_path = '<?php echo WPBC_PLUGIN_URL; ?>/css/skins/green-01.css';
						jQuery( '#ui_btn_cstm__set_calendar_skin' ).find( 'option' ).prop( 'selected', false );
						jQuery( '#ui_btn_cstm__set_calendar_skin' ).find( 'option[value="' + wpbc_cal_dark_skin_path + '"]' ).prop( 'selected', true ).trigger( 'change' );
						wpbc_cal_dark_skin_path = '<?php echo WPBC_PLUGIN_URL; ?>/css/time_picker_skins/grey.css';
						jQuery( '#ui_btn_cstm__set_time_picker_skin' ).find( 'option' ).prop( 'selected', false );
						jQuery( '#ui_btn_cstm__set_time_picker_skin' ).find( 'option[value="' + wpbc_cal_dark_skin_path + '"]' ).prop( 'selected', true ).trigger( 'change' );
					}
				}
			</script><?php
		}


		public function content_section__calendar_skin(){

			?><script type="text/javascript">
				jQuery( document ).ready( function (){

					// Calendar skin
					var template__var = wp.template( 'wpbc_ajx_widget_change_calendar_skin' );

					jQuery( '.wpbc_widget_color_skins .ui_container' ).append(

								template__var({ 'ajx_cleaned_params': {
																	   'customize_plugin__booking_skin':     '<?php echo esc_js( get_bk_option( 'booking_skin' ) ); ?>',
																	 }
								              })
																	);
				} );
			</script><?php
		}

		public function content_section__time_picker_skin(){

			?><script type="text/javascript">
				jQuery( document ).ready( function (){
					// Time Picker
					var template__var = wp.template( 'wpbc_ajx_widget_change_time_picker' );

					jQuery( '.wpbc_widget_color_skins .ui_container' ).append(

								template__var({ 'ajx_cleaned_params': {
																	   'customize_plugin__time_picker_skin': '<?php echo esc_js( get_bk_option( 'booking_timeslot_picker_skin' ) ); ?>'
																	 }
								              })
																	);
				} );
			</script><?php
		}


		public function content_section__form_captcha(){

			$default_options_values = wpbc_get_default_options();

			?><table class="form-table"><?php

				$field_name = 'booking_is_use_captcha';
				$field_params = array(
										  'type'        => 'checkbox'
										, 'default'     => $default_options_values[ $field_name ]   					//'Off'
										, 'title'       => __('CAPTCHA' ,'booking')
										, 'label'       => __('Check the box to activate CAPTCHA inside the booking form.' ,'booking')
										, 'description' => '<div class="wpbc-general-settings-notice wpbc-settings-notice notice-warning" style="margin-top:-10px;">'
														   .  '<strong>' . __('Note' ,'booking') . '!</strong> ' .
										                   	__( 'If your website uses a cache plugin or system, exclude pages with booking forms from caching to ensure CAPTCHA functions correctly.', 'booking' )
														   .'</div>'
										, 'group'       => 'form'
										, 'value'       => ( empty( get_bk_option( $field_name ) ) ? $default_options_values[ $field_name ] : get_bk_option( $field_name ) )
				);
				WPBC_Settings_API::field_checkbox_row_static( $field_name, $field_params );

 			?></table><?php
		}


		public function content_section__form_options(){

			$default_options_values = wpbc_get_default_options();

			?><table class="form-table"><?php

				$field_name = 'booking_is_use_autofill_4_logged_user';
				$field_params = array(
										'type'          => 'checkbox'
										, 'default'     => $default_options_values[ $field_name ]   					//'Off'
										, 'title'       => __('Auto-fill fields' ,'booking')
										, 'label'       => __('Check the box to activate auto-fill form fields for logged in users.' ,'booking')
										, 'description' => ''
										, 'group'       => 'form'
										, 'value'       => ( empty( get_bk_option( $field_name ) ) ? $default_options_values[ $field_name ] : get_bk_option( $field_name ) )
				);
				WPBC_Settings_API::field_checkbox_row_static( $field_name, $field_params );


				$field_name = 'booking_timeslot_picker';
				$field_params = array(
										  'type'        => 'checkbox'
										, 'default'     => $default_options_values[ $field_name ]   					//'Off'
										, 'title'       => __('Time picker for time slots' ,'booking')
										, 'label'       => __('Show time slots as a time picker instead of a select box.' ,'booking')
										, 'description' => ''
										, 'group'       => 'time_slots'
										, 'tr_class'    => 'wpbc_timeslot_picker'
										, 'value'       => ( empty( get_bk_option( $field_name ) ) ? $default_options_values[ $field_name ] : get_bk_option( $field_name ) )
				);
				WPBC_Settings_API::field_checkbox_row_static( $field_name, $field_params );


				if ( class_exists( 'wpdev_bk_personal' ) ) {
					$field_name = 'booking_is_use_simple_booking_form';
					$field_params = array(
										'type'          => 'checkbox'
										, 'default'     => $default_options_values[ $field_name ]   					//'Off'
										, 'title'       => __('Simple' ,'booking') . ' ' . __('Booking Form', 'booking')
										, 'label'       => __('Check the box, if you want to use simple booking form customization from Free plugin version at Settings - Form page.' ,'booking')
										, 'description' => ''
										, 'group'       => 'form'
										, 'value'       => ( empty( get_bk_option( $field_name ) ) ? $default_options_values[ $field_name ] : get_bk_option( $field_name ) )
					);
					WPBC_Settings_API::field_checkbox_row_static( $field_name, $field_params );
				}

 			?></table><?php
		}



	//TODO:
	// 		Check user server configuration  relative to:
	//														suhosin.post.max_array_index_length - Defines the maximum length of array indices for variables registered through a POST request
    //  													suhosin.post.max_array_depth - https://suhosin.org/stories/configuration.html
    public function update() {

        if ( $_POST['reset_to_default_form'] == 'standard' ) {

        	update_bk_option( 'booking_form_structure_type',  'vertical'  );

            $visual_form_structure = $this->import_old_booking_form();              // We are importing old structure to  have default booking form.
            update_bk_option( 'booking_form_visual',  $visual_form_structure  );        
            wpbc_show_changes_saved_message();
            return;        
        }

		// -------------------------------------------------------------------------------------------------------------
        // Update booking form structure
        update_bk_option( 'booking_form_structure_type',  	WPBC_Settings_API::validate_text_post_static( 'booking_form_structure_type' )  );
		// -------------------------------------------------------------------------------------------------------------
		// Update Color Theme and skins/picker not in MU versions
		if ( isset( $_POST[ 'booking_form_theme' ] ) ) {
			$wpbc_selected_theme = WPBC_Settings_API::validate_text_post_static( 'booking_form_theme' );
			update_bk_option( 'booking_form_theme', $wpbc_selected_theme );
			//if (! class_exists( 'wpdev_bk_multiuser' ) ){
				if ( 'wpbc_theme_dark_1' === $wpbc_selected_theme ) {
					update_bk_option( 'booking_skin', '/css/skins/black-2.css' );
					update_bk_option( 'booking_timeslot_picker_skin', '/css/time_picker_skins/black.css' );
				}
				if ( '' === $wpbc_selected_theme ) {
					update_bk_option( 'booking_skin', '/css/skins/green-01.css' );
					update_bk_option( 'booking_timeslot_picker_skin', '/css/time_picker_skins/grey.css' );
				}
			//}
		}

		// -------------------------------------------------------------------------------------------------------------
	    // Calendar skin
	    if ( isset( $_POST['set_calendar_skin'] ) ) {

		    $selected_calendar_skin = WPBC_Settings_API::validate_text_post_static( 'set_calendar_skin' );

		    $selected_calendar_skin = str_replace( array( WPBC_PLUGIN_DIR, WPBC_PLUGIN_URL ), '', $selected_calendar_skin );

		    // Check if this skin exist in the plugin  folder
		    if ( file_exists( WPBC_PLUGIN_DIR . $selected_calendar_skin ) ) {
			    update_bk_option( 'booking_skin', $selected_calendar_skin );
		    }
	    }
	    // Calendar skin
	    if ( isset( $_POST['set_time_picker_skin'] ) ) {

		    $selected_calendar_skin = WPBC_Settings_API::validate_text_post_static( 'set_time_picker_skin' );

		    $selected_calendar_skin = str_replace( array( WPBC_PLUGIN_DIR, WPBC_PLUGIN_URL ), '', $selected_calendar_skin );

		    // Check if this skin exist in the plugin  folder
		    if ( file_exists( WPBC_PLUGIN_DIR . $selected_calendar_skin ) ) {
			    update_bk_option( 'booking_timeslot_picker_skin', $selected_calendar_skin );
		    }
	    }

		// -------------------------------------------------------------------------------------------------------------

		$booking_form_layout_width = WPBC_Settings_API::validate_text_post_static( 'booking_form_layout_width' );
	    $booking_form_layout_width = ( intval( $booking_form_layout_width ) <= 0 ) ? 100 : intval( $booking_form_layout_width );
	    update_bk_option( 'booking_form_layout_width',  $booking_form_layout_width );

		$booking_form_layout_width_px_pr = WPBC_Settings_API::validate_text_post_static( 'booking_form_layout_width_px_pr' );
	    if ( ! in_array( $booking_form_layout_width_px_pr, array( 'px', '%' ) ) ) {
		    $booking_form_layout_width_px_pr = '%';
	    }
	    update_bk_option( 'booking_form_layout_width_px_pr',  $booking_form_layout_width_px_pr);

	    if ( wpbc_is_mu_user_can_be_here( 'only_super_admin' ) ) {
		    update_bk_option( 'booking_is_use_captcha', WPBC_Settings_API::validate_checkbox_post_static( 'booking_is_use_captcha' ) );
		    update_bk_option( 'booking_is_use_autofill_4_logged_user', WPBC_Settings_API::validate_checkbox_post_static( 'booking_is_use_autofill_4_logged_user' ) );
		    update_bk_option( 'booking_timeslot_picker', WPBC_Settings_API::validate_checkbox_post_static( 'booking_timeslot_picker' ) );
	    }

		if ( class_exists( 'wpdev_bk_personal' ) ) {
			update_bk_option( 'booking_is_use_simple_booking_form', WPBC_Settings_API::validate_checkbox_post_static( 'booking_is_use_simple_booking_form' ) );
		}
		// -------------------------------------------------------------------------------------------------------------

        update_bk_option( 'booking_send_button_title',  WPBC_Settings_API::validate_text_post_static( 'booking_send_button_title_name' )  );

		// -------------------------------------------------------------------------------------------------------------
        $skip_obligatory_field_types = array( 'calendar', 'submit', 'captcha', 'email' );

        $if_exist_required = array( 'rangetime' );																		//FixIn:  TimeFreeGenerator

        $visual_form_structure = array();

        $visual_form_structure[] = array(
                                      'type'     => 'calendar'
                                    , 'obligatory' => 'On'
                                );

        // Loop  all form  filds for saving them.
        if ( isset( $_POST['form_field_name'] ) ) {
            foreach ( $_POST['form_field_name'] as $field_key => $field_name ) {

				$form_field_type_val = wpbc_clean_text_value( $_POST['form_field_type'][ $field_key ] );
	            $form_field_type_val = ( 'select-one' === $form_field_type_val ) ? 'selectbox-one' : $form_field_type_val;
	            $form_field_type_val = ( 'select-multiple' === $form_field_type_val ) ? 'selectbox-multiple' : $form_field_type_val;

                $visual_form_structure[] = array(
                                              'type'     => $form_field_type_val
                                            , 'name'     => wpbc_clean_text_value( $field_name )
                                            , 'obligatory' => ( ( in_array( wpbc_clean_text_value( $_POST['form_field_type'][ $field_key ] ), $skip_obligatory_field_types  ) ) ? 'On' : 'Off' )
                                            , 'active'   => ( ( in_array( wpbc_clean_text_value( $_POST['form_field_type'][ $field_key ] ), $skip_obligatory_field_types  ) ) ? 'On' : ( isset($_POST['form_field_active'][ $field_key ] ) ? 'On': 'Off' ) )         //FixIn: 7.0.1.22
											//FixIn:  TimeFreeGenerator
                                            , 'required' => (
                                            					( in_array( wpbc_clean_text_value( $_POST['form_field_type'][ $field_key ] ), $skip_obligatory_field_types  ) )
																? 'On'
																: (
																	( in_array( wpbc_clean_text_value( $field_name ), $if_exist_required  ) )
																	? 'On'
																	: ( isset($_POST['form_field_required'][ $field_key ] ) ? 'On': 'Off' )
																  )
															)       //FixIn: 7.0.1.22
											, 'if_exist_required' => ( ( in_array( wpbc_clean_text_value( $field_name ), $if_exist_required  ) ) ? 'On': 'Off' ) 	//FixIn:  TimeFreeGenerator
                                            , 'label'    => WPBC_Settings_API::validate_text_post_static( 'form_field_label', $field_key )
                                            , 'value'    => WPBC_Settings_API::validate_text_post_static( 'form_field_value', $field_key ) 
                                        );
            }
        }

        $visual_form_structure[] = array(
                                      'type'     => 'captcha'
                                    , 'name'     => 'captcha'
                                    , 'obligatory' => 'On'
                                    , 'active'   => get_bk_option( 'booking_is_use_captcha' )
                                    , 'required' => 'On'
                                    , 'label'    => ''
                                );
    
        $visual_form_structure[] = array(
                                      'type'     => 'submit'
                                    , 'name'     => 'submit'
                                    , 'obligatory' => 'On'
                                    , 'active'   => 'On'
                                    , 'required' => 'On'
                                    , 'label'    => get_bk_option( 'booking_send_button_title' )  						//FixIn:  8.8.1.14		// __('Send', 'booking')
                                );

        update_bk_option( 'booking_form_visual',  $visual_form_structure  );
        update_bk_option( 'booking_form',      str_replace( '\\n\\', '', $this->get_form_in__shortcodes( $visual_form_structure ) ) );
        update_bk_option( 'booking_form_show', str_replace( '\\n\\', '', $this->get_form_show_in__shortcodes() ) );


	    //FixIn: 9.8.6.1
	    if ( ! empty( $_POST['form_visible_section'] ) ) {
			?><script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery( '<?php echo esc_js( $_POST['form_visible_section'] ); ?> a' ).trigger('click');
				});
			</script><?php
	    }


	    if ( class_exists( 'wpdev_bk_personal' ) ) {
		    $is_use_simgple_form = get_bk_option( 'booking_is_use_simple_booking_form' );
		    if ( 'Off' === $is_use_simgple_form ) {
			    ?><script type="text/javascript"> window.location.href = '<?php echo wpbc_get_settings_url() . '&tab=form'; ?>'; </script><?php
		    }
	    }

        wpbc_show_changes_saved_message();

		// To  refresh  the Calendar skin we need to  reload the page
		if ( isset( $_POST[ 'booking_form_theme' ] ) ) {
			?><script type="text/javascript"> window.location.href = '<?php echo wpbc_get_settings_url() . '&tab=form'; ?>'; </script><?php
		}
    }

    
    // <editor-fold     defaultstate="collapsed"                        desc=" Support "  >
    
		/** Show notice */
		private function show_pro_notice() {
			/* //FixIn: 9.5.4.10	 */
		?>
		<span class="wpdevelop">
			<?php $my_close_open_alert_id = 'bk_alert_settings_form_in_free'; ?>
			<div    class="wpbc-settings-notice0 notice-info  <?php //if ( '1' == get_user_option( 'booking_win_' . $my_close_open_alert_id ) ) echo 'hide'; ?>"
					id="<?php echo $my_close_open_alert_id; ?>"
					style="padding: 5px 1em 0;font-size: 14px;">
				<!--a  data-original-title="Don't show the message anymore"
					class="close tooltip_left"
					style="margin-top:4px;" rel="tooltip" data-dismiss="alert"
					href="javascript:void(0)"
					onclick="javascript:wpbc_verify_window_opening(<?php echo wpbc_get_current_user_id(); ?>, '<?php echo $my_close_open_alert_id; ?>');wpbc_hide_window('<?php echo $my_close_open_alert_id; ?>');"
				>&times;</a-->

				<?php
					printf( 'Explore %sother versions%s of Booking Calendar where you can %sfully customize the booking form%s structure to meet your website design and your needs.',
						'<a href="https://wpbookingcalendar.com/features/" target="_blank" style="text-decoration:underline;">','</a>',
						'<a href="https://wpbookingcalendar.com/faq/booking-form-fields/" target="_blank" style="text-decoration:underline;">','</a>'
					);
					/*
				?>
				<strong class="alert-heading">Note!</strong>
					Check how in <a href="https://wpbookingcalendar.com/features/" target="_blank" style="text-decoration:underline;">other versions of Booking Calendar</a>
					possible fully <a href="https://wpbookingcalendar.com/faq/booking-form-fields/" target="_blank" style="text-decoration:underline;">customize the booking form</a>
					<em>(add or remove fields, configure time-slots, change structure of booking form, etc...).</em>
					*/?>
			</div>
		</span>
		<?php
	}
    
    // </editor-fold>

    
    // <editor-fold     defaultstate="collapsed"                        desc=" Import and Get Forms  "  >

    /** Get Visual Structure of booking form,  that imported from OLD Free version */
    private function import_old_booking_form() {

        $visual_form_structure = array();

        // calendar
        $visual_form_structure[] = array(
                                          'type'     => 'calendar'
                                        , 'obligatory' => 'On'
                                    );

        $visual_form_structure[] = array(
										'type'              => 'selectbox',
										'name'              => 'rangetime',
										'obligatory'        => 'Off',
										'active'            => 'On',
										'required'          => 'On',
										'if_exist_required' => 'On',
										'label' => __( 'Time Slots', 'booking' ),
										'value' =>     '9:00 AM - 10:00 AM@@09:00 - 10:00' . "\r\n"
													. '10:00 AM - 11:00 AM@@10:00 - 11:00' . "\r\n"
									         . '11:00 AM - 12:00 PM (Noon)@@11:00 - 12:00' . "\r\n"
							   		          . '12:00 PM (Noon) - 1:00 PM@@12:00 - 13:00' . "\r\n"
											  	      . '1:00 PM - 2:00 PM@@13:00 - 14:00' . "\r\n"
													  . '2:00 PM - 3:00 PM@@14:00 - 15:00' . "\r\n"
													  . '3:00 PM - 4:00 PM@@15:00 - 16:00' . "\r\n"
													  . '4:00 PM - 5:00 PM@@16:00 - 17:00' . "\r\n"
													  . '5:00 PM - 6:00 PM@@17:00 - 18:00' . "\r\n"
													  . '6:00 PM - 7:00 PM@@18:00 - 19:00' . "\r\n"
													           . 'Full Day@@00:00 - 24:00'
									);
        // 1
        $visual_form_structure[] = array(
                                          'type'     => 'text'
                                        , 'name'     => 'name'
                                        , 'obligatory' => 'Off'
                                        , 'active'   => get_bk_option( 'booking_form_field_active1')
                                        , 'required' => get_bk_option( 'booking_form_field_required1')
                                        , 'label'    => get_bk_option( 'booking_form_field_label1')            
                                    );
        // 2
        $visual_form_structure[] = array(
                                          'type'     => 'text'
                                        , 'name'     => 'secondname'
                                        , 'obligatory' => 'Off'
                                        , 'active'   => get_bk_option( 'booking_form_field_active2')
                                        , 'required' => get_bk_option( 'booking_form_field_required2')
                                        , 'label'    => get_bk_option( 'booking_form_field_label2')            
                                    );
        // 3
        $visual_form_structure[] = array(
                                          'type'     => 'email'
                                        , 'name'     => 'email'
                                        , 'obligatory' => 'On'
                                        , 'active'   => get_bk_option( 'booking_form_field_active3')
                                        , 'required' => get_bk_option( 'booking_form_field_required3')
                                        , 'label'    => get_bk_option( 'booking_form_field_label3')            
                                    );
        // 6 - select
        $visual_form_structure[] = array(
                                          'type'     => 'selectbox'
                                        , 'name'     => 'visitors'
                                        , 'obligatory' => 'Off'
                                        , 'active'   => get_bk_option( 'booking_form_field_active6')
                                        , 'required' => get_bk_option( 'booking_form_field_required6')
                                        , 'label'    => get_bk_option( 'booking_form_field_label6')     
                                        , 'value'    => get_bk_option( 'booking_form_field_values6' )
                                    );
        // 4
        $visual_form_structure[] = array(
                                          'type'     => 'text'
                                        , 'name'     => 'phone'
                                        , 'obligatory' => 'Off'
                                        , 'active'   => get_bk_option( 'booking_form_field_active4')
                                        , 'required' => get_bk_option( 'booking_form_field_required4')
                                        , 'label'    => get_bk_option( 'booking_form_field_label4')            
                                    );
        // 5 - textarea
        $visual_form_structure[] = array(
                                          'type'     => 'textarea'
                                        , 'name'     => 'details'
                                        , 'obligatory' => 'Off'
                                        , 'active'   => get_bk_option( 'booking_form_field_active5')
                                        , 'required' => get_bk_option( 'booking_form_field_required5')
                                        , 'label'    => get_bk_option( 'booking_form_field_label5')            
                                    );
        // captcha
        $visual_form_structure[] = array(
                                          'type'     => 'captcha'
                                        , 'name'     => 'captcha'
                                        , 'obligatory' => 'On'
                                        , 'active'   => get_bk_option( 'booking_is_use_captcha' )
                                        , 'required' => 'On'
                                        , 'label'    => ''
                                    );
        // submit
        $visual_form_structure[] = array(
                                          'type'     => 'submit'
                                        , 'name'     => 'submit'
                                        , 'obligatory' => 'On'
                                        , 'active'   => 'On'
                                        , 'required' => 'On'
                                        , 'label'    => get_bk_option( 'booking_send_button_title' )    				//FixIn:  8.8.1.14		// __('Send', 'booking')
                                    );

        return $visual_form_structure;                
    }

    /** Get booking form Structure for Visual  Table */
    public function get_booking_form_structure_for_visual() {
        
        $visual_form_structure = get_bk_option( 'booking_form_visual' );        
        
        if ( $visual_form_structure == false )
            $visual_form_structure = $this->import_old_booking_form();
        
        return $visual_form_structure;
    }


    /** Get HTML of booking form based on Visual Structure */
    public function get_form_in__html( $resource_id = 1 ) {

	    $booking_data__parsed_fields = array();																			//FixIn: 9.2.3.4
	    $booking_data__dates         = array();
		if ( isset( $_GET['booking_hash'] ) ) {

			$booking_id__resource_id = wpbc_hash__get_booking_id__resource_id( $_GET['booking_hash'] );

			if ( $booking_id__resource_id != false ) {

				$booking_data = wpbc_search_booking_by_id( $booking_id__resource_id[0] );
				if ( false !== $booking_data ) {
					$booking_data__parsed_fields = $booking_data->parsed_fields;
					$booking_data__dates         = $booking_data->dates;
				}
			}
		}
        $visual_form_structure = $this->get_booking_form_structure_for_visual();        
        $visual_form_structure = maybe_unserialize( $visual_form_structure );


						$booking_form_structure = get_bk_option( 'booking_form_structure_type' );
						if ( empty( $booking_form_structure ) ) {
							$booking_form_structure = 'vertical';
						}

						$my_form = '';
						// -------------------------------------------------------------------------------------------------------------
						// Booking Form
						// -------------------------------------------------------------------------------------------------------------

						//TODO: refactor hhere: 2024-05-31 23:13
						if ( 'form_right' == $booking_form_structure ) {
							$my_form .= '	<r>' . "\n";
							$my_form .= '		<c>'. "\n";
						}
							$my_form .= '[calendar]' . "\n";
						if ( 'form_right' == $booking_form_structure ) {
							$my_form .= '		</c>'. "\n";
							$my_form .= '		<c>'. "\n";
						}
			$my_form .= '<div class="wpbc__form__div">' . "\n";


			$skip_already_exist_field_types = array( 'calendar', 'submit', 'captcha' );
			$show_field_index = 0;
			foreach ( $visual_form_structure as $key => $form_field ) {

				$defaults = array(
									'type'     => 'text'
								  , 'name'     => 'unique_name'
								  , 'obligatory' => 'Off'
								  , 'active'   => 'On'
								  , 'required' => 'Off'
								  , 'label'    => 'Label'
								  , 'value'    => ''
				);
				$form_field = wp_parse_args( $form_field, $defaults );

				if (   ( ! in_array( $form_field['type'], $skip_already_exist_field_types  ) )   &&   (  ( $form_field['active'] != 'Off' ) || ( $form_field['obligatory'] == 'On' )  )   ){
//TODO: refactor 2024-06-01 00:15
//					$show_field_index++;
//					$is_first = ( $show_field_index % 2 !== 0 );
//					if ( ( 'form_2_columns' == $booking_form_structure ) && ( $is_first ) ){
//						$my_form .= '	<r>' . "\n";
//					} else {
//						if ( 'form_2_columns' != $booking_form_structure )
						$my_form .= '	<r>' . "\n";
//					}

					$my_form .= '		<c> ';
//TODO: refactor 2024-06-01 00:15
//					if ( ( 'form_2_columns' == $booking_form_structure ) && ( $is_first ) && ('rangetime'===$form_field['name'])){
//						$is_first = false;
//						$show_field_index++;
//					}


					// -----------------------------------------------------------------------------------------------------
					// Label
					// -----------------------------------------------------------------------------------------------------
					$form_field['label'] = wpbc_lang( $form_field['label'] );
					if ( function_exists( 'icl_translate' ) ) {                             								// WPML
						$form_field['label'] = icl_translate( 'wpml_custom', 'wpbc_custom_form_field_label_' . $form_field['name'], $form_field['label'] );
					}
					if ( $form_field['type'] != 'checkbox' ) {
						$my_form .= ' <l for="'. $form_field['name'] . $resource_id . '" >' . $form_field['label'] . ( ( $form_field['required'] == 'On' ) ? '*' : '' ) . ':</l><br>';
					}

					// -----------------------------------------------------------------------------------------------------
					// Field Shortcode
					// -----------------------------------------------------------------------------------------------------
					$my_form .= $this->get_html_form_input( $form_field, $booking_data__parsed_fields, $resource_id );


					$my_form .= '</c>' . "\n";
//TODO: refactor 2024-06-01 00:15
//					if ( ( 'form_2_columns' == $booking_form_structure ) && ( ! $is_first ) ) {
//						$my_form .= '	</r>' . "\n";
//					} else {
//						if ( 'form_2_columns' != $booking_form_structure )
						$my_form .= '	</r>' . "\n";
//					}
				}
			}
//TODO: refactor 2024-06-01 00:15
//			if ( ( 'form_2_columns' == $booking_form_structure ) && ( $is_first ) ) {
//				$my_form .= '	</r>' . "\n";
//			}
			if ( get_bk_option( 'booking_is_use_captcha' ) == 'On' ) {
				$my_form .= '	<spacer>height:10px;</spacer>' . "\n";
				$my_form .= '	<r>' . "\n";
				$my_form .= '		<c> [captcha] </c>' . "\n";
				$my_form .= '	</r>' . "\n";
			}

			$submit_button_title = ( ! empty( $booking_data__parsed_fields ) ) ? __( 'Change your Booking', 'booking' ) : get_bk_option( 'booking_send_button_title' );
			$submit_button_title = str_replace( '"', '', html_entity_decode( esc_js( wpbc_lang( $submit_button_title ) ), ENT_QUOTES ) );
			$my_form .= '	<r>' . "\n";
			$my_form .= '		<c> <p>'
									. '<button class="wpbc_button_light" type="button" onclick="mybooking_submit(this.form,' . $resource_id . ',\'' . wpbc_get_maybe_reloaded_booking_locale() . '\');" >'
										. $submit_button_title
									. '</button>'
							  .'</p> </c>' . "\n";
			$my_form .= '	</r>' . "\n";
			$my_form .= '</div>' . "\n";

							if ( 'form_right' == $booking_form_structure ) {
								$my_form .= '	</c>' . "\n";
								$my_form .= '	</r>' . "\n";
							}


							//
							// -------------------------------------------------------------------------------------------------------------
							// ==  Booking Form  ::  Structure  ==
							// -------------------------------------------------------------------------------------------------------------
							$form_css_class_arr = array();

							// Center Form
							if ( 'form_center' == $booking_form_structure ) {
								$form_css_class_arr[] = 'wpbc_booking_form_structure';
								$form_css_class_arr[] = 'wpbc_form_center';
							}
							// Center Form
							if ( 'form_right' == $booking_form_structure ) {
								//$form_css_class_arr[] = 'wpbc_booking_form_structure';
								//$form_css_class_arr[] = 'wpbc_form_right';
							}
							$my_form =   '<div class="wpbc_booking_form_simple ' . implode( ' ', $form_css_class_arr ) . '">' . "\n"
											. $my_form
									   . '</div>';

							// Form Width
							$form_layout_width       = get_bk_option( 'booking_form_layout_width' );
							$form_layout_width_px_pr = get_bk_option( 'booking_form_layout_width_px_pr' );
							$my_form = '<style type="text/css">.wpbc_container_booking_form .block_hints, .wpbc_booking_form_simple .wpbc__form__div{max-width:' . $form_layout_width . $form_layout_width_px_pr . ';} </style>' . "\n" . $my_form;



	    if ( ! empty( $booking_data__dates ) ) {
		    $my_form .= wpbc_get_dates_selection_js_code( $booking_data__dates, $resource_id );							//FixIn: 9.2.3.4
	    }
	    $admin_uri = ltrim( str_replace( get_site_url( null, '', 'admin' ), '', admin_url( 'admin.php?' ) ), '/' );
	    if ( ( strpos( $_SERVER['REQUEST_URI'], $admin_uri ) !== false ) && ( isset( $_SERVER['HTTP_REFERER'] ) ) ) {
		    $my_form .= '<input type="hidden" name="wpdev_http_referer" id="wpdev_http_referer" value="' . $_SERVER['HTTP_REFERER'] . '" />';
	    }


		// Parse Simple HTML tags
		$booking_form = wpbc_bf__replace_custom_html_shortcodes( $my_form );

        return $booking_form;
    }


		/**
		 * Get HTML for INPUT  based on Stuctured form field data
		 *
		 * @param $form_field	array
		 * @param $booking_data__parsed_fields	array
		 * @param $resource_id int
		 *
		 * @return string
		 */
		public function get_html_form_input( $form_field, $booking_data__parsed_fields = array(), $resource_id = 1 ) {

			$my_form = '';

			// TODO: Fields check all 2024-05-30 12:48
			if ( $form_field['type'] == 'text' ){
				$my_form.='   <input type="text" name="'. $form_field['name'] . $resource_id . '" id="' . $form_field['name'] . $resource_id . '" class="input-xlarge'
								. ( ( $form_field['required'] == 'On' ) ? ' wpdev-validates-as-required' : '' )
								//. ( ( strpos( $form_field['name'], 'phone' ) !== false ) ? ' validate_as_digit' : '' )
							  .'" '
							  . ( isset( $booking_data__parsed_fields[ $form_field['name'] ] )					//FixIn: 9.2.3.4
								  ? ' value="' . esc_attr( $booking_data__parsed_fields[ $form_field['name'] ] ) . '"'
								  : ''
								)
							  . '/>';
			}

			if ( $form_field['type'] == 'email' ) {
				$my_form.='   <input type="text" name="'. $form_field['name'] . $resource_id . '" id="' . $form_field['name'] . $resource_id . '" class="input-xlarge wpdev-validates-as-email'
								. ( ( $form_field['required'] == 'On' ) ? ' wpdev-validates-as-required' : '' )
								. ' wpdev-validates-as-required'        //FixIn: 7.0.1.22
							  .'" '
							  . ( isset( $booking_data__parsed_fields[ $form_field['name'] ] )					//FixIn: 9.2.3.4
								  ? ' value="' . esc_attr( $booking_data__parsed_fields[ $form_field['name'] ] ) . '"'
								  : ''
								)
							  . '/>';
			}

			if ( ( $form_field['type'] == 'selectbox' ) || ( $form_field['type'] == 'select' ) ) {

				$my_form.='   <select name="'. $form_field['name'] . $resource_id . '" id="' . $form_field['name'] . $resource_id . '" class="input-xlarge'
							. ( ( $form_field['required'] == 'On' ) ? ' wpdev-validates-as-required' : '' )
							. '" >';																			//FixIn: 8.1.1.4

						$form_field['value'] = preg_split( '/\r\n|\r|\n/', $form_field['value'] );

						foreach ($form_field['value'] as $key => $select_option) {  //FixIn: 7.0.1.21


							$select_option = wpbc_lang( $select_option );
							if ( function_exists('icl_translate') )                             // WPML
								$select_option = icl_translate( 'wpml_custom', 'wpbc_custom_form_select_value_'
																				. wpbc_get_slug_format( $form_field['name']) . '_' .$key
																				, $select_option );
																					// //FixIn: 7.0.1.21
							$select_option = str_replace(array("'",'"'), '', $select_option);

																												//FixIn:  TimeFreeGenerator
							if ( strpos( $select_option, '@@' ) !== false ) {
								$select_option_title = explode( '@@', $select_option );
								$select_option_val = esc_attr( $select_option_title[1] );
								$select_option_title = trim( $select_option_title[0] );
							} else {
								$select_option_val = esc_attr( $select_option );
								$select_option_title = trim( $select_option );

								if ( 'rangetime' == $form_field['name'] ) {
									$select_option_title = wpbc_time_slot_in_format(  $select_option_title );
								}
							}

							//FixIn: 9.2.3.4	10.0.0.52
							if (
									(
											( isset( $booking_data__parsed_fields[ $form_field['name'] ] ) )
										&&  ( $select_option_val == $booking_data__parsed_fields[ $form_field['name'] ] )
									)
								 || (
											( isset( $booking_data__parsed_fields[ $form_field['name'] . '_in_24_hour' ] ) )
										&&  ( $select_option_val == $booking_data__parsed_fields[ $form_field['name'] .'_in_24_hour' ] )
									)
							){
								$is_option_selected = ' selected="selected" ';
							} else {
								$is_option_selected = '';
							}

							$my_form .= '  <option value="' . $select_option_val . '" ' . $is_option_selected . '>' . $select_option_title . '</option>';

							// $my_form.='  <option value="' . $select_option . '">' . $select_option . '</option>';
						}

				$my_form.='     </select>';
			}

			if ( $form_field['type'] == 'checkbox' ) {

				$my_form.='    <label for="'. $form_field['name'] . $resource_id . '" class="control-label" style="display: inline-block;">';

				//FixIn: 9.2.3.4
				if (
						( isset( $booking_data__parsed_fields[ $form_field['name'] ] ) )
					 && (
							   ( $form_field['value'] == $booking_data__parsed_fields[ $form_field['name'] ] )
							|| ( $form_field['label'] == $booking_data__parsed_fields[ $form_field['name'] ] )
							|| ( strtolower( __( 'Yes', 'booking' ) ) == strtolower( $booking_data__parsed_fields[ $form_field['name'] ] ) )
						)
				){
					$is_option_selected = ' checked="checked" ';
				} else {
					$is_option_selected = '';
				}

				$my_form.='   <input type="checkbox" name="'. $form_field['name'] . $resource_id . '" id="' . $form_field['name'] . $resource_id . '" class="wpdev-checkbox '
								. ( ( $form_field['required'] == 'On' ) ? ' wpdev-validates-as-required' : '' )
								. '" style="margin:0 0.25em 3px;" value="true" '
								. ' value="' . esc_attr( $form_field['label'] ) . '" '
								. $is_option_selected
								. '/>';

				$my_form.=   '&nbsp;' . $form_field['label']
							. ( ( $form_field['required'] == 'On' ) ? '' : '' )
						  . '</label>';

			}

			if ( $form_field['type'] == 'textarea' ) {
				$my_form.='   <textarea  rows="3" name="'. $form_field['name'] . $resource_id . '" id="' . $form_field['name'] . $resource_id . '" class="input-xlarge'
							. ( ( $form_field['required'] == 'On' ) ? ' wpdev-validates-as-required' : '' )
							. '" >';																			//FixIn: 8.1.1.4

				$my_form.= ( isset( $booking_data__parsed_fields[ $form_field['name'] ] )						//FixIn: 9.2.3.4
							  ? esc_attr( $booking_data__parsed_fields[ $form_field['name'] ] )                 //FixIn: 9.7.4.3
							  : ''
							);

				$my_form.='</textarea>';
			}

			return $my_form;
		}


	/**
	 * This function    get transfer    "Booking form"        from    "Simple (free)  -->  Paid"                        Free Structure -> Shortcodes
	 *
	 * usually later it saved 	update_bk_option( 'booking_form', ... ) on $this->update()  function
	 *
	 * Get Booking form in Shortcodes - format  compatible with  premium versions
	 *
	 * @param $visual_form_structure
	 *
	 * @return string
	 */
    public function get_form_in__shortcodes( $visual_form_structure = false ) {

		// -------------------------------------------------------------------------------------------------------------
		// Get simple booking form  structure
		// -------------------------------------------------------------------------------------------------------------
	    if ( empty( $visual_form_structure ) ) {
		    $visual_form_structure = $this->get_booking_form_structure_for_visual();
	    }
        $visual_form_structure = maybe_unserialize( $visual_form_structure );

		// -------------------------------------------------------------------------------------------------------------
		// Get Type of booking form
		// -------------------------------------------------------------------------------------------------------------
						$booking_form_structure = get_bk_option( 'booking_form_structure_type' );
						if ( empty( $booking_form_structure ) ) {
							$booking_form_structure = 'vertical';
						}

						$my_form = '';
						// -------------------------------------------------------------------------------------------------------------
						// Booking Form
						// -------------------------------------------------------------------------------------------------------------

						//TODO: refactor hhere: 2024-05-31 23:13
						if ( 'form_right' == $booking_form_structure ) {
							$my_form .= '	<r>' . "\n";
							$my_form .= '		<c>'. "\n";
						}
							$my_form .= '[calendar]' . "\n";
						if ( 'form_right' == $booking_form_structure ) {
							$my_form .= '		</c>'. "\n";
							$my_form .= '		<c>'. "\n";
						}

	    $my_form .= '<div class="wpbc__form__div">' . "\n";

	    $skip_already_exist_field_types = array( 'calendar', 'submit', 'captcha' );
        foreach ( $visual_form_structure as $key => $form_field ) {
            $defaults = array(
                                'type'     => 'text'
                              , 'name'     => 'unique_name'
                              , 'obligatory' => 'Off'
                              , 'active'   => 'On'
                              , 'required' => 'Off'
                              , 'label'    => 'Label'
                              , 'value'    => ''
            );        
            $form_field = wp_parse_args( $form_field, $defaults );
            if (  ( ! in_array( $form_field['type'], $skip_already_exist_field_types  ) ) &&  (  ( $form_field['active'] != 'Off' ) || ( $form_field['obligatory'] == 'On' )  )  ){

				$my_form .= '	<r>' . "\n";
				$my_form .= '		<c> ';

	            // -----------------------------------------------------------------------------------------------------
	            // Label
	            // -----------------------------------------------------------------------------------------------------
	            $form_field['label'] = wpbc_lang( $form_field['label'] );
	            if ( function_exists( 'icl_translate' ) ) {                             								// WPML
		            $form_field['label'] = icl_translate( 'wpml_custom', 'wpbc_custom_form_field_label_' . $form_field['name'], $form_field['label'] );
	            }

	            if ( $form_field['type'] != 'checkbox' ) {
		            $my_form .= ' <l>' . $form_field['label'] . ( ( $form_field['required'] == 'On' ) ? '*' : '' ) . ':</l><br>';
	            }

				// -----------------------------------------------------------------------------------------------------
                // Field Shortcode
				// -----------------------------------------------------------------------------------------------------
	            if ( 1 ) {
					if ( $form_field['type'] == 'text' )                        // Text
						$my_form .= '[text'
									. ( ( $form_field['required'] == 'On' ) ? '*' : '' )
									. ' '. $form_field['name']
									.']';

					if ( $form_field['type'] == 'email' )                       // Email
						$my_form .= '[email'
									. ( ( $form_field['required'] == 'On' ) ? '*' : '' )
									. ' '. $form_field['name']
									.']';

					if ( ( $form_field['type'] == 'selectbox' ) || ( $form_field['type'] == 'select' ) ){                    // Select
						$my_form .= '[selectbox'
									. ( ( $form_field['required'] == 'On' ) ? '*' : '' )
									. ' '. $form_field['name'];

							$form_field['value'] = preg_split( '/\r\n|\r|\n/', $form_field['value'] );
							foreach ($form_field['value'] as $select_option) {

								$select_option = str_replace(array("'",'"'), '', $select_option);

								$my_form.='  "' . $select_option . '"';
							}

						$my_form .= ']';
					}

					if ( $form_field['type'] == 'textarea' )                    // Textarea
						$my_form .= '[textarea'
									. ( ( $form_field['required'] == 'On' ) ? '*' : '' )
									. ' '. $form_field['name']
									.']';


					if ( $form_field['type'] == 'checkbox' ) {                    // Checkbox
						$my_form .= '[checkbox'
									. ( ( $form_field['required'] == 'On' ) ? '*' : '' )
									. ' '. $form_field['name'];
						$my_form .= ' use_label_element';
						$my_form .= ' "' . str_replace( array('"', "'"), '', $form_field['label'] ) .'"]';
					}
				}

	            $my_form .= '</c>' . "\n";
				$my_form .= '	</r>' . "\n";
            }
        }

		if ( get_bk_option( 'booking_is_use_captcha' ) == 'On' ) {
			$my_form .= '	<spacer>height:10px;</spacer>' . "\n";
			$my_form .= '	<r>' . "\n";
			$my_form .= '		<c> [captcha] </c>' . "\n";
			$my_form .= '	</r>' . "\n";
		}
		$submit_button_title = str_replace( '"','', html_entity_decode( esc_js( wpbc_lang( get_bk_option( 'booking_send_button_title' ) ) ),ENT_QUOTES) );
		$my_form .= '	<r>' . "\n";
		$my_form .= '		<c> <p>[submit class:btn "' . $submit_button_title .'"]</p> </c>' . "\n";
		$my_form .= '	</r>' . "\n";
		$my_form .= '</div>' . "\n";


					if ( 'form_right' == $booking_form_structure ) {
						$my_form .= '	</c>' . "\n";
						$my_form .= '	</r>' . "\n";
					}


					//
					// -------------------------------------------------------------------------------------------------------------
					// ==  Booking Form  ::  Structure  ==
					// -------------------------------------------------------------------------------------------------------------
					$form_css_class_arr = array();

					// Center Form
					if ( 'form_center' == $booking_form_structure ) {
						$form_css_class_arr[] = 'wpbc_booking_form_structure';
						$form_css_class_arr[] = 'wpbc_form_center';
					}
					// Center Form
					if ( 'form_right' == $booking_form_structure ) {
						//$form_css_class_arr[] = 'wpbc_booking_form_structure';
						//$form_css_class_arr[] = 'wpbc_form_right';
					}
					$my_form =   '<div class="wpbc_booking_form_simple ' . implode( ' ', $form_css_class_arr ) . '">' . "\n"
									. $my_form
							   . '</div>';

					// Form Width
					$form_layout_width       = get_bk_option( 'booking_form_layout_width' );
					$form_layout_width_px_pr = get_bk_option( 'booking_form_layout_width_px_pr' );
					$my_form = '<style type="text/css">.wpbc_container_booking_form .block_hints, .wpbc_booking_form_simple .wpbc__form__div{max-width:' . $form_layout_width . $form_layout_width_px_pr . ';} </style>' . "\n" . $my_form;


        return $my_form;
    }



    /** Get "Content of booking fields data" form based on Visual Structure table for showing booking details in Listing page */
    public function get_form_show_in__shortcodes() {
        
        $visual_form_structure = $this->get_booking_form_structure_for_visual();        
        $visual_form_structure = maybe_unserialize( $visual_form_structure );
        

        $booking_form_show = '<div style="text-align:left;word-wrap: break-word;">'  . "\n";
        
        $skip_already_exist_field_types = array( 'calendar', 'submit', 'captcha' );

        foreach ( $visual_form_structure as $key => $form_field ) {

            $defaults = array(
                                'type'     => 'text'
                              , 'name'     => 'unique_name'
                              , 'obligatory' => 'Off'
                              , 'active'   => 'On'
                              , 'required' => 'Off'
                              , 'label'    => 'Label'
                              , 'value'    => ''
            );        
            $form_field = wp_parse_args( $form_field, $defaults );
                        
            if (  
                       ( ! in_array( $form_field['type'], $skip_already_exist_field_types  ) ) 
                   &&  (  ( $form_field['active'] != 'Off' ) || ( $form_field['obligatory'] == 'On' )  )
                ){
                    // Label language                    
                    $form_field['label'] = wpbc_lang( $form_field['label'] );
                    if ( function_exists('icl_translate') )                     // WPML    
                        $form_field['label'] = icl_translate( 'wpml_custom', 'wpbc_custom_form_field_label_' . $form_field['name'] , $form_field['label'] );
                 
                    
                    $booking_form_show.= '  <strong>' . $form_field['label'] . '</strong>: ' . '<span class="fieldvalue">[' . $form_field['name'] . ']</span><br/>'  . "\n";        
            }            
        }
        
        $booking_form_show.='</div>'; 
        
        return $booking_form_show;                 
    }
    
    // </editor-fold>
    
    
    // <editor-fold     defaultstate="collapsed"                        desc=" Toolbar "  >
    
    /** Show Save button  in toolbar  for saving form */
    private function toolbar_save_button( $save_button ) {
                
        ?>
        <div class="clear-for-mobile"></div><input 
                                type="button" 
                                class="button button-primary wpbc_submit_button" 
                                value="<?php echo $save_button['title']; ?>" 
                                onclick="if (typeof document.forms['<?php echo $save_button['form']; ?>'] !== 'undefined'){ 
                                            document.forms['<?php echo $save_button['form']; ?>'].submit(); 
                                         } else { 
                                             wpbc_admin_show_message( '<?php echo  ' <strong>Error!</strong> Form <strong>' , $save_button['form'] , '</strong> does not exist.'; ?>.', 'error', 10000 );   //FixIn: 7.0.1.56
                                         }" 
                                />
        <?php
    }
    
    
    /**
	 * Button for Reseting to default booking form
     * (import form  fields  from OLD  free version 
     */
    private function toolbar_reset_booking_form() {
        
        $params = array(  
                      'label_for' => 'min_cost'                             // "For" parameter  of label element
                    , 'label' => '' //__('Add New Field', 'booking')        // Label above the input group
                    , 'style' => 'margin-left:auto;'                                         // CSS Style of entire div element
                    , 'items' => array(
                                        array( 
                                            'type' => 'button'
                                            , 'title' => __('Reset to default form', 'booking')  // __('Reset', 'booking')
                                            , 'class' => 'button wpbc_ui_button_danger wpbc_bs_button_red'
											, 'style' => ''
                                            , 'font_icon' => 'wpbc_icn_rotate_left'
                                            , 'icon_position' => 'right'
                                            , 'action' => "if ( wpbc_are_you_sure('" . esc_js(__('Do you really want to do this ?' ,'booking')) . "') ) {"
                                                        . "var selected_val = 'standard';"
                                                        . "jQuery('#reset_to_default_form').val( selected_val );jQuery('#wpbc_form_field_free').trigger( 'submit' );"
                                                        . "}"  
                                        )                            
                            )
                    );

        ?><div class="control-group wpbc-no-padding" style="float:right;"><?php
                wpbc_bs_input_group( $params );                   
        ?></div><?php

    }


    /** Show selectbox for selection Field Elements in Toolbar */
    private function toolbar_select_field() {


            $params = array(
                          'label_for' => 'min_cost'                             // "For" parameter  of label element
                        , 'label' => '' //__('Add New Field', 'booking')        // Label above the input group
                        , 'style' => ''                                         // CSS Style of entire div element
                        , 'items' => array(
//                                array(
//                                    'type' => 'addon'
//                                    , 'element' => 'text'           // text | radio | checkbox
//                                    , 'text' => __('Add New Field', 'booking') . ':'
//                                    , 'class' => ''                 // Any CSS class here
//                                    , 'style' => 'font-weight:600;' // CSS Style of entire div element
//                                ),
//                                // Warning! Can be text or selectbox, not both  OR you need to define width
                                array(
                                      'type' => 'select'
                                    , 'id' => 'select_form_help_shortcode'
                                    , 'name' => 'select_form_help_shortcode'
                                    , 'style' => ''
                                    , 'class' => ''
                                    , 'multiple' => false
                                    , 'disabled' => false
                                    , 'disabled_options' => array()             // If some options disbaled,  then its must list  here
                                    , 'attr' => array()                         // Any  additional attributes, if this radio | checkbox element
                                    , 'options' => array(                       // Associated array  of titles and values
                                                          'selector_hint' => array(
                                                                        'title' => __('Select', 'booking') . ' ' .  __('Form Field', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => 'font-weight: 400;border-bottom:1px dashed #ccc;color:#ccc;'
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
//                                                          , 'info' => array(
//                                                                        'title' => __('General Info', 'booking')
//                                                                        , 'id' => ''
//                                                                        , 'name' => ''
//                                                                        , 'style' => ''
//                                                                        , 'class' => ''
//                                                                        , 'disabled' => false
//                                                                        , 'selected' => false
//                                                                        , 'attr' => array()
//                                                                    )
                                                        , 'optgroup_sf_s' => array(
                                                                        'optgroup' => true
                                                                        , 'close'  => false
                                                                        , 'title'  => '&nbsp;' . __('Standard Fields' ,'booking')
                                                                    )
                                                        , 'text' => array(
                                                                        'title' => __('Text', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
                                                        , 'select' => array(
                                                                        'title' => __('Select', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
                                                        , 'textarea' => array(
                                                                        'title' => __('Textarea', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
                                                        , 'checkbox' => array(
                                                                        'title' => __('Checkbox', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
                                                        , 'optgroup_sf_e' => array( 'optgroup' => true, 'close'  => true )


                                                        , 'optgroup_af_s' => array(
                                                                        'optgroup' => true
                                                                        , 'close'  => false
                                                                        , 'title'  => '&nbsp;' . __('Advanced Fields' ,'booking')
                                                                    )
				            																							//FixIn: TimeFreeGenerator
                                                        , 'rangetime' => array(
                                                                        'title' => __('Time Slots', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )

                                                        , 'info_advanced' => array(
                                                                        'title' => __('Info', 'booking')
                                                                        , 'id' => ''
                                                                        , 'name' => ''
                                                                        , 'style' => ''
                                                                        , 'class' => ''
                                                                        , 'disabled' => false
                                                                        , 'selected' => false
                                                                        , 'attr' => array()
                                                                    )
                                                        , 'optgroup_af_e' => array( 'optgroup' => true, 'close'  => true )

                                                    )
                                    , 'value' => ''                             // Some Value from optins array that selected by default
                                    , 'onfocus' => ''
                                    , 'onchange' => "wpbc_show_fields_generator( this.options[this.selectedIndex].value );"
                                )
                        )
                    );


		//FixIn:  TimeFreeGenerator
		//If the 'rangetime' already  exist  in the booking form,  so  we do NOT show it as add new field in generator,  because it can exist  only  once in booking form.
        $visual_form_structure = $this->get_booking_form_structure_for_visual();
        $visual_form_structure = maybe_unserialize( $visual_form_structure );

        // Update Field Type Selector in Toolbar
        $params = apply_filters( 'wpbc_form_gen_free_fields_selection', $params,  $visual_form_structure );

        ?>
        <?php
        ?><div class="control-group wpbc-no-padding"><?php
                wpbc_bs_input_group( $params );
        ?></div><?php


        $params = array(
                      'label_for' => 'min_cost'                             // "For" parameter  of label element
                    , 'label' => '' //__('Add New Field', 'booking')        // Label above the input group
                    , 'style' => ''                                         // CSS Style of entire div element
                    , 'items' => array(
                                        array(
                                            'type' => 'button'
                                            , 'title' => __('Add New Field', 'booking')  // __('Reset', 'booking')
                                            , 'class' => 'button wpbc_bs_button_green'
											, 'style' => 'margin-left: 2em;'
                                            , 'font_icon' => 'wpbc_icn_add'
                                            , 'icon_position' => 'right'
                                            , 'action' => "if ( 'selector_hint'===jQuery( '#select_form_help_shortcode').val() ) { wpbc_field_highlight( '#select_form_help_shortcode' ); } else { "
                                                        . "wpbc_show_fields_generator( jQuery( '#select_form_help_shortcode').val() );"
                                                        . "}"
                                        )
                            )
                    );
        ?><div class="control-group wpbc-no-padding"><?php
                wpbc_bs_input_group( $params );
        ?></div><?php
    }



    // </editor-fold>
    
    
    // <editor-fold     defaultstate="collapsed"                        desc=" T a b l e   of    F i e l d s"  >
    /**
	 * Show Fields Table */
    private function show_booking_form_fields_table( $booking_form_structure ) {
       
        $booking_form_structure = maybe_unserialize( $booking_form_structure );  
//debuge($booking_form_structure);     
        $skip_obligatory_field_types = array( 'calendar', 'submit', 'captcha' );
        ?><table class="widefat wpbc_input_table sortable wpdevelop wpbc_table_form_free" cellspacing="0" cellpadding="0"> <?php //FixIn: 10.1.2.2 style="flex:0 1 49.9%;"> ?>
            <thead>
                <tr>
                    <th class="sort"><span class="wpbc_icn_swap_vert" aria-hidden="true"></span></th>
                    <th class="field_active"><?php      echo esc_js( __('Active', 'booking') ); ?></th>
                    <th class="field_label"><?php       echo esc_js( __('Field Label', 'booking') ); ?></th>
                    <th class="field_required"><?php    echo esc_js( __('Required', 'booking') ); ?></th>                    
                    <!--th class="field_options"><?php     echo esc_js( __('Type', 'booking') ) . ' | ' . esc_js( __('Name', 'booking') ); ?></th-->
                    <th class="field_actions"><?php     echo esc_js( __('Actions', 'booking') ); ?></th>
                </tr>
            </thead>
            <tbody class="wpbc_form_fields_body">
            <?php 

            $i=0;
            
            foreach ( $booking_form_structure as $form_field ) {
                
                $defaults = array(
                                    'type'     => 'text'
                                  , 'name'     => 'unique_name'
                                  , 'obligatory' => 'Off'
                                  , 'active'   => 'On'
                                  , 'required' => 'Off'
                                  , 'label'    => 'Label'
                                  , 'value'    => ''
                );        
                $form_field = wp_parse_args( $form_field, $defaults );
                                
                if( ! in_array( $form_field['type'], $skip_obligatory_field_types  ) ) {
                    
                    $i++;
                
                    $row = '<tr class="account">';
                    
                    $row .= '<td class="sort"><span class="wpbc_icn_drag_indicator" aria-hidden="true"></span></td>';

					// Flex Toggle
	                if ( 1 ) {
						ob_start();
						$is_checked = ( $form_field['active'] === 'On' );
						$field_id    = 'wpbc_on_off_' . 'active_' . intval( microtime( true ) ) . '_' . rand( 1, 1000 );
						$field_name  = 'form_field_active[' . $i . ']';
						$field_value = esc_attr( $form_field['active'] );
						$params_checkbox = array(
												  'id'       => $field_id 													// HTML ID  of element
												, 'name'     => $field_name
												, 'label'    => array( 'title' => '', 'position' => 'right' )
												, 'toggle_style' => '' 														// CSS of select element
												, 'class'    => 'wpbc_visible_but_out_screen '  							// CSS Class of select element
												, 'disabled' => ''
												, 'attr' => array( 'autocomplete' => 'off' ) 								// Any  additional attributes, if this radio | checkbox element
												, 'legend'   => ''															//wp_kses_post( $field_title )			// aria-label parameter
												, 'value'    => $field_value 												// Some Value from options array that selected by default
												, 'selected' => $is_checked													// Selected or not
												//, 'onchange' 	=> "jQuery( this ).parents('.wpbc_searchable_on_off').find('.wpbc_label_on_off').hide();jQuery( this ).parents('.wpbc_searchable_on_off').find( jQuery( this ).is(':checked') ? '.wpbc_label_on' : '.wpbc_label_off' ).show();"					// JavaScript code
												//, 'onfocus' 	=>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
												//, 'hint' 		=> array( 'title' => __('Send email notification to customer about this operation' ,'booking') , 'position' => 'top' )
											);
						wpbc_flex_toggle( $params_checkbox );
						$flex_toggle = ob_get_clean();
					}

                    $row .= '<td class="field_active"><div class="wpbc_align_vertically">'
                                . ( ( $form_field['obligatory'] != 'On' )
									? $flex_toggle
								 // ? '<input type="checkbox" name="form_field_active[' . $i . ']" value="' . esc_attr( $form_field['active'] ) . '" ' . checked(  $form_field['active'], 'On' , false ) . ' autocomplete="off" />'
									: '' )
                            
                            .'</div></td>';
                    $row .= '<td class="field_label"><div class="wpbc_align_vertically">'
                                . '<legend class="screen-reader-text"><span>' . esc_attr( $form_field['label'] ) . '</span></legend>'
                                  .'<input  type="text" 
                                        name="form_field_label[' . $i . ']"
                                        value="' . esc_attr( $form_field['label'] ) . '" 
                                        class="regular-text"                                 
                                        placeholder="' . esc_attr( $form_field['label'] ) . '" 
                                        autocomplete="off"
                                    /> '
								. '<div class="field_type_name_description">                                    
                                    	' . __( 'Type', 'booking' ) . ': <div class="field_type_name_value">' . $form_field['type'] . '</div>  
                                    	<span class="field_type_name_separator">|</span>  
                                    	' . __( 'Name', 'booking' ) . ': <div class="field_type_name_value">' . $form_field['name'] . '</div>
									</div>'
                                . '<input type="hidden"  value="'. esc_attr( ( 'select' == $form_field['type'] ) ? 'selectbox' : $form_field['type'] ) 	. '"  name="form_field_type[' . $i . ']" autocomplete="off" />'
                                . '<input type="hidden"  value="'. esc_attr( $form_field['name'] ) 	. '"  name="form_field_name[' . $i . ']" autocomplete="off" />'
                                . '<input type="hidden"  value="'. esc_attr( $form_field['value'] ) . '"  name="form_field_value[' . $i . ']" autocomplete="off" />'
                            .'</div></td>';

                    																									//FixIn:  TimeFreeGenerator
                    $is_show_required_checkbox = true;
                    if ( $form_field['obligatory'] == 'On' ) {
                    	$is_show_required_checkbox = false;
					}
                    if (  isset( $form_field['if_exist_required'] ) &&  ( $form_field['if_exist_required'] == 'On' )  ) {
                    	$is_show_required_checkbox = false;
					}

					// Flex Toggle
	                if ( 1 ) {
						ob_start();
						$is_checked = ( $form_field['required'] === 'On' );
						$field_id    = 'wpbc_on_off_' . 'required_' . intval( microtime( true ) ) . '_' . rand( 1, 1000 );
						$field_name  = 'form_field_required[' . $i . ']';
						$field_value = esc_attr( $form_field['required'] );
						$params_checkbox = array(
												  'id'       => $field_id 													// HTML ID  of element
												, 'name'     => $field_name
												, 'label'    => array( 'title' => '', 'position' => 'right' )
												, 'toggle_style' => '' 														// CSS of select element
												, 'class'    => 'wpbc_visible_but_out_screen '  							// CSS Class of select element
												, 'disabled' => ''
												, 'attr' => array( 'autocomplete' => 'off' ) 								// Any  additional attributes, if this radio | checkbox element
												, 'legend'   => ''															//wp_kses_post( $field_title )			// aria-label parameter
												, 'value'    => $field_value 												// Some Value from options array that selected by default
												, 'selected' => $is_checked													// Selected or not
												//, 'onchange' 	=> "jQuery( this ).parents('.wpbc_searchable_on_off').find('.wpbc_label_on_off').hide();jQuery( this ).parents('.wpbc_searchable_on_off').find( jQuery( this ).is(':checked') ? '.wpbc_label_on' : '.wpbc_label_off' ).show();"					// JavaScript code
												//, 'onfocus' 	=>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
												//, 'hint' 		=> array( 'title' => __('Send email notification to customer about this operation' ,'booking') , 'position' => 'top' )
											);
						wpbc_flex_toggle( $params_checkbox );
						$flex_toggle = ob_get_clean();
					}
                    $row .= '<td class="field_required"><div class="wpbc_align_vertically">'
                            	. ( $is_show_required_checkbox ? $flex_toggle : '' )
								// ( $is_show_required_checkbox ? '<input    type="checkbox" name="form_field_required[' . $i . ']" value="' . esc_attr( $form_field['required'] ) . '" ' . checked(  $form_field['required'], 'On'  , false ) . ' autocomplete="off" />' : '' )
                            .'</div></td>';
//                    $row .= '<td class="field_options"><div class="wpbc_align_vertically">'
//                                . '<input type="text" disabled="DISABLED" value="'. '' . $form_field['type']. ' | ' . $form_field['name'] . '"  autocomplete="off" />'
//                            .'</div></td>';
                    $row .= '<td class="field_actions">'; 
                    if ( $form_field['obligatory'] != 'On' ) {
//TODO: refactor here: 2024-05-31 23:15
						$row .= '<a href="javascript:void(0)" onclick="javascript:wpbc_start_edit_form_field(' . $i . ');" class="tooltip_top button-secondary button" title="'.__('Edit' ,'booking').'"><i class="wpbc_icn_draw"></i></a>';
						$row .= '<a href="javascript:void(0)" class="tooltip_top button-secondary button delete_bk_link" title="'.__('Remove' ,'booking').'"><i class="wpbc_icn_close"></i></a>';
//TODO: refactor here: 2024-05-31 23:15
/*
ob_start();
?>
<div class="wpbc_ajx_toolbar wpbc_no_background" style="margin:0;">
	<div class="ui_container ui_container_small">
		<div class="ui_group ui_group__search_url">
<?php
wpbc_ajx__ui__all_or_new( array(), array('wh_what_bookings'=>'new') );

echo '<div class="ui_element" style="margin:0 5px 0 -10px"><a href="javascript:void(0)" onclick="javascript:wpbc_start_edit_form_field(' . $i . ');" class="wpbc_ui_control wpbc_ui_button wpbc_ui_button tooltip_top " title="'.__('Edit' ,'booking').'"><i class="wpbc_icn_draw"></i></a></div>';
echo '<div class="ui_element"><a href="javascript:void(0)" class="wpbc_ui_control wpbc_ui_button wpbc_ui_button tooltip_top  button delete_bk_link" title="'.__('Remove' ,'booking').'"><i class="wpbc_icn_close"></i></a></div>';

?></div></div></div><?php
$elemnt = ob_get_clean();
$row .= $elemnt;
*/
                    }
                    $row .= '</td>';   
                    
                    $row .= '</tr>'; 
                            
                    echo $row;        
                }
            }            

            ?>
            </tbody>
            <?php /* ?>
            <tfoot>
                <tr>
                    <th colspan="6">
                        <a href="#" class="remove_rows button"><?php _e( 'Remove selected field' ,'booking'); ?></a>
                    </th>
                </tr>
            </tfoot>
            <?php  /**/ ?>
        </table><?php  
        
        $this->js();
    } 
    
    // </editor-fold>

    
    // -----------------------------------------------------------------------------------------------------------------
    // CSS & JS 
    // -----------------------------------------------------------------------------------------------------------------
    
    /** CSS for this page */
    private function css() {
        ?>
        <style type="text/css"> 
            /* toolbar fix */
            .wpdevelop .visibility_container .control-group {
                margin: 2px 8px 3px 0;  /* margin: 0 8px 5px 0; */ /* FixIn:  9.5.4.8	*/
            }
            /* Selectbox element in toolbar */
            .visibility_container select optgroup{                            
                color:#999;
                vertical-align: middle;
                font-style: italic;
                font-weight: 400;
            }
            .visibility_container select option {
                padding:5px;
                font-weight: 600;
            }
            .visibility_container select optgroup option{
                padding: 5px 20px;       
                color:#555;
                font-weight: 600;
            }
        </style>
        <?php
		wpbc_timeslots_free_css();																						//FixIn: TimeFreeGenerator
    }


	//TODO: Refactor this function. 	Transfer some  JavaScript realtive timeslots to the ../generator-timeslots.php and finish it.	2018-05-27
    /** JS for Sorting, removing form fields */
    private function js() {
        ?>
        <script type="text/javascript">

			/**
			 *  Add 'last_selected', 'current' CSS classes  on FOCUS to table rows
			 */
            ( function( $ ){
                var controlled = false;
                var shifted = false;
                var hasFocus = false;

                $(document).on('keyup keydown', function(e){ shifted = e.shiftKey; controlled = e.ctrlKey || e.metaKey } );

                $('.wpbc_input_table').on( 'focus click', 'input', function( e ) {

                        var $this_table = $(this).closest('table');
                        var $this_row   = $(this).closest('tr');

                        if ( ( e.type == 'focus' && hasFocus != $this_row.index() ) || ( e.type == 'click' && $(this).is(':focus') ) ) {

                                hasFocus = $this_row.index();

                                if ( ! shifted && ! controlled ) {
                                        $('tr', $this_table).removeClass('current').removeClass('last_selected');
                                        $this_row.addClass('current').addClass('last_selected');
                                } else if ( shifted ) {
                                        $('tr', $this_table).removeClass('current');
                                        $this_row.addClass('selected_now').addClass('current');

                                        if ( $('tr.last_selected', $this_table).size() > 0 ) {
                                                if ( $this_row.index() > $('tr.last_selected, $this_table').index() ) {
                                                        $('tr', $this_table).slice( $('tr.last_selected', $this_table).index(), $this_row.index() ).addClass('current');
                                                } else {
                                                        $('tr', $this_table).slice( $this_row.index(), $('tr.last_selected', $this_table).index() + 1 ).addClass('current');
                                                }
                                        }

                                        $('tr', $this_table).removeClass('last_selected');
                                        $this_row.addClass('last_selected');
                                } else {
                                        $('tr', $this_table).removeClass('last_selected');
                                        if ( controlled && $(this).closest('tr').is('.current') ) {
                                                $this_row.removeClass('current');
                                        } else {
                                                $this_row.addClass('current').addClass('last_selected');
                                        }
                                }

                                $('tr', $this_table).removeClass('selected_now');

                        }
                }).on( 'blur', 'input', function( e ) {
                        hasFocus = false;
                });

            }( jQuery ) );


			// Make Table sortable
			function wpbc_make_table_sortable(){

				jQuery('.wpbc_input_table tbody th').css('cursor','move');

				jQuery('.wpbc_input_table tbody td.sort').css('cursor','move');

				jQuery('.wpbc_input_table.sortable tbody').sortable({
						items:'tr',
						cursor:'move',
						axis:'y',
	// connectWith: ".wpbc_table_form_free tbody",					////FixIn: 10.1.2.2
	// //axis:'y',
						scrollSensitivity:40,
						forcePlaceholderSize: true,
						helper: 'clone',
						opacity: 0.65,
						placeholder: '.wpbc_input_table .sort',
						start:function(event,ui){
								ui.item.css('background-color','#f6f6f6');
						},
						stop:function(event,ui){
								ui.item.removeAttr('style');
						}
				});
			}


			// Activate row delete
			function wpbc_activate_table_row_delete( del_btn_css_class, is_confirm ){

				// Delete Row
				jQuery( del_btn_css_class ).on( 'click', function(){                   //FixIn: 8.7.11.12

					if ( true === is_confirm ){
						if ( ! wpbc_are_you_sure( '<?php echo esc_js( __( 'Do you really want to do this ?', 'booking' ) ); ?>' ) ){
							return false;
						}
					}

					var $current = jQuery(this).closest('tr');
					if ( $current.size() > 0 ) {
						$current.each(function(){
								jQuery(this).remove();
						});
						return true;
					}

					return false;
				});

			}


		//////////////////////////////////////////////////////////
		// Fields Generator Section
		//////////////////////////////////////////////////////////


            /**
	 		 * Check  Name  in  "field form" about possible usage of this name and about  any Duplicates in Filds Table
             * @param {string} field_name
             */
            function wpbc_check_typed_name( field_name ){

                // Set Name only Letters
                if (    ( jQuery('#' + field_name + '_name').val() != '' )
                     && ( ! jQuery('#' + field_name + '_name').is(':disabled') )
                    ){
                    var p_name = jQuery('#' + field_name + '_name').val();
                    p_name = p_name.replace(/[^A-Za-z0-9_-]*[0-9]*$/g,'').replace(/[^A-Za-z0-9_-]/g,'');
                    p_name = p_name.toLowerCase();


                    jQuery('input[name^=form_field_name]').each(function(){
                        var text_value = jQuery(this).val();
                        if( text_value == p_name ) {                            // error element with this name exist

                            p_name +=  '_' + Math.round( new Date().getTime()  ) + '_rand';         //Add random sufix
                        }
                    });

                    jQuery('#' + field_name + '_name').val( p_name );
                }
            }


            /** Reset to default values all Form  fields for creation new fields */
            function wpbc_reset_all_forms(){

                jQuery('.wpbc_table_form_free tr').removeClass('highlight');
                jQuery('.wpbc_add_field_row').hide();
                jQuery('.wpbc_edit_field_row').hide();

                var field_type_array = [ 'text', 'textarea', 'select','selectbox', 'checkbox' , 'rangetime'];						//FixIn: TimeFreeGenerator
                var field_type;

                for (var i = 0; i < field_type_array.length; i++) {
                    field_type = field_type_array[i];

                    if ( ! jQuery('#' + field_type + '_field_generator_name').is(':disabled') ){						//FixIn: TimeFreeGenerator
						jQuery( '#' + field_type + '_field_generator_active' ).prop( 'checked', true );
						jQuery( '#' + field_type + '_field_generator_required' ).prop( 'checked', false );
						jQuery( '#' + field_type + '_field_generator_label' ).val( '' );

						jQuery( '#' + field_type + '_field_generator_name' ).prop( 'disabled', false );
						jQuery( '#' + field_type + '_field_generator_name' ).val( '' );
						jQuery( '#' + field_type + '_field_generator_value' ).val( '' );
					}
                }
            }


            /**
	 		 * Show selected Add New Field form, and reset fields in this form
             *  
             * @param string selected_field_value
             */
            function wpbc_show_fields_generator( selected_field_value ) {
            	wpbc_reset_all_forms();
				if ( selected_field_value == 'edit_rangetime' ){
					// this field already  exist  in the booking form,  and thats why  we can  not add a new field,  and instead of that  edit it.
					var range_time_edit_field = jQuery( '.wpbc_table_form_free :input[value="rangetime"]' );
					var range_time_field_num = 0;

					if ( range_time_edit_field.length > 0 ){
						var range_time_edit_field_name = jQuery( range_time_edit_field.get( 0 ) ).attr( 'name' );
						range_time_edit_field_name = range_time_edit_field_name.replaceAll( 'form_field_name[', '' ).replaceAll( ']', '' );
						range_time_field_num = parseInt( range_time_edit_field_name );
						if ( range_time_field_num > 0 ){
							wpbc_start_edit_form_field( range_time_field_num );
						}
					}
					if ( 0 == range_time_field_num ){
						//alert( 'Ups... Something wrong.' );
						selected_field_value = 'rangetime';
					} else {
						return;
					}

				}

                if (selected_field_value == 'selector_hint') { 
                    jQuery('.metabox_wpbc_form_field_free_generator').hide();
                    jQuery( '#wpbc_form_field_free input.wpbc_submit_button[type="submit"],input.wpbc_submit_button[type="button"]').show();						//FixIn: 8.7.11.7
					jQuery( '#wpbc_settings__form_fields__toolbar').show();
                } else {
                    jQuery('.metabox_wpbc_form_field_free_generator').show();
                    jQuery('.wpbc_field_generator').hide();
                    jQuery('.wpbc_field_generator_' + selected_field_value ).show();
                    jQuery('#wpbc_form_field_free_generator_metabox h3.hndle span').html( jQuery('#select_form_help_shortcode option:selected').text() );                    
                    jQuery('.wpbc_add_field_row').show();
                    jQuery( '#wpbc_form_field_free input.wpbc_submit_button[type="submit"],input.wpbc_submit_button[type="button"]').hide();						//FixIn: 8.7.11.7
					jQuery( '#wpbc_settings__form_fields__toolbar').hide();
                }            
            }


            /** Hide all Add New Field forms, and reset fields in these forms*/
            function wpbc_hide_fields_generators() {
                wpbc_reset_all_forms();
                jQuery('.metabox_wpbc_form_field_free_generator').hide();
                jQuery('#select_form_help_shortcode>option:eq(0)').attr('selected', true);

                jQuery( '#wpbc_form_field_free input.wpbc_submit_button[type="submit"],input.wpbc_submit_button[type="button"]').show();						//FixIn: 8.7.11.7
				jQuery( '#wpbc_settings__form_fields__toolbar').show();
            }


            /**
	 		 * Add New Row with new Field to Table and Submit Saving changes.
             *
             * @param {string} field_name
             * @param {string} field_type
             */
            function wpbc_add_field ( field_name, field_type ) {
            
				//FixIn: TimeFreeGenerator
				if ( 'rangetime_field_generator' == field_name ) {
					var replaced_result = wpbc_get_saved_value_from_timeslots_table();
					if ( false === replaced_result ){
						wpbc_hide_fields_generators();
						//TOO: Show warning at  the top of page,  about error during saving timeslots
						console.log( 'error during parsing timeslots tbale and savig it.' )
						return;
					}
				}

                if ( jQuery('#' + field_name + '_name').val() != '' ) { 
                    
                    wpbc_check_typed_name( field_name );

                    var row_num = jQuery('.wpbc_table_form_free tbody tr').length + Math.round( new Date().getTime()  ) ;                    
                    
                    var row_active = 'Off';
                    var row_active_checked = '';
                    if ( jQuery('#' + field_name + '_active').is( ":checked" ) ) {
                        row_active = 'On';
                        row_active_checked = ' checked="checked" ';
                    }
                    
                    var row_required = 'Off';
                    var row_required_checked = '';
                    if ( jQuery('#' + field_name + '_required').is( ":checked" ) ) {
                        row_required = 'On';
                        row_required_checked = ' checked="checked" ';
                    }
                    
                    
                    var row;
                    row = '<tr class="account ui-sortable-handle">';
                    
                    ////////////////////////////////////////////////////////////
                    row += '<td class="sort" style="cursor: move;"><span class="wpbc_icn_drag_indicator" aria-hidden="true"></span></td>';
                    
                    row += '<td class="field_active">';                                
                    row +=      '<input type="checkbox" name="form_field_active['+ row_num +']" value="' + row_active + '" ' + row_active_checked + ' autocomplete="off" />';
                    row += '</td>';        
                    
                    ////////////////////////////////////////////////////////////
                    row += '<td class="field_label">';
                    
                    //row +=      '<legend class="screen-reader-text"><span>' + jQuery('#' + field_name + '_label').val() + '</span></legend>';
                    
                    row +=      '<input type="text" name="form_field_label['+ row_num +']" value="' 
                                        + jQuery('#' + field_name + '_label').val() + '" placeholder="'  
                                        + jQuery('#' + field_name + '_label').val() + '" class="regular-text" autocomplete="off" />';

					row +=        		'<div class="field_type_name_description">';
					row +=        			'<?php echo esc_js( __( 'Type', 'booking' ) ); ?>: <div class="field_type_name_value">' +field_type+ '</div>';
					row +=        			'<span class="field_type_name_separator">|</span>';
					row +=        			'<?php echo esc_js( __( 'Name', 'booking' ) ); ?>: <div class="field_type_name_value">' + jQuery('#' + field_name + '_name').val() + '</div>';
					row +=        		'</div>';

                    row +=        '<input type="hidden" value="' + ( ( 'select' == field_type ) ? 'selectbox' : field_type )  +  '"  name="form_field_type[' + row_num + ']" autocomplete="off" />';
                    row +=        '<input type="hidden" value="' + jQuery('#' + field_name + '_name').val() + '"  name="form_field_name[' + row_num + ']" autocomplete="off" />';
                    row +=        '<input type="hidden" value="' + jQuery('#' + field_name + '_value').val() + '"  name="form_field_value[' + row_num + ']" autocomplete="off" />';

                    row += '</td>';
                    
                    ////////////////////////////////////////////////////////////
                    row += '<td class="field_required">';

						//FixIn:  TimeFreeGenerator
						if ( 'rangetime' == field_name ) {
							row +=      '<input type="checkbox" disabled="DISABLED" name="form_field_required['+ row_num +']" value="' + 'On' + '" ' + ' checked="checked" ' + ' autocomplete="off" />';
						} else {
							row += 		'<input type="checkbox" name="form_field_required[' + row_num + ']" value="' + row_required + '" ' + row_required_checked + ' autocomplete="off" />';
						}

                    row += '</td>'; 
                    
                    ////////////////////////////////////////////////////////////
                    // row += '<td class="field_options">';
                    // row +=        '<input type="text" disabled="DISABLED" value="' + field_type + ' | ' + jQuery('#' + field_name + '_name').val() + '"  autocomplete="off" />';
                    // row += '</td>';
                    
                    ////////////////////////////////////////////////////////////
                    row += '<td class="field_options">';
                    
                    //row +=      '<a href="javascript:void(0)" class="tooltip_top button-secondary button" title="<?php echo esc_js( __('Edit' ,'booking') ) ; ?>"><i class="wpbc_icn_draw"></i></a>';
                    //row +=      '<a href="javascript:void(0)" class="tooltip_top button-secondary button delete_bk_link" title="<?php echo esc_js( __('Remove' ,'booking') ) ; ?>"><i class="wpbc_icn_close"></i></a>';
                    
                    row += '</td>';   
                    ////////////////////////////////////////////////////////////
                    row += '</tr>'; 
                    
                    jQuery('.wpbc_table_form_free tbody').append( row );
                    
                    wpbc_hide_fields_generators();
                    
                    document.forms['wpbc_form_field_free'].submit();            //Submit form
                    
                } else {                    
                    wpbc_field_highlight( '#' + field_name + '_name' );
                }
            }
             

			/**
			 * Prepare Edit section for editing specific field.
			 * @param row_number
			 */
			function wpbc_start_edit_form_field( row_number ) {

                wpbc_reset_all_forms();																					// Reset Fields in all generator rows (text,select,...) to init (empty) values
                jQuery('.wpbc_edit_field_row').show();																	// Show row with edit btn
                
                jQuery('.wpbc_table_form_free tr').removeClass('highlight');
                jQuery('input[name="form_field_name['+row_number+']"]').closest('tr').addClass('highlight');			//Highlight row

				// Get exist data from EXIST fields Table
                var field_active = jQuery('input[name="form_field_active['+row_number+']"]').is( ":checked" );
                var field_required = jQuery('input[name="form_field_required['+row_number+']"]').is( ":checked" );
                var field_label = jQuery('input[name="form_field_label['+row_number+']"]').val();
                var field_value = jQuery('input[name="form_field_value['+row_number+']"]').val();
                var field_name = jQuery('input[name="form_field_name['+row_number+']"]').val();
                var field_type = jQuery('input[name="form_field_type['+row_number+']"]').val();
//console.log( 'field_active, field_required, field_label, field_value, field_name, field_type', field_active, field_required, field_label, field_value, field_name, field_type );

				jQuery('.metabox_wpbc_form_field_free_generator').show();												// Show Generator section
                jQuery('.wpbc_field_generator').hide();																	// Hide inside of generator sub section  relative to fields types



//FixIn: TimeFreeGenerator	- Exception - field with  name 'rangetime, have type 'rangetype' in Generator BUT, it have to  be saved as 'select' type'
if ( 'rangetime' == field_name ) {
/**
 *  Field 'rangetime_field_generator' have DIV section, which have CSS class 'wpbc_field_generator_rangetime',
 *  but its also  defined with  type 'select'  for adding this field via    javascript:wpbc_add_field ( 'rangetime_field_generator', 'select' );
 */

	field_type = 'rangetime';

/**
 * During editing 'field_required' == false,  because this field does not exist  in the Table with exist fields,  but we need to  set it to  true and disabled.
 */

}

                jQuery('.wpbc_field_generator_' + field_type ).show();													// Show specific generator sub section  relative to selected Field Type
                jQuery('#wpbc_form_field_free_generator_metabox h3.hndle span').html( '<?php echo __('Edit', 'booking') . ': '  ?>' + field_name );
                //jQuery('#wpbc_form_field_free_generator_metabox h3.hndle span').html( this.options[this.selectedIndex].text )

                jQuery( '#' + field_type + '_field_generator_active' ).prop( 'checked', field_active );
                jQuery( '#' + field_type + '_field_generator_required' ).prop( 'checked', field_required );
                jQuery( '#' + field_type + '_field_generator_label' ).val( field_label );
                jQuery( '#' + field_type + '_field_generator_name' ).val( field_name );
                jQuery( '#' + field_type + '_field_generator_value' ).val( field_value );
                jQuery( '#' + field_type + '_field_generator_name' ).prop('disabled' , true);

//FixIn: TimeFreeGenerator
if ( 'rangetime' == field_name ) {
	jQuery( '#' + field_type + '_field_generator_required' ).prop( 'checked',  true ).prop( 'disabled', true );			// Set Disabled and Checked -- Required field
	wpbc_check_typed_values( field_name + '_field_generator' );															// Update Options and Titles for TimeSlots
	wpbc_timeslots_table__fill_rows();
}

				jQuery( '#wpbc_form_field_free input.wpbc_submit_button[type="submit"],input.wpbc_submit_button[type="button"]').hide();						//FixIn: 8.7.11.7
				jQuery( '#wpbc_settings__form_fields__toolbar').hide();

                wpbc_scroll_to('#wpbc_form_field_free_generator_metabox' );
            }


			/**
			 * Prepare fields data, and submit Edited field by clicking "Save changes" btn.
			 *
			 * @param field_name
			 * @param field_type
			 */
			function wpbc_finish_edit_form_field( field_name, field_type ) {


//FixIn: TimeFreeGenerator
if ( 'rangetime_field_generator' == field_name ) {
	var replaced_result = wpbc_get_saved_value_from_timeslots_table();
	if ( false === replaced_result ){
		wpbc_hide_fields_generators();
		//TOO: Show warning at  the top of page,  about error during saving timeslots
		console.log( 'error during parsing timeslots tbale and savig it.' )
		return;
	}
}


                // Get Values in  Edit Form ////////////////////////////////////
                
                //0: var field_type
                //1:
                var row_active = 'Off';
                var row_active_checked = false;
                if ( jQuery('#' + field_name + '_active').is( ":checked" ) ) {
                    row_active = 'On';
                    row_active_checked = true;
                }
                //2:    
                var row_required = 'Off';
                var row_required_checked = false;
                if ( jQuery('#' + field_name + '_required').is( ":checked" ) ) {
                    row_required = 'On';
                    row_required_checked = true;
                }
                //3:
                var row_label = jQuery('#' + field_name + '_label').val();                
                //4:
                var row_name = jQuery('#' + field_name + '_name').val();
                //5:
                var row_value = jQuery('#' + field_name + '_value').val();

                // Set  values to  the ROW in Fields Table /////////////////////
                //1:
                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_active]').prop( 'checked', row_active_checked );
                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_active]').val( row_active );
                //2:
                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_required]').prop( 'checked', row_required_checked );
                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_required]').val( row_required );
                //3:
                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_label]').val( row_label );
//                //4:
//                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_name]').val( row_name );
//                //0:
//                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_type]').val( field_type );
                //5:
                jQuery('.wpbc_table_form_free tr.highlight input[name^=form_field_value]').val( row_value );                
//                // Options field:
//                jQuery('.wpbc_table_form_free tr.highlight td.field_options input:disabled').val( field_type + '|' +  row_name );
                
                
                //Hide generators and Reset forms  and Disable highlighting ////
                wpbc_hide_fields_generators();
                
                //Send submit //////////////////////////////////////////////////
                document.forms['wpbc_form_field_free'].submit();                // Submit form


                
            }


            /**
	 		 * Check  Value and parse it to Options and Titles
             * @param {string} field_name
             */
            function wpbc_check_typed_values( field_name ){

            	var t_options_titles_arr = wpbc_get_titles_options_from_values( '#' + field_name + '_value' );

            	if ( false !== t_options_titles_arr ) {

					var t_options = t_options_titles_arr[0].join( "\n" );
                    var t_titles  = t_options_titles_arr[1].join( "\n" );
					jQuery('#' + field_name + '_options_options').val( t_options );
					jQuery('#' + field_name + '_options_titles').val( t_titles );

				}
            }


			/**
			 * Get array  with  Options and Titles from  Values,  if in values was defined constrution  like this 			' Option @@ Title '
			 * @param field_id string
			 * @returns array | false
			 */
			function wpbc_get_titles_options_from_values( field_id ){
                if (    ( jQuery( field_id ).val() != '' )
                     && ( ! jQuery( field_id ).is(':disabled') )
                    ){

                    var tslots = jQuery( field_id ).val();
                    tslots = tslots.split('\n');
                    var t_options = [];
                    var t_titles  = [];
                    var slot_t = '';

                    if ( ( typeof tslots !== 'undefined' ) && ( tslots.length > 0 ) ){

                    	for ( var i=0; i < tslots.length; i++ ) {

                    		slot_t = tslots[ i ].split( '@@' );

							if ( slot_t.length > 1 ){
								t_options.push( slot_t[ 1 ].trim() );
								t_titles.push(  slot_t[ 0 ].trim() );
							} else {
								t_options.push( slot_t[ 0 ].trim() );
								t_titles.push(  '' );
							}
						}

					}
					var t_options_titles_arr = [];
                    t_options_titles_arr.push( t_options );
                    t_options_titles_arr.push( t_titles );

					return t_options_titles_arr;
                }
                return false;
			}

        </script>
        <?php
    }
    
    
    // -----------------------------------------------------------------------------------------------------------------
    // Generators
    // -----------------------------------------------------------------------------------------------------------------
    
    /** Sections with Add New Fields forms */
    private function fields_generator_section() {
        ?>
        <div class="wpbc_field_generator wpbc_field_generator_info">
        <?php 
            
            echo
                '<p><strong>' . __('Shortcodes' ,'booking') . '.</strong> ' 
                           . sprintf(__('You can generate the form fields for your form (at the left side) by selection specific field in the above selectbox.' ,'booking'),'<code><strong>[email* email]</strong></code>')
                .'<br/>'   . sprintf(__('Please read more about the booking form fields configuration %shere%s.' ,'booking'),'<a href="https://wpbookingcalendar.com/faq/booking-form-fields/" target="_blank">', '</a>' ) 

                . '</p><p><strong>' . __('Default Form Templates' ,'booking') . '.</strong> ' . 
                             sprintf(__('You can reset your active form template by selecting default %sform template%s at the top toolbar. Please select the form template and click on %sReset%s button for resetting only active form (Booking Form or Content of Booking Fields form). Click  on %sBoth%s button if you want to reset both forms: Booking Form and Content of Booking Fields form.' ,'booking')
                                        ,'<strong>','</strong>'
                                        ,'<strong>','</strong>'
                                        ,'<strong>','</strong>'
                                     )
                .'</p>';

            $this->show_pro_notice();             
        ?>
        </div>
        <div class="wpbc_field_generator wpbc_field_generator_text">
        <?php 
        
            $this->generate_field(  
                                    'text_field_generator'
                                    , array( 
                                        'active' => true
                                        , 'required' => true
                                        , 'label' => true
                                        , 'name' => true
                                        , 'value' => false 
                                        , 'type' => 'text' 
                                    )  
                                );            
        ?>
        </div>
        <div class="wpbc_field_generator wpbc_field_generator_textarea">
        <?php  
        
            $this->generate_field(  
                                    'textarea_field_generator'
                                    , array( 
                                        'active' => true
                                        , 'required' => true
                                        , 'label' => true
                                        , 'name' => true
                                        , 'value' => false 
                                        , 'type' => 'textarea' 
                                    )  
                                );        
        ?>
        </div>
        <div class="wpbc_field_generator wpbc_field_generator_select wpbc_field_generator_selectbox">
        <?php 
            $this->generate_field(  
                                    'selectbox_field_generator'
                                    , array( 
                                        'active' => true
                                        , 'required' => true
                                        , 'label' => true
                                        , 'name' => true
                                        , 'value' => true 
                                        , 'type' => 'selectbox'
                                    )  
                                );        
        ?>    
        </div>
        <div class="wpbc_field_generator wpbc_field_generator_checkbox">
        <?php 
        
            $this->generate_field(  
                                    'checkbox_field_generator'
                                    , array( 
                                        'active' => true
                                        , 'required' => true
                                        , 'label' => true
                                        , 'name' => true
                                        , 'value' => false 
                                        , 'type' => 'checkbox' 
                                    )  
                                );        
        ?>
        </div>
		<?php
																														//FixIn: TimeFreeGenerator
		?>
        <div class="wpbc_field_generator wpbc_field_generator_rangetime">
        <?php

            $this->generate_field(
                                    'rangetime_field_generator'
                                    , array(
                                          'active' 	 => true
                                        , 'required' => true
                                        , 'label' 	 => true
                                        , 'name' 	 => true
                                        , 'value' 	 => true
                                        , 'type' 	 => 'selectbox'

										, 'required_attr' 	=> array( 'disabled' => true
																	, 'value' => 'On'
																)
										, 'label_attr' 		=> array( 'placeholder' => __( 'Time Slots', 'booking' )
																	, 'value' 		=> __( 'Time Slots', 'booking' )
																)
										, 'name_attr' 		=> array( 'disabled' 	=> true
																	, 'placeholder' => 'rangetime'
																	, 'value' 		=> 'rangetime'
																)
										, 'value_attr' 		=> array( 'value' => "10:00 AM - 12:00 PM@@10:00 - 12:00\n12:00 PM - 02:00 PM@@12:00 - 14:00\n13:00 - 14:00\n11:00 - 15:00\n14:00 - 16:00\n16:00 - 18:00\n18:00 - 20:00"
																	, 'attr' => array(
																						'placeholder' => "10:00 AM - 12:00 PM@@10:00 - 12:00\n12:00 PM - 02:00 PM@@12:00 - 14:00\n13:00 - 14:00\n11:00 - 15:00\n14:00 - 16:00\n16:00 - 18:00\n18:00 - 20:00"
																					)
																	, 'rows' => 5
																	, 'cols' => 37
																)
                                    )
                                );
        ?>
        </div>
        <div class="wpbc_field_generator wpbc_field_generator_info_advanced">
            <?php  $this->show_pro_notice(); ?>
			<div class="clear" style="margin-top:20px;"></div>
			<a onclick="javascript:wpbc_hide_fields_generators();" href="javascript:void(0)" style="margin: 0 15px;"
		   		class="button button"><i class="menu_icon icon-1x wpbc_icn_visibility_off"></i>&nbsp;&nbsp;<?php _e( 'Close' ,'booking'); ?></a>
        </div>        
        <?php
    }

		/** General Fields Generator */
		private function generate_field( $field_name = 'some_field_name', $field_options = array()  ) {

			$defaults = array(
						'active'   => true
					  , 'required' => true
					  , 'label'    => true
					  , 'name'     => true
					  , 'value'    => true
																															//FixIn: TimeFreeGenerator 	(inside of form fields edited,  as well)
					  , 'required_attr' => array( 	  'disabled' => false
													, 'value' => 'Off'
											)
					  , 'label_attr' 	=> array( 	  'placeholder' => __('First Name', 'booking')
													, 'value' => ''
											)
					  , 'name_attr' 	=> array( 	  'disabled' => false
													, 'placeholder' => 'first_name'
													, 'value' => ''
											)
					  , 'value_attr' 	=> array( 	  'value' => ''
													, 'attr' => array( 'placeholder' => "1\n2\n3\n4" )
													, 'rows' => 2
													, 'cols' => 37
											)
					  );
			$field_options = wp_parse_args( $field_options, $defaults );

			?><table class="form-table"><?php

			if ( $field_options['active'] )
				WPBC_Settings_API::field_checkbox_row_static(   $field_name . '_active'
															, array(
																	'type'              => 'checkbox'
																	, 'title'             => __('Active', 'booking')
																	, 'label'             => __('Show / hide field in booking form', 'booking')
																	, 'disabled'          => false
																	, 'class'             => ''
																	, 'css'               => ''
																	, 'type'              => 'checkbox'
																	, 'description'       => ''
																	, 'attr'              => array()
																	, 'group'             => 'general'
																	, 'tr_class'          => ''
																	, 'only_field'        => false
																	, 'is_new_line'       => true
																	, 'description_tag'   => 'span'
																	, 'value' => 'On'
															)
															, true
														);
			if ( $field_options['required'] )
				WPBC_Settings_API::field_checkbox_row_static(   $field_name . '_required'
															, array(
																	'type'              => 'checkbox'
																	, 'title'             => __('Required', 'booking')
																	, 'label'             => __('Set field as required', 'booking')
																	, 'disabled'          => $field_options[ 'required_attr' ][ 'disabled' ]				//false
																	, 'class'             => ''
																	, 'css'               => ''
																	, 'type'              => 'checkbox'
																	, 'description'       => ''
																	, 'attr'              => array()
																	, 'group'             => 'general'
																	, 'tr_class'          => ''
																	, 'only_field'        => false
																	, 'is_new_line'       => true
																	, 'description_tag'   => 'span'
																	, 'value' 			  => $field_options[ 'required_attr' ][ 'value' ]				//'Off'
															)
															, true
														);
			if ( $field_options['label'] )
				WPBC_Settings_API::field_text_row_static(   $field_name . '_label'
															, array(
																	'type'                => 'text'
																	, 'title'             => __('Label', 'booking')
																	, 'disabled'          => false
																	, 'class'             => ''
																	, 'css'               => ''
																	, 'placeholder'       => $field_options[ 'label_attr' ][ 'placeholder' ]				//'First Name'
																	, 'description'       => ''//__('Enter field label', 'booking')
																	, 'group'             => 'general'
																	, 'tr_class'          => ''
																	, 'only_field'        => false
																	, 'description_tag'   => 'p'
																	, 'value' 			  => $field_options[ 'label_attr' ][ 'value' ]				//''
																	, 'attr'              => array(
																		  'oninput'   => "javascript:this.onchange();"
																		, 'onpaste'   => "javascript:this.onchange();"
																		, 'onkeypress'=> "javascript:this.onchange();"
																		, 'onchange'  => "javascript:if ( ! jQuery('#".$field_name . '_name'."').is(':disabled') ) { jQuery('#".$field_name . '_name'."').val(jQuery(this).val() );} wpbc_check_typed_name('".$field_name."');"
																	)
															)
															, true
														);
			if ( $field_options['name'] )
				WPBC_Settings_API::field_text_row_static(   $field_name . '_name'
															, array(
																	'type'              => 'text'
																	, 'title'             => __('Name', 'booking') . '  *'
																	, 'disabled'          => $field_options[ 'name_attr' ][ 'disabled' ]				//false
																	, 'class'             => ''
																	, 'css'               => ''
																	, 'placeholder'       => $field_options[ 'name_attr' ][ 'placeholder' ]				//'first_name'
																	, 'description'       => sprintf( __('Type only %sunique field name%s, that is not using in form', 'booking'), '<strong>', '</strong>' )
																	, 'group'             => 'general'
																	, 'tr_class'          => ''
																	, 'only_field'        => false
																	, 'description_tag'   => 'p'
																	, 'value' 			  => $field_options[ 'name_attr' ][ 'value' ]					//''
																	, 'attr'              => array(
																		  'oninput'   => "javascript:this.onchange();"
																		, 'onpaste'   => "javascript:this.onchange();"
																		, 'onkeypress'=> "javascript:this.onchange();"
																		, 'onchange'  => "javascript:wpbc_check_typed_name('".$field_name."');"

																	)

															)
															, true
														);
			if ( $field_options['value'] )
				WPBC_Settings_API::field_textarea_row_static(   $field_name . '_value'
															, array(

																	 'title'             => __('Values', 'booking')
																	, 'disabled'          => false
																	, 'class'             => ''
																	, 'css'               => ''
																	, 'placeholder'       => ''
																	, 'description'       => sprintf( __('Enter dropdown options. One option per line.', 'booking'), '<strong>', '</strong>' )
																	, 'group'             => 'general'
																	, 'tr_class'          => ''
																	, 'only_field'        => false
																	, 'description_tag'   => 'p'
																	, 'value' 			  => $field_options[ 'value_attr' ][ 'value' ]					// ''
																	, 'attr'              => $field_options[ 'value_attr' ][ 'attr' ]					//array( 'placeholder' => "1\n2\n3\n4" )   //Override Placeholder value, because of escaping \n symbols
																	, 'rows'              => $field_options[ 'value_attr' ][ 'rows' ]					//2
																	, 'cols'              => $field_options[ 'value_attr' ][ 'cols' ]					//37
																	, 'show_in_2_cols'    => false
																	, 'attr'              => array(
																		  'oninput'   => "javascript:this.onchange();"
																		, 'onpaste'   => "javascript:this.onchange();"
																		, 'onkeypress'=> "javascript:this.onchange();"
																		, 'onchange'  => "javascript:wpbc_check_typed_values('".$field_name."');"
																	)
															)
															, true
														);

				do_action( 'wpbc_settings_form_page_after_values', $field_name, $field_options );                            //FixIn: TimeFreeGenerator

				?>
				<tr><th colspan="2" style="border-bottom:1px solid #eee;padding:10px 0 0;"></th></tr>

				<tr class="wpbc_add_field_row">
					<th colspan="2" class="wpdevelop">
						<a onclick="javascript:wpbc_add_field ( '<?php echo $field_name; ?>', '<?php echo $field_options['type']; ?>' );"
						   href="javascript:void(0)"
						   style=""
						   class="button button-primary"><i class="menu_icon icon-1x wpbc_icn_add_circle_outline"></i>&nbsp;&nbsp;<?php _e( 'Add New Field' ,'booking'); ?></a>
						&nbsp;&nbsp;
						<a onclick="javascript:wpbc_hide_fields_generators();"
						   href="javascript:void(0)"
						   style=""
						   class="button button"><i class="menu_icon icon-1x wpbc_icn_visibility_off"></i>&nbsp;&nbsp;<?php _e( 'Close' ,'booking'); ?></a>
					</th>
				</tr>

				<tr class="wpbc_edit_field_row">
					<th colspan="2" class="wpdevelop">
						<a onclick="javascript:wpbc_finish_edit_form_field ( '<?php echo $field_name; ?>', '<?php echo $field_options['type']; ?>' );"
						   href="javascript:void(0)"
						   style=""
						   class="button button-primary"><i class="menu_icon icon-1x wpbc_icn_draw"></i>&nbsp;&nbsp;<?php _e( 'Save Changes' ,'booking'); ?></a>
						&nbsp;&nbsp;
						<a onclick="javascript:wpbc_hide_fields_generators();"
						   href="javascript:void(0)"
						   style=""
						   class="button button"><i class="menu_icon icon-1x wpbc_icn_close"></i>&nbsp;&nbsp;<?php _e( 'Cancel' ,'booking'); ?></a>
					</th>
				</tr>

			</table><?php
		}


    //                                                                              <editor-fold   defaultstate="collapsed"   desc=" Activate | Deactivate " >    
    
    public function activate() {
        
        add_bk_option( 'booking_form',          $this->get_form_in__shortcodes() );
        add_bk_option( 'booking_form_show',     $this->get_form_show_in__shortcodes() );
        add_bk_option( 'booking_form_visual',   $this->import_old_booking_form() );
    }
    
    public function deactivate() {
        
        delete_bk_option( 'booking_form' );
        delete_bk_option( 'booking_form_show' );
        delete_bk_option( 'booking_form_visual');
    }

    //                                                                              </editor-fold>
}

add_action('wpbc_menu_created', array( new WPBC_Page_SettingsFormFieldsFree() , '__construct') );    // Executed after creation of Menu



