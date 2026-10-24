@php
    $bg_header = asset('assets/images/xosid_bg_neural_premium.png');
    $xosid_logo = asset('storage/uploads/xosid_logo_1780388384.png');
@endphp

<style>
    @keyframes xosid-kenburns {
        0% { transform: scale(1) translate(0, 0); }
        50% { transform: scale(1.08) translate(-1%, -1%); }
        100% { transform: scale(1) translate(0, 0); }
    }
    .xosid-animated-bg {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        animation: xosid-kenburns 30s ease-in-out infinite;
        z-index: 0;
    }
    .xosid-fullscreen-header {
        position: relative;
        overflow: hidden;
    }
    .xosid-hero-overlay, .xosid-hero-content {
        position: relative;
        z-index: 10;
    }
</style>

<div class="w-full px-0 lg:px-10 mt-0 lg:mt-4">
    <!-- Header setinggi layar penuh (min-h-[92vh]) dengan layout flex vertikal terpusat -->
    <header class="bg-slate-900 rounded-none lg:rounded-3xl xosid-fullscreen-header ring-1 ring-white/5 ring-inset shadow-2xl transform-gpu">
        
        <!-- Animated Background Layer -->
        <div class="xosid-animated-bg" style="background-image: url({{ $bg_header }});"></div>

        <!-- Modern Dark Radial Overlay -->
        <div class="xosid-hero-overlay"></div>

        <!-- Bagian Atas: Kategori Menu -->
        <div class="xosid-hero-content" style="z-index: 50 !important;">
            @include('theme::commons.category_menu')
        </div>

        <!-- Bagian Tengah (Hero Center): Judul Desa & Logo Glowing -->
        <section class="xosid-hero-content text-center flex-grow flex flex-col justify-center items-center space-y-6 px-4 py-8">
            <a href="{{ site_url('/') }}" class="group block max-w-4xl mx-auto space-y-6">
                <figure class="relative inline-block">
                    <!-- Glowing circular aura behind logo -->
                    <div class="xosid-logo-aura"></div>
                    <!-- Transparent Inline SVG Logo -->
                    <svg class="h-28 mx-auto xosid-logo-img drop-shadow-[0_0_15px_rgba(6,182,212,0.6)]" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="xGradient" x1="10" y1="10" x2="90" y2="90" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#06b6d4" />
                                <stop offset="1" stop-color="#8b5cf6" />
                            </linearGradient>
                            <linearGradient id="glowGradient" x1="0" y1="0" x2="100" y2="100">
                                <stop stop-color="#22d3ee" stop-opacity="0.8" />
                                <stop offset="1" stop-color="#c084fc" stop-opacity="0.8" />
                            </linearGradient>
                            <filter id="neonGlow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="4" result="blur" />
                                <feMerge>
                                    <feMergeNode in="blur" />
                                    <feMergeNode in="SourceGraphic" />
                                </feMerge>
                            </filter>
                        </defs>
                        <!-- Hexagon outer frame -->
                        <path d="M50 5 L93 25 L93 75 L50 95 L7 75 L7 25 Z" stroke="url(#xGradient)" stroke-width="2" fill="none" filter="url(#neonGlow)" />
                        <!-- Inner X geometric shape -->
                        <path d="M30 30 L70 70 M70 30 L30 70" stroke="url(#glowGradient)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" filter="url(#neonGlow)" />
                        <path d="M30 30 L70 70 M70 30 L30 70" stroke="#ffffff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </figure>
                
                <div class="space-y-3">
                    <!-- Glowing premium font for XOSID village name -->
                    <span class="xosid-premium-title">
                        {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}
                    </span>
                    <p class="text-slate-400 text-sm md:text-base font-semibold tracking-widest uppercase relative z-10">
                        {{ ucfirst(setting('sebutan_kecamatan_singkat')) }} {{ ucwords($desa['nama_kecamatan']) }} • 
                        {{ ucfirst(setting('sebutan_kabupaten_singkat')) }} {{ ucwords($desa['nama_kabupaten']) }} • 
                        Provinsi {{ ucwords($desa['nama_propinsi']) }}
                    </p>

                    <!-- Official Tagline Brand Statement -->
                    <div class="pt-4 pb-2 relative z-10 max-w-2xl mx-auto border-t border-cyan-500/10 mt-4">
                        <p class="text-xs text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400 font-extrabold tracking-wider leading-none uppercase">
                            xoSID SUPRADESA
                        </p>
                        <p class="text-[11px] text-slate-300 font-medium tracking-wide mt-1 leading-relaxed max-w-lg mx-auto">
                            Kemandirian, Kecerdasan dan Lompatan Teknologi Masa Depan Desa Indonesia.
                        </p>
                    </div>
                </div>
            </a>
            
            {{-- Commented out the marquee gallery track under the village name to keep the header clean as requested by the user --}}
            {{--
            @if ($w_gal)
                @php
                    $has_valid_galeri = false;
                    foreach ($w_gal as $album) {
                        if (is_file(LOKASI_GALERI . 'kecil_' . $album['gambar'])) {
                            $has_valid_galeri = true;
                            break;
                        }
                    }
                @endphp
                @if ($has_valid_galeri)
                    <div class="block w-10/12 lg:w-1/4 mx-auto overflow-hidden rounded-2xl border border-white/5 bg-slate-950/40 p-1 backdrop-blur-sm relative z-10">
                        <div class="marquee-track flex gap-3 pt-1">
                            @for ($i = 0; $i < 2; $i++)
                                @foreach ($w_gal as $album)
                                    @if (is_file(LOKASI_GALERI . 'kecil_' . $album['gambar']))
                                        @php $link = route('web.galeri.detail', $album['id']) @endphp
                                        <a href="{{ $link }}" class="block flex-shrink-0 w-32 h-20 rounded-xl overflow-hidden border border-white/10 hover:border-cyan-500/50 transition duration-300" title="{{ $album['nama'] }}" @if($i === 1) aria-hidden="true" @endif>
                                            <img src="{{ AmbilGaleri($album['gambar'], 'kecil') }}" alt="{{ $album['nama'] }}" class="w-32 h-20 object-cover hover:scale-110 transition duration-300">
                                        </a>
                                    @endif
                                @endforeach
                            @endfor
                        </div>
                    </div>
                @endif
            @endif
            --}}
        </section>

        <!-- Bagian Bawah: Teks Berjalan -->
        @if ($teks_berjalan)
            <div class="xosid-hero-content block px-4 bg-cyan-950/30 text-cyan-300 border-t border-cyan-500/15 py-3 text-xs relative overflow-hidden backdrop-blur-sm">
                <div class="marquee-track flex whitespace-nowrap">
                    @for ($i = 0; $i < 2; $i++)
                        @foreach ($teks_berjalan as $marquee)
                            <span class="px-4 border-r border-cyan-500/20 flex-shrink-0 font-medium" @if($i === 1) aria-hidden="true" @endif>
                                <i class="fas fa-bullhorn mr-2 text-cyan-400"></i>
                                {{ $marquee['teks'] }}
                                @if (trim($marquee['tautan']) && $marquee['judul_tautan'])
                                    <a href="{{ $marquee['tautan'] }}" class="text-cyan-400 hover:text-purple-400 ml-1 underline decoration-dotted">{{ $marquee['judul_tautan'] }}</a>
                                @endif
                            </span>
                        @endforeach
                    @endfor
                </div>
            </div>
        @endif
    </header>
    @include('theme::commons.main_menu')
    @include('theme::commons.mobile_menu')
</div>
