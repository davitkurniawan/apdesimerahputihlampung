@extends('xosid.portal_layout')

@section('content')
<style>
    /* Styling overrides for checkout modal */
    .xosid-modal-backdrop {
        background: rgba(3, 7, 18, 0.8);
        backdrop-filter: blur(12px);
    }
</style>

<div class="space-y-12">
    <!-- HERO HEADER -->
    <div class="relative p-8 md:p-12 rounded-[32px] md:rounded-[40px] xosid-glass overflow-hidden border border-white/5">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="space-y-4 max-w-2xl">
                <div class="inline-flex items-center gap-3 px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/20 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                    </span>
                    <span class="text-[10px] font-bold tracking-widest text-indigo-400 uppercase">Katalog Resmi & Belanja Warga</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
                    Portal Belanja BUMDes <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-cyan-400 to-emerald-400">Pusat Komoditas Desa {{ $desa['nama_desa'] }}</span>
                </h2>
                <p class="text-slate-400 text-sm leading-relaxed max-w-xl">
                    Dapatkan produk sembako, kerajinan, dan layanan jasa terbaik langsung dari Badan Usaha Milik Desa. Khusus warga desa setempat, nikmati kemudahan verifikasi NIK untuk pengantaran langsung ke rumah (COD).
                </p>
            </div>
            
            <!-- Dynamic Mini Cart Summary -->
            <div class="p-6 bg-white/5 border border-white/5 rounded-2xl flex flex-col items-center justify-center text-center shrink-0 w-full lg:w-auto min-w-[200px]">
                <div class="w-10 h-10 bg-indigo-600/20 rounded-xl flex items-center justify-center text-indigo-400 mb-2">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
                <span class="text-2xl font-black text-white" id="cart-item-count">0 Item</span>
                <span class="text-[9px] text-slate-500 uppercase tracking-widest font-bold mt-1" id="cart-total-price">Rp0</span>
            </div>
        </div>
    </div>

    <!-- PRODUCT CATALOG & SHOPPING CART LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Products Grid -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i data-lucide="store" class="text-indigo-400 w-5 h-5"></i> Produk Tersedia
                </h3>
                <span class="text-xs text-slate-500">{{ count($products) }} produk ditemukan</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @if(empty($products))
                    <div class="col-span-2 p-12 bg-white/5 border border-white/5 rounded-3xl text-center space-y-2">
                        <i data-lucide="inbox" class="w-12 h-12 text-slate-600 mx-auto"></i>
                        <h4 class="font-extrabold text-white text-sm">Produk Kosong</h4>
                        <p class="text-xs text-slate-400">Belum ada produk aktif yang terdaftar di katalog BUMDes saat ini.</p>
                    </div>
                @else
                    @foreach($products as $prod)
                        <div class="flex flex-col justify-between xosid-glass p-5 rounded-3xl border border-white/5 overflow-hidden group">
                            <div class="space-y-4">
                                <!-- Image placeholder -->
                                <div class="w-full h-44 rounded-2xl overflow-hidden relative bg-slate-950 border border-white/5 flex items-center justify-center">
                                    @if(!empty($prod->foto))
                                        <img src="{{ base_url('uploads/bumdes/produk/' . $prod->foto) }}" alt="{{ $prod->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                                    @else
                                        <div class="flex flex-col items-center justify-center text-slate-700">
                                            <i data-lucide="package" class="w-12 h-12 mb-2"></i>
                                            <span class="text-[10px] uppercase font-bold tracking-wider">Tanpa Foto</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Stock badge -->
                                    @if($prod->stok <= 5)
                                        <span class="absolute top-3 right-3 px-2 py-0.5 bg-rose-500 text-white text-[8px] font-black tracking-wider uppercase rounded-full shadow-lg">Stok Terbatas</span>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $prod->nama_kategori ?: 'Lainnya' }}</span>
                                    <h4 class="text-base font-bold text-white tracking-tight line-clamp-1">{{ $prod->nama_produk }}</h4>
                                    
                                    <div class="flex items-baseline gap-1 pt-1">
                                        <span class="text-lg font-black text-indigo-400">Rp {{ number_format($prod->harga_jual, 0, ',', '.') }}</span>
                                        <span class="text-[9px] font-semibold text-slate-500">/ {{ $prod->satuan }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed pt-1">{{ $prod->deskripsi ?: 'Tidak ada deskripsi produk.' }}</p>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between gap-4">
                                <div class="text-xs text-slate-500">Stok: <b class="text-slate-300">{{ $prod->stok }}</b></div>
                                <button onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->nama_produk) }}', {{ $prod->harga_jual }}, {{ $prod->harga_beli }}, {{ $prod->stok }})" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-[0_0_15px_rgba(99,102,241,0.1)]">
                                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> Tambah
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Sidebar Shopping Cart Details -->
        <div class="lg:col-span-1">
            <div class="p-6 xosid-glass rounded-[32px] border border-white/5 sticky top-32 space-y-6">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="text-indigo-400 w-5 h-5"></i> Keranjang Belanja
                </h3>
                
                <!-- Items list -->
                <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2" id="cart-items-container">
                    <div class="py-8 text-center text-slate-500 text-xs">
                        <i data-lucide="shopping-cart" class="w-8 h-8 mx-auto mb-2 text-slate-700"></i>
                        Keranjang masih kosong.
                    </div>
                </div>

                <!-- Summary prices -->
                <div class="pt-4 border-t border-white/5 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-400">
                        <span>Subtotal</span>
                        <span id="cart-summary-subtotal">Rp0</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Ongkos Kirim / COD</span>
                        <span class="text-emerald-400 font-bold">Gratis (COD Desa)</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-white pt-2 border-t border-white/5">
                        <span>Total Bayar</span>
                        <span id="cart-summary-total">Rp0</span>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button onclick="triggerCheckout()" id="btn-trigger-checkout" disabled class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 disabled:bg-white/5 disabled:text-slate-600 disabled:border-transparent text-white border border-indigo-500/20 rounded-2xl text-xs font-black tracking-widest uppercase transition-all flex items-center justify-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4"></i> Checkout Pesanan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CHECKOUT WARGA (NIK VERIFICATION) -->
