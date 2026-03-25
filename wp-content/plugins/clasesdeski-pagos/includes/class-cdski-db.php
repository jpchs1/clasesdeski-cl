<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_DB {

    public static function table_name() {
        global $wpdb;
        return $wpdb->prefix . 'cdski_pagos';
    }

    public static function create_table() {
        global $wpdb;
        $table   = self::table_name();
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            fecha DATE DEFAULT NULL,
            cliente VARCHAR(255) DEFAULT '',
            email VARCHAR(255) DEFAULT '',
            telefono VARCHAR(50) DEFAULT '',
            reserva VARCHAR(100) DEFAULT '',
            concepto VARCHAR(255) DEFAULT '',
            fecha_clase DATE DEFAULT NULL,
            monto DECIMAL(12,2) NOT NULL DEFAULT 0,
            gateway VARCHAR(50) NOT NULL DEFAULT '',
            estado VARCHAR(50) NOT NULL DEFAULT 'pending',
            transaction_id VARCHAR(255) DEFAULT '',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_estado (estado),
            KEY idx_gateway (gateway),
            KEY idx_email (email)
        ) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    public static function insert_payment( $data ) {
        global $wpdb;
        $data['fecha'] = current_time( 'Y-m-d' );

        $result = $wpdb->insert( self::table_name(), $data );
        return $result ? $wpdb->insert_id : false;
    }

    public static function update_payment( $id, $data ) {
        global $wpdb;
        return $wpdb->update( self::table_name(), $data, [ 'id' => $id ] );
    }

    public static function get_payment( $id ) {
        global $wpdb;
        $table = self::table_name();
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id ) );
    }

    public static function get_payments( $args = [] ) {
        global $wpdb;
        $table = self::table_name();

        $where   = '1=1';
        $params  = [];

        if ( ! empty( $args['estado'] ) ) {
            $where  .= ' AND estado = %s';
            $params[] = $args['estado'];
        }
        if ( ! empty( $args['gateway'] ) ) {
            $where  .= ' AND gateway = %s';
            $params[] = $args['gateway'];
        }

        $limit  = intval( $args['per_page'] ?? 25 );
        $offset = intval( $args['offset'] ?? 0 );

        $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY id DESC LIMIT %d OFFSET %d";
        $params[] = $limit;
        $params[] = $offset;

        return $wpdb->get_results( $wpdb->prepare( $sql, $params ) );
    }

    public static function count_payments( $args = [] ) {
        global $wpdb;
        $table = self::table_name();

        $where  = '1=1';
        $params = [];

        if ( ! empty( $args['estado'] ) ) {
            $where  .= ' AND estado = %s';
            $params[] = $args['estado'];
        }
        if ( ! empty( $args['gateway'] ) ) {
            $where  .= ' AND gateway = %s';
            $params[] = $args['gateway'];
        }

        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
        if ( $params ) {
            return (int) $wpdb->get_var( $wpdb->prepare( $sql, $params ) );
        }
        return (int) $wpdb->get_var( $sql );
    }
}
