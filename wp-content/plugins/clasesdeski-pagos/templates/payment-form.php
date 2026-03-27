<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card">
        <div class="cdski-pago-header">
            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2023/07/Logo1.png' ) ); ?>" alt="Clasesdeski" class="cdski-pago-logo-img" width="64" height="64">
            <h2>Pago Seguro de Reserva</h2>
            <p>Completa tu pago para confirmar tu clase de ski o snowboard.</p>
            <div class="cdski-trust-pill"><svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M7 1L2 3.5V6.5C2 9.55 4.13 12.36 7 13C9.87 12.36 12 9.55 12 6.5V3.5L7 1Z" fill="#d97706" stroke="#d97706" stroke-width="1"/></svg> <strong>Pago 100% seguro</strong> &middot; Confirmacion por email o WhatsApp</div>
        </div>

        <form id="cdski-pago-form" class="cdski-pago-form" novalidate>

            <!-- Monto -->
            <div class="cdski-field cdski-field--required cdski-field--amount">
                <label for="cdski-amount">Monto a pagar (CLP) <span class="cdski-required">*</span></label>
                <div class="cdski-input-prefix">
                    <span class="cdski-prefix">$</span>
                    <input type="text" id="cdski-amount" name="amount" inputmode="numeric"
                           placeholder="50.000" required autocomplete="off">
                </div>
                <span class="cdski-field-hint">Monto mínimo: $1.000 CLP</span>
            </div>

            <!-- Datos opcionales -->
            <div class="cdski-section-title">Datos del cliente <span class="cdski-optional-badge">opcionales</span></div>
            <div class="cdski-fields-grid">
                <div class="cdski-field">
                    <label for="cdski-nombre">Nombre</label>
                    <input type="text" id="cdski-nombre" name="nombre" placeholder="Ej: Maria Gonzalez">
                </div>

                <div class="cdski-field">
                    <label for="cdski-email">Email</label>
                    <input type="email" id="cdski-email" name="email" placeholder="ejemplo@correo.cl">
                </div>

                <div class="cdski-field">
                    <label for="cdski-telefono">WhatsApp</label>
                    <input type="tel" id="cdski-telefono" name="telefono" placeholder="+56 9 1234 5678">
                </div>

                <div class="cdski-field">
                    <label for="cdski-reserva">Número de reserva</label>
                    <input type="text" id="cdski-reserva" name="reserva" placeholder="Ej: RES-2024-001">
                </div>

                <div class="cdski-field">
                    <label for="cdski-fecha-clase">Fecha de clase</label>
                    <input type="date" id="cdski-fecha-clase" name="fecha_clase">
                </div>

                <div class="cdski-field">
                    <label for="cdski-concepto">Concepto</label>
                    <input type="text" id="cdski-concepto" name="concepto" placeholder="Ej: Clase ski intermedio — Valle Nevado">
                </div>
            </div>

            <!-- Medio de pago -->
            <div class="cdski-field cdski-field--required">
                <div class="cdski-section-title">Medio de pago <span class="cdski-required">*</span></div>
                <div class="cdski-gateways">
                    <label class="cdski-gateway-option">
                        <input type="radio" name="gateway" value="webpay">
                        <div class="cdski-gateway-card">
                            <div class="cdski-gateway-logo cdski-gateway-logo--webpay">
                                <svg viewBox="0 0 80 32" width="80" height="32">
                                    <rect width="80" height="32" rx="4" fill="#E30613"/>
                                    <text x="40" y="21" text-anchor="middle" font-family="Arial,sans-serif" font-weight="bold" font-size="12" fill="#fff">Webpay</text>
                                </svg>
                            </div>
                            <div class="cdski-gateway-info">
                                <span class="cdski-gateway-name">Webpay Plus</span>
                                <span class="cdski-gateway-desc">Tarjeta de débito o crédito</span>
                            </div>
                            <div class="cdski-gateway-cards">
                                <svg width="32" height="20" viewBox="0 0 32 20"><rect width="32" height="20" rx="3" fill="#1A1F71"/><circle cx="12" cy="10" r="6" fill="#EB001B"/><circle cx="20" cy="10" r="6" fill="#F79E1B" opacity=".8"/></svg>
                                <svg width="32" height="20" viewBox="0 0 32 20"><rect width="32" height="20" rx="3" fill="#1A1F71"/><text x="16" y="14" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="9" fill="#fff">VISA</text></svg>
                            </div>
                        </div>
                    </label>
                    <label class="cdski-gateway-option">
                        <input type="radio" name="gateway" value="mercadopago">
                        <div class="cdski-gateway-card">
                            <div class="cdski-gateway-logo cdski-gateway-logo--mp">
                                <svg viewBox="0 0 80 32" width="80" height="32">
                                    <rect width="80" height="32" rx="4" fill="#00B1EA"/>
                                    <text x="40" y="21" text-anchor="middle" font-family="Arial,sans-serif" font-weight="bold" font-size="10" fill="#fff">MercadoPago</text>
                                </svg>
                            </div>
                            <div class="cdski-gateway-info">
                                <span class="cdski-gateway-name">Mercado Pago</span>
                                <span class="cdski-gateway-desc">Tarjeta, transferencia y más</span>
                            </div>
                            <div class="cdski-gateway-cards">
                                <svg width="32" height="20" viewBox="0 0 32 20"><rect width="32" height="20" rx="3" fill="#009EE3"/><text x="16" y="14" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="10" fill="#fff">MP</text></svg>
                            </div>
                        </div>
                    </label>
                    <label class="cdski-gateway-option">
                        <input type="radio" name="gateway" value="paypal">
                        <div class="cdski-gateway-card">
                            <div class="cdski-gateway-logo cdski-gateway-logo--paypal">
                                <svg viewBox="0 0 80 32" width="80" height="32">
                                    <rect width="80" height="32" rx="4" fill="#003087"/>
                                    <text x="40" y="21" text-anchor="middle" font-family="Arial,sans-serif" font-weight="bold" font-size="12" fill="#fff">Pay<tspan fill="#009CDE">Pal</tspan></text>
                                </svg>
                            </div>
                            <div class="cdski-gateway-info">
                                <span class="cdski-gateway-name">PayPal</span>
                                <span class="cdski-gateway-desc">Tarjeta / PayPal</span>
                            </div>
                            <div class="cdski-gateway-cards">
                                <svg width="32" height="20" viewBox="0 0 32 20"><rect width="32" height="20" rx="3" fill="#1A1F71"/><circle cx="12" cy="10" r="6" fill="#EB001B"/><circle cx="20" cy="10" r="6" fill="#F79E1B" opacity=".8"/></svg>
                                <svg width="32" height="20" viewBox="0 0 32 20"><rect width="32" height="20" rx="3" fill="#1A1F71"/><text x="16" y="14" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="9" fill="#fff">VISA</text></svg>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Resumen -->
            <div class="cdski-summary" id="cdski-summary" style="display:none;">
                <span class="cdski-summary-label">Total a pagar</span>
                <span class="cdski-summary-value" id="cdski-summary-amount">$0 CLP</span>
            </div>

            <!-- PayPal Smart Buttons -->
            <div id="paypal-button-container"></div>

            <!-- Submit -->
            <div class="cdski-submit-area">
                <button type="submit" id="cdski-submit-btn" class="cdski-btn-pagar" disabled>
                    <span class="cdski-btn-text">Pagar ahora</span>
                    <span class="cdski-btn-spinner" style="display:none;">
                        <svg class="cdski-spinner-icon" width="20" height="20" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="#fff" stroke-width="2" fill="none" stroke-dasharray="40" stroke-dashoffset="10"><animateTransform attributeName="transform" type="rotate" from="0 10 10" to="360 10 10" dur="0.8s" repeatCount="indefinite"/></circle></svg>
                        Procesando...
                    </span>
                </button>
            </div>

            <div id="cdski-pago-error" class="cdski-error-msg" style="display:none;"></div>

            <p class="cdski-redirect-note"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1L1.5 3v3c0 2.75 1.92 5.32 4.5 6 2.58-.68 4.5-3.25 4.5-6V3L6 1z" stroke="#94a3b8" stroke-width="1"/></svg> Seras redirigido a la plataforma de pago segura.</p>

            <div class="cdski-trust-footer">
                <span><svg width="13" height="13" viewBox="0 0 14 14" fill="none"><path d="M7 1L2 3.5V6.5C2 9.55 4.13 12.36 7 13C9.87 12.36 12 9.55 12 6.5V3.5L7 1Z" fill="#16a34a" opacity=".2" stroke="#16a34a" stroke-width="1"/><path d="M5 7l1.5 1.5L9 6" stroke="#16a34a" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Pago seguro</span>
                <span><svg width="13" height="13" viewBox="0 0 14 14" fill="none"><rect x="1.5" y="3.5" width="11" height="8" rx="1.5" stroke="#64748b" stroke-width="1.2"/><path d="M1.5 6h11" stroke="#64748b" stroke-width="1.2"/></svg> Tarjetas nacionales e internacionales</span>
                <span><svg width="13" height="13" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="5.5" stroke="#64748b" stroke-width="1.2"/><path d="M5 7l1.5 1.5L9.5 5.5" stroke="#64748b" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Confirmacion inmediata</span>
            </div>
        </form>
    </div>
</div>
