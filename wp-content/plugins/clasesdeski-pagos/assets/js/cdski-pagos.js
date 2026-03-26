/**
 * Clasesdeski Pagos — v2.1
 */
(function () {
    'use strict';

    var form       = document.getElementById('cdski-pago-form');
    var submitBtn  = document.getElementById('cdski-submit-btn');
    var errorBox   = document.getElementById('cdski-pago-error');
    var summary    = document.getElementById('cdski-summary');
    var summaryAmt = document.getElementById('cdski-summary-amount');

    if (!form || !submitBtn) return;

    var amountInput   = document.getElementById('cdski-amount');
    var gatewayInputs = form.querySelectorAll('[name="gateway"]');

    function fmt(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
    function parse(s) { return parseInt((s || '').replace(/\./g, ''), 10) || 0; }

    amountInput.addEventListener('input', function () {
        var raw = this.value.replace(/\D/g, '');
        if (raw.length > 0) this.value = fmt(parseInt(raw, 10));
        refresh();
    });

    function gateway() {
        for (var i = 0; i < gatewayInputs.length; i++) if (gatewayInputs[i].checked) return gatewayInputs[i].value;
        return '';
    }

    for (var i = 0; i < gatewayInputs.length; i++) gatewayInputs[i].addEventListener('change', refresh);

    function refresh() {
        var amt = parse(amountInput.value);
        var gw  = gateway();
        submitBtn.disabled = !(amt >= 1000 && gw);

        if (amt >= 1000 && summary) {
            summary.style.display = 'flex';
            summaryAmt.textContent = '$' + fmt(amt) + ' CLP';
        } else if (summary) {
            summary.style.display = 'none';
        }
    }

    function showError(m) { errorBox.textContent = m; errorBox.style.display = 'block'; }
    function hideError() { errorBox.style.display = 'none'; }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        hideError();

        var amt = parse(amountInput.value);
        if (amt < 1000) { showError('El monto minimo es $1.000 CLP.'); return; }
        if (!gateway()) { showError('Selecciona un medio de pago.'); return; }

        submitBtn.disabled = true;
        submitBtn.classList.add('cdski-loading');
        submitBtn.querySelector('.cdski-btn-text').style.display = 'none';
        submitBtn.querySelector('.cdski-btn-spinner').style.display = 'inline-flex';

        var data = new FormData(form);
        data.set('amount', amt);
        data.append('action', 'cdski_create_payment');
        data.append('nonce', cdskiPagos.nonce);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', cdskiPagos.ajaxUrl, true);
        xhr.onload = function () {
            var r; try { r = JSON.parse(xhr.responseText); } catch (e) { reset(); showError('Error de conexion.'); return; }
            if (r.success && r.data && r.data.redirect) window.location.href = r.data.redirect;
            else { reset(); showError(r.data && r.data.message ? r.data.message : 'Error al procesar el pago.'); }
        };
        xhr.onerror = function () { reset(); showError('Error de conexion.'); };
        xhr.send(data);
    });

    function reset() {
        submitBtn.disabled = false;
        submitBtn.classList.remove('cdski-loading');
        submitBtn.querySelector('.cdski-btn-text').style.display = 'inline';
        submitBtn.querySelector('.cdski-btn-spinner').style.display = 'none';
        refresh();
    }
})();
