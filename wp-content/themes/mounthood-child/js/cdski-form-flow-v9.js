/**
 * CDSKI Form Flow - Calculator to Booking Summary
 *
 * Captures page 1 data (persons, modality, price) from the Formidable Forms
 * multi-page calculator form (form_id=2, key=calculadora1) and displays
 * a styled summary card when page 2 (Agendar Clases) loads via AJAX.
 * Also hides the Quantity and duplicate Product fields on page 2.
 *
 * Features:
 *   - "Cotizador Online" title injected above the form
 *   - Prices displayed in CLP (with dots) and USD (using dolar observado)
 *   - Calendar datepicker month/year visibility fix
 *   - Updated "Tomo conocimiento de" disclaimer text
 *
 * Page 2 field IDs (verified from live site):
 *   item_meta[24] = Fecha (date picker)
 *   item_meta[27] = Tomo conocimiento de (checkboxes)
 *   item_meta[32] = Nombre
 *   item_meta[33] = Apellido
 *   item_meta[36] = Email
 *   item_meta[37] = Telefono
 *   item_meta[60] = Quantity   (WooCommerce - HIDE)
 *   item_meta[61] = Product    (WooCommerce - HIDE)
 *   item_meta[62] = Product    (WooCommerce - HIDE)
 *
 * Important: Formidable replaces the entire fieldset content via AJAX
 * when changing pages, so we must store data BEFORE the page swap.
 */
