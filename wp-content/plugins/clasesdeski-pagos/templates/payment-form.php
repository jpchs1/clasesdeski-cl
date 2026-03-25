<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card">
        <div class="cdski-pago-header">
            <h2>Abonar Clase</h2>
            <p>Ingresa los datos de tu reserva y realiza tu pago de forma segura.</p>
        </div>

        <form id="cdski-pago-form" class="cdski-pago-form" novalidate>

            <!-- Monto -->
            <div class="cdski-field cdski-field--required">
                <label for="cdski-amount">Monto a pagar (CLP) <span class="cdski-required">*</span></label>
                <div class="cdski-input-prefix">
                    <span class="cdski-prefix">$</span>
                    <input type="number" id="cdski-amount" name="amount" min="1000" step="1"
                           placeholder="Ej: 50000" required>
                </div>
            </div>

            <!-- Datos opcionales -->
            <div class="cdski-fields-grid">
                <div class="cdski-field">
                    <label for="cdski-nombre">Nombre</label>
                    <input type="text" id="cdski-nombre" name="nombre" placeholder="Tu nombre completo">
                </div>

                <div class="cdski-field">
                    <label for="cdski-email">Email</label>
                    <input type="email" id="cdski-email" name="email" placeholder="correo@ejemplo.cl">
                </div>

                <div class="cdski-field">
                    <label for="cdski-telefono">Teléfono</label>
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
                    <input type="text" id="cdski-concepto" name="concepto" placeholder="Ej: Clase de ski principiante">
                </div>
            </div>

            <!-- Medio de pago -->
            <div class="cdski-field cdski-field--required">
                <label>Medio de pago <span class="cdski-required">*</span></label>
                <div class="cdski-gateways">
                    <label class="cdski-gateway-option">
                        <input type="radio" name="gateway" value="webpay">
                        <div class="cdski-gateway-card">
                            <span class="cdski-gateway-name">Webpay</span>
                            <span class="cdski-gateway-desc">Tarjeta de débito/crédito</span>
                        </div>
                    </label>
                    <label class="cdski-gateway-option">
                        <input type="radio" name="gateway" value="mercadopago">
                        <div class="cdski-gateway-card">
                            <span class="cdski-gateway-name">Mercado Pago</span>
                            <span class="cdski-gateway-desc">Tarjeta, transferencia y más</span>
                        </div>
                    </label>
                    <label class="cdski-gateway-option">
                        <input type="radio" name="gateway" value="paypal">
                        <div class="cdski-gateway-card">
                            <span class="cdski-gateway-name">PayPal</span>
                            <span class="cdski-gateway-desc">Pago internacional</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div class="cdski-submit-area">
                <button type="submit" id="cdski-submit-btn" class="cdski-btn-pagar" disabled>
                    <span class="cdski-btn-text">Pagar reserva</span>
                    <span class="cdski-btn-spinner" style="display:none;">Procesando...</span>
                </button>
            </div>

            <div id="cdski-pago-error" class="cdski-error-msg" style="display:none;"></div>
        </form>
    </div>
</div>
