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
        if (el.tagName === 'IMG') return;
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

  // React hidrata el documento completo y descarta todo lo que colgamos de
  // <body> antes de que termine. El menú móvil y el botón de volver arriba
  // desaparecían por eso: se reponen hasta que la hidratación se asienta.
  function keepAlive(ensure) {
    var attempts = 0;
    var iv = setInterval(function () {
      ensure();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    ensure();
  }

  function setupMobileMenu() {
    var menu = null;

    function buildMenu(header) {
      // Build panel from desktop nav so labels are already localised
      var desktopNav = header.querySelector('nav');
      var navLinks = desktopNav
        ? Array.prototype.slice.call(desktopNav.querySelectorAll('a'))
        : [];

      var panel = document.createElement('div');
      panel.className = 'cdski-mobile-menu';
      panel.setAttribute('role', 'dialog');
      panel.setAttribute('aria-modal', 'true');
      panel.setAttribute('aria-label', 'Menú principal');

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
      panel.innerHTML = html;

      panel.addEventListener('click', function (e) {
        var t = e.target;
        while (t && t !== panel) {
          if (t.tagName === 'A') { setOpen(false); break; }
          t = t.parentNode;
        }
      });
      return panel;
    }

    function setOpen(open) {
      if (!menu) return;
      menu.classList.toggle('open', open);
      document.body.classList.toggle('cdski-menu-open', open);
      var btn = document.querySelector('header button[aria-label="Toggle menu"]');
      if (btn) btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    // Tras hidratar, el propio sitio maneja su menú móvil. Nuestro panel sólo
    // existe como respaldo para cuando React todavía no tomó el control (o falló);
    // si React ya lo maneja, lo retiramos para no mostrar dos menús encima.
    function reactOwnsToggle(btn) {
      for (var k in btn) {
        if (k.indexOf('__reactProps') === 0) {
          var p = btn[k];
          if (p && typeof p.onClick === 'function') return true;
        }
      }
      return false;
    }

    function ensure() {
      var header = document.querySelector('header');
      if (!header) return;
      var toggleBtn = header.querySelector('button[aria-label="Toggle menu"]');
      if (!toggleBtn) return;

      if (reactOwnsToggle(toggleBtn)) {
        if (menu && menu.parentNode) menu.parentNode.removeChild(menu);
        var stray = document.querySelector('.cdski-mobile-menu');
        if (stray && stray.parentNode) stray.parentNode.removeChild(stray);
        menu = null;
        document.body.classList.remove('cdski-menu-open');
        return;
      }

      if (!menu || !document.body.contains(menu)) {
        var existing = document.querySelector('.cdski-mobile-menu');
        menu = existing || buildMenu(header);
        document.body.appendChild(menu);
      }

      // El botón también se recrea al hidratar: reenganchamos una sola vez por nodo.
      if (toggleBtn.getAttribute('data-cdski-menu') !== '1') {
        toggleBtn.setAttribute('data-cdski-menu', '1');
        toggleBtn.setAttribute('aria-expanded', 'false');
        toggleBtn.addEventListener('click', function (e) {
          e.preventDefault();
          setOpen(!(menu && menu.classList.contains('open')));
        });
      }
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && menu && menu.classList.contains('open')) setOpen(false);
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1024) setOpen(false);
    });

    keepAlive(ensure);
  }

  function setupScrollToTop() {
    var btn = null;

    function onScroll() {
      if (!btn) return;
      if (window.scrollY > CONFIG.scrollThresholdTopBtn) {
        btn.classList.add('visible');
      } else {
        btn.classList.remove('visible');
      }
    }

    function ensure() {
      if (btn && document.body.contains(btn)) return;
      btn = document.querySelector('.cdski-to-top');
      if (!btn) {
        btn = document.createElement('button');
        btn.className = 'cdski-to-top';
        btn.type = 'button';
        btn.setAttribute('aria-label', 'Volver arriba');
        btn.innerHTML =
          '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
          'stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
          '<path d="M18 15l-6-6-6 6"/></svg>';
        btn.addEventListener('click', function () {
          try {
            window.scrollTo({ top: 0, behavior: 'smooth' });
          } catch (e) {
            window.scrollTo(0, 0);
          }
        });
      }
      document.body.appendChild(btn);
      onScroll();
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    keepAlive(ensure);
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
        hint: 'Con las fechas y el centro nos basta para revisar disponibilidad. El resto es opcional y nos ayuda a preparar mejor tu clase.',
        required: 'Obligatorio',
        errorMsg: 'Completa las fechas y el centro de ski antes de enviar por WhatsApp.',
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
        hint: 'Dates and resort are all we need to check availability. The rest is optional and helps us prepare your lesson.',
        required: 'Required',
        errorMsg: 'Please fill in the dates and the ski resort before sending via WhatsApp.',
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
        hint: 'Com as datas e o centro já verificamos a disponibilidade. O resto é opcional e nos ajuda a preparar sua aula.',
        required: 'Obrigatório',
        errorMsg: 'Preencha as datas e o centro de esqui antes de enviar por WhatsApp.',
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
      companions: 'Acompañantes (sólo traslado)',
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

    function decodeHrefText(link) {
      var href = (link && link.getAttribute('href')) || '';
      var qIdx = href.indexOf('?text=');
      if (qIdx < 0) return '';
      try { return decodeURIComponent(href.slice(qIdx + 6)); } catch (e) { return ''; }
    }

    function childrenCountFromText(text) {
      var m = /(\d+)\s*ni[ñn]o/.exec(text || '');
      return m ? parseInt(m[1], 10) : 0;
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

    function buildField(emoji, labelText, control, modifier, fieldName) {
      var wrap = document.createElement('label');
      wrap.className = 'cdski-extras-field' + (modifier ? ' ' + modifier : '');
      if (fieldName) wrap.setAttribute('data-field', fieldName);
      var span = document.createElement('span');
      span.className = 'cdski-extras-label';
      span.textContent = emoji + ' ' + labelText;
      if (control && control.required) {
        var star = document.createElement('span');
        star.className = 'cdski-extras-required';
        star.textContent = ' *';
        star.setAttribute('aria-label', t.required);
        span.appendChild(star);
      }
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
      dInput.required = true;
      dInput.value = saved.dates;
      grid.appendChild(buildField('📅', t.dates, dInput, '', 'dates'));

      var rSel = document.createElement('select');
      rSel.name = 'resort';
      rSel.required = true;
      t.resortOpts.forEach(function (o, i) { rSel.appendChild(buildOption(o, o, i === 0)); });
      if (saved.resort) rSel.value = saved.resort;
      grid.appendChild(buildField('⛰️', t.resort, rSel, '', 'resort'));

      var aInput = document.createElement('input');
      aInput.type = 'text';
      aInput.name = 'ages';
      aInput.placeholder = t.agesPh;
      aInput.autocomplete = 'off';
      aInput.required = true;
      aInput.value = saved.ages;
      grid.appendChild(buildField('👶', t.ages, aInput, '', 'ages'));

      var lSel = document.createElement('select');
      lSel.name = 'level';
      lSel.required = false;
      t.levelOpts.forEach(function (o, i) { lSel.appendChild(buildOption(o, o, i === 0)); });
      if (saved.level) lSel.value = saved.level;
      grid.appendChild(buildField('⛷️', t.level, lSel, '', 'level'));

      var loSel = document.createElement('select');
      loSel.name = 'lodging';
      loSel.required = false;
      t.lodgingOpts.forEach(function (o, i) { loSel.appendChild(buildOption(o, o, i === 0)); });
      if (saved.lodging) loSel.value = saved.lodging;
      grid.appendChild(buildField('🏨', t.lodging, loSel, 'cdski-extras-full', 'lodging'));

      card.appendChild(grid);

      var err = document.createElement('div');
      err.className = 'cdski-extras-error';
      err.setAttribute('role', 'alert');
      err.setAttribute('aria-live', 'polite');
      err.textContent = t.errorMsg;
      card.appendChild(err);

      return card;
    }

    function applyChildrenVisibility(card, link) {
      if (!card) return;
      var ageWrap = card.querySelector('[data-field="ages"]');
      if (!ageWrap) return;
      var hasKids = childrenCountFromText(decodeHrefText(link)) > 0;
      ageWrap.style.display = hasKids ? '' : 'none';
      var input = ageWrap.querySelector('input');
      if (input) input.required = hasKids;
      if (!hasKids) ageWrap.classList.remove('cdski-extras-invalid');
    }

    function ensureFormFor(link) {
      // Anchor: the buttons container that wraps the WhatsApp link.
      var btnGroup = link.parentNode;
      if (!btnGroup) return;
      var host = btnGroup.parentNode;
      if (!host) return;

      var existing = host.querySelector(':scope > .cdski-extras');
      if (existing) {
        applyChildrenVisibility(existing, link);
        return;
      }

      var card = buildForm();
      host.insertBefore(card, btnGroup);

      card.addEventListener('input', onFieldChange, true);
      card.addEventListener('change', onFieldChange, true);

      applyChildrenVisibility(card, link);
    }

    function onFieldChange(e) {
      var el = e.target;
      if (!el || !el.name) return;
      if (Object.prototype.hasOwnProperty.call(saved, el.name)) {
        saved[el.name] = (el.value || '').trim();
      }
      var wrap = el.closest('[data-field]');
      if (wrap && saved[el.name]) {
        wrap.classList.remove('cdski-extras-invalid');
        var card = wrap.closest('.cdski-extras');
        if (card && !card.querySelector('.cdski-extras-invalid')) {
          card.classList.remove('cdski-extras-show-error');
        }
      }
    }

    function validate(card, link) {
      if (!card) return false;
      var fields = ['dates', 'resort', 'ages', 'level', 'lodging'];
      var hasKids = childrenCountFromText(decodeHrefText(link)) > 0;
      var allOk = true;
      fields.forEach(function (name) {
        if (name === 'ages' && !hasKids) return;
        var wrap = card.querySelector('[data-field="' + name + '"]');
        if (!wrap) return;
        var input = wrap.querySelector('input,select');
        // Sólo se exigen los campos marcados como obligatorios: nivel y
        // alojamiento son opcionales y no deben bloquear el envío.
        if (input && !input.required) { wrap.classList.remove('cdski-extras-invalid'); return; }
        var val = input ? (input.value || '').trim() : '';
        if (!val) {
          wrap.classList.add('cdski-extras-invalid');
          allOk = false;
        } else {
          wrap.classList.remove('cdski-extras-invalid');
        }
      });
      card.classList.toggle('cdski-extras-show-error', !allOk);
      return allOk;
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
      // Los acompañantes sólo existen en nuestro contador: sin esta línea, el
      // total llegaría con un traslado que no cuadra con las personas listadas.
      var acomp = 0;
      try { acomp = (window.__cdskiQuote && window.__cdskiQuote.state && window.__cdskiQuote.state.companions) || 0; } catch (e) { acomp = 0; }

      bullets.forEach(function (b) {
        if (/Total estimado:/i.test(b)) {
          if (acomp > 0) out.push('• ' + MSG.companions + ': ' + acomp);
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

        var card = document.querySelector('.cdski-extras');
        if (!validate(card, link)) {
          e.preventDefault();
          e.stopPropagation();
          if (card) {
            var firstInvalid = card.querySelector('.cdski-extras-invalid input, .cdski-extras-invalid select');
            if (firstInvalid) {
              try { firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (_) {}
              try { firstInvalid.focus({ preventScroll: true }); } catch (_) { firstInvalid.focus(); }
            }
          }
          return;
        }

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

  function setupMissionSection() {
    var path = location.pathname;
    var lang = path.indexOf('/en') === 0 ? 'en' : path.indexOf('/pt') === 0 ? 'pt' : 'es';
    var T = {
      es: {
        kicker: 'Nuestro foco',
        body: 'Nuestro principal foco y objetivo es transformar la primera experiencia en la nieve en un recuerdo inolvidable, combinando aprendizaje, seguridad y diversión. Más que enseñar a esquiar, buscamos que cada alumno gane confianza, disfrute la montaña y descubra una nueva pasión.',
        tag: 'Aprende con seguridad, avanza con confianza y vive la montaña al máximo.'
      },
      pt: {
        kicker: 'Nosso foco',
        body: 'Nosso principal foco é transformar a primeira experiência na neve em uma lembrança inesquecível, combinando aprendizado, segurança e diversão. Mais do que ensinar a esquiar, queremos que cada aluno ganhe confiança, aproveite a montanha e descubra uma nova paixão.',
        tag: 'Aprenda com segurança, avance com confiança e viva a montanha ao máximo.'
      },
      en: {
        kicker: 'Our focus',
        body: 'Our main focus is to turn your first time on the snow into an unforgettable memory, blending learning, safety and fun. More than teaching you to ski, we want every student to gain confidence, enjoy the mountain and discover a new passion.',
        tag: 'Learn safely, progress with confidence and live the mountain to the fullest.'
      }
    }[lang];

    function insert() {
      if (document.querySelector('.cdski-mission')) return true;
      var whyUs = document.getElementById('why-us');
      if (!whyUs || !whyUs.parentNode) return false;

      var sec = document.createElement('section');
      sec.className = 'cdski-mission';
      sec.setAttribute('aria-label', T.kicker);
      sec.innerHTML =
        '<div class="cdski-mission-card">'
        + '<span class="cdski-mission-kicker">'
        +   '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>'
        +   T.kicker
        + '</span>'
        + '<p class="cdski-mission-body">' + T.body + '</p>'
        + '<p class="cdski-mission-tag">' + T.tag + '</p>'
        + '</div>';
      whyUs.parentNode.insertBefore(sec, whyUs);
      return true;
    }

    // React hydration re-renders the tree and discards injected nodes, so we
    // re-insert for a few seconds until it survives.
    var attempts = 0;
    var iv = setInterval(function () {
      insert();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    insert();
  }

  function setupLazyImages() {
    var imgs = document.querySelectorAll('img[loading="lazy"]');
    Array.prototype.forEach.call(imgs, function(img) {
      if (img.complete && img.naturalWidth > 0) {
        img.classList.add('loaded');
      } else {
        img.addEventListener('load', function() {
          img.classList.add('loaded');
        }, { once: true });
        img.addEventListener('error', function() {
          img.classList.add('loaded');
        }, { once: true });
      }
    });
    setTimeout(function() {
      var remaining = document.querySelectorAll('img[loading="lazy"]:not(.loaded)');
      Array.prototype.forEach.call(remaining, function(img) {
        img.classList.add('loaded');
      });
    }, 3000);
  }

  function setupFooter() {
    var footer = document.querySelector('footer');
    if (!footer) return;
    var cols = footer.querySelectorAll('.grid > div');
    Array.prototype.forEach.call(cols, function(col, i) {
      col.style.opacity = '0';
      col.style.transform = 'translateY(20px)';
      col.style.transition = 'opacity 0.6s cubic-bezier(0.16,1,0.3,1) ' + (i * 0.12) + 's, transform 0.6s cubic-bezier(0.16,1,0.3,1) ' + (i * 0.12) + 's';
    });
    if ('IntersectionObserver' in window) {
      var obs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            Array.prototype.forEach.call(cols, function(col) {
              col.style.opacity = '1';
              col.style.transform = 'translateY(0)';
            });
            obs.disconnect();
          }
        });
      }, { threshold: 0.15 });
      obs.observe(footer);
    } else {
      Array.prototype.forEach.call(cols, function(col) {
        col.style.opacity = '1';
        col.style.transform = 'translateY(0)';
      });
    }
  }

  function setupWhatsAppFab() {
    var waLink = document.querySelector('a[href*="wa.me/56940211459"]');
    if (!waLink) return;
    var fab = document.createElement('a');
    fab.className = 'cdski-wa-fab';
    fab.href = 'https://wa.me/56940211459';
    fab.target = '_blank';
    fab.rel = 'noopener noreferrer';
    fab.setAttribute('aria-label', 'WhatsApp');
    fab.innerHTML = '<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';
    document.body.appendChild(fab);

    var onScroll = function() {
      if (window.scrollY > 400) {
        fab.classList.add('visible');
      } else {
        fab.classList.remove('visible');
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  function cdskiFlag(code, label, w) {
    w = w || 26;
    var h = Math.round(w * 2 / 3);
    return '<img src="/images/flags/' + code + '.svg" alt="' + label + '" width="' + w + '" height="' + h + '" class="cdski-flag-img" loading="lazy"/>';
  }

  function setupWelcomeBanner() {
    var path = location.pathname;
    var lang = path.indexOf('/en') === 0 ? 'en' : path.indexOf('/pt') === 0 ? 'pt' : 'es';
    var T = {
      es: {
        kicker: 'Comunidad latinoamericana',
        title: '¡Bienvenidos todos nuestros <em>Vecinos</em>!',
        sub: 'Recibimos con los brazos abiertos a nuestros amigos de Brasil, Perú, Argentina y todo el continente. Clases en tu idioma, pagos sin fronteras y el mejor trato de los Andes.',
        tag: 'La nieve nos une ❄'
      },
      pt: {
        kicker: 'Comunidade latino-americana',
        title: 'Bem-vindos, todos os nossos <em>Vizinhos</em>!',
        sub: 'Recebemos de braços abertos nossos amigos do Brasil, Peru, Argentina e de todo o continente. Aulas no seu idioma, pagamentos sem fronteiras e o melhor atendimento dos Andes.',
        tag: 'A neve nos une ❄'
      },
      en: {
        kicker: 'Latin American community',
        title: 'Welcome, all our <em>Neighbors</em>!',
        sub: 'We welcome with open arms our friends from Brazil, Peru, Argentina and the whole continent. Lessons in your language, borderless payments and the warmest service in the Andes.',
        tag: 'Snow unites us ❄'
      }
    }[lang];

    var COUNTRIES = [
      ['cl', 'Chile'], ['br', 'Brasil'], ['pe', 'Perú'],
      ['ar', 'Argentina'], ['uy', 'Uruguay'], ['us', 'USA']
    ];

    function chipsHtml() {
      var html = '';
      COUNTRIES.forEach(function (c, i) {
        html += '<span class="cdski-wb-chip" style="animation-delay:' + (0.15 + i * 0.08) + 's">'
          + cdskiFlag(c[0], c[1], 28)
          + '<span>' + c[1] + '</span></span>';
      });
      return html;
    }

    function insertBanner() {
      if (document.querySelector('.cdski-welcome-banner')) return true;
      var services = document.getElementById('services');
      if (!services || !services.parentNode) return false;

      var banner = document.createElement('div');
      banner.className = 'cdski-welcome-banner';
      banner.innerHTML =
        '<div class="cdski-wb-card">'
        + '<div class="cdski-wb-shine" aria-hidden="true"></div>'
        + '<div class="cdski-wb-text">'
        +   '<span class="cdski-wb-kicker"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>' + T.kicker + '</span>'
        +   '<h3>' + T.title + '</h3>'
        +   '<p>' + T.sub + '</p>'
        + '</div>'
        + '<div class="cdski-wb-side">'
        +   '<div class="cdski-wb-chips">' + chipsHtml() + '</div>'
        +   '<span class="cdski-wb-tag">' + T.tag + '</span>'
        + '</div>'
        + '</div>';
      services.parentNode.insertBefore(banner, services);
      return true;
    }

    function insertFlagStrip() {
      if (document.querySelector('.cdski-flags-strip')) return true;
      var whyUs = document.getElementById('why-us');
      if (!whyUs) return false;
      var heading = whyUs.querySelector('h2');
      if (!heading || !heading.parentNode) return false;

      var flagStrip = document.createElement('div');
      flagStrip.className = 'cdski-flags-strip';
      var imgs = '';
      COUNTRIES.forEach(function (c) { imgs += cdskiFlag(c[0], c[1], 22); });
      flagStrip.innerHTML = '<span class="cdski-fs-flags">' + imgs + '</span>'
        + '<span class="cdski-fs-label">'
        + (lang === 'pt' ? 'Instrutores para toda a América Latina'
           : lang === 'en' ? 'Instructors for all of Latin America'
           : 'Instructores para toda Latinoamérica')
        + '</span>';
      heading.parentNode.insertBefore(flagStrip, heading.nextSibling);
      return true;
    }

    // React hydration re-renders the tree and discards injected nodes, so we
    // re-insert for a few seconds until both survive.
    var attempts = 0;
    var iv = setInterval(function () {
      insertBanner();
      insertFlagStrip();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    insertBanner();
    insertFlagStrip();
  }

  /* =========================================================
     Experiencia guiada de aprendizaje
     - Píldora en el hero
     - Realce (sin cambiar el texto) del claim ya existente
     - Sección propia con el método paso a paso
     ========================================================= */

  function currentLang() {
    var path = location.pathname;
    return path.indexOf('/en') === 0 ? 'en' : path.indexOf('/pt') === 0 ? 'pt' : 'es';
  }

  var GUIDED_COPY = {
    es: {
      pill: 'Experiencia guiada de aprendizaje · Instructor experto',
      claim: 'Experiencias guiadas con instructores expertos',
      softClaims: ['instructores expertos', 'instructor experto'],
      kicker: 'Experiencia guiada de aprendizaje',
      title: 'Más que una clase: una <em>experiencia guiada</em> con tu instructor experto',
      lead: 'La experiencia comienza completamente desde cero y se adapta a tu ritmo, buscando que aprendas de manera progresiva, segura y, sobre todo, que disfrutes tu primera experiencia en la nieve 🏂❄️',
      leadStrong: ['completamente desde cero', 'se adapta a tu ritmo', 'disfrutes tu primera experiencia en la nieve'],
      steps: [
        ['Partimos desde cero', 'Sin experiencia previa ni equipo propio. Tu instructor te acompaña desde el primer paso sobre la nieve.'],
        ['Instructor experto contigo', 'Un profesional te guía durante toda la jornada: corrige en el momento y cuida cada detalle.'],
        ['Progresión a tu ritmo', 'Equilibrio, deslizamiento, giros y control. Cada etapa avanza cuando tú estás listo.'],
        ['Tu primera bajada, disfrutando', 'Terminas el día bajando con confianza, seguridad y ganas de volver a la montaña.']
      ],
      badges: [
        'Instructor experto que te guía',
        'Método progresivo paso a paso',
        'Seguridad en cada etapa',
        'Español · English · Português'
      ],
      cta: 'Reserva tu experiencia guiada'
    },
    en: {
      pill: 'Guided learning experience · Expert instructor',
      claim: 'Guided experiences with expert instructors',
      softClaims: ['expert instructors', 'expert instructor'],
      kicker: 'Guided learning experience',
      title: 'More than a lesson: a <em>guided experience</em> with your expert instructor',
      lead: 'The experience starts completely from scratch and adapts to your own pace, so you learn progressively, safely and — above all — enjoy your first time on the snow 🏂❄️',
      leadStrong: ['completely from scratch', 'adapts to your own pace', 'enjoy your first time on the snow'],
      steps: [
        ['We start from zero', 'No previous experience or gear of your own. Your instructor is with you from your very first step on the snow.'],
        ['An expert instructor with you', 'A professional guides you through the whole day: correcting on the spot and taking care of every detail.'],
        ['Progress at your own pace', 'Balance, gliding, turns and control. Each stage moves forward when you are ready.'],
        ['Your first run, enjoying it', 'You finish the day skiing down with confidence, safety and eager to come back to the mountain.']
      ],
      badges: [
        'An expert instructor guiding you',
        'Step-by-step progressive method',
        'Safety at every stage',
        'Español · English · Português'
      ],
      cta: 'Book your guided experience'
    },
    pt: {
      pill: 'Experiência guiada de aprendizado · Instrutor experto',
      claim: 'Experiências guiadas com instrutores expertos',
      softClaims: ['instrutores expertos', 'instrutor experto'],
      kicker: 'Experiência guiada de aprendizado',
      title: 'Mais que uma aula: uma <em>experiência guiada</em> com seu instrutor experto',
      lead: 'A experiência começa completamente do zero e se adapta ao seu ritmo, para que você aprenda de forma progressiva, segura e, acima de tudo, aproveite sua primeira experiência na neve 🏂❄️',
      leadStrong: ['completamente do zero', 'se adapta ao seu ritmo', 'aproveite sua primeira experiência na neve'],
      steps: [
        ['Começamos do zero', 'Sem experiência prévia nem equipamento próprio. Seu instrutor acompanha você desde o primeiro passo na neve.'],
        ['Instrutor experto com você', 'Um profissional guia toda a jornada: corrige na hora e cuida de cada detalhe.'],
        ['Progressão no seu ritmo', 'Equilíbrio, deslize, curvas e controle. Cada etapa avança quando você estiver pronto.'],
        ['Sua primeira descida, aproveitando', 'Você termina o dia descendo com confiança, segurança e vontade de voltar à montanha.']
      ],
      badges: [
        'Instrutor experto que guia você',
        'Método progressivo passo a passo',
        'Segurança em cada etapa',
        'Español · English · Português'
      ],
      cta: 'Reserve sua experiência guiada'
    }
  };

  var GUIDED_ICONS = [
    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>',
    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/><path d="M18 4l1.2 2.6L22 7.8l-2 1.9.5 2.8-2.5-1.4-2.5 1.4.5-2.8-2-1.9 2.8-.2z"/></svg>',
    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 17l5-5 4 3 8-8"/><path d="M15 7h5v5"/></svg>',
    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20l6-14 5 8 2-3 5 9z"/><circle cx="8" cy="4" r="1.6"/></svg>'
  ];

  var CHECK_ICON = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>';

  // Envuelve una frase ya presente en el DOM sin alterar el texto.
  function highlightPhrase(el, phrase, className) {
    if (!el || !phrase) return false;
    if (el.querySelector('.' + className)) return true;
    var nodes = Array.prototype.slice.call(el.childNodes);
    for (var i = 0; i < nodes.length; i++) {
      var node = nodes[i];
      if (node.nodeType !== 3) continue;
      var idx = node.nodeValue.toLowerCase().indexOf(phrase.toLowerCase());
      if (idx === -1) continue;
      var exact = node.nodeValue.substr(idx, phrase.length);
      var after = node.splitText(idx);
      after.nodeValue = after.nodeValue.substr(phrase.length);
      var mark = document.createElement('span');
      mark.className = className;
      mark.textContent = exact;
      after.parentNode.insertBefore(mark, after);
      return true;
    }
    return false;
  }

  function emphasizeLead(text, phrases) {
    var out = text;
    phrases.forEach(function (p) {
      var idx = out.indexOf(p);
      if (idx === -1) return;
      out = out.slice(0, idx) + '<strong>' + p + '</strong>' + out.slice(idx + p.length);
    });
    return out;
  }

  function setupGuidedExperience() {
    var T = GUIDED_COPY[currentLang()];
    var heroLead = null;

    function findHeroLead() {
      if (heroLead && document.contains(heroLead)) return heroLead;
      var ps = document.querySelectorAll('main section p');
      for (var i = 0; i < ps.length; i++) {
        if ((ps[i].textContent || '').indexOf(T.claim) === 0) { heroLead = ps[i]; return heroLead; }
      }
      return null;
    }

    function insertHeroPill() {
      var lead = findHeroLead();
      if (!lead || !lead.parentNode) return false;
      if (lead.parentNode.querySelector('.cdski-hero-pill')) return true;
      var wrap = document.createElement('div');
      wrap.className = 'cdski-hero-pill-wrap';
      wrap.innerHTML = '<span class="cdski-hero-pill">'
        + '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20l6-14 5 8 2-3 5 9z"/><circle cx="8" cy="4" r="1.6"/></svg>'
        + T.pill + '</span>';
      lead.parentNode.insertBefore(wrap, lead);
      return true;
    }

    function markClaims() {
      var lead = findHeroLead();
      if (lead) highlightPhrase(lead, T.claim, 'cdski-mark');
      var svc = document.getElementById('services');
      if (svc) {
        Array.prototype.forEach.call(svc.querySelectorAll('p'), function (p) {
          for (var i = 0; i < T.softClaims.length; i++) {
            if (highlightPhrase(p, T.softClaims[i], 'cdski-mark-soft')) break;
          }
        });
      }
    }

    function stepsHtml() {
      var html = '';
      T.steps.forEach(function (s, i) {
        html += '<article class="cdski-guided-step" style="transition-delay:' + (0.08 * i + 0.05).toFixed(2) + 's">'
          + '<div class="cdski-guided-step-icon">' + GUIDED_ICONS[i]
          + '<span class="cdski-guided-step-num">' + (i + 1) + '</span></div>'
          + '<h3>' + s[0] + '</h3>'
          + '<p>' + s[1] + '</p>'
          + '</article>';
      });
      return html;
    }

    function badgesHtml() {
      var html = '';
      T.badges.forEach(function (b, i) {
        html += '<span class="cdski-guided-badge" style="transition-delay:' + (0.4 + 0.07 * i).toFixed(2) + 's">'
          + CHECK_ICON + b + '</span>';
      });
      return html;
    }

    function snowHtml() {
      var html = '';
      for (var i = 0; i < 14; i++) {
        var size = 8 + (i % 4) * 4;
        html += '<i aria-hidden="true" style="left:' + ((i * 7.3) % 98).toFixed(1) + '%;'
          + 'font-size:' + size + 'px;'
          + 'animation-duration:' + (11 + (i % 5) * 3) + 's;'
          + 'animation-delay:' + (i * 0.9).toFixed(1) + 's">❄</i>';
      }
      return html;
    }

    function insertSection() {
      if (document.querySelector('.cdski-guided')) return true;
      var services = document.getElementById('services');
      if (!services || !services.parentNode) return false;

      var sec = document.createElement('section');
      sec.className = 'cdski-guided';
      sec.setAttribute('aria-label', T.kicker);
      sec.innerHTML =
        '<div class="cdski-guided-snow" aria-hidden="true">' + snowHtml() + '</div>'
        + '<div class="cdski-guided-inner">'
        +   '<div class="cdski-guided-head">'
        +     '<span class="cdski-guided-kicker"><span class="cdski-guided-dot"></span>' + T.kicker + '</span>'
        +     '<h2 class="cdski-guided-title">' + T.title + '</h2>'
        +     '<p class="cdski-guided-lead">' + emphasizeLead(T.lead, T.leadStrong) + '</p>'
        +   '</div>'
        +   '<div class="cdski-guided-steps">' + stepsHtml() + '</div>'
        +   '<div class="cdski-guided-badges">' + badgesHtml() + '</div>'
        +   '<div class="cdski-guided-cta"><a href="#contact">' + T.cta
        +     '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>'
        +   '</a></div>'
        + '</div>';
      services.parentNode.insertBefore(sec, services.nextSibling);

      if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
          entries.forEach(function (e) {
            if (e.isIntersecting) {
              e.target.classList.add('cdski-inview');
              io.unobserve(e.target);
            }
          });
        }, { threshold: 0.12 });
        io.observe(sec);
      } else {
        sec.classList.add('cdski-inview');
      }
      return true;
    }

    // La hidratación de React descarta nodos inyectados: reintentamos unos segundos.
    var attempts = 0;
    var iv = setInterval(function () {
      insertSection();
      insertHeroPill();
      markClaims();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    insertSection();
    insertHeroPill();
    markClaims();
  }

  /* =========================================================
     "Vida": barra de progreso, subrayados animados y parallax
     ========================================================= */
  var HEAD_SELECTOR = '#services h2, #pricing h2, #how-to-book h2, #gallery h2, #testimonials h2, #faq h2, #blog h2, #contact h2, #why-us h2';

  function setupLiveMotion() {
    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Barra de progreso de lectura.
    // La hidratación de React descarta nodos sueltos, así que la reponemos.
    var bar = null;
    var ticking = false;
    function updateProgress() {
      ticking = false;
      if (!bar) return;
      var h = document.documentElement.scrollHeight - window.innerHeight;
      var pct = h > 0 ? Math.min(100, (window.scrollY / h) * 100) : 0;
      bar.style.width = pct + '%';
    }
    function ensureProgressBar() {
      if (bar && document.body.contains(bar)) return true;
      bar = document.querySelector('.cdski-progress');
      if (!bar) {
        bar = document.createElement('div');
        bar.className = 'cdski-progress';
      }
      document.body.appendChild(bar);
      updateProgress();
      return true;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; window.requestAnimationFrame(updateProgress); }
    }, { passive: true });

    // Subrayado animado de los títulos de sección.
    var headObserver = null;
    function observeHeadings() {
      var heads = document.querySelectorAll(HEAD_SELECTOR);
      if (!heads.length) return false;
      if (!('IntersectionObserver' in window)) {
        Array.prototype.forEach.call(heads, function (h) { h.classList.add('cdski-h-inview'); });
        return true;
      }
      if (headObserver) headObserver.disconnect();
      headObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) e.target.classList.add('cdski-h-inview');
        });
      }, { threshold: 0.35 });
      Array.prototype.forEach.call(heads, function (h) { headObserver.observe(h); });
      return true;
    }

    // Hero: ken burns + parallax suave del fondo.
    var heroBg = null;
    function findHeroBg() {
      var hero = document.querySelector('main section');
      heroBg = hero ? hero.querySelector('.bg-cover.bg-center') : null;
      if (heroBg && !reduced) heroBg.classList.add('cdski-kenburns');
      return !!heroBg;
    }

    if (!reduced) {
      var pTicking = false;
      var parallax = function () {
        var y = window.scrollY;
        if (heroBg && y < window.innerHeight * 1.2) {
          heroBg.style.backgroundPosition = 'center calc(50% + ' + (y * 0.12).toFixed(1) + 'px)';
        }
        pTicking = false;
      };
      window.addEventListener('scroll', function () {
        if (!pTicking) { pTicking = true; window.requestAnimationFrame(parallax); }
      }, { passive: true });
    }

    // La hidratación de React reescribe clases y nodos: reaplicamos unos segundos.
    var attempts = 0;
    var iv = setInterval(function () {
      ensureProgressBar();
      observeHeadings();
      findHeroBg();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    ensureProgressBar();
    observeHeadings();
    findHeroBg();
  }

  /* =========================================================
     Trabaja con nosotros — postulación de guías
     Guía de Ski · Guía de Freeride · Guía Backcountry
     ========================================================= */

  var CAREERS_COPY = {
    es: {
      kicker: 'Trabaja con nosotros',
      title: 'Envíanos tu CV',
      lead: 'Sumamos guías a nuestro equipo de guiados de experiencias en la nieve en Valle Nevado, El Colorado y La Parva. Si vives la montaña como nosotros, queremos conocerte.',
      roles: ['Guía de Ski', 'Guía de Freeride', 'Guía Backcountry'],
      reqTitle: 'Requisito excluyente',
      req: 'Para postular es <strong>100% necesario adjuntar tus títulos y certificaciones</strong>. Las postulaciones sin documentación no se revisan.',
      note: 'Sumas puntos si tienes formación en seguridad en montaña, primeros auxilios y manejo de avalanchas.',
      ctaMail: 'Enviar mi CV',
      ctaWa: 'Postular por WhatsApp',
      subject: 'Postulación de guía — CDSKI',
      body: 'Hola CDSKI, quiero postular como guía.\n\n- Nombre:\n- Ciudad / país:\n- Postulo como (Guía de Ski / Guía de Freeride / Guía Backcountry):\n- Temporadas de experiencia:\n- Idiomas:\n\nAdjunto mi CV junto a mis títulos y certificaciones.',
      waText: 'Hola CDSKI, quiero postular como guía (Guía de Ski / Freeride / Backcountry). Adjunto mi CV con mis títulos y certificaciones.'
    },
    en: {
      kicker: 'Work with us',
      title: 'Send us your CV',
      lead: 'We are growing the team behind our guided snow experiences in Valle Nevado, El Colorado and La Parva. If you live the mountain the way we do, we want to meet you.',
      roles: ['Ski Guide', 'Freeride Guide', 'Backcountry Guide'],
      reqTitle: 'Mandatory requirement',
      req: 'To apply it is <strong>100% mandatory to attach your qualifications and certifications</strong>. Applications without documentation are not reviewed.',
      note: 'Mountain safety, first aid and avalanche training are a strong plus.',
      ctaMail: 'Send my CV',
      ctaWa: 'Apply via WhatsApp',
      subject: 'Guide application — CDSKI',
      body: 'Hi CDSKI, I would like to apply as a guide.\n\n- Name:\n- City / country:\n- Applying as (Ski Guide / Freeride Guide / Backcountry Guide):\n- Seasons of experience:\n- Languages:\n\nI attach my CV together with my qualifications and certifications.',
      waText: 'Hi CDSKI, I would like to apply as a guide (Ski / Freeride / Backcountry). I am attaching my CV with my qualifications and certifications.'
    },
    pt: {
      kicker: 'Trabalhe conosco',
      title: 'Envie seu currículo',
      lead: 'Estamos ampliando a equipe dos nossos guiados de experiências na neve em Valle Nevado, El Colorado e La Parva. Se você vive a montanha como nós, queremos te conhecer.',
      roles: ['Guia de Ski', 'Guia de Freeride', 'Guia Backcountry'],
      reqTitle: 'Requisito obrigatório',
      req: 'Para se candidatar é <strong>100% necessário anexar seus títulos e certificações</strong>. Candidaturas sem documentação não são analisadas.',
      note: 'Formação em segurança na montanha, primeiros socorros e avalanches conta pontos.',
      ctaMail: 'Enviar meu currículo',
      ctaWa: 'Candidatar-se pelo WhatsApp',
      subject: 'Candidatura de guia — CDSKI',
      body: 'Olá CDSKI, quero me candidatar como guia.\n\n- Nome:\n- Cidade / país:\n- Candidato-me como (Guia de Ski / Guia de Freeride / Guia Backcountry):\n- Temporadas de experiência:\n- Idiomas:\n\nAnexo meu currículo junto com meus títulos e certificações.',
      waText: 'Olá CDSKI, quero me candidatar como guia (Ski / Freeride / Backcountry). Anexo meu currículo com meus títulos e certificações.'
    }
  };

  var ROLE_ICONS = [
    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20l6-14 5 8 2-3 5 9z"/><circle cx="8" cy="4" r="1.6"/></svg>',
    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L7 13h5l-1 9 7-12h-5z"/></svg>',
    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 20h20"/><path d="M4 20l5-9 4 5 3-5 4 9"/><path d="M12 3v4M10 5h4"/></svg>'
  ];

  function setupCareersSection() {
    var T = CAREERS_COPY[currentLang()];
    var mailHref = 'mailto:info@clasesdeski.cl'
      + '?subject=' + encodeURIComponent(T.subject)
      + '&body=' + encodeURIComponent(T.body);
    var waHref = 'https://wa.me/56940211459?text=' + encodeURIComponent(T.waText);

    function rolesHtml() {
      var html = '';
      T.roles.forEach(function (r, i) {
        html += '<span class="cdski-careers-role">' + ROLE_ICONS[i] + r + '</span>';
      });
      return html;
    }

    function insert() {
      if (document.querySelector('.cdski-careers')) return true;
      var contact = document.getElementById('contact');
      if (!contact || !contact.parentNode) return false;

      var sec = document.createElement('section');
      sec.className = 'cdski-careers';
      sec.id = 'cdski-cv';
      sec.setAttribute('aria-label', T.kicker);
      sec.innerHTML =
        '<div class="cdski-careers-card">'
        + '<div class="cdski-careers-main">'
        +   '<span class="cdski-careers-kicker">'
        +     '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>'
        +     T.kicker
        +   '</span>'
        +   '<h2>' + T.title + '</h2>'
        +   '<p class="cdski-careers-lead">' + T.lead + '</p>'
        +   '<div class="cdski-careers-roles">' + rolesHtml() + '</div>'
        +   '<p class="cdski-careers-note">' + T.note + '</p>'
        + '</div>'
        + '<div class="cdski-careers-side">'
        +   '<div class="cdski-careers-req">'
        +     '<span class="cdski-careers-req-title">'
        +       '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg>'
        +       T.reqTitle
        +     '</span>'
        +     '<p>' + T.req + '</p>'
        +   '</div>'
        +   '<div class="cdski-careers-actions">'
        +     '<a class="cdski-careers-cta" href="' + mailHref + '">'
        +       '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>'
        +       T.ctaMail
        +     '</a>'
        +     '<a class="cdski-careers-cta cdski-careers-cta-wa" href="' + waHref + '" target="_blank" rel="noopener noreferrer">'
        +       '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/><path d="M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413A11.815 11.815 0 0012.05 0zm0 21.785h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884z"/></svg>'
        +       T.ctaWa
        +     '</a>'
        +   '</div>'
        + '</div>'
        + '</div>';
      contact.parentNode.insertBefore(sec, contact.nextSibling);
      return true;
    }

    var attempts = 0;
    var iv = setInterval(function () {
      insert();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    insert();
  }

  /* =========================================================
     FASE 1 — Destapar el embudo
     1. El modal de reserva era ilegible (texto blanco sobre panel
        blanco): el color se corrige por CSS, aquí se le ponen
        etiquetas accesibles a unos campos que sólo tenían placeholder.
     2. El home no enlazaba a /pago/ en ninguna parte.
     3. El formulario de contacto no tenía dónde guardar el lead.
     ========================================================= */

  var FUNNEL_COPY = {
    es: {
      pay: 'Pagar mi reserva',
      payHint: 'Pago seguro: Webpay, Mercado Pago, PayPal y transferencia.',
      payAfterQuote: '¿Ya coordinaste tu clase? Paga aquí',
      labelName: 'Nombre completo',
      labelEmail: 'Email',
      labelPhone: 'WhatsApp',
      labelDate: 'Fecha tentativa',
      labelMsg: 'Mensaje',
      waIntro: 'Hola! Quiero consultar por clases con CDSKI:'
    },
    en: {
      pay: 'Pay my booking',
      payHint: 'Secure payment: Webpay, Mercado Pago, PayPal and bank transfer.',
      payAfterQuote: 'Already arranged your lesson? Pay here',
      labelName: 'Full name',
      labelEmail: 'Email',
      labelPhone: 'WhatsApp',
      labelDate: 'Preferred date',
      labelMsg: 'Message',
      waIntro: 'Hola! Quiero consultar por clases con CDSKI:'
    },
    pt: {
      pay: 'Pagar minha reserva',
      payHint: 'Pagamento seguro: Webpay, Mercado Pago, PayPal e transferência.',
      payAfterQuote: 'Já combinou sua aula? Pague aqui',
      labelName: 'Nome completo',
      labelEmail: 'Email',
      labelPhone: 'WhatsApp',
      labelDate: 'Data pretendida',
      labelMsg: 'Mensagem',
      waIntro: 'Hola! Quiero consultar por clases con CDSKI:'
    }
  };

  var PAY_URL = '/pago/';

  var CARD_ICON = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>';

  function payLink(cls, label) {
    var a = document.createElement('a');
    a.className = 'cdski-pay-link ' + cls;
    a.href = PAY_URL;
    a.innerHTML = CARD_ICON + '<span>' + label + '</span>';
    return a;
  }

  /* ---- 2. Caminos hacia la pasarela de pago ---- */
  function setupPaymentPaths() {
    var T = FUNNEL_COPY[currentLang()];

    // a) Footer, junto a los logos de medios de pago que ya están ahí.
    function inFooter() {
      var footer = document.querySelector('footer');
      if (!footer) return false;
      if (footer.querySelector('.cdski-pay-footer')) return true;
      var host = null;
      var nodes = footer.querySelectorAll('div, span, p');
      for (var i = 0; i < nodes.length; i++) {
        var txt = (nodes[i].textContent || '');
        if (txt.indexOf('PCI') !== -1 && nodes[i].children.length <= 2) { host = nodes[i]; break; }
      }
      var anchor = host ? (host.closest ? host.closest('div') : host) : null;
      if (!anchor || !anchor.parentNode) {
        // Si la fila de medios de pago no está, colgamos del pie igual.
        anchor = footer.querySelector('.grid');
        if (!anchor || !anchor.parentNode) return false;
      }
      var wrap = document.createElement('div');
      wrap.className = 'cdski-pay-footer';
      wrap.appendChild(payLink('cdski-pay-primary', T.pay));
      var hint = document.createElement('span');
      hint.className = 'cdski-pay-hint';
      hint.textContent = T.payHint;
      wrap.appendChild(hint);
      anchor.parentNode.insertBefore(wrap, anchor.nextSibling);
      return true;
    }

    // b) Bajo los botones de la calculadora, para quien ya cotizó.
    function inCalculator() {
      var links = document.querySelectorAll('a[href*="wa.me/56940211459"]');
      var target = null;
      for (var i = 0; i < links.length; i++) {
        var href = '';
        try { href = decodeURIComponent(links[i].getAttribute('href') || ''); } catch (e) { href = ''; }
        if (href.indexOf('Quiero reservar clases con CDSKI') !== -1) { target = links[i]; break; }
      }
      if (!target || !target.parentNode) return false;
      if (target.parentNode.querySelector('.cdski-pay-quote')) return true;
      target.parentNode.insertBefore(payLink('cdski-pay-quote', T.payAfterQuote), target.nextSibling);
      return true;
    }

    // c) Menú móvil nativo del sitio: se monta y desmonta con React.
    function inNativeMenu() {
      var panel = document.querySelector('header div[class*="bg-white/95"]');
      if (!panel) return false;
      var list = panel.querySelector('div');
      if (!list) return false;
      if (list.querySelector('.cdski-pay-nav')) return true;
      list.appendChild(payLink('cdski-pay-nav', T.pay));
      return true;
    }

    // El panel se monta al pulsar la hamburguesa: reintentamos justo después.
    document.addEventListener('click', function (e) {
      var t = e.target;
      var hit = t && t.closest ? t.closest('button[aria-label="Toggle menu"]') : null;
      if (!hit) return;
      setTimeout(inNativeMenu, 60);
      setTimeout(inNativeMenu, 260);
      setTimeout(inNativeMenu, 700);
    });

    var attempts = 0;
    var iv = setInterval(function () {
      inFooter(); inCalculator(); inNativeMenu();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    inFooter(); inCalculator(); inNativeMenu();
  }

  /* ---- 1. Modal de reserva: etiquetas accesibles + salida a pago ---- */
  function setupReservationModal() {
    var T = FUNNEL_COPY[currentLang()];

    function labelFor(el) {
      var ph = (el.getAttribute('placeholder') || '').toLowerCase();
      if (el.type === 'date') return T.labelDate;
      if (el.tagName === 'TEXTAREA') return T.labelMsg;
      if (ph.indexOf('mail') !== -1) return T.labelEmail;
      if (ph.indexOf('whats') !== -1 || el.type === 'tel') return T.labelPhone;
      if (ph) return el.getAttribute('placeholder');
      return T.labelName;
    }

    function dressModal(modal) {
      if (!modal || modal.getAttribute('data-cdski-modal') === '1') return;
      modal.setAttribute('data-cdski-modal', '1');
      modal.classList.add('cdski-modal-fixed');

      var fields = modal.querySelectorAll('input, select, textarea');
      Array.prototype.forEach.call(fields, function (el) {
        if (!el.getAttribute('aria-label')) el.setAttribute('aria-label', labelFor(el));
      });

      // Salida a la pasarela desde el propio modal.
      var btns = modal.querySelectorAll('button');
      var confirm = null;
      for (var i = 0; i < btns.length; i++) {
        if (btns[i].type === 'submit' || (btns[i].textContent || '').length > 6) confirm = btns[i];
      }
      if (confirm && confirm.parentNode && !modal.querySelector('.cdski-pay-modal')) {
        confirm.parentNode.insertBefore(payLink('cdski-pay-modal', T.pay), confirm.nextSibling);
      }
      guardForm(modal.querySelector('form') || modal);
    }

    function scan() {
      var candidates = document.querySelectorAll('[class*="z-[60]"], [class*="z-[70]"], [role="dialog"]');
      Array.prototype.forEach.call(candidates, function (el) {
        if (el.querySelector && el.querySelector('input')) dressModal(el);
      });
    }

    if (window.MutationObserver) {
      var mo = new MutationObserver(function () { scan(); });
      mo.observe(document.body, { childList: true, subtree: true });
    }
    document.addEventListener('click', function () { setTimeout(scan, 120); });
    scan();
  }

  /* ---- 3. Resguardo de leads ---- */
  var LEAD_KEY = 'cdski_leads';

  function collectLead(scope) {
    var out = {};
    var els = scope.querySelectorAll('input, select, textarea');
    Array.prototype.forEach.call(els, function (el, i) {
      if (el.type === 'hidden' || el.type === 'button' || el.type === 'submit') return;
      var key = el.name || el.getAttribute('aria-label') || el.getAttribute('placeholder') || (el.type + '_' + i);
      var val = (el.value || '').trim();
      if (val) out[key] = val;
    });
    return out;
  }

  function persistLead(data) {
    try {
      var raw = window.localStorage.getItem(LEAD_KEY);
      var list = raw ? JSON.parse(raw) : [];
      list.push({ at: new Date().toISOString(), page: location.pathname, data: data });
      while (list.length > 20) list.shift();
      window.localStorage.setItem(LEAD_KEY, JSON.stringify(list));
    } catch (e) { /* modo privado o storage lleno: seguimos igual */ }
  }

  function sendLead(data) {
    var endpoint = window.CDSKI_LEAD_ENDPOINT;
    if (!endpoint || !window.fetch) return;
    try {
      window.fetch(endpoint, {
        method: 'POST',
        mode: 'cors',
        keepalive: true,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
      })['catch'](function () { /* el lead ya quedó en localStorage */ });
    } catch (e) { /* no-op */ }
  }

  function leadWhatsAppUrl(data) {
    var T = FUNNEL_COPY[currentLang()];
    var text = T.waIntro + '\n';
    for (var k in data) {
      if (Object.prototype.hasOwnProperty.call(data, k)) text += '\n- ' + k + ': ' + data[k];
    }
    return 'https://wa.me/56940211459?text=' + encodeURIComponent(text);
  }

  function reactHandles(form) {
    for (var k in form) {
      if (k.indexOf('__reactProps') === 0) {
        var p = form[k];
        if (p && typeof p.onSubmit === 'function') return true;
      }
    }
    return false;
  }

  function guardForm(form) {
    if (!form || form.tagName !== 'FORM') return;
    if (form.getAttribute('data-cdski-guarded') === '1') return;
    form.setAttribute('data-cdski-guarded', '1');

    // Nunca por GET: los datos del lead no pueden terminar en la URL.
    if ((form.getAttribute('method') || 'get').toLowerCase() !== 'post') {
      form.setAttribute('method', 'post');
    }

    form.addEventListener('submit', function (e) {
      var data = collectLead(form);
      persistLead(data);
      sendLead(data);
      // Si React no llegó a hidratar, el envío lo completamos nosotros.
      if (!reactHandles(form)) {
        e.preventDefault();
        window.location.href = leadWhatsAppUrl(data);
      }
    }, true);
  }

  function setupLeadSafety() {
    function scan() {
      var forms = document.querySelectorAll('#contact form, form');
      Array.prototype.forEach.call(forms, guardForm);
    }
    var attempts = 0;
    var iv = setInterval(function () {
      scan();
      attempts++;
      if (attempts > 20) clearInterval(iv);
    }, 500);
    scan();
  }

  /* =========================================================
     FASE 2 — Acortar el camino
     04. El partner y el blog se interponían antes de contactar.
     06. No había ningún precio arriba del pliegue.
     08. "Reservar Ahora" apuntaba a dos destinos distintos.
     05. Ningún camino decía qué pasa después de enviar.
     ========================================================= */

  var PATH_COPY = {
    es: {
      blogMore: 'Ver todas las notas',
      blogLess: 'Ver menos',
      priceFrom: 'Desde',
      pricePer: 'por persona',
      priceLink: 'Calcula tu precio',
      answer: 'Respondemos por WhatsApp tan pronto nos sea posible, con la disponibilidad confirmada.'
    },
    en: {
      blogMore: 'See all posts',
      blogLess: 'Show less',
      priceFrom: 'From',
      pricePer: 'per person',
      priceLink: 'Calculate your price',
      answer: 'We reply on WhatsApp as soon as we can, with availability confirmed.'
    },
    pt: {
      blogMore: 'Ver todas as notas',
      blogLess: 'Ver menos',
      priceFrom: 'A partir de',
      pricePer: 'por pessoa',
      priceLink: 'Calcule seu preço',
      answer: 'Respondemos pelo WhatsApp assim que possível, com a disponibilidade confirmada.'
    }
  };

  /* ---- 04. El blog y el partner, después del formulario ---- */
  function setupPageOrder() {
    function ensure() {
      var contact = document.getElementById('contact');
      var blog = document.getElementById('blog');
      var tourevo = document.getElementById('tourevo');
      if (!contact || !blog || !tourevo || !contact.parentNode) return;
      if (contact.nextElementSibling === blog && blog.nextElementSibling === tourevo) return;
      contact.parentNode.insertBefore(blog, contact.nextSibling);
      blog.parentNode.insertBefore(tourevo, blog.nextSibling);
    }
    keepAlive(ensure);
  }

  /* ---- 04b. El blog mostraba 15 tarjetas: dejamos 3 ---- */
  function setupBlogDigest() {
    var T = PATH_COPY[currentLang()];
    function ensure() {
      var blog = document.getElementById('blog');
      if (!blog) return;
      var grid = blog.querySelector('.grid');
      if (!grid || grid.children.length <= 3) return;
      if (blog.querySelector('.cdski-blog-toggle')) {
        grid.classList.add('cdski-blog-collapsed');
        return;
      }
      grid.classList.add('cdski-blog-collapsed');
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'cdski-blog-toggle';
      btn.textContent = T.blogMore + ' (' + grid.children.length + ')';
      btn.addEventListener('click', function () {
        var open = grid.classList.toggle('cdski-blog-collapsed');
        btn.textContent = open ? T.blogMore + ' (' + grid.children.length + ')' : T.blogLess;
      });
      if (grid.parentNode) grid.parentNode.insertBefore(btn, grid.nextSibling);
    }
    keepAlive(ensure);
  }

  /* ---- 06. Precio de entrada en el hero ----
     Se lee del propio cotizador para que nunca quede desfasado; si no se
     puede leer, no se inventa nada y la franja simplemente no aparece. */
  function setupHeroPrice() {
    var T = PATH_COPY[currentLang()];

    // Se lee dentro del bloque de resumen del cotizador, no del texto suelto
    // de la sección: fuera de ahí hay otros números ("desde 1 persona…").
    function readQuote() {
      var pricing = document.getElementById('pricing');
      if (!pricing) return null;

      var marker = null;
      var nodes = pricing.querySelectorAll('div, span, p');
      for (var i = 0; i < nodes.length; i++) {
        if (nodes[i].children.length) continue;
        if (/^\d+\s*(persona|pessoa|person|people)/i.test((nodes[i].textContent || '').trim())) {
          marker = nodes[i];
          break;
        }
      }
      if (!marker) return null;

      var box = marker.parentNode;
      for (var up = 0; up < 4 && box; up++) {
        if ((box.innerText || '').indexOf('Total') !== -1) break;
        box = box.parentNode;
      }
      if (!box) return null;

      var txt = box.innerText || '';
      var mTotal = /Total\s*\$\s*([\d.]+)/.exec(txt);
      var mPeople = /(\d+)\s*(?:persona|pessoa|person|people)/i.exec(txt);
      if (!mTotal || !mPeople) return null;

      var total = parseInt(mTotal[1].replace(/\./g, ''), 10);
      var n = parseInt(mPeople[1], 10);
      if (!total || !n) return null;

      // La modalidad son las dos líneas justo antes de "N persona(s)":
      // se saltan el horario (lleva ':'), los montos y los subtotales.
      var lines = txt.split('\n').map(function (l) { return l.trim(); }).filter(Boolean);
      var at = -1;
      for (var j = 0; j < lines.length; j++) {
        if (/^\d+\s*(persona|pessoa|person|people)/i.test(lines[j])) { at = j; break; }
      }
      var mode = [];
      for (var k = at - 1; k >= 0 && mode.length < 2; k--) {
        var l = lines[k];
        if (/^(CLP|USD)$/i.test(l)) break;
        if (!l || l.indexOf('$') !== -1 || l.indexOf(':') !== -1 || l.indexOf('/') !== -1) continue;
        mode.unshift(l);
      }
      // "2 persona(s) · 1 día(s)" -> "2 persona(s)": ya viene localizado por el sitio
      var quienes = (lines[at] || '').split('·')[0].trim();
      return { per: Math.round(total / n), total: total, mode: mode.join(' · '), quienes: quienes };
    }

    function ensure() {
      var q = readQuote();
      if (!q || !q.total) return;
      var formatted;
      try { formatted = q.total.toLocaleString('es-CL'); } catch (e) { formatted = String(q.total); }

      var existing = document.querySelector('.cdski-hero-price');
      if (existing) {
        var val = existing.querySelector('.cdski-hero-price-val');
        if (val) val.textContent = '$' + formatted;
        return;
      }
      var etiqueta = q.mode + (q.quienes ? ' · ' + q.quienes : '');
      var hero = document.querySelector('main section');
      if (!hero) return;
      var ctas = hero.querySelector('.flex.flex-col.sm\\:flex-row.gap-4');
      if (!ctas || !ctas.parentNode) return;

      var band = document.createElement('div');
      band.className = 'cdski-hero-price';
      band.innerHTML = '<span class="cdski-hero-price-from">' + etiqueta + '</span>'
        + '<span class="cdski-hero-price-val">$' + formatted + '</span>'
        + '<a href="#pricing">' + T.priceLink + '</a>';
      ctas.parentNode.insertBefore(band, ctas.nextSibling);
    }
    keepAlive(ensure);
  }

  /* ---- 08. "Reservar" siempre lleva al cotizador ---- */
  function setupCtaConsistency() {
    var BOOK = /(reserv|book|agenda)/i;
    function ensure() {
      var links = document.querySelectorAll('a[href="#contact"], a[href$="/#contact"]');
      Array.prototype.forEach.call(links, function (a) {
        var txt = (a.textContent || '').trim();
        if (!txt || !BOOK.test(txt)) return;
        if (a.className && a.className.indexOf('cdski-') !== -1) return;
        a.setAttribute('href', '#pricing');
      });
    }
    keepAlive(ensure);
  }

  /* ---- 05. Qué pasa después de enviar ---- */
  function setupResponseExpectation() {
    var T = PATH_COPY[currentLang()];

    function note() {
      var el = document.createElement('p');
      el.className = 'cdski-answer-note';
      el.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>'
        + '<span>' + T.answer + '</span>';
      return el;
    }

    function ensure() {
      // Bajo el enlace de pago de la calculadora (queda al final de esa columna).
      var quote = document.querySelector('.cdski-pay-quote');
      if (quote && quote.parentNode && !quote.parentNode.querySelector('.cdski-answer-note')) {
        quote.parentNode.insertBefore(note(), quote.nextSibling);
      }
      // Bajo el botón de envío del formulario de contacto.
      var form = document.querySelector('#contact form');
      if (form && !form.querySelector('.cdski-answer-note')) {
        var submit = form.querySelector('button[type="submit"]');
        if (submit && submit.parentNode) {
          submit.parentNode.insertBefore(note(), submit.nextSibling);
        }
      }
    }
    keepAlive(ensure);
  }

  /* =========================================================
     FASE 3 — Reforzar la confianza
     09. La barra de reputación del hero estaba en inglés en las
         versiones ES y PT, y en blanco al 50% sobre una foto clara.
     10. Un solo testimonio en toda la página.
     11. El bloque del partner se leía como otro sitio.
     ========================================================= */

  var TRUST_COPY = {
    es: {
      rating: '5,0 en Google',
      years: '+10 años',
      resorts: '3 centros de ski',
      reviewsLabel: 'reseñas verificadas',
      reviewsCta: 'Ver las reseñas en Google'
    },
    en: {
      rating: '5.0 on Google',
      years: '10+ years',
      resorts: '3 ski resorts',
      reviewsLabel: 'verified reviews',
      reviewsCta: 'Read the reviews on Google'
    },
    pt: {
      rating: '5,0 no Google',
      years: '+10 anos',
      resorts: '3 centros de esqui',
      reviewsLabel: 'avaliações verificadas',
      reviewsCta: 'Ver as avaliações no Google'
    }
  };

  /* ---- 09. Barra de reputación: idioma y contraste ---- */
  function setupTrustBar() {
    var T = TRUST_COPY[currentLang()];
    var MAP = [
      [/^5\.0\s*Google Reviews$/i, T.rating],
      [/^10\+\s*years$/i, T.years],
      [/^3\s*ski resorts$/i, T.resorts]
    ];

    function ensure() {
      var hero = document.querySelector('main section');
      if (!hero) return;
      var spans = hero.querySelectorAll('span');
      var touched = 0;
      Array.prototype.forEach.call(spans, function (el) {
        if (el.children.length) return;
        var txt = (el.textContent || '').trim();
        for (var i = 0; i < MAP.length; i++) {
          if (MAP[i][0].test(txt)) {
            if (txt !== MAP[i][1]) el.textContent = MAP[i][1];
            touched++;
            var row = el.parentNode;
            while (row && row !== hero && !/mt-16/.test(row.className || '')) row = row.parentNode;
            if (row && row !== hero) row.classList.add('cdski-trust-row');
            break;
          }
        }
      });
      return touched > 0;
    }
    keepAlive(ensure);
  }

  /* ---- 10. La reputación que el sitio ya declara, junto al testimonio ----
     No se inventan testimonios: se muestra el agregado que el propio
     JSON-LD del sitio publica y se invita a leer las reseñas reales. */
  function setupSocialProof() {
    var T = TRUST_COPY[currentLang()];

    function readAggregate() {
      var scripts = document.querySelectorAll('script[type="application/ld+json"]');
      for (var i = 0; i < scripts.length; i++) {
        var raw = scripts[i].textContent || '';
        if (raw.indexOf('aggregateRating') === -1) continue;
        try {
          var data = JSON.parse(raw);
          var graph = data['@graph'] || [data];
          for (var j = 0; j < graph.length; j++) {
            var ar = graph[j] && graph[j].aggregateRating;
            if (ar && ar.ratingValue && ar.reviewCount) {
              return { value: String(ar.ratingValue), count: String(ar.reviewCount) };
            }
          }
        } catch (e) { /* seguimos con el siguiente bloque */ }
      }
      return null;
    }

    function starsHtml(n) {
      var html = '';
      for (var i = 0; i < n; i++) {
        html += '<svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
      }
      return html;
    }

    function ensure() {
      var section = document.getElementById('testimonials');
      if (!section) return;
      if (section.querySelector('.cdski-rating-badge')) return;
      var agg = readAggregate();
      if (!agg) return;
      var heading = section.querySelector('h2');
      if (!heading || !heading.parentNode) return;

      var badge = document.createElement('div');
      badge.className = 'cdski-rating-badge';
      badge.innerHTML =
        '<span class="cdski-rating-stars">' + starsHtml(5) + '</span>'
        + '<span class="cdski-rating-value">' + agg.value.replace('.', ',') + '</span>'
        + '<span class="cdski-rating-count">' + agg.count + ' ' + T.reviewsLabel + '</span>';
      heading.parentNode.insertBefore(badge, heading.nextSibling);
    }
    keepAlive(ensure);
  }

  /* ---- 11. El bloque del partner, con la forma del resto del sitio ---- */
  function setupPartnerAlignment() {
    function ensure() {
      var tourevo = document.getElementById('tourevo');
      if (!tourevo) return;
      tourevo.classList.add('cdski-partner-aligned');
    }
    keepAlive(ensure);
  }


  /* =========================================================
     Acompanantes que sólo van en el traslado
     Suman al traslado (y al vehículo), no a las clases.
     El cálculo vive en el bundle; aquí va el control.
     ========================================================= */

  var COMPANION_COPY = {
    es: {
      label: 'Acompañantes',
      desc: 'Sólo traslado, no toman clases',
      hint: 'Cada acompañante suma $61.750 al traslado.'
    },
    en: {
      label: 'Companions',
      desc: 'Transfer only, no lessons',
      hint: 'Each companion adds $61.750 to the transfer.'
    },
    pt: {
      label: 'Acompanhantes',
      desc: 'Só traslado, não fazem aulas',
      hint: 'Cada acompanhante soma $61.750 ao traslado.'
    }
  };

  function setupCompanions() {
    var T = COMPANION_COPY[currentLang()];
    var DIAS = /(Cantidad de d|Number of days|N.mero de d|Quantidade de d|days)/i;

    function quote() { return window.__cdskiQuote; }

    function current() {
      var q = quote();
      return (q && q.state && q.state.companions) || 0;
    }

    function setCompanions(n) {
      var q = quote();
      if (!q || typeof q.set !== 'function') return;
      var next = {};
      for (var k in q.state) {
        if (Object.prototype.hasOwnProperty.call(q.state, k)) next[k] = q.state[k];
      }
      next.companions = Math.max(0, Math.min(12, n));
      q.set(next);
      setTimeout(refresh, 60);
    }

    // Se clona la fila de "Cantidad de días" para que el control quede
    // idéntico a los contadores que el sitio ya tiene.
    function build(modelo) {
      var row = modelo.cloneNode(true);
      row.classList.add('cdski-companions');
      var label = row.querySelector('span');
      if (label) {
        label.textContent = T.label;
        var nota = document.createElement('span');
        nota.className = 'cdski-companions-desc';
        nota.textContent = T.desc;
        label.parentNode.insertBefore(nota, label.nextSibling);
        label.classList.add('cdski-companions-label');
      }
      var btns = row.querySelectorAll('button');
      if (btns.length < 2) return null;
      btns[0].classList.add('cdski-companions-minus');
      btns[1].classList.add('cdski-companions-plus');
      btns[0].removeAttribute('disabled');
      btns[0].addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        setCompanions(current() - 1);
      });
      btns[1].addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        setCompanions(current() + 1);
      });
      var val = row.querySelector('.cdski-companions-value')
        || btns[0].nextElementSibling;
      if (val) val.classList.add('cdski-companions-value');
      return row;
    }

    function refresh() {
      var row = document.querySelector('.cdski-companions');
      if (!row) return;
      var n = current();
      var val = row.querySelector('.cdski-companions-value');
      if (val) val.textContent = String(n);
      var minus = row.querySelector('.cdski-companions-minus');
      if (minus) minus.disabled = n === 0;
    }

    function ensure() {
      if (!quote()) return;
      var grupos = document.querySelectorAll('#pricing .space-y-3');
      var modelo = null, grupo = null;
      for (var i = 0; i < grupos.length && !modelo; i++) {
        var hijos = grupos[i].children;
        for (var j = 0; j < hijos.length; j++) {
          if (hijos[j].classList.contains('cdski-companions')) continue;
          if (DIAS.test(hijos[j].textContent || '')) { modelo = hijos[j]; grupo = grupos[i]; break; }
        }
      }
      if (!modelo || !grupo) return;

      var row = document.querySelector('.cdski-companions');
      if (!row || !document.body.contains(row)) {
        row = build(modelo);
        if (!row) return;
        grupo.insertBefore(row, modelo.nextSibling);
      } else if (row.parentNode !== grupo) {
        grupo.insertBefore(row, modelo.nextSibling);
      }
      refresh();
    }

    keepAlive(ensure);
    // React vuelve a renderizar con cada interacción: resincronizamos el número.
    document.addEventListener('click', function () { setTimeout(refresh, 80); });
  }


  /* =========================================================
     Campaña de Septiembre — Fiestas Patrias en la nieve
     Vive sola: se arma con la fecha del sistema, cuenta atrás al 18 y
     desaparece por sí misma el 1 de octubre. Al año siguiente vuelve a
     aparecer sin tocar el código.
     ========================================================= */

  var SEPT_COPY = {
    es: {
      aria: 'Fiestas Patrias en la nieve',
      kicker: 'Septiembre en la nieve',
      title: 'Este <em>18 de Septiembre</em> celebra arriba en la montaña',
      lead: 'Viernes 18 y sábado 19: el fin de semana largo llega con los días más largos, sol de primavera y nieve blanda. La mejor combinación del año para <strong>aprender desde cero</strong>, <strong>subir de nivel</strong> o <strong>salir por primera vez fuera de pista</strong>.',
      countdownLabel: 'Faltan para el 18',
      units: ['días', 'horas', 'min', 'seg'],
      liveTitle: '¡Estamos en Fiestas Patrias!',
      liveText: 'Quedan cupos esta semana larga. Escríbenos y te confirmamos disponibilidad hoy mismo.',
      cardsTitle: 'Elige cómo quieres vivir tu 18',
      cards: [
        {
          tag: 'Primera vez',
          title: 'Aprende a esquiar desde cero',
          text: '¿Nunca te has puesto unos esquís? Este 18 es tu momento. Partimos desde cero, con equipo incluido y un instructor experto contigo toda la jornada.',
          cta: 'Quiero aprender'
        },
        {
          tag: 'Sube de nivel',
          title: 'Pasa de bajar a esquiar bien',
          text: 'Ya te deslizas, pero quieres girar limpio, tomar velocidad y perderle el miedo a las pistas difíciles. Clases enfocadas justo en lo que te falta.',
          cta: 'Quiero mejorar'
        },
        {
          tag: 'Freeride',
          title: 'Sal fuera de pista con guía',
          text: 'Nieve virgen, guía experto y equipo de seguridad completo. La experiencia de la que todos hablan cuando vuelven de la montaña.',
          cta: 'Quiero fuera de pista'
        }
      ],
      note: 'Cupos limitados: el fin de semana largo se llena primero.',
      cta: 'Reserva tu 18 en la nieve'
    },
    en: {
      aria: 'Chilean Independence week in the snow',
      kicker: 'September in the snow',
      title: 'Spend this <em>September 18th</em> up in the mountains',
      lead: 'Friday the 18th and Saturday the 19th: Chile’s long weekend arrives with longer days, spring sun and soft snow. The best mix of the year to <strong>learn from scratch</strong>, <strong>level up</strong> or <strong>ride off-piste for the first time</strong>.',
      countdownLabel: 'Countdown to the 18th',
      units: ['days', 'hours', 'min', 'sec'],
      liveTitle: 'Independence week is on!',
      liveText: 'There are still spots left this long week. Message us and we confirm availability today.',
      cardsTitle: 'Choose how you want to spend it',
      cards: [
        {
          tag: 'First time',
          title: 'Learn to ski from scratch',
          text: 'Never had skis on? This is your moment. We start from zero, gear included and an expert instructor with you all day long.',
          cta: 'I want to learn'
        },
        {
          tag: 'Level up',
          title: 'Go from surviving to really skiing',
          text: 'You already slide down, but you want clean turns, more speed and no fear of the steeper runs. Lessons aimed exactly at what you are missing.',
          cta: 'I want to improve'
        },
        {
          tag: 'Freeride',
          title: 'Head off-piste with a guide',
          text: 'Untracked snow, an expert guide and full safety equipment. The experience everyone talks about on the way back down.',
          cta: 'I want off-piste'
        }
      ],
      note: 'Limited spots: the long weekend fills up first.',
      cta: 'Book your September 18th on the snow'
    },
    pt: {
      aria: 'Feriado do 18 de Setembro na neve',
      kicker: 'Setembro na neve',
      title: 'Passe este <em>18 de Setembro</em> lá em cima na montanha',
      lead: 'Sexta 18 e sábado 19: o feriadão chega com os dias mais longos, sol de primavera e neve macia. A melhor combinação do ano para <strong>aprender do zero</strong>, <strong>subir de nível</strong> ou <strong>sair pela primeira vez fora de pista</strong>.',
      countdownLabel: 'Faltam para o dia 18',
      units: ['dias', 'horas', 'min', 'seg'],
      liveTitle: 'O feriadão já começou!',
      liveText: 'Ainda há vagas nesta semana longa. Fale com a gente e confirmamos a disponibilidade hoje mesmo.',
      cardsTitle: 'Escolha como quer viver o feriadão',
      cards: [
        {
          tag: 'Primeira vez',
          title: 'Aprenda a esquiar do zero',
          text: 'Nunca calçou um esqui? Este é o seu momento. Começamos do zero, com equipamento incluído e um instrutor experto com você o dia inteiro.',
          cta: 'Quero aprender'
        },
        {
          tag: 'Suba de nível',
          title: 'Deixe de só descer e esquie de verdade',
          text: 'Você já desliza, mas quer curvas limpas, mais velocidade e perder o medo das pistas difíceis. Aulas focadas exatamente no que falta.',
          cta: 'Quero melhorar'
        },
        {
          tag: 'Freeride',
          title: 'Saia fora de pista com guia',
          text: 'Neve virgem, guia experto e equipamento de segurança completo. A experiência de que todos falam na volta da montanha.',
          cta: 'Quero fora de pista'
        }
      ],
      note: 'Vagas limitadas: o feriadão enche primeiro.',
      cta: 'Reserve seu 18 de Setembro na neve'
    }
  };

  var SEPT_ICONS = [
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2.5l1.9 4.4 4.4 1.9-4.4 1.9L10 15.1 8.1 10.7 3.7 8.8l4.4-1.9z"/><path d="M17.5 14l.9 2.1 2.1.9-2.1.9-.9 2.1-.9-2.1-2.1-.9 2.1-.9z"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 17l5-5 4 3 8-8"/><path d="M15 7h5v5"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20l6-14 5 8 2-3 5 9z"/><circle cx="8" cy="4" r="1.6"/></svg>'
  ];

  // Chile está en UTC-3 durante septiembre, así que las fechas se fijan en UTC.
  function septWindow(now) {
    var year = now.getUTCFullYear();
    var start = Date.UTC(year, 7, 20, 3, 0, 0);   // 20 de agosto
    var target = Date.UTC(year, 8, 18, 3, 0, 0);  // 18 de septiembre, 00:00
    var end = Date.UTC(year, 9, 1, 3, 0, 0);      // 1 de octubre, 00:00
    var t = now.getTime();
    if (t < start || t >= end) return null;
    return { target: target, live: t >= target };
  }

  function setupSeptember() {
    var win = septWindow(new Date());
    if (!win) return;

    var T = SEPT_COPY[currentLang()];
    var tick = null;

    function boxesHtml() {
      var html = '';
      T.units.forEach(function (u, i) {
        html += '<span class="cdski-sept-box">'
          + '<b data-unit="' + i + '">--</b>'
          + '<i>' + u + '</i>'
          + '</span>';
      });
      return html;
    }

    function cardsHtml() {
      var html = '';
      T.cards.forEach(function (c, i) {
        html += '<article class="cdski-sept-card" style="transition-delay:' + (0.08 * i + 0.05).toFixed(2) + 's">'
          + '<span class="cdski-sept-tag">' + c.tag + '</span>'
          + '<div class="cdski-sept-card-icon">' + SEPT_ICONS[i] + '</div>'
          + '<h3>' + c.title + '</h3>'
          + '<p>' + c.text + '</p>'
          + '<a href="#contact">' + c.cta
          + '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>'
          + '</a>'
          + '</article>';
      });
      return html;
    }

    function confettiHtml() {
      var html = '';
      for (var i = 0; i < 16; i++) {
        html += '<i aria-hidden="true" style="left:' + ((i * 6.4) % 97).toFixed(1) + '%;'
          + 'animation-duration:' + (9 + (i % 5) * 2.5) + 's;'
          + 'animation-delay:' + (i * 0.7).toFixed(1) + 's">'
          + (i % 3 === 0 ? '❄' : i % 3 === 1 ? '★' : '✦') + '</i>';
      }
      return html;
    }

    function countdownHtml() {
      if (win.live) {
        return '<div class="cdski-sept-live">'
          + '<strong>' + T.liveTitle + '</strong>'
          + '<span>' + T.liveText + '</span>'
          + '</div>';
      }
      return '<div class="cdski-sept-countdown" role="timer" aria-live="off">'
        + '<span class="cdski-sept-cd-label">' + T.countdownLabel + '</span>'
        + '<div class="cdski-sept-boxes">' + boxesHtml() + '</div>'
        + '</div>';
    }

    function refresh(sec) {
      if (win.live) return;
      var left = Math.max(0, win.target - Date.now());
      var s = Math.floor(left / 1000);
      var vals = [Math.floor(s / 86400), Math.floor(s / 3600) % 24, Math.floor(s / 60) % 60, s % 60];
      Array.prototype.forEach.call(sec.querySelectorAll('.cdski-sept-box b'), function (b, i) {
        var v = String(vals[i]);
        if (i > 0 && v.length < 2) v = '0' + v;
        if (b.textContent !== v) b.textContent = v;
      });
    }

    function insertSection() {
      var existing = document.querySelector('.cdski-sept');
      if (existing && document.contains(existing)) return true;

      var services = document.getElementById('services');
      if (!services || !services.parentNode) return false;

      var sec = document.createElement('section');
      sec.className = 'cdski-sept';
      sec.id = 'septiembre';
      sec.setAttribute('aria-label', T.aria);
      sec.innerHTML =
        '<div class="cdski-sept-confetti" aria-hidden="true">' + confettiHtml() + '</div>'
        + '<div class="cdski-sept-inner">'
        +   '<div class="cdski-sept-head">'
        +     '<span class="cdski-sept-kicker">' + cdskiFlag('cl', 'Chile', 22) + T.kicker + '</span>'
        +     '<h2 class="cdski-sept-title">' + T.title + '</h2>'
        +     '<p class="cdski-sept-lead">' + T.lead + '</p>'
        +     countdownHtml()
        +   '</div>'
        +   '<h3 class="cdski-sept-cards-title">' + T.cardsTitle + '</h3>'
        +   '<div class="cdski-sept-cards">' + cardsHtml() + '</div>'
        +   '<div class="cdski-sept-foot">'
        +     '<a class="cdski-sept-cta" href="#contact">' + T.cta + '</a>'
        +     '<span class="cdski-sept-note">' + T.note + '</span>'
        +   '</div>'
        + '</div>';

      // Va delante del saludo a los vecinos, lo más arriba posible del scroll.
      var banner = document.querySelector('.cdski-welcome-banner');
      services.parentNode.insertBefore(sec, banner && banner.parentNode === services.parentNode ? banner : services);

      refresh(sec);
      if (tick) clearInterval(tick);
      if (!win.live) tick = setInterval(function () {
        if (!document.contains(sec)) { clearInterval(tick); tick = null; return; }
        refresh(sec);
      }, 1000);

      if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
          entries.forEach(function (e) {
            if (e.isIntersecting) { e.target.classList.add('cdski-inview'); io.unobserve(e.target); }
          });
        }, { threshold: 0.1 });
        io.observe(sec);
      } else {
        sec.classList.add('cdski-inview');
      }
      return true;
    }

    keepAlive(insertSection);
  }

  /* =========================================================
     Huaso bailando cueca — adorno del hero durante Septiembre
     Va arriba a la derecha de la primera vista, con la bandera y el
     pañuelo animados. Vive dentro de la misma ventana que la campaña.
     ========================================================= */

  var HUASO_TEXTO = {
    es: '¡Disfruta este 18 en la nieve!',
    en: 'Enjoy this September 18th on the snow!',
    pt: 'Aproveite este 18 de Setembro na neve!'
  };

  var BANDERA_SVG =
    '<svg class="cdski-huaso-bandera" viewBox="0 0 58 56" role="img" aria-hidden="true">'
    +  '<rect x="6" y="4" width="3" height="50" rx="1.5" fill="#8b5a2b"/>'
    +  '<circle cx="7.5" cy="3.4" r="2.6" fill="#e0b972"/>'
    +  '<g class="cdski-huaso-tela">'
    +    '<rect x="9" y="6" width="44" height="36" fill="#d52b1e"/>'
    +    '<rect x="9" y="6" width="44" height="18" fill="#ffffff"/>'
    +    '<rect x="9" y="6" width="18" height="18" fill="#0039a6"/>'
    +    '<path fill="#ffffff" d="M18 9 L19.41 13.06 L23.71 13.15 L20.28 15.74 L21.53 19.85'
    +      ' L18 17.4 L14.47 19.85 L15.72 15.74 L12.29 13.15 L16.59 13.06 Z"/>'
    +  '</g>'
    + '</svg>';

  var HUASO_SVG =
    '<svg class="cdski-huaso-fig" viewBox="0 0 120 130" role="img" aria-hidden="true">'
    +  '<defs><clipPath id="cdskiMantaClip">'
    +    '<path d="M44 50 L80 50 L86 88 L38 88 Z"/>'
    +  '</clipPath></defs>'
    +  '<ellipse cx="56" cy="108" rx="33" ry="2.8" fill="rgba(100,116,139,.32)"/>'
    +  '<g class="cdski-huaso-polvo">'
    +    '<circle class="cdski-huaso-nube" cx="89" cy="100" r="5" fill="#ffffff"/>'
    +    '<circle class="cdski-huaso-nube" cx="96" cy="96" r="3.4" fill="#e0f2fe"/>'
    +    '<circle class="cdski-huaso-nube" cx="84" cy="102" r="4.2" fill="#ffffff"/>'
    +  '</g>'
    +  '<g class="cdski-huaso-cuerpo">'
    +    '<g class="cdski-huaso-skis">'
    +      '<g transform="translate(32,104)">'
    +        '<path d="M0 4 Q-9 2.2 -10.5 -3.2 Q-3.4 -2 1.6 0 Z" fill="#0284c7"/>'
    +        '<rect x="0" y="0" width="54" height="4" rx="2" fill="#0284c7"/>'
    +        '<rect x="8" y="1.1" width="34" height="1.2" rx=".6" fill="rgba(255,255,255,.45)"/>'
    +        '<rect x="33.5" y="-1.6" width="8" height="2.4" rx="1" fill="#1f2937"/>'
    +      '</g>'
    +      '<g transform="translate(36,106)">'
    +        '<path d="M0 4 Q-9 2.2 -10.5 -3.2 Q-3.4 -2 1.6 0 Z" fill="#38bdf8"/>'
    +        '<rect x="0" y="0" width="54" height="4" rx="2" fill="#38bdf8"/>'
    +        '<rect x="8" y="1.1" width="34" height="1.2" rx=".6" fill="rgba(255,255,255,.6)"/>'
    +        '<rect x="10.5" y="-1.6" width="8" height="2.4" rx="1" fill="#1f2937"/>'
    +      '</g>'
    +    '</g>'
    +    '<g class="cdski-huaso-pierna cdski-huaso-pierna-i">'
    +      '<rect x="47" y="86" width="11" height="16" rx="3" fill="#1f2937"/>'
    +      '<path d="M45 100 h13 v6 h-15 a2 2 0 0 1 -2 -3 z" fill="#5b3a1e"/>'
    +      '<circle cx="43" cy="104" r="2.2" fill="none" stroke="#e0b972" stroke-width="1.1"/>'
    +    '</g>'
    +    '<g class="cdski-huaso-pierna cdski-huaso-pierna-d">'
    +      '<rect x="64" y="86" width="11" height="16" rx="3" fill="#1f2937"/>'
    +      '<path d="M64 100 h13 v6 h-15 a2 2 0 0 1 -2 -3 z" fill="#5b3a1e"/>'
    +      '<circle cx="62" cy="104" r="2.2" fill="none" stroke="#e0b972" stroke-width="1.1"/>'
    +    '</g>'
    +    '<path d="M78 55 Q89 61 85 71" fill="none" stroke="#f8fafc" stroke-width="6" stroke-linecap="round"/>'
    +    '<path d="M46 56 Q35 47 30 34" fill="none" stroke="#f8fafc" stroke-width="6" stroke-linecap="round"/>'
    +    '<path d="M44 50 L80 50 L86 88 L38 88 Z" fill="#b91c1c"/>'
    +    '<g clip-path="url(#cdskiMantaClip)">'
    +      '<rect x="30" y="58" width="70" height="5" fill="#f59e0b"/>'
    +      '<rect x="30" y="67" width="70" height="3" fill="#fde68a"/>'
    +      '<rect x="30" y="74" width="70" height="5" fill="#7f1d1d"/>'
    +      '<rect x="60" y="50" width="4" height="40" fill="rgba(0,0,0,.14)"/>'
    +    '</g>'
    +    '<path d="M52 50 L62 60 L72 50 Z" fill="#f8fafc"/>'
    +    '<circle cx="62" cy="38" r="10" fill="#f2c9a2"/>'
    +    '<ellipse cx="53.8" cy="40.4" rx="2.6" ry="1.8" fill="rgba(244,63,94,.35)"/>'
    +    '<ellipse cx="70.2" cy="40.4" rx="2.6" ry="1.8" fill="rgba(244,63,94,.35)"/>'
    +    '<path d="M56.5 36.8 Q58.4 34.4 60.3 36.8 M63.7 36.8 Q65.6 34.4 67.5 36.8"'
    +      ' fill="none" stroke="#1f2937" stroke-width="1.7" stroke-linecap="round"/>'
    +    '<path d="M56.6 43.4 Q62 48.6 67.4 43.4 Q62 45.4 56.6 43.4 Z" fill="#7f1d1d"/>'
    +    '<path d="M55.6 40.6 q3.4 -1.4 6.4 .8 q3 -2.2 6.4 -.8 q-3.4 3.4 -6.4 1.8 q-3 1.6 -6.4 -1.8 z" fill="#4a2c17"/>'
    +    '<ellipse cx="62" cy="27" rx="27" ry="6.4" fill="#e0b972"/>'
    +    '<path d="M49 27 q0 -14 13 -14 q13 0 13 14 z" fill="#eccb8d"/>'
    +    '<rect x="49" y="22" width="26" height="4" fill="#7f1d1d"/>'
    +    '<circle cx="86" cy="72" r="3.6" fill="#f2c9a2"/>'
    +    '<g class="cdski-huaso-mano">'
    +      '<circle cx="29" cy="32" r="4" fill="#f2c9a2"/>'
    +      '<g class="cdski-huaso-panuelo">'
    +        '<path d="M0 0 Q-11 -1 -20 -7 Q-19 -17 -14 -26 Q-5 -23 4 -17'
    +          ' Q2 -8 0 0 Z" transform="translate(29,30)"'
    +          ' fill="#ffffff" stroke="#d8e0e9" stroke-width="1"/>'
    +        '<path d="M-14 -26 Q-9 -14 -20 -7" transform="translate(29,30)"'
    +          ' fill="rgba(15,23,42,.07)" stroke="none"/>'
    +        '<path d="M-2 -3 Q-9 -11 -13 -24"'
    +          ' transform="translate(29,30)" fill="none" stroke="#d8e0e9" stroke-width=".9"/>'
    +      '</g>'
    +    '</g>'
    +  '</g>'
    + '</svg>';

  function setupHuaso() {
    if (!septWindow(new Date())) return;
    var texto = HUASO_TEXTO[currentLang()];

    // En el hero movil no hay rincon libre: cualquier adorno flotante tapa
    // el listado de centros o el titular. Ahi entra en el flujo, sobre el
    // titulo; en pantallas grandes se despega al rincon superior derecho.
    function ubicar(a, hero) {
      var movil = window.matchMedia('(max-width: 767px)').matches;
      var h1 = hero.querySelector('h1');
      var destino = movil && h1 && h1.parentNode ? h1.parentNode : hero;
      if (a.parentNode === destino) return;
      if (destino === hero) destino.appendChild(a);
      else destino.insertBefore(a, h1);
    }

    function insertar() {
      var hero = document.querySelector('main section');
      if (!hero) return false;

      var a = document.querySelector('.cdski-huaso');
      if (!a || !document.contains(a)) {
        a = document.createElement('a');
        a.className = 'cdski-huaso';
        a.href = '#septiembre';
        a.setAttribute('aria-label', texto);
        a.innerHTML =
          '<span class="cdski-huaso-escena">' + BANDERA_SVG + HUASO_SVG + '</span>'
          + '<span class="cdski-huaso-texto">' + texto + '</span>';
      }
      ubicar(a, hero);
      return true;
    }

    window.addEventListener('resize', function () { insertar(); });
    keepAlive(insertar);
  }

  /* =========================================================
     Guiados — nueva modalidad (En Pista / Fuera de Pista)
     Precio por persona que baja al crecer el grupo. La moneda no la
     decide esta sección: sigue la que el visitante eligió en la web
     (window.__cdskiQuote.currency + evento "cdski:moneda").
     ========================================================= */

  // Tarifas base en USD, por persona y por día (1 persona).
  var GUIDES_USD = {
    onpiste: { half: 316, full: 418 },
    offpiste: { half: 428, full: 568 }
  };

  // Factor por persona según el tamaño del grupo: 1, 2, 3, 4, 5, 6 o más.
  var GUIDES_SCALE = [1, 0.92, 0.86, 0.82, 0.78, 0.75];

  var GUIDES_RATE = 950; // misma paridad que usa la calculadora del sitio

  function guidesFactor(n) {
    return GUIDES_SCALE[Math.min(Math.max(n, 1), GUIDES_SCALE.length) - 1];
  }

  function guidesPrice(usdBase, n, currency) {
    var usd = usdBase * guidesFactor(n);
    if (currency === 'USD') return 'US$' + Math.round(usd).toLocaleString('en-US');
    // En pesos se redondea al millar para que no queden cifras rotas.
    return '$' + (Math.round(usd * GUIDES_RATE / 1000) * 1000).toLocaleString('es-CL');
  }

  var GUIDES_COPY = {
    es: {
      aria: 'Guiados en pista y fuera de pista',
      kicker: 'Nueva modalidad · Guiados',
      title: 'Guiados con <em>guía experto</em>: en pista y fuera de pista',
      lead: 'No es una clase: es un día de montaña con un <strong>guía de ski</strong> que te lleva a lo mejor del cerro según tu nivel, la nieve y la hora. El valor es por persona, y mientras más grande el grupo, menos paga cada uno.',
      sizeLabel: '¿Cuántos van?',
      people: ['1', '2', '3', '4', '5', '6 o más'],
      peopleAria: 'personas',
      perPerson: 'por persona / día',
      groupTotal: 'Total del grupo',
      saving: 'por persona vs. 1 solo',
      durations: { half: 'Half Day · 3 hrs', full: 'Full Day · 6 hrs' },
      cards: [
        {
          key: 'onpiste',
          tag: 'Guía de Ski',
          title: 'Guiado En Pista',
          text: 'Recorres el cerro con tu guía: las mejores pistas según la nieve y la hora, sin perder el día buscando dónde ir.',
          bullets: ['Guía experto todo el día', 'Ruta armada a tu nivel', 'Ideal para conocer el cerro']
        },
        {
          key: 'offpiste',
          tag: 'Guía de Freeride · Backcountry',
          title: 'Guiado Fuera de Pista',
          text: 'Nieve virgen fuera del área marcada, con guía de freeride y equipo de seguridad completo incluido en la salida.',
          bullets: ['ARVA, pala y sonda incluidos', 'Evaluación de nieve y terreno', 'Requiere nivel intermedio-avanzado']
        }
      ],
      cta: 'Consultar disponibilidad',
      note: 'Valores por persona y por día. El guiado fuera de pista se confirma según condiciones de nieve y seguridad del terreno.',
      currencyLabel: 'Ver precios en'
    },
    en: {
      aria: 'Guided on-piste and off-piste days',
      kicker: 'New format · Guided days',
      title: 'Guided days with an <em>expert guide</em>: on-piste and off-piste',
      lead: 'This is not a lesson: it is a day on the mountain with a <strong>ski guide</strong> who takes you to the best of the resort according to your level, the snow and the time of day. The price is per person, and the bigger the group, the less each one pays.',
      sizeLabel: 'How many of you?',
      people: ['1', '2', '3', '4', '5', '6 or more'],
      peopleAria: 'people',
      perPerson: 'per person / day',
      groupTotal: 'Group total',
      saving: 'per person vs. going alone',
      durations: { half: 'Half Day · 3 hrs', full: 'Full Day · 6 hrs' },
      cards: [
        {
          key: 'onpiste',
          tag: 'Ski guide',
          title: 'Guided On-Piste',
          text: 'You ride the mountain with your guide: the best runs for the snow and the hour, without wasting the day figuring out where to go.',
          bullets: ['Expert guide all day long', 'Route built around your level', 'Ideal to get to know the resort']
        },
        {
          key: 'offpiste',
          tag: 'Freeride · Backcountry guide',
          title: 'Guided Off-Piste',
          text: 'Untracked snow outside the marked area, with a freeride guide and full safety equipment included in the outing.',
          bullets: ['Transceiver, shovel and probe included', 'Snow and terrain assessment', 'Intermediate-advanced level required']
        }
      ],
      cta: 'Check availability',
      note: 'Prices are per person and per day. Off-piste guiding is confirmed based on snow conditions and terrain safety.',
      currencyLabel: 'Show prices in'
    },
    pt: {
      aria: 'Guiados na pista e fora de pista',
      kicker: 'Nova modalidade · Guiados',
      title: 'Guiados com <em>guia experto</em>: na pista e fora de pista',
      lead: 'Não é uma aula: é um dia de montanha com um <strong>guia de ski</strong> que leva você ao melhor da estação conforme seu nível, a neve e o horário. O valor é por pessoa, e quanto maior o grupo, menos cada um paga.',
      sizeLabel: 'Quantos vão?',
      people: ['1', '2', '3', '4', '5', '6 ou mais'],
      peopleAria: 'pessoas',
      perPerson: 'por pessoa / dia',
      groupTotal: 'Total do grupo',
      saving: 'por pessoa vs. sozinho',
      durations: { half: 'Half Day · 3 hrs', full: 'Full Day · 6 hrs' },
      cards: [
        {
          key: 'onpiste',
          tag: 'Guia de Ski',
          title: 'Guiado Na Pista',
          text: 'Você percorre a montanha com seu guia: as melhores pistas conforme a neve e o horário, sem perder o dia procurando para onde ir.',
          bullets: ['Guia experto o dia todo', 'Roteiro montado no seu nível', 'Ideal para conhecer a estação']
        },
        {
          key: 'offpiste',
          tag: 'Guia de Freeride · Backcountry',
          title: 'Guiado Fora de Pista',
          text: 'Neve virgem fora da área demarcada, com guia de freeride e equipamento de segurança completo incluído na saída.',
          bullets: ['ARVA, pá e sonda incluídos', 'Avaliação de neve e terreno', 'Exige nível intermediário-avançado']
        }
      ],
      cta: 'Consultar disponibilidade',
      note: 'Valores por pessoa e por dia. O guiado fora de pista é confirmado conforme as condições de neve e a segurança do terreno.',
      currencyLabel: 'Ver preços em'
    }
  };

  function setupGuides() {
    var T = GUIDES_COPY[currentLang()];
    var people = 1;
    var moneda = 'CLP';
    var sec = null;

    function monedaDelSitio() {
      var q = window.__cdskiQuote;
      return q && q.currency === 'USD' ? 'USD' : 'CLP';
    }

    function pintar() {
      if (!sec || !document.contains(sec)) return;

      sec.querySelectorAll('[data-precio]').forEach(function (el) {
        var ref = el.getAttribute('data-precio').split('.');
        el.textContent = guidesPrice(GUIDES_USD[ref[0]][ref[1]], people, moneda);
      });

      sec.querySelectorAll('[data-total]').forEach(function (el) {
        var ref = el.getAttribute('data-total').split('.');
        var totalUsd = GUIDES_USD[ref[0]][ref[1]] * guidesFactor(people) * people;
        el.textContent = T.groupTotal + ': ' + (moneda === 'USD'
          ? 'US$' + Math.round(totalUsd).toLocaleString('en-US')
          : '$' + (Math.round(totalUsd * GUIDES_RATE / 1000) * 1000).toLocaleString('es-CL'));
        el.hidden = people === 1;
      });

      var ahorro = Math.round((1 - guidesFactor(people)) * 100);
      sec.querySelectorAll('[data-ahorro]').forEach(function (el) {
        el.textContent = '−' + ahorro + '% ' + T.saving;
        el.hidden = ahorro === 0;
      });

      sec.querySelectorAll('.cdski-guides-size button').forEach(function (b, i) {
        var activo = i + 1 === people;
        b.classList.toggle('cdski-on', activo);
        b.setAttribute('aria-pressed', activo ? 'true' : 'false');
      });

      sec.querySelectorAll('.cdski-guides-cur button').forEach(function (b) {
        var activo = b.getAttribute('data-cur') === moneda;
        b.classList.toggle('cdski-on', activo);
        b.setAttribute('aria-pressed', activo ? 'true' : 'false');
      });
    }

    function sizeHtml() {
      var html = '';
      T.people.forEach(function (p, i) {
        html += '<button type="button" data-n="' + (i + 1) + '" aria-pressed="false" '
          + 'aria-label="' + p + ' ' + T.peopleAria + '">' + p + '</button>';
      });
      return html;
    }

    function cardHtml(c) {
      var filas = '';
      ['half', 'full'].forEach(function (d) {
        filas += '<div class="cdski-guides-row">'
          + '<span class="cdski-guides-dur">' + T.durations[d] + '</span>'
          + '<span class="cdski-guides-amount">'
          +   '<b data-precio="' + c.key + '.' + d + '">—</b>'
          +   '<i>' + T.perPerson + '</i>'
          + '</span>'
          + '<span class="cdski-guides-total" data-total="' + c.key + '.' + d + '" hidden></span>'
          + '</div>';
      });
      var bullets = '';
      c.bullets.forEach(function (b) {
        bullets += '<li>' + CHECK_ICON + '<span>' + b + '</span></li>';
      });
      return '<article class="cdski-guides-card cdski-guides-' + c.key + '">'
        + '<span class="cdski-guides-tag">' + c.tag + '</span>'
        + '<h3>' + c.title + '</h3>'
        + '<p>' + c.text + '</p>'
        + '<div class="cdski-guides-rows">' + filas + '</div>'
        + '<span class="cdski-guides-saving" data-ahorro hidden></span>'
        + '<ul class="cdski-guides-bullets">' + bullets + '</ul>'
        + '<a class="cdski-guides-cta" href="#contact">' + T.cta + '</a>'
        + '</article>';
    }

    function insertSection() {
      var existing = document.querySelector('.cdski-guides');
      if (existing && document.contains(existing)) { sec = existing; return true; }

      var pricing = document.getElementById('pricing');
      if (!pricing || !pricing.parentNode) return false;

      var el = document.createElement('section');
      el.className = 'cdski-guides';
      el.id = 'guiados';
      el.setAttribute('aria-label', T.aria);
      el.innerHTML =
        '<div class="cdski-guides-inner">'
        +   '<div class="cdski-guides-head">'
        +     '<span class="cdski-guides-kicker"><span class="cdski-guided-dot"></span>' + T.kicker + '</span>'
        +     '<h2 class="cdski-guides-title">' + T.title + '</h2>'
        +     '<p class="cdski-guides-lead">' + T.lead + '</p>'
        +   '</div>'
        +   '<div class="cdski-guides-controls">'
        +     '<div class="cdski-guides-control">'
        +       '<span class="cdski-guides-control-label">' + T.sizeLabel + '</span>'
        +       '<div class="cdski-guides-size" role="group">' + sizeHtml() + '</div>'
        +     '</div>'
        +     '<div class="cdski-guides-control">'
        +       '<span class="cdski-guides-control-label">' + T.currencyLabel + '</span>'
        +       '<div class="cdski-guides-cur" role="group">'
        +         '<button type="button" data-cur="CLP" aria-pressed="false">CLP</button>'
        +         '<button type="button" data-cur="USD" aria-pressed="false">USD</button>'
        +       '</div>'
        +     '</div>'
        +   '</div>'
        +   '<div class="cdski-guides-cards">' + cardHtml(T.cards[0]) + cardHtml(T.cards[1]) + '</div>'
        +   '<p class="cdski-guides-note">' + T.note + '</p>'
        + '</div>';

      el.addEventListener('click', function (ev) {
        var btn = ev.target.closest ? ev.target.closest('button[data-n], button[data-cur]') : null;
        if (!btn) return;
        if (btn.hasAttribute('data-n')) {
          people = parseInt(btn.getAttribute('data-n'), 10) || 1;
        } else {
          moneda = btn.getAttribute('data-cur');
          // Si la calculadora está montada, mandamos la moneda al sitio entero.
          var q = window.__cdskiQuote;
          if (q && typeof q.setCurrency === 'function') q.setCurrency(moneda);
        }
        pintar();
      });

      pricing.parentNode.insertBefore(el, pricing.nextSibling);
      sec = el;
      moneda = monedaDelSitio();
      pintar();
      return true;
    }

    window.addEventListener('cdski:moneda', function (ev) {
      var nueva = ev.detail === 'USD' ? 'USD' : 'CLP';
      if (nueva === moneda) return;
      moneda = nueva;
      pintar();
    });

    keepAlive(insertSection);
  }

  /* =========================================================
     Botones flotantes: apilado según la barra inferior
     La barra "Reservar Clase" sólo aparece al desplazarse, así que su alto
     se mide en vivo y los flotantes se colocan encima. Sin barra, vuelven
     a su posición de siempre.
     ========================================================= */
  function setupFloatingStack() {
    var raiz = document.documentElement;

    // La barra se busca recorriendo el árbol, que es caro: se cachea la
    // referencia y en cada scroll sólo se mide ese nodo.
    var barra = null;

    function buscarBarra() {
      var vh = window.innerHeight, vw = window.innerWidth;
      var nodos = document.querySelectorAll('body *');
      for (var i = 0; i < nodos.length; i++) {
        var el = nodos[i];
        var cs = window.getComputedStyle(el);
        if (cs.position !== 'fixed') continue;
        var r = el.getBoundingClientRect();
        if (r.width >= vw * 0.85 && r.height > 36 && r.height < 140 && Math.abs(r.bottom - vh) < 4) {
          return el;
        }
      }
      return null;
    }

    function altoBarra() {
      if (!barra || !document.body.contains(barra)) barra = buscarBarra();
      if (!barra) return 0;
      var cs = window.getComputedStyle(barra);
      if (cs.display === 'none' || cs.visibility === 'hidden' || Number(cs.opacity) === 0) return 0;
      var r = barra.getBoundingClientRect();
      // Sólo cuenta mientras esté pegada al borde inferior
      if (Math.abs(r.bottom - window.innerHeight) > 4) return 0;
      return Math.round(r.height);
    }

    var ultimo = -1;
    function aplicar() {
      var a = altoBarra();
      if (a === ultimo) return;
      ultimo = a;
      raiz.style.setProperty('--cdski-bottom-bar', a + 'px');
    }

    // El botón de WhatsApp se solapaba con el CTA del hero en pantallas
    // bajas. Se muestra apenas se empieza a bajar, igual que el de volver
    // arriba, para no tapar el botón principal.
    var fab = null;
    function ensureFab() {
      if (fab && document.body.contains(fab)) return;
      fab = document.querySelector('div[class~="fixed"][class~="bottom-6"][class~="right-6"]');
      if (fab) fab.classList.add('cdski-fab-auto');
    }
    function ajustarFab() {
      ensureFab();
      if (!fab) return;
      fab.classList.toggle('cdski-fab-oculto', window.scrollY < 160);
    }

    var pendiente = false;
    function pedir() {
      if (pendiente) return;
      pendiente = true;
      window.requestAnimationFrame(function () { pendiente = false; aplicar(); ajustarFab(); });
    }

    window.addEventListener('scroll', pedir, { passive: true });
    window.addEventListener('resize', pedir);
    keepAlive(function () { aplicar(); ajustarFab(); });
  }

  ready(function () {
    document.documentElement.classList.add('cdski-reveal-ready');
    revealInlineHidden();
    setupHeader();
    setupMobileMenu();
    setupScrollToTop();
    setupSmoothAnchors();
    setupBookingExtras();
    setupLazyImages();
    setupWhatsAppFab();
    setupFooter();
    setupWelcomeBanner();
    setupSeptember();
    setupHuaso();
    setupGuides();
    setupMissionSection();
    setupGuidedExperience();
    setupCareersSection();
    setupLiveMotion();
    setupPaymentPaths();
    setupReservationModal();
    setupLeadSafety();
    setupPageOrder();
    setupBlogDigest();
    setupHeroPrice();
    setupCtaConsistency();
    setupResponseExpectation();
    setupTrustBar();
    setupSocialProof();
    setupPartnerAlignment();
    setupCompanions();
    setupFloatingStack();
  });
})();
