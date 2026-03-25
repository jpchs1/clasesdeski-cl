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

<div class="cdski-pago-wrapper cdski-result-wrapper">
    <div class="cdski-result-card cdski-result--success">

        <!-- Top green bar -->
        <div class="cdski-result-topbar">
            <div class="cdski-result-check">
                <svg width="44" height="44" viewBox="0 0 44 44" fill="none">
                    <circle cx="22" cy="22" r="20" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.5)" stroke-width="2"/>
                    <path d="M13 22.5L19 28.5L31 16.5" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="cdski-check-animate"/>
                </svg>
            </div>
            <div class="cdski-result-topbar-text">
                <span class="cdski-result-badge-inline">Pago exitoso</span>
                <span class="cdski-result-topbar-amount">$<?php echo number_format( $payment->monto, 0, ',', '.' ); ?> <small>CLP</small></span>
            </div>
        </div>

        <!-- Body -->
        <div class="cdski-result-body">
            <h2>Felicitaciones<?php echo $payment->cliente ? ', ' . esc_html( $payment->cliente ) : ''; ?>!</h2>
            <p class="cdski-result-subtitle">Tu pago ha sido procesado exitosamente.<br>Hemos enviado un comprobante a tu correo.</p>

            <!-- Receipt -->
            <div class="cdski-receipt-card">
                <div class="cdski-receipt-head">
                    <span>Detalle de la transaccion</span>
                    <span class="cdski-receipt-date-sm"><?php echo esc_html( date_i18n( 'j M Y, H:i', strtotime( $payment->created_at ) ) ); ?></span>
                </div>

                <?php if ( $payment->cliente ): ?>
                <div class="cdski-receipt-item">
                    <span class="cdski-receipt-k">Cliente</span>
                    <span class="cdski-receipt-v"><?php echo esc_html( $payment->cliente ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $payment->email ): ?>
                <div class="cdski-receipt-item">
                    <span class="cdski-receipt-k">Email</span>
                    <span class="cdski-receipt-v"><?php echo esc_html( $payment->email ); ?></span>
                </div>
                <?php endif; ?>

                <div class="cdski-receipt-item">
                    <span class="cdski-receipt-k">Medio de pago</span>
                    <span class="cdski-receipt-v"><?php echo esc_html( $gateway_labels[ $payment->gateway ] ?? ucfirst( $payment->gateway ) ); ?></span>
                </div>

                <?php if ( $payment->reserva ): ?>
                <div class="cdski-receipt-item">
                    <span class="cdski-receipt-k">N° Reserva</span>
                    <span class="cdski-receipt-v cdski-receipt-v--accent"><?php echo esc_html( $payment->reserva ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $payment->concepto ): ?>
                <div class="cdski-receipt-item">
                    <span class="cdski-receipt-k">Concepto</span>
                    <span class="cdski-receipt-v"><?php echo esc_html( $payment->concepto ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $payment->fecha_clase ): ?>
                <div class="cdski-receipt-item">
                    <span class="cdski-receipt-k">Fecha de clase</span>
                    <span class="cdski-receipt-v"><?php echo esc_html( date_i18n( 'j \d\e F, Y', strtotime( $payment->fecha_clase ) ) ); ?></span>
                </div>
                <?php endif; ?>

                <div class="cdski-receipt-item cdski-receipt-item--txn">
                    <span class="cdski-receipt-k">ID Transaccion</span>
                    <span class="cdski-receipt-v"><code><?php echo esc_html( $payment->transaction_id ); ?></code></span>
                </div>
            </div>

            <!-- Amount summary -->
            <div class="cdski-receipt-total">
                <span>Total pagado</span>
                <span class="cdski-receipt-total-val">$<?php echo number_format( $payment->monto, 0, ',', '.' ); ?></span>
            </div>

            <!-- Actions -->
            <div class="cdski-result-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-pagar">Volver al inicio</a>
            </div>

            <div class="cdski-result-powered">
                <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2023/07/Logo1.png' ) ); ?>" alt="Clasesdeski" width="28" height="28">
                <span>clasesdeski.cl</span>
            </div>

            <div class="cdski-result-footer">
                <a href="https://wa.me/56992337976" target="_blank" rel="noopener">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 .5A6.5 6.5 0 001.07 10.03L.5 13.5l3.56-.54A6.5 6.5 0 107 .5z" fill="#25d366"/><path d="M10 8.3c-.15-.08-.9-.44-1.04-.5-.14-.04-.24-.07-.34.08-.1.15-.4.5-.48.6-.1.1-.18.1-.33.04-.15-.08-.64-.24-1.22-.76-.45-.4-.76-.9-.84-1.05-.1-.15 0-.23.07-.3.07-.08.15-.18.23-.27.07-.1.1-.15.15-.26.04-.1.02-.2-.02-.27-.04-.08-.34-.82-.47-1.13-.12-.3-.25-.25-.34-.26h-.3c-.1 0-.26.04-.4.2-.14.14-.53.52-.53 1.26s.55 1.46.62 1.56c.08.1 1.08 1.64 2.6 2.3.37.16.65.25.87.32.36.12.7.1.96.06.29-.04.9-.36 1.03-.72.12-.35.12-.65.08-.72-.04-.07-.14-.1-.3-.18z" fill="#fff"/></svg>
                    ¿Necesitas ayuda? Escribenos por WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<?php elseif ( $status === 'pending' ): ?>