<div id="checkout-modal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 xosid-modal-backdrop" onclick="closeCheckoutModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-md bg-slate-900 border border-white/10 rounded-[32px] overflow-hidden shadow-2xl z-10 flex flex-col">
        <!-- Header -->
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="shield-check" class="text-emerald-400 w-5 h-5"></i> Verifikasi Identitas Warga
                </h3>
                <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest font-semibold">Validasi database penduduk desa</p>
            </div>
            <button onclick="closeCheckoutModal()" class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <div class="space-y-2">
                <label class="text-[10px] font-bold tracking-widest text-slate-500 uppercase">Nomor Induk Kependudukan (NIK)</label>
                <div class="relative">
                    <input type="text" id="warga-nik" placeholder="Masukkan 16 digit NIK Anda..." class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm focus:outline-none focus:border-indigo-500/50 text-white placeholder-slate-600">
                    <button onclick="verifyWargaNIK()" class="absolute right-2 top-1.5 px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-all">
                        Cek NIK
                    </button>
                </div>
            </div>

            <!-- Verification result box -->
            <div id="warga-info-box" class="hidden p-4 rounded-xl border">
                <!-- Will be dynamically filled -->
            </div>

            <div class="pt-4 border-t border-white/5 grid grid-cols-2 gap-3">
                <button onclick="closeCheckoutModal()" class="py-3 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl text-xs font-bold transition-all text-center">
                    Batal
                </button>
                <button onclick="submitCitizenOrder()" id="btn-submit-order" disabled class="py-3 bg-emerald-600 hover:bg-emerald-500 disabled:bg-white/5 disabled:text-slate-600 text-white rounded-xl text-xs font-bold transition-all text-center">
                    Pesan Sekarang (COD)
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL SUCCESS RECEIPT -->
<div id="success-modal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
    <div class="absolute inset-0 xosid-modal-backdrop"></div>
    <div class="relative w-full max-w-sm bg-slate-900 border border-white/10 rounded-[32px] overflow-hidden shadow-2xl z-10 p-8 text-center space-y-6">
        <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/20 rounded-full flex items-center justify-center text-emerald-400 mx-auto">
            <i data-lucide="check-circle" class="w-8 h-8"></i>
        </div>
        <div class="space-y-2">
            <h3 class="text-xl font-bold text-white">Pesanan Berhasil Dikirim!</h3>
            <p class="text-xs text-slate-400">BUMDes desa setempat akan memproses dan mengantarkan belanjaan langsung ke alamat Anda.</p>
        </div>
        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl font-mono text-xs text-slate-300">
            Invoice: <b class="text-cyan-400" id="success-invoice-num">-</b>
        </div>
        <button onclick="window.location.reload()" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all">
            Belanja Lagi
        </button>
    </div>
</div>

