<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_Admin {

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ] );
    }

    public static function add_menu() {
        add_menu_page(
            'Pagos Clasesdeski',
            'Pagos Clases',
            'manage_options',
            'cdski-pagos',
            [ __CLASS__, 'render_page' ],
            'dashicons-money-alt',
            30
        );
    }

    public static function render_page() {
        $per_page = 25;
        $paged    = max( 1, intval( $_GET['paged'] ?? 1 ) );
        $offset   = ( $paged - 1 ) * $per_page;

        $filter_estado  = sanitize_text_field( $_GET['estado'] ?? '' );
        $filter_gateway = sanitize_text_field( $_GET['gateway'] ?? '' );

        $args = [
            'per_page' => $per_page,
            'offset'   => $offset,
        ];
        if ( $filter_estado ) $args['estado'] = $filter_estado;
        if ( $filter_gateway ) $args['gateway'] = $filter_gateway;

        $payments = CDSKI_DB::get_payments( $args );
        $total    = CDSKI_DB::count_payments( $args );
        $pages    = ceil( $total / $per_page );

        $estado_colors = [
            'approved'  => '#28a745',
            'pending'   => '#ffc107',
            'rejected'  => '#dc3545',
            'cancelled' => '#6c757d',
            'error'     => '#dc3545',
            'refunded'  => '#17a2b8',
        ];

        $estado_labels = [
            'approved'  => 'Aprobado',
            'pending'   => 'Pendiente',
            'rejected'  => 'Rechazado',
            'cancelled' => 'Cancelado',
            'error'     => 'Error',
            'refunded'  => 'Reembolsado',
        ];
        ?>
        <div class="wrap">
            <h1>Pagos Clasesdeski</h1>

            <div style="margin-bottom:15px;display:flex;gap:10px;align-items:center;">
                <form method="get" style="display:flex;gap:8px;align-items:center;">
                    <input type="hidden" name="page" value="cdski-pagos">
                    <select name="estado">
                        <option value="">-- Estado --</option>
                        <?php foreach ( $estado_labels as $k => $v ): ?>
                            <option value="<?php echo esc_attr( $k ); ?>" <?php selected( $filter_estado, $k ); ?>><?php echo esc_html( $v ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="gateway">
                        <option value="">-- Gateway --</option>
                        <option value="paypal" <?php selected( $filter_gateway, 'paypal' ); ?>>PayPal</option>
                        <option value="mercadopago" <?php selected( $filter_gateway, 'mercadopago' ); ?>>Mercado Pago</option>
                        <option value="webpay" <?php selected( $filter_gateway, 'webpay' ); ?>>Webpay</option>
                    </select>
                    <button type="submit" class="button">Filtrar</button>
                    <a href="<?php echo admin_url( 'admin.php?page=cdski-pagos' ); ?>" class="button">Limpiar</a>
                </form>
                <span style="margin-left:auto;"><strong><?php echo $total; ?></strong> pagos registrados</span>
            </div>

            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Reserva</th>
                        <th>Monto</th>
                        <th>Gateway</th>
                        <th>Estado</th>
                        <th>Transaction ID</th>
                        <th>Creado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $payments ) ): ?>
                        <tr><td colspan="10" style="text-align:center;">No hay pagos registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ( $payments as $p ): ?>
                            <tr>
                                <td><?php echo esc_html( $p->id ); ?></td>
                                <td><?php echo esc_html( $p->fecha ); ?></td>
                                <td><?php echo esc_html( $p->cliente ); ?></td>
                                <td><?php echo esc_html( $p->email ); ?></td>
                                <td><?php echo esc_html( $p->reserva ); ?></td>
                                <td style="text-align:right;">$<?php echo number_format( $p->monto, 0, ',', '.' ); ?></td>
                                <td><?php echo esc_html( ucfirst( $p->gateway ) ); ?></td>
                                <td>
                                    <span style="background:<?php echo esc_attr( $estado_colors[ $p->estado ] ?? '#999' ); ?>;color:#fff;padding:2px 8px;border-radius:3px;font-size:12px;">
                                        <?php echo esc_html( $estado_labels[ $p->estado ] ?? $p->estado ); ?>
                                    </span>
                                </td>
                                <td><code style="font-size:11px;"><?php echo esc_html( $p->transaction_id ); ?></code></td>
                                <td><?php echo esc_html( $p->created_at ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ( $pages > 1 ): ?>
                <div class="tablenav" style="margin-top:10px;">
                    <div class="tablenav-pages">
                        <?php
                        $base_url = admin_url( 'admin.php?page=cdski-pagos' );
                        if ( $filter_estado ) $base_url = add_query_arg( 'estado', $filter_estado, $base_url );
                        if ( $filter_gateway ) $base_url = add_query_arg( 'gateway', $filter_gateway, $base_url );

                        echo paginate_links( [
                            'base'    => add_query_arg( 'paged', '%#%', $base_url ),
                            'format'  => '',
                            'current' => $paged,
                            'total'   => $pages,
                        ] );
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

CDSKI_Admin::init();
