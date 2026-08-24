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
        hint: 'Necesitamos estos datos para revisar disponibilidad. Todos los campos son obligatorios.',
        required: 'Obligatorio',
        errorMsg: 'Por favor completa todos los campos antes de enviar por WhatsApp.',
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
        hint: 'We need these to check availability. All fields are required.',
        required: 'Required',
        errorMsg: 'Please complete all fields before sending via WhatsApp.',
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
        hint: 'Precisamos destes dados para verificar disponibilidade. Todos os campos são obrigatórios.',
        required: 'Obrigatório',
        errorMsg: 'Por favor preencha todos os campos antes de enviar por WhatsApp.',
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
      var star = document.createElement('span');
      star.className = 'cdski-extras-required';
      star.textContent = ' *';
      star.setAttribute('aria-label', t.required);
      span.appendChild(star);
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
      lSel.required = true;
      t.levelOpts.forEach(function (o, i) { lSel.appendChild(buildOption(o, o, i === 0)); });
      if (saved.level) lSel.value = saved.level;
      grid.appendChild(buildField('⛷️', t.level, lSel, '', 'level'));

      var loSel = document.createElement('select');
      loSel.name = 'lodging';
      loSel.required = true;
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
    setupMissionSection();
    setupGuidedExperience();
    setupCareersSection();
    setupLiveMotion();
    setupPaymentPaths();
    setupReservationModal();
    setupLeadSafety();
  });
})();
