@extends('theme::layouts.right-sidebar')
@php
    $title = !empty($judul_kategori) ? $judul_kategori : 'Artikel Terkini';
    $slug = 'terkini';
    if (is_array($title)) {
        $slug = $title['slug'];
        $title = $title['kategori'];
    }
@endphp
@section('content')
    <!-- Tampilkan slider hanya di halaman awal. Tidak tampil pada daftar artikel di halaman kategori atau halaman selanjutnya serta halaman hasil pencarian -->
    @if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
        @include('theme::partials.slider')
    @endif

    <!-- NEURAL INFO GRID (DASHBOARD DEMOGRAFI FUTURISTIK) -->
    <div id="debug-routes" data-segments="{{ json_encode(request()->segments()) }}" data-cari="{{ json_encode($cari ?? null) }}" data-kategori="{{ json_encode($judul_kategori ?? null) }}"></div>
    @if (empty($cari) && empty($judul_kategori) && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
        @php
            $CI =& get_instance();
            // Fetch database counts safely
            $total_jiwa = $CI->db->where('status_dasar', 1)->count_all_results('tweb_penduduk');
            $total_kk = $CI->db->count_all('tweb_keluarga');
            $total_dusun = $CI->db->where('rt', '0')->where('rw', '0')->count_all_results('tweb_wil_clusterdesa');
            $total_rt = $CI->db->where('rt >', '0')->count_all_results('tweb_wil_clusterdesa');
            
            // Ambil nama desa dari config
            $desa_config = $CI->db->get('config')->row_array();
            $nama_desa = !empty($desa_config['nama_desa']) ? $desa_config['nama_desa'] : 'Supradesa';
        @endphp

        <style>
            .neural-grid-container {
                position: relative;
                width: 100%;
                margin-top: 20px;
                margin-bottom: 35px;
                border-radius: 16px;
                border: 1px solid rgba(6, 182, 212, 0.25);
                background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.8) 100%);
                padding: 24px;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                color: #fff;
                overflow: hidden;
                box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), inset 0 1px 0 0 rgba(255, 255, 255, 0.05);
                font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
            }
            
            .neural-glow-1 {
                position: absolute;
                top: -60px;
                left: -60px;
                width: 180px;
                height: 180px;
                border-radius: 50%;
                background: rgba(6, 182, 212, 0.2);
                filter: blur(50px);
                pointer-events: none;
            }

            .neural-glow-2 {
                position: absolute;
                bottom: -60px;
                right: -60px;
                width: 180px;
                height: 180px;
                border-radius: 50%;
                background: rgba(236, 72, 153, 0.15);
                filter: blur(50px);
                pointer-events: none;
            }

            .neural-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                padding-bottom: 16px;
                position: relative;
                z-index: 10;
            }

            .neural-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(6, 182, 212, 0.12);
                border: 1px solid rgba(6, 182, 212, 0.35);
                color: #22d3ee;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .neural-dot-pulse {
                width: 6px;
                height: 6px;
                background-color: #22d3ee;
                border-radius: 50%;
                display: inline-block;
                animation: pulse-glow-neural 1.8s infinite ease-in-out;
            }

            @keyframes pulse-glow-neural {
                0%, 100% { transform: scale(0.8); opacity: 0.5; box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7); }
                50% { transform: scale(1.2); opacity: 1; box-shadow: 0 0 8px 3px rgba(6, 182, 212, 0.7); }
            }

            .neural-grid {
                display: grid;
                grid-template-columns: repeat(1, minmax(0, 1fr));
                gap: 16px;
                position: relative;
                z-index: 10;
            }

            @media (min-width: 640px) {
                .neural-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (min-width: 1024px) {
                .neural-grid {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }
            }

            .neural-card {
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                padding: 20px;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                position: relative;
                overflow: hidden;
            }
            
            /* NEON TOP BORDERS */
            .neural-card.jiwa { border-top: 3px solid #06b6d4; }
            .neural-card.kk { border-top: 3px solid #ec4899; }
            .neural-card.dusun { border-top: 3px solid #a855f7; }
            .neural-card.rt { border-top: 3px solid #10b981; }

            .neural-card::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
                opacity: 0;
                transition: opacity 0.3s;
                z-index: 1;
            }

            /* WATERMARK ICONS */
            .neural-card::after {
                font-family: "Font Awesome 5 Free";
                font-weight: 900;
                position: absolute;
                right: -10px;
                bottom: -15px;
                font-size: 80px;
                opacity: 0.05;
                z-index: 0;
                transition: all 0.5s ease;
                transform: rotate(-10deg);
            }
            .neural-card.jiwa::after { content: "\f0c0"; color: #06b6d4; } /* fa-users */
            .neural-card.kk::after { content: "\f015"; color: #ec4899; } /* fa-home */
            .neural-card.dusun::after { content: "\f5a0"; color: #a855f7; } /* fa-map-marked-alt */
            .neural-card.rt::after { content: "\f500"; color: #10b981; } /* fa-user-friends */

            .neural-card:hover {
                transform: translateY(-8px);
                background: rgba(30, 41, 59, 0.8);
                box-shadow: 0 15px 30px -10px rgba(0,0,0,0.6);
            }
            
            .neural-card:hover::after {
                transform: scale(1.1) rotate(0deg);
                opacity: 0.15;
            }

            .neural-card.jiwa:hover { border-color: rgba(6, 182, 212, 0.6); box-shadow: 0 10px 30px rgba(6, 182, 212, 0.15); }
            .neural-card.kk:hover { border-color: rgba(236, 72, 153, 0.6); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.15); }
            .neural-card.dusun:hover { border-color: rgba(168, 85, 247, 0.6); box-shadow: 0 10px 30px rgba(168, 85, 247, 0.15); }
            .neural-card.rt:hover { border-color: rgba(16, 185, 129, 0.6); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.15); }

            .neural-card-label {
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 1px;
                text-transform: uppercase;
                margin-bottom: 8px;
                position: relative;
                z-index: 2;
            }

            .neural-card.jiwa .neural-card-label { color: #22d3ee; }
            .neural-card.kk .neural-card-label { color: #f472b6; }
            .neural-card.dusun .neural-card-label { color: #c084fc; }
            .neural-card.rt .neural-card-label { color: #34d399; }

            .neural-card-value-group {
                display: flex;
                align-items: baseline;
                gap: 6px;
                position: relative;
                z-index: 2;
            }

            .neural-card-value {
                font-family: 'Outfit', 'Poppins', sans-serif;
                font-size: 32px;
                font-weight: 900;
                line-height: 1;
                letter-spacing: -0.5px;
                color: #fff;
            }

            .neural-card-unit {
                font-size: 12px;
                color: #94a3b8;
                font-weight: 600;
            }

            .neural-card-footer {
                font-size: 11px;
                color: #94a3b8;
                margin-top: 15px;
                display: flex;
                align-items: center;
                gap: 8px;
                position: relative;
                z-index: 2;
            }

            .neural-indicator {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                box-shadow: 0 0 5px currentColor;
            }

            .neural-card.jiwa .neural-indicator { background-color: #06b6d4; color: #06b6d4; }
            .neural-card.kk .neural-indicator { background-color: #ec4899; color: #ec4899; }
            .neural-card.dusun .neural-indicator { background-color: #a855f7; color: #a855f7; }
            .neural-card.rt .neural-indicator { background-color: #10b981; color: #10b981; }

            .neural-title-main {
                font-family: 'Outfit', sans-serif;
                font-size: 22px;
                font-weight: 800;
                letter-spacing: -0.3px;
                color: #fff;
                margin: 4px 0 0 0;
            }

            .neural-subtitle {
                font-size: 11px;
                color: #94a3b8;
                margin: 2px 0 0 0;
                letter-spacing: 0.5px;
            }
        </style>

        <div class="neural-grid-container">
            <div class="neural-glow-1"></div>
            <div class="neural-glow-2"></div>

            <div class="neural-header">
                <div>
                    <span class="neural-badge">
                        <span class="neural-dot-pulse"></span>
                        Neural Real-time Sync
                    </span>
                    <h2 class="neural-title-main">Statistik Kependudukan {{ $nama_desa }}</h2>
                    <p class="neural-subtitle">Portal Informasi Supradesa Modern • KITASEDESA Engine</p>
                </div>
                <div class="hidden sm:block p-3 bg-cyan-950/40 border border-cyan-500/30 rounded-xl text-cyan-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                </div>
            </div>

            <div class="neural-grid">
                <!-- Jiwa Card -->
                <div class="neural-card jiwa">
                    <p class="neural-card-label">Total Jiwa</p>
                    <div class="neural-card-value-group">
                        <span class="neural-card-value neural-counter" data-target="{{ $total_jiwa }}">0</span>
                        <span class="neural-card-unit">Jiwa</span>
                    </div>
                    <p class="neural-card-footer">
                        <span class="neural-indicator"></span> Penduduk Terintegrasi
                    </p>
                </div>

                <!-- KK Card -->
                <div class="neural-card kk">
                    <p class="neural-card-label">Keluarga</p>
                    <div class="neural-card-value-group">
                        <span class="neural-card-value neural-counter" data-target="{{ $total_kk }}">0</span>
                        <span class="neural-card-unit">KK</span>
                    </div>
                    <p class="neural-card-footer">
                        <span class="neural-indicator"></span> Kepala Keluarga Aktif
                    </p>
                </div>

                <!-- Dusun Card -->
                <div class="neural-card dusun">
                    <p class="neural-card-label">Wilayah Dusun</p>
                    <div class="neural-card-value-group">
                        <span class="neural-card-value neural-counter" data-target="{{ $total_dusun }}">0</span>
                        <span class="neural-card-unit">Kewilayahan</span>
                    </div>
                    <p class="neural-card-footer">
                        <span class="neural-indicator"></span> Dusun Terdaftar
                    </p>
                </div>

                <!-- RT Card -->
                <div class="neural-card rt">
                    <p class="neural-card-label">Rukun Tetangga</p>
                    <div class="neural-card-value-group">
                        <span class="neural-card-value neural-counter" data-target="{{ $total_rt }}">0</span>
                        <span class="neural-card-unit">RT</span>
                    </div>
                    <p class="neural-card-footer">
                        <span class="neural-indicator"></span> Sektor Lingkungan
                    </p>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const counters = document.querySelectorAll('.neural-counter');
                const speed = 100; // Semakin kecil, semakin cepat

                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        const count = +counter.innerText.replace(/,/g, '');
                        
                        // Increment dinamis
                        const inc = target / speed;

                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc).toLocaleString('en-US');
                            setTimeout(updateCount, 15);
                        } else {
                            counter.innerText = target.toLocaleString('en-US');
                        }
                    };

                    // Memulai animasi dengan delay sedikit agar terlihat
                    setTimeout(updateCount, 300);
                });
            });
        </script>
    @endif

    <!-- Judul Kategori / Artikel Terkini -->
    <div class="flex justify-between items-center w-full mt-6 pb-4 mb-2">
        <h3 class="xosid-section-title flex items-center gap-2">
            <span class="xosid-section-title-dot"></span>
            {{ $title }}
        </h3>
        <a href="{{ site_url('arsip') }}" class="xosid-indeks-link flex items-center gap-1">
            Indeks <i class="fas fa-chevron-right text-[10px]"></i>
        </a>
    </div>

    @if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
        @include('theme::partials.headline')
    @endif

    @if ($artikel->count() > 0)
        <style>
            .xosid-card-grid {
                display: grid !important;
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
                gap: 32px !important;
            }
            @media (min-width: 640px) {
                .xosid-card-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
            }
        </style>
        <div class="xosid-card-grid mt-6">
            @foreach ($artikel as $post)
                @include('theme::partials.artikel.list', ['post' => $post])
            @endforeach
        </div>
        <div class="pagination space-y-1 flex-wrap w-full mt-8">
            @include('theme::commons.paging', ['paging_page' => $paging_page])
        </div>
    @else
        @include('theme::partials.artikel.empty', ['title' => $title])
    @endif
@endsection
