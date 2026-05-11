<?php
/**
 * Plugin Name: ClasesdeSki Translator (18 languages)
 * Description: 18-language dropdown for clasesdeski.cl. Native ES/EN via data-es/data-en when present; rest via Google Translate with bulletproof cookie + reload. Auto-detects user language via navigator.languages + api.country.is. Mirrors the deckeva.com/.cl implementation.
 * Version:     1.0.0
 * Author:      jpchs1
 *
 * Drop-in mu-plugin - no admin UI, no settings. Loads on every front-end page.
 * Reference: jpchs1/deckeva PRs #71 (install) + #72 (bulletproof fix).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Do not load on wp-admin / login / cron / ajax.
if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
	return;
}

/**
 * Early <html data-lang="es"> hint to avoid FOUC of native ES/EN swaps.
 */
add_action( 'wp_head', function () {
	?>
<script>(function(){try{var l=localStorage.getItem('cdski-lang');if(l==='en'||l==='es'){document.documentElement.setAttribute('data-lang',l);}else{document.documentElement.setAttribute('data-lang','es');}}catch(e){document.documentElement.setAttribute('data-lang','es');}})();</script>
<style id="cdski-translator-css">
/* ════════════════════════════════════════════════════════════
   ClasesdeSki LANG TRANSLATOR DROPDOWN (18 idiomas)
   Scoped to #cdski-langDropdown to avoid theme collisions
   ════════════════════════════════════════════════════════════ */
#cdski-langDropdown {
	position: fixed;
	top: 14px;
	right: 14px;
	z-index: 999999;
	font-family: 'DM Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
}
#cdski-langDropdown .lang-trigger {
	display: inline-flex !important;
	align-items: center !important;
	gap: 7px !important;
	padding: 8px 14px !important;
	background: rgba(255,255,255,0.95) !important;
	border: 1px solid rgba(15,23,42,0.10) !important;
	border-radius: 100px !important;
	cursor: pointer;
	font-family: inherit !important;
	font-size: 12px !important;
	font-weight: 600 !important;
	color: #0f172a !important;
	letter-spacing: 0 !important;
	text-transform: none !important;
	box-shadow: 0 4px 14px rgba(15,23,42,0.10), 0 1px 3px rgba(15,23,42,0.06);
	backdrop-filter: saturate(140%) blur(6px);
	-webkit-backdrop-filter: saturate(140%) blur(6px);
	transition: all 0.2s ease;
	line-height: 1;
}
#cdski-langDropdown .lang-trigger:hover { background: #ffffff !important; }
#cdski-langDropdown .lang-trigger[aria-expanded="true"] { background: rgba(14,107,168,0.10) !important; border-color: rgba(14,107,168,0.20) !important; }
#cdski-langDropdown .lang-flag-current { font-size: 15px; line-height: 1; }
#cdski-langDropdown .lang-label-current { line-height: 1; }
#cdski-langDropdown .lang-trigger svg { transition: transform 0.2s ease; color: #6b7280; }
#cdski-langDropdown .lang-trigger[aria-expanded="true"] svg { transform: rotate(180deg); }

#cdski-langDropdown .lang-menu {
	position: absolute;
	top: calc(100% + 8px);
	right: 0;
	background: #ffffff;
	border: 1px solid rgba(15,23,42,0.10);
	border-radius: 14px;
	padding: 6px;
	box-shadow: 0 12px 30px rgba(15,23,42,0.12), 0 2px 8px rgba(15,23,42,0.06);
	list-style: none;
	margin: 0;
	min-width: 200px;
	max-height: 420px;
	overflow-y: auto;
	display: none;
	z-index: 1000000;
}
#cdski-langDropdown .lang-menu.open { display: block; animation: cdskiLangMenuIn 0.15s ease; }
@keyframes cdskiLangMenuIn {
	from { opacity: 0; transform: translateY(-4px); }
	to   { opacity: 1; transform: translateY(0); }
}
#cdski-langDropdown .lang-menu-section {
	padding: 8px 12px 4px;
	font-family: inherit;
	font-size: 10px;
	font-weight: 700;
	letter-spacing: 0.6px;
	color: #6b7280;
	text-transform: uppercase;
}
#cdski-langDropdown .lang-menu li { margin: 0; list-style: none; }
#cdski-langDropdown .lang-menu li button {
	display: block;
	width: 100%;
	padding: 8px 12px;
	background: none;
	border: 0;
	text-align: left;
	font: 500 13px/1.3 inherit;
	color: #0f172a;
	cursor: pointer;
	border-radius: 8px;
	transition: background 0.15s, color 0.15s;
}
#cdski-langDropdown .lang-menu li button:hover {
	background: rgba(14,107,168,0.08);
	color: #0E6BA8;
}
#cdski-langDropdown .lang-menu li button.active {
	background: rgba(14,107,168,0.12);
	color: #0E6BA8;
	font-weight: 700;
}
#cdski-langDropdown .lang-menu::-webkit-scrollbar { width: 6px; }
#cdski-langDropdown .lang-menu::-webkit-scrollbar-thumb { background: rgba(15,23,42,0.20); border-radius: 3px; }

