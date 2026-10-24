#!/bin/bash
echo "🔍 Initiating BUMDes POS checkout API transaction verification..."

COOKIE_FILE="/tmp/bumdes_cookie.txt"
HTML_FILE="/tmp/bumdes_pos.html"

# 1. Fetch POS index to initialize session & extract CSRF parameters
echo "Step 1: Fetching index page to get session and CSRF token..."
curl -s -c "$COOKIE_FILE" -H "Host: demo.localhost:8080" "http://127.0.0.1:8080/index.php/bumdes_pos" > "$HTML_FILE"

# Extract CSRF name & value from the script tag in token.blade.php
TOKEN_NAME=$(grep -o "var csrfParam = \"[^\"]*\"" "$HTML_FILE" | head -n 1 | cut -d'"' -f2)
TOKEN_VAL=$(grep -o "var csrfVal = \"[^\"]*\"" "$HTML_FILE" | head -n 1 | cut -d'"' -f2)

if [ -z "$TOKEN_NAME" ] || [ -z "$TOKEN_VAL" ]; then
    echo "⚠️ CSRF token variables not found in HTML. Check if csrf is enabled."
    # Fallback to standard names
    TOKEN_NAME="csrf_xosid"
    TOKEN_VAL=""
fi

echo "CSRF Token Name: $TOKEN_NAME"
echo "CSRF Token Value: $TOKEN_VAL"

# 2. Submit transaction with session cookie and CSRF parameter
echo "Step 2: Sending POST checkout request..."
RESPONSE=$(curl -s -b "$COOKIE_FILE" -c "$COOKIE_FILE" \
    -H "Host: demo.localhost:8080" \
    -d "cart_json=[{\"id\":1,\"qty\":2},{\"id\":2,\"qty\":1}]" \
    -d "customer_type=umum" \
    -d "customer_name_umum=Budi Santoso" \
    -d "discount=5000" \
    -d "payment_method=tunai" \
    -d "$TOKEN_NAME=$TOKEN_VAL" \
    "http://127.0.0.1:8080/index.php/bumdes_pos/checkout")

echo "Server Response:"
echo "$RESPONSE" | json_pp 2>/dev/null || echo "$RESPONSE"

# 3. Query DB to verify transaction was created and stocks were deducted
echo -e "\n📊 Checking DB records for xosid_tenant_demo..."

echo "a. Transaction header record:"
docker exec -i xosid_neural_db mysql -uroot -ppassword -D xosid_tenant_demo -e "SELECT id, invoice_number, nama_pelanggan_umum, total_harga, diskon, metode_pembayaran FROM bumdes_transaksi ORDER BY id DESC LIMIT 1;"

echo "b. Transaction details record:"
docker exec -i xosid_neural_db mysql -uroot -ppassword -D xosid_tenant_demo -e "SELECT * FROM bumdes_transaksi_detail ORDER BY id DESC LIMIT 2;"

echo "c. Product stocks (IDs 1 & 2):"
docker exec -i xosid_neural_db mysql -uroot -ppassword -D xosid_tenant_demo -e "SELECT id, sku, nama_produk, stok FROM bumdes_produk WHERE id IN (1, 2);"

echo "d. Stock adjustment log entries:"
docker exec -i xosid_neural_db mysql -uroot -ppassword -D xosid_tenant_demo -e "SELECT * FROM bumdes_stok_log ORDER BY id DESC LIMIT 2;"
