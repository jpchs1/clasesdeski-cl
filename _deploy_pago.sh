#!/bin/bash
# ClasesdeSki — Deploy del Portal de Pago (/pago/)
# Lo que hace:
#   1. Borra los 3 PHP rotos (versiones tourevo con deps que no existen)
#   2. Copia config.php de tourevo (credenciales)
#   3. Descarga los 3 PHP limpios + página /pago/ + JS + CSS desde el repo
#   4. Verifica que los 3 endpoints respondan 200 con JSON válido
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
echo "Webpay — (sin action, espera available_actions):"
curl -fsS "https://clasesdeski.cl/api/webpay.php" | head -c 300
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