/* Hide Google Translate widget defaults */
#cdski-google-translate-element { display: none !important; position: absolute; left: -9999px; }
.goog-te-banner-frame.skiptranslate,
.skiptranslate iframe { display: none !important; }
body { top: 0 !important; }
.goog-te-balloon-frame { display: none !important; }
.goog-tooltip, .goog-tooltip:hover { display: none !important; }
.goog-text-highlight { background: transparent !important; box-shadow: none !important; }
font[style*="vertical-align"] { vertical-align: baseline !important; }
.goog-logo-link, .goog-te-gadget { color: transparent !important; }
.goog-te-gadget > span > a { display: none !important; }
.goog-te-gadget { font-size: 0 !important; }

/* Native data-es / data-en swap support (no-op when site has no data-en) */
html[data-lang="en"] [data-es]:not([data-en]) { display: none !important; }
html[data-lang="es"] [data-en]:not([data-es]) { display: none !important; }

/* Mobile */
@media (max-width: 640px) {
	#cdski-langDropdown { top: 10px; right: 10px; }
	#cdski-langDropdown .lang-trigger { padding: 6px 10px !important; font-size: 11px !important; }
	#cdski-langDropdown .lang-label-current { display: none; }
	#cdski-langDropdown .lang-menu { min-width: 180px; right: 0; max-height: 320px; }
}
</style>
	<?php
}, 1 );

/**
 * Dropdown HTML + main JS + Google Translate loader in the footer.
 */
