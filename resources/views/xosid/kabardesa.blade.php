@extends('xosid.portal_layout')

@section('content')
    @php
        $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        $is_esensial = ($tenant_paket === 'esensial');
        $accent_color = $is_esensial ? '#4895ef' : '#06b6d4';
    @endphp
    <div class="space-y-12 animate__animated animate__fadeIn">
        <!-- TOP PHOTO SLIDER (INSTAGRAM 4:5 SOCIAL MEDIA FEED STYLE ON MOBILE) -->
        <div class="relative w-full h-[460px] md:h-[420px] bg-slate-900 border {{ $is_esensial ? 'border-[#153E7E]/30 shadow-2xl shadow-[#153E7E]/20' : 'border-white/10 shadow-2xl' }} rounded-3xl overflow-hidden group transition-all duration-300">
            <!-- Slides Container -->
            <div id="xosid-hero-slider" class="relative w-full h-full">
                @php
                    $slides = [
                        [
                            'image' => '/assets/images/xosid_office.png',
                            'badge' => 'Layanan Publik',
                            'title' => 'Pusat Pelayanan Digital Terpadu',
                            'desc' => 'Pengurusan surat mandiri secara daring (online), cepat, transparan, dan terintegrasi penuh.',
                            'btn_text' => 'Layanan Mandiri',
                            'btn_url' => site_url('xoSID-login')
                        ],
                        [
                            'image' => '/assets/images/xosid_bg_desa.png',
                            'badge' => 'Pesona Desa',
                            'title' => 'Keindahan Alam & Harmoni Warga ' . ucwords($desa['nama_desa'] ?? 'Bumi Daya'),
                            'desc' => 'Sinergi alam yang asri dengan inovasi pelayanan terpadu untuk kemandirian desa berkelanjutan.',
                            'btn_text' => 'Jelajahi Profil',
                            'btn_url' => site_url('profil')
                        ],
                        [
                            'image' => '/assets/images/xosid_bg_sunset.jpg',
                            'badge' => 'Ekonomi & Potensi',
                            'title' => 'Ekonomi Kreatif & UMKM Warga',
                            'desc' => 'Dukung produk lokal unggulan hasil karya warga desa melalui pasar rakyat digital terpadu.',
                            'btn_text' => 'Belanja di OlShop',
                            'btn_url' => site_url('lapak')
                        ],
                        [
                            'image' => '/assets/images/xosid_bg_gunung.png',
                            'badge' => 'Kecerdasan Data',
                            'title' => $is_esensial ? 'Statistik & Open Data Desa' : 'XO Intelligence Engine',
                            'desc' => $is_esensial ? 'Transparansi data statistik kependudukan dan pembangunan desa yang akurat dan terpercaya.' : 'Analisis komprehensif IDM & SDGs Desa untuk perumusan program pembangunan presisi.',
                            'btn_text' => 'Lihat Open Data',
                            'btn_url' => site_url('opendata')
                        ]
                    ];
                @endphp

                @foreach($slides as $index => $slide)
                    <div class="xosid-slide absolute inset-0 w-full h-full transition-all duration-700 ease-in-out" 
                         data-slide="{{ $index }}" 
                         style="{{ $index === 0 ? 'opacity: 1; z-index: 20; pointer-events: auto;' : 'opacity: 0; z-index: 10; pointer-events: none;' }}">
                        <!-- Image with Light Transparent Overlay -->
                        <div class="absolute inset-0 w-full h-full">
                            <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="w-full h-full object-cover">
                            <!-- Soft Social Media Gradient Mask -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#153E7E]/95 via-[#153E7E]/40 to-black/20 md:bg-gradient-to-r md:from-[#153E7E]/70 md:via-[#153E7E]/30 md:to-transparent"></div>
                        </div>
                        
                        <!-- Content (Instagram Story/Post Layout for Mobile) -->
                        <div class="absolute inset-0 flex flex-col justify-end md:justify-center p-6 pb-12 md:px-16 md:pb-0 max-w-2xl space-y-3 md:space-y-4 z-20">
                            <span class="inline-flex w-fit px-3 py-1 bg-[#153E7E] border border-white/40 text-white shadow-md rounded-full text-[9px] md:text-[10px] font-black tracking-widest uppercase backdrop-blur-md">
                                {{ $slide['badge'] }}
                            </span>
                            <h2 class="text-xl md:text-4xl font-black text-white leading-snug md:leading-tight drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)]">
                                {{ $slide['title'] }}
                            </h2>
                            <p class="text-slate-100 text-xs md:text-sm leading-relaxed font-medium line-clamp-2 max-w-xl drop-shadow-[0_1px_4px_rgba(0,0,0,0.8)]">
                                {{ $slide['desc'] }}
                            </p>
                            <div class="pt-1">
                                <a href="{{ $slide['btn_url'] }}" class="px-5 py-2.5 bg-[#153E7E] hover:bg-blue-900 border border-white/40 text-white shadow-lg shadow-black/30 rounded-xl text-xs font-black transition-all inline-flex items-center gap-2">
                                    <span>{{ $slide['btn_text'] }}</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Controls (Hidden on Mobile for IG Feed Aesthetics, Visible on Desktop) -->
            <button id="btn-hero-prev" onclick="prevSlide()" type="button" class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full {{ $is_esensial ? 'bg-[#153E7E]/85 hover:bg-[#153E7E] border border-white/40 text-white shadow-xl' : 'bg-slate-950/60 hover:bg-slate-950 border border-white/10 text-white' }} items-center justify-center transition-all z-30 opacity-90 hover:opacity-100 hover:scale-110 cursor-pointer" aria-label="Slide Sebelumnya">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <button id="btn-hero-next" onclick="nextSlide()" type="button" class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full {{ $is_esensial ? 'bg-[#153E7E]/85 hover:bg-[#153E7E] border border-white/40 text-white shadow-xl' : 'bg-slate-950/60 hover:bg-slate-950 border border-white/10 text-white' }} items-center justify-center transition-all z-30 opacity-90 hover:opacity-100 hover:scale-110 cursor-pointer" aria-label="Slide Berikutnya">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>

            <!-- Slide Indicators (Instagram Dots Style) -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-30">
                @foreach($slides as $index => $slide)
                    <button onclick="goToSlide({{ $index }})" type="button" class="xosid-slide-dot h-2 rounded-full transition-all duration-300 cursor-pointer" style="{{ $index === 0 ? 'width: 20px; background-color: #ffffff; box-shadow: 0 0 10px white;' : 'width: 7px; background-color: rgba(255, 255, 255, 0.4);' }}" data-slide-dot="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        </div>

        @if ($is_esensial)
        <!-- ESENSIAL QUICK STATS BAR -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="xosid-glass p-5 rounded-2xl border border-white/5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#4895ef]/10 border border-[#4895ef]/20 flex items-center justify-center text-[#4895ef] shrink-0">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-xl font-black text-white">{{ number_format($total_jiwa ?? 0, 0, ',', '.') }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Jiwa Penduduk</div>
                </div>
            </div>
            <div class="xosid-glass p-5 rounded-2xl border border-white/5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#4895ef]/10 border border-[#4895ef]/20 flex items-center justify-center text-[#4895ef] shrink-0">
                    <i data-lucide="home" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-xl font-black text-white">{{ number_format($total_kk ?? 0, 0, ',', '.') }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kepala Keluarga (KK)</div>
                </div>
            </div>
            <div class="xosid-glass p-5 rounded-2xl border border-white/5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#4895ef]/10 border border-[#4895ef]/20 flex items-center justify-center text-[#4895ef] shrink-0">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-xl font-black text-white">{{ number_format($total_dusun ?? 0, 0, ',', '.') }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Wilayah Dusun</div>
                </div>
            </div>
            <div class="xosid-glass p-5 rounded-2xl border border-white/5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#4895ef]/10 border border-[#4895ef]/20 flex items-center justify-center text-[#4895ef] shrink-0">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-xl font-black text-white">{{ number_format($total_rt ?? 0, 0, ',', '.') }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rukun Tetangga (RT)</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section Header -->
        <div class="text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 {{ $is_esensial ? 'bg-[#4895ef]/15 border border-[#4895ef]/30 text-[#4895ef]' : 'bg-indigo-600/15 border border-indigo-500/30 text-indigo-300' }} rounded-full text-[10px] font-extrabold tracking-widest uppercase select-none">
                <span class="w-1.5 h-1.5 {{ $is_esensial ? 'bg-[#4895ef]' : 'bg-indigo-400' }} rounded-full animate-ping"></span>
                Portal Informasi Utama
            </div>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white">KABAR <span class="text-transparent bg-clip-text bg-gradient-to-r {{ $is_esensial ? 'from-[#4895ef] to-blue-400' : 'from-cyan-400 to-indigo-400' }}">{{ strtoupper(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? '')) }}</span></h1>
            <p class="text-slate-400 text-sm max-w-xl mx-auto leading-relaxed">Arsip berita terintegrasi, pengumuman resmi pemerintahan, dan sorotan kegiatan pembangunan {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] ?? '' }} secara transparan.</p>
        </div>

        <!-- Category Menu Navigation -->
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ site_url('kabar-desa') }}" class="px-5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $current_category === '' ? ($is_esensial ? 'bg-[#4895ef] text-white shadow-[0_0_15px_rgba(72,149,239,0.3)]' : 'bg-indigo-600 text-white shadow-[0_0_15px_rgba(99,102,241,0.3)]') : 'bg-slate-900/60 border border-white/5 text-slate-400 hover:text-white hover:bg-slate-800/60' }}">SEMUA BERITA</a>
            @foreach($categories as $cat)
                @if($cat->slug === 'berita-pusat')
                    <a href="{{ site_url('kabar-desa?kategori=' . $cat->slug) }}" class="px-5 py-2.5 rounded-2xl text-xs font-extrabold transition-all flex items-center gap-2 {{ $current_category === $cat->slug ? 'bg-gradient-to-r from-cyan-500 to-indigo-600 text-white shadow-[0_0_20px_rgba(6,182,212,0.5)] border border-cyan-300/40' : 'bg-slate-900/80 border border-cyan-500/30 text-cyan-300 hover:text-white hover:border-cyan-400 shadow-[0_0_12px_rgba(6,182,212,0.25)]' }}">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-cyan-300 filter drop-shadow-[0_0_6px_rgba(6,182,212,0.8)]"></i>
                        <span>BERITA PUSAT</span>
                    </a>
                @else
                    <a href="{{ site_url('kabar-desa?kategori=' . $cat->slug) }}" class="px-5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $current_category === $cat->slug ? ($is_esensial ? 'bg-[#4895ef] text-white shadow-[0_0_15px_rgba(72,149,239,0.3)]' : 'bg-indigo-600 text-white shadow-[0_0_15px_rgba(99,102,241,0.3)]') : 'bg-slate-900/60 border border-white/5 text-slate-400 hover:text-white hover:bg-slate-800/60' }}">{{ strtoupper($cat->kategori) }}</a>
                @endif
            @endforeach
        </div>

        <!-- Main Content Layout with Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- News Feed Column (takes 3 cols on large screens) -->
            <div class="lg:col-span-3 space-y-12">
                <!-- MOBILE NEWSFEED COMPONENT (< 768px) -->
                @include('xosid.components.mobile_newsfeed', ['articles' => $articles])

                <!-- DESKTOP NEWSGRID COMPONENT (>= 768px) -->
                @if($articles->count() > 0)
                    <div class="hidden md:grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($articles as $index => $post)
                            @php
                                $url = site_url('artikel/' . date('Y/m/d/', strtotime($post->tgl_upload)) . $post->slug);
                                $abstract = strip_tags($post->isi);
                                $abstract = strlen($abstract) > 130 ? substr($abstract, 0, 130) . '...' : $abstract;
                                
                                $is_pusat = !empty($post->is_berita_pusat);
                                if ($is_pusat) {
                                    $image = (strpos($post->gambar, '/') === 0) ? $post->gambar : base_url('assets/images/xosid_default_news.png');
                                } else {
                                    $image_path = FCPATH . 'desa/upload/artikel/sedang_' . $post->gambar;
                                    $image = ($post->gambar && is_file($image_path)) ? AmbilFotoArtikel($post->gambar, 'sedang') : base_url('assets/images/xosid_default_news.png');
                                }
                            @endphp
                            
                            <!-- Article Card -->
                            <div class="xosid-glass rounded-3xl overflow-hidden group {{ $is_pusat ? 'border-2 border-cyan-500/40 shadow-[0_0_25px_rgba(6,182,212,0.25)]' : ($is_esensial ? 'hover:border-[#4895ef]/40' : 'hover:border-cyan-500/30') }} transition-all duration-300 flex flex-col justify-between h-[450px]">
                                <div class="relative h-48 overflow-hidden">
                                    @if($is_pusat)
                                        <span class="absolute top-4 left-4 z-10 px-3.5 py-1.5 bg-slate-950/80 border border-cyan-400/60 text-white font-extrabold shadow-[0_0_20px_rgba(6,182,212,0.6)] backdrop-blur-xl rounded-full text-[9px] tracking-widest uppercase flex items-center gap-2">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                                            </span>
                                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-cyan-300 filter drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]"></i>
                                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-300 via-sky-200 to-indigo-300 font-black">BERITA PUSAT</span>
                                        </span>
                                    @else
                                        <span class="absolute top-4 left-4 z-10 px-3 py-1 {{ $is_esensial ? 'bg-[#4895ef]/20 border border-[#4895ef]/30 text-[#4895ef] shadow-[0_0_10px_rgba(72,149,239,0.2)]' : 'bg-cyan-600/20 border border-cyan-500/30 text-cyan-300 shadow-[0_0_10px_rgba(6,182,212,0.15)]' }} backdrop-blur-md rounded-full text-[9px] font-bold tracking-wider uppercase">{{ $post->kategori_nama ?? 'Kabar Desa' }}</span>
                                    @endif
                                    <img src="{{ $image }}" alt="{{ $post->judul }}" class="w-full h-full object-cover group-hover:scale-105 duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                                </div>
                                <div class="p-6 flex-grow flex flex-col justify-between">
                                    <div class="space-y-3">
                                        <h3 class="text-lg font-extrabold tracking-tight text-white {{ $is_esensial ? 'group-hover:text-[#4895ef]' : 'group-hover:text-cyan-400' }} transition-colors line-clamp-2">
                                            <a href="{{ $url }}">{{ $post->judul }}</a>
                                        </h3>
                                        <p class="text-xs text-slate-400 line-clamp-3 leading-relaxed">{!! html_entity_decode($abstract) !!}</p>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-4 border-t border-white/5 mt-4">
                                        <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5 {{ $is_esensial ? 'text-[#4895ef]' : 'text-cyan-400' }}"></i> {{ date('d M Y', strtotime($post->tgl_upload)) }}</span>
                                        <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3.5 h-3.5 {{ $is_esensial ? 'text-[#4895ef]' : 'text-cyan-400' }}"></i> {{ $post->hit }} dibaca</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Standalone Pagination -->
                    @if($total_pages > 1)
                        <div class="flex items-center justify-center gap-2 mt-12 select-none">
                            @if($current_page > 1)
                                <a href="{{ site_url('kabar-desa?page=' . ($current_page - 1) . ($current_category ? '&kategori=' . $current_category : '')) }}" class="w-10 h-10 xosid-glass rounded-xl flex items-center justify-center text-slate-400 hover:text-white {{ $is_esensial ? 'hover:border-[#4895ef]/40' : 'hover:border-cyan-500/30' }} transition-all"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
                            @endif
                            @for($p = 1; $p <= $total_pages; $p++)
                                <a href="{{ site_url('kabar-desa?page=' . $p . ($current_category ? '&kategori=' . $current_category : '')) }}" class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs transition-all {{ $current_page === $p ? ($is_esensial ? 'bg-[#4895ef] text-white shadow-[0_0_15px_rgba(72,149,239,0.3)]' : 'bg-indigo-600 text-white shadow-[0_0_15px_rgba(99,102,241,0.3)]') : ($is_esensial ? 'xosid-glass text-slate-400 hover:text-white hover:border-[#4895ef]/40' : 'xosid-glass text-slate-400 hover:text-white hover:border-cyan-500/30') }}">{{ $p }}</a>
                            @endfor
                            @if($current_page < $total_pages)
                                <a href="{{ site_url('kabar-desa?page=' . ($current_page + 1) . ($current_category ? '&kategori=' . $current_category : '')) }}" class="w-10 h-10 xosid-glass rounded-xl flex items-center justify-center text-slate-400 hover:text-white {{ $is_esensial ? 'hover:border-[#4895ef]/40' : 'hover:border-cyan-500/30' }} transition-all"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                            @endif
                        </div>
                    @endif
                @else
                    <!-- Empty state -->
                    <div class="xosid-glass rounded-3xl p-12 text-center max-w-xl mx-auto space-y-4">
                        <div class="w-16 h-16 rounded-2xl {{ $is_esensial ? 'bg-[#4895ef]/10 border border-[#4895ef]/30 text-[#4895ef]' : 'bg-indigo-600/10 border border-indigo-500/30 text-indigo-400' }} flex items-center justify-center mx-auto">
                            <i data-lucide="folder-open" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Belum Ada Berita</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Maaf, saat ini belum ada berita atau artikel yang diterbitkan pada kategori ini. Silakan kembali beberapa saat lagi.</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar Column (takes 1 col on large screens) -->
            <div class="lg:col-span-1 space-y-6">
                @include('xosid.widgets.weather')
                @include('xosid.widgets.food_prices')
            </div>
        </div>

        <!-- BOTTOM ADVERTISEMENT SLOT -->
        <div class="w-full relative bg-slate-900/60 border border-white/5 rounded-3xl overflow-hidden p-6 shadow-2xl backdrop-blur-md group {{ $is_esensial ? 'hover:border-[#4895ef]/40' : 'hover:border-cyan-500/30' }} transition-all duration-300 mt-12">
            <div class="absolute top-2 left-6 text-[8px] font-bold text-slate-500 tracking-[0.2em] select-none">IKLAN SPONSOR DESA</div>
            <div class="flex flex-col items-center justify-center text-center py-4">
                <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center mb-3">
                    <i data-lucide="sparkles" class="text-[#4895ef] w-5 h-5"></i>
                </div>
                <h4 class="text-white font-extrabold text-sm tracking-wide">Ingin Usaha Anda Tampil Di Sini?</h4>
                <p class="text-slate-400 text-xs mt-1 mb-4 max-w-lg">Sponsori kumpulan berita kami dan tingkatkan penjualan usaha/UMKM lokal Anda dengan harga terjangkau.</p>
                <a href="https://wa.me/6281234567890?text=Saya%20tertarik%20memasang%20iklan%20di%20Website%20{{ rawurlencode(ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? ''))) }}" target="_blank" class="px-5 py-2 {{ $is_esensial ? 'bg-[#4895ef]/20 hover:bg-[#4895ef] border border-[#4895ef]/30 text-[#4895ef]' : 'bg-purple-600/20 hover:bg-purple-600 border border-purple-500/30 text-purple-400' }} hover:text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 select-none">
                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i> Hubungi Pemasaran
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function() {
            let currentSlideIndex = 0;
            let slideInterval = null;

            function showSlide(index) {
                const slides = document.querySelectorAll('.xosid-slide');
                const dots = document.querySelectorAll('.xosid-slide-dot');
                if (!slides || slides.length === 0) return;
                
                // Handle index bounds
                if (index >= slides.length) currentSlideIndex = 0;
                else if (index < 0) currentSlideIndex = slides.length - 1;
                else currentSlideIndex = index;

                // Animate slides via inline styles
                slides.forEach((slide, idx) => {
                    if (idx === currentSlideIndex) {
                        slide.style.opacity = '1';
                        slide.style.zIndex = '20';
                        slide.style.pointerEvents = 'auto';
                    } else {
                        slide.style.opacity = '0';
                        slide.style.zIndex = '10';
                        slide.style.pointerEvents = 'none';
                    }
                });

                // Update dots
                if (dots && dots.length > 0) {
                    dots.forEach((dot, idx) => {
                        if (idx === currentSlideIndex) {
                            dot.style.width = '24px';
                            dot.style.backgroundColor = '#ffffff';
                            dot.style.boxShadow = '0 0 10px white';
                        } else {
                            dot.style.width = '8px';
                            dot.style.backgroundColor = 'rgba(255, 255, 255, 0.4)';
                            dot.style.boxShadow = 'none';
                        }
                    });
                }
            }

            function nextSlide() {
                showSlide(currentSlideIndex + 1);
                resetAutoplay();
            }

            function prevSlide() {
                showSlide(currentSlideIndex - 1);
                resetAutoplay();
            }

            function goToSlide(index) {
                showSlide(index);
                resetAutoplay();
            }

            function startAutoplay() {
                clearInterval(slideInterval);
                slideInterval = setInterval(() => {
                    nextSlide();
                }, 6000);
            }

            function resetAutoplay() {
                clearInterval(slideInterval);
                startAutoplay();
            }

            // Expose globally for inline onclick handlers
            window.showSlide = showSlide;
            window.nextSlide = nextSlide;
            window.prevSlide = prevSlide;
            window.goToSlide = goToSlide;

            // Direct event listener attachments
            function initSliderListeners() {
                showSlide(0);
                startAutoplay();

                const btnPrev = document.getElementById('btn-hero-prev');
                const btnNext = document.getElementById('btn-hero-next');
                if (btnPrev) {
                    btnPrev.onclick = function(e) {
                        e.preventDefault();
                        prevSlide();
                    };
                }
                if (btnNext) {
                    btnNext.onclick = function(e) {
                        e.preventDefault();
                        nextSlide();
                    };
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSliderListeners);
            } else {
                initSliderListeners();
            }
        })();
    </script>
@endpush