<div class="cdski-pago-wrapper cdski-result-wrapper">
    <div class="cdski-result-card cdski-result--pending">
        <div class="cdski-result-topbar cdski-result-topbar--pending">
            <div class="cdski-result-check">
                <svg width="44" height="44" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="22" r="20" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.5)" stroke-width="2"/><path d="M22 12v12l7 4" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="cdski-result-topbar-text">
                <span class="cdski-result-badge-inline">Pago pendiente</span>
                <span class="cdski-result-topbar-sub">Estamos procesando tu pago</span>
            </div>
        </div>
        <div class="cdski-result-body">
            <h2>Tu pago esta siendo procesado</h2>
            <p class="cdski-result-subtitle">Te notificaremos por correo cuando se confirme. Esto puede tomar unos minutos.</p>
            <div class="cdski-result-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>

<?php elseif ( $status === 'rejected' ): ?>

<div class="cdski-pago-wrapper cdski-result-wrapper">
    <div class="cdski-result-card cdski-result--error">
        <div class="cdski-result-topbar cdski-result-topbar--error">
            <div class="cdski-result-check">
                <svg width="44" height="44" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="22" r="20" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.5)" stroke-width="2"/><path d="M16 16l12 12M28 16L16 28" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>
            </div>
            <div class="cdski-result-topbar-text">
                <span class="cdski-result-badge-inline">Pago rechazado</span>
                <span class="cdski-result-topbar-sub">No se realizo ningun cobro</span>
            </div>
        </div>
        <div class="cdski-result-body">
            <h2>Tu pago no pudo ser procesado</h2>
            <p class="cdski-result-subtitle">Esto puede ocurrir por fondos insuficientes, tarjeta bloqueada o datos incorrectos. Intenta nuevamente o elige otro medio de pago.</p>
            <div class="cdski-result-actions">
                <a href="<?php echo esc_url( home_url( '/pago/' ) ); ?>" class="cdski-btn-pagar">Intentar nuevamente</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>

<?php elseif ( $status === 'cancelled' ): ?>

<div class="cdski-pago-wrapper cdski-result-wrapper">
    <div class="cdski-result-card cdski-result--cancelled">
        <div class="cdski-result-topbar cdski-result-topbar--cancelled">
            <div class="cdski-result-check">
                <svg width="44" height="44" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="22" r="20" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.5)" stroke-width="2"/><path d="M22 14v10M22 28v2" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>
            </div>
            <div class="cdski-result-topbar-text">
                <span class="cdski-result-badge-inline">Pago cancelado</span>
                <span class="cdski-result-topbar-sub">No se realizo ningun cobro</span>
            </div>
        </div>
        <div class="cdski-result-body">
            <h2>Has cancelado el pago</h2>
            <p class="cdski-result-subtitle">No se realizo ningun cobro a tu cuenta. Puedes intentar nuevamente cuando lo desees.</p>
            <div class="cdski-result-actions">
                <a href="<?php echo esc_url( home_url( '/pago/' ) ); ?>" class="cdski-btn-pagar">Intentar nuevamente</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

<div class="cdski-pago-wrapper cdski-result-wrapper">
    <div class="cdski-result-card cdski-result--error">
        <div class="cdski-result-topbar cdski-result-topbar--error">
            <div class="cdski-result-check">
                <svg width="44" height="44" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="22" r="20" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.5)" stroke-width="2"/><path d="M16 16l12 12M28 16L16 28" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>
            </div>
            <div class="cdski-result-topbar-text">
                <span class="cdski-result-badge-inline">Error en el pago</span>
                <span class="cdski-result-topbar-sub">No se realizo ningun cobro</span>
            </div>
        </div>
        <div class="cdski-result-body">
            <h2>Ocurrio un error inesperado</h2>
            <p class="cdski-result-subtitle">Por favor intenta nuevamente o elige otro medio de pago. Si el problema persiste, contactanos por WhatsApp.</p>
            <div class="cdski-result-actions">
                <a href="<?php echo esc_url( home_url( '/pago/' ) ); ?>" class="cdski-btn-pagar">Intentar nuevamente</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
