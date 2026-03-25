/**
 * Clasesdeski Pagos — Frontend JS
 */
(function () {
    'use strict';

    var form      = document.getElementById('cdski-pago-form');
    var submitBtn = document.getElementById('cdski-submit-btn');
    var errorBox  = document.getElementById('cdski-pago-error');

    if (!form || !submitBtn) return;

    var amountInput   = form.querySelector('[name="amount"]');
    var gatewayInputs = form.querySelectorAll('[name="gateway"]');

    function getSelectedGateway() {
        for (var i = 0; i < gatewayInputs.length; i++) {
            if (gatewayInputs[i].checked) return gatewayInputs[i].value;
        }
        return '';
    }

    function validateForm() {
        var amount  = parseFloat(amountInput.value) || 0;
        var gateway = getSelectedGateway();
        submitBtn.disabled = !(amount >= 1000 && gateway);
    }

    amountInput.addEventListener('input', validateForm);
    for (var i = 0; i < gatewayInputs.length; i++) {
        gatewayInputs[i].addEventListener('change', validateForm);
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

        var amount = parseFloat(amountInput.value) || 0;
        if (amount < 1000) {
            showError('El monto mínimo es $1.000 CLP.');
            return;
        }

        var gateway = getSelectedGateway();
        if (!gateway) {
            showError('Selecciona un medio de pago.');
            return;
        }

        // Disable button, show loading
        submitBtn.disabled = true;
        submitBtn.classList.add('cdski-loading');
        submitBtn.querySelector('.cdski-btn-text').style.display = 'none';
        submitBtn.querySelector('.cdski-btn-spinner').style.display = 'inline';

        var data = new FormData(form);
        data.append('action', 'cdski_create_payment');
        data.append('nonce', cdskiPagos.nonce);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', cdskiPagos.ajaxUrl, true);
        xhr.onload = function () {
            var resp;
            try {
                resp = JSON.parse(xhr.responseText);
            } catch (err) {
                resetButton();
                showError('Error de conexión. Intenta nuevamente.');
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
            showError('Error de conexión. Verifica tu internet e intenta nuevamente.');
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