<script>
    let cart = [];

    // Add item to cart
    function addToCart(id, name, price, costPrice, maxStock) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.qty < maxStock) {
                existing.qty++;
            } else {
                alert('Stok produk tidak mencukupi.');
                return;
            }
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                costPrice: costPrice,
                qty: 1,
                maxStock: maxStock
            });
        }
        updateCartUI();
    }

    // Modify qty
    function updateQty(id, delta) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            } else if (item.qty > item.maxStock) {
                alert('Stok tidak mencukupi.');
                item.qty = item.maxStock;
            }
        }
        updateCartUI();
    }

    // Refresh UI
    function updateCartUI() {
        const container = document.getElementById('cart-items-container');
        const countEl = document.getElementById('cart-item-count');
        const priceEl = document.getElementById('cart-total-price');
        const subtotalEl = document.getElementById('cart-summary-subtotal');
        const totalEl = document.getElementById('cart-summary-total');
        const btnCheckout = document.getElementById('btn-trigger-checkout');

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="py-8 text-center text-slate-500 text-xs">
                    <i data-lucide="shopping-cart" class="w-8 h-8 mx-auto mb-2 text-slate-700"></i>
                    Keranjang masih kosong.
                </div>
            `;
            countEl.textContent = '0 Item';
            priceEl.textContent = 'Rp0';
            subtotalEl.textContent = 'Rp0';
            totalEl.textContent = 'Rp0';
            btnCheckout.disabled = true;
            lucide.createIcons();
            return;
        }

        container.innerHTML = '';
        let totalItems = 0;
        let totalPrice = 0;

        cart.forEach(item => {
            totalItems += item.qty;
            totalPrice += item.price * item.qty;

            const row = document.createElement('div');
            row.className = 'flex items-center justify-between py-2 border-b border-white/5';
            row.innerHTML = `
                <div class="max-w-[60%]">
                    <h5 class="text-xs font-bold text-white line-clamp-1">${item.name}</h5>
                    <span class="text-[10px] text-indigo-400">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</span>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQty(${item.id}, -1)" class="w-6 h-6 bg-white/5 hover:bg-white/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-all text-xs font-bold">-</button>
                    <span class="text-xs font-bold text-white w-4 text-center">${item.qty}</span>
                    <button onclick="updateQty(${item.id}, 1)" class="w-6 h-6 bg-white/5 hover:bg-white/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-all text-xs font-bold">+</button>
                </div>
            `;
            container.appendChild(row);
        });

        countEl.textContent = `${totalItems} Item`;
        priceEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        subtotalEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        totalEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        btnCheckout.disabled = false;
    }

    // Checkout Triggers
    function triggerCheckout() {
        document.getElementById('checkout-modal').classList.remove('hidden');
        document.getElementById('checkout-modal').classList.add('flex');
    }

    function closeCheckoutModal() {
        document.getElementById('checkout-modal').classList.add('hidden');
        document.getElementById('checkout-modal').classList.remove('flex');
    }

    let verifiedWarga = null;

    // Verify NIK via internal AJAX API
    function verifyWargaNIK() {
        const nik = document.getElementById('warga-nik').value.trim();
        const infoBox = document.getElementById('warga-info-box');
        const btnSubmit = document.getElementById('btn-submit-order');

        if (!nik) return;

        fetch(`{{ site_url('bumdes') }}?check_nik=${nik}`)
            .then(res => res.json())
            .then(data => {
                infoBox.classList.remove('hidden');
                if (data.status === 'success') {
                    verifiedWarga = data;
                    infoBox.style.background = 'rgba(16, 185, 129, 0.05)';
                    infoBox.style.borderColor = 'rgba(16, 185, 129, 0.15)';
                    infoBox.style.color = '#10b981';
                    infoBox.innerHTML = `
                        <div class="text-xs font-bold uppercase tracking-wider mb-1">Warga Terverifikasi</div>
                        <div class="text-sm font-extrabold text-white">${data.nama}</div>
                        <div class="text-xs text-slate-400 mt-1">Alamat: ${data.alamat}</div>
                    `;
                    btnSubmit.disabled = false;
                } else {
                    verifiedWarga = null;
                    infoBox.style.background = 'rgba(239, 68, 68, 0.05)';
                    infoBox.style.borderColor = 'rgba(239, 68, 68, 0.15)';
                    infoBox.style.color = '#ef4444';
                    infoBox.innerHTML = `
                        <div class="text-xs font-bold uppercase tracking-wider mb-1">Gagal Verifikasi</div>
                        <div class="text-xs text-slate-300">${data.message}</div>
                    `;
                    btnSubmit.disabled = true;
                }
            })
            .catch(() => {
                alert('Gagal menghubungi server verifikasi NIK.');
            });
    }

    // Submit Citizen Order to POS checkout API
    function submitCitizenOrder() {
        if (!verifiedWarga || cart.length === 0) return;

        const csrfTokenName = 'sidcsrf'; // Default config name
        const csrfHash = '{{ $token_value }}';

        // Map cart to checkout format
        const itemsPayload = cart.map(item => ({
            id: item.id,
            qty: item.qty
        }));

        const total_price = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

        const formData = new FormData();
        formData.append('customer_type', 'umum'); // Represent general checkout in DB
        formData.append('customer_name_umum', `${verifiedWarga.nama} (Warga NIK: ${document.getElementById('warga-nik').value.trim()})`);
        formData.append('payment_method', 'tunai'); // COD / Tunai
        formData.append('discount', '0');
        formData.append('total_price', total_price.toString());
        formData.append('cart_json', JSON.stringify(itemsPayload));
        formData.append(csrfTokenName, csrfHash);

        fetch(`{{ site_url('bumdes_pos/checkout') }}`, {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    closeCheckoutModal();
                    document.getElementById('success-invoice-num').textContent = data.invoice.invoice_number;
                    document.getElementById('success-modal').classList.remove('hidden');
                    document.getElementById('success-modal').classList.add('flex');
                    cart = [];
                    updateCartUI();
                } else {
                    alert('Gagal mengirimkan pesanan: ' + data.message);
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi saat memproses checkout.');
            });
    }
</script>
@endsection
