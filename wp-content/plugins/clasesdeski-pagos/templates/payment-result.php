<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$status     = sanitize_text_field( $_GET['cdski_status'] ?? '' );
$payment_id = intval( $_GET['cdski_pid'] ?? 0 );
$payment    = $payment_id ? CDSKI_DB::get_payment( $payment_id ) : null;

$gateway_labels = [
    'webpay'      => 'Webpay Plus',
    'mercadopago' => 'Mercado Pago',
    'paypal'      => 'PayPal',
];
?>

<?php if ( $status === 'approved' && $payment ): ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card cdski-result cdski-result--success">

        <!-- Animated check -->
        <div class="cdski-check-container">
            <svg class="cdski-check-svg" viewBox="0 0 100 100">
                <circle class="cdski-check-circle" cx="50" cy="50" r="45" fill="none" stroke="#16a34a" stroke-width="4"/>
                <path class="cdski-check-path" d="M30 52 L44 66 L70 38" fill="none" stroke="#16a34a" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <div class="cdski-result-badge">PAGO EXITOSO</div>

        <h2>Felicitaciones, <?php echo esc_html( $payment->cliente ?: 'tu pago fue confirmado' ); ?><?php echo $payment->cliente ? '!' : ''; ?></h2>
        <p>Tu pago ha sido procesado exitosamente. Hemos enviado un correo de confirmación con los detalles de tu transacción.</p>

        <!-- Receipt card -->
        <div class="cdski-receipt">
            <div class="cdski-receipt-header">
                <span class="cdski-receipt-title">Comprobante de pago</span>
                <span class="cdski-receipt-date"><?php echo esc_html( date_i18n( 'j M Y, H:i', strtotime( $payment->created_at ) ) ); ?></span>
            </div>

            <div class="cdski-receipt-amount">
                <span class="cdski-receipt-amount-label">Monto pagado</span>
                <span class="cdski-receipt-amount-value">$<?php echo number_format( $payment->monto, 0, ',', '.' ); ?> <small>CLP</small></span>
            </div>

            <div class="cdski-receipt-rows">
                <?php if ( $payment->cliente ): ?>
                <div class="cdski-receipt-row">
                    <span class="cdski-receipt-label">Cliente</span>
                    <span class="cdski-receipt-value"><?php echo esc_html( $payment->cliente ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $payment->email ): ?>
                <div class="cdski-receipt-row">
                    <span class="cdski-receipt-label">Email</span>
                    <span class="cdski-receipt-value"><?php echo esc_html( $payment->email ); ?></span>
                </div>
                <?php endif; ?>

                <div class="cdski-receipt-row">
                    <span class="cdski-receipt-label">Medio de pago</span>
                    <span class="cdski-receipt-value"><?php echo esc_html( $gateway_labels[ $payment->gateway ] ?? ucfirst( $payment->gateway ) ); ?></span>
                </div>

                <?php if ( $payment->reserva ): ?>
                <div class="cdski-receipt-row">
                    <span class="cdski-receipt-label">N° Reserva</span>
                    <span class="cdski-receipt-value cdski-receipt-highlight"><?php echo esc_html( $payment->reserva ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $payment->concepto ): ?>
                <div class="cdski-receipt-row">
                    <span class="cdski-receipt-label">Concepto</span>
                    <span class="cdski-receipt-value"><?php echo esc_html( $payment->concepto ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $payment->fecha_clase ): ?>
                <div class="cdski-receipt-row">
                    <span class="cdski-receipt-label">Fecha de clase</span>
                    <span class="cdski-receipt-value"><?php echo esc_html( date_i18n( 'j M Y', strtotime( $payment->fecha_clase ) ) ); ?></span>
                </div>
                <?php endif; ?>

                <div class="cdski-receipt-row cdski-receipt-row--txn">
                    <span class="cdski-receipt-label">ID Transacción</span>
                    <span class="cdski-receipt-value"><code><?php echo esc_html( $payment->transaction_id ); ?></code></span>
                </div>
            </div>
        </div>

        <div class="cdski-result-help">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 1a7 7 0 100 14A7 7 0 008 1z" stroke="#9ca3af" stroke-width="1.2"/><path d="M8 7.5V11M8 5.5V5" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round"/></svg>
            ¿Necesitas ayuda? Escríbenos por <a href="https://wa.me/56992337976" target="_blank" rel="noopener">WhatsApp</a>
        </div>

        <div class="cdski-result-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-pagar">Volver al inicio</a>
        </div>
    </div>
</div>

<?php elseif ( $status === 'pending' ): ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card cdski-result cdski-result--pending">
        <div class="cdski-status-icon cdski-status-icon--pending">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" stroke="#d97706" stroke-width="3" fill="#fef3c7"/><path d="M24 14v12l8 4" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h2>Pago pendiente</h2>
        <p>Tu pago está siendo procesado. Te notificaremos por correo cuando se confirme. Esto puede tomar unos minutos.</p>
        <div class="cdski-result-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
        </div>
    </div>
</div>

<?php elseif ( $status === 'rejected' ): ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card cdski-result cdski-result--error">
        <div class="cdski-status-icon cdski-status-icon--error">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" stroke="#dc2626" stroke-width="3" fill="#fee2e2"/><path d="M18 18l12 12M30 18L18 30" stroke="#dc2626" stroke-width="3" stroke-linecap="round"/></svg>
        </div>
        <h2>Pago rechazado</h2>
        <p>Lamentablemente tu pago no pudo ser procesado. Esto puede ocurrir por fondos insuficientes, tarjeta bloqueada o datos incorrectos. Por favor intenta nuevamente.</p>
        <div class="cdski-result-actions">
            <a href="<?php echo esc_url( home_url( '/pago/' ) ); ?>" class="cdski-btn-pagar">Intentar nuevamente</a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
        </div>
    </div>
</div>

<?php elseif ( $status === 'cancelled' ): ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card cdski-result cdski-result--cancelled">
        <div class="cdski-status-icon cdski-status-icon--cancelled">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" stroke="#6b7280" stroke-width="3" fill="#f3f4f6"/><path d="M24 16v10M24 30v2" stroke="#6b7280" stroke-width="3" stroke-linecap="round"/></svg>
        </div>
        <h2>Pago cancelado</h2>
        <p>Has cancelado el proceso de pago. No se realizó ningún cobro. Puedes intentar nuevamente cuando lo desees.</p>
        <div class="cdski-result-actions">
            <a href="<?php echo esc_url( home_url( '/pago/' ) ); ?>" class="cdski-btn-pagar">Intentar nuevamente</a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
        </div>
    </div>
</div>

<?php else: ?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card cdski-result cdski-result--error">
        <div class="cdski-status-icon cdski-status-icon--error">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" stroke="#dc2626" stroke-width="3" fill="#fee2e2"/><path d="M18 18l12 12M30 18L18 30" stroke="#dc2626" stroke-width="3" stroke-linecap="round"/></svg>
        </div>
        <h2>Error en el pago</h2>
        <p>Ocurrió un error inesperado al procesar tu pago. No se realizó ningún cobro. Por favor intenta nuevamente o elige otro medio de pago.</p>
        <div class="cdski-result-actions">
            <a href="<?php echo esc_url( home_url( '/pago/' ) ); ?>" class="cdski-btn-pagar">Intentar nuevamente</a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
        </div>
    </div>
</div>

<?php endif; ?>
