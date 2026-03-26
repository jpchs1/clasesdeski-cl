/**
 * Clasesdeski Pagos — Frontend JS v1.4
 * Amount formatting + summary + redirect flow.
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

    // Format number with dots: 50000 → 50.000
    function formatNumber(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Parse formatted number: 50.000 → 50000
    function parseAmount(str) {
        return parseInt(str.replace(/\./g, ''), 10) || 0;
    }

    // Format amount input on typing
    amountInput.addEventListener('input', function () {
        var raw = this.value.replace(/\D/g, '');
        if (raw.length > 0) {
            this.value = formatNumber(parseInt(raw, 10));
        }
        updateSummary();
        validateForm();
    });

    function getSelectedGateway() {
        for (var i = 0; i < gatewayInputs.length; i++) {
            if (gatewayInputs[i].checked) return gatewayInputs[i].value;
        }
        return '';
    }

    function getAmount() {
        return parseAmount(amountInput.value);
    }

    function validateForm() {
        var amount  = getAmount();
        var gateway = getSelectedGateway();
        submitBtn.disabled = !(amount >= 1000 && gateway);
    }

    function updateSummary() {
        var amount = getAmount();
        if (amount >= 1000 && summary) {
            summary.style.display = 'flex';
            summaryAmt.textContent = '$' + formatNumber(amount) + ' CLP';
        } else if (summary) {
            summary.style.display = 'none';
        }
    }

    for (var i = 0; i < gatewayInputs.length; i++) {
        gatewayInputs[i].addEventListener('change', function () {
            validateForm();
            updateSummary();
        });
    }

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.style.display = 'block';
    }

    function hideError() {
        errorBox.style.display = 'none';
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        hideError();

        var amount = getAmount();
        if (amount < 1000) {
            showError('El monto minimo es $1.000 CLP.');
            return;
        }

        var gateway = getSelectedGateway();
        if (!gateway) {
            showError('Selecciona un medio de pago.');
            return;
        }

        // Loading
        submitBtn.disabled = true;
        submitBtn.classList.add('cdski-loading');
        submitBtn.querySelector('.cdski-btn-text').style.display = 'none';
        submitBtn.querySelector('.cdski-btn-spinner').style.display = 'inline-flex';

        // Send raw numeric amount
        var data = new FormData(form);
        data.set('amount', amount);
        data.append('action', 'cdski_create_payment');
        data.append('nonce', cdskiPagos.nonce);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', cdskiPagos.ajaxUrl, true);
        xhr.onload = function () {
            var resp;
            try { resp = JSON.parse(xhr.responseText); } catch (err) {
                resetButton();
                showError('Error de conexion. Intenta nuevamente.');
                return;
            }
            if (resp.success && resp.data && resp.data.redirect) {
                window.location.href = resp.data.redirect;
            } else {
                resetButton();
                showError(resp.data && resp.data.message ? resp.data.message : 'Error al procesar el pago.');
            }
        };
        xhr.onerror = function () {
            resetButton();
            showError('Error de conexion. Verifica tu internet.');
        };
        xhr.send(data);
    });

    function resetButton() {
        submitBtn.disabled = false;
        submitBtn.classList.remove('cdski-loading');
        submitBtn.querySelector('.cdski-btn-text').style.display = 'inline';
        submitBtn.querySelector('.cdski-btn-spinner').style.display = 'none';
        validateForm();
    }
})();
