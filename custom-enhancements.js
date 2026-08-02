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
  });
})();
