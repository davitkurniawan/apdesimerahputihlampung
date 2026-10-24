@extends('admin.layouts.index')

@section('content')
<style>
    /* TOTAL BLACKOUT - Force global dark theme for this module */
    html, body, .wrapper, .content-wrapper, .content, .main-footer, .main-header {
        background: #060910 !important;
        background-color: #060910 !important;
        border: none !important;
        box-shadow: none !important;
        color: #f1f5f9 !important;
    }

    .neural-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        padding: 25px;
        margin-bottom: 25px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
        max-height: 60vh;
        overflow-y: auto;
        padding-right: 5px;
    }

    .product-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .product-card:hover {
        background: rgba(6, 182, 212, 0.04);
        border-color: rgba(6, 182, 212, 0.25);
        transform: translateY(-3px);
    }

    .product-card img {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
        margin-bottom: 12px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .product-placeholder-img {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        margin: 0 auto 12px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }

    .qty-btn {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 700;
        transition: all 0.2s;
    }

    .qty-btn:hover {
        background: #06b6d4;
        border-color: #06b6d4;
    }

    .payment-tab {
        flex: 1;
        text-align: center;
        padding: 12px;
        border: 1px solid rgba(255,255,255,0.05);
        background: rgba(255,255,255,0.01);
        color: #94a3b8;
        border-radius: 12px;
        cursor: pointer;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .payment-tab.active {
        background: rgba(6, 182, 212, 0.08);
        border-color: #06b6d4;
        color: white;
    }

    .input-xosid {
        background: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 10px 14px !important;
        width: 100%;
        outline: none;
        transition: all 0.3s ease;
    }

    .input-xosid:focus {
        border-color: #06b6d4 !important;
    }

    .label-xosid {
        font-family: 'Outfit', sans-serif;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
        display: block;
    }

    .btn-xosid-primary {
        background: #06b6d4 !important;
        border: 1px solid #06b6d4 !important;
        color: white !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        padding: 12px 24px !important;
        border-radius: 14px !important;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        cursor: pointer;
    }

    .btn-xosid-primary:hover {
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.4) !important;
    }

    .category-pill {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    .category-pill.active {
        background: rgba(6, 182, 212, 0.08);
        border-color: #06b6d4;
        color: white;
    }

    /* select2 override */
    .select2-container--default .select2-selection--single {
        background-color: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        height: 42px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
        line-height: 42px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
    }

    .select2-dropdown {
        background-color: #0f172a !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }
</style>

<div class="content-wrapper-inner" style="padding: 30px;">
    <div class="row">
        <!-- LEFT COLUMN: PRODUCTS CATALOG -->
        <div class="col-md-7">
            <!-- Header search -->
            <div class="neural-card" style="padding: 20px; margin-bottom: 20px;">
                <div style="display: flex; gap: 15px; align-items: center;">
                    <i data-lucide="search" style="width: 20px; color: #64748b;"></i>
                    <input type="text" id="search-pos" onkeyup="filterProducts()" placeholder="Cari produk berdasarkan SKU atau nama..." class="input-xosid" style="border: none !important; background: transparent !important; padding: 0 !important;">
                </div>
            </div>

            <!-- Categories -->
            <div style="margin-bottom: 20px;">
                <div class="category-pill active" onclick="filterCategory(0, this)">Semua Kategori</div>
                @foreach($categories as $cat)
                    <div class="category-pill" onclick="filterCategory({{ $cat['id'] }}, this)">{{ $cat['nama_kategori'] }}</div>
                @endforeach
            </div>

            <!-- Products list -->
            <div class="product-grid" id="product-grid-container">
                @foreach($products as $prod)
                    <div class="product-card" data-category="{{ $prod['kategori_id'] }}" data-sku="{{ strtolower($prod['sku']) }}" data-nama="{{ strtolower($prod['nama_produk']) }}" onclick='addToCart(@json($prod))'>
                        @if(!empty($prod['foto_produk']))
                            <img src="{{ base_url($prod['foto_produk']) }}">
                        @else
                            <div class="product-placeholder-img">
                                <i data-lucide="package" style="width: 24px;"></i>
                            </div>
                        @endif
                        <div style="font-family: monospace; font-size: 10px; color: #06b6d4; font-weight: 700; margin-bottom: 4px;">{{ $prod['sku'] }}</div>
                        <div style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 13px; color: white; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $prod['nama_produk'] }}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 10px; color: #64748b;">Stok: <b>{{ $prod['satuan'] === 'tiket' ? '∞' : $prod['stok'] }}</b></span>
                            <span style="font-size: 12px; font-weight: 800; color: #10b981;">Rp{{ number_format($prod['harga_jual'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- RIGHT COLUMN: SHOPPING CART -->
        <div class="col-md-5">
            <div class="neural-card" style="padding: 25px; height: calc(100vh - 120px); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: white; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
                        <span><i data-lucide="shopping-cart" style="width: 20px; color: #06b6d4; display: inline; margin-right: 8px;"></i> Keranjang POS</span>
                        <span id="cart-item-count" style="font-size: 11px; background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.2); color: #06b6d4; padding: 2px 10px; border-radius: 9999px;">0 Item</span>
                    </h4>

                    <!-- Cart items list -->
                    <div id="cart-items-container" style="max-height: 35vh; overflow-y: auto; padding-right: 5px; margin-bottom: 20px;">
                        <div id="cart-empty-message" style="text-align: center; color: #64748b; padding: 30px 0;">
                            <i data-lucide="shopping-bag" style="width: 32px; height: 32px; display: block; margin: 0 auto 10px; opacity: 0.5;"></i>
                            Keranjang kosong. Pilih produk di sebelah kiri.
                        </div>
                    </div>
                </div>

                <!-- Footer configurations & Checkout -->
                <div>
                    <!-- Customer Select -->
                    <div style="margin-bottom: 15px;">
                        <label class="label-xosid">Pelanggan</label>
                        <div style="display: flex; gap: 15px; margin-bottom: 10px;">
                            <label style="color: #94a3b8; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                <input type="radio" name="cust_type" value="umum" checked onchange="toggleCustomerType('umum')" style="accent-color: #06b6d4;"> UMUM
                            </label>
                            <label style="color: #94a3b8; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                <input type="radio" name="cust_type" value="penduduk" onchange="toggleCustomerType('penduduk')" style="accent-color: #06b6d4;"> WARGA LOKAL
                            </label>
                        </div>
                        
                        <div id="customer-umum-input">
                            <input type="text" id="customer_name_umum" placeholder="Masukkan nama pelanggan umum..." class="input-xosid">
                        </div>
                        <div id="customer-penduduk-input" style="display: none;">
                            <select id="customer_id_select2" class="select2-penduduk" style="width: 100%;"></select>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div style="margin-bottom: 15px;">
                        <label class="label-xosid">Potongan Diskon (Rupiah)</label>
                        <input type="number" id="discount-nominal" value="0" onkeyup="calculateTotals()" placeholder="0" class="input-xosid">
                    </div>

                    <!-- Payment methods -->
                    <div style="margin-bottom: 20px;">
                        <label class="label-xosid">Metode Pembayaran</label>
                        <div style="display: flex; gap: 10px;">
                            <div class="payment-tab active" onclick="setPaymentMethod('tunai', this)">
                                <i data-lucide="banknote" style="width: 20px;"></i> Tunai
                            </div>
                            <div class="payment-tab" onclick="setPaymentMethod('transfer', this)">
                                <i data-lucide="landmark" style="width: 20px;"></i> Transfer
                            </div>
                            <div class="payment-tab" onclick="setPaymentMethod('qris', this)">
                                <i data-lucide="qr-code" style="width: 20px;"></i> QRIS
                            </div>
                        </div>
                    </div>

                    <!-- Receipt calculations -->
                    <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); padding: 15px; border-radius: 16px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: #94a3b8; margin-bottom: 8px;">
                            <span>Subtotal</span>
                            <span id="txt-subtotal">Rp0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: #94a3b8; margin-bottom: 8px;">
                            <span>Diskon</span>
                            <span id="txt-diskon" style="color: #ef4444;">-Rp0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: white;">
                            <span>Total Tagihan</span>
                            <span id="txt-total" style="color: #10b981;">Rp0</span>
                        </div>
                    </div>

                    <button onclick="processCheckout()" class="btn-xosid-primary">
                        <i data-lucide="check-circle" style="width: 18px; display: inline; margin-right: 8px;"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: PAYMENT MODAL & CASH RETURN CALCULATOR -->
<div id="payment-modal" class="modal fade" role="dialog" style="background: rgba(6, 9, 16, 0.85); backdrop-filter: blur(10px);">
    <div class="modal-dialog" style="margin-top: 100px; max-width: 450px;">
        <div class="modal-content" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.7; font-size: 28px;">&times;</button>
                <h4 class="modal-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: white; text-align: center;">Konfirmasi Pembayaran</h4>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Total Pembayaran</div>
                    <h2 id="modal-total-tagihan" style="font-family: 'Outfit', sans-serif; font-weight: 900; color: #10b981; font-size: 32px; margin-top: 5px;">Rp0</h2>
                </div>

                <!-- CASH CONTAINER -->
                <div id="payment-cash-container">
                    <div style="margin-bottom: 20px;">
                        <label class="label-xosid">Nominal Uang Diterima</label>
                        <input type="number" id="cash-paid" onkeyup="calculateChange()" placeholder="Masukkan jumlah uang..." class="input-xosid" style="font-size: 18px; text-align: center; font-weight: 700; color: white; height: 50px;">
                    </div>
                    
                    <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); padding: 15px; border-radius: 16px; text-align: center;">
                        <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Uang Kembalian</div>
                        <h3 id="txt-change" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #06b6d4; font-size: 24px; margin-top: 5px; margin-bottom: 0;">Rp0</h3>
                    </div>
                </div>

                <!-- TRANSFER CONTAINER -->
                <div id="payment-transfer-container" style="display: none; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); padding: 20px; border-radius: 16px; text-align: center;">
                    <i data-lucide="landmark" style="width: 32px; color: #8b5cf6; margin-bottom: 10px; display: block; margin: 0 auto 10px;"></i>
                    <div style="font-size: 13px; font-weight: 700; color: white; margin-bottom: 5px;">Transfer Bank Mandiri BUMDes</div>
                    <div style="font-family: monospace; font-size: 18px; color: #06b6d4; font-weight: 800; margin-bottom: 8px;">137-00-1234567-8</div>
                    <div style="font-size: 11px; color: #64748b;">Nama Rekening: BUMDES BERKARYA MANDIRI</div>
                </div>

                <!-- QRIS CONTAINER -->
                <div id="payment-qris-container" style="display: none; text-align: center;">
                    <div style="background: white; padding: 15px; border-radius: 16px; display: inline-block; margin-bottom: 15px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=BUMDES-QRIS-MOCK" style="width: 180px; height: 180px;">
                    </div>
                    <div style="font-size: 11px; color: #64748b;">Scan kode QRIS di atas menggunakan dompet digital atau mobile banking.</div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05); padding: 20px; display: flex; gap: 10px;">
                <button type="button" class="btn-xosid" data-dismiss="modal" style="flex: 1;">Batal</button>
                <button type="button" id="btn-submit-payment" onclick="submitTransaction()" class="btn-xosid btn-xosid-primary" style="flex: 2;">Selesaikan & Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: SUCCESS RECEIPT -->
<div id="receipt-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" style="background: rgba(6, 9, 16, 0.95);">
    <div class="modal-dialog" style="margin-top: 50px; max-width: 400px;">
        <div class="modal-content" style="background: white; border: none; border-radius: 20px; color: #0f172a;">
            <div class="modal-body" style="padding: 30px; font-family: 'Courier New', Courier, monospace;" id="receipt-print-area">
                <div style="text-align: center; border-bottom: 1px dashed #cbd5e1; padding-bottom: 15px; margin-bottom: 15px;">
                    <h3 style="font-weight: 800; font-size: 18px; margin: 0; text-transform: uppercase;">{{ $desa['nama_desa'] ? 'BUMDes ' . $desa['nama_desa'] : 'BUMDes Desa' }}</h3>
                    <div style="font-size: 10px; color: #64748b; margin-top: 5px;">{{ $desa['alamat_kantor'] ?: 'Alamat Kantor BUMDes' }}</div>
                    <div style="font-size: 10px; color: #64748b;">{{ $desa['telepon'] ?: '' }}</div>
                </div>

                <div style="font-size: 11px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>No: <b id="receipt-invoice"></b></span>
                        <span id="receipt-date"></span>
                    </div>
                    <div style="margin-top: 5px;">Pelanggan: <span id="receipt-customer"></span></div>
                    <div>Kasir: <span>Pamong Desa</span></div>
                </div>

                <div style="border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px; margin-bottom: 10px; font-size: 11px;" id="receipt-items-list">
                    <!-- Dinamic items row -->
                </div>

                <div style="font-size: 11px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Subtotal:</span>
                        <span id="receipt-subtotal"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Diskon:</span>
                        <span id="receipt-discount"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 13px; border-top: 1px dotted #cbd5e1; padding-top: 5px;">
                        <span>TOTAL:</span>
                        <span id="receipt-net"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                        <span>Metode:</span>
                        <span id="receipt-method"></span>
                    </div>
                </div>

                <div style="text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 15px; font-size: 10px; color: #64748b;">
                    <span>TERIMA KASIH ATAS KUNJUNGAN ANDA</span>
                    <br><span>* Lunas - BUMDes Online System *</span>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 20px 20px; display: flex; gap: 10px;">
                <button type="button" onclick="window.print()" class="btn btn-default" style="flex: 1; border-radius: 10px; font-weight: 700; height: 40px;"><i data-lucide="printer" style="width: 14px; display: inline; margin-right: 5px;"></i> Cetak Nota</button>
                <button type="button" onclick="location.reload()" class="btn btn-primary" style="flex: 1; border-radius: 10px; background: #06b6d4; border-color: #06b6d4; font-weight: 700; height: 40px;"><i data-lucide="plus" style="width: 14px; display: inline; margin-right: 5px;"></i> Transaksi Baru</button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];
    let selectedPayment = 'tunai';

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize Select2 with dynamic remote data for resident search
        if (typeof jQuery !== 'undefined' && jQuery().select2) {
            jQuery('.select2-penduduk').select2({
                ajax: {
                    url: "{{ site_url('bumdes_profil/apipenduduk') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Ketik NIK / Nama Warga Desa...',
                allowClear: true,
                dropdownParent: $('#customer-penduduk-input')
            });
        }
    });

    function filterCategory(catId, pillEl) {
        document.querySelectorAll('.category-pill').forEach(el => el.classList.remove('active'));
        pillEl.classList.add('active');

        document.querySelectorAll('.product-card').forEach(card => {
            const cardCat = parseInt(card.getAttribute('data-category'));
            if (catId === 0 || cardCat === catId) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterProducts() {
        const query = document.getElementById('search-pos').value.toLowerCase().trim();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.getAttribute('data-nama');
            const sku = card.getAttribute('data-sku');
            if (name.includes(query) || sku.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function addToCart(prod) {
        // Validate stock
        if (prod.satuan !== 'tiket' && prod.stok <= 0) {
            alert('Stok produk habis!');
            return;
        }

        const existing = cart.find(item => item.id === prod.id);
        if (existing) {
            if (prod.satuan !== 'tiket' && existing.qty >= prod.stok) {
                alert('Tidak bisa melebihi batas stok tersedia!');
                return;
            }
            existing.qty += 1;
        } else {
            cart.push({
                id: prod.id,
                sku: prod.sku,
                nama_produk: prod.nama_produk,
                harga_jual: parseFloat(prod.harga_jual),
                satuan: prod.satuan,
                stok: parseInt(prod.stok),
                qty: 1
            });
        }
        renderCart();
    }

    function updateQty(index, amount) {
        const item = cart[index];
        if (item.satuan !== 'tiket' && amount > 0 && item.qty >= item.stok) {
            alert('Batas stok maksimal tercapai!');
            return;
        }
        
        item.qty += amount;
        if (item.qty <= 0) {
            cart.splice(index, 1);
        }
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const emptyMsg = document.getElementById('cart-empty-message');
        const itemCountBadge = document.getElementById('cart-item-count');

        // Clear previous rows
        container.querySelectorAll('.cart-item').forEach(el => el.remove());

        if (cart.length === 0) {
            emptyMsg.style.display = 'block';
            itemCountBadge.textContent = '0 Item';
        } else {
            emptyMsg.style.display = 'none';
            itemCountBadge.textContent = cart.reduce((acc, curr) => acc + curr.qty, 0) + ' Item';

            cart.forEach((item, index) => {
                const subtotal = item.harga_jual * item.qty;
                const row = document.createElement('div');
                row.className = 'cart-item';
                row.innerHTML = `
                    <div style="flex: 2;">
                        <div style="font-weight: 700; color: white; font-size: 13px;">${item.nama_produk}</div>
                        <div style="font-size: 11px; color: #64748b;">Rp${new Intl.NumberFormat('id-ID').format(item.harga_jual)} / ${item.satuan}</div>
                    </div>
                    <div style="flex: 1; text-align: center; display: inline-flex; align-items: center; gap: 8px; justify-content: center;">
                        <button onclick="updateQty(${index}, -1)" class="qty-btn">-</button>
                        <span style="font-weight: 700; font-size: 13px; min-width: 20px;">${item.qty}</span>
                        <button onclick="updateQty(${index}, 1)" class="qty-btn">+</button>
                    </div>
                    <div style="flex: 1; text-align: right; font-weight: 800; color: white;">
                        Rp${new Intl.NumberFormat('id-ID').format(subtotal)}
                    </div>
                    <button onclick="removeFromCart(${index})" style="background: transparent; border: none; color: #ef4444; margin-left: 10px; cursor: pointer;">
                        <i data-lucide="trash-2" style="width: 14px;"></i>
                    </button>
                `;
                container.appendChild(row);
            });
        }
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
        calculateTotals();
    }

    function calculateTotals() {
        const subtotal = cart.reduce((acc, curr) => acc + (curr.harga_jual * curr.qty), 0);
        const discount = parseFloat(document.getElementById('discount-nominal').value) || 0;
        const total = Math.max(0, subtotal - discount);

        document.getElementById('txt-subtotal').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);
        document.getElementById('txt-diskon').textContent = '-Rp' + new Intl.NumberFormat('id-ID').format(discount);
        document.getElementById('txt-total').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(total);
    }

    function toggleCustomerType(type) {
        if (type === 'umum') {
            document.getElementById('customer-umum-input').style.display = 'block';
            document.getElementById('customer-penduduk-input').style.display = 'none';
        } else {
            document.getElementById('customer-umum-input').style.display = 'none';
            document.getElementById('customer-penduduk-input').style.display = 'block';
        }
    }

    function setPaymentMethod(method, el) {
        selectedPayment = method;
        document.querySelectorAll('.payment-tab').forEach(tab => tab.classList.remove('active'));
        el.classList.add('active');
    }

    function processCheckout() {
        if (cart.length === 0) {
            alert('Keranjang belanja kosong!');
            return;
        }

        const subtotal = cart.reduce((acc, curr) => acc + (curr.harga_jual * curr.qty), 0);
        const discount = parseFloat(document.getElementById('discount-nominal').value) || 0;
        const total = Math.max(0, subtotal - discount);

        document.getElementById('modal-total-tagihan').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(total);

        // Toggle payment containers in modal
        document.getElementById('payment-cash-container').style.display = 'none';
        document.getElementById('payment-transfer-container').style.display = 'none';
        document.getElementById('payment-qris-container').style.display = 'none';

        if (selectedPayment === 'tunai') {
            document.getElementById('payment-cash-container').style.display = 'block';
            document.getElementById('cash-paid').value = '';
            document.getElementById('txt-change').textContent = 'Rp0';
        } else if (selectedPayment === 'transfer') {
            document.getElementById('payment-transfer-container').style.display = 'block';
        } else if (selectedPayment === 'qris') {
            document.getElementById('payment-qris-container').style.display = 'block';
        }

        $('#payment-modal').modal('show');
    }

    function calculateChange() {
        const subtotal = cart.reduce((acc, curr) => acc + (curr.harga_jual * curr.qty), 0);
        const discount = parseFloat(document.getElementById('discount-nominal').value) || 0;
        const total = Math.max(0, subtotal - discount);

        const paid = parseFloat(document.getElementById('cash-paid').value) || 0;
        const change = Math.max(0, paid - total);

        document.getElementById('txt-change').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(change);
    }

    function submitTransaction() {
        const subtotal = cart.reduce((acc, curr) => acc + (curr.harga_jual * curr.qty), 0);
        const discount = parseFloat(document.getElementById('discount-nominal').value) || 0;
        const total = Math.max(0, subtotal - discount);

        // Validation for cash
        if (selectedPayment === 'tunai') {
            const paid = parseFloat(document.getElementById('cash-paid').value) || 0;
            if (paid < total) {
                alert('Uang tunai yang diterima kurang!');
                return;
            }
        }

        const isResident = document.querySelector('input[name="cust_type"]:checked').value === 'penduduk';
        const payload = {
            cart_json: JSON.stringify(cart.map(item => ({ id: item.id, qty: item.qty }))),
            customer_type: isResident ? 'penduduk' : 'umum',
            customer_id: isResident ? document.getElementById('customer_id_select2').value : null,
            customer_name_umum: document.getElementById('customer_name_umum').value || 'Umum',
            discount: discount,
            payment_method: selectedPayment
        };

        // Submit via jQuery AJAX
        document.getElementById('btn-submit-payment').disabled = true;
        document.getElementById('btn-submit-payment').textContent = 'Memproses...';

        $.ajax({
            url: "{{ site_url('bumdes_pos/checkout') }}",
            type: 'POST',
            data: payload,
            dataType: 'json',
            success: function(data) {
                document.getElementById('btn-submit-payment').disabled = false;
                document.getElementById('btn-submit-payment').textContent = 'Selesaikan & Simpan';

                if (data.status === 'success') {
                    $('#payment-modal').modal('hide');
                    showReceipt(data.invoice);
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                document.getElementById('btn-submit-payment').disabled = false;
                document.getElementById('btn-submit-payment').textContent = 'Selesaikan & Simpan';
                alert('Gagal menghubungi server. Silakan coba lagi.');
            }
        });
    }

    function showReceipt(inv) {
        document.getElementById('receipt-invoice').textContent = inv.invoice_number;
        document.getElementById('receipt-date').textContent = inv.tanggal;
        document.getElementById('receipt-customer').textContent = inv.customer_name;
        document.getElementById('receipt-subtotal').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(inv.total_harga);
        document.getElementById('receipt-discount').textContent = '-Rp' + new Intl.NumberFormat('id-ID').format(inv.diskon);
        document.getElementById('receipt-net').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(inv.net_total);
        document.getElementById('receipt-method').textContent = inv.metode_pembayaran;

        const itemsContainer = document.getElementById('receipt-items-list');
        itemsContainer.innerHTML = '';

        inv.items.forEach(item => {
            const row = document.createElement('div');
            row.style.marginBottom = '6px';
            row.innerHTML = `
                <div><b>${item.nama_produk}</b></div>
                <div style="display: flex; justify-content: space-between;">
                    <span>${item.qty} x Rp${new Intl.NumberFormat('id-ID').format(item.harga_satuan)}</span>
                    <span>Rp${new Intl.NumberFormat('id-ID').format(item.subtotal)}</span>
                </div>
            `;
            itemsContainer.appendChild(row);
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
        $('#receipt-modal').modal('show');
    }
</script>
@endsection
