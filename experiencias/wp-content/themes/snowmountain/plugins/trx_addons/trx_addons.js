/* global jQuery:false */
/* global SNOWMOUNTAIN_STORAGE:false */
/* global TRX_ADDONS_STORAGE:false */

(function() {
	"use strict";
	
	jQuery(document).on('action.add_googlemap_styles', snowmountain_trx_addons_add_googlemap_styles);
	jQuery(document).on('action.init_shortcodes', snowmountain_trx_addons_init);
	jQuery(document).on('action.init_hidden_elements', snowmountain_trx_addons_init);
	
	// Add theme specific styles to the Google map
	function snowmountain_trx_addons_add_googlemap_styles(e) {
		if (typeof TRX_ADDONS_STORAGE == 'undefined') return;
		TRX_ADDONS_STORAGE['googlemap_styles']['dark'] = [
            {
                "featureType": "administrative.locality",
                "elementType": "all",
                "stylers": [
                    {
                        "hue": "#2c2e33"
                    },
                    {
                        "saturation": 7
                    },
                    {
                        "lightness": 19
                    },
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [
                    {
                        "hue": "#ffffff"
                    },
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 100
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [
                    {
                        "hue": "#ffffff"
                    },
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 100
                    },
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    {
                        "hue": "#bbc0c4"
                    },
                    {
                        "saturation": -93
                    },
                    {
                        "lightness": 31
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "labels",
                "stylers": [
                    {
                        "hue": "#bbc0c4"
                    },
                    {
                        "saturation": -93
                    },
                    {
                        "lightness": 31
                    },
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels",
                "stylers": [
                    {
                        "hue": "#bbc0c4"
                    },
                    {
                        "saturation": -93
                    },
                    {
                        "lightness": -2
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [
                    {
                        "hue": "#e9ebed"
                    },
                    {
                        "saturation": -90
                    },
                    {
                        "lightness": -8
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [
                    {
                        "hue": "#e9ebed"
                    },
                    {
                        "saturation": 10
                    },
                    {
                        "lightness": 69
                    },
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [
                    {
                        "hue": "#e9ebed"
                    },
                    {
                        "saturation": -78
                    },
                    {
                        "lightness": 67
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            }
        ];
	}
	
	
	function snowmountain_trx_addons_init(e, container) {
		if (arguments.length < 2) var container = jQuery('body');
		if (container===undefined || container.length === undefined || container.length == 0) return;
		container.find('.sc_countdown_item canvas:not(.inited)').addClass('inited').attr('data-color', SNOWMOUNTAIN_STORAGE['alter_link_color']);
	}

})();