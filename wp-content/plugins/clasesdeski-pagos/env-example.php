<?php
/**
 * Clasesdeski Pagos — Variables de entorno
 *
 * Copiar estas definiciones a wp-config.php (antes de "That's all, stop editing!")
 * y completar con las credenciales reales de cada gateway.
 */

// ─── PayPal ─────────────────────────────────────────────
define( 'CDSKI_PAYPAL_CLIENT_ID', '' );  // PayPal App Client ID
define( 'CDSKI_PAYPAL_SECRET',    '' );  // PayPal App Secret
define( 'CDSKI_PAYPAL_SANDBOX',   true ); // true = sandbox, false = producción

// ─── Mercado Pago ───────────────────────────────────────
define( 'CDSKI_MP_ACCESS_TOKEN', '' );   // Access Token de Mercado Pago (Chile)

// ─── Webpay (Transbank) ────────────────────────────────
define( 'CDSKI_WEBPAY_COMMERCE_CODE', '' ); // Código de comercio
define( 'CDSKI_WEBPAY_API_KEY',       '' ); // API Key de producción
define( 'CDSKI_WEBPAY_SANDBOX',       true ); // true = integración, false = producción