add_action( 'wp_footer', function () {
	?>
<div class="cdski-lang-dropdown" id="cdski-langDropdown">
	<button class="lang-trigger" type="button" onclick="cdskiToggleLangMenu(event)" aria-haspopup="true" aria-expanded="false" aria-label="Cambiar idioma / Change language">
		<span class="lang-flag-current">🌐</span>
		<span class="lang-label-current">Español</span>
		<svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M1.5 3.5 L5 7 L8.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
	</button>
	<ul class="lang-menu" role="menu">
		<li class="lang-menu-section">NATIVE</li>
		<li><button class="active" onclick="cdskiChangeLang('es','Español','🇪🇸')" data-lang="es">🇪🇸 Español</button></li>
		<li><button onclick="cdskiChangeLang('en','English','🇬🇧')" data-lang="en">🇬🇧 English</button></li>
		<li class="lang-menu-section">TRANSLATE</li>
		<li><button onclick="cdskiChangeLang('pt','Português','🇧🇷')" data-lang="pt">🇧🇷 Português</button></li>
		<li><button onclick="cdskiChangeLang('fr','Français','🇫🇷')" data-lang="fr">🇫🇷 Français</button></li>
		<li><button onclick="cdskiChangeLang('de','Deutsch','🇩🇪')" data-lang="de">🇩🇪 Deutsch</button></li>
		<li><button onclick="cdskiChangeLang('it','Italiano','🇮🇹')" data-lang="it">🇮🇹 Italiano</button></li>
		<li><button onclick="cdskiChangeLang('nl','Nederlands','🇳🇱')" data-lang="nl">🇳🇱 Nederlands</button></li>
		<li><button onclick="cdskiChangeLang('pl','Polski','🇵🇱')" data-lang="pl">🇵🇱 Polski</button></li>
		<li><button onclick="cdskiChangeLang('sv','Svenska','🇸🇪')" data-lang="sv">🇸🇪 Svenska</button></li>
		<li><button onclick="cdskiChangeLang('ru','Русский','🇷🇺')" data-lang="ru">🇷🇺 Русский</button></li>
		<li><button onclick="cdskiChangeLang('zh-CN','中文','🇨🇳')" data-lang="zh-CN">🇨🇳 中文</button></li>
		<li><button onclick="cdskiChangeLang('ja','日本語','🇯🇵')" data-lang="ja">🇯🇵 日本語</button></li>
		<li><button onclick="cdskiChangeLang('ko','한국어','🇰🇷')" data-lang="ko">🇰🇷 한국어</button></li>
		<li><button onclick="cdskiChangeLang('ar','العربية','🇸🇦')" data-lang="ar">🇸🇦 العربية</button></li>
		<li><button onclick="cdskiChangeLang('hi','हिन्दी','🇮🇳')" data-lang="hi">🇮🇳 हिन्दी</button></li>
		<li><button onclick="cdskiChangeLang('tr','Türkçe','🇹🇷')" data-lang="tr">🇹🇷 Türkçe</button></li>
		<li><button onclick="cdskiChangeLang('el','Ελληνικά','🇬🇷')" data-lang="el">🇬🇷 Ελληνικά</button></li>
		<li><button onclick="cdskiChangeLang('he','עברית','🇮🇱')" data-lang="he">🇮🇱 עברית</button></li>
	</ul>
</div>
<div id="cdski-google-translate-element" aria-hidden="true"></div>

<script id="cdski-translator-js">
(function(){
	'use strict';

	// Spanish-speaking countries (ISO 3166-1 alpha-2). Source country is Chile -> ES default.
	var ES_COUNTRIES = ['AR','BO','CL','CO','CR','CU','DO','EC','ES','GT','HN','MX','NI','PA','PE','PR','PY','SV','UY','VE'];

	// Does the page have any data-en native translation attribute?
	function hasNativeEN() {
		return !!document.querySelector('[data-en]');
	}

	function applyNativeLang(lang) {
		document.documentElement.setAttribute('data-lang', lang);
	}

	// Cookie helpers - bulletproof multi-domain cleanup
	function clearGoogleTranslateCookies() {
		var host  = location.hostname;
		var bare  = host.replace(/^www\./, '');
		var hosts = ['', host, '.' + host, bare, '.' + bare];
		var paths = ['/', ''];
		var expired = ';expires=Thu, 01 Jan 1970 00:00:00 GMT';
		hosts.forEach(function(h) {
			paths.forEach(function(p) {
				document.cookie = 'googtrans=' + expired + ';path=' + (p || '/') + (h ? ';domain=' + h : '');
			});
		});
	}

	function setGoogleTranslateCookie(target) {
		clearGoogleTranslateCookies();
		var host  = location.hostname;
		var bare  = host.replace(/^www\./, '');
		var value = '/es/' + target;
		var maxAge = ';max-age=2592000;path=/';
		document.cookie = 'googtrans=' + value + maxAge;
		document.cookie = 'googtrans=' + value + maxAge + ';domain=' + host;
		document.cookie = 'googtrans=' + value + maxAge + ';domain=.' + bare;
	}

	function gtIsActive() {
		return document.body.classList.contains('translated-ltr') ||
		       document.body.classList.contains('translated-rtl') ||
		       document.cookie.indexOf('googtrans=') !== -1;
	}

	// Dropdown UI
	window.cdskiToggleLangMenu = function(e) {
		if (e) e.stopPropagation();
		var dd = document.getElementById('cdski-langDropdown');
		if (!dd) return;
		var menu = dd.querySelector('.lang-menu');
		var trigger = dd.querySelector('.lang-trigger');
		var isOpen = menu.classList.toggle('open');
		trigger.setAttribute('aria-expanded', isOpen);
	};

	function updateTriggerUI(code, label, flag) {
		var trigger = document.querySelector('#cdski-langDropdown .lang-trigger');
		if (trigger) {
			var fe = trigger.querySelector('.lang-flag-current');
			var le = trigger.querySelector('.lang-label-current');
			if (fe) fe.textContent = flag;
			if (le) le.textContent = label;
		}
		document.querySelectorAll('#cdski-langDropdown .lang-menu button').forEach(function(b) {
			b.classList.toggle('active', b.getAttribute('data-lang') === code);
		});
	}

	window.cdskiChangeLang = function(code, label, flag) {
		var dd = document.getElementById('cdski-langDropdown');
		if (dd) {
			var menu = dd.querySelector('.lang-menu');
			if (menu) menu.classList.remove('open');
			var trig = dd.querySelector('.lang-trigger');
			if (trig) trig.setAttribute('aria-expanded', 'false');
		}
		updateTriggerUI(code, label, flag);

		try {
			localStorage.setItem('cdski-lang', code);
			localStorage.setItem('cdski-lang-source', 'user');
		} catch(e) {}

		if (code === 'es') {
			if (gtIsActive()) {
				clearGoogleTranslateCookies();
				location.reload();
				return;
			}
			applyNativeLang('es');
			return;
		}

		if (code === 'en' && hasNativeEN()) {
			if (gtIsActive()) {
				clearGoogleTranslateCookies();
				location.reload();
				return;
			}
			applyNativeLang('en');
			return;
		}

		// All other languages (and EN when no native attrs): bulletproof GT cookie + reload
		setGoogleTranslateCookie(code);
		location.reload();
	};

	// Google Translate widget init (called by remote script)
	window.googleTranslateElementInit = function() {
		if (!window.google || !window.google.translate) return;
		new google.translate.TranslateElement({
			pageLanguage: 'es',
			includedLanguages: 'en,es,pt,fr,de,it,nl,pl,sv,ru,zh-CN,ja,ko,ar,hi,tr,el,he',
			autoDisplay: false,
			layout: google.translate.TranslateElement.InlineLayout.SIMPLE
		}, 'cdski-google-translate-element');
	};

	// Outside-click + Esc close
	document.addEventListener('click', function(e) {
		var dd = document.getElementById('cdski-langDropdown');
		if (dd && !dd.contains(e.target)) {
			var menu = dd.querySelector('.lang-menu');
			if (menu) menu.classList.remove('open');
			var trig = dd.querySelector('.lang-trigger');
			if (trig) trig.setAttribute('aria-expanded', 'false');
		}
	});
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') {
			var m = document.querySelector('#cdski-langDropdown .lang-menu.open');
			if (m) {
				m.classList.remove('open');
				var trig = document.querySelector('#cdski-langDropdown .lang-trigger');
				if (trig) trig.setAttribute('aria-expanded', 'false');
			}
		}
	});

	// Restore active state on load
	document.addEventListener('DOMContentLoaded', function() {
		var cookieMatch = document.cookie.match(/googtrans=\/[a-z]+\/([a-zA-Z-]+)/);
		var current = cookieMatch ? cookieMatch[1] : (document.documentElement.getAttribute('data-lang') || 'es');
		var btn = document.querySelector('#cdski-langDropdown .lang-menu button[data-lang="' + current + '"]');
		if (btn) {
			var raw = btn.textContent.trim();
			var parts = raw.split(' ');
			var flag = parts.shift();
			var label = parts.join(' ');
			updateTriggerUI(current, label, flag);
		}
	});

	// Smart auto-detect (navigator + IP geolocation)
	(function autoDetectLang() {
		// 1) Manual user pick wins - never override
		try {
			if (localStorage.getItem('cdski-lang-source') === 'user') return;
		} catch(e) {}

		// 2) If a googtrans cookie is already set, don't override
		if (document.cookie.indexOf('googtrans=') !== -1) return;

		// 3) navigator.languages - fast, no network
		var langs = (navigator.languages && navigator.languages.length)
		            ? navigator.languages
		            : [navigator.language || 'es-CL'];
		var detected = null;
		for (var i = 0; i < langs.length; i++) {
			var tag = String(langs[i]).toLowerCase();
			var m = tag.match(/^([a-z]+)(?:[-_]([a-z]+))?/i);
			if (!m) continue;
			var lang = m[1];
			var region = (m[2] || '').toUpperCase();
			if (lang === 'es') { detected = 'es'; break; }
			if (region && ES_COUNTRIES.indexOf(region) !== -1) { detected = 'es'; break; }
			if (!detected && lang !== 'es') detected = lang;
		}

		// Source language is ES. If detected ES, refine with IP.
		if (!detected || detected === 'es') {
			try {
				fetch('https://api.country.is', {cache: 'default'})
					.then(function(r){ return r.ok ? r.json() : null; })
					.then(function(d){
						if (!d || !d.country) return;
						try { if (localStorage.getItem('cdski-lang-source') === 'user') return; } catch(e) {}
						if (document.cookie.indexOf('googtrans=') !== -1) return;
						if (ES_COUNTRIES.indexOf(d.country) !== -1) return; // stay ES
						// Non-ES country: switch to EN as a sensible default
						setGoogleTranslateCookie('en');
						location.reload();
					})
					.catch(function(){});
			} catch(e) {}
			return;
		}

		// 4) navigator says non-ES - only auto-switch to a GT-supported target
		var SUPPORTED = ['en','pt','fr','de','it','nl','pl','sv','ru','zh','ja','ko','ar','hi','tr','el','he'];
		var target = detected === 'zh' ? 'zh-CN' : detected;
		if (SUPPORTED.indexOf(detected) === -1) target = 'en';
		setGoogleTranslateCookie(target);
		location.reload();
	})();
})();
</script>

<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async></script>
	<?php
}, 99 );
