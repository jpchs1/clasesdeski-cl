function mounthood_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof MOUNTHOOD_STORAGE['googlemap_init_obj'] == 'undefined') mounthood_googlemap_init_styles();
	MOUNTHOOD_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		MOUNTHOOD_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: MOUNTHOOD_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		mounthood_googlemap_create(id);

	} catch (e) {};
}

function mounthood_googlemap_create(id) {
	"use strict";

	// Create map
	MOUNTHOOD_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(MOUNTHOOD_STORAGE['googlemap_init_obj'][id].dom, MOUNTHOOD_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers)
		MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	mounthood_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].map)
			MOUNTHOOD_STORAGE['googlemap_init_obj'][id].map.setCenter(MOUNTHOOD_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function mounthood_googlemap_add_markers(id) {
	"use strict";
	for (var i in MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (MOUNTHOOD_STORAGE['googlemap_init_obj'].geocoder == '') MOUNTHOOD_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			MOUNTHOOD_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			MOUNTHOOD_STORAGE['googlemap_init_obj'].geocoder.geocode({address: MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = MOUNTHOOD_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					MOUNTHOOD_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						mounthood_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(MOUNTHOOD_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: MOUNTHOOD_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].title;
			MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				MOUNTHOOD_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				MOUNTHOOD_STORAGE['googlemap_init_obj'][id].map.setCenter(MOUNTHOOD_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								MOUNTHOOD_STORAGE['googlemap_init_obj'][id].map,
								MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			MOUNTHOOD_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function mounthood_googlemap_refresh() {
	"use strict";
	for (id in MOUNTHOOD_STORAGE['googlemap_init_obj']) {
		mounthood_googlemap_create(id);
	}
}

function mounthood_googlemap_init_styles() {
    'use strict';
	// Init Google map
	MOUNTHOOD_STORAGE['googlemap_init_obj'] = {};
	MOUNTHOOD_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.mounthood_theme_googlemap_styles!==undefined)
		MOUNTHOOD_STORAGE['googlemap_styles'] = mounthood_theme_googlemap_styles(MOUNTHOOD_STORAGE['googlemap_styles']);
}