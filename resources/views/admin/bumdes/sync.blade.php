@extends('admin.layouts.index')

@section('content')
<style>
    /* Force global dark theme for this module */
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
    }

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
    }

    .table-xosid tr td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 16px 0 0 16px;
    }

    .table-xosid tr td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 0 16px 16px 0;
    }

    .badge-xosid {
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
</style>

<div class="content-wrapper-inner" style="padding: 30px;">
    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 10px; color: #06b6d4; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px;">Hagtou Central Agregator</div>
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 28px; color: white; margin: 0;">Sinkronisasi Katalog Pusat</h2>
        </div>
        <div>
            <button onclick="runHagtouSync()" class="btn-xosid btn-xosid-primary">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Sinkronisasikan Sekarang
            </button>
        </div>
    </div>

    <!-- STATS OVERVIEW -->
    <div class="row" style="margin-bottom: 35px;">
        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                    <i data-lucide="package" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Produk</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: white; margin-top: 2px;">
                        {{ $stats['total'] }} Items
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                    <i data-lucide="check-circle" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Terbit di Hagtou</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #10b981; margin-top: 2px;">
                        {{ $stats['synced'] }} Items
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #f59e0b;">
                    <i data-lucide="clock" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Menunggu Sinkron</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #f59e0b; margin-top: 2px;">
                        {{ $stats['pending'] }} Items
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.15); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #ef4444;">
                    <i data-lucide="eye-off" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Diarsipkan / Lokal</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #ef4444; margin-top: 2px;">
                        {{ $stats['disabled'] }} Items
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CATALOG GRID CONTROL -->
    <div class="neural-card" style="padding: 0; border: none; background: transparent; box-shadow: none;">
        <table class="table-xosid">
            <thead>
                <tr>
                    <th style="width: 140px;">SKU</th>
                    <th>Nama Produk BUMDes</th>
                    <th>Kategori</th>
                    <th style="text-align: right; width: 140px;">Harga Jual</th>
                    <th style="text-align: center; width: 100px;">Stok</th>
                    <th style="text-align: center; width: 180px;">Status Sinkronisasi</th>
                    <th style="text-align: right; width: 180px;">Aksi Publikasi</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($products))
                    <tr>
                        <td colspan="7" style="text-align: center; color: #64748b; padding: 40px;">
                            Belum ada katalog produk yang diinputkan. Hubungi admin POS BUMDes.
                        </td>
                    </tr>
                @else
                    @foreach($products as $p)
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: #94a3b8;">{{ $p['sku'] }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if(!empty($p['foto']))
                                        <img src="{{ base_url('uploads/bumdes/produk/' . $p['foto']) }}" style="width: 32px; height: 32px; object-cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                                    @else
                                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.02); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b;"><i data-lucide="package" style="width: 14px;"></i></div>
                                    @endif
                                    <span style="font-weight: 700; color: white;">{{ $p['nama_produk'] }}</span>
                                </div>
                            </td>
                            <td>{{ $p['nama_kategori'] ?: 'Lainnya' }}</td>
                            <td style="text-align: right; font-weight: 700;">Rp {{ number_format($p['harga_jual'], 0, ',', '.') }}</td>
                            <td style="text-align: center; font-weight: 700; color: {{ $p['stok'] > 5 ? 'white' : '#ef4444' }};">{{ $p['stok'] }}</td>
                            <td style="text-align: center;">
                                @if($p['is_hagtou_katalog'] == 1)
                                    @if($p['sync_status'] === 'synced')
                                        <span class="badge-xosid" style="background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.15); color: #10b981;" title="Sync Terakhir: {{ $p['sync_date'] ?: '-' }}">
                                            <i data-lucide="check-circle" style="width: 10px;"></i> Synced
                                        </span>
                                    @else
                                        <span class="badge-xosid" style="background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.15); color: #f59e0b;">
                                            <i data-lucide="clock" style="width: 10px;"></i> Pending Sync
                                        </span>
                                    @endif
                                @else
                                    <span class="badge-xosid" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: #64748b;">
                                        <i data-lucide="eye-off" style="width: 10px;"></i> Tidak Sinkron
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ site_url('bumdes_sync/toggle_sync/' . $p['id']) }}" class="btn-xosid" style="padding: 6px 14px !important; font-size: 10px !important; border-radius: 10px !important;">
                                    @if($p['is_hagtou_katalog'] == 1)
                                        <i data-lucide="eye-off" style="width: 12px; color: #ef4444;"></i> Arsipkan dari Hagtou
                                    @else
                                        <i data-lucide="eye" style="width: 12px; color: #10b981;"></i> Publikasikan
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function runHagtouSync() {
        // Trigger catalog sync via AJAX
        fetch(`{{ site_url('bumdes_sync/sync_all') }}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Sync Gagal: ' + data.message);
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi saat memproses sinkronisasi.');
            });
    }
</script>
@endsection
