/**
 * ClasesdeSki — Portal de Pago v2.3.0
 *
 * v2.3.0: Loader con logo CDSKI (overlay animado) al conectar con Webpay/
 *         MercadoPago, al abrir el formulario de tarjeta y al confirmar PayPal;
 *         skeleton animado con logo mientras carga el SDK de PayPal.
 * v2.2.0: Detección de cliente extranjero por prefijo telefónico.
 *         - isChileanPhone(): teléfono chileno solo si (4+ dígitos y empieza con "56");
 *           teléfono vacío/corto se trata como local.
 *         - Cliente extranjero → oculta métodos nacionales (Webpay/MercadoPago) y deja
 *           solo PayPal; auto-selecciona PayPal si el método activo quedó oculto.
 *           Filtro reversible: al volver a +56 reaparecen los métodos nacionales.
 *         - Conversión de monto automática al cambiar de moneda (no se borra el monto).
 *         - Prefill por querystring: ?pais= (payer_country), nombre/email/telefono/reserva
 *           y ?metodo= para preselección de método.
 * v2.1.3: USD_RATE default 950 → 870 (tipo de cambio CLP/USD actualizado).
 * v2.1.2: Fix "window.paypal.isFundingEligible is not a function".
 * v2.1.1: amount US$ prefix overlap fix (CSS only).
 * v2.1:   PayPal Smart Buttons split into 2 labeled funding sources.
 * v2.0:   live summary, stepper, inline validation, URL prefill.
 */
