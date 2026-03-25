/**
 * Clasesdeski Pagos — Frontend JS v1.3
 * Handles Webpay/MP redirects and PayPal Buttons (card without login).
 */
(function () {
    'use strict';

    var form      = document.getElementById('cdski-pago-form');
    var submitBtn = document.getElementById('cdski-submit-btn');
    var errorBox  = document.getElementById('cdski-pago-error');

    if (!form || !submitBtn) return;

    var amountInput     = form.querySelector('[name="amount"]');
    var gatewayInputs   = form.querySelectorAll('[name="gateway"]');
    var ppBtnContainer  = document.getElementById('cdski-paypal-btn-container');

    // State
    var currentPaymentId = null;
    var ppButtonsRendered = false;

    function getSelectedGateway() {
        for (var i = 0; i < gatewayInputs.length; i++) {
            if (gatewayInputs[i].checked) return gatewayInputs[i].value;
        }
        return '';
    }

    function validateForm() {
        var amount  = parseFloat(amountInput.value) || 0;
        var gateway = getSelectedGateway();
        var isValid = amount >= 1000 && gateway;

        // For PayPal, hide submit button (PayPal has its own button)
        if (gateway === 'paypal' && ppBtnContainer) {
            submitBtn.style.display = 'none';
            ppBtnContainer.style.display = 'block';
        } else {
            submitBtn.style.display = '';
            if (ppBtnContainer) ppBtnContainer.style.display = 'none';
        }

        submitBtn.disabled = !isValid;
    }

    amountInput.addEventListener('input', validateForm);
    for (var i = 0; i < gatewayInputs.length; i++) {
        gatewayInputs[i].addEventListener('change', function () {
            validateForm();
            initPayPalButtons();
        });
    }

    function initPayPalButtons() {
        if (ppButtonsRendered) return;
        if (!ppBtnContainer) return;
        if (typeof paypal === 'undefined') return;
        if (getSelectedGateway() !== 'paypal') return;

        ppButtonsRendered = true;

        paypal.Buttons({
            fundingSource: paypal.FUNDING.CARD,
            style: {
                color: 'black',
                shape: 'rect',
                label: 'pay',
                height: 50
            },
            createOrder: function (data, actions) {
                hideError();
                var amount = parseFloat(amountInput.value) || 0;
                if (amount < 1000) {
                    showError('El monto mínimo es $1.000 CLP.');
                    return actions.reject();
                }

                // First register payment in DB
                return new Promise(function (resolve, reject) {
                    var formData = new FormData(form);
                    formData.append('action', 'cdski_create_payment');
                    formData.append('nonce', cdskiPagos.nonce);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', cdskiPagos.ajaxUrl, true);
                    xhr.onload = function () {
                        var resp;
                        try { resp = JSON.parse(xhr.responseText); } catch (e) {
                            showError('Error de conexión.');
                            reject();
                            return;
                        }
                        if (resp.success && resp.data && resp.data.payment_id) {
                            currentPaymentId = resp.data.payment_id;
                            // Now create PayPal order
                            var fd2 = new FormData();
                            fd2.append('action', 'cdski_paypal_create_order');
                            fd2.append('nonce', cdskiPagos.nonce);
                            fd2.append('payment_id', currentPaymentId);

                            var xhr2 = new XMLHttpRequest();
                            xhr2.open('POST', cdskiPagos.ajaxUrl, true);
                            xhr2.onload = function () {
                                var r2;
                                try { r2 = JSON.parse(xhr2.responseText); } catch (e) {
                                    showError('Error al crear la orden.');
                                    reject();
                                    return;
                                }
                                if (r2.success && r2.data && r2.data.orderID) {
                                    resolve(r2.data.orderID);
                                } else {
                                    showError(r2.data && r2.data.message ? r2.data.message : 'Error con PayPal.');
                                    reject();
                                }
                            };
                            xhr2.onerror = function () { showError('Error de conexión.'); reject(); };
                            xhr2.send(fd2);
                        } else {
                            showError(resp.data && resp.data.message ? resp.data.message : 'Error al registrar pago.');
                            reject();
                        }
                    };
                    xhr.onerror = function () { showError('Error de conexión.'); reject(); };
                    xhr.send(formData);
                });
            },
            onApprove: function (data, actions) {
                // Capture the order
                var fd = new FormData();
                fd.append('action', 'cdski_paypal_capture_order');
                fd.append('nonce', cdskiPagos.nonce);
                fd.append('payment_id', currentPaymentId);
                fd.append('orderID', data.orderID);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', cdskiPagos.ajaxUrl, true);
                xhr.onload = function () {
                    var resp;
                    try { resp = JSON.parse(xhr.responseText); } catch (e) {
                        showError('Error al confirmar el pago.');
                        return;
                    }
                    var redirect = (resp.data && resp.data.redirect) ? resp.data.redirect : null;
                    if (redirect) {
                        window.location.href = redirect;
                    } else {
                        showError(resp.data && resp.data.message ? resp.data.message : 'Pago rechazado.');
                    }
                };
                xhr.onerror = function () { showError('Error de conexión.'); };
                xhr.send(fd);
            },
            onCancel: function () {
                showError('Pago cancelado.');
            },
            onError: function (err) {
                showError('Error al procesar. Intenta con otro medio de pago.');
            }
        }).render('#cdski-paypal-btn-container');
    }

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.style.display = 'block';
    }

    function hideError() {
        errorBox.style.display = 'none';
    }

    function setLoading(loading) {
        submitBtn.disabled = loading;
        if (loading) {
            submitBtn.classList.add('cdski-loading');
            submitBtn.querySelector('.cdski-btn-text').style.display = 'none';
            submitBtn.querySelector('.cdski-btn-spinner').style.display = 'inline-flex';
        }
    }

    function resetButton() {
        submitBtn.classList.remove('cdski-loading');
        submitBtn.querySelector('.cdski-btn-text').style.display = 'inline';
        submitBtn.querySelector('.cdski-btn-spinner').style.display = 'none';
        validateForm();
    }

    // ─── Form submit (Webpay / Mercado Pago only) ────────
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

        // PayPal uses its own button, not this submit
        if (gateway === 'paypal') return;

        setLoading(true);

        var data = new FormData(form);
        data.append('action', 'cdski_create_payment');
        data.append('nonce', cdskiPagos.nonce);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', cdskiPagos.ajaxUrl, true);
        xhr.onload = function () {
            var resp;
            try { resp = JSON.parse(xhr.responseText); } catch (err) {
                resetButton();
                showError('Error de conexión.');
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
            showError('Error de conexión. Verifica tu internet.');
        };
        xhr.send(data);
    });
})();
