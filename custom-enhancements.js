/* =========================================================
   CDSKI — Pro UI/UX & Responsive Enhancements
   - Progressive reveal of hero/intro elements
   - Header scroll state
   - Functional mobile menu (mirrors desktop nav, localised)
   - Scroll-to-top button
   - Smooth anchor scrolling with header offset
   ========================================================= */
(function () {
  'use strict';

  // Guard against double-inclusion (the static export renders two <body> sections).
  if (window.__cdskiEnhancementsLoaded) return;
  window.__cdskiEnhancementsLoaded = true;

  var CONFIG = {
    scrollThresholdHeader: 32,
    scrollThresholdTopBtn: 640,
    revealStagger: 90,
    headerOffset: 80
  };

  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn, { once: true });
    } else {
      fn();
    }
  }

  function revealInlineHidden() {
    try {
      var nodes = document.querySelectorAll('[style*="opacity:0"]');
      Array.prototype.forEach.call(nodes, function (el, i) {
        setTimeout(function () {
          el.style.opacity = '1';
          el.style.transform = 'translateY(0)';
        }, 80 + i * CONFIG.revealStagger);
      });
    } catch (e) { /* no-op */ }
  }

  function setupHeader() {
    var header = document.querySelector('header');
    if (!header) return;
    var onScroll = function () {
      if (window.scrollY > CONFIG.scrollThresholdHeader) {
        header.classList.add('cdski-scrolled');
      } else {
        header.classList.remove('cdski-scrolled');
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  function setupMobileMenu() {
    var header = document.querySelector('header');
    if (!header) return;
    var toggleBtn = header.querySelector('button[aria-label="Toggle menu"]');
    if (!toggleBtn) return;

    // Build panel from desktop nav so labels are already localised
    var desktopNav = header.querySelector('nav');
    var navLinks = desktopNav
      ? Array.prototype.slice.call(desktopNav.querySelectorAll('a'))
      : [];

    var menu = document.createElement('div');
    menu.className = 'cdski-mobile-menu';
    menu.setAttribute('role', 'dialog');
    menu.setAttribute('aria-modal', 'true');
    menu.setAttribute('aria-label', 'Menú principal');

    var html = '';
    navLinks.forEach(function (a) {
      var href = a.getAttribute('href') || '#';
      var label = (a.textContent || '').trim();
      if (!label) return;
      html += '<a href="' + href + '">' + label + '</a>';
    });

    // CTA — prefer the existing visible CTA in the header
    var ctaEl = header.querySelector('a.bg-orange-500[href="#contact"], a.bg-orange-500[href*="#contact"]');
    var ctaText = ctaEl && ctaEl.textContent ? ctaEl.textContent.trim() : 'Reservar';
    html += '<a href="#contact" class="cdski-mm-cta">' + ctaText + '</a>';
    menu.innerHTML = html;

    document.body.appendChild(menu);

    var setOpen = function (open) {
      menu.classList.toggle('open', open);
      document.body.classList.toggle('cdski-menu-open', open);
      toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    toggleBtn.setAttribute('aria-expanded', 'false');
    toggleBtn.addEventListener('click', function (e) {
      e.preventDefault();
      setOpen(!menu.classList.contains('open'));
    });

    menu.addEventListener('click', function (e) {
      var t = e.target;
      while (t && t !== menu) {
        if (t.tagName === 'A') { setOpen(false); break; }
        t = t.parentNode;
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && menu.classList.contains('open')) setOpen(false);
    });

    // Close on resize to desktop
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1024) setOpen(false);
    });
  }

  function setupScrollToTop() {
    var btn = document.createElement('button');
    btn.className = 'cdski-to-top';
    btn.type = 'button';
    btn.setAttribute('aria-label', 'Volver arriba');
    btn.innerHTML =
      '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
      'stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M18 15l-6-6-6 6"/></svg>';
    document.body.appendChild(btn);

    btn.addEventListener('click', function () {
      try {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } catch (e) {
        window.scrollTo(0, 0);
      }
    });

    var onScroll = function () {
      if (window.scrollY > CONFIG.scrollThresholdTopBtn) {
        btn.classList.add('visible');
      } else {
        btn.classList.remove('visible');
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  function setupSmoothAnchors() {
    document.addEventListener('click', function (e) {
      var a = e.target && e.target.closest ? e.target.closest('a[href^="#"]') : null;
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href || href === '#' || href.length < 2) return;
      var target;
      try { target = document.querySelector(href); } catch (err) { return; }
      if (!target) return;
      e.preventDefault();
      var rect = target.getBoundingClientRect();
      var top = rect.top + window.pageYOffset - CONFIG.headerOffset;
      try {
        window.scrollTo({ top: top, behavior: 'smooth' });
      } catch (err) {
        window.scrollTo(0, top);
      }
    });
  }

  /* =========================================================
     Booking-extras form
     Adds a small set of optional questions (dates, ski center,
     children's ages, level, lodging) to the calculator card and
     rewrites the WhatsApp pre-filled message so the answers
     arrive together with the quote in the first message.
     ========================================================= */
  function setupBookingExtras() {
    var lang = 'es';
    var path = location.pathname || '';
    if (path.indexOf('/en') === 0) lang = 'en';
    else if (path.indexOf('/pt') === 0) lang = 'pt';
    else lang = 'es';

    var I18N = {
      es: {
        title: 'Detalles adicionales',
        hint: 'Se enviarán junto a tu solicitud por WhatsApp para coordinar más rápido.',
        dates: 'Fechas exactas',
        datesPh: 'Ej: 15 al 18 de julio 2026',
        resort: 'Centro de ski',
        resortOpts: ['Selecciona…', 'Valle Nevado', 'El Colorado', 'La Parva', 'Farellones', 'Aún no decidido', 'Otro'],
        ages: 'Edades de los niños',
        agesPh: 'Ej: 8 y 10 años',
        level: 'Nivel de ski',
        levelOpts: ['Selecciona…', 'Primera vez', 'Básico', 'Intermedio', 'Avanzado', 'Mixto'],
        lodging: 'Alojamiento',
        lodgingOpts: ['Selecciona…', 'Santiago', 'Centro de ski', 'Aún no decidido', 'Otro']
      },
      en: {
        title: 'Additional details',
        hint: "These will be sent with your WhatsApp request so we can coordinate faster.",
        dates: 'Exact dates',
        datesPh: 'E.g. 15-18 July 2026',
        resort: 'Ski resort',
        resortOpts: ['Select…', 'Valle Nevado', 'El Colorado', 'La Parva', 'Farellones', 'Not decided yet', 'Other'],
        ages: "Children's ages",
        agesPh: 'E.g. 8 and 10 years old',
        level: 'Ski level',
        levelOpts: ['Select…', 'First time', 'Beginner', 'Intermediate', 'Advanced', 'Mixed'],
        lodging: 'Lodging',
        lodgingOpts: ['Select…', 'Santiago', 'Ski resort', 'Not decided yet', 'Other']
      },
      pt: {
        title: 'Detalhes adicionais',
        hint: 'Serão enviados junto à sua solicitação no WhatsApp para coordenarmos mais rápido.',
        dates: 'Datas exatas',
        datesPh: 'Ex: 15 a 18 de julho 2026',
        resort: 'Centro de esqui',
        resortOpts: ['Selecione…', 'Valle Nevado', 'El Colorado', 'La Parva', 'Farellones', 'Ainda não decidido', 'Outro'],
        ages: 'Idades das crianças',
        agesPh: 'Ex: 8 e 10 anos',
        level: 'Nível de esqui',
        levelOpts: ['Selecione…', 'Primeira vez', 'Básico', 'Intermediário', 'Avançado', 'Misto'],
        lodging: 'Hospedagem',
        lodgingOpts: ['Selecione…', 'Santiago', 'Centro de esqui', 'Ainda não decidido', 'Outro']
      }
    };

    // The WhatsApp message is always sent in Spanish (the recipient is in Chile).
    var MSG = {
      title: 'Hola! Quiero reservar clases con CDSKI',
      planLabel: 'Detalles de la cotización',
      extraLabel: 'Información adicional',
      dates: 'Fechas',
      resort: 'Centro de ski',
      ages: 'Edades de los niños',
      level: 'Nivel',
      lodging: 'Alojamiento',
      thanks: '¡Gracias! Quedo atento(a) para coordinar.'
    };

    var t = I18N[lang] || I18N.es;
    var saved = { dates: '', resort: '', ages: '', level: '', lodging: '' };

    function findCalcWhatsAppLink() {
      var links = document.querySelectorAll('a[href*="wa.me/56940211459"]');
      for (var i = 0; i < links.length; i++) {
        var href = links[i].getAttribute('href') || '';
        var decoded = '';
        try { decoded = decodeURIComponent(href); } catch (e) { decoded = href; }
        if (decoded.indexOf('Quiero reservar clases con CDSKI') !== -1) {
          return links[i];
        }
      }
      return null;
    }

    function buildOption(label, value, isPlaceholder) {
      var opt = document.createElement('option');
      opt.value = isPlaceholder ? '' : value;
      opt.textContent = label;
      if (isPlaceholder) {
        opt.disabled = true;
        opt.selected = true;
      }
      return opt;
    }

    function buildField(emoji, labelText, control, modifier) {
      var wrap = document.createElement('label');
      wrap.className = 'cdski-extras-field' + (modifier ? ' ' + modifier : '');
      var span = document.createElement('span');
      span.className = 'cdski-extras-label';
      span.textContent = emoji + ' ' + labelText;
      wrap.appendChild(span);
      wrap.appendChild(control);
      return wrap;
    }

    function buildForm() {
      var card = document.createElement('div');
      card.className = 'cdski-extras';

      var head = document.createElement('div');
      head.className = 'cdski-extras-head';
      var ttl = document.createElement('span');
      ttl.className = 'cdski-extras-title';
      ttl.textContent = '📝 ' + t.title;
      var hnt = document.createElement('span');
      hnt.className = 'cdski-extras-hint';
      hnt.textContent = t.hint;
      head.appendChild(ttl);
      head.appendChild(hnt);
      card.appendChild(head);

      var grid = document.createElement('div');
      grid.className = 'cdski-extras-grid';

      var dInput = document.createElement('input');
      dInput.type = 'text';
      dInput.name = 'dates';
      dInput.placeholder = t.datesPh;
      dInput.autocomplete = 'off';
      dInput.value = saved.dates;
      grid.appendChild(buildField('📅', t.dates, dInput));

      var rSel = document.createElement('select');
      rSel.name = 'resort';
      t.resortOpts.forEach(function (o, i) { rSel.appendChild(buildOption(o, o, i === 0)); });
      if (saved.resort) rSel.value = saved.resort;
      grid.appendChild(buildField('⛰️', t.resort, rSel));

      var aInput = document.createElement('input');
      aInput.type = 'text';
      aInput.name = 'ages';
      aInput.placeholder = t.agesPh;
      aInput.autocomplete = 'off';
      aInput.value = saved.ages;
      grid.appendChild(buildField('👶', t.ages, aInput));

      var lSel = document.createElement('select');
      lSel.name = 'level';
      t.levelOpts.forEach(function (o, i) { lSel.appendChild(buildOption(o, o, i === 0)); });
      if (saved.level) lSel.value = saved.level;
      grid.appendChild(buildField('⛷️', t.level, lSel));

      var loSel = document.createElement('select');
      loSel.name = 'lodging';
      t.lodgingOpts.forEach(function (o, i) { loSel.appendChild(buildOption(o, o, i === 0)); });
      if (saved.lodging) loSel.value = saved.lodging;
      grid.appendChild(buildField('🏨', t.lodging, loSel, 'cdski-extras-full'));

      card.appendChild(grid);
      return card;
    }

    function ensureFormFor(link) {
      // Anchor: the buttons container that wraps the WhatsApp link.
      var btnGroup = link.parentNode;
      if (!btnGroup) return;
      var host = btnGroup.parentNode;
      if (!host) return;

      var existing = host.querySelector(':scope > .cdski-extras');
      if (existing) return;

      var card = buildForm();
      host.insertBefore(card, btnGroup);

      card.addEventListener('input', captureSaved, true);
      card.addEventListener('change', captureSaved, true);
    }

    function captureSaved(e) {
      var el = e.target;
      if (!el || !el.name) return;
      if (Object.prototype.hasOwnProperty.call(saved, el.name)) {
        saved[el.name] = (el.value || '').trim();
      }
    }

    function buildMessage(originalText) {
      var lines = (originalText || '').split('\n');
      var bullets = [];
      for (var i = 1; i < lines.length; i++) {
        var ln = lines[i];
        if (!ln) continue;
        bullets.push(ln.replace(/^- /, '• '));
      }

      var out = [];
      out.push('*' + MSG.title + '* 🎿❄️');
      out.push('');
      out.push('📋 *' + MSG.planLabel + ':*');
      bullets.forEach(function (b) {
        if (/Total estimado:/i.test(b)) {
          // Bold the total amount line
          out.push(b.replace(/^• (.*)$/, '• *$1*'));
        } else {
          out.push(b);
        }
      });

      var extras = [];
      if (saved.dates)   extras.push('📅 ' + MSG.dates + ': ' + saved.dates);
      if (saved.resort)  extras.push('⛰️ ' + MSG.resort + ': ' + saved.resort);
      if (saved.ages)    extras.push('👶 ' + MSG.ages + ': ' + saved.ages);
      if (saved.level)   extras.push('⛷️ ' + MSG.level + ': ' + saved.level);
      if (saved.lodging) extras.push('🏨 ' + MSG.lodging + ': ' + saved.lodging);

      if (extras.length) {
        out.push('');
        out.push('📝 *' + MSG.extraLabel + ':*');
        for (var j = 0; j < extras.length; j++) out.push(extras[j]);
      }

      out.push('');
      out.push(MSG.thanks);
      return out.join('\n');
    }

    function hookLink(link) {
      if (link.dataset.cdskiExtrasHooked === '1') return;
      link.dataset.cdskiExtrasHooked = '1';
      link.addEventListener('click', function (e) {
        var href = link.getAttribute('href') || '';
        var qIdx = href.indexOf('?text=');
        if (qIdx < 0) return;
        var originalText = '';
        try { originalText = decodeURIComponent(href.slice(qIdx + 6)); } catch (err) { return; }
        if (originalText.indexOf('Quiero reservar clases con CDSKI') === -1) return;
        e.preventDefault();
        var newText = buildMessage(originalText);
        var base = href.slice(0, qIdx + 6);
        var newHref = base + encodeURIComponent(newText);
        window.open(newHref, '_blank', 'noopener,noreferrer');
      }, true);
    }

    function tick() {
      var link = findCalcWhatsAppLink();
      if (!link) return;
      hookLink(link);
      ensureFormFor(link);
    }

    // Poll a few seconds because React hydrates after first paint, and
    // re-runs cheaply afterwards in case the calculator card re-renders.
    var attempts = 0;
    var iv = setInterval(function () {
      tick();
      attempts++;
      if (attempts > 80) clearInterval(iv);
    }, 250);
    tick();
  }

  ready(function () {
    document.documentElement.classList.add('cdski-reveal-ready');
    revealInlineHidden();
    setupHeader();
    setupMobileMenu();
    setupScrollToTop();
    setupSmoothAnchors();
    setupBookingExtras();
  });
})();