(function($) {
    'use strict';

    var FORM_KEY       = 'calculadora1';
    var FORM_ID        = '2';
    var storedData     = null;
    var summaryInjected = false;
    var titleInjected   = false;
    var dolarObservado  = null;
    // (_formattingPrices and _formatPriceTimer removed – no longer needed
    //  with setInterval-based scheduling; JS is single-threaded)

    /* ── fetch dolar observado from mindicador.cl ──────────── */

    function fetchDolarObservado() {
        $.ajax({
            url: 'https://mindicador.cl/api/dolar',
            dataType: 'json',
            timeout: 8000,
            success: function(resp) {
                if (resp && resp.serie && resp.serie.length > 0) {
                    dolarObservado = resp.serie[0].valor;
                    updatePriceDisplays();
                }
            },
            error: function() {
                $.ajax({
                    url: 'https://mindicador.cl/api',
                    dataType: 'json',
                    timeout: 8000,
                    success: function(resp) {
                        if (resp && resp.dolar && resp.dolar.valor) {
                            dolarObservado = resp.dolar.valor;
                            updatePriceDisplays();
                        }
                    }
                });
            }
        });
    }

    /* ── helpers ───────────────────────────────────────────── */

    /**
     * Parse a CLP value that may use Chilean locale formatting.
     * Formidable Forms outputs values like "266.913" where "." is the
     * thousands separator (meaning 266,913 CLP).  We detect this pattern
     * (digits separated by dots where every group after the first has
     * exactly 3 digits) and strip the dots so JS reads the full integer.
     */
    function parseCLPValue(val) {
        if (val === null || val === undefined || val === '') return 0;
        var s = String(val).trim();
        // Pattern: one or more groups of exactly 3 digits after dots  →  thousands sep
        // e.g. "266.913"  →  266913  |  "1.266.913"  →  1266913
        if (/^\d{1,3}(\.\d{3})+$/.test(s)) {
            return parseInt(s.replace(/\./g, ''), 10) || 0;
        }
        // Also handle comma as decimal separator (Chilean: "266.913,50")
        if (/^\d{1,3}(\.\d{3})+(,\d+)?$/.test(s)) {
            return parseInt(s.replace(/\./g, '').replace(/,.*$/, ''), 10) || 0;
        }
        // Fallback: standard parseFloat for normal numeric strings
        var num = parseFloat(s);
        return isNaN(num) ? 0 : Math.round(num);
    }

    function formatCLP(val) {
        var num = parseCLPValue(val);
        if (!num) return 'CLP $0';
        return 'CLP $' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatUSD(clpVal) {
        var num = parseCLPValue(clpVal);
        if (!num || !dolarObservado) return '';
        var usd = num / dolarObservado;
        return 'USD $' + usd.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function formatPriceBlock(val) {
        var clp = formatCLP(val);
        var usd = formatUSD(val);
        if (usd) {
            return clp + ' <span class="cdski-usd-price">(' + usd + ')</span>';
        }
        return clp;
    }

    var personasMap = { 'Dos': '2', 'Tres': '3', 'Cuatro': '4', 'Cinco': '5' };
    function personasToNum(txt) { return personasMap[txt] || txt; }

    /* ── inject "Cotizador Online" title above the form ──── */

    function injectCotizadorTitle() {
        if (titleInjected) return;
        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return;

        if (document.getElementById('cdski-cotizador-title')) {
            titleInjected = true;
            return;
        }

        var titleHTML = '<div id="cdski-cotizador-title" style="text-align:center;margin-bottom:20px;">' +
            '<h2 style="color:#f7941d;font-size:28px;font-weight:800;margin:0 0 5px;letter-spacing:0.5px;text-transform:uppercase;">' +
            'Cotizador Online' +
            '</h2>' +
            '<div style="width:60px;height:3px;background:linear-gradient(135deg,#f7941d 0%,#f15a22 100%);margin:0 auto;border-radius:2px;"></div>' +
            '</div>';

        form.insertAdjacentHTML('beforebegin', titleHTML);
        titleInjected = true;
    }

    /* ── capture page-1 values BEFORE Formidable replaces them ── */

    function capturePageOneData() {
        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return null;

        var personas = '';
        form.querySelectorAll('input[name="item_meta[17]"]').forEach(function(r) {
            if (r.checked) personas = r.value;
        });

        var plan = '';
        form.querySelectorAll('input[name="item_meta[16]"]').forEach(function(r) {
            if (r.checked) plan = r.value;
        });

        // Find the currently VISIBLE price fields dynamically.
        // Formidable uses different field IDs per persona/plan combination.
        var precio = '';
        var precioDescuento = '';
        var allContainers = form.querySelectorAll('.frm_form_field');
        allContainers.forEach(function(c) {
            var lbl = c.querySelector('.frm_primary_label');
            if (!lbl) return;
            var isVisible = c.offsetParent !== null && c.style.display !== 'none';
            if (!isVisible) return;
            var inp = c.querySelector('input[type="number"], input[type="text"][name^="item_meta"]');
            if (!inp || !inp.value) return;
            var lt = lbl.textContent.trim();
            if (lt === 'Precio') precio = inp.value;
            else if (lt === 'Precio con Descuento') precioDescuento = inp.value;
        });

        var data = {
            personas:        personas,
            plan:            plan,
            precio:          precio,
            precioDescuento: precioDescuento
        };

        if (data.personas && data.plan) {
            storedData = data;
            try { sessionStorage.setItem('cdski_calc_data', JSON.stringify(data)); } catch (e) {}
        }
        return data;
    }

    /* ── build the summary card HTML ───────────────────────── */

    function buildSummaryHTML(d) {
        return '<div id="cdski-booking-summary" class="frm_form_field">' +
            '<div class="cdski-summary-header">' +
                '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>' +
                '<span>Resumen de tu Clase</span>' +
            '</div>' +
            '<div class="cdski-summary-body">' +
                '<div class="cdski-summary-row">' +
                    '<div class="cdski-summary-label">Personas</div>' +
                    '<div class="cdski-summary-value">' + personasToNum(d.personas) + ' (' + d.personas + ')</div>' +
                '</div>' +
                '<div class="cdski-summary-row">' +
                    '<div class="cdski-summary-label">Modalidad</div>' +
                    '<div class="cdski-summary-value">' + d.plan + '</div>' +
                '</div>' +
                '<div class="cdski-summary-divider"></div>' +
                '<div class="cdski-summary-row cdski-summary-price">' +
                    '<div class="cdski-summary-label">Precio Regular</div>' +
                    '<div class="cdski-summary-value cdski-price-strike">' + formatPriceBlock(d.precio) + '</div>' +
                '</div>' +
                '<div class="cdski-summary-row cdski-summary-total">' +
                    '<div class="cdski-summary-label">Precio con Descuento</div>' +
                    '<div class="cdski-summary-value cdski-price-final">' + formatPriceBlock(d.precioDescuento) + '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    }

    /* ── hide Quantity and Product fields on page 2 ────────── */

    function hideQuantityAndProductFields() {
        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return;

        // Field selectors for Quantity (60), Product (61), Product (62)
        var fieldsToHide = [
            'input[name="item_meta[60]"]',
            'select[name="item_meta[61]"]',
            'select[name="item_meta[62]"]'
        ];

        fieldsToHide.forEach(function(selector) {
            var el = form.querySelector(selector);
            if (el) {
                // Walk up to the .frm_form_field container and hide it
                var container = el.closest('.frm_form_field');
                if (container) {
                    container.style.display = 'none';
                }
            }
        });
    }

    /* ── format the raw Precio / Precio con Descuento fields on page 1 ── */

    function formatRawPriceFields() {
        try { _formatRawPriceFieldsInner(); } catch(e) { console.error('cdski formatRawPriceFields error:', e); }
    }

    function _formatRawPriceFieldsInner() {
        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return;

        // Dynamically find ALL price field containers by their label text.
        // Formidable uses different field IDs per persona/plan combination
        // (e.g. item_meta[19], item_meta[28], item_meta[31], etc.)
        // so we cannot hardcode selectors. Instead, find fields by label.
        var allContainers = form.querySelectorAll('.frm_form_field');

        allContainers.forEach(function(container) {
            var label = container.querySelector('.frm_primary_label');
            if (!label) return;

            var labelText = label.textContent.trim();
            if (labelText !== 'Precio' && labelText !== 'Precio con Descuento') return;

            var input = container.querySelector('input[type="number"], input[type="text"][name^="item_meta"]');
            if (!input) return;

            // Only process visible fields (Formidable hides inactive combos)
            var isVisible = container.offsetParent !== null && container.style.display !== 'none';
            if (!isVisible) {
                // Clean up any stale overlay on hidden fields
                var staleOverlay = container.querySelector('.cdski-price-overlay');
                if (staleOverlay) staleOverlay.remove();
                return;
            }

            // Parse the raw value
            var rawVal = input.value;
            if (!rawVal) return;

            // Normalize: Formidable may output "392,275" or "392.275" depending on locale
            var normalized = rawVal;
            if (/^\d{1,3}(,\d{3})+$/.test(normalized)) {
                normalized = normalized.replace(/,/g, '');
            } else {
                normalized = String(parseCLPValue(rawVal));
            }

            var clpFormatted = formatCLP(normalized);
            var usdFormatted = formatUSD(normalized);

            // Check if overlay already exists in this container, update it
            var existing = container.querySelector('.cdski-price-overlay');
            if (existing) {
                var clpEl = existing.querySelector('.cdski-raw-clp');
                var usdEl = existing.querySelector('.cdski-raw-usd');
                if (clpEl) clpEl.textContent = clpFormatted;
                if (usdEl) usdEl.textContent = usdFormatted ? '(' + usdFormatted + ')' : '';
                return;
            }

            // Create overlay div
            var overlay = document.createElement('div');
            overlay.className = 'cdski-price-overlay';
            overlay.style.cssText = 'margin-top:4px;font-size:16px;font-weight:700;color:#1a2332;line-height:1.4;';
            overlay.innerHTML = '<span class="cdski-raw-clp" style="font-size:18px;">' + clpFormatted + '</span>' +
                (usdFormatted ? ' <span class="cdski-raw-usd" style="font-size:14px;color:#f7941d;font-weight:600;">(' + usdFormatted + ')</span>' : '<span class="cdski-raw-usd" style="font-size:14px;color:#f7941d;font-weight:600;"></span>');

            // Hide the raw input text visually but keep it fully "visible" to
            // Formidable's conditional-logic checks (jQuery :visible needs
            // offsetHeight > 0).  We make the text transparent instead of
            // collapsing the element so the value is still submitted.
            input.style.cssText = 'color:transparent !important;-webkit-text-fill-color:transparent !important;position:relative !important;z-index:0 !important;pointer-events:none !important;';

            // Also hide the "AGENDAR CLASE" button next to the raw input
            var agendarBtn = container.querySelector('button[type="submit"]');
            if (agendarBtn) {
                agendarBtn.style.display = 'none';
            }

            // Insert overlay after the input
            input.parentNode.insertBefore(overlay, input.nextSibling);
        });
    }

    /* ── update price displays when dolar arrives ──────────── */

    function updatePriceDisplays() {
        var summary = document.getElementById('cdski-booking-summary');
        if (summary && storedData) {
            var strikeEl = summary.querySelector('.cdski-price-strike');
            var finalEl  = summary.querySelector('.cdski-price-final');
            if (strikeEl) strikeEl.innerHTML = formatPriceBlock(storedData.precio);
            if (finalEl)  finalEl.innerHTML  = formatPriceBlock(storedData.precioDescuento);
        }
        // Also update raw price field overlays
        formatRawPriceFields();
    }

    /* ── update "Tomo conocimiento de" disclaimer text ────── */

    function updateTomoConocimientoText() {
        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return;

        var field = form.querySelector('[name="item_meta[27][]"]') ||
                    form.querySelector('[name="item_meta[27]"]');
        if (!field) return;

        var container = field.closest('.frm_form_field');
        if (!container) return;

        if (container.querySelector('.cdski-disclaimer-notice')) return;

        var disclaimerHTML = '<div class="cdski-disclaimer-notice" style="' +
            'background:linear-gradient(135deg,#fff8e1 0%,#fff3cd 100%);' +
            'border:1px solid #f7941d;' +
            'border-radius:10px;' +
            'padding:14px 18px;' +
            'margin-top:12px;' +
            'font-size:13px;' +
            'line-height:1.6;' +
            'color:#5a4a00;' +
            '">' +
            '<p style="margin:0 0 8px;font-weight:700;color:#d4760a;font-size:14px;">Importante:</p>' +
            '<ul style="margin:0;padding-left:18px;">' +
            '<li style="margin-bottom:4px;">Esto es <strong>solo una cotizacion</strong> y no constituye una reserva confirmada.</li>' +
            '<li style="margin-bottom:4px;">Para agendar su clase, se debe <strong>abonar el 30%</strong> del valor total para generar la reserva/booking.</li>' +
            '<li><strong>Sujeto a disponibilidad</strong>, cupos limitados.</li>' +
            '</ul>' +
            '</div>';

        container.insertAdjacentHTML('beforeend', disclaimerHTML);
    }

    /* ── fix datepicker calendar month/year visibility ─────── */

    function fixDatepickerVisibility() {
        if (document.getElementById('cdski-datepicker-fix')) return;

        var css = document.createElement('style');
        css.id = 'cdski-datepicker-fix';
        css.textContent =
            '.ui-datepicker .ui-datepicker-header {' +
                'background: #1a2332 !important;' +
                'color: #fff !important;' +
                'padding: 8px !important;' +
                'border: none !important;' +
                'border-radius: 8px 8px 0 0 !important;' +
            '}' +
            '.ui-datepicker .ui-datepicker-title {' +
                'color: #fff !important;' +
                'font-weight: 700 !important;' +
                'font-size: 15px !important;' +
            '}' +
            '.ui-datepicker .ui-datepicker-title select,' +
            '.ui-datepicker .ui-datepicker-title .ui-datepicker-month,' +
            '.ui-datepicker .ui-datepicker-title .ui-datepicker-year {' +
                'color: #fff !important;' +
                'background: #2d3e50 !important;' +
                'border: 1px solid rgba(255,255,255,0.2) !important;' +
                'border-radius: 4px !important;' +
                'padding: 4px 8px !important;' +
                'font-size: 14px !important;' +
                'font-weight: 600 !important;' +
                '-webkit-appearance: auto !important;' +
                'appearance: auto !important;' +
                'cursor: pointer !important;' +
            '}' +
            '.ui-datepicker .ui-datepicker-title select option {' +
                'color: #1a2332 !important;' +
                'background: #fff !important;' +
            '}' +
            '.ui-datepicker .ui-datepicker-prev,' +
            '.ui-datepicker .ui-datepicker-next {' +
                'color: #fff !important;' +
                'cursor: pointer !important;' +
                'top: 8px !important;' +
            '}' +
            '.ui-datepicker .ui-datepicker-prev span,' +
            '.ui-datepicker .ui-datepicker-next span {' +
                'color: #fff !important;' +
            '}' +
            '.ui-datepicker .ui-datepicker-prev:hover,' +
            '.ui-datepicker .ui-datepicker-next:hover {' +
                'background: rgba(255,255,255,0.1) !important;' +
                'border-radius: 4px !important;' +
            '}' +
            '.ui-datepicker {' +
                'z-index: 99999 !important;' +
                'background: #fff !important;' +
                'border-radius: 8px !important;' +
                'box-shadow: 0 8px 32px rgba(0,0,0,0.15) !important;' +
                'border: none !important;' +
                'font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;' +
            '}' +
            '.ui-datepicker table {' +
                'font-size: 14px !important;' +
            '}' +
            '.ui-datepicker th {' +
                'color: #94a3b8 !important;' +
                'font-weight: 600 !important;' +
                'padding: 6px !important;' +
            '}' +
            '.ui-datepicker td a,' +
            '.ui-datepicker td span {' +
                'text-align: center !important;' +
                'padding: 6px !important;' +
                'border-radius: 4px !important;' +
            '}' +
            '.ui-datepicker td a:hover {' +
                'background: #f7941d !important;' +
                'color: #fff !important;' +
            '}' +
            '.ui-datepicker .ui-state-active {' +
                'background: #f7941d !important;' +
                'color: #fff !important;' +
            '}' +
            '.flatpickr-calendar {' +
                'z-index: 99999 !important;' +
            '}' +
            '.flatpickr-months .flatpickr-month,' +
            '.flatpickr-current-month {' +
                'color: #fff !important;' +
                'fill: #fff !important;' +
                'background: #1a2332 !important;' +
            '}' +
            '.flatpickr-current-month .flatpickr-monthDropdown-months,' +
            '.flatpickr-current-month input.cur-year {' +
                'color: #fff !important;' +
                'background: #2d3e50 !important;' +
                'border: 1px solid rgba(255,255,255,0.2) !important;' +
                'border-radius: 4px !important;' +
                'font-weight: 600 !important;' +
                '-webkit-appearance: auto !important;' +
                'appearance: auto !important;' +
            '}' +
            '.flatpickr-current-month .flatpickr-monthDropdown-months option {' +
                'color: #1a2332 !important;' +
                'background: #fff !important;' +
            '}' +
            '.flatpickr-months .flatpickr-prev-month,' +
            '.flatpickr-months .flatpickr-next-month {' +
                'color: #fff !important;' +
                'fill: #fff !important;' +
            '}' +
            '.flatpickr-months .flatpickr-prev-month svg,' +
            '.flatpickr-months .flatpickr-next-month svg {' +
                'fill: #fff !important;' +
            '}' +
            '#ui-datepicker-div .ui-datepicker-header {' +
                'background: #1a2332 !important;' +
                'color: #fff !important;' +
            '}' +
            '#ui-datepicker-div .ui-datepicker-title select {' +
                'color: #fff !important;' +
                'background: #2d3e50 !important;' +
                'border: 1px solid rgba(255,255,255,0.3) !important;' +
                'padding: 4px 8px !important;' +
                'border-radius: 4px !important;' +
                'font-size: 14px !important;' +
                '-webkit-appearance: auto !important;' +
                'appearance: auto !important;' +
            '}' +
            '#ui-datepicker-div {' +
                'z-index: 99999 !important;' +
            '}';

        document.head.appendChild(css);
    }

    /* ── enhance form visuals: invitation text, tooltips, styling ── */

    var _formVisualsInjected = false;

    function enhanceFormVisuals() {
        if (_formVisualsInjected) return;
        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return;

        var personasContainer = document.getElementById('frm_field_17_container');
        var planContainer     = document.getElementById('frm_field_16_container');
        if (!personasContainer || !planContainer) return;

        _formVisualsInjected = true;

        /* ---- 1. Inject CSS for visual enhancements ---- */
        if (!document.getElementById('cdski-form-enhance-css')) {
            var style = document.createElement('style');
            style.id = 'cdski-form-enhance-css';
            style.textContent =
                /* Section labels */
                '#frm_field_17_container .frm_primary_label,' +
                '#frm_field_16_container .frm_primary_label {' +
                    'font-size: 17px !important;' +
                    'font-weight: 800 !important;' +
                    'color: #1a2332 !important;' +
                    'letter-spacing: 0.3px !important;' +
                    'margin-bottom: 2px !important;' +
                '}' +
                /* Invitation subtitle */
                '.cdski-invite-subtitle {' +
                    'font-size: 13px;' +
                    'color: #6b7a8d;' +
                    'font-weight: 500;' +
                    'margin: 0 0 12px;' +
                    'display: flex;' +
                    'align-items: center;' +
                    'gap: 6px;' +
                '}' +
                '.cdski-invite-subtitle svg {' +
                    'flex-shrink: 0;' +
                '}' +
                /* "Por grupo familiar" badge */
                '#frm_field_17_container .frm_description {' +
                    'display: inline-block !important;' +
                    'background: linear-gradient(135deg, #e8f4fd 0%, #dbeafe 100%) !important;' +
                    'color: #1e40af !important;' +
                    'font-size: 12px !important;' +
                    'font-weight: 600 !important;' +
                    'padding: 4px 12px !important;' +
                    'border-radius: 20px !important;' +
                    'margin-top: 8px !important;' +
                    'border: 1px solid #93c5fd !important;' +
                '}' +
                /* Hide static schedule text - replaced by tooltips */
                '#frm_field_16_container .frm_description {' +
                    'display: none !important;' +
                '}' +
                /* Radio card enhancements */
                '#frm_field_17_container .frm_radio label .frm_image_option_container,' +
                '#frm_field_16_container .frm_radio label .frm_image_option_container {' +
                    'transition: transform 0.2s ease, box-shadow 0.2s ease !important;' +
                    'border-radius: 12px !important;' +
                '}' +
                '#frm_field_17_container .frm_radio label .frm_image_option_container:hover,' +
                '#frm_field_16_container .frm_radio label .frm_image_option_container:hover {' +
                    'transform: translateY(-3px) !important;' +
                    'box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;' +
                '}' +
                /* Tooltip styles for modalidad */
                '.cdski-plan-card-wrapper {' +
                    'position: relative;' +
                    'display: inline-block;' +
                '}' +
                '.cdski-schedule-tooltip {' +
                    'position: absolute;' +
                    'bottom: calc(100% + 8px);' +
                    'left: 50%;' +
                    'transform: translateX(-50%);' +
                    'background: #1a2332;' +
                    'color: #fff;' +
                    'padding: 10px 16px;' +
                    'border-radius: 10px;' +
                    'font-size: 13px;' +
                    'font-weight: 500;' +
                    'line-height: 1.5;' +
                    'white-space: nowrap;' +
                    'opacity: 0;' +
                    'visibility: hidden;' +
                    'transition: opacity 0.25s ease, visibility 0.25s ease;' +
                    'z-index: 100;' +
                    'pointer-events: none;' +
                    'box-shadow: 0 6px 20px rgba(0,0,0,0.25);' +
                '}' +
                '.cdski-schedule-tooltip::after {' +
                    'content: "";' +
                    'position: absolute;' +
                    'top: 100%;' +
                    'left: 50%;' +
                    'transform: translateX(-50%);' +
                    'border: 6px solid transparent;' +
                    'border-top-color: #1a2332;' +
                '}' +
                '.cdski-schedule-tooltip .cdski-tt-icon {' +
                    'margin-right: 6px;' +
                '}' +
                '.cdski-plan-card-wrapper:hover .cdski-schedule-tooltip,' +
                '.cdski-plan-card-wrapper:focus-within .cdski-schedule-tooltip {' +
                    'opacity: 1;' +
                    'visibility: visible;' +
                '}' +
                /* Schedule info bar below plan */
                '.cdski-schedule-info-bar {' +
                    'margin-top: 10px;' +
                    'padding: 8px 14px;' +
                    'background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);' +
                    'border: 1px solid #f59e0b;' +
                    'border-radius: 8px;' +
                    'font-size: 13px;' +
                    'color: #92400e;' +
                    'font-weight: 600;' +
                    'text-align: center;' +
                    'transition: all 0.3s ease;' +
                    'line-height: 1.4;' +
                '}' +
                '.cdski-schedule-info-bar .cdski-sib-icon {' +
                    'margin-right: 4px;' +
                '}' +
                /* Selected radio card highlight */
                '#frm_field_17_container .frm_radio .frm_image_option_container,' +
                '#frm_field_16_container .frm_radio .frm_image_option_container {' +
                    'border: 3px solid transparent !important;' +
                    'opacity: 0.65 !important;' +
                    'filter: grayscale(20%) !important;' +
                '}' +
                '#frm_field_17_container .frm_radio .frm_image_option_container.cdski-radio-selected,' +
                '#frm_field_16_container .frm_radio .frm_image_option_container.cdski-radio-selected {' +
                    'border: 3px solid #f7941d !important;' +
                    'box-shadow: 0 0 0 3px rgba(247,148,29,0.25), 0 8px 25px rgba(247,148,29,0.3) !important;' +
                    'transform: scale(1.05) !important;' +
                    'opacity: 1 !important;' +
                    'filter: none !important;' +
                '}' +
                /* Selected card text label bold */
                '#frm_field_17_container .frm_radio .cdski-radio-selected ~ .frm_image_option_text,' +
                '#frm_field_16_container .frm_radio .cdski-radio-selected ~ .frm_image_option_text {' +
                    'font-weight: 700 !important;' +
                    'color: #f7941d !important;' +
                '}' +
                /* ──── Button & Navigation Improvements ──── */
                /* Step navigation tabs (rootline) */
                '#form_calculadora1 .frm_rootline_group {' +
                    'display: flex !important;' +
                    'justify-content: center !important;' +
                    'gap: 12px !important;' +
                    'margin-bottom: 20px !important;' +
                    'padding: 10px 0 !important;' +
                '}' +
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single {' +
                    'flex: none !important;' +
                '}' +
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single input[type="button"] {' +
                    'background: linear-gradient(135deg, #e0e5ec 0%, #cfd8e3 100%) !important;' +
                    'color: #4a5568 !important;' +
                    'border: 2px solid #cbd5e0 !important;' +
                    'border-radius: 50% !important;' +
                    'width: 44px !important;' +
                    'height: 44px !important;' +
                    'font-size: 16px !important;' +
                    'font-weight: 700 !important;' +
                    'cursor: pointer !important;' +
                    'transition: all 0.3s ease !important;' +
                    'box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;' +
                    'line-height: 1 !important;' +
                    'padding: 0 !important;' +
                '}' +
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single.frm_current_page input[type="button"],' +
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single input[type="button"][disabled] {' +
                    'background: linear-gradient(135deg, #f7941d 0%, #f15a22 100%) !important;' +
                    'color: #fff !important;' +
                    'border-color: #f7941d !important;' +
                    'box-shadow: 0 4px 15px rgba(247,148,29,0.4) !important;' +
                    'transform: scale(1.1) !important;' +
                '}' +
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single input[type="button"]:hover:not([disabled]) {' +
                    'background: linear-gradient(135deg, #f7941d 0%, #f15a22 100%) !important;' +
                    'color: #fff !important;' +
                    'border-color: #f7941d !important;' +
                    'transform: scale(1.05) !important;' +
                '}' +
                /* Step labels */
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single .frm_rootline_title {' +
                    'font-size: 11px !important;' +
                    'font-weight: 600 !important;' +
                    'color: #4a5568 !important;' +
                    'margin-top: 6px !important;' +
                    'text-align: center !important;' +
                    'max-width: 120px !important;' +
                '}' +
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single.frm_current_page .frm_rootline_title {' +
                    'color: #f7941d !important;' +
                    'font-weight: 700 !important;' +
                '}' +
                /* Progress bar */
                '#form_calculadora1 .frm_rootline_group .frm_percent_complete {' +
                    'display: none !important;' +
                '}' +
                '#form_calculadora1 .frm_page_bar .frm_percent_complete {' +
                    'display: none !important;' +
                '}' +
                /* Connector line between steps */
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single::after {' +
                    'background: #e0e5ec !important;' +
                    'height: 3px !important;' +
                    'top: 22px !important;' +
                '}' +
                '#form_calculadora1 .frm_rootline_group .frm_rootline_single.frm_current_page::after {' +
                    'background: linear-gradient(90deg, #f7941d, #e0e5ec) !important;' +
                '}' +
                /* "Continuar" submit button */
                '#form_calculadora1 button[type="submit"].frm_button_submit,' +
                '#form_calculadora1 .frm_submit button[type="submit"],' +
                '#form_calculadora1 button.frm_next_page,' +
                '#form_calculadora1 .frm_next_page {' +
                    'display: block !important;' +
                    'width: 100% !important;' +
                    'max-width: 320px !important;' +
                    'margin: 25px auto 10px !important;' +
                    'padding: 14px 32px !important;' +
                    'background: linear-gradient(135deg, #f7941d 0%, #f15a22 100%) !important;' +
                    'color: #fff !important;' +
                    'border: none !important;' +
                    'border-radius: 50px !important;' +
                    'font-size: 16px !important;' +
                    'font-weight: 700 !important;' +
                    'letter-spacing: 0.5px !important;' +
                    'text-transform: uppercase !important;' +
                    'cursor: pointer !important;' +
                    'transition: all 0.3s ease !important;' +
                    'box-shadow: 0 4px 15px rgba(247,148,29,0.4) !important;' +
                '}' +
                '#form_calculadora1 button[type="submit"].frm_button_submit:hover,' +
                '#form_calculadora1 .frm_submit button[type="submit"]:hover,' +
                '#form_calculadora1 button.frm_next_page:hover,' +
                '#form_calculadora1 .frm_next_page:hover {' +
                    'background: linear-gradient(135deg, #e8850f 0%, #d94e1a 100%) !important;' +
                    'transform: translateY(-2px) !important;' +
                    'box-shadow: 0 6px 20px rgba(247,148,29,0.5) !important;' +
                '}' +
                /* Submit container alignment */
                '#form_calculadora1 .frm_submit {' +
                    'text-align: center !important;' +
                    'margin-top: 15px !important;' +
                '}' +
                /* "Anterior" (Previous) button on page 2 */
                '#form_calculadora1 .frm_prev_page,' +
                '#form_calculadora1 button.frm_prev_page {' +
                    'display: block !important;' +
                    'width: 100% !important;' +
                    'max-width: 320px !important;' +
                    'margin: 8px auto !important;' +
                    'padding: 12px 28px !important;' +
                    'background: transparent !important;' +
                    'color: #4a5568 !important;' +
                    'border: 2px solid #cbd5e0 !important;' +
                    'border-radius: 50px !important;' +
                    'font-size: 14px !important;' +
                    'font-weight: 600 !important;' +
                    'letter-spacing: 0.3px !important;' +
                    'cursor: pointer !important;' +
                    'transition: all 0.3s ease !important;' +
                '}' +
                '#form_calculadora1 .frm_prev_page:hover,' +
                '#form_calculadora1 button.frm_prev_page:hover {' +
                    'background: #f7f8fa !important;' +
                    'border-color: #f7941d !important;' +
                    'color: #f7941d !important;' +
                '}' +
                /* Smooth transitions for form field changes */
                '#form_calculadora1 .frm_form_field {' +
                    'transition: opacity 0.2s ease !important;' +
                '}' +
                /* Price overlay transitions */
                '.cdski-price-overlay {' +
                    'transition: opacity 0.3s ease !important;' +
                '}' +
                /* Prevent layout jump during radio change */
                '#form_calculadora1 .frm_opt_container {' +
                    'min-height: 120px !important;' +
                '}';
            document.head.appendChild(style);
        }

        /* ---- 2. Add invitation subtitle under personas label ---- */
        var personasLabel = personasContainer.querySelector('.frm_primary_label');
        if (personasLabel && !personasContainer.querySelector('.cdski-invite-subtitle')) {
            var sub1 = document.createElement('p');
            sub1.className = 'cdski-invite-subtitle';
            sub1.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7a8d" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>' +
                'Selecciona la cantidad de personas para tu clase';
            personasLabel.parentNode.insertBefore(sub1, personasLabel.nextSibling);
        }

        /* ---- 3. Add invitation subtitle under plan label ---- */
        var planLabel = planContainer.querySelector('.frm_primary_label');
        if (planLabel && !planContainer.querySelector('.cdski-invite-subtitle')) {
            var sub2 = document.createElement('p');
            sub2.className = 'cdski-invite-subtitle';
            sub2.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7a8d" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' +
                'Elige tu modalidad preferida';
            planLabel.parentNode.insertBefore(sub2, planLabel.nextSibling);
        }

        /* ---- 4. Add tooltips on modalidad cards ---- */
        var scheduleMap = {
            'Half-day':  '11:00 a 14:00 hrs',
            'Full-day':  '11:00 a 14:00 hrs y 15:00 a 16:30 hrs'
        };

        var planRadios = planContainer.querySelectorAll('.frm_radio label');
        planRadios.forEach(function(label) {
            if (label.querySelector('.cdski-schedule-tooltip')) return;
            var imgContainer = label.querySelector('.frm_image_option_container');
            if (!imgContainer) return;

            var labelText = label.textContent.trim();
            var scheduleText = '';
            var planKey = '';
            if (labelText.indexOf('Half-day') > -1) {
                scheduleText = scheduleMap['Half-day'];
                planKey = 'Half-day';
            } else if (labelText.indexOf('Full-day') > -1) {
                scheduleText = scheduleMap['Full-day'];
                planKey = 'Full-day';
            }
            if (!scheduleText) return;

            // Wrap the image container in a wrapper div for tooltip positioning
            var wrapper = document.createElement('div');
            wrapper.className = 'cdski-plan-card-wrapper';
            imgContainer.parentNode.insertBefore(wrapper, imgContainer);
            wrapper.appendChild(imgContainer);

            // Create tooltip
            var tooltip = document.createElement('div');
            tooltip.className = 'cdski-schedule-tooltip';
            tooltip.innerHTML = '<span class="cdski-tt-icon">&#9200;</span> ' +
                '<strong>' + planKey + ':</strong> ' + scheduleText;
            wrapper.appendChild(tooltip);
        });

        /* ---- 5. Add dynamic schedule info bar below plan cards ---- */
        if (!planContainer.querySelector('.cdski-schedule-info-bar')) {
            var infoBar = document.createElement('div');
            infoBar.className = 'cdski-schedule-info-bar';
            infoBar.id = 'cdski-schedule-info-bar';
            infoBar.innerHTML = '<span class="cdski-sib-icon">&#128197;</span> Pasa el cursor sobre cada modalidad para ver los horarios';

            // Insert after the radio options container
            var optContainer = planContainer.querySelector('.frm_opt_container');
            if (optContainer) {
                optContainer.parentNode.insertBefore(infoBar, optContainer.nextSibling);
            }
        }

        /* ---- 6. Listen for plan radio changes to update info bar ---- */
        $(document).on('change', '#frm_field_16_container input[type="radio"]', function() {
            var selectedLabel = this.closest('label') || this.parentNode;
            var labelText = selectedLabel ? selectedLabel.textContent.trim() : '';
            var bar = document.getElementById('cdski-schedule-info-bar');
            if (!bar) return;

            if (labelText.indexOf('Half-day') > -1) {
                bar.innerHTML = '<span class="cdski-sib-icon">&#9728;&#65039;</span> <strong>Half-day:</strong> de 11:00 a 14:00 hrs';
            } else if (labelText.indexOf('Full-day') > -1) {
                bar.innerHTML = '<span class="cdski-sib-icon">&#9728;&#65039;</span> <strong>Full-day:</strong> de 11:00 a 14:00 hrs y de 15:00 a 16:30 hrs';
            }
        });

        // Trigger initial state if a plan is already selected
        var checkedPlan = planContainer.querySelector('input[type="radio"]:checked');
        if (checkedPlan) {
            $(checkedPlan).trigger('change');
        }
    }

    /* ── highlight selected radio cards ─────────────────────── */

    function highlightSelectedRadios() {
        var containers = ['frm_field_17_container', 'frm_field_16_container'];
        containers.forEach(function(containerId) {
            var container = document.getElementById(containerId);
            if (!container) return;

            // Remove selected class from all image option containers
            var allImgContainers = container.querySelectorAll('.frm_image_option_container');
            allImgContainers.forEach(function(el) {
                el.classList.remove('cdski-radio-selected');
            });

            // Add selected class to the checked radio's image container
            var checked = container.querySelector('input[type="radio"]:checked');
            if (checked) {
                var label = checked.closest('label');
                if (label) {
                    var imgContainer = label.querySelector('.frm_image_option_container');
                    if (imgContainer) {
                        imgContainer.classList.add('cdski-radio-selected');
                    }
                }
            }
        });
    }

    /* ── inject the summary card into page 2 ───────────────── */

    function injectSummary() {
        if (summaryInjected) return;

        var data = storedData;
        if (!data) {
            try {
                var raw = sessionStorage.getItem('cdski_calc_data');
                if (raw) data = JSON.parse(raw);
            } catch (e) {}
        }
        if (!data || !data.personas || !data.plan) return;

        // Remove any previously injected summary
        var existing = document.getElementById('cdski-booking-summary');
        if (existing) existing.remove();

        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return;

        var html = buildSummaryHTML(data);

        // Look for page 2 indicator: the date field (item_meta[24])
        var dateField = form.querySelector('input[name="item_meta[24]"]');
        if (dateField) {
            var dateContainer = dateField.closest('.frm_form_field');
            if (dateContainer) {
                dateContainer.insertAdjacentHTML('beforebegin', html);
                summaryInjected = true;
                return;
            }
        }

        // Fallback: insert before the first visible field on page 2
        var $form = $(form);
        var $firstField = $form.find('.frm_form_field:visible').first();
        if ($firstField.length) {
            $firstField.before(html);
            summaryInjected = true;
            return;
        }

        // Last fallback: prepend inside fieldset after progress bar
        var $fieldset = $form.find('fieldset');
        if ($fieldset.length) {
            var $rootline = $fieldset.find('.frm_rootline_group, .frm_page_bar');
            if ($rootline.length) {
                $rootline.last().after(html);
            } else {
                $fieldset.prepend(html);
            }
            summaryInjected = true;
        }
    }

    /* ── handle page 2 load ───────────────────────────────── */

    function onPage2Loaded() {
        summaryInjected = false;
        setTimeout(function() {
            hideQuantityAndProductFields();
            injectSummary();
            updateTomoConocimientoText();
            fixDatepickerVisibility();
        }, 200);
    }

    /* ── event listeners ───────────────────────────────────── */

    function init() {
        var form = document.getElementById('form_' + FORM_KEY);

        // Fetch dolar observado for USD conversion
        fetchDolarObservado();

        // Inject "Cotizador Online" title
        injectCotizadorTitle();

        // Fix datepicker visibility globally
        fixDatepickerVisibility();

        // Fix WooCommerce product grid layout (Necesitas Equiparte? section)
        // Products were stacking vertically because float:none was applied.
        // Use flexbox to force horizontal layout on desktop.
        if (!document.getElementById('cdski-products-grid-fix')) {
            var gridFixStyle = document.createElement('style');
            gridFixStyle.id = 'cdski-products-grid-fix';
            gridFixStyle.textContent =
                '@media (min-width: 768px) {' +
                    '.woocommerce ul.products.columns-4 {' +
                        'display: flex !important;' +
                        'flex-wrap: wrap !important;' +
                        'list-style: none !important;' +
                        'padding: 0 !important;' +
                    '}' +
                    '.woocommerce ul.products.columns-4 li.product {' +
                        'flex: 0 0 25% !important;' +
                        'max-width: 25% !important;' +
                        'box-sizing: border-box !important;' +
                        'padding: 0 15px !important;' +
                        'list-style: none !important;' +
                    '}' +
                '}';
            document.head.appendChild(gridFixStyle);
        }

        // Enhance form visuals: invitation text, tooltips, styling
        enhanceFormVisuals();

        // Highlight the initially selected radio cards
        highlightSelectedRadios();

        // Pre-hide all number inputs inside the form via CSS so that when
        // Formidable shows new price fields on radio change, the raw value
        // is never visible (prevents flash of unformatted price).
        // IMPORTANT: We use color:transparent instead of height:0 because
        // Formidable clears values of fields that jQuery :visible considers
        // hidden (offsetHeight === 0).  Transparent text keeps the input
        // "visible" to Formidable so values are submitted correctly.
        if (!document.getElementById('cdski-price-prehide-css')) {
            var prehideStyle = document.createElement('style');
            prehideStyle.id = 'cdski-price-prehide-css';
            prehideStyle.textContent =
                '#form_' + FORM_KEY + ' input[type="number"] {' +
                    'color: transparent !important;' +
                    '-webkit-text-fill-color: transparent !important;' +
                    'pointer-events: none !important;' +
                '}';
            document.head.appendChild(prehideStyle);
        }

        // Format raw price fields on page 1 (runs on interval to catch
        // Formidable's async value population without MutationObserver conflicts)
        setInterval(formatRawPriceFields, 500);

        // 1. Capture data on every radio change (keeps storedData fresh)
        //    Use multiple timeouts to catch Formidable's async price recalculation
        $(document).on('change',
            '#form_' + FORM_KEY + ' input[name="item_meta[17]"], ' +
            '#form_' + FORM_KEY + ' input[name="item_meta[16]"]',
            function() {
                // Add transition class to prevent visual flash
                var form = document.getElementById('form_' + FORM_KEY);
                if (form) {
                    // Fade out the price area during transition
                    var priceOverlays = form.querySelectorAll('.cdski-price-overlay');
                    priceOverlays.forEach(function(el) { el.style.opacity = '0'; });
                }

                // Immediately format prices at 0ms to prevent flash of raw values.
                // CSS pre-hides number inputs, but we need overlays ASAP.
                formatRawPriceFields();
                capturePageOneData();

                // Update selected radio card highlight immediately
                highlightSelectedRadios();

                // Formidable needs time to show/hide the correct price fields
                // and populate their values. Use staggered timeouts.
                var delays = [200, 500, 1000, 1500];
                delays.forEach(function(ms) {
                    setTimeout(function() {
                        capturePageOneData();
                        formatRawPriceFields();
                        // Fade price overlays back in
                        if (form) {
                            var overlays = form.querySelectorAll('.cdski-price-overlay');
                            overlays.forEach(function(el) { el.style.opacity = '1'; });
                        }
                        // Update summary card if it exists
                        var summary = document.getElementById('cdski-booking-summary');
                        if (summary && storedData) {
                            summary.outerHTML = buildSummaryHTML(storedData);
                        }
                    }, ms);
                });
            }
        );

        // 2. Capture data right before "Continuar" fires the AJAX page swap
        $(document).on('click',
            '#form_' + FORM_KEY + ' .frm_next_page, ' +
            '#form_' + FORM_KEY + ' button[type="submit"]',
            capturePageOneData
        );

        // 3. Formidable fires frmPageChanged after AJAX page swap completes
        $(document).on('frmPageChanged', onPage2Loaded);

        // 4. Backup: intercept any AJAX completion for our form
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings && settings.data && typeof settings.data === 'string' &&
                settings.data.indexOf('form_id=' + FORM_ID) > -1) {
                onPage2Loaded();
            }
        });

        // 5. MutationObserver fallback for page-2 detection and price field formatting
        if (form) {
            var observer = new MutationObserver(function() {
                var finalBtn = form.querySelector('.frm_final_submit');
                if (finalBtn && !summaryInjected) {
                    setTimeout(function() {
                        hideQuantityAndProductFields();
                        injectSummary();
                        updateTomoConocimientoText();
                    }, 150);
                }
                // Price field formatting handled by setInterval in init()
            });
            observer.observe(form, { childList: true, subtree: true });
        }

        // Capture initial data on page load
        capturePageOneData();

        // If already on page 2 (e.g. page loaded with frm_page=2), handle immediately
        if (form && form.querySelector('input[name="item_meta[24]"]')) {
            onPage2Loaded();
        }
    }

    /* ── bootstrap ─────────────────────────────────────────── */
    $(document).ready(init);

})(jQuery);
