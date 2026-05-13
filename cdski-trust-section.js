/**
 * ClasesdeSki — Multilingual Trust Section v2.2
 *
 * Detects page language from URL path (/, /es/, /en/, /pt/) and serves
 * translated copy + WhatsApp message accordingly.
 *
 * v2.2: removed FAQPage from injected @graph (the static HTML already has
 *       an i18n'd FAQPage with 8 questions per language — our injection
 *       caused duplicate FAQ blocks → "Unnamed item / 3 invalid" in
 *       Google's Rich Results Test). LocalBusiness with enriched data
 *       (areaServed, paymentAccepted, aggregateRating, offers) is still
 *       injected for AEO.
 * v2.0: i18n support (es, en, pt-BR).
 * v1.0: initial empathetic trust section + Schema.org JSON-LD for AEO.
 */
(function () {
	'use strict';
	if (window.__cdskiTrustLoaded) return;
	window.__cdskiTrustLoaded = true;

	function detectLang() {
		var p = location.pathname || '/';
		if (p.indexOf('/en/') === 0 || p === '/en') return 'en';
		if (p.indexOf('/pt/') === 0 || p === '/pt') return 'pt';
		return 'es';
	}
	var LANG = detectLang();

	var I18N = {
		es: {
			eyebrow: 'Nuestro compromiso contigo',
			titleA: 'No solo aprendes a esquiar.',
			titleB: 'Vives la montaña.',
			subtitle: 'Cada esquiador es único. Por eso nuestro servicio está pensado para acompañarte en cada paso de tu aventura en los Andes chilenos — desde el primer mensaje hasta el último descenso en <strong>Valle Nevado</strong>, <strong>El Colorado</strong> o <strong>La Parva</strong>.',
			pillars: [
				{ t: 'Atención 100% personalizada', d: 'Conocemos tu nivel, tus objetivos y tu ritmo. Adaptamos cada clase a ti — no al revés. Sin grupos masivos: máximo 4 a 6 personas por instructor, o totalmente privado si lo prefieres.' },
				{ t: 'Comunicación intachable', d: 'Respondemos por WhatsApp en menos de 1 hora. Antes de tu viaje te ayudamos a planificar, durante estamos disponibles ante cualquier ajuste, y después de la experiencia mantenemos el contacto.' },
				{ t: 'Conocemos cada metro', d: 'Valle Nevado, El Colorado y La Parva — sabemos las mejores pistas según tu nivel, los horarios para evitar las filas, los puntos para almorzar con la mejor vista. Información local imposible de googlear.' },
				{ t: 'Acompañamiento integral', d: 'Más allá de la clase: transporte desde Santiago, arriendo de equipo, recomendaciones de hospedaje cerca del centro, planificación completa de tu estadía. Tu única responsabilidad es disfrutar.' }
			],
			stats: ['Años en los Andes chilenos', 'Esquiadores acompañados', 'Google Reviews promedio', 'Centros de ski cubiertos'],
			ctaText: '¿Listo para vivir tu próxima experiencia en la nieve?',
			ctaPrimary: 'Cotizar mi experiencia',
			ctaWhatsapp: 'Hablar por WhatsApp',
			ctaNote: 'Cada esquiador es único. Cada experiencia, irrepetible.',
			waMessage: 'Hola%20CDSKI%20Chile%21%20Vi%20su%20sitio%20web%20y%20me%20interesan%20las%20clases%20de%20ski%20y%20snowboard.%20%C2%BFMe%20pueden%20ayudar%3F',
			schemaDesc: 'Escuela premium de ski y snowboard en los Andes chilenos. Clases personalizadas y experiencias guiadas en Valle Nevado, El Colorado y La Parva, Santiago de Chile. Instructores certificados bilingues, atencion 1-a-1, comunicacion impecable y acompanamiento integral antes, durante y despues de tu viaje.'
		},
		en: {
			eyebrow: 'Our commitment to you',
			titleA: 'More than learning to ski.',
			titleB: 'You live the mountain.',
			subtitle: 'Every skier is unique. That is why our service is built to support you in every step of your adventure in the Chilean Andes — from the first message to the final descent in <strong>Valle Nevado</strong>, <strong>El Colorado</strong> or <strong>La Parva</strong>.',
			pillars: [
				{ t: '100% Personalized attention', d: 'We know your level, your goals and your rhythm. Each lesson adapts to you — not the other way around. No massive groups: maximum 4 to 6 people per instructor, or fully private if you prefer.' },
				{ t: 'Impeccable communication', d: 'We reply on WhatsApp in under 1 hour. Before your trip we help you plan, during your stay we are available for any adjustment, and after the experience we stay in touch.' },
				{ t: 'We know every meter', d: 'Valle Nevado, El Colorado and La Parva — we know the best slopes for your level, the timing to avoid lift queues, the spots with the best lunch view. Local info impossible to Google.' },
				{ t: 'Full support', d: 'Beyond the lesson: transportation from Santiago, equipment rental, lodging recommendations near the resort, full planning of your stay. Your only job is to enjoy.' }
			],
			stats: ['Years in the Chilean Andes', 'Skiers we have guided', 'Google Reviews average', 'Ski resorts covered'],
			ctaText: 'Ready to live your next snow experience?',
			ctaPrimary: 'Get my quote',
			ctaWhatsapp: 'Chat on WhatsApp',
			ctaNote: 'Every skier is unique. Every experience, unrepeatable.',
			waMessage: 'Hi%20CDSKI%20Chile%21%20I%20saw%20your%20website%20and%20I%27m%20interested%20in%20ski%20and%20snowboard%20lessons.%20Can%20you%20help%20me%3F',
			schemaDesc: 'Premium ski and snowboard school in the Chilean Andes. Personalized lessons and guided experiences in Valle Nevado, El Colorado and La Parva, Santiago, Chile. Certified bilingual instructors, 1-on-1 attention, impeccable communication and full support before, during and after your trip.'
		},
		pt: {
			eyebrow: 'Nosso compromisso com você',
			titleA: 'Mais do que aprender a esquiar.',
			titleB: 'Você vive a montanha.',
			subtitle: 'Cada esquiador é único. Por isso, nosso serviço é pensado para acompanhar você em cada passo da sua aventura nos Andes chilenos — desde a primeira mensagem até a última descida em <strong>Valle Nevado</strong>, <strong>El Colorado</strong> ou <strong>La Parva</strong>.',
			pillars: [
				{ t: 'Atenção 100% personalizada', d: 'Conhecemos seu nível, seus objetivos e seu ritmo. Adaptamos cada aula a você — não o contrário. Sem grupos enormes: no máximo 4 a 6 pessoas por instrutor, ou totalmente privado se preferir.' },
				{ t: 'Comunicação impecável', d: 'Respondemos no WhatsApp em menos de 1 hora. Antes da sua viagem ajudamos a planejar, durante estamos disponíveis para qualquer ajuste, e depois da experiência mantemos contato.' },
				{ t: 'Conhecemos cada metro', d: 'Valle Nevado, El Colorado e La Parva — sabemos as melhores pistas para seu nível, os horários sem filas, os pontos com a melhor vista para almoçar. Informação local impossível de googlar.' },
				{ t: 'Acompanhamento integral', d: 'Além da aula: transporte desde Santiago, aluguel de equipamento, recomendações de hospedagem perto da estação, planejamento completo da sua estadia. Sua única tarefa é aproveitar.' }
			],
			stats: ['Anos nos Andes chilenos', 'Esquiadores acompanhados', 'Média Google Reviews', 'Estações de esqui cobertas'],
			ctaText: 'Pronto para viver sua próxima experiência na neve?',
			ctaPrimary: 'Solicitar cotação',
			ctaWhatsapp: 'Falar no WhatsApp',
			ctaNote: 'Cada esquiador é único. Cada experiência, irrepetível.',
			waMessage: 'Ol%C3%A1%20CDSKI%20Chile%21%20Vi%20seu%20site%20e%20estou%20interessado%20nas%20aulas%20de%20ski%20e%20snowboard.%20Podem%20me%20ajudar%3F',
			schemaDesc: 'Escola premium de ski e snowboard nos Andes chilenos. Aulas personalizadas e experiências guiadas em Valle Nevado, El Colorado e La Parva, Santiago, Chile. Instrutores certificados bilingues, atencao 1-a-1, comunicacao impecavel e acompanhamento integral antes, durante e depois da sua viagem.'
		}
	};

	var T = I18N[LANG];

	var SCHEMA = {
		'@context': 'https://schema.org',
		'@graph': [
			{
				'@type': ['LocalBusiness', 'SportsActivityLocation'],
				'@id': 'https://clasesdeski.cl/#organization',
				'name': 'CDSKI — Clases de Ski y Snowboard Chile',
				'alternateName': ['CDSKI Chile', 'CDSKI', 'Clases de Ski Chile'],
				'description': T.schemaDesc,
				'url': 'https://clasesdeski.cl',
				'telephone': '+56940211459',
				'email': 'info@clasesdeski.cl',
				'priceRange': '$$',
				'image': 'https://clasesdeski.cl/og-image.jpg',
				'logo': 'https://clasesdeski.cl/images/logo-cdski.png',
				'currenciesAccepted': 'CLP, USD, BRL, EUR',
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
					{ '@type': 'Country', 'name': 'Chile' },
					{ '@type': 'Country', 'name': 'Brazil' },
					{ '@type': 'Country', 'name': 'United States' },
					{ '@type': 'Country', 'name': 'Argentina' },
					{ '@type': 'Country', 'name': 'Spain' }
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
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Group Ski Lessons / Clases de Ski Grupales' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Private Ski Lessons / Clases de Ski Privadas' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Group Snowboard Lessons / Clases de Snowboard Grupales' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Private Snowboard Lessons / Clases de Snowboard Privadas' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Heliski in the Andes / Heliski en los Andes' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'Kids Ski Programs / Programas de Ski para Ninos' } },
						{ '@type': 'Offer', 'itemOffered': { '@type': 'Service', 'name': 'All-Inclusive Packages with Transport & Lodging' } }
					]
				},
				'knowsLanguage': ['es', 'en', 'pt'],
				'sameAs': [
					'https://www.facebook.com/clasesdeski',
					'https://www.instagram.com/clasesdeski'
				]
			}
		]
	};

	function injectSchema() {
		var old = document.getElementById('cdski-schema-ld');
		if (old) old.parentNode.removeChild(old);
		var s = document.createElement('script');
		s.id = 'cdski-schema-ld';
		s.type = 'application/ld+json';
		s.textContent = JSON.stringify(SCHEMA);
		document.head.appendChild(s);
	}

	function pillarSvg(idx) {
		var paths = [
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>',
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><path d="M3 21h18M5 21V8l7-4 7 4v13M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/></svg>',
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>'
		];
		return paths[idx];
	}

	var STATS_NUMS = ['10+', '500+', '5.0<span class="cdski-trust-stat-star">⭐</span>', '3'];

	function buildSectionHTML() {
		var pillarsHTML = T.pillars.map(function (p, i) {
			return '<article class="cdski-trust-pillar">' +
				'<div class="cdski-trust-pillar-icon">' + pillarSvg(i) + '</div>' +
				'<h3 class="cdski-trust-pillar-title">' + p.t + '</h3>' +
				'<p class="cdski-trust-pillar-desc">' + p.d + '</p>' +
			'</article>';
		}).join('');

		var statsHTML = T.stats.map(function (label, i) {
			return '<div class="cdski-trust-stat">' +
				'<div class="cdski-trust-stat-num">' + STATS_NUMS[i] + '</div>' +
				'<div class="cdski-trust-stat-label">' + label + '</div>' +
			'</div>';
		}).join('');

		var calIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
		var waIcon = '<svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.334.101 11.893c0 2.096.549 4.14 1.595 5.945L0 24l6.335-1.652a12.05 12.05 0 005.71 1.448h.005c6.585 0 11.946-5.336 11.949-11.896 0-3.176-1.24-6.165-3.495-8.411zM12.05 21.785h-.004a9.929 9.929 0 01-5.045-1.378l-.36-.214-3.745.978 1-3.648-.235-.374a9.84 9.84 0 01-1.51-5.26c.002-5.45 4.456-9.884 9.94-9.884 2.654 0 5.146 1.032 7.022 2.903a9.798 9.798 0 012.911 6.99c-.003 5.45-4.458 9.886-9.94 9.886z"/></svg>';

		return '<section id="compromiso-cdski" class="cdski-trust-section" lang="' + LANG + '">' +
			'<div class="cdski-trust-glow-1"></div>' +
			'<div class="cdski-trust-glow-2"></div>' +
			'<div class="cdski-trust-inner">' +
				'<div class="cdski-trust-header">' +
					'<span class="cdski-trust-eyebrow"><span class="cdski-trust-eyebrow-star">⭐</span> ' + T.eyebrow + '</span>' +
					'<h2 class="cdski-trust-title">' + T.titleA + '<br><span class="cdski-trust-title-accent">' + T.titleB + '</span></h2>' +
					'<p class="cdski-trust-subtitle">' + T.subtitle + '</p>' +
				'</div>' +
				'<div class="cdski-trust-pillars">' + pillarsHTML + '</div>' +
				'<div class="cdski-trust-stats">' + statsHTML + '</div>' +
				'<div class="cdski-trust-cta">' +
					'<p class="cdski-trust-cta-text">' + T.ctaText + '</p>' +
					'<div class="cdski-trust-cta-buttons">' +
						'<a href="#pricing" class="cdski-trust-cta-primary">' + calIcon + ' ' + T.ctaPrimary + '</a>' +
						'<a href="https://wa.me/56940211459?text=' + T.waMessage + '" target="_blank" rel="noopener noreferrer" class="cdski-trust-cta-whatsapp">' + waIcon + ' ' + T.ctaWhatsapp + '</a>' +
					'</div>' +
					'<p class="cdski-trust-cta-note">' + T.ctaNote + '</p>' +
				'</div>' +
			'</div>' +
		'</section>';
	}

	function isMounted() {
		var s = document.getElementById('compromiso-cdski');
		return s && s.getAttribute('lang') === LANG;
	}
	function removeOldSection() {
		var s = document.getElementById('compromiso-cdski');
		if (s) s.parentNode.removeChild(s);
	}
	function ensureMounted() {
		if (isMounted()) return;
		removeOldSection();
		var anchor = document.getElementById('why-us');
		if (!anchor) {
			anchor = document.getElementById('pricing');
			if (!anchor) return;
		}
		var wrapper = document.createElement('div');
		wrapper.innerHTML = buildSectionHTML();
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
