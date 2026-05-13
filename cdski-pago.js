/**
 * ClasesdeSki — Portal de Pago (v1.0)
 *
 * Integrates 3 payment processors against clasesdeski.cl/api/*.php:
 *   - Webpay Plus       POST /api/webpay.php?action=create_transaction → redirect form
 *   - MercadoPago       POST /api/mercadopago.php?action=create_preference → redirect to init_point
 *   - PayPal Smart Btns GET  /api/paypal.php?action=get_client_id + create_order/capture_order
 *
 * Webpay/MP charge in CLP. PayPal charges in USD (with CLP→USD rate adjustable).
 */
(function () {
  'use strict';

  var USD_RATE = Number(window.CDSKI_USD_RATE || 950);

  var $ = function (id) { return document.getElementById(id); };
  var form          = $('cdski-pago-form');
  var amountInput   = $('amount');
  var amountCurLbl  = $('amountCurrencyLabel');
  var usdRateLabel  = $('usdRateLabel');
  var helpClp       = document.querySelector('[data-clp]');
  var helpUsd       = document.querySelector('[data-usd]');
  var statusEl      = $('cdski-pago-status');
  var submitBtn     = $('cdski-pago-submit');
  var ppContainer   = $('paypal-buttons-container');
  var methodButtons = document.querySelectorAll('.cdski-pago-method');
  var acceptTerms   = $('acceptTerms');

  if (usdRateLabel) usdRateLabel.textContent = '≈ ' + USD_RATE;

  var selectedMethod   = null;
  var selectedCurrency = 'CLP';
  var paypalButtonsInstance = null;

  function parseAmount(str) {
    if (!str) return 0;
    return Number(String(str).replace(/[^\d.,]/g, '').replace(/\./g, '').replace(/,/g, '.')) || 0;
  }
  function clpToUsd(clp) {
    return Math.max(1, +((clp / USD_RATE).toFixed(2)));
  }
  function setStatus(html, kind) {
    if (!statusEl) return;
    statusEl.hidden = false;
    statusEl.className = 'cdski-pago-status cdski-pago-status-' + (kind || 'info');
    statusEl.innerHTML = html;
    statusEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  function clearStatus() {
    if (statusEl) { statusEl.hidden = true; statusEl.innerHTML = ''; }
  }
  function validateForm() {
    if (!form.checkValidity()) return { ok: false, msg: 'Completa todos los campos requeridos.' };
    if (!acceptTerms.checked)  return { ok: false, msg: 'Debes aceptar los términos y condiciones.' };
    var amt = parseAmount(amountInput.value);
    if (amt < 1) return { ok: false, msg: 'Monto inválido.' };
    if (selectedCurrency === 'CLP' && amt < 50) return { ok: false, msg: 'Monto mínimo: $50 CLP.' };
    return { ok: true };
  }
  function payload() {
    return {
      payer_name:   $('payerName').value.trim(),
      payer_email:  $('payerEmail').value.trim(),
      payer_phone:  $('payerPhone').value.trim(),
      booking_code: $('bookingCode').value.trim(),
      description:  $('description').value.trim(),
      amount:       parseAmount(amountInput.value),
      currency:     selectedCurrency,
    };
  }
  function updateCurrencyUI(cur) {
    selectedCurrency = cur;
    if (amountCurLbl) amountCurLbl.textContent = '(' + cur + ')';
    if (helpClp) helpClp.hidden = (cur !== 'CLP');
    if (helpUsd) helpUsd.hidden = (cur !== 'USD');
  }

  function selectMethod(method) {
    selectedMethod = method;
    methodButtons.forEach(function (b) {
      var active = b.dataset.method === method;
      b.classList.toggle('is-active', active);
      b.setAttribute('aria-pressed', active ? 'true' : 'false');
    });

    var cur = (method === 'paypal') ? 'USD' : 'CLP';
    updateCurrencyUI(cur);
    submitBtn.disabled = false;

    if (method === 'paypal') {
      submitBtn.hidden = true;
      ppContainer.hidden = false;
      mountPayPalButtons();
    } else {
      submitBtn.hidden = false;
      ppContainer.hidden = true;
      submitBtn.textContent = (method === 'webpay')
        ? 'Pagar con Webpay Plus'
        : 'Pagar con MercadoPago';
    }
    clearStatus();
  }

  methodButtons.forEach(function (b) {
    b.addEventListener('click', function () { selectMethod(b.dataset.method); });
  });

  function payWithWebpay() {
    var v = validateForm();
    if (!v.ok) return setStatus('⚠ ' + v.msg, 'error');

    var p = payload();
    setStatus('Generando transacción Webpay…', 'info');
    submitBtn.disabled = true;

    fetch('/api/webpay.php?action=create_transaction', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        amount:       p.amount,
        description:  p.description,
        booking_code: p.booking_code,
        payer_email:  p.payer_email,
      })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
      if (!data.success || !data.token || !data.url) {
        throw new Error(data.error || 'No se pudo crear la transacción.');
      }
      var f = document.createElement('form');
      f.method = 'POST';
      f.action = data.url;
      var inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = 'token_ws';
      inp.value = data.token;
      f.appendChild(inp);
      document.body.appendChild(f);
      setStatus('Redirigiendo a Webpay…', 'info');
      f.submit();
    })
    .catch(function (e) {
      submitBtn.disabled = false;
      setStatus('❌ ' + (e.message || 'Error con Webpay.'), 'error');
    });
  }

  function payWithMercadoPago() {
    var v = validateForm();
    if (!v.ok) return setStatus('⚠ ' + v.msg, 'error');

    var p = payload();
    setStatus('Generando preferencia MercadoPago…', 'info');
    submitBtn.disabled = true;

    fetch('/api/mercadopago.php?action=create_preference', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(p),
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
      if (!data.success || !data.init_point) {
        throw new Error(data.error || 'No se pudo crear la preferencia.');
      }
      setStatus('Redirigiendo a MercadoPago…', 'info');
      location.href = data.init_point;
    })
    .catch(function (e) {
      submitBtn.disabled = false;
      setStatus('❌ ' + (e.message || 'Error con MercadoPago.'), 'error');
    });
  }

  function loadPayPalSdk(clientId) {
    return new Promise(function (resolve, reject) {
      if (window.paypal) return resolve();
      var s = document.createElement('script');
      s.src = 'https://www.paypal.com/sdk/js?client-id=' + encodeURIComponent(clientId) +
              '&currency=USD&intent=capture&disable-funding=credit';
      s.onload  = function () { resolve(); };
      s.onerror = function () { reject(new Error('No se pudo cargar el SDK de PayPal.')); };
      document.head.appendChild(s);
    });
  }

  function mountPayPalButtons() {
    if (paypalButtonsInstance) {
      paypalButtonsInstance.close();
      paypalButtonsInstance = null;
    }
    ppContainer.innerHTML = '';
    setStatus('Cargando PayPal…', 'info');

    fetch('/api/paypal.php?action=get_client_id')
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.client_id) throw new Error('PayPal client_id no disponible.');
        return loadPayPalSdk(data.client_id);
      })
      .then(function () {
        clearStatus();
        paypalButtonsInstance = window.paypal.Buttons({
          style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'pay', height: 48 },

          onClick: function (data, actions) {
            var v = validateForm();
            if (!v.ok) {
              setStatus('⚠ ' + v.msg, 'error');
              return actions.reject();
            }
            return actions.resolve();
          },

          createOrder: function () {
            var p = payload();
            var usdAmount = (selectedCurrency === 'USD')
              ? p.amount
              : clpToUsd(p.amount);

            return fetch('/api/paypal.php?action=create_order', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                amount:       usdAmount,
                currency:     'USD',
                description:  p.description,
                booking_code: p.booking_code,
              })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
              if (!data.success || !data.order_id) {
                throw new Error(data.error || 'No se pudo crear la orden PayPal.');
              }
              return data.order_id;
            });
          },

          onApprove: function (data) {
            setStatus('Confirmando pago con PayPal…', 'info');
            return fetch('/api/paypal.php?action=capture_order', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ order_id: data.orderID })
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
              if (res.success) {
                showSuccess('paypal', res);
              } else {
                throw new Error(res.error || 'No se pudo capturar el pago.');
              }
            });
          },

          onError: function (err) {
            console.error('PayPal error:', err);
            setStatus('❌ Error con PayPal. ' + (err && err.message ? err.message : 'Intenta de nuevo.'), 'error');
          },

          onCancel: function () {
            setStatus('Pago cancelado por el usuario.', 'info');
          }
        });

        paypalButtonsInstance.render('#paypal-buttons-container').catch(function (e) {
          setStatus('❌ No se pudieron renderizar los botones de PayPal: ' + e.message, 'error');
        });
      })
      .catch(function (e) {
        setStatus('❌ ' + e.message, 'error');
      });
  }

  function showSuccess(method, info) {
    var amount = info.amount || payload().amount;
    var cur    = info.currency || selectedCurrency;
    var refs = {
      paypal:      'PayPal · Orden ' + (info.order_id || ''),
      webpay:      'Webpay · ' + (info.authorization_code || ''),
      mercadopago: 'MercadoPago · ' + (info.payment_id || '')
    };
    setStatus(
      '<h3>✅ ¡Pago confirmado!</h3>' +
      '<p>Gracias por tu reserva. Te enviaremos un email con el detalle a <strong>' +
        $('payerEmail').value + '</strong>.</p>' +
      '<p><strong>Monto:</strong> ' + amount + ' ' + cur + '<br/>' +
      '<strong>Referencia:</strong> ' + (refs[method] || method) + '</p>' +
      '<p><a class="cdski-pago-cta-link" href="/">Volver a clasesdeski.cl</a></p>',
      'success'
    );
    form.hidden = true;
    document.querySelector('.cdski-pago-methods').hidden = true;
  }

  submitBtn.addEventListener('click', function () {
    if (selectedMethod === 'webpay')      payWithWebpay();
    else if (selectedMethod === 'mercadopago') payWithMercadoPago();
  });

  function handleReturnUrl() {
    var params = new URLSearchParams(location.search);
    var method = params.get('method');
    var status = params.get('status');

    if (method === 'webpay' && status === 'return') {
      var token = params.get('token_ws');
      if (!token) return;
      setStatus('Confirmando pago Webpay…', 'info');
      fetch('/api/webpay.php?action=commit_transaction', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token: token })
      })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.success && res.status === 'AUTHORIZED') {
          showSuccess('webpay', res);
        } else {
          setStatus('❌ Pago Webpay rechazado o cancelado. ' + (res.error || ''), 'error');
        }
      })
      .catch(function (e) {
        setStatus('❌ Error confirmando pago Webpay: ' + e.message, 'error');
      });
    }

    else if (method === 'mp') {
      if (status === 'success') {
        var pid = params.get('payment_id');
        showSuccess('mercadopago', { payment_id: pid });
      } else if (status === 'failure') {
        setStatus('❌ Pago MercadoPago rechazado o cancelado.', 'error');
      } else if (status === 'pending') {
        setStatus('⏳ Pago MercadoPago en revisión. Te avisaremos por email cuando se confirme.', 'info');
      }
    }
  }
  handleReturnUrl();

})();
