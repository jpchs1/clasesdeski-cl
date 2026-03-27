/**
 * Clasesdeski Pagos — v2.2 (PayPal Smart Buttons)
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
    var ppContainer   = document.getElementById('paypal-button-container');

    var API_BASE = (typeof cdskiPagos !== 'undefined' && cdskiPagos.siteUrl)
        ? cdskiPagos.siteUrl + '/api'
        : '/api';

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

    for (var i = 0; i < gatewayInputs.length; i++) {
        gatewayInputs[i].addEventListener('change', function () {
            selectMethod(gateway());
        });
    }

    function selectMethod(method) {
        refresh();
        if (method === 'paypal') {
            submitBtn.style.display = 'none';
            if (ppContainer) {
                ppContainer.style.display = 'block';
                renderPayPalButtons();
            }
        } else {
            submitBtn.style.display = 'flex';
            if (ppContainer) ppContainer.style.display = 'none';
        }
    }

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

    function showStatus(msg, type) {
        if (type === 'success') {
            errorBox.style.display = 'block';
            errorBox.style.background = '#f0fdf4';
            errorBox.style.borderColor = '#bbf7d0';
            errorBox.style.color = '#15803d';
            errorBox.textContent = msg;
        } else if (type === 'info') {
            errorBox.style.display = 'block';
            errorBox.style.background = '#eff6ff';
            errorBox.style.borderColor = '#bfdbfe';
            errorBox.style.color = '#1d4ed8';
            errorBox.textContent = msg;
        } else {
            errorBox.style.display = 'block';
            errorBox.style.background = '#fef2f2';
            errorBox.style.borderColor = '#fecaca';
            errorBox.style.color = '#dc2626';
            errorBox.textContent = msg;
        }
    }

    function validateForm() {
        hideError();
        var amt = parse(amountInput.value);
        if (amt < 1000) { showError('El monto mínimo es $1.000 CLP.'); return false; }

        var nombre  = (document.getElementById('cdski-nombre') || {}).value || '';
        var email   = (document.getElementById('cdski-email') || {}).value || '';
        var concepto = (document.getElementById('cdski-concepto') || {}).value || '';

        return {
            amount: amt,
            description: concepto || 'Clase de Ski - Clasesdeski',
            name: nombre,
            email: email
        };
    }

    /* --- PayPal Smart Buttons (Guest Checkout with Card) --- */
    var paypalButtonsRendered = false;

    function showPayPalLoader() {
        var container = document.getElementById('paypal-button-container');
        if (!container || container.querySelector('.paypal-loading')) return;
        container.innerHTML = '<div class="paypal-loading"><div class="paypal-spinner"></div><div class="paypal-loading-text">Cargando opciones de pago...</div></div>';
    }

    function hidePayPalLoader() {
        var loader = document.querySelector('#paypal-button-container .paypal-loading');
        if (loader) loader.remove();
    }

    function renderPayPalButtons() {
        if (paypalButtonsRendered) return;
        if (typeof paypal === 'undefined' || typeof paypal.Buttons !== 'function') {
            showPayPalLoader();
            loadPayPalSDKForButtons();
            return;
        }
        doRenderPayPalButtons();
    }

    function loadPayPalSDKForButtons() {
        if (typeof paypal !== 'undefined' && typeof paypal.Buttons === 'function') {
            doRenderPayPalButtons();
            return;
        }
        fetch(API_BASE + '/paypal.php?action=get_client_id').then(function(r) { return r.json(); }).then(function(cfg) {
            if (!cfg.client_id) {
                hidePayPalLoader();
                showStatus('Error: no se pudo obtener la configuración de PayPal.', 'error');
                return;
            }
            var sdkDomain = (cfg.environment === 'production') ? 'www.paypal.com' : 'www.sandbox.paypal.com';
            var script = document.createElement('script');
            script.src = 'https://' + sdkDomain + '/sdk/js?client-id=' + cfg.client_id + '&currency=USD&components=buttons&enable-funding=card';
            script.onload = function() { doRenderPayPalButtons(); };
            script.onerror = function() {
                hidePayPalLoader();
                showStatus('Error al cargar PayPal SDK. Intente nuevamente.', 'error');
            };
            document.head.appendChild(script);
        }).catch(function(err) {
            console.error('PayPal config fetch error:', err);
            hidePayPalLoader();
            showStatus('Error al conectar con PayPal. Verifique su conexión.', 'error');
        });
    }

    function doRenderPayPalButtons() {
        if (paypalButtonsRendered) return;
        var container = document.getElementById('paypal-button-container');
        if (!container) return;
        hidePayPalLoader();
        container.innerHTML = '';
        paypalButtonsRendered = true;

        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'gold',
                shape: 'rect',
                label: 'paypal',
                tagline: false
            },
            createOrder: function(data, actions) {
                var formData = validateForm();
                if (!formData) return Promise.reject(new Error('Datos inválidos'));
                var usdAmount = Math.max(1, Math.round(formData.amount / 950 * 100) / 100).toFixed(2);
                var phone = (document.getElementById('cdski-telefono') || {}).value || '';
                return fetch(API_BASE + '/paypal.php?action=create_order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        amount: parseFloat(usdAmount),
                        description: formData.description,
                        currency: 'USD',
                        plan_name: formData.description,
                        payer_email: formData.email,
                        payer_name: formData.name,
                        payer_phone: phone,
                        type: 'link'
                    })
                }).then(function(res) { return res.json(); }).then(function(result) {
                    if (result.success && result.order_id) {
                        return result.order_id;
                    }
                    throw new Error(result.error || 'Error al crear orden PayPal');
                });
            },
            onApprove: function(data, actions) {
                showStatus('Procesando pago...', 'info');
                return fetch(API_BASE + '/paypal.php?action=capture_order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: data.orderID })
                }).then(function(res) { return res.json(); }).then(function(result) {
                    if (result.success) {
                        showStatus('¡Pago procesado exitosamente! Recibirás un email de confirmación en breve.', 'success');
                    } else {
                        throw new Error(result.error || 'Error al capturar el pago');
                    }
                });
            },
            onCancel: function() {
                showStatus('Pago cancelado. Puedes intentar nuevamente.', 'error');
            },
            onError: function(err) {
                console.error('PayPal Buttons error:', err);
                showStatus('Error al procesar el pago con PayPal: ' + (err.message || 'Intente nuevamente'), 'error');
            }
        }).render('#paypal-button-container');
    }

    /* --- Form submit (Webpay / MercadoPago only) --- */
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        hideError();

        var selectedMethod = gateway();

        /* PayPal is handled by Smart Buttons inline, not this flow */
        if (selectedMethod === 'paypal') {
            return;
        }

        var amt = parse(amountInput.value);
        if (amt < 1000) { showError('El monto minimo es $1.000 CLP.'); return; }
        if (!selectedMethod) { showError('Selecciona un medio de pago.'); return; }

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
