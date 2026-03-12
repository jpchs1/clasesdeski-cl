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

    function formatCLP(val) {
        if (!val || isNaN(val)) return 'CLP $0';
        var num = Math.round(parseFloat(val));
        return 'CLP $' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatUSD(clpVal) {
        if (!clpVal || isNaN(clpVal) || !dolarObservado) return '';
        var num = parseFloat(clpVal) / dolarObservado;
        return 'USD $' + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
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

        var precioEl = form.querySelector('input[name="item_meta[19]"]');
        var descEl   = form.querySelector('input[name="item_meta[20]"]');

        var data = {
            personas:        personas,
            plan:            plan,
            precio:          precioEl ? precioEl.value : '',
            precioDescuento: descEl   ? descEl.value   : ''
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

    /* ── update price displays when dolar arrives ──────────── */

    function updatePriceDisplays() {
        var summary = document.getElementById('cdski-booking-summary');
        if (summary && storedData) {
            var strikeEl = summary.querySelector('.cdski-price-strike');
            var finalEl  = summary.querySelector('.cdski-price-final');
            if (strikeEl) strikeEl.innerHTML = formatPriceBlock(storedData.precio);
            if (finalEl)  finalEl.innerHTML  = formatPriceBlock(storedData.precioDescuento);
        }
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

    /* ── set default selections: 2 personas + Full-day ────── */

    function setDefaultSelections() {
        var form = document.getElementById('form_' + FORM_KEY);
        if (!form) return;

        // Default personas: "Dos" (2 personas)
        var personasRadios = form.querySelectorAll('input[name="item_meta[17]"]');
        var anyPersonasChecked = false;
        personasRadios.forEach(function(r) { if (r.checked) anyPersonasChecked = true; });
        if (!anyPersonasChecked) {
            personasRadios.forEach(function(r) {
                if (r.value === 'Dos') {
                    r.checked = true;
                    r.dispatchEvent(new Event('change', { bubbles: true }));
                    // Also mark the label as selected for Formidable's visual state
                    var label = r.closest('label') || form.querySelector('label[for="' + r.id + '"]');
                    if (label) label.click();
                }
            });
        }

        // Default plan: "Full-day (Día completo)"
        var planRadios = form.querySelectorAll('input[name="item_meta[16]"]');
        var anyPlanChecked = false;
        planRadios.forEach(function(r) { if (r.checked) anyPlanChecked = true; });
        if (!anyPlanChecked) {
            planRadios.forEach(function(r) {
                if (r.value && r.value.indexOf('Full') > -1) {
                    r.checked = true;
                    r.dispatchEvent(new Event('change', { bubbles: true }));
                    var label = r.closest('label') || form.querySelector('label[for="' + r.id + '"]');
                    if (label) label.click();
                }
            });
        }
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

        // 1. Capture data on every radio change (keeps storedData fresh)
        $(document).on('change',
            '#form_' + FORM_KEY + ' input[name="item_meta[17]"], ' +
            '#form_' + FORM_KEY + ' input[name="item_meta[16]"]',
            capturePageOneData
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

        // 5. MutationObserver fallback for page-2 detection
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
            });
            observer.observe(form, { childList: true, subtree: true });
        }

        // Set default selections: 2 personas (Dos) + Full-day
        setDefaultSelections();

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
