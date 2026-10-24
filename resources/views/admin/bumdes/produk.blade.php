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
        padding: 30px;
        margin-bottom: 30px;
    }

    .input-xosid {
        background: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        width: 100%;
        outline: none;
        transition: all 0.3s ease;
    }

    .input-xosid:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.2) !important;
    }

    .label-xosid {
        font-family: 'Outfit', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 8px;
        display: block;
    }

    .btn-xosid {
        background: rgba(6, 182, 212, 0.03) !important;
        color: #94a3b8 !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        padding: 12px 24px !important;
        border-radius: 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        letter-spacing: 1px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
        cursor: pointer;
    }

    .btn-xosid i {
        color: #06b6d4 !important;
        opacity: 0.8 !important;
    }

    .btn-xosid:hover {
        background: rgba(6, 182, 212, 0.08) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
        color: white !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 0 15px rgba(6, 182, 212, 0.2) !important;
    }

    .btn-xosid-primary {
        background: rgba(6, 182, 212, 0.08) !important;
        border: 1px solid #06b6d4 !important;
        color: white !important;
        box-shadow: 0 8px 20px -10px rgba(6, 182, 212, 0.3) !important;
    }

    .btn-xosid-primary:hover {
        background: rgba(6, 182, 212, 0.15) !important;
        color: white !important;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.4) !important;
        border-color: #06b6d4 !important;
    }

    .btn-xosid-danger {
        background: rgba(239, 68, 68, 0.03) !important;
        border-color: rgba(239, 68, 68, 0.2) !important;
        color: #ef4444 !important;
    }

    .btn-xosid-danger i {
        color: #ef4444 !important;
    }

    .btn-xosid-danger:hover {
        background: rgba(239, 68, 68, 0.1) !important;
        border-color: #ef4444 !important;
        color: white !important;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.2) !important;
    }

    /* CUSTOM TABS */
    .nav-tabs-xosid {
        display: flex;
        gap: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        margin-bottom: 30px;
        padding-bottom: 5px;
    }

    .tab-xosid {
        color: #64748b;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 10px 20px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .tab-xosid.active {
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: #06b6d4;
    }

    .tab-content-xosid {
        display: none;
    }

    .tab-content-xosid.active {
        display: block;
    }

    /* CUSTOM PREMIUM TABLE */
    .table-xosid {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table-xosid th {
        background: transparent;
        border: none;
        color: #64748b;
        font-family: 'Outfit', sans-serif;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 12px 20px;
    }

    .table-xosid td {
        background: rgba(255, 255, 255, 0.01);
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        padding: 15px 20px;
        font-size: 13px;
        color: #f1f5f9;
        transition: all 0.3s ease;
    }

    .table-xosid tr td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 16px 0 0 16px;
    }

    .table-xosid tr td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 0 16px 16px 0;
    }

    .table-xosid tr:hover td {
        background: rgba(6, 182, 212, 0.03);
        border-color: rgba(6, 182, 212, 0.15);
    }

    .sync-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sync-active { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; }
    .sync-inactive { background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.2); color: #64748b; }
</style>

