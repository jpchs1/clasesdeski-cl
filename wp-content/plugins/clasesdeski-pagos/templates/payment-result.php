<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$status     = sanitize_text_field( $_GET['cdski_status'] ?? '' );
$payment_id = intval( $_GET['cdski_pid'] ?? 0 );
$payment    = $payment_id ? CDSKI_DB::get_payment( $payment_id ) : null;

$status_config = [
    'approved' => [
        'title'   => 'Pago confirmado',
        'message' => 'Tu pago ha sido procesado exitosamente. Recibirás un correo de confirmación.',
        'class'   => 'cdski-result--success',
    ],
    'pending' => [
        'title'   => 'Pago pendiente',
        'message' => 'Tu pago está siendo procesado. Te notificaremos cuando se confirme.',
        'class'   => 'cdski-result--pending',
    ],
    'rejected' => [
        'title'   => 'Pago rechazado',
        'message' => 'Tu pago no pudo ser procesado. Por favor intenta nuevamente o elige otro medio de pago.',
        'class'   => 'cdski-result--error',
    ],
    'cancelled' => [
        'title'   => 'Pago cancelado',
        'message' => 'Has cancelado el proceso de pago. Puedes intentar nuevamente cuando lo desees.',
        'class'   => 'cdski-result--cancelled',
    ],
    'error' => [
        'title'   => 'Error en el pago',
        'message' => 'Ocurrió un error al procesar tu pago. Por favor intenta nuevamente.',
        'class'   => 'cdski-result--error',
    ],
];

$config = $status_config[ $status ] ?? $status_config['error'];
?>

<div class="cdski-pago-wrapper">
    <div class="cdski-pago-card cdski-result <?php echo esc_attr( $config['class'] ); ?>">
        <div class="cdski-result-icon"></div>
        <h2><?php echo esc_html( $config['title'] ); ?></h2>
        <p><?php echo esc_html( $config['message'] ); ?></p>

        <?php if ( $payment && $status === 'approved' ): ?>
            <div class="cdski-result-details">
                <table>
                    <?php if ( $payment->cliente ): ?>
                        <tr><td><strong>Nombre:</strong></td><td><?php echo esc_html( $payment->cliente ); ?></td></tr>
                    <?php endif; ?>
                    <tr><td><strong>Monto:</strong></td><td>$<?php echo number_format( $payment->monto, 0, ',', '.' ); ?> CLP</td></tr>
                    <tr><td><strong>Medio de pago:</strong></td><td><?php echo esc_html( ucfirst( $payment->gateway ) ); ?></td></tr>
                    <?php if ( $payment->reserva ): ?>
                        <tr><td><strong>Reserva:</strong></td><td><?php echo esc_html( $payment->reserva ); ?></td></tr>
                    <?php endif; ?>
                    <tr><td><strong>ID Transacción:</strong></td><td><code><?php echo esc_html( $payment->transaction_id ); ?></code></td></tr>
                </table>
            </div>
        <?php endif; ?>

        <div class="cdski-result-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cdski-btn-secondary">Volver al inicio</a>
            <?php if ( $status !== 'approved' ): ?>
                <a href="<?php echo esc_url( home_url( '/pago/' ) ); ?>" class="cdski-btn-pagar">Intentar nuevamente</a>
            <?php endif; ?>
        </div>
    </div>
</div>
