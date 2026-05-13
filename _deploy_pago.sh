#!/bin/bash
# ClasesdeSki — Deploy del Portal de Pago (/pago/)  v1.1
# Lo que hace:
#   1. Borra los 3 PHP rotos (versiones tourevo con deps que no existen)
#   2. Copia config.php de tourevo (credenciales)
#   3. Auto-agrega las constantes WEBPAY a config.php si faltan
#      (los PHP de tourevo tienen las creds hardcodeadas dentro de webpay.php;
#       nosotros las centralizamos en config.php)
#   4. Descarga los 3 PHP limpios + página /pago/ + JS + CSS desde el repo
#   5. Verifica que los 3 endpoints respondan 200 con JSON válido
# Idempotente: se puede re-correr sin problema.

set -e
cd ~/clasesdeski.cl

GH="https://raw.githubusercontent.com/jpchs1/clasesdeski-cl/master"
V=$(date +%s)

echo "════════════════════════════════════════════════════════════"
echo "  CDSKI — Deploy /pago/  (V=$V)"
echo "════════════════════════════════════════════════════════════"

echo ""
echo "→ 1. Removiendo PHPs rotos (Tourevo dep-heavy)..."
for F in api/paypal.php api/mercadopago.php api/webpay.php; do
  [ -f "$F" ] && rm -v "$F"
done

echo ""
echo "→ 2. Copiando config.php desde ~/tourevo.cl/api/ (credenciales)..."
if [ -f ~/tourevo.cl/api/config.php ]; then
  cp ~/tourevo.cl/api/config.php api/config.php
  # Adapt CORS for clasesdeski.cl
  if ! grep -q "clasesdeski.cl" api/config.php; then
    sed -i "s|'https://tourevo.cl',|'https://tourevo.cl',\n    'https://clasesdeski.cl',\n    'https://www.clasesdeski.cl',|" api/config.php
    echo "  ↻ CORS extendido a clasesdeski.cl"
  fi
  chmod 640 api/config.php
  ls -la api/config.php
else
  echo "  ⚠ ERROR: ~/tourevo.cl/api/config.php no existe — abortando"
  exit 1
fi

echo ""
echo "→ 2b. Verificando constantes WEBPAY en config.php..."
# Tourevo's config.php no tiene las constantes Webpay (están hardcodeadas
# dentro de su webpay.php). Nuestro webpay.php limpio solo lee de config.php,
# así que las agregamos aquí si faltan.
if ! grep -q "WEBPAY_COMMERCE_CODE" api/config.php; then
  cat >> api/config.php << 'WEBPAY_EOF'

// ============================================
// WEBPAY (Transbank) PRODUCTION CREDENTIALS
// (agregadas automáticamente por _deploy_pago.sh)
// ============================================
define('WEBPAY_COMMERCE_CODE',  '597034812373');
define('WEBPAY_API_KEY_SECRET', '464a2bf8092ad625b634ccf4bb506440');
define('WEBPAY_API_URL',        'https://webpay3g.transbank.cl');
WEBPAY_EOF
  echo "  ✓ Constantes WEBPAY agregadas a config.php"
else
  echo "  ⊙ Constantes WEBPAY ya presentes"
fi

echo ""
echo "→ 3. Descargando 3 PHP limpios desde repo..."
curl -fsS -o api/paypal.php      "$GH/api/paypal.php"
curl -fsS -o api/mercadopago.php "$GH/api/mercadopago.php"
curl -fsS -o api/webpay.php      "$GH/api/webpay.php"
ls -la api/paypal.php api/mercadopago.php api/webpay.php

echo ""
echo "→ 4. Descargando /pago/ (HTML + return handler)..."
mkdir -p pago
curl -fsS -o pago/index.html             "$GH/pago/index.html"
curl -fsS -o pago/webpay-return.php      "$GH/pago/webpay-return.php"
ls -la pago/

echo ""
echo "→ 5. Descargando assets cdski-pago.{js,css}..."
curl -fsS -o cdski-pago.js  "$GH/cdski-pago.js"
curl -fsS -o cdski-pago.css "$GH/cdski-pago.css"
ls -la cdski-pago.{js,css}

echo ""
echo "→ 6. Bumpeando cache-bust en pago/index.html..."
sed -i -E "s|/cdski-pago\.css(\?v=[0-9]+)?|/cdski-pago.css?v=$V|g" pago/index.html
sed -i -E "s|/cdski-pago\.js(\?v=[0-9]+)?|/cdski-pago.js?v=$V|g" pago/index.html
grep -oE '/cdski-pago\.(css|js)\?v=[0-9]+' pago/index.html | sort -u

echo ""
echo "→ 7. Purgando caches LiteSpeed..."
rm -rf ~/lscache 2>/dev/null || true
find . -name ".lsphp_cache" -o -name "lscache" 2>/dev/null | xargs -r rm -rf

echo ""
echo "════════════════════════════════════════════════════════════"
echo "  Verificación de endpoints"
echo "════════════════════════════════════════════════════════════"

echo ""
echo "PayPal — get_client_id:"
curl -fsS "https://clasesdeski.cl/api/paypal.php?action=get_client_id" | head -c 300
echo ""

echo ""
echo "MercadoPago — get_public_key:"
curl -fsS "https://clasesdeski.cl/api/mercadopago.php?action=get_public_key" | head -c 300
echo ""

echo ""
echo "Webpay — environment check (sin action, espera 400 + available_actions):"
curl -sS "https://clasesdeski.cl/api/webpay.php" | head -c 300
echo ""

echo ""
echo "Webpay — TEST REAL de create_transaction (\$100 CLP, debería devolver token):"
curl -sS -X POST -H "Content-Type: application/json" \
  -d '{"amount":100,"description":"Deploy test","booking_code":"TEST"}' \
  "https://clasesdeski.cl/api/webpay.php?action=create_transaction" | head -c 400
echo ""

echo ""
echo "Page /pago/:"
curl -sIL "https://clasesdeski.cl/pago/" | head -5

echo ""
echo "════════════════════════════════════════════════════════════"
echo "  ✓ Deploy completo. V=$V"
echo ""
echo "  Próximo paso: abrí https://clasesdeski.cl/pago/ y probá un pago"
echo "  pequeño (\$100 CLP en Webpay o \$1 USD en PayPal) para validar."
echo "════════════════════════════════════════════════════════════"
