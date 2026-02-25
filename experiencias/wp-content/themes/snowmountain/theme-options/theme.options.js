/* global jQuery:false */
/* global SNOWMOUNTAIN_STORAGE:false */

//-------------------------------------------
// Override options manipulations
//-------------------------------------------
jQuery(document).ready(function() {
	"use strict";

	// jQuery Tabs
	jQuery('#snowmountain_override_option_tabs').tabs();

	// Toggle inherit button and cover
	jQuery('#snowmountain_override_option_tabs').on('click', '.snowmountain_override_option_inherit_lock,.snowmountain_override_option_inherit_cover', function (e) {
		var parent = jQuery(this).parents('.snowmountain_override_option_item');
		var inherit = parent.hasClass('snowmountain_override_option_inherit_on');
		if (inherit) {
			parent.removeClass('snowmountain_override_option_inherit_on').addClass('snowmountain_override_option_inherit_off');
			parent.find('.snowmountain_override_option_inherit_cover').fadeOut().find('input[type="hidden"]').val('');
		} else {
			parent.removeClass('snowmountain_override_option_inherit_off').addClass('snowmountain_override_option_inherit_on');
			parent.find('.snowmountain_override_option_inherit_cover').fadeIn().find('input[type="hidden"]').val('inherit');
			
		}
		e.preventDefault();
		return false;
	});

	// Refresh linked field
	jQuery('#snowmountain_override_option_tabs').on('change', '[data-linked] select,[data-linked] input', function (e) {
		var chg_name     = jQuery(this).parent().data('param');
		var chg_value    = jQuery(this).val();
		var linked_name  = jQuery(this).parent().data('linked');
		var linked_data  = jQuery('#snowmountain_override_option_tabs [data-param="'+linked_name+'"]');
		var linked_field = linked_data.find('select');
		var linked_field_type = 'select';
		if (linked_field.length == 0) {
			linked_field = linked_data.find('input');
			linked_field_type = 'input';
		}
		var linked_lock = linked_data.parent().parent().find('.snowmountain_override_option_inherit_lock').addClass('snowmountain_override_option_wait');
		// Prepare data
		var data = {
			action: 'snowmountain_get_linked_data',
			nonce: SNOWMOUNTAIN_STORAGE['ajax_nonce'],
			chg_name: chg_name,
			chg_value: chg_value
		};
		jQuery.post(SNOWMOUNTAIN_STORAGE['ajax_url'], data, function(response) {
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: SNOWMOUNTAIN_STORAGE['ajax_error_msg'] };
				console.log(response);
			}
			if (rez.error === '') {
				if (linked_field_type == 'select') {
					var opt_list = '';
					for (var i in rez.list) {
						opt_list += '<option value="'+i+'">'+rez.list[i]+'</option>';
					}
					linked_field.html(opt_list);
				} else {
					linked_field.val(rez.value);
				}
				linked_lock.removeClass('snowmountain_override_option_wait');
			}
		});
		e.preventDefault();
		return false;
	});


    // Check for internal dependencies
    jQuery( document ).ready( function() {
        "use strict";

        // Check all inner dependencies
        jQuery( '.snowmountain_override_option .snowmountain_override_option_section' ).each( function () {
            snowmountain_override_option_check_dependencies( jQuery( this ) );
        } );

        // Check dependencies on any field change
        jQuery( '.snowmountain_override_option .snowmountain_override_option_item_field [name^="snowmountain_override_option_field_"]' ).on( 'change', function () {
            snowmountain_override_option_check_dependencies( jQuery( this ).parents( '.snowmountain_override_option_section' ) );
        } );

        // Check dependencies on a field with a page template is appear
        jQuery( document ).on( 'trx_addons_action_page_template_selector_appear', function() {
            jQuery( '.snowmountain_override_option .snowmountain_override_option_section' ).each( function () {
                snowmountain_override_option_check_dependencies( jQuery( this ) );
            } );
        } );

    } );

    // Return value of the field
    function snowmountain_override_option_get_field_value(fld, num) {
        var ctrl = fld.parents( '.snowmountain_override_option_item_field' );
        var val  = fld.attr( 'type' ) == 'checkbox' || fld.attr( 'type' ) == 'radio'
            ? (ctrl.find( '[name^="snowmountain_override_option_field_"]:checked' ).length > 0
                    ? (num === true
                            ? ctrl.find( '[name^="snowmountain_override_option_field_"]:checked' ).parent().index() + 1
                            : (ctrl.find( '[name^="snowmountain_override_option_field_"]:checked' ).val() !== ''
                                && '' + ctrl.find( '[name^="snowmountain_override_option_field_"]:checked' ).val() != '0'
                                    ? ctrl.find( '[name^="snowmountain_override_option_field_"]:checked' ).val()
                                    : 1
                            )
                    )
                    : 0
            )
            : (num === true ? fld.find( ':selected' ).index() + 1 : fld.val());
        if (val === undefined || val === null) {
            val = '';
        }
        return val;
    }

    // Check for dependencies
    function snowmountain_override_option_check_dependencies(cont) {
        if ( typeof snowmountain_dependencies == 'undefined' || SNOWMOUNTAIN_STORAGE['check_dependencies_now'] ) {
            return;
        }
        SNOWMOUNTAIN_STORAGE['check_dependencies_now'] = true;
        cont.find( '.snowmountain_override_option_item_field,.snowmountain_override_option_group[data-param]' ).each( function() {
            var ctrl = jQuery( this ),
                id = ctrl.data( 'param' );
            if (id === undefined) {
                return;
            }
            var depend = false, fld;
            for (fld in snowmountain_dependencies) {
                if (fld == id) {
                    depend = snowmountain_dependencies[id];
                    break;
                }
            }
            if (depend) {
                var dep_cnt    = 0, dep_all = 0;
                var dep_cmp    = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
                var dep_strict = typeof depend.strict != 'undefined';
                var val        = undefined;
                var name       = '', subname = '';
                var parts      = '', parts2 = '';
                var i;
                fld = null;
                for (i in depend) {
                    if (i == 'compare' || i == 'strict') {
                        continue;
                    }
                    dep_all++;
                    name    = i;
                    subname = '';
                    if (name.indexOf( '[' ) > 0) {
                        parts   = name.split( '[' );
                        name    = parts[0];
                        subname = parts[1].replace( ']', '' );
                    }
                    // If a name is a selector to the DOM-object
                    if ( name.charAt( 0 ) == '#' || name.charAt( 0 ) == '.' || name.slice( 0, 8 ) == '@editor/' ) {
                        if ( name.charAt( 0 ) == '#' || name.charAt( 0 ) == '.' ) {
                            fld = jQuery( name );
                        }
                        if ( fld && fld.length > 0 ) {
                            var panel = fld.closest('.edit-post-sidebar');
                            if ( panel.length === 0 ) {
                                if ( ! fld.hasClass('snowmountain_inited') ) {
                                    fld.addClass('snowmountain_inited').on('change', function () {
                                        jQuery('.snowmountain_override_option .snowmountain_override_option_section').each( function () {
                                            snowmountain_override_option_check_dependencies(jQuery(this));
                                        } );
                                    } );
                                }
                            } else {
                                if ( ! panel.hasClass('snowmountain_inited') ) {
                                    panel.addClass('snowmountain_inited').on('change', fld, function () {
                                        jQuery('.snowmountain_override_option .snowmountain_override_option_section').each( function () {
                                            snowmountain_override_option_check_dependencies(jQuery(this));
                                        } );
                                    } );
                                }
                            }
                        } else if ( name == '#page_template' || name == '.editor-page-attributes__template select' || name.slice( 0, 8 ) == '@editor/' ) {
                            var prop_check = 'template';
                            if ( name.slice( 0, 8 ) == '@editor/' ) {
                                prop_check = name.slice( 8 );
                            }
                            if ( typeof wp == 'object' && typeof wp.data == 'object' && typeof wp.data.select( 'core/editor' ) == 'object' ) {
                                if ( typeof SNOWMOUNTAIN_STORAGE['editor_props'] == 'undefined' ) {
                                    SNOWMOUNTAIN_STORAGE['editor_props'] = {};
                                }
                                if ( typeof SNOWMOUNTAIN_STORAGE['editor_props'][ prop_check ] == 'undefined' ) {
                                    var prop_val = wp.data.select( 'core/editor' ).getEditedPostAttribute( prop_check );
                                    if ( prop_val !== undefined ) {
                                        SNOWMOUNTAIN_STORAGE['editor_props'][ prop_check ] = prop_val;
                                    }
                                }
                                val = typeof SNOWMOUNTAIN_STORAGE['editor_props'][ prop_check ] != 'undefined' ? SNOWMOUNTAIN_STORAGE['editor_props'][ prop_check ] : '';
                                var $body = jQuery( 'body' );
                                if ( ! $body.hasClass( 'snowmountain_editor_props_listener_inited' ) ) {
                                    $body.addClass( 'snowmountain_editor_props_listener_inited' );
                                    // Call a check_dependencies() on a page template is changed
                                    wp.data.subscribe( function() {
                                        var prop_val = wp.data.select( 'core/editor' ).getEditedPostAttribute( prop_check );
                                        if ( prop_val !== undefined && ( typeof SNOWMOUNTAIN_STORAGE['editor_props'][ prop_check ] == 'undefined' || prop_val != SNOWMOUNTAIN_STORAGE['editor_props'][ prop_check ] ) ) {
                                            SNOWMOUNTAIN_STORAGE['editor_props'][ prop_check ] = prop_val;
                                            jQuery('.snowmountain_override_option .snowmountain_override_option_section').each( function () {
                                                snowmountain_override_option_check_dependencies( jQuery( this ) );
                                            } );
                                        }

                                    } );
                                }
                            }
                        }
                        // A name is a field from options
                    } else {
                        fld = cont.find( '[name="snowmountain_override_option_field_' + name + '"]' );
                    }
                    if ( val !== undefined || ( fld && fld.length > 0 ) ) {
                        if ( val === undefined ) {
                            val = snowmountain_override_option_get_field_value( fld );
                        }
                        if ( val == 'inherit' ) {
                            dep_cnt = 0;
                            dep_all = 1;
                            var parent = ctrl,
                                tag;
                            if ( ! parent.hasClass('snowmountain_override_option_group') ) {
                                parent = parent.parents('.snowmountain_override_option_item');
                            }
                            var lock = parent.find( '.snowmountain_override_option_inherit_lock' );
                            if ( lock.length ) {
                                if ( ! parent.hasClass( 'snowmountain_override_option_inherit_on' ) ) {
                                    lock.trigger( 'click' );
                                }
                            } else if ( ctrl.data('type') == 'select' ) {
                                tag = ctrl.find('select');
                                if ( tag.find('option[value="inherit"]').length ) {
                                    tag.val('inherit').trigger('change');
                                }
                            } else if ( ctrl.data('type') == 'radio' ) {
                                tag = ctrl.find('input[type="radio"][value="inherit"]');
                                if ( tag.length && ! tag.get(0).checked ) {
                                    ctrl.find('input[type="radio"]:checked').get(0).checked = false;
                                    tag.get(0).checked = true;
                                    tag.trigger('change');
                                }
                            }
                            break;
                        } else {
                            if (subname !== '') {
                                parts = val.split( '|' );
                                for (var p = 0; p < parts.length; p++) {
                                    parts2 = parts[p].split( '=' );
                                    if (parts2[0] == subname) {
                                        val = parts2[1];
                                    }
                                }
                            }
                            if ( typeof depend[i] != 'object' && typeof depend[i] != 'array' ) {
                                depend[i] = { '0': depend[i] };
                            }
                            for (var j in depend[i]) {
                                if (
                                    (depend[i][j] == 'not_empty' && val !== '')   // Main field value is not empty - show current field
                                    || (depend[i][j] == 'is_empty' && val === '') // Main field value is empty - show current field
                                    || (val !== '' && ( ! isNaN( depend[i][j] )   // Main field value equal to specified value - show current field
                                            ? val == depend[i][j]
                                            : (dep_strict
                                                    ? val == depend[i][j]
                                                    : ('' + val).indexOf( depend[i][j] ) === 0
                                            )
                                        )
                                    )
                                    || (val !== '' && ("" + depend[i][j]).charAt( 0 ) == '^' && ('' + val).indexOf( depend[i][j].substr( 1 ) ) == -1)
                                    // Main field value not equal to specified value - show current field
                                ) {
                                    dep_cnt++;
                                    break;
                                }
                            }
                        }
                    } else {
                        dep_all--;
                    }
                    if (dep_cnt > 0 && dep_cmp == 'or') {
                        break;
                    }
                }
                if ( ! ctrl.hasClass('snowmountain_override_option_group') ) {
                    ctrl = ctrl.parents('.snowmountain_override_option_item');
                }
                var section = ctrl.parents('.snowmountain_tabs_section'),
                    tab = jQuery( '[aria-labelledby="' + section.attr('aria-labelledby') + '"]' );
                if (((dep_cnt > 0 || dep_all === 0) && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
                    ctrl.slideDown().removeClass( 'snowmountain_override_option_no_use' );
                    if ( section.find('>.snowmountain_override_option_item:not(.snowmountain_override_option_item_info),>.snowmountain_override_option_group[data-param]').length != section.find('.snowmountain_override_option_no_use').length ) {
                        if ( tab.hasClass( 'snowmountain_override_option_item_hidden' ) ) {
                            tab.removeClass('snowmountain_override_option_item_hidden');
                        }
                    }
                } else {
                    ctrl.slideUp().addClass( 'snowmountain_override_option_no_use' );
                    if ( section.find('>.snowmountain_override_option_item:not(.snowmountain_override_option_item_info),>.snowmountain_override_option_group[data-param]').length == section.find('.snowmountain_override_option_no_use').length ) {
                        if ( ! tab.hasClass( 'snowmountain_override_option_item_hidden' ) ) {
                            tab.addClass('snowmountain_override_option_item_hidden');
                            if ( tab.hasClass('ui-state-active') ) {
                                tab.parents('.snowmountain_tabs').find(' > ul > li:not(.snowmountain_override_option_item_hidden)').eq(0).find('> a').trigger('click');
                            }
                        }
                    }
                }
            }

            // Individual dependencies
            //------------------------------------

            // Remove 'false' to disable color schemes less then main scheme!
            // This behavious is not need for the version with sorted schemes (leave false)
            if (false && id == 'color_scheme') {
                fld = ctrl.find( '[name="snowmountain_override_option_field_' + id + '"]' );
                if (fld.length > 0) {
                    val     = snowmountain_override_option_get_field_value( fld );
                    var num = snowmountain_override_option_get_field_value( fld, true );
                    cont.find( '.snowmountain_override_option_item_field' ).each(
                        function() {
                            var ctrl2 = jQuery( this ), id2 = ctrl2.data( 'param' );
                            if (id2 == undefined) {
                                return;
                            }
                            if (id2 == id || id2.substr( -7 ) != '_scheme') {
                                return;
                            }
                            var fld2 = ctrl2.find( '[name="snowmountain_override_option_field_' + id2 + '"]' ),
                                val2     = snowmountain_override_option_get_field_value( fld2 );
                            if (fld2.attr( 'type' ) != 'radio') {
                                fld2 = fld2.find( 'option' );
                            }
                            fld2.each(
                                function(idx2) {
                                    var dom_obj      = jQuery( this ).get( 0 );
                                    dom_obj.disabled = idx2 !== 0 && idx2 < num;
                                    if (dom_obj.disabled) {
                                        if (jQuery( this ).val() == val2) {
                                            if (fld2.attr( 'type' ) == 'radio') {
                                                fld2.each(
                                                    function(idx3) {
                                                        jQuery( this ).get( 0 ).checked = idx3 === 0;
                                                    }
                                                );
                                            } else {
                                                fld2.each(
                                                    function(idx3) {
                                                        jQuery( this ).get( 0 ).selected = idx3 === 0;
                                                    }
                                                );
                                            }
                                        }
                                    }
                                }
                            );
                        }
                    );
                }
            }
        } );
        SNOWMOUNTAIN_STORAGE['check_dependencies_now'] = false;
    }

});
