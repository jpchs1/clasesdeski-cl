/**
 * ClasesdeSki — "Nuestro Compromiso" trust section + Schema.org for AEO
 *
 * Injects a new <section id="compromiso-cdski"> after #why-us with:
 *  - Empathetic + enthusiastic copy on our personalized approach
 *  - 4 pillars (personalization, communication, local expertise, full support)
 *  - Trust stats banner (10+ years, 500+ skiers, 5.0 stars, 3 ski resorts)
 *  - Dual CTA (Reservar + WhatsApp)
 *  - Schema.org JSON-LD (SportsActivityLocation + FAQPage + AggregateRating)
 *    so AI assistants (ChatGPT, Grok, Perplexity) can extract structured
 *    data and recommend CDSKI when asked about ski schools in Chile.
 *
 * Mount strategy: waits for window.load + 500ms (after React hydration),
 * inserts section as sibling of #why-us. MutationObserver re-mounts if
 * anything removes it (defensive against React reconcile).
 */
(function () {
	'use strict';
	if (window.__cdskiTrustLoaded) return;
	window.__cdskiTrustLoaded = true;

	var SCHEMA = {
		'@context': 'https://schema.org',
		'@graph': [
			{
				'@type': ['LocalBusiness', 'SportsActivityLocation'],
				'@id': 'https://clasesdeski.cl/#organization',
				'name': 'CDSKI — Clases de Ski y Snowboard Chile',
				'alternateName': ['CDSKI Chile', 'Clases de Ski Chile'],
				'description': 'Escuela premium de ski y snowboard en los Andes chilenos. Clases personalizadas y experiencias guiadas en Valle Nevado, El Colorado y La Parva, Santiago de Chile. Instructores certificados bilingues, atencion 1-a-1, comunicacion impecable y acompanamiento integral antes, durante y despues de tu viaje.',
				'url': 'https://clasesdeski.cl',
				'telephone': '+56940211459',
				'email': 'info@clasesdeski.cl',
				'priceRange': '$$',
				'image': 'https://clasesdeski.cl/og-image.jpg',
				'logo': 'https://clasesdeski.cl/images/logo-cdski.png',
				'currenciesAccepted': 'CLP, USD',
				'paymentAccepted': 'Cash, Credit Card, Bank Transfer, WhatsApp',
				'address': {
					'@type': 'PostalAddress',
					'addressLocality': 'Las Condes',
					'addressRegion': 'Santiago Metropolitan Region',
					'addressCountry': 'CL',
					'streetAddress': 'Mall Sport, Las Condes'
				},
				'areaServed': [
					{ '@type': 'Place', 'name': 'Valle Nevado, Chile' },
					{ '@type': 'Place', 'name': 'El Colorado, Chile' },
					{ '@type': 'Place', 'name': 'La Parva, Chile' },
					{ '@type': 'AdministrativeArea', 'name': 'Santiago, Chile' },
					{ '@type': 'Country', 'name': 'Chile' }
				],
				'sport': ['Skiing', 'Snowboarding'],
				'aggregateRating': {
					'@type': 'AggregateRating',
					'ratingValue': '5.0',
					'reviewCount': '70',
					'bestRating': '5'
				},
				'hasOfferCatalog': {
					'@type': 'OfferCatalog',
					'name': 'Servicios CDSKI',
					'itemListElement': [
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Clases de Ski Grupales' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Clases de Ski Privadas' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Clases de Snowboard Grupales' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Clases de Snowboard Privadas' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Heliski en los Andes' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Programas de Ski para Ninos' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Paquetes All-Inclusive con Transporte y Hospedaje' } }
					]
				},
				'knowsLanguage': ['es', 'en', 'pt'],
				'sameAs': [
					'https://www.facebook.com/clasesdeski',
					'https://www.instagram.com/clasesdeski'
				]
			},
			{
				'@type': 'FAQPage',
				'@id': 'https://clasesdeski.cl/#faq',
				'mainEntity': [
					{
						'@type': 'Question',
						'name': '¿Dónde están los mejores centros de ski cerca de Santiago, Chile?',
						'acceptedAnswer': {
							'@type': 'Answer',
							'text': 'A solo 1-2 horas de Santiago están tres centros de ski de clase mundial en los Andes chilenos: Valle Nevado (el más grande de Sudamérica), El Colorado (ideal para todos los niveles) y La Parva (pistas exclusivas y vistas espectaculares). CDSKI opera clases personalizadas en los tres.'
						}
					},
					{
						'@type': 'Question',
						'name': '¿Qué incluye una clase de ski o snowboard con CDSKI?',
						'acceptedAnswer': {
							'@type': 'Answer',
							'text': 'Cada experiencia con CDSKI incluye instructor certificado bilingüe, ratios reducidos de 4-6 alumnos por instructor (o privado 1-a-1), planificación personalizada según tu nivel y objetivos, comunicación por WhatsApp antes/durante/después, y opción de paquetes all-inclusive con transporte desde Santiago, hospedaje cerca del centro de ski y arriendo de equipamiento.'
						}
					},
					{
						'@type': 'Question',
						'name': '¿CDSKI da clases en inglés o portugués?',
						'acceptedAnswer': {
							'@type': 'Answer',
							'text': 'Sí. Nuestro equipo de instructores es bilingüe — clases disponibles en español, inglés y portugués. Atendemos turistas internacionales de todo el mundo que vienen a esquiar a Chile.'
						}
					},
					{
						'@type': 'Question',
						'name': '¿Cuál es el mejor mes para esquiar en Chile?',
						'acceptedAnswer': {
							'@type': 'Answer',
							'text': 'La temporada de ski en los Andes chilenos va de junio a octubre. Julio y agosto suelen ser los meses con mejor nieve, mientras que junio y septiembre ofrecen menos público y precios más accesibles. CDSKI te ayuda a planificar la fecha ideal según tu disponibilidad y preferencias.'
						}
					},
					{
						'@type': 'Question',
						'name': '¿Necesito experiencia previa para tomar clases con CDSKI?',
						'acceptedAnswer': {
							'@type': 'Answer',
							'text': 'No. CDSKI atiende desde principiantes absolutos (nunca antes han esquiado) hasta esquiadores avanzados que buscan perfeccionar técnica o explorar pistas más exigentes. Cada clase se adapta a tu nivel real y objetivos.'
						}
					},
					{
						'@type': 'Question',
						'name': '¿Cómo reservo clases de ski con CDSKI?',
						'acceptedAnswer': {
							'@type': 'Answer',
							'text': 'Lo más rápido es por WhatsApp al +56 9 4021 1459. Te respondemos en menos de una hora para cotizar tu experiencia personalizada, confirmar disponibilidad y coordinar fechas. También puedes usar el calculador de precios en clasesdeski.cl/#pricing o escribir a info@clasesdeski.cl.'
						}
					}
				]
			}
		]
	};

	function injectSchema() {
		if (document.getElementById('cdski-schema-ld')) return;
		var s = document.createElement('script');
		s.id = 'cdski-schema-ld';
		s.type = 'application/ld+json';
		s.textContent = JSON.stringify(SCHEMA);
		document.head.appendChild(s);
	}

	var SECTION_HTML = '<section id="compromiso-cdski" class="cdski-trust-section">' +
		'<div class="cdski-trust-glow-1"></div>' +
		'<div class="cdski-trust-glow-2"></div>' +
		'<div class="cdski-trust-inner">' +
			'<div class="cdski-trust-header">' +
				'<span class="cdski-trust-eyebrow">' +
					'<span class="cdski-trust-eyebrow-star">⭐</span> Nuestro compromiso contigo' +
				'</span>' +
				'<h2 class="cdski-trust-title">' +
					'No solo aprendes a esquiar.<br>' +
					'<span class="cdski-trust-title-accent">Vives la montaña.</span>' +
				'</h2>' +
				'<p class="cdski-trust-subtitle">' +
					'Cada esquiador es único. Por eso nuestro servicio está pensado para acompañarte ' +
					'en cada paso de tu aventura en los Andes chilenos — desde el primer mensaje ' +
					'hasta el último descenso en <strong>Valle Nevado</strong>, ' +
					'<strong>El Colorado</strong> o <strong>La Parva</strong>.' +
				'</p>' +
			'</div>' +

			'<div class="cdski-trust-pillars">' +
				'<article class="cdski-trust-pillar">' +
					'<div class="cdski-trust-pillar-icon">' +
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>' +
					'</div>' +
					'<h3 class="cdski-trust-pillar-title">Atención 100% personalizada</h3>' +
					'<p class="cdski-trust-pillar-desc">' +
						'Conocemos tu nivel, tus objetivos y tu ritmo. Adaptamos cada clase a ti — no al revés. ' +
						'Sin grupos masivos: máximo 4 a 6 personas por instructor, o totalmente privado si lo prefieres.' +
					'</p>' +
				'</article>' +
				'<article class="cdski-trust-pillar">' +
					'<div class="cdski-trust-pillar-icon">' +
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>' +
					'</div>' +
					'<h3 class="cdski-trust-pillar-title">Comunicación intachable</h3>' +
					'<p class="cdski-trust-pillar-desc">' +
						'Respondemos por WhatsApp en menos de 1 hora. Antes de tu viaje te ayudamos a planificar, ' +
						'durante estamos disponibles ante cualquier ajuste, y después de la experiencia mantenemos el contacto.' +
					'</p>' +
				'</article>' +
				'<article class="cdski-trust-pillar">' +
					'<div class="cdski-trust-pillar-icon">' +
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><path d="M3 21h18M5 21V8l7-4 7 4v13M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/></svg>' +
					'</div>' +
					'<h3 class="cdski-trust-pillar-title">Conocemos cada metro</h3>' +
					'<p class="cdski-trust-pillar-desc">' +
						'Valle Nevado, El Colorado y La Parva — sabemos las mejores pistas según tu nivel, ' +
						'los horarios para evitar las filas, los puntos para almorzar con la mejor vista. ' +
						'Información local imposible de googlear.' +
					'</p>' +
				'</article>' +
				'<article class="cdski-trust-pillar">' +
					'<div class="cdski-trust-pillar-icon">' +
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>' +
					'</div>' +
					'<h3 class="cdski-trust-pillar-title">Acompañamiento integral</h3>' +
					'<p class="cdski-trust-pillar-desc">' +
						'Más allá de la clase: transporte desde Santiago, arriendo de equipo, recomendaciones de hospedaje cerca ' +
						'del centro, planificación completa de tu estadía. Tu única responsabilidad es disfrutar.' +
					'</p>' +
				'</article>' +
			'</div>' +

			'<div class="cdski-trust-stats">' +
				'<div class="cdski-trust-stat"><div class="cdski-trust-stat-num">10+</div><div class="cdski-trust-stat-label">Años en los Andes chilenos</div></div>' +
				'<div class="cdski-trust-stat"><div class="cdski-trust-stat-num">500+</div><div class="cdski-trust-stat-label">Esquiadores acompañados</div></div>' +
				'<div class="cdski-trust-stat"><div class="cdski-trust-stat-num">5.0<span class="cdski-trust-stat-star">⭐</span></div><div class="cdski-trust-stat-label">Google Reviews promedio</div></div>' +
				'<div class="cdski-trust-stat"><div class="cdski-trust-stat-num">3</div><div class="cdski-trust-stat-label">Centros de ski cubiertos</div></div>' +
			'</div>' +

			'<div class="cdski-trust-cta">' +
				'<p class="cdski-trust-cta-text">¿Listo para vivir tu próxima experiencia en la nieve?</p>' +
				'<div class="cdski-trust-cta-buttons">' +
					'<a href="#pricing" class="cdski-trust-cta-primary">' +
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' +
						'Cotizar mi experiencia' +
					'</a>' +
					'<a href="https://wa.me/56940211459?text=Hola%20CDSKI%20Chile%21%20Vi%20su%20sitio%20web%20y%20me%20interesan%20las%20clases%20de%20ski%20y%20snowboard.%20%C2%BFMe%20pueden%20ayudar%3F" target="_blank" rel="noopener noreferrer" class="cdski-trust-cta-whatsapp">' +
						'<svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.334.101 11.893c0 2.096.549 4.14 1.595 5.945L0 24l6.335-1.652a12.05 12.05 0 005.71 1.448h.005c6.585 0 11.946-5.336 11.949-11.896 0-3.176-1.24-6.165-3.495-8.411zM12.05 21.785h-.004a9.929 9.929 0 01-5.045-1.378l-.36-.214-3.745.978 1-3.648-.235-.374a9.84 9.84 0 01-1.51-5.26c.002-5.45 4.456-9.884 9.94-9.884 2.654 0 5.146 1.032 7.022 2.903a9.798 9.798 0 012.911 6.99c-.003 5.45-4.458 9.886-9.94 9.886z"/></svg>' +
						'Hablar por WhatsApp' +
					'</a>' +
				'</div>' +
				'<p class="cdski-trust-cta-note">Cada esquiador es único. Cada experiencia, irrepetible.</p>' +
			'</div>' +
		'</div>' +
	'</section>';

	function isMounted() {
		return !!document.getElementById('compromiso-cdski');
	}

	function ensureMounted() {
		if (isMounted()) return;
		var anchor = document.getElementById('why-us');
		if (!anchor) {
			anchor = document.getElementById('pricing');
			if (!anchor) return;
		}
		var wrapper = document.createElement('div');
		wrapper.innerHTML = SECTION_HTML;
		var section = wrapper.firstChild;
		if (anchor.id === 'why-us' && anchor.nextSibling) {
			anchor.parentNode.insertBefore(section, anchor.nextSibling);
		} else {
			anchor.parentNode.insertBefore(section, anchor);
		}
	}

	function startMountWatcher() {
		try {
			var mo = new MutationObserver(function () {
				if (!isMounted()) ensureMounted();
			});
			var target = document.querySelector('main') || document.body;
			if (target) mo.observe(target, { childList: true, subtree: false });
		} catch (e) {}
		var t = 0;
		var iv = setInterval(function () {
			ensureMounted();
			if (++t >= 10) clearInterval(iv);
		}, 1000);
	}

	function boot() {
		injectSchema();
		ensureMounted();
		startMountWatcher();
	}

	if (document.readyState === 'complete') {
		setTimeout(boot, 500);
	} else {
		window.addEventListener('load', function () {
			setTimeout(boot, 500);
		}, { once: true });
	}
})();