(function () {
  'use strict';

  var USD_RATE = Number(window.CDSKI_USD_RATE || 870);

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
  var ppBtnPayPal   = $('paypal-btn-paypal');
  var ppBtnCard     = $('paypal-btn-card');
  var methodButtons = $$('.cdski-pago-method-card');
  var phoneInput    = $('payerPhone');
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
  var paypalInstances  = [];
  var payerCountry     = '';   // país de facturación (prefill ?pais=) → payer_country en PayPal

  function formatNumber(n, cur) {
    n = Number(n) || 0;
    if (cur === 'USD' || cur === 'EUR') {
      return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    return Math.round(n).toLocaleString('es-CL').replace(/,/g, '.');
  }
  function parseAmount(str) {
    if (!str) return 0;
    var s = String(str).replace(/[^\d.,]/g, '');
    if (selectedCurrency === 'USD') {
      // USD: punto o coma es separador decimal; se conserva la parte decimal.
      s = s.replace(/,/g, '.');
      var parts = s.split('.');
      if (parts.length > 2) s = parts[0] + '.' + parts.slice(1).join('');
      return Number(s) || 0;
    }
    // CLP: puntos/comas son separadores de miles.
    return Number(s.replace(/[.,]/g, '')) || 0;
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

  // ── Loader con logo CDSKI ───────────────────────────────────────────────────
  // Overlay a pantalla completa con el logo de CDSKI girando. Se usa al conectar
  // con Webpay/MercadoPago, al abrir el formulario de tarjeta y al confirmar PayPal.
  var LOGO_SRC = '/images/logo-cdski.png';
  var loaderEl = null, loaderMsgTimer = null, loaderSafetyTimer = null;

  function buildLoader() {
    if (loaderEl) return loaderEl;
    loaderEl = document.createElement('div');
    loaderEl.className = 'cdski-pago-loader';
    loaderEl.setAttribute('role', 'status');
    loaderEl.setAttribute('aria-live', 'polite');
    loaderEl.hidden = true;
    loaderEl.innerHTML =
      '<div class="cdski-pago-loader-card">' +
        '<div class="cdski-pago-loader-orbit">' +
          '<span class="cdski-pago-loader-ring"></span>' +
          '<img class="cdski-pago-loader-logo" src="' + LOGO_SRC + '" alt="CDSKI" />' +
        '</div>' +
        '<strong class="cdski-pago-loader-title">Procesando</strong>' +
        '<small class="cdski-pago-loader-sub">Conexión cifrada · no cierres esta página</small>' +
      '</div>';
    document.body.appendChild(loaderEl);
    return loaderEl;
  }

  function showLoader(messages, sub) {
    buildLoader();
    var titleEl = loaderEl.querySelector('.cdski-pago-loader-title');
    var subEl   = loaderEl.querySelector('.cdski-pago-loader-sub');
    var msgs = Array.isArray(messages) ? messages : [messages || 'Procesando'];
    var i = 0;
    if (titleEl) titleEl.textContent = msgs[0];
    if (subEl && sub) subEl.textContent = sub;
    loaderEl.hidden = false;
    document.body.classList.add('cdski-pago-loading');
    if (loaderMsgTimer) clearInterval(loaderMsgTimer);
    if (msgs.length > 1) {
      loaderMsgTimer = setInterval(function () {
        i = (i + 1) % msgs.length;
        if (titleEl) titleEl.textContent = msgs[i];
      }, 2200);
    }
    // Seguridad: nunca dejar el overlay pegado si algo se cuelga (los flujos que
    // redirigen navegan antes de este plazo).
    if (loaderSafetyTimer) clearTimeout(loaderSafetyTimer);
    loaderSafetyTimer = setTimeout(hideLoader, 25000);
  }

  function hideLoader() {
    if (loaderMsgTimer) { clearInterval(loaderMsgTimer); loaderMsgTimer = null; }
    if (loaderSafetyTimer) { clearTimeout(loaderSafetyTimer); loaderSafetyTimer = null; }
    if (loaderEl) loaderEl.hidden = true;
    document.body.classList.remove('cdski-pago-loading');
  }

  // Skeleton inline (dentro del área de botones PayPal) mientras carga el SDK.
  var skelTimer = null;
  function paypalSkelHtml(msg) {
    return '<div class="cdski-pago-skel">' +
        '<div class="cdski-pago-loader-orbit cdski-pago-loader-orbit-sm">' +
          '<span class="cdski-pago-loader-ring"></span>' +
          '<img class="cdski-pago-loader-logo" src="' + LOGO_SRC + '" alt="" />' +
        '</div>' +
        '<div class="cdski-pago-skel-caption">' +
          '<span class="cdski-pago-skel-msg">' + (msg || 'Conectando con PayPal') + '</span>' +
          '<small>Conexión cifrada · no cierres esta página</small>' +
        '</div>' +
        '<div class="cdski-pago-skel-rows"><span></span><span></span></div>' +
      '</div>';
  }
  function startSkelRotation() {
    var msgs = ['Conectando con PayPal', 'Verificando pago seguro', 'Preparando botones', 'Un momento más'];
    var i = 0;
    stopSkelRotation();
    skelTimer = setInterval(function () {
      i = (i + 1) % msgs.length;
      var el = ppBtnPayPal && ppBtnPayPal.querySelector('.cdski-pago-skel-msg');
      if (el) el.textContent = msgs[i];
    }, 2200);
  }
  function stopSkelRotation() { if (skelTimer) { clearInterval(skelTimer); skelTimer = null; } }

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

  // ── Detección de cliente extranjero por prefijo telefónico ──────────────────
  // Limpia todo lo que no sea dígito. Con menos de 4 dígitos se asume LOCAL
  // (para no ocultar métodos mientras el usuario todavía escribe). Con 4+ dígitos
  // es chileno solo si empieza con "56". Detecta igual formatos con espacios/símbolos
  // como "+56 9 1234 5678".
  function isChileanPhone(value) {
    var digits = String(value == null ? '' : value).replace(/\D/g, '');
    if (digits.length < 4) return true;
    return digits.indexOf('56') === 0;
  }

  // Muestra/oculta los métodos nacionales según el teléfono. Reversible.
  function applyPhoneFilter() {
    if (!phoneInput) return;
    var chilean = isChileanPhone(phoneInput.value);
    methodButtons.forEach(function (b) {
      if (b.dataset.method === 'paypal') return;   // PayPal (internacional) siempre visible
      b.style.display = chilean ? '' : 'none';
    });
    // Si el método seleccionado quedó oculto, forzar PayPal.
    if (!chilean && selectedMethod && selectedMethod !== 'paypal') {
      selectMethod('paypal');
    }
  }

  function updateCurrencyUI(cur) {
    var prevCur = selectedCurrency;
    var converted = false;

    // No borrar el monto: convertirlo con la tasa referencial al cambiar de moneda.
    if (cur !== prevCur) {
      var amt = parseAmount(amountInput.value);   // interpretado en prevCur
      if (amt > 0) {
        var newAmt = amt;
        if (cur === 'USD' && prevCur === 'CLP')      newAmt = clpToUsd(amt);
        else if (cur === 'CLP' && prevCur === 'USD') newAmt = usdToClp(amt);
        amountInput.value = (cur === 'USD')
          ? String(newAmt)
          : Math.round(newAmt).toLocaleString('es-CL').replace(/,/g, '.');
        converted = true;
      }
    }

    selectedCurrency = cur;
    if (amountCurLbl) amountCurLbl.textContent = cur;
    if (amountPrefix) amountPrefix.textContent = (cur === 'USD' ? 'US$' : '$');
    if (helpClp) helpClp.hidden = (cur !== 'CLP');
    if (helpUsd) helpUsd.hidden = (cur !== 'USD');
    updateSummary();

    // Mensaje informativo (solo si efectivamente cambió la moneda).
    if (cur !== prevCur) {
      if (converted) {
        setStatus('💱 Monto convertido a ' + cur + ' con tasa referencial ($' +
          USD_RATE.toLocaleString('es-CL') + '/USD) — puedes ajustarlo.', 'info');
      } else if (cur === 'USD') {
        setStatus('🌎 Pago internacional: ingrese el monto en USD (dólares estadounidenses).', 'info');
      } else {
        clearStatus();
      }
    }
  }

  function selectMethod(method) {
    selectedMethod = method;
    methodButtons.forEach(function (b) {
      var active = b.dataset.method === method;
      b.classList.toggle('is-active', active);
      b.setAttribute('aria-pressed', active ? 'true' : 'false');
    });

    var cur = (method === 'paypal') ? 'USD' : 'CLP';
    updateCurrencyUI(cur);   // convierte el monto y muestra el mensaje si corresponde

    if (method === 'paypal') {
      ppContainer.hidden = false;
      mountPayPalButtons();
    } else {
      ppContainer.hidden = true;
    }
    updateStepper();
  }

  methodButtons.forEach(function (b) {
    b.addEventListener('click', function () { selectMethod(b.dataset.method); });
  });

  // Re-evaluar el filtro de métodos en cada cambio del teléfono.
  if (phoneInput) {
    phoneInput.addEventListener('input', applyPhoneFilter);
    phoneInput.addEventListener('change', applyPhoneFilter);
  }

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
    showLoader(['Conectando con Webpay', 'Generando transacción segura', 'Te estamos redirigiendo'],
      'Transbank · conexión cifrada');

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
      hideLoader();
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
    showLoader(['Conectando con MercadoPago', 'Generando preferencia segura', 'Te estamos redirigiendo'],
      'MercadoPago · conexión cifrada');

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
      hideLoader();
      updateStepper();
      setStatus('❌ ' + (e.message || 'Error con MercadoPago.'), 'error');
    });
  }

  function loadPayPalSdk(clientId) {
    return new Promise(function (resolve, reject) {
      if (window.paypal) return resolve();
      var s = document.createElement('script');
      s.src = 'https://www.paypal.com/sdk/js?client-id=' + encodeURIComponent(clientId) +
              '&currency=USD&intent=capture&commit=true' +
              '&enable-funding=card&disable-funding=paylater,credit' +
              '&components=buttons&locale=es_CL';
      s.onload  = function () { resolve(); };
      s.onerror = function () { reject(new Error('No se pudo cargar el SDK de PayPal.')); };
      document.head.appendChild(s);
    });
  }

  function paypalCallbacks() {
    return {
      onClick: function (data, actions) {
        if (!validateAllFields()) {
          setStatus('⚠ Completa todos los campos antes de pagar.', 'error');
          return actions.reject();
        }
        // Al pagar con tarjeta: loader breve mientras PayPal abre el formulario seguro.
        if (data && data.fundingSource === 'card') {
          showLoader(['Abriendo formulario seguro de tarjeta', 'Cifrado de extremo a extremo'],
            'No cierres esta página');
          setTimeout(hideLoader, 2600);
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
            amount:        usdAmount,
            currency:      'USD',
            description:   $('description').value.trim(),
            booking_code:  $('bookingCode').value.trim(),
            payer_country: payerCountry,   // prefill ?pais= → país de facturación
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
        showLoader(['Confirmando tu pago', 'Verificando con PayPal'], 'Casi listo · no cierres esta página');
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
        hideLoader();
        console.error('PayPal error:', err);
        setStatus('❌ Error con PayPal. Intentá de nuevo o usá otro método.', 'error');
      },

      onCancel: function () {
        hideLoader();
        setStatus('Pago cancelado.', 'info');
      }
    };
  }

  function mountPayPalButtons() {
    if (paypalInstances.length) {
      paypalInstances.forEach(function (inst) { try { inst.close(); } catch (e) {} });
      paypalInstances = [];
    }
    if (ppBtnPayPal) ppBtnPayPal.innerHTML = '';
    if (ppBtnCard)   ppBtnCard.innerHTML   = '';

    if (ppBtnPayPal) ppBtnPayPal.innerHTML = paypalSkelHtml('Conectando con PayPal');
    if (ppBtnCard)   ppBtnCard.innerHTML   = paypalSkelHtml('Preparando pago con tarjeta');
    startSkelRotation();

    fetch('/api/paypal.php?action=get_client_id')
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.client_id) throw new Error('PayPal client_id no disponible.');
        return loadPayPalSdk(data.client_id);
      })
      .then(function () {
        if (!window.paypal || !window.paypal.Buttons) {
          throw new Error('SDK de PayPal no disponible.');
        }
        stopSkelRotation();
        if (ppBtnPayPal) ppBtnPayPal.innerHTML = '';
        if (ppBtnCard)   ppBtnCard.innerHTML   = '';

        var cbs = paypalCallbacks();
        var FUNDING = window.paypal.FUNDING || {};

        if (FUNDING.PAYPAL && ppBtnPayPal) {
          try {
            var paypalBtn = window.paypal.Buttons(Object.assign({}, cbs, {
              fundingSource: FUNDING.PAYPAL,
              style: { layout: 'horizontal', color: 'gold', shape: 'rect', label: 'paypal', tagline: false, height: 48 }
            }));
            if (paypalBtn.isEligible && paypalBtn.isEligible()) {
              paypalBtn.render('#paypal-btn-paypal').catch(function (e) {
                console.error('PayPal button render error:', e);
                ppBtnPayPal.innerHTML = '<small style="color:#ef4444">No se pudo cargar.</small>';
              });
              paypalInstances.push(paypalBtn);
            } else {
              ppBtnPayPal.innerHTML = '<small style="color:#64748b">PayPal no disponible en tu región.</small>';
            }
          } catch (e) {
            console.error('PayPal Buttons construction error:', e);
            ppBtnPayPal.innerHTML = '<small style="color:#ef4444">Error: ' + e.message + '</small>';
          }
        }

        if (FUNDING.CARD && ppBtnCard) {
          try {
            var cardBtn = window.paypal.Buttons(Object.assign({}, cbs, {
              fundingSource: FUNDING.CARD,
              style: { layout: 'horizontal', color: 'black', shape: 'rect', label: 'pay', height: 48 }
            }));
            if (cardBtn.isEligible && cardBtn.isEligible()) {
              cardBtn.render('#paypal-btn-card').catch(function (e) {
                console.error('Card button render error:', e);
                ppBtnCard.innerHTML = '<small style="color:#ef4444">No se pudo cargar.</small>';
              });
              paypalInstances.push(cardBtn);
            } else {
              ppBtnCard.innerHTML = '<small style="color:#64748b">Tarjeta no disponible en tu región. Usá PayPal arriba.</small>';
            }
          } catch (e) {
            console.error('Card Buttons construction error:', e);
            ppBtnCard.innerHTML = '<small style="color:#ef4444">Error: ' + e.message + '</small>';
          }
        }

        if (paypalInstances.length === 0) {
          setStatus('❌ No hay métodos PayPal disponibles. Intentá con Webpay o MercadoPago.', 'error');
        }
      })
      .catch(function (e) {
        stopSkelRotation();
        if (ppBtnPayPal) ppBtnPayPal.innerHTML = '';
        if (ppBtnCard)   ppBtnCard.innerHTML   = '';
        setStatus('❌ ' + e.message, 'error');
      });
  }

  function showSuccess(method, info) {
    hideLoader();
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

    // ── Prefill por querystring ──────────────────────────────────────────────
    var qName = params.get('nombre') || params.get('name');
    if (qName && $('payerName')) { $('payerName').value = qName; }
    var qEmail = params.get('email');
    if (qEmail && $('payerEmail')) { $('payerEmail').value = qEmail; }
    var qPhone = params.get('telefono') || params.get('phone');
    if (qPhone && phoneInput) { phoneInput.value = qPhone; }
    var qBooking = params.get('reserva') || params.get('booking');
    if (qBooking && $('bookingCode')) { $('bookingCode').value = qBooking; }
    // País de facturación → payer_country que se pasa a PayPal.
    payerCountry = (params.get('pais') || params.get('country') || '').trim();
    updateSummary();

    // Preselección de método por URL (?metodo=webpay|mercadopago|paypal).
    var qMethod = (params.get('metodo') || '').toLowerCase();
    if (qMethod === 'webpay' || qMethod === 'mercadopago' || qMethod === 'paypal') {
      selectMethod(qMethod);
    }
    // Aplicar el filtro por teléfono: si el cliente prellenado es extranjero,
    // oculta métodos nacionales y fuerza PayPal (aunque venga preseleccionado nacional).
    applyPhoneFilter();

    if (method === 'webpay' && status === 'return') {
      var token = params.get('token_ws');
      if (!token) return;
      setStatus('Confirmando pago Webpay…', 'info');
      showLoader(['Confirmando tu pago', 'Verificando con Transbank'], 'Casi listo · no cierres esta página');
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
          hideLoader();
          setStatus('❌ Pago Webpay rechazado o cancelado. ' + (res.error || ''), 'error');
        }
      })
      .catch(function (e) {
        hideLoader();
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
