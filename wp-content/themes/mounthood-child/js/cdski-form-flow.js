/**
 * CDSKI Form Flow - Calculator to Booking Summary
 *
 * Captures page 1 data (persons, modality, price) from the Formidable Forms
 * multi-page calculator form (form_id=2, key=calculadora1) and displays
 * a styled summary card when page 2 (Agendar Clases) loads via AJAX.
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

    /* ── helpers ───────────────────────────────────────────── */

    function formatPrice(val) {
        if (!val || isNaN(val)) return '$0';
        var num = Math.round(parseFloat(val));
        return '$' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    var personasMap = { 'Dos': '2', 'Tres': '3', 'Cuatro': '4', 'Cinco': '5' };
    function personasToNum(txt) { return personasMap[txt] || txt; }

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
        return '<div id="cdski-booking-summary">' +
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
                    '<div class="cdski-summary-value cdski-price-strike">' + formatPrice(d.precio) + '</div>' +
                '</div>' +
                '<div class="cdski-summary-row cdski-summary-total">' +
                    '<div class="cdski-summary-label">Precio con Descuento</div>' +
                    '<div class="cdski-summary-value cdski-price-final">' + formatPrice(d.precioDescuento) + '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
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

        var $form      = $(form);
        var html       = buildSummaryHTML(data);

        // Formidable replaces the inner fieldset content for page 2.
        // Insert the summary card before the first visible form field.
        var $firstField = $form.find('.frm_form_field:visible').first();
        if ($firstField.length) {
            $firstField.before(html);
        } else {
            // Fallback: prepend inside fieldset
            var $fieldset = $form.find('fieldset');
            if ($fieldset.length) {
                // Insert after the rootline/progress bar if it exists
                var $rootline = $fieldset.find('.frm_rootline_group, .frm_page_bar');
                if ($rootline.length) {
                    $rootline.last().after(html);
                } else {
                    $fieldset.prepend(html);
                }
            }
        }

        summaryInjected = true;
    }

    /* ── event listeners ───────────────────────────────────── */

    function init() {
        var form = document.getElementById('form_' + FORM_KEY);

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
        $(document).on('frmPageChanged', function() {
            summaryInjected = false;
            setTimeout(injectSummary, 200);
        });

        // 4. Backup: intercept any AJAX completion for our form
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings && settings.data && typeof settings.data === 'string' &&
                settings.data.indexOf('form_id=' + FORM_ID) > -1) {
                summaryInjected = false;
                setTimeout(injectSummary, 300);
            }
        });

        // 5. MutationObserver fallback for page-2 detection
        if (form) {
            var observer = new MutationObserver(function() {
                var finalBtn = form.querySelector('.frm_final_submit');
                if (finalBtn && !summaryInjected) {
                    setTimeout(injectSummary, 150);
                }
            });
            observer.observe(form, { childList: true, subtree: true });
        }

        // Capture initial data on page load
        capturePageOneData();
    }

    /* ── bootstrap ─────────────────────────────────────────── */
    $(document).ready(init);

})(jQuery);
