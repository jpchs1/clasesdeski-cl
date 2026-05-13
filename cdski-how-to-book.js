/**
 * ClasesdeSki — "¿Cómo Reservar?" section: payment methods panel
 *
 * Injects a payment methods panel BELOW the 3-step flow that highlights:
 *  - Webpay (Chile, local)
 *  - Mercado Pago (LATAM)
 *  - PayPal (international, with Visa/Mastercard/Amex)
 *
 * Multilingual (es / en / pt) based on URL path.
 * Mount after window.load + 500ms (avoid React hydration race).
 */
(function () {
	'use strict';
	if (window.__cdskiHowToBookLoaded) return;
	window.__cdskiHowToBookLoaded = true;

	function detectLang() {
		var p = location.pathname || '/';
		if (p.indexOf('/en/') === 0 || p === '/en') return 'en';
		if (p.indexOf('/pt/') === 0 || p === '/pt') return 'pt';
		return 'es';
	}
	var LANG = detectLang();

	var I18N = {
		es: {
			eyebrow: '🔒 Pagos 100% seguros',
			title: 'Métodos de pago',
			subtitle: 'Pagas como prefieras — desde Chile o desde cualquier país del mundo. Procesadores certificados y seguros.',
			cards: [
				{
					name: 'Webpay Plus',
					logo: 'webpay',
					flags: '🇨🇱',
					desc: 'Transbank — tarjetas chilenas de crédito y débito. RedCompra incluido.',
					tag: 'Chile · Local'
				},
				{
					name: 'Mercado Pago',
					logo: 'mercadopago',
					flags: '🇨🇱🇦🇷🇧🇷🇲🇽',
					desc: 'Tarjetas y cuenta Mercado Pago para Chile, Argentina, Brasil, México y más.',
					tag: 'LATAM'
				},
				{
					name: 'PayPal',
					logo: 'paypal',
					flags: '🌎',
					desc: 'Tarjetas de crédito y débito internacionales — Visa, MasterCard, American Express, Discover.',
					tag: 'Internacional',
					highlight: true
				}
			],
			calloutTitle: 'Pago desde el extranjero',
			calloutText: 'Si nos visitas desde fuera de Chile, <strong>PayPal es la opción más simple</strong>: paga directamente desde tu tarjeta o cuenta PayPal, en tu moneda local. Aceptamos:',
			brands: ['VISA', 'MasterCard', 'American Express', 'Discover', 'PayPal Balance']
		},
		en: {
			eyebrow: '🔒 100% secure payments',
			title: 'Payment methods',
			subtitle: 'Pay the way you prefer — from Chile or from anywhere in the world. Certified, secure payment processors.',
			cards: [
				{
					name: 'Webpay Plus',
					logo: 'webpay',
					flags: '🇨🇱',
					desc: 'Transbank — Chilean credit and debit cards. RedCompra included.',
					tag: 'Chile · Local'
				},
				{
					name: 'Mercado Pago',
					logo: 'mercadopago',
					flags: '🇨🇱🇦🇷🇧🇷🇲🇽',
					desc: 'Cards and Mercado Pago account for Chile, Argentina, Brazil, Mexico and more.',
					tag: 'LATAM'
				},
				{
					name: 'PayPal',
					logo: 'paypal',
					flags: '🌎',
					desc: 'International credit and debit cards — Visa, MasterCard, American Express, Discover.',
					tag: 'International',
					highlight: true
				}
			],
			calloutTitle: 'Paying from abroad',
			calloutText: 'If you are visiting us from outside Chile, <strong>PayPal is the easiest option</strong>: pay directly with your credit/debit card or your PayPal balance, in your local currency. We accept:',
			brands: ['VISA', 'MasterCard', 'American Express', 'Discover', 'PayPal Balance']
		},
		pt: {
			eyebrow: '🔒 Pagamentos 100% seguros',
			title: 'Formas de pagamento',
			subtitle: 'Pague como preferir — do Chile ou de qualquer país do mundo. Processadores certificados e seguros.',
			cards: [
				{
					name: 'Webpay Plus',
					logo: 'webpay',
					flags: '🇨🇱',
					desc: 'Transbank — cartões chilenos de crédito e débito. RedCompra incluído.',
					tag: 'Chile · Local'
				},
				{
					name: 'Mercado Pago',
					logo: 'mercadopago',
					flags: '🇧🇷🇨🇱🇦🇷🇲🇽',
					desc: 'Cartões e conta Mercado Pago para Brasil, Chile, Argentina, México e mais.',
					tag: 'LATAM'
				},
				{
					name: 'PayPal',
					logo: 'paypal',
					flags: '🌎',
					desc: 'Cartões de crédito e débito internacionais — Visa, MasterCard, American Express, Discover.',
					tag: 'Internacional',
					highlight: true
				}
			],
			calloutTitle: 'Pagamento do exterior',
			calloutText: 'Se você está nos visitando de fora do Chile, <strong>o PayPal é a opção mais simples</strong>: pague direto com seu cartão de crédito/débito ou saldo PayPal, na sua moeda local. Aceitamos:',
			brands: ['VISA', 'MasterCard', 'American Express', 'Discover', 'PayPal Balance']
		}
	};

	var T = I18N[LANG];

	// Inline SVG logos (text-based for reliability + correct brand colors)
	function logoSvg(name) {
		if (name === 'webpay') {
			return '<svg viewBox="0 0 120 32" width="120" height="32" xmlns="http://www.w3.org/2000/svg"><text x="0" y="22" font-family="Arial, sans-serif" font-size="18" font-weight="700" fill="#e8002a">Webpay</text><text x="68" y="22" font-family="Arial, sans-serif" font-size="13" font-weight="700" fill="#003876">Plus</text></svg>';
		}
		if (name === 'mercadopago') {
			return '<svg viewBox="0 0 130 32" width="130" height="32" xmlns="http://www.w3.org/2000/svg"><ellipse cx="20" cy="16" rx="18" ry="9" fill="#00b1ea"/><text x="6" y="20" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#ffffff">MP</text><text x="44" y="14" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#00b1ea">Mercado</text><text x="44" y="27" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#0058a3">Pago</text></svg>';
		}
		if (name === 'paypal') {
			return '<svg viewBox="0 0 120 32" width="120" height="32" xmlns="http://www.w3.org/2000/svg"><text x="0" y="22" font-family="Arial, sans-serif" font-size="20" font-weight="700" fill="#003087" font-style="italic">Pay</text><text x="40" y="22" font-family="Arial, sans-serif" font-size="20" font-weight="700" fill="#009cde" font-style="italic">Pal</text></svg>';
		}
		return '';
	}

	function buildPanelHTML() {
		var cardsHTML = T.cards.map(function (c) {
			return '<div class="cdski-pay-card" ' + (c.highlight ? 'data-highlight="true"' : '') + '>' +
				'<div class="cdski-pay-card-flag-row">' + c.flags + '</div>' +
				'<div class="cdski-pay-card-logo">' + logoSvg(c.logo) + '</div>' +
				'<h4 class="cdski-pay-card-name">' + c.name + '</h4>' +
				'<p class="cdski-pay-card-desc">' + c.desc + '</p>' +
				'<span class="cdski-pay-card-tag">' + c.tag + '</span>' +
			'</div>';
		}).join('');

		var brandsHTML = T.brands.map(function (b) {
			return '<span class="cdski-pay-card-brand">' + b + '</span>';
		}).join('');

		return '<div id="cdski-payment-methods" lang="' + LANG + '">' +
			'<div class="cdski-pay-header">' +
				'<span class="cdski-pay-eyebrow">' + T.eyebrow + '</span>' +
				'<h3 class="cdski-pay-title">' + T.title + '</h3>' +
				'<p class="cdski-pay-subtitle">' + T.subtitle + '</p>' +
			'</div>' +
			'<div class="cdski-pay-grid">' + cardsHTML + '</div>' +
			'<div class="cdski-pay-callout">' +
				'<strong>' + T.calloutTitle + ':</strong> ' + T.calloutText +
				'<div class="cdski-pay-cards-row">' + brandsHTML + '</div>' +
			'</div>' +
		'</div>';
	}

	function isMounted() {
		var p = document.getElementById('cdski-payment-methods');
		return p && p.getAttribute('lang') === LANG;
	}

	function removeOld() {
		var p = document.getElementById('cdski-payment-methods');
		if (p) p.parentNode.removeChild(p);
	}

	function ensureMounted() {
		if (isMounted()) return;
		removeOld();
		var section = document.getElementById('how-to-book');
		if (!section) return;
		var container = section.querySelector('.max-w-5xl, .max-w-7xl, [class*="max-w-"]') || section.firstElementChild;
		if (!container) return;
		var wrapper = document.createElement('div');
		wrapper.innerHTML = buildPanelHTML();
		container.appendChild(wrapper.firstChild);
	}

	function startWatcher() {
		try {
			var mo = new MutationObserver(function () {
				if (!isMounted()) ensureMounted();
			});
			var target = document.querySelector('main') || document.body;
			if (target) mo.observe(target, { childList: true, subtree: true });
		} catch (e) {}
		var t = 0;
		var iv = setInterval(function () {
			ensureMounted();
			if (++t >= 10) clearInterval(iv);
		}, 1000);
	}

	function boot() {
		ensureMounted();
		startWatcher();
	}

	if (document.readyState === 'complete') {
		setTimeout(boot, 500);
	} else {
		window.addEventListener('load', function () {
			setTimeout(boot, 500);
		}, { once: true });
	}
})();