<div class="content-wrapper-inner" style="padding: 30px;">
    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 10px; color: #06b6d4; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px;">BUMDes Sub-system</div>
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 28px; color: white; margin: 0;">Katalog & Stok Produk</h2>
        </div>
        <div style="display: flex; gap: 12px;">
            <button onclick="openKategoriModal()" class="btn-xosid">
                <i data-lucide="folder-plus" style="width: 16px;"></i> Kelola Kategori
            </button>
            <button onclick="openProdukModal()" class="btn-xosid btn-xosid-primary">
                <i data-lucide="plus" style="width: 16px;"></i> Tambah Produk Baru
            </button>
        </div>
    </div>

    <!-- NAVIGATION TABS -->
    <div class="nav-tabs-xosid">
        <div class="tab-xosid active" onclick="switchTab('daftar-produk')">
            <i data-lucide="package" style="width: 16px;"></i> Daftar Produk
        </div>
        <div class="tab-xosid" onclick="switchTab('daftar-kategori')">
            <i data-lucide="grid" style="width: 16px;"></i> Daftar Kategori
        </div>
    </div>

    <!-- TAB: DAFTAR PRODUK -->
    <div id="daftar-produk" class="tab-content-xosid active">
        <div class="neural-card" style="padding: 0; border: none; background: transparent; box-shadow: none;">
            <table class="table-xosid">
                <thead>
                    <tr>
                        <th style="width: 60px;">Foto</th>
                        <th style="width: 120px;">SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th style="text-align: right;">Harga Beli</th>
                        <th style="text-align: right;">Harga Jual</th>
                        <th style="text-align: center; width: 100px;">Stok</th>
                        <th style="text-align: center; width: 120px;">Hagtou Sync</th>
                        <th style="text-align: right; width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($products))
                        <tr>
                            <td colspan="9" style="text-align: center; color: #64748b; padding: 40px;">
                                <i data-lucide="inbox" style="width: 32px; height: 32px; display: block; margin: 0 auto 10px;"></i>
                                Belum ada produk terdaftar. Klik tombol Tambah Produk Baru di atas.
                            </td>
                        </tr>
                    @else
                        @foreach($products as $prod)
                            <tr>
                                <td>
                                    @if(!empty($prod['foto_produk']))
                                        <img src="{{ base_url($prod['foto_produk']) }}" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 1px solid rgba(255,255,255,0.05);">
                                    @else
                                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                            <i data-lucide="image" style="width: 18px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td style="font-family: monospace; font-size: 12px; color: #06b6d4; font-weight: 700;">{{ $prod['sku'] }}</td>
                                <td style="font-weight: 700; color: white;">
                                    {{ $prod['nama_produk'] }}
                                    @if($prod['stok'] <= 5 && $prod['satuan'] !== 'tiket')
                                        <span style="color: #ef4444; font-size: 9px; border: 1px solid rgba(239, 68, 68, 0.2); background: rgba(239, 68, 68, 0.05); padding: 2px 6px; border-radius: 6px; margin-left: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Stok Limit</span>
                                    @endif
                                </td>
                                <td><span style="color: #94a3b8; font-size: 12px;">{{ $prod['nama_kategori'] ?: 'Tanpa Kategori' }}</span></td>
                                <td style="text-align: right; font-family: 'Outfit', sans-serif;">Rp {{ number_format($prod['harga_beli'], 0, ',', '.') }}</td>
                                <td style="text-align: right; font-family: 'Outfit', sans-serif; color: #10b981; font-weight: 700;">Rp {{ number_format($prod['harga_jual'], 0, ',', '.') }}</td>
                                <td style="text-align: center; font-weight: 700; color: {{ $prod['stok'] > 5 ? 'white' : '#ef4444' }};">
                                    {{ $prod['satuan'] === 'tiket' ? '∞' : $prod['stok'] }} <span style="font-size: 10px; font-weight: 500; color: #64748b;">{{ $prod['satuan'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    @if($prod['is_hagtou_katalog'])
                                        <span class="sync-badge sync-active"><i data-lucide="refresh-cw" style="width: 10px;"></i> Aktif</span>
                                    @else
                                        <span class="sync-badge sync-inactive"><i data-lucide="eye-off" style="width: 10px;"></i> Lokal</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <button onclick='editProduk(@json($prod))' class="btn-xosid" style="padding: 6px 12px !important; border-radius: 8px !important; font-size: 10px !important;">
                                            <i data-lucide="edit-3" style="width: 12px;"></i> Edit
                                        </button>
                                        <a href="{{ site_url('bumdes_produk/delete/' . $prod['id']) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" class="btn-xosid btn-xosid-danger" style="padding: 6px 12px !important; border-radius: 8px !important; font-size: 10px !important;">
                                            <i data-lucide="trash-2" style="width: 12px;"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB: DAFTAR KATEGORI -->
    <div id="daftar-kategori" class="tab-content-xosid">
        <div class="row">
            <div class="col-md-8">
                <div class="neural-card" style="padding: 0; border: none; background: transparent; box-shadow: none;">
                    <table class="table-xosid">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th style="text-align: right; width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($categories))
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #64748b; padding: 40px;">
                                        Belum ada kategori terdaftar. Gunakan panel di sebelah kanan untuk menambahkan.
                                    </td>
                                </tr>
                            @else
                                @foreach($categories as $cat)
                                    <tr>
                                        <td style="font-family: monospace; color: #94a3b8;">#{{ $cat['id'] }}</td>
                                        <td style="font-weight: 700; color: white;">{{ $cat['nama_kategori'] }}</td>
                                        <td style="color: #64748b; font-size: 12px;">{{ $cat['deskripsi'] ?: '-' }}</td>
                                        <td style="text-align: right;">
                                            <div style="display: inline-flex; gap: 8px;">
                                                <button onclick='editKategori(@json($cat))' class="btn-xosid" style="padding: 6px 12px !important; border-radius: 8px !important; font-size: 10px !important;">
                                                    <i data-lucide="edit-3" style="width: 12px;"></i> Edit
                                                </button>
                                                <a href="{{ site_url('bumdes_produk/delete_kategori/' . $cat['id']) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="btn-xosid btn-xosid-danger" style="padding: 6px 12px !important; border-radius: 8px !important; font-size: 10px !important;">
                                                    <i data-lucide="trash-2" style="width: 12px;"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="neural-card" style="padding: 25px;">
                    <h4 id="kategori-form-title" style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 16px; color: white; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="folder-plus" style="width: 18px; color: #06b6d4;"></i> Tambah Kategori
                    </h4>
                    <form action="{{ site_url('bumdes_produk/save_kategori') }}" method="POST">
                        <input type="hidden" name="id" id="kategori-id">
                        <div style="margin-bottom: 15px;">
                            <label class="label-xosid">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="kategori-nama" required placeholder="Contoh: Sembako" class="input-xosid">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label class="label-xosid">Deskripsi</label>
                            <textarea name="deskripsi" id="kategori-deskripsi" rows="3" placeholder="Keterangan kategori..." class="input-xosid" style="resize: vertical;"></textarea>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                            <button type="button" onclick="cancelEditKategori()" class="btn-xosid" style="padding: 8px 16px !important; font-size: 10px !important;">Batal</button>
                            <button type="submit" class="btn-xosid btn-xosid-primary" style="padding: 8px 16px !important; font-size: 10px !important;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: ADD / EDIT PRODUCT -->
<div id="produk-modal" class="modal fade" role="dialog" style="background: rgba(6, 9, 16, 0.85); backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" style="margin-top: 50px;">
        <div class="modal-content" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 20px 30px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.7; font-size: 28px;">&times;</button>
                <h4 class="modal-title" id="produk-modal-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: white;">Tambah Produk Baru</h4>
            </div>
            <form action="{{ site_url('bumdes_produk/save') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="id" id="produk-id">
                    
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 20px;">
                            <label class="label-xosid">Kategori</label>
                            <select name="kategori_id" id="produk-kategori" required class="input-xosid" style="height: 48px;">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat['id'] }}">{{ $cat['nama_kategori'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 20px;">
                            <label class="label-xosid">SKU / Kode Produk</label>
                            <input type="text" name="sku" id="produk-sku" placeholder="Dihasilkan otomatis jika kosong..." class="input-xosid">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8" style="margin-bottom: 20px;">
                            <label class="label-xosid">Nama Produk / Jasa</label>
                            <input type="text" name="nama_produk" id="produk-nama" required placeholder="Contoh: Beras Premium 5kg" class="input-xosid">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 20px;">
                            <label class="label-xosid">Satuan</label>
                            <input type="text" name="satuan" id="produk-satuan" required placeholder="Contoh: pcs, kg, botol, tiket" class="input-xosid">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4" style="margin-bottom: 20px;">
                            <label class="label-xosid">Harga Beli (HPP)</label>
                            <input type="number" name="harga_beli" id="produk-harga-beli" required placeholder="0" class="input-xosid">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 20px;">
                            <label class="label-xosid">Harga Jual</label>
                            <input type="number" name="harga_jual" id="produk-harga-jual" required placeholder="0" class="input-xosid">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 20px;">
                            <label class="label-xosid">Stok Awal</label>
                            <input type="number" name="stok" id="produk-stok" required placeholder="0" class="input-xosid">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 20px;">
                            <label class="label-xosid">Deskripsi Produk</label>
                            <textarea name="deskripsi" id="produk-deskripsi" rows="3" placeholder="Keterangan detail mengenai produk..." class="input-xosid" style="resize: vertical;"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8" style="margin-bottom: 10px;">
                            <label class="label-xosid">Foto Produk</label>
                            <input type="file" name="foto_produk" class="input-xosid" style="padding: 10px;">
                            <small style="color: #64748b; font-size: 11px; margin-top: 4px; display: block;">Format: PNG, JPG, JPEG, atau WebP. Maksimal 2MB.</small>
                        </div>
                        <div class="col-md-4" style="margin-bottom: 10px; display: flex; align-items: center; padding-top: 25px;">
                            <label style="display: flex; align-items: center; gap: 10px; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; color: #94a3b8; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px;">
                                <input type="checkbox" name="is_hagtou_katalog" id="produk-hagtou" value="1" style="width: 18px; height: 18px; accent-color: #06b6d4;">
                                Tampilkan di Hagtou
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05); padding: 20px 30px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn-xosid" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-xosid btn-xosid-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function switchTab(tabId) {
        // Toggle tabs active class
        document.querySelectorAll('.tab-xosid').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-content-xosid').forEach(el => el.classList.remove('active'));

        const targetTab = Array.from(document.querySelectorAll('.tab-xosid')).find(el => el.getAttribute('onclick').includes(tabId));
        if (targetTab) targetTab.classList.add('active');
        
        const content = document.getElementById(tabId);
        if (content) content.classList.add('active');
    }

    function openProdukModal() {
        document.getElementById('produk-id').value = '';
        document.getElementById('produk-kategori').value = '';
        document.getElementById('produk-sku').value = '';
        document.getElementById('produk-nama').value = '';
        document.getElementById('produk-satuan').value = 'pcs';
        document.getElementById('produk-harga-beli').value = '';
        document.getElementById('produk-harga-jual').value = '';
        document.getElementById('produk-stok').value = '';
        document.getElementById('produk-deskripsi').value = '';
        document.getElementById('produk-hagtou').checked = false;
        document.getElementById('produk-modal-title').textContent = 'Tambah Produk Baru';
        $('#produk-modal').modal('show');
    }

    function editProduk(prod) {
        document.getElementById('produk-id').value = prod.id;
        document.getElementById('produk-kategori').value = prod.kategori_id;
        document.getElementById('produk-sku').value = prod.sku;
        document.getElementById('produk-nama').value = prod.nama_produk;
        document.getElementById('produk-satuan').value = prod.satuan;
        document.getElementById('produk-harga-beli').value = Math.round(prod.harga_beli);
        document.getElementById('produk-harga-jual').value = Math.round(prod.harga_jual);
        document.getElementById('produk-stok').value = prod.stok;
        document.getElementById('produk-deskripsi').value = prod.deskripsi;
        document.getElementById('produk-hagtou').checked = parseInt(prod.is_hagtou_katalog) === 1;
        document.getElementById('produk-modal-title').textContent = 'Edit Detail Produk';
        $('#produk-modal').modal('show');
    }

    function editKategori(cat) {
        document.getElementById('kategori-id').value = cat.id;
        document.getElementById('kategori-nama').value = cat.nama_kategori;
        document.getElementById('kategori-deskripsi').value = cat.deskripsi;
        document.getElementById('kategori-form-title').innerHTML = '<i data-lucide="edit-3" style="width: 18px; color: #06b6d4;"></i> Edit Kategori';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function cancelEditKategori() {
        document.getElementById('kategori-id').value = '';
        document.getElementById('kategori-nama').value = '';
        document.getElementById('kategori-deskripsi').value = '';
        document.getElementById('kategori-form-title').innerHTML = '<i data-lucide="folder-plus" style="width: 18px; color: #06b6d4;"></i> Tambah Kategori';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
</script>
@endsection
