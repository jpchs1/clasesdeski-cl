/**
 * ClasesdeSki — Portal de Pago v2.0 (Super PRO UX)
 *
 * Enhanced over v1.0:
 *  + Live order summary updates as user types (amount, description, name+email)
 *  + Live CLP↔USD conversion preview
 *  + Stepper progresses based on form validity / method selection
 *  + Inline field validation with error messages
 *  + Smooth UX: auto-scroll to method section after form complete
 *
 * Integrates 3 payment processors against clasesdeski.cl/api/*.php:
 *   - Webpay Plus       POST /api/webpay.php?action=create_transaction → redirect form
 *   - MercadoPago       POST /api/mercadopago.php?action=create_preference → redirect to init_point
 *   - PayPal Smart Btns GET  /api/paypal.php?action=get_client_id + create_order/capture_order
 */
(function () {
  'use strict';

  var USD_RATE = Number(window.CDSKI_USD_RATE || 950);

  var $ = function (id) { return document.getElementById(id); };
  var $$ = function (sel) { return document.querySelectorAll(sel); };

  var form          = $('cdski-pago-form');
  var amountInput   = $('amount');
  var amountCurLbl  = $('amountCurrencyLabel');
  var amountPrefix  = $('amountPrefix');
  var helpClp       = document.querySelector('[data-clp]');
  var helpUsd       = document.querySelector('[data-usd]');
  var statusEl      = $('cdski-pago-status');
  var submitBtn     = $('cdski-pago-submit');
  var submitBtnText = submitBtn ? submitBtn.querySelector('.cdski-pago-btn-text') : null;
  var ppContainer   = $('paypal-buttons-container');
  var methodButtons = $$('.cdski-pago-method-card');
  var acceptTerms   = $('acceptTerms');

  var summaryDesc    = $('summaryDescription');
  var summaryClient  = $('summaryClient');
  var summaryAmt     = $('summaryAmount');
  var summaryCur     = $('summaryCurrency');
  var summaryConv    = $('summaryConversion');
  var summaryConvVal = $('summaryConversionValue');

  var step1 = document.querySelector('.cdski-step[data-step="1"]');
  var step2 = document.querySelector('.cdski-step[data-step="2"]');
  var step3 = document.querySelector('.cdski-step[data-step="3"]');
  var line12 = document.querySelector('.cdski-step-line[data-line="1-2"]');
  var line23 = document.querySelector('.cdski-step-line[data-line="2-3"]');

  var selectedMethod   = null;
  var selectedCurrency = 'CLP';
  var paypalButtonsInstance = null;

  function formatNumber(n, cur) {
    n = Number(n) || 0;
    if (cur === 'USD' || cur === 'EUR') {
      return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    return Math.round(n).toLocaleString('es-CL').replace(/,/g, '.');
  }
  function parseAmount(str) {
    if (!str) return 0;
    return Number(String(str).replace(/[^\d.,]/g, '').replace(/\./g, '').replace(/,/g, '.')) || 0;
  }
  function clpToUsd(clp) { return Math.max(1, +((clp / USD_RATE).toFixed(2))); }
  function usdToClp(usd) { return Math.round(usd * USD_RATE); }
  function setStatus(html, kind) {
    if (!statusEl) return;
    statusEl.hidden = false;
    statusEl.className = 'cdski-pago-status cdski-pago-status-' + (kind || 'info');
    statusEl.innerHTML = html;
    statusEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  function clearStatus() { if (statusEl) { statusEl.hidden = true; statusEl.innerHTML = ''; } }

  function showFieldError(fieldId, msg) {
    var input = document.getElementById(fieldId);
    if (!input) return;
    var field = input.closest('.cdski-pago-field');
    if (!field) return;
    field.classList.add('has-error');
    var err = field.querySelector('[data-error-for="' + fieldId + '"]');
    if (err) err.textContent = msg;
  }
  function clearFieldError(fieldId) {
    var input = document.getElementById(fieldId);
    if (!input) return;
    var field = input.closest('.cdski-pago-field');
    if (field) field.classList.remove('has-error');
  }
  function validateField(fieldId) {
    var input = $(fieldId);
    if (!input) return true;
    var val = input.value.trim();
    var ok = true, msg = '';
    if (input.required && !val) { ok = false; msg = 'Campo requerido.'; }
    else if (fieldId === 'payerEmail' && val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
      ok = false; msg = 'Email inválido.';
    }
    else if (fieldId === 'amount') {
      var amt = parseAmount(val);
      if (amt < 1) { ok = false; msg = 'Monto inválido.'; }
      else if (selectedCurrency === 'CLP' && amt < 50) { ok = false; msg = 'Monto mínimo: $50 CLP.'; }
    }
    if (ok) clearFieldError(fieldId);
    else showFieldError(fieldId, msg);
    return ok;
  }
  function isFormValid() {
    if (!form.checkValidity()) return false;
    if (!acceptTerms.checked) return false;
    var amt = parseAmount(amountInput.value);
    if (amt < 1) return false;
    if (selectedCurrency === 'CLP' && amt < 50) return false;
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($('payerEmail').value.trim())) return false;
    return true;
  }
  function validateAllFields() {
    var ok = true;
    ['payerName', 'payerEmail', 'description', 'amount'].forEach(function (id) {
      if (!validateField(id)) ok = false;
    });
    if (!acceptTerms.checked) ok = false;
    return ok;
  }

  function updateSummary() {
    var desc  = $('description').value.trim();
    var name  = $('payerName').value.trim();
    var email = $('payerEmail').value.trim();
    var amt   = parseAmount(amountInput.value);

    if (summaryDesc) summaryDesc.textContent = desc || '—';
    if (summaryClient) {
      var who = name || email || '—';
      if (name && email) who = name + ' · ' + email;
      summaryClient.textContent = who;
    }
    if (summaryAmt) summaryAmt.textContent = (selectedCurrency === 'USD' ? 'US$ ' : '$') + formatNumber(amt, selectedCurrency);
    if (summaryCur) summaryCur.textContent = selectedCurrency;

    if (summaryConv && summaryConvVal && amt > 0) {
      if (selectedCurrency === 'CLP') {
        summaryConv.hidden = false;
        summaryConvVal.textContent = '≈ US$ ' + formatNumber(clpToUsd(amt), 'USD');
      } else if (selectedCurrency === 'USD') {
        summaryConv.hidden = false;
        summaryConvVal.textContent = '≈ $' + formatNumber(usdToClp(amt), 'CLP') + ' CLP';
      } else {
        summaryConv.hidden = true;
      }
    } else if (summaryConv) {
      summaryConv.hidden = true;
    }
  }

  function updateStepper() {
    var formOk = isFormValid();
    var methodOk = !!selectedMethod;

    step1.classList.toggle('is-complete', formOk);
    step1.classList.toggle('is-active', !formOk);

    step2.classList.toggle('is-active', formOk && !methodOk);
    step2.classList.toggle('is-complete', formOk && methodOk);

    step3.classList.toggle('is-active', formOk && methodOk);

    line12.classList.toggle('is-complete', formOk);
    line23.classList.toggle('is-complete', formOk && methodOk);

    if (submitBtnText && submitBtn) {
      submitBtn.disabled = !(formOk && methodOk);
      if (!formOk) {
        submitBtnText.textContent = 'Completa tus datos para continuar';
      } else if (!methodOk) {
        submitBtnText.textContent = 'Selecciona un método de pago';
      } else if (selectedMethod === 'paypal') {
        submitBtnText.textContent = '↓ Usa los botones de PayPal abajo';
        submitBtn.disabled = true;
      } else {
        submitBtnText.textContent = selectedMethod === 'webpay'
          ? 'Pagar con Webpay Plus →'
          : 'Pagar con MercadoPago →';
      }
    }
  }

  function updateCurrencyUI(cur) {
    selectedCurrency = cur;
    if (amountCurLbl) amountCurLbl.textContent = cur;
    if (amountPrefix) amountPrefix.textContent = (cur === 'USD' ? 'US$' : '$');
    if (helpClp) helpClp.hidden = (cur !== 'CLP');
    if (helpUsd) helpUsd.hidden = (cur !== 'USD');
    updateSummary();
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

    if (method === 'paypal') {
      ppContainer.hidden = false;
      mountPayPalButtons();
    } else {
      ppContainer.hidden = true;
    }
    updateStepper();
    clearStatus();
  }

  methodButtons.forEach(function (b) {
    b.addEventListener('click', function () { selectMethod(b.dataset.method); });
  });

  ['payerName', 'payerEmail', 'payerPhone', 'bookingCode', 'description'].forEach(function (id) {
    var el = $(id);
    if (!el) return;
    el.addEventListener('input', function () { updateSummary(); updateStepper(); });
    el.addEventListener('blur', function () { validateField(id); });
  });
  amountInput.addEventListener('input', function () {
    if (selectedCurrency === 'CLP') {
      var raw = amountInput.value.replace(/\D/g, '');
      if (raw) {
        var n = parseInt(raw, 10);
        amountInput.value = n.toLocaleString('es-CL').replace(/,/g, '.');
      } else {
        amountInput.value = '';
      }
    }
    updateSummary();
    updateStepper();
  });
  amountInput.addEventListener('blur', function () { validateField('amount'); });
  acceptTerms.addEventListener('change', updateStepper);

  function payWithWebpay() {
    if (!validateAllFields()) {
      setStatus('⚠ Revisa los campos marcados antes de continuar.', 'error');
      return;
    }

    setStatus('Generando transacción Webpay…', 'info');
    submitBtn.disabled = true;

    fetch('/api/webpay.php?action=create_transaction', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        amount:       parseAmount(amountInput.value),
        description:  $('description').value.trim(),
        booking_code: $('bookingCode').value.trim(),
        payer_email:  $('payerEmail').value.trim(),
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
      inp.type = 'hidden';
      inp.name = 'token_ws';
      inp.value = data.token;
      f.appendChild(inp);
      document.body.appendChild(f);
      setStatus('Redirigiendo a Webpay…', 'info');
      f.submit();
    })
    .catch(function (e) {
      updateStepper();
      setStatus('❌ ' + (e.message || 'Error con Webpay.'), 'error');
    });
  }

  function payWithMercadoPago() {
    if (!validateAllFields()) {
      setStatus('⚠ Revisa los campos marcados antes de continuar.', 'error');
      return;
    }

    setStatus('Generando preferencia MercadoPago…', 'info');
    submitBtn.disabled = true;

    fetch('/api/mercadopago.php?action=create_preference', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        payer_name:   $('payerName').value.trim(),
        payer_email:  $('payerEmail').value.trim(),
        booking_code: $('bookingCode').value.trim(),
        description:  $('description').value.trim(),
        amount:       parseAmount(amountInput.value),
      }),
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
      updateStepper();
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
    ppContainer.innerHTML = '<div style="text-align:center;color:#475569;font-size:.85rem;padding:14px;">Cargando PayPal…</div>';

    fetch('/api/paypal.php?action=get_client_id')
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.client_id) throw new Error('PayPal client_id no disponible.');
        return loadPayPalSdk(data.client_id);
      })
      .then(function () {
        ppContainer.innerHTML = '';
        paypalButtonsInstance = window.paypal.Buttons({
          style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'pay', height: 48 },

          onClick: function (data, actions) {
            if (!validateAllFields()) {
              setStatus('⚠ Completa todos los campos antes de pagar.', 'error');
              return actions.reject();
            }
            return actions.resolve();
          },

          createOrder: function () {
            var amt = parseAmount(amountInput.value);
            var usdAmount = (selectedCurrency === 'USD') ? amt : clpToUsd(amt);

            return fetch('/api/paypal.php?action=create_order', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                amount:       usdAmount,
                currency:     'USD',
                description:  $('description').value.trim(),
                booking_code: $('bookingCode').value.trim(),
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
            setStatus('❌ Error con PayPal. Intentá de nuevo o usá otro método.', 'error');
          },

          onCancel: function () {
            setStatus('Pago cancelado.', 'info');
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
    var amount = info.amount || parseAmount(amountInput.value);
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
      '<p>' +
        '<a class="cdski-pago-cta-link" href="/">Volver a clasesdeski.cl</a> ' +
        '<a class="cdski-pago-cta-link" href="https://wa.me/56940211459?text=Hola%2C%20acabo%20de%20pagar.%20Ref%3A%20' +
        encodeURIComponent(refs[method] || method) + '" target="_blank" style="background:#25d366">📱 Avisar por WhatsApp</a>' +
      '</p>',
      'success'
    );
    form.hidden = true;
    document.querySelector('.cdski-pago-summary-card').hidden = true;
    document.querySelector('.cdski-pago-methods-section').hidden = true;
    document.querySelector('.cdski-pago-cta-section').hidden = true;

    step1.classList.add('is-complete'); step1.classList.remove('is-active');
    step2.classList.add('is-complete'); step2.classList.remove('is-active');
    step3.classList.add('is-complete'); step3.classList.remove('is-active');
    line12.classList.add('is-complete');
    line23.classList.add('is-complete');
  }

  submitBtn.addEventListener('click', function () {
    if (selectedMethod === 'webpay')      payWithWebpay();
    else if (selectedMethod === 'mercadopago') payWithMercadoPago();
  });

  function handleReturnUrl() {
    var params = new URLSearchParams(location.search);
    var method = params.get('method');
    var status = params.get('status');

    var qAmt = params.get('amount');
    var qDesc = params.get('desc') || params.get('description');
    if (qAmt) { amountInput.value = formatNumber(parseAmount(qAmt), 'CLP'); updateSummary(); }
    if (qDesc) { $('description').value = qDesc; updateSummary(); }

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
    else if (method === 'webpay' && status === 'abort') {
      setStatus('⚠ Pago cancelado en Webpay. Podés intentar nuevamente.', 'info');
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
  updateSummary();
  updateStepper();

})();
