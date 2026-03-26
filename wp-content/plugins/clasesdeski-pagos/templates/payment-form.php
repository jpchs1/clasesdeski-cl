<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="cdski-pay">
    <div class="cdski-pay__card">

        <!-- Header -->
        <div class="cdski-pay__header">
            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2023/07/Logo1.png' ) ); ?>" alt="CDSKI" width="56" height="56">
            <h2>Pago Seguro</h2>
            <p>Completa tu abono o pago de forma rapida y segura.</p>
        </div>

        <form id="cdski-pago-form" novalidate>

            <!-- 1. Nombre -->
            <div class="cdski-pay__field">
                <label for="cdski-nombre">Nombre completo</label>
                <input type="text" id="cdski-nombre" name="nombre" placeholder="Ej: Maria Gonzalez">
            </div>

            <!-- 2. Email -->
            <div class="cdski-pay__field">
                <label for="cdski-email">Email</label>
                <input type="email" id="cdski-email" name="email" placeholder="Ej: maria@correo.cl">
            </div>

            <!-- 3. WhatsApp -->
            <div class="cdski-pay__field">
                <label for="cdski-telefono">WhatsApp</label>
                <input type="tel" id="cdski-telefono" name="telefono" placeholder="Ej: +56 9 1234 5678">
            </div>

            <!-- 4. Concepto -->
            <div class="cdski-pay__field">
                <label for="cdski-concepto">Concepto del pago</label>
                <input type="text" id="cdski-concepto" name="concepto" placeholder="Ej: Clase de ski intermedio — Valle Nevado">
            </div>

            <!-- 5. Reserva + Fecha (row) -->
            <div class="cdski-pay__row">
                <div class="cdski-pay__field">
                    <label for="cdski-reserva">N° de reserva <span class="cdski-pay__opt">(opcional)</span></label>
                    <input type="text" id="cdski-reserva" name="reserva" placeholder="Ej: RES-2024-001">
                </div>
                <div class="cdski-pay__field">
                    <label for="cdski-fecha-clase">Fecha de clase</label>
                    <input type="date" id="cdski-fecha-clase" name="fecha_clase">
                </div>
            </div>

            <!-- Divider -->
            <hr class="cdski-pay__divider">

            <!-- 6. Monto -->
            <div class="cdski-pay__field">
                <label for="cdski-amount">Monto a pagar <span class="cdski-pay__req">*</span></label>
                <div class="cdski-pay__amount">
                    <span class="cdski-pay__currency">$</span>
                    <input type="text" id="cdski-amount" name="amount" inputmode="numeric" placeholder="50.000" required autocomplete="off">
                    <span class="cdski-pay__clp">CLP</span>
                </div>
                <small class="cdski-pay__hint">Monto minimo: $1.000 CLP</small>
            </div>

            <!-- 7. Medio de pago -->
            <div class="cdski-pay__field">
                <label>Medio de pago <span class="cdski-pay__req">*</span></label>
                <div class="cdski-pay__gateways">

                    <label class="cdski-pay__gw">
                        <input type="radio" name="gateway" value="webpay">
                        <div class="cdski-pay__gw-box">
                            <div class="cdski-pay__gw-dot"></div>
                            <div class="cdski-pay__gw-text">
                                <strong>Webpay Plus</strong>
                                <span>Debito y credito nacional</span>
                            </div>
                            <div class="cdski-pay__gw-brands">
                                <i class="cdski-brand cdski-brand--visa">VISA</i>
                                <i class="cdski-brand cdski-brand--mc">MC</i>
                                <i class="cdski-brand cdski-brand--rc">RC</i>
                            </div>
                        </div>
                    </label>

                    <label class="cdski-pay__gw">
                        <input type="radio" name="gateway" value="mercadopago">
                        <div class="cdski-pay__gw-box">
                            <div class="cdski-pay__gw-dot"></div>
                            <div class="cdski-pay__gw-text">
                                <strong>Mercado Pago</strong>
                                <span>Tarjetas, transferencia o saldo</span>
                            </div>
                            <div class="cdski-pay__gw-brands">
                                <i class="cdski-brand cdski-brand--mp">MP</i>
                            </div>
                        </div>
                    </label>

                    <label class="cdski-pay__gw">
                        <input type="radio" name="gateway" value="paypal">
                        <div class="cdski-pay__gw-box">
                            <div class="cdski-pay__gw-dot"></div>
                            <div class="cdski-pay__gw-text">
                                <strong>PayPal</strong>
                                <span>Pago internacional · Requiere cuenta PayPal</span>
                            </div>
                            <div class="cdski-pay__gw-brands">
                                <i class="cdski-brand cdski-brand--pp">PP</i>
                            </div>
                        </div>
                    </label>

                </div>
            </div>

            <!-- 8. Resumen -->
            <div class="cdski-pay__summary" id="cdski-summary" style="display:none;">
                <div class="cdski-pay__summary-row">
                    <span>Resumen del pago</span>
                    <strong id="cdski-summary-amount">$0 CLP</strong>
                </div>
                <div class="cdski-pay__summary-sub" id="cdski-summary-concepto"></div>
            </div>

            <!-- 9. Submit -->
            <button type="submit" id="cdski-submit-btn" class="cdski-pay__btn" disabled>
                <span class="cdski-btn-text">Pagar ahora</span>
                <span class="cdski-btn-spinner" style="display:none;">
                    <svg width="18" height="18" viewBox="0 0 18 18"><circle cx="9" cy="9" r="7" stroke="#fff" stroke-width="2" fill="none" stroke-dasharray="35" stroke-dashoffset="8"><animateTransform attributeName="transform" type="rotate" from="0 9 9" to="360 9 9" dur=".7s" repeatCount="indefinite"/></circle></svg>
                    Procesando...
                </span>
            </button>

            <div id="cdski-pago-error" class="cdski-pay__error" style="display:none;"></div>

            <!-- 10. Trust -->
            <div class="cdski-pay__trust">
                <span><svg width="13" height="13" viewBox="0 0 14 14" fill="none"><path d="M7 1L2 3.5V6.5C2 9.55 4.13 12.36 7 13C9.87 12.36 12 9.55 12 6.5V3.5L7 1Z" fill="#16a34a" opacity=".2" stroke="#16a34a" stroke-width="1.2"/><path d="M5 7l1.5 1.5L9 6" stroke="#16a34a" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Pago seguro</span>
                <span><svg width="13" height="13" viewBox="0 0 14 14" fill="none"><rect x="1.5" y="3.5" width="11" height="8" rx="1.5" stroke="#64748b" stroke-width="1.2"/><path d="M1.5 6h11" stroke="#64748b" stroke-width="1.2"/></svg> Tarjetas nacionales e internacionales</span>
                <span><svg width="13" height="13" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="5.5" stroke="#64748b" stroke-width="1.2"/><path d="M5 7l1.5 1.5L9.5 5.5" stroke="#64748b" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Confirmacion por email o WhatsApp</span>
            </div>

        </form>
    </div>
</div>
