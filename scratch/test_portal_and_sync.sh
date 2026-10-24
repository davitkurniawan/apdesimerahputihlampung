#!/bin/bash
echo "🔍 Initiating BUMDes Portal Shopping & Hagtou Catalog Sync E2E Verification..."

# 1. Clear cache
./xosid_clear_cache.sh

# 2. Verify BUMDes Portal View (Tenant Storefront)
echo "Step 1: Compiling and fetching BUMDes Portal main page..."
PORTAL_HTTP=$(curl -s -o /dev/null -w "%{http_code}" -L "http://demo.localhost:8080/index.php/bumdes")
if [ "$PORTAL_HTTP" == "200" ]; then
    echo "✅ BUMDes Portal loaded successfully with 200 OK!"
else
    echo "❌ BUMDes Portal failed to compile! HTTP: $PORTAL_HTTP"
    exit 1
fi

# 2.1 Verify Hagtou National Directory Page
echo "Step 1.1: Fetching Hagtou BUMDes National Directory page..."
DIR_HTTP=$(curl -s -o /dev/null -w "%{http_code}" -L "http://hagtou.localhost:8080/bumdes-terdaftar")
if [ "$DIR_HTTP" == "200" ]; then
    echo "✅ Hagtou National Directory page loaded successfully with 200 OK!"
else
    echo "❌ Hagtou National Directory page failed! HTTP: $DIR_HTTP"
    exit 1
fi

# 2.2 Verify Hagtou Partnership (Kerjasama) Page
echo "Step 1.2: Fetching Hagtou Partnership page..."
KERJASAMA_HTTP=$(curl -s -o /dev/null -w "%{http_code}" -L "http://hagtou.localhost:8080/kerjasama")
if [ "$KERJASAMA_HTTP" == "200" ]; then
    echo "✅ Hagtou Partnership page loaded successfully with 200 OK!"
else
    echo "❌ Hagtou Partnership page failed! HTTP: $KERJASAMA_HTTP"
    exit 1
fi

# 2.3 Verify Hagtou Sejarah Page
echo "Step 1.3: Fetching Hagtou Sejarah page..."
SEJARAH_HTTP=$(curl -s -o /dev/null -w "%{http_code}" -L "http://hagtou.localhost:8080/sejarah")
if [ "$SEJARAH_HTTP" == "200" ]; then
    echo "✅ Hagtou Sejarah page loaded successfully with 200 OK!"
else
    echo "❌ Hagtou Sejarah page failed! HTTP: $SEJARAH_HTTP"
    exit 1
fi

# 3. Test NIK verification API
echo "Step 2: Querying check_nik verification API..."
NIK_RESPONSE=$(curl -s -L "http://demo.localhost:8080/index.php/bumdes?check_nik=1801101000000001")
echo "Response: $NIK_RESPONSE"
if [[ "$NIK_RESPONSE" == *"Lukman Hakim"* ]]; then
    echo "✅ NIK verification API succeeded!"
else
    echo "❌ NIK verification API failed!"
    exit 1
fi

# 4. Emulate Citizen Checkout POST request
echo "Step 3: Fetching CSRF token..."
HTML_FILE="/tmp/bumdes_portal.html"
curl -s -L "http://demo.localhost:8080/index.php/bumdes" -c /tmp/cookies.txt > "$HTML_FILE"

TOKEN_NAME="sidcsrf"
TOKEN_VALUE=$(grep -w "sidcsrf_demo" /tmp/cookies.txt | awk '{print $7}')

echo "CSRF Token Value: $TOKEN_VALUE"

CART_PAYLOAD='[{"id":1,"qty":1}]'
CHECKOUT_RESP=$(curl -v -s -X POST \
  -b /tmp/cookies.txt \
  -F "customer_type=umum" \
  -F "customer_name_umum=Lukman Hakim (Warga NIK: 1801101000000001)" \
  -F "payment_method=tunai" \
  -F "discount=0" \
  -F "total_price=75000" \
  -F "cart_json=$CART_PAYLOAD" \
  -F "$TOKEN_NAME=$TOKEN_VALUE" \
  "http://demo.localhost:8080/index.php/bumdes_pos/checkout" 2>&1)

echo "Checkout Server Response:"
echo "$CHECKOUT_RESP"

if [[ "$CHECKOUT_RESP" == *"success"* ]]; then
    echo "✅ Citizen Online Purchase order submitted successfully!"
else
    echo "❌ Citizen Online Purchase order failed!"
    exit 1
fi

# 5. Verify Hagtou Catalog Sync
echo "Step 5: Setting product 1 sync status to pending..."
docker exec -i xosid_neural_db mysql -uroot -ppassword -D xosid_tenant_demo -e "
UPDATE bumdes_produk SET is_hagtou_katalog = 1, sync_status = 'pending' WHERE id = 1;
"

echo "Step 6: Executing sync_all API..."
SYNC_RESP=$(curl -v -s -L "http://demo.localhost:8080/index.php/bumdes_sync/sync_all" 2>&1)
echo "Sync Server Response:"
echo "$SYNC_RESP"

if [[ "$SYNC_RESP" == *"success"* ]]; then
    echo "✅ Hagtou Catalog Sync simulation succeeded!"
else
    echo "❌ Hagtou Catalog Sync simulation failed!"
    exit 1
fi

# 6. Check final DB state for sync status
echo "📊 Final DB states after sync:"
docker exec -i xosid_neural_db mysql -uroot -ppassword -D xosid_tenant_demo -e "
SELECT id, nama_produk, is_hagtou_katalog, sync_status FROM bumdes_produk WHERE id = 1;
"

echo "🎉 All BUMDes Portal and Catalog Sync E2E verifications completed successfully!"
