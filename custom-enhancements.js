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

  ready(function () {
    document.documentElement.classList.add('cdski-reveal-ready');
    revealInlineHidden();
    setupHeader();
    setupMobileMenu();
    setupScrollToTop();
    setupSmoothAnchors();
  });
})();
