#!/bin/bash
echo "🔍 Initiating Hagtou BUMDes Central Login & Dashboard Integration E2E Verification..."

COOKIE_FILE="/tmp/hagtou_cookie.txt"
rm -f "$COOKIE_FILE"

# 1. Clear cache
./xosid_clear_cache.sh
echo "✅ Cache cleared!"

# 2. Perform Login POST to hagtou-login
echo "Step 1: Authenticating BUMDes operator (demo / admin) at /hagtou-login..."
LOGIN_RESP=$(curl -s -i -c "$COOKIE_FILE" \
    -d "tenant_type=demo" \
    -d "username=admin" \
    -d "password=password" \
    "http://hagtou.localhost:8080/hagtou-login")

if echo "$LOGIN_RESP" | grep -q "hagtou-dashboard"; then
    echo "✅ Authentication successful! Redirecting to dashboard."
else
    echo "❌ Authentication failed!"
    echo "$LOGIN_RESP"
    exit 1
fi

# 3. Access Dashboard GET
echo "Step 2: Fetching /hagtou-dashboard using cookie..."
DASHBOARD_HTML=$(curl -s -b "$COOKIE_FILE" "http://hagtou.localhost:8080/hagtou-dashboard")

if echo "$DASHBOARD_HTML" | grep -q "Dasbor Pengelola BUMDes"; then
    echo "✅ Dashboard page loaded successfully!"
else
    echo "❌ Dashboard page failed to load or requires authentication!"
    exit 1
fi

# 4. Save BUMDes Profile
echo "Step 3: Updating BUMDes Profile..."
SAVE_PROFIL_RESP=$(curl -s -b "$COOKIE_FILE" -X POST \
    -d "nama_bumdes=BUMDes Bumi Jaya Mandiri" \
    -d "nomor_badan_hukum=AHU-00123.AH.01.01.2026" \
    -d "sk_pendirian=PERDES-03-2026" \
    -d "tanggal_berdiri=2026-07-01" \
    -d "nama_penasihat=Lukman Hakim" \
    -d "nama_direktur=Abdul Gani" \
    -d "nama_sekretaris=Siti Sarah" \
    -d "nama_bendahara=Budi Sentosa" \
    -d "nama_pengawas=Zulkifli" \
    -d "unit_usaha=Perdagangan komoditas kopi dan pertanian" \
    "http://hagtou.localhost:8080/hagtou-dashboard/save-profil")

echo "Response: $SAVE_PROFIL_RESP"
if echo "$SAVE_PROFIL_RESP" | grep -q "success"; then
    echo "✅ BUMDes profile saved successfully!"
else
    echo "❌ Failed to save BUMDes profile!"
    exit 1
fi

# Verify in database tenant
echo "Step 3.1: Verifying profile update in tenant DB..."
docker exec -i xosid_neural_db mysql -u root -ppassword -D xosid_tenant_demo -e "SELECT nama_bumdes, nama_direktur FROM bumdes_profil LIMIT 1;"

# 5. Save Product
echo "Step 4: Registering a new product in the dashboard..."
SAVE_PROD_RESP=$(curl -s -b "$COOKIE_FILE" -X POST \
    -d "kategori_id=1" \
    -d "sku=KOP-ARG-01" \
    -d "nama_produk=Kopi Arabika Gayo Organik 250g" \
    -d "deskripsi=Kopi arabika organik dataran tinggi Gayo, proses semi-washed." \
    -d "harga_beli=35000" \
    -d "harga_jual=50000" \
    -d "stok=50" \
    -d "satuan=pcs" \
    "http://hagtou.localhost:8080/hagtou-dashboard/save-produk")

echo "Response: $SAVE_PROD_RESP"
if echo "$SAVE_PROD_RESP" | grep -q "success"; then
    echo "✅ Product created successfully!"
else
    echo "❌ Failed to create product!"
    exit 1
fi

# Fetch new product ID
PROD_ID=$(docker exec -i xosid_neural_db mysql -u root -ppassword -D xosid_tenant_demo -e "SELECT id FROM bumdes_produk WHERE sku='KOP-ARG-01' LIMIT 1;" -N)
echo "Resolved Product ID: $PROD_ID"

# 6. Sync Product to Central Catalog
echo "Step 5: Syncing product $PROD_ID to Hagtou Central Catalog..."
SYNC_RESP=$(curl -s -b "$COOKIE_FILE" -X POST \
    -d "produk_id=$PROD_ID" \
    "http://hagtou.localhost:8080/hagtou-dashboard/sync-produk")

echo "Response: $SYNC_RESP"
if echo "$SYNC_RESP" | grep -q "success"; then
    echo "✅ Catalog sync succeeded!"
else
    echo "❌ Catalog sync failed!"
    exit 1
fi

# Verify sync in master DB
echo "Step 5.1: Verifying sync inside master hagtou_catalog_produk table..."
docker exec -i xosid_neural_db mysql -u root -ppassword -D xosid -e "SELECT subdomain, tenant_product_id, nama_produk, harga_jual, stok FROM hagtou_catalog_produk WHERE sku='KOP-ARG-01';"

# 7. Create Marketplace Order via API
echo "Step 6: Simulating customer checkout to create an order..."
ORDER_RESP=$(curl -s -X POST \
    -d "is_cart=0" \
    -d "customer_name=Budi Santoso" \
    -d "phone=081234567890" \
    -d "address=Jl. Sudirman No. 45" \
    -d "city_name=Kota Bandung" \
    -d "courier=JNE" \
    -d "shipping_cost=23000" \
    -d "product_id=$PROD_ID" \
    -d "qty=2" \
    -d "subdomain=demo" \
    "http://hagtou.localhost:8080/api/hagtou-order/create")

echo "Response: $ORDER_RESP"
if echo "$ORDER_RESP" | grep -q "success"; then
    echo "✅ Order created and recorded successfully!"
else
    echo "❌ Failed to create order!"
    exit 1
fi

# Verify transaction in tenant database
echo "Step 6.1: Verifying order transaction record inside tenant DB..."
docker exec -i xosid_neural_db mysql -u root -ppassword -D xosid_tenant_demo -e "SELECT invoice_number, total_harga, hagtou_order_id, nama_pelanggan_umum FROM bumdes_transaksi WHERE hagtou_order_id IS NOT NULL LIMIT 1;"

# Fetch dashboard again to ensure orders list is returned
echo "Step 6.2: Re-fetching /hagtou-dashboard to verify orders list data mapping..."
DASHBOARD_HTML_UPDATED=$(curl -s -b "$COOKIE_FILE" "http://hagtou.localhost:8080/hagtou-dashboard")
if echo "$DASHBOARD_HTML_UPDATED" | grep -q "ORD-HG-"; then
    echo "✅ Dashboard lists the incoming order successfully!"
else
    echo "❌ Dashboard failed to list the order!"
    exit 1
fi

echo "🎉 All Hagtou BUMDes Central Login & Dashboard E2E verifications completed successfully!"
