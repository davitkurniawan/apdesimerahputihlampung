@extends('theme::layouts.' . $layout)
@php
    $post = $single_artikel;
    $alt_slug = PREMIUM ? 'artikel' : 'first';
    
    // Get full current URL for sharing
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080') . $_SERVER['REQUEST_URI'];
    $share_url = rawurlencode($current_url);
    $share_title = rawurlencode($post['judul'] ?? '');
    
    // Author Initial for avatar
    $author = $post['owner'] ?? 'Admin';
    $author_initial = strtoupper(substr(trim($author), 0, 1));
@endphp
@include('theme::commons.asset_highcharts')
@section('content')
    @if ($post)
        <div class="premium-news-portal animate__animated animate__fadeIn">
            <!-- Breadcrumbs -->
            <nav role="navigation" aria-label="navigation" class="text-xs mb-4 text-slate-400">
                <ol class="flex items-center space-x-2 flex-wrap">
                    <li><a href="{{ ci_route() }}" class="hover:text-cyan-400 transition-colors">Beranda</a></li>
                    <li class="before:content-['/'] before:pr-2 before:text-slate-600">
                        @if ($post['kategori'])
                            <a href="{{ site_url('kabar-desa?kategori=' . $post['kat_slug']) }}" class="hover:text-cyan-400 transition-colors">
                                {{ $post['kategori'] }}
                            </a>
                        @else
                            <span class="text-slate-500">Artikel</span>
                        @endif
                    </li>
                </ol>
            </nav>

            <!-- TOP ADVERTISEMENT SLOT (Google Adsense / Local Sponsor Billboard) -->
            <div class="premium-ad-slot-horizontal mb-6 group">
                <div class="premium-ad-inner">
                    <span class="premium-ad-tag">IKLAN SPONSOR DESA</span>
                    <div class="premium-ad-placeholder">
                        <i class="fas fa-bullhorn text-xl mb-1 text-cyan-400/40 group-hover:scale-110 transition duration-300"></i>
                        <p class="premium-ad-title">Pasang Iklan Bisnis Anda Di Sini</p>
                        <p class="premium-ad-desc font-title">Jangkau ribuan pembaca setia setiap harinya. Hubungi Admin Desa.</p>
                        <a href="https://wa.me/6281234567890?text=Saya%20tertarik%20memasang%20iklan%20di%20Website%20Desa%20Bumi%20Daya" target="_blank" class="premium-ad-cta select-none">
                            <i class="fab fa-whatsapp mr-1"></i> Mulai Promosi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Header Info -->
            <header class="mb-6">
                <!-- Category Badge -->
                @if ($post['kategori'])
                    <a href="{{ site_url('kabar-desa?kategori=' . $post['kat_slug']) }}" class="premium-news-badge mb-3">
                        <i class="fas fa-tags mr-1.5 text-[10px]"></i> {{ $post['kategori'] }}
                    </a>
                @else
                    <span class="premium-news-badge mb-3">
                        <i class="fas fa-newspaper mr-1.5 text-[10px]"></i> Kabar Desa
                    </span>
                @endif

                <!-- Headline Title -->
                <h1 class="premium-news-title font-title">
                    {{ $post['judul'] }}
                </h1>

                <!-- Byline & Meta Info Card (Kumparan/Detik Style) -->
                <div class="premium-news-meta-card">
                    <div class="premium-news-author-section">
                        <!-- Stylized Circle Avatar -->
                        <div class="premium-news-author-avatar font-title select-none">
                            {{ $author_initial }}
                        </div>
                        <div class="premium-news-author-info">
                            <span class="premium-news-author-name">
                                {{ $author }} 
                                <span class="premium-verified-badge" title="Jurnalis Resmi Desa">
                                    <i class="fas fa-check text-[8px] text-white"></i>
                                </span>
                            </span>
                            <div class="premium-news-date-hit">
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $post['tgl_upload_local'] }}</span>
                                <span class="before:content-['•'] before:pr-2 before:inline-block">
                                    <i class="far fa-eye mr-1"></i> Dibaca {{ hit($post['hit']) }} kali
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Share Action Buttons -->
                    <div class="premium-news-share-buttons">
                        <a href="https://api.whatsapp.com/send?text={{ $share_title }}%20{{ $share_url }}" target="_blank" class="premium-news-share-btn text-[#25D366] hover:bg-[#25D366]/10 hover:border-[#25D366]/40" title="Bagikan ke WhatsApp">
                            <i class="fab fa-whatsapp text-inherit"></i>
                        </a>
                        <a href="https://telegram.me/share/url?url={{ $share_url }}&text={{ $share_title }}" target="_blank" class="premium-news-share-btn text-[#0088cc] hover:bg-[#0088cc]/10 hover:border-[#0088cc]/40" title="Bagikan ke Telegram">
                            <i class="fab fa-telegram-plane text-inherit"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $share_url }}" target="_blank" class="premium-news-share-btn text-[#1877F2] hover:bg-[#1877F2]/10 hover:border-[#1877F2]/40" title="Bagikan ke Facebook">
                            <i class="fab fa-facebook-f text-inherit"></i>
                        </a>
                        <button onclick="copyToClipboard('{{ $current_url }}')" class="premium-news-share-btn text-cyan-400 hover:bg-cyan-500/10 hover:border-cyan-500/40" title="Salin Tautan Artikel">
                            <i class="far fa-copy text-inherit"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Hero Image Section -->
            @if ($post['gambar'])
                <div class="premium-news-hero-container">
                    <a href="{{ AmbilFotoArtikel($post['gambar'], 'sedang') }}" class="block overflow-hidden" data-fancybox="images">
                        <img src="{{ AmbilFotoArtikel($post['gambar'], 'sedang') }}" alt="{{ $post['judul'] }}" class="premium-news-hero-img">
                    </a>
                </div>
                <span class="premium-news-hero-caption font-title">
                    <i class="fas fa-camera mr-1"></i> Dokumentasi: Pemerintah Desa Bumi Daya
                </span>
            @endif

            <!-- Article Body Content -->
            <div class="premium-news-body">
                {!! $post['isi'] !!}
            </div>

            <!-- In-Content Ad Card (Village Business Promotion Slot / Mid-Adsense) -->
            <div class="premium-inline-ad-card group">
                <div class="premium-inline-ad-glow"></div>
                <div class="premium-inline-ad-content">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="premium-ad-icon-wrapper">
                                <i class="fas fa-store text-xl text-purple-400 group-hover:rotate-12 transition duration-300"></i>
                            </div>
                            <div>
                                <span class="premium-ad-sponsor-label">USAHA LOKAL MANDIRI</span>
                                <h4 class="premium-ad-sponsor-title">Lapak Desa Bumi Daya</h4>
                                <p class="premium-ad-sponsor-desc font-title">Dukung produk UMKM lokal warga desa kami. Belanja murah, langsung dari produsen.</p>
                            </div>
                        </div>
                        <a href="{{ site_url('index.php/lapak') }}" class="premium-ad-sponsor-btn select-none">
                            Kunjungi Lapak <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Supporting Images Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-8">
                @for ($i = 1; $i <= 3; $i++)
                    @if ($post['gambar' . $i])
                        <div class="premium-news-subhero-container">
                            <a href="{{ AmbilFotoArtikel($post['gambar' . $i], 'sedang') }}" class="block overflow-hidden" data-fancybox="images">
                                <img src="{{ AmbilFotoArtikel($post['gambar' . $i], 'sedang') }}" alt="{{ $post['nama'] ?? 'Foto Lampiran' }}" class="premium-news-subhero-img">
                            </a>
                        </div>
                    @endif
                @endfor
            </div>

            <!-- Document Attachments -->
            @if ($post['dokumen'])
                <div class="premium-news-attachment-box">
                    <div class="flex items-center gap-3">
                        <div class="premium-attachment-icon">
                            <i class="fas fa-file-pdf text-xl text-red-400"></i>
                        </div>
                        <div>
                            <h4 class="premium-attachment-title">Unduh Dokumen Lampiran</h4>
                            <a href="{{ ci_route('first.unduh_dokumen_artikel', $post['id']) }}" class="premium-attachment-link">
                                <i class="fas fa-download mr-1"></i> {{ $post['dokumen'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Share Bottom Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-center py-6 border-t border-b border-white/5 my-8 gap-4">
                <span class="text-sm text-slate-400"><i class="fas fa-share-alt mr-2 text-cyan-400"></i> Bagikan berita berkelas ini ke rekan Anda</span>
                <div class="flex gap-2">
                    <a href="https://api.whatsapp.com/send?text={{ $share_title }}%20{{ $share_url }}" target="_blank" class="premium-bottom-share-btn bg-[#25D366]/10 hover:bg-[#25D366] hover:text-white border-[#25D366]/20">
                        <i class="fab fa-whatsapp mr-1.5"></i> WhatsApp
                    </a>
                    <a href="https://telegram.me/share/url?url={{ $share_url }}&text={{ $share_title }}" target="_blank" class="premium-bottom-share-btn bg-[#0088cc]/10 hover:bg-[#0088cc] hover:text-white border-[#0088cc]/20">
                        <i class="fab fa-telegram-plane mr-1.5"></i> Telegram
                    </a>
                </div>
            </div>

            <!-- Comments Section -->
            @include('theme::partials.artikel.comment')
        </div>

        <!-- Dynamic Clipboard Copied Toast Alert -->
        <div id="premium-toast" class="premium-toast-hide">
            <div class="premium-toast-content">
                <i class="fas fa-check-circle text-emerald-400 text-lg mr-2 animate__animated animate__bounceIn"></i>
                <span class="text-sm font-title text-white">Tautan berita berhasil disalin ke papan klip!</span>
            </div>
        </div>

        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    var toast = document.getElementById('premium-toast');
                    toast.className = 'premium-toast-show animate__animated animate__fadeInUp';
                    setTimeout(function() {
                        toast.className = 'premium-toast-hide animate__animated animate__fadeOutDown';
                    }, 2500);
                }, function(err) {
                    console.error('Could not copy text: ', err);
                });
            }
        </script>
    @else
        @include('theme::commons.404')
    @endif
@endsection
