/**
 * ClasesdeSki Translator (18 languages) — v1.1
 *
 * Self-contained drop-in. Wire via:
 *   <script src="/cdski-translator.js" defer></script>
 * before </body> on every index.html.
 *
 * v1.1 fixes the React/Next.js hydration race: instead of mutating the
 * existing ES/EN/PT pill row in the React-controlled nav (which gets
 * wiped on reconcile), we now:
 *   - hide the original pill row via a pure-CSS :has() rule, and
 *   - mount the new dropdown as a floating top-right element on
 *     document.body, OUTSIDE React's tree.
 * A MutationObserver re-mounts the dropdown if anything removes it.
 *
 * Auto-detect is non-intrusive: it never auto-redirects between the
 * native /, /es/, /en/, /pt/ pages. It only sets the Google Translate
 * cookie + reload for the 15 non-native languages on first visit.
 *
 * Reference: jpchs1/deckeva PRs #71 + #72.
 */
(function () {
	'use strict';

	if (window.__cdskiTranslatorLoaded) return;
	window.__cdskiTranslatorLoaded = true;
	window.__cdskiTranslatorVersion = '1.1.0';

	var NATIVE_PATHS = { es: '/es/', en: '/en/', pt: '/pt/' };
	var ES_COUNTRIES = ['AR','BO','CL','CO','CR','CU','DO','EC','ES','GT','HN','MX','NI','PA','PE','PR','PY','SV','UY','VE'];
	var SUPPORTED_GT = ['en','pt','fr','de','it','nl','pl','sv','ru','zh','ja','ko','ar','hi','tr','el','he'];

	function currentPathLang() {
		var p = location.pathname || '/';
		if (p.indexOf('/en/') === 0 || p === '/en') return 'en';
		if (p.indexOf('/pt/') === 0 || p === '/pt') return 'pt';
		if (p.indexOf('/es/') === 0 || p === '/es') return 'es';
		return 'es';
	}

	function pageSourceLang() { return currentPathLang(); }

	function clearGoogleTranslateCookies() {
		var host = location.hostname;
		var bare = host.replace(/^www\./, '');
		var hosts = ['', host, '.' + host, bare, '.' + bare];
		var paths = ['/', ''];
		var expired = ';expires=Thu, 01 Jan 1970 00:00:00 GMT';
		hosts.forEach(function (h) {
			paths.forEach(function (p) {
				document.cookie = 'googtrans=' + expired + ';path=' + (p || '/') + (h ? ';domain=' + h : '');
			});
		});
	}

	function setGoogleTranslateCookie(target) {
		clearGoogleTranslateCookies();
		var host = location.hostname;
		var bare = host.replace(/^www\./, '');
		var value = '/' + pageSourceLang() + '/' + target;
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

	var STYLE = [
		// Hide the original ES/EN/PT pill row (and any 2-of-3 variant) without touching the DOM
		'div:has(> a[href="/es/"]):has(> a[href="/en/"]):has(> a[href="/pt/"]){display:none !important}',
		'div:has(> a[href="/es"]):has(> a[href="/en"]):has(> a[href="/pt"]){display:none !important}',

		'#cdski-langDropdown{position:fixed !important;top:14px;right:14px;z-index:2147483646;font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;line-height:1}',
		'#cdski-langDropdown .cdski-trigger{display:inline-flex;align-items:center;gap:7px;padding:7px 13px;background:rgba(255,255,255,0.96);color:#0f172a;border:1px solid rgba(15,23,42,0.10);border-radius:100px;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;letter-spacing:0;text-transform:none;box-shadow:0 4px 14px rgba(15,23,42,0.12),0 1px 3px rgba(15,23,42,0.06);backdrop-filter:saturate(140%) blur(6px);-webkit-backdrop-filter:saturate(140%) blur(6px);transition:all 0.2s ease;line-height:1}',
		'#cdski-langDropdown .cdski-trigger:hover{background:#fff}',
		'#cdski-langDropdown .cdski-trigger[aria-expanded="true"]{background:rgba(249,115,22,0.10);border-color:rgba(249,115,22,0.30)}',
		'#cdski-langDropdown .cdski-flag{font-size:14px;line-height:1}',
		'#cdski-langDropdown .cdski-label{line-height:1}',
		'#cdski-langDropdown .cdski-trigger svg{transition:transform 0.2s ease;color:currentColor;opacity:0.8}',
		'#cdski-langDropdown .cdski-trigger[aria-expanded="true"] svg{transform:rotate(180deg)}',
		'#cdski-langDropdown .cdski-menu{position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid rgba(15,23,42,0.10);border-radius:14px;padding:6px;box-shadow:0 12px 30px rgba(15,23,42,0.15),0 2px 8px rgba(15,23,42,0.08);list-style:none;margin:0;min-width:200px;max-height:420px;overflow-y:auto;display:none;z-index:2147483647}',
		'#cdski-langDropdown .cdski-menu.open{display:block;animation:cdskiIn 0.15s ease}',
		'@keyframes cdskiIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}',
		'#cdski-langDropdown .cdski-section{padding:8px 12px 4px;font:700 10px/1.2 inherit;letter-spacing:0.6px;color:#6b7280;text-transform:uppercase}',
		'#cdski-langDropdown .cdski-menu li{margin:0;list-style:none}',
		'#cdski-langDropdown .cdski-menu li button{display:block;width:100%;padding:8px 12px;background:none;border:0;text-align:left;font:500 13px/1.3 inherit;color:#0f172a;cursor:pointer;border-radius:8px;transition:background 0.15s,color 0.15s}',
		'#cdski-langDropdown .cdski-menu li button:hover{background:rgba(249,115,22,0.08);color:#ea580c}',
		'#cdski-langDropdown .cdski-menu li button.active{background:rgba(249,115,22,0.12);color:#ea580c;font-weight:700}',
		'#cdski-langDropdown .cdski-menu::-webkit-scrollbar{width:6px}',
		'#cdski-langDropdown .cdski-menu::-webkit-scrollbar-thumb{background:rgba(15,23,42,0.20);border-radius:3px}',
		'#cdski-google-translate-element{display:none !important;position:absolute;left:-9999px}',
		'.goog-te-banner-frame.skiptranslate,.skiptranslate iframe{display:none !important}',
		'body{top:0 !important}',
		'.goog-te-balloon-frame,.goog-tooltip,.goog-tooltip:hover{display:none !important}',
		'.goog-text-highlight{background:transparent !important;box-shadow:none !important}',
		'font[style*="vertical-align"]{vertical-align:baseline !important}',
		'.goog-logo-link,.goog-te-gadget{color:transparent !important}',
		'.goog-te-gadget > span > a{display:none !important}',
		'.goog-te-gadget{font-size:0 !important}',
		'@media (max-width:640px){#cdski-langDropdown{top:10px;right:10px}#cdski-langDropdown .cdski-trigger{padding:6px 10px;font-size:11px}#cdski-langDropdown .cdski-label{display:none}#cdski-langDropdown .cdski-menu{min-width:180px;right:0;max-height:320px}}'
	].join('');

	var LANGS = [
		['es', 'Español',      '\u{1F1EA}\u{1F1F8}', 'NATIVE'],
		['en', 'English',      '\u{1F1EC}\u{1F1E7}', 'NATIVE'],
		['pt', 'Português',    '\u{1F1E7}\u{1F1F7}', 'NATIVE'],
		['fr', 'Français',     '\u{1F1EB}\u{1F1F7}', 'TRANSLATE'],
		['de', 'Deutsch',      '\u{1F1E9}\u{1F1EA}', 'TRANSLATE'],
		['it', 'Italiano',     '\u{1F1EE}\u{1F1F9}', 'TRANSLATE'],
		['nl', 'Nederlands',   '\u{1F1F3}\u{1F1F1}', 'TRANSLATE'],
		['pl', 'Polski',       '\u{1F1F5}\u{1F1F1}', 'TRANSLATE'],
		['sv', 'Svenska',      '\u{1F1F8}\u{1F1EA}', 'TRANSLATE'],
		['ru', 'Русский',      '\u{1F1F7}\u{1F1FA}', 'TRANSLATE'],
		['zh-CN', '中文',       '\u{1F1E8}\u{1F1F3}', 'TRANSLATE'],
		['ja', '日本語',         '\u{1F1EF}\u{1F1F5}', 'TRANSLATE'],
		['ko', '한국어',         '\u{1F1F0}\u{1F1F7}', 'TRANSLATE'],
		['ar', 'العربية',       '\u{1F1F8}\u{1F1E6}', 'TRANSLATE'],
		['hi', 'हिन्दी',          '\u{1F1EE}\u{1F1F3}', 'TRANSLATE'],
		['tr', 'Türkçe',       '\u{1F1F9}\u{1F1F7}', 'TRANSLATE'],
		['el', 'Ελληνικά',      '\u{1F1EC}\u{1F1F7}', 'TRANSLATE'],
		['he', 'עברית',        '\u{1F1EE}\u{1F1F1}', 'TRANSLATE']
	];

	function buildDropdown() {
		var dd = document.createElement('div');
		dd.id = 'cdski-langDropdown';

		var currentCookie = (document.cookie.match(/googtrans=\/[a-z-]+\/([a-zA-Z-]+)/) || [])[1];
		var currentLang = currentCookie || currentPathLang();
		var currentEntry = LANGS.filter(function (l) { return l[0] === currentLang; })[0] || LANGS[0];

		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'cdski-trigger';
		btn.setAttribute('aria-haspopup', 'true');
		btn.setAttribute('aria-expanded', 'false');
		btn.setAttribute('aria-label', 'Cambiar idioma / Change language');
		btn.innerHTML =
			'<span class="cdski-flag">\u{1F310}</span>' +
			'<span class="cdski-label"></span>' +
			'<svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M1.5 3.5 L5 7 L8.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		btn.querySelector('.cdski-label').textContent = currentEntry[1];
		dd.appendChild(btn);

		var ul = document.createElement('ul');
		ul.className = 'cdski-menu';
		ul.setAttribute('role', 'menu');
		var lastSection = null;
		LANGS.forEach(function (l) {
			if (l[3] !== lastSection) {
				lastSection = l[3];
				var sec = document.createElement('li');
				sec.className = 'cdski-section';
				sec.textContent = lastSection;
				ul.appendChild(sec);
			}
			var li = document.createElement('li');
			var b = document.createElement('button');
			b.type = 'button';
			b.setAttribute('data-lang', l[0]);
			b.textContent = l[2] + ' ' + l[1];
			if (l[0] === currentLang) b.className = 'active';
			b.addEventListener('click', function () { changeLang(l[0], l[1], l[2]); });
			li.appendChild(b);
			ul.appendChild(li);
		});
		dd.appendChild(ul);

		btn.addEventListener('click', function (e) {
			e.stopPropagation();
			var isOpen = ul.classList.toggle('open');
			btn.setAttribute('aria-expanded', isOpen);
		});

		return dd;
	}

	function changeLang(code, label, flag) {
		var dd = document.getElementById('cdski-langDropdown');
		if (dd) {
			dd.querySelector('.cdski-menu').classList.remove('open');
			dd.querySelector('.cdski-trigger').setAttribute('aria-expanded', 'false');
			dd.querySelector('.cdski-label').textContent = label;
			dd.querySelectorAll('.cdski-menu button').forEach(function (b) {
				b.classList.toggle('active', b.getAttribute('data-lang') === code);
			});
		}

		try {
			localStorage.setItem('cdski-lang', code);
			localStorage.setItem('cdski-lang-source', 'user');
		} catch (e) {}

		if (NATIVE_PATHS[code]) {
			clearGoogleTranslateCookies();
			if (location.pathname.indexOf(NATIVE_PATHS[code]) !== 0) {
				location.href = NATIVE_PATHS[code];
			} else if (gtIsActive()) {
				location.reload();
			}
			return;
		}

		setGoogleTranslateCookie(code);
		location.reload();
	}

	function injectStyle() {
		if (document.getElementById('cdski-translator-css')) return;
		var s = document.createElement('style');
		s.id = 'cdski-translator-css';
		s.textContent = STYLE;
		(document.head || document.documentElement).appendChild(s);
	}

	function injectGTContainer() {
		if (document.getElementById('cdski-google-translate-element')) return;
		var d = document.createElement('div');
		d.id = 'cdski-google-translate-element';
		d.setAttribute('aria-hidden', 'true');
		document.body.appendChild(d);
	}

	function ensureMounted() {
		if (document.getElementById('cdski-langDropdown')) return;
		var dd = buildDropdown();
		document.body.appendChild(dd);
	}

	function attachGlobalListeners() {
		if (window.__cdskiListenersAttached) return;
		window.__cdskiListenersAttached = true;
		document.addEventListener('click', function (e) {
			var node = document.getElementById('cdski-langDropdown');
			if (node && !node.contains(e.target)) {
				var menu = node.querySelector('.cdski-menu');
				if (menu) menu.classList.remove('open');
				var trig = node.querySelector('.cdski-trigger');
				if (trig) trig.setAttribute('aria-expanded', 'false');
			}
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				var m = document.querySelector('#cdski-langDropdown .cdski-menu.open');
				if (m) {
					m.classList.remove('open');
					var trig = document.querySelector('#cdski-langDropdown .cdski-trigger');
					if (trig) trig.setAttribute('aria-expanded', 'false');
				}
			}
		});
	}

	function startMountWatcher() {
		// Re-mount if anything (React, theme JS, etc.) removes our dropdown.
		// Cheap: a single getElementById on each batch of mutations.
		try {
			var mo = new MutationObserver(function () {
				ensureMounted();
			});
			mo.observe(document.body, { childList: true, subtree: false });
		} catch (e) {}
		// Safety net: re-check every second for the first 10s (covers cases
		// where React hydration runs after our defer script).
		var ticks = 0;
		var t = setInterval(function () {
			ensureMounted();
			if (++ticks >= 10) clearInterval(t);
		}, 1000);
	}

	function loadGoogleTranslate() {
		if (window.__cdskiGTLoaded) return;
		window.__cdskiGTLoaded = true;
		window.googleTranslateElementInit = function () {
			if (!window.google || !window.google.translate) return;
			new google.translate.TranslateElement({
				pageLanguage: pageSourceLang(),
				includedLanguages: 'en,es,pt,fr,de,it,nl,pl,sv,ru,zh-CN,ja,ko,ar,hi,tr,el,he',
				autoDisplay: false,
				layout: google.translate.TranslateElement.InlineLayout.SIMPLE
			}, 'cdski-google-translate-element');
		};
		var s = document.createElement('script');
		s.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
		s.async = true;
		document.body.appendChild(s);
	}

	function autoDetectLang() {
		try {
			if (localStorage.getItem('cdski-lang-source') === 'user') return;
		} catch (e) {}
		if (document.cookie.indexOf('googtrans=') !== -1) return;

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

		// Non-intrusive: never auto-redirect between the native pages
		// (/, /es/, /en/, /pt/). Only set the GT cookie + reload for the
		// 15 non-native languages on first visit.
		if (!detected || detected === 'es' || detected === 'en' || detected === 'pt') return;

		var target = detected === 'zh' ? 'zh-CN' : detected;
		if (SUPPORTED_GT.indexOf(target.replace('-CN','')) === -1 && target !== 'zh-CN') return;
		setGoogleTranslateCookie(target);
		location.reload();
	}

	function boot() {
		injectStyle();
		injectGTContainer();
		ensureMounted();
		attachGlobalListeners();
		startMountWatcher();
		loadGoogleTranslate();
		setTimeout(autoDetectLang, 50);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
