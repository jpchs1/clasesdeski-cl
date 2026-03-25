/**
 * Clasesdeski Pagos — Frontend JS v1.2
 * Handles Webpay/MP redirects and PayPal inline card fields.
 */
(function () {
    'use strict';

    var form      = document.getElementById('cdski-pago-form');
    var submitBtn = document.getElementById('cdski-submit-btn');
    var errorBox  = document.getElementById('cdski-pago-error');

    if (!form || !submitBtn) return;

    var amountInput    = form.querySelector('[name="amount"]');
    var gatewayInputs  = form.querySelectorAll('[name="gateway"]');
    var ppCardContainer = document.getElementById('cdski-paypal-card-container');

    // State
    var currentPaymentId = null;
    var ppCardFields     = null;
    var ppFieldsRendered = false;

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
        gatewayInputs[i].addEventListener('change', function () {
            validateForm();
            togglePayPalFields();
        });
    }

    function togglePayPalFields() {
        if (!ppCardContainer) return;
        var isPayPal = getSelectedGateway() === 'paypal';
        ppCardContainer.style.display = isPayPal ? 'block' : 'none';

        // Initialize PayPal card fields on first selection
        if (isPayPal && !ppFieldsRendered && cdskiPagos.hasPaypalSdk && typeof paypal !== 'undefined') {
            initPayPalCardFields();
        }
    }

    function initPayPalCardFields() {
        if (ppFieldsRendered) return;
        if (typeof paypal === 'undefined' || !paypal.CardFields) {
            // Fallback: if card-fields not available, hide container
            if (ppCardContainer) ppCardContainer.style.display = 'none';
            return;
        }

        ppCardFields = paypal.CardFields({
            createOrder: function () {
                return createPayPalOrder();
            },
            onApprove: function (data) {
                return capturePayPalOrder(data.orderID);
            },
            onError: function (err) {
                resetButton();
                showError('Error al procesar la tarjeta. Verifica los datos e intenta nuevamente.');
            },
            style: {
                input: {
                    'font-size': '15px',
                    'font-family': '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                    color: '#1a1a2e',
                    padding: '11px 14px'
                },
                '.invalid': {
                    color: '#dc2626'
                }
            }
        });

        if (ppCardFields.isEligible()) {
            ppCardFields.NumberField().render('#card-number-field-container');
            ppCardFields.ExpiryField().render('#card-expiry-field-container');
            ppCardFields.CVVField().render('#card-cvv-field-container');
            ppCardFields.NameField().render('#card-name-field-container');
            ppFieldsRendered = true;
        } else {
            // Card fields not eligible — hide the container
            if (ppCardContainer) ppCardContainer.style.display = 'none';
        }
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

    /**
     * Step 1 for all gateways: register payment in DB via AJAX.
     */
    function registerPayment(callback) {
        var data = new FormData(form);
        data.append('action', 'cdski_create_payment');
        data.append('nonce', cdskiPagos.nonce);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', cdskiPagos.ajaxUrl, true);
        xhr.onload = function () {
            var resp;
            try { resp = JSON.parse(xhr.responseText); } catch (e) {
                resetButton();
                showError('Error de conexión.');
                return;
            }
            callback(resp);
        };
        xhr.onerror = function () {
            resetButton();
            showError('Error de conexión. Verifica tu internet.');
        };
        xhr.send(data);
    }

    /**
     * Create PayPal order (called by PayPal JS SDK).
     */
    function createPayPalOrder() {
        return new Promise(function (resolve, reject) {
            var data = new FormData();
            data.append('action', 'cdski_paypal_create_order');
            data.append('nonce', cdskiPagos.nonce);
            data.append('payment_id', currentPaymentId);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', cdskiPagos.ajaxUrl, true);
            xhr.onload = function () {
                var resp;
                try { resp = JSON.parse(xhr.responseText); } catch (e) {
                    reject(new Error('Parse error'));
                    return;
                }
                if (resp.success && resp.data && resp.data.orderID) {
                    resolve(resp.data.orderID);
                } else {
                    reject(new Error(resp.data && resp.data.message ? resp.data.message : 'Error'));
                }
            };
            xhr.onerror = function () { reject(new Error('Network error')); };
            xhr.send(data);
        });
    }

    /**
     * Capture PayPal order after card approval.
     */
    function capturePayPalOrder(orderID) {
        var data = new FormData();
        data.append('action', 'cdski_paypal_capture_order');
        data.append('nonce', cdskiPagos.nonce);
        data.append('payment_id', currentPaymentId);
        data.append('orderID', orderID);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', cdskiPagos.ajaxUrl, true);
        xhr.onload = function () {
            var resp;
            try { resp = JSON.parse(xhr.responseText); } catch (e) {
                resetButton();
                showError('Error al confirmar el pago.');
                return;
            }
            if (resp.success && resp.data && resp.data.redirect) {
                window.location.href = resp.data.redirect;
            } else if (resp.data && resp.data.redirect) {
                window.location.href = resp.data.redirect;
            } else {
                resetButton();
                showError(resp.data && resp.data.message ? resp.data.message : 'Pago rechazado.');
            }
        };
        xhr.onerror = function () {
            resetButton();
            showError('Error de conexión.');
        };
        xhr.send(data);
    }

    // ─── Form submit handler ─────────────────────────────
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

        setLoading(true);

        if (gateway === 'paypal') {
            // PayPal flow: register payment → then submit card fields via SDK
            registerPayment(function (resp) {
                if (resp.success && resp.data) {
                    // For paypal, the server returns payment_id instead of redirect
                    currentPaymentId = resp.data.payment_id;
                    // Trigger PayPal card fields submit
                    if (ppCardFields) {
                        ppCardFields.submit().catch(function (err) {
                            resetButton();
                            showError('Error al procesar la tarjeta. Verifica los datos.');
                        });
                    } else {
                        // Fallback: redirect to PayPal
                        if (resp.data.redirect) {
                            window.location.href = resp.data.redirect;
                        } else {
                            resetButton();
                            showError('Error al conectar con PayPal.');
                        }
                    }
                } else {
                    resetButton();
                    showError(resp.data && resp.data.message ? resp.data.message : 'Error al procesar.');
                }
            });
        } else {
            // Webpay / Mercado Pago: register payment → redirect
            registerPayment(function (resp) {
                if (resp.success && resp.data && resp.data.redirect) {
                    window.location.href = resp.data.redirect;
                } else {
                    resetButton();
                    showError(resp.data && resp.data.message ? resp.data.message : 'Error al procesar el pago.');
                }
            });
        }
    });
})();
