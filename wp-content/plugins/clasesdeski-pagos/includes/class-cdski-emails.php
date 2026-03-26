<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_Emails {

    /**
     * Send email notification to client.
     */
    public static function send_client_notification( $payment, $status ) {
        $to      = $payment->email;
        $subject = self::get_subject( $status, $payment );
        $body    = self::build_client_html( $payment, $status );
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Clasesdeski <no-reply@clasesdeski.cl>',
        ];

        wp_mail( $to, $subject, $body, $headers );
    }

    /**
     * Send email notification to admin.
     */
    public static function send_admin_notification( $payment, $status ) {
        $to      = 'info@clasesdeski.cl';
        $subject = '[Clasesdeski] ' . self::get_subject( $status, $payment );
        $body    = self::build_admin_html( $payment, $status );
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Clasesdeski Pagos <no-reply@clasesdeski.cl>',
            'Bcc: jpchs1@gmail.com',
        ];

        wp_mail( $to, $subject, $body, $headers );
    }

    private static function get_subject( $status, $payment ) {
        $monto = '$' . number_format( $payment->monto, 0, ',', '.' );
        switch ( $status ) {
            case 'approved':
                return "Pago confirmado - {$monto} CLP - Clasesdeski";
            case 'pending':
                return "Pago pendiente - {$monto} CLP - Clasesdeski";
            case 'rejected':
                return "Pago rechazado - {$monto} CLP - Clasesdeski";
            case 'cancelled':
                return "Pago cancelado - Clasesdeski";
            default:
                return "Error en pago - Clasesdeski";
        }
    }

    private static function build_client_html( $payment, $status ) {
        $monto   = '$' . number_format( $payment->monto, 0, ',', '.' ) . ' CLP';
        $gateway = ucfirst( $payment->gateway );
        $nombre  = $payment->cliente ?: 'Cliente';

        $status_config = [
            'approved' => [
                'color'   => '#16a34a',
                'title'   => 'Pago Confirmado',
                'message' => 'Tu pago ha sido procesado exitosamente.',
                'icon'    => '&#10004;',
            ],
            'pending' => [
                'color'   => '#d97706',
                'title'   => 'Pago Pendiente',
                'message' => 'Tu pago está siendo procesado. Te notificaremos cuando se confirme.',
                'icon'    => '&#9203;',
            ],
            'rejected' => [
                'color'   => '#dc2626',
                'title'   => 'Pago Rechazado',
                'message' => 'Lamentablemente tu pago no pudo ser procesado. Puedes intentar nuevamente con otro medio de pago.',
                'icon'    => '&#10008;',
            ],
            'cancelled' => [
                'color'   => '#6b7280',
                'title'   => 'Pago Cancelado',
                'message' => 'El proceso de pago fue cancelado. Puedes intentar nuevamente cuando lo desees.',
                'icon'    => '&#9888;',
            ],
            'error' => [
                'color'   => '#dc2626',
                'title'   => 'Error en el Pago',
                'message' => 'Ocurrió un error al procesar tu pago. Por favor intenta nuevamente.',
                'icon'    => '&#10008;',
            ],
        ];

        $cfg = $status_config[ $status ] ?? $status_config['error'];

        $details_rows = '';
        if ( $payment->reserva ) {
            $details_rows .= '<tr><td style="padding:8px 12px;font-weight:600;color:#333;">Reserva</td><td style="padding:8px 12px;color:#555;">' . esc_html( $payment->reserva ) . '</td></tr>';
        }
        if ( $payment->concepto ) {
            $details_rows .= '<tr><td style="padding:8px 12px;font-weight:600;color:#333;">Concepto</td><td style="padding:8px 12px;color:#555;">' . esc_html( $payment->concepto ) . '</td></tr>';
        }
        if ( $payment->fecha_clase ) {
            $details_rows .= '<tr><td style="padding:8px 12px;font-weight:600;color:#333;">Fecha clase</td><td style="padding:8px 12px;color:#555;">' . esc_html( $payment->fecha_clase ) . '</td></tr>';
        }
        if ( $status === 'approved' && $payment->transaction_id ) {
            $details_rows .= '<tr><td style="padding:8px 12px;font-weight:600;color:#333;">ID Transacción</td><td style="padding:8px 12px;color:#555;font-family:monospace;font-size:13px;">' . esc_html( $payment->transaction_id ) . '</td></tr>';
        }

        $retry_btn = '';
        if ( $status !== 'approved' && $status !== 'pending' ) {
            $pago_url  = esc_url( home_url( '/pago/' ) );
            $retry_btn = '<p style="text-align:center;margin-top:24px;"><a href="' . $pago_url . '" style="display:inline-block;padding:12px 32px;background:#2563eb;color:#fff;text-decoration:none;border-radius:8px;font-weight:600;">Intentar nuevamente</a></p>';
        }

        $whatsapp_url = 'https://wa.me/56992337976';

        return '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;background:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">
  <!-- Header -->
  <tr><td style="background:' . $cfg['color'] . ';padding:32px;text-align:center;">
    <div style="font-size:48px;color:#fff;margin-bottom:8px;">' . $cfg['icon'] . '</div>
    <h1 style="color:#fff;margin:0;font-size:24px;">' . $cfg['title'] . '</h1>
  </td></tr>
  <!-- Body -->
  <tr><td style="padding:32px;">
    <p style="font-size:16px;color:#333;margin:0 0 8px;">Hola <strong>' . esc_html( $nombre ) . '</strong>,</p>
    <p style="font-size:15px;color:#555;margin:0 0 24px;">' . $cfg['message'] . '</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb;">
      <tr><td style="padding:8px 12px;font-weight:600;color:#333;">Monto</td><td style="padding:8px 12px;color:#555;font-weight:700;font-size:18px;">' . $monto . '</td></tr>
      <tr><td style="padding:8px 12px;font-weight:600;color:#333;">Medio de pago</td><td style="padding:8px 12px;color:#555;">' . $gateway . '</td></tr>
      <tr><td style="padding:8px 12px;font-weight:600;color:#333;">Fecha</td><td style="padding:8px 12px;color:#555;">' . esc_html( $payment->fecha ) . '</td></tr>
      ' . $details_rows . '
    </table>

    ' . $retry_btn . '

    <p style="font-size:13px;color:#999;margin:24px 0 0;text-align:center;">¿Necesitas ayuda? Escríbenos por <a href="' . $whatsapp_url . '" style="color:#25d366;">WhatsApp</a></p>
  </td></tr>
  <!-- Footer -->
  <tr><td style="background:#f9fafb;padding:16px;text-align:center;border-top:1px solid #e5e7eb;">
    <p style="margin:0;font-size:12px;color:#999;">Clasesdeski — Clases de ski y snowboard en Chile</p>
    <p style="margin:4px 0 0;font-size:12px;color:#bbb;">clasesdeski.cl</p>
  </td></tr>
</table>
</td></tr></table>
</body></html>';
    }

    private static function build_admin_html( $payment, $status ) {
        $monto   = '$' . number_format( $payment->monto, 0, ',', '.' ) . ' CLP';
        $gateway_labels = [
            'webpay'      => 'Webpay Plus',
            'mercadopago' => 'Mercado Pago',
            'paypal'      => 'PayPal',
        ];
        $gateway = $gateway_labels[ $payment->gateway ] ?? ucfirst( $payment->gateway );

        $status_labels = [
            'approved'  => 'APROBADO',
            'pending'   => 'PENDIENTE',
            'rejected'  => 'RECHAZADO',
            'cancelled' => 'CANCELADO',
            'error'     => 'ERROR',
        ];
        $status_colors = [
            'approved'  => '#16a34a',
            'pending'   => '#d97706',
            'rejected'  => '#dc2626',
            'cancelled' => '#6b7280',
            'error'     => '#dc2626',
        ];
        $label = $status_labels[ $status ] ?? strtoupper( $status );
        $color = $status_colors[ $status ] ?? '#6b7280';

        $fecha  = date_i18n( 'j M Y, H:i', strtotime( $payment->created_at ) );

        // Build detail rows
        $details = '';
        $add_row = function( $lbl, $val ) use ( &$details ) {
            if ( ! $val ) return;
            $details .= '<tr><td style="padding:10px 16px;font-size:13px;color:#6b7280;border-bottom:1px solid #f0f0f0;">' . $lbl . '</td><td style="padding:10px 16px;font-size:13px;color:#1f2937;font-weight:600;border-bottom:1px solid #f0f0f0;text-align:right;">' . esc_html( $val ) . '</td></tr>';
        };

        $add_row( 'Cliente', $payment->cliente );
        $add_row( 'Email', $payment->email );
        $add_row( 'WhatsApp', $payment->telefono );
        $add_row( 'Medio de pago', $gateway );
        $add_row( 'N° Reserva', $payment->reserva );
        $add_row( 'Concepto', $payment->concepto );
        if ( $payment->fecha_clase ) {
            $add_row( 'Fecha de clase', date_i18n( 'j M Y', strtotime( $payment->fecha_clase ) ) );
        }
        $add_row( 'ID Transaccion', $payment->transaction_id );

        $admin_url = admin_url( 'admin.php?page=cdski-pagos' );

        return '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;background:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:32px 16px;">
<tr><td align="center">
<table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">

  <!-- Header bar -->
  <tr><td style="background:' . $color . ';padding:20px 28px;">
    <table width="100%" cellpadding="0" cellspacing="0"><tr>
      <td style="color:#fff;font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">NUEVO PAGO ' . $label . '</td>
      <td style="color:#fff;font-size:22px;font-weight:800;text-align:right;letter-spacing:-0.3px;">' . $monto . '</td>
    </tr></table>
  </td></tr>

  <!-- Body -->
  <tr><td style="padding:28px;">
    <p style="font-size:14px;color:#6b7280;margin:0 0 4px;">Pago #' . $payment->id . ' &middot; ' . $fecha . '</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;">
      ' . $details . '
    </table>

    <p style="margin:24px 0 0;text-align:center;">
      <a href="' . esc_url( $admin_url ) . '" style="display:inline-block;padding:12px 28px;background:#1e293b;color:#fff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:600;">Ver todos los pagos</a>
    </p>
  </td></tr>

  <!-- Footer -->
  <tr><td style="background:#f9fafb;padding:14px 28px;text-align:center;border-top:1px solid #e5e7eb;">
    <p style="margin:0;font-size:11px;color:#9ca3af;">Clasesdeski — Notificacion automatica de pagos</p>
  </td></tr>

</table>
</td></tr></table>
</body></html>';
    }
}
