<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card">

        <!-- Header -->
        <div class="cdski-pago-header">
            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2023/07/Logo1.png' ) ); ?>" alt="CDSKI" class="cdski-pago-logo-img" width="72" height="72">
            <h2>Pago Seguro de Reserva</h2>
            <p>Completa tu pago para confirmar tu clase de ski o snowboard.</p>
            <div class="cdski-trust-bar">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 1L2 3.5V6.5C2 9.55 4.13 12.36 7 13C9.87 12.36 12 9.55 12 6.5V3.5L7 1Z" fill="#d97706" stroke="#d97706" stroke-width="1"/></svg>
                <strong>Pago 100% seguro</strong> &middot; Confirmacion por email o WhatsApp
            </div>
        </div>

        <form id="cdski-pago-form" class="cdski-pago-form" novalidate>

            <!-- Monto -->
            <div class="cdski-section">
                <label class="cdski-label-main" for="cdski-amount">Monto a pagar</label>
                <div class="cdski-amount-box">
                    <span class="cdski-amount-prefix">$</span>
                    <input type="text" id="cdski-amount" name="amount" inputmode="numeric" placeholder="50.000" required autocomplete="off">
                </div>
                <span class="cdski-hint">Monto minimo: $1.000 CLP</span>
                <div class="cdski-info-banner">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="6" stroke="#6b7280" stroke-width="1.2"/><path d="M7 6.5V10M7 4.5V5" stroke="#6b7280" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Puedes pagar el monto que desees para reservar tu clase.
                </div>
            </div>

            <!-- Informacion del alumno -->
            <div class="cdski-section">
                <h3 class="cdski-section-heading">Informacion del alumno</h3>
                <div class="cdski-grid">
                    <div class="cdski-field">
                        <label for="cdski-nombre"><strong>Nombre completo</strong></label>
                        <input type="text" id="cdski-nombre" name="nombre" placeholder="Ej: Juan Perez">
                    </div>
                    <div class="cdski-field">
                        <label for="cdski-email"><strong>Email</strong></label>
                        <input type="email" id="cdski-email" name="email" placeholder="ejemplo@correo.cl">
                    </div>
                    <div class="cdski-field">
                        <label for="cdski-telefono"><strong>WhatsApp</strong></label>
                        <input type="tel" id="cdski-telefono" name="telefono" placeholder="+56 9 1234 5678">
                    </div>
                    <div class="cdski-field">
                        <label for="cdski-reserva"><strong>Numero de reserva</strong> <span class="cdski-optional">(opcional)</span></label>
                        <input type="text" id="cdski-reserva" name="reserva" placeholder="Ej: RES-2024-001">
                    </div>
                    <div class="cdski-field">
                        <label for="cdski-fecha-clase"><strong>Fecha de clase</strong></label>
                        <input type="date" id="cdski-fecha-clase" name="fecha_clase">
                    </div>
                    <div class="cdski-field">
                        <label for="cdski-concepto"><strong>Concepto</strong></label>
                        <input type="text" id="cdski-concepto" name="concepto" placeholder="Ej: Clase de ski intermedio - Valle Nevado">
                    </div>
                </div>
            </div>

            <!-- Medio de pago -->
            <div class="cdski-section">
                <h3 class="cdski-section-heading">Selecciona tu medio de pago</h3>
                <div class="cdski-gateways">
                    <label class="cdski-gw">
                        <input type="radio" name="gateway" value="webpay">
                        <div class="cdski-gw-card">
                            <div class="cdski-gw-radio"></div>
                            <div class="cdski-gw-info">
                                <span class="cdski-gw-name">Webpay Plus</span>
                                <span class="cdski-gw-desc">Tarjetas de credito o debito nacionales</span>
                            </div>
                            <div class="cdski-gw-logos">
                                <span class="cdski-chip cdski-chip--visa">VISA</span>
                                <span class="cdski-chip cdski-chip--mc"><svg width="20" height="12" viewBox="0 0 20 12"><circle cx="7" cy="6" r="5.5" fill="#EB001B"/><circle cx="13" cy="6" r="5.5" fill="#F79E1B" opacity=".85"/></svg></span>
                                <span class="cdski-chip cdski-chip--rc">Redcompra</span>
                            </div>
                        </div>
                    </label>
                    <label class="cdski-gw">
                        <input type="radio" name="gateway" value="mercadopago">
                        <div class="cdski-gw-card">
                            <div class="cdski-gw-radio"></div>
                            <div class="cdski-gw-info">
                                <span class="cdski-gw-name">Mercado Pago</span>
                                <span class="cdski-gw-desc">Tarjetas, transferencia o saldo</span>
                            </div>
                            <div class="cdski-gw-logos">
                                <svg width="28" height="28" viewBox="0 0 28 28"><rect width="28" height="28" rx="6" fill="#00B1EA"/><text x="14" y="18" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="10" fill="#fff">MP</text></svg>
                            </div>
                        </div>
                    </label>
                    <label class="cdski-gw">
                        <input type="radio" name="gateway" value="paypal">
                        <div class="cdski-gw-card">
                            <div class="cdski-gw-radio"></div>
                            <div class="cdski-gw-info">
                                <span class="cdski-gw-name">PayPal / Tarjetas Internacionales</span>
                                <span class="cdski-gw-desc">Visa, Mastercard, Amex &middot; Requiere cuenta PayPal</span>
                            </div>
                            <div class="cdski-gw-logos">
                                <svg width="28" height="28" viewBox="0 0 28 28"><rect width="28" height="28" rx="6" fill="#003087"/><text x="14" y="18" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="11" fill="#009CDE">P</text></svg>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Resumen -->
            <div class="cdski-summary" id="cdski-summary" style="display:none;">
                <div class="cdski-summary-left">
                    <strong>Resumen de tu pago</strong>
                    <span>Clase de ski / snowboard con CDSKI Chile</span>
                </div>
                <div class="cdski-summary-amount" id="cdski-summary-amount">$0 CLP</div>
            </div>

            <!-- Submit -->
            <button type="submit" id="cdski-submit-btn" class="cdski-btn-submit" disabled>
                <span class="cdski-btn-text">PAGAR AHORA</span>
                <span class="cdski-btn-spinner" style="display:none;">
                    <svg width="20" height="20" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="#fff" stroke-width="2" fill="none" stroke-dasharray="40" stroke-dashoffset="10"><animateTransform attributeName="transform" type="rotate" from="0 10 10" to="360 10 10" dur="0.8s" repeatCount="indefinite"/></circle></svg>
                    Procesando...
                </span>
            </button>

            <div class="cdski-redirect-note">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1L1.5 3v3c0 2.75 1.92 5.32 4.5 6 2.58-.68 4.5-3.25 4.5-6V3L6 1z" stroke="#9ca3af" stroke-width="1"/></svg>
                Seras redirigido a la plataforma de pago segura.
            </div>

            <div id="cdski-pago-error" class="cdski-error-msg" style="display:none;"></div>

            <!-- Trust badges -->
            <div class="cdski-trust-badges">
                <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 1L2 3.5V6.5C2 9.55 4.13 12.36 7 13C9.87 12.36 12 9.55 12 6.5V3.5L7 1Z" fill="#16a34a" opacity=".2" stroke="#16a34a" stroke-width="1"/><path d="M5 7l1.5 1.5L9 6" stroke="#16a34a" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Pago 100% seguro</span>
                <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none"><rect x="1" y="3" width="12" height="9" rx="2" stroke="#6b7280" stroke-width="1.2"/><path d="M1 6h12" stroke="#6b7280" stroke-width="1.2"/></svg> Tarjetas nacionales e internacionales</span>
                <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="6" stroke="#6b7280" stroke-width="1.2"/><path d="M5 7l1.5 1.5L9 6" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Confirmacion inmediata</span>
            </div>
        </form>
    </div>
</div>
