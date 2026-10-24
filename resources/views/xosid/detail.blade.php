@extends('xosid.portal_layout')

@section('content')
@php
    $article_title = addslashes($post['judul'] ?? '');
    $article_desc = addslashes(substr(strip_tags($post['isi'] ?? ''), 0, 200));
    $article_date = date('c', strtotime($post['tgl_upload'] ?? date('Y-m-d')));
    $article_img = (!empty($post['gambar'])) ? AmbilFotoArtikel($post['gambar'], 'sedang') : base_url('assets/images/xosid_default_news.png');
    $site_name = ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? 'Bumi Daya'));
@endphp

<!-- GOOGLE NEWSARTICLE JSON-LD RICH SNIPPET SCHEMA -->
<script type="application/ld+json">
<?php
echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $article_title,
    'image' => [$article_img],
    'datePublished' => $article_date,
    'dateModified' => $article_date,
    'author' => [[
        '@type' => 'Organization',
        'name' => 'Pemerintah ' . $site_name,
        'url' => base_url()
    ]],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Pemerintah ' . $site_name,
        'logo' => [
            '@type' => 'ImageObject',
            'url' => favico_desa()
        ]
    ],
    'description' => $article_desc
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
</script>

<div class="max-w-4xl mx-auto animate__animated animate__fadeIn">
    <!-- BREADCRUMBS -->
    <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-6 select-none uppercase tracking-wider">
        <a href="{{ site_url() }}" class="hover:text-cyan-400 transition-colors">Beranda</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 opacity-50"></i>
        <a href="{{ site_url('kabar-desa') }}" class="hover:text-cyan-400 transition-colors">Kabar Desa</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 opacity-50"></i>
        @if ($post['id_kategori'])
            <span class="text-cyan-400 font-black">{{ $post['kategori'] ?? 'Kabar Desa' }}</span>
        @else
            <span class="text-cyan-400 font-black">Umum</span>
        @endif
    </nav>

    <!-- HORIZONTAL AD SLOT (Sponsor Billboard) -->
    <div class="xosid-glass p-5 rounded-3xl mb-8 border border-white/5 relative overflow-hidden group shadow-2xl">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500"></div>
        <div class="relative z-10 flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
            <div>
                <span class="text-[8px] font-black tracking-[0.2em] bg-indigo-500/20 text-indigo-400 px-2 py-0.5 rounded-full uppercase">IKLAN SPONSOR DESA</span>
                <h4 class="text-sm font-extrabold text-white mt-1.5 font-title">Pusat Pemasangan Iklan & Informasi Premium</h4>
                <p class="text-[10px] text-slate-300 mt-0.5 leading-relaxed font-semibold">Optimalkan promosi bisnis Anda langsung ke ribuan pengunjung aktif portal desa kami. Slot Google AdSense didukung.</p>
            </div>
            <a href="https://wa.me/6281234567890?text=Saya%20tertarik%20memasang%20iklan%20di%20Website%20{{ rawurlencode(ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? ''))) }}" target="_blank" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-600/35 active:scale-95 flex items-center gap-1.5 select-none">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Hubungi Pemasaran
            </a>
        </div>
    </div>

    <!-- ARTICLE HEADER -->
    <header class="mb-8">
        <!-- Category tag -->
        @if ($post['id_kategori'])
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-black rounded-lg uppercase tracking-wider mb-4">
                <i data-lucide="tag" class="w-3 h-3"></i> {{ $post['kategori'] }}
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black rounded-lg uppercase tracking-wider mb-4">
                <i data-lucide="newspaper" class="w-3 h-3"></i> Kabar Desa
            </span>
        @endif

        <!-- Title -->
        <h1 class="text-2xl sm:text-4xl font-extrabold text-white leading-tight mb-6 font-title tracking-tight">
            {{ $post['judul'] }}
        </h1>

        <!-- Byline & Meta Info Card (Kumparan/Detik Style) -->
        <div class="xosid-glass p-5 rounded-[24px] border border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
            <div class="flex items-center gap-3.5">
                <!-- Author Circle Avatar -->
                <div class="w-11 h-11 bg-indigo-600 rounded-2xl flex items-center justify-center font-bold text-white text-base shadow-lg shadow-indigo-600/30 uppercase font-title select-none">
                    {{ strtoupper(substr(trim($post['owner'] ?? 'A'), 0, 1)) }}
                </div>
                <div>
                    <span class="text-xs font-black text-white flex items-center gap-1.5">
                        {{ $post['owner'] ?? 'Admin Desa' }}
                        <span class="w-3.5 h-3.5 bg-cyan-500/20 rounded-full flex items-center justify-center border border-cyan-500/30" title="Jurnalis Resmi Desa">
                            <i data-lucide="check" class="text-cyan-400 w-2.5 h-2.5"></i>
                        </span>
                    </span>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 mt-0.5 tracking-wide">
                        <span><i data-lucide="calendar" class="inline w-3 h-3 mr-1 text-indigo-400"></i> {{ $post['tgl_upload_local'] }}</span>
                        <span>•</span>
                        <span><i data-lucide="eye" class="inline w-3 h-3 mr-1 text-cyan-400"></i> Dibaca {{ number_format($post['hit']) }} kali</span>
                    </div>
                </div>
            </div>

            <!-- Share Buttons -->
            <div class="flex items-center gap-2">
                <a href="https://api.whatsapp.com/send?text={{ rawurlencode($post['judul']) }}%20{{ rawurlencode(current_url()) }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 shadow-md" title="Bagikan ke WhatsApp">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                </a>
                <a href="https://telegram.me/share/url?url={{ rawurlencode(current_url()) }}&text={{ rawurlencode($post['judul']) }}" target="_blank" class="w-9 h-9 rounded-xl bg-[#0088cc]/10 border border-[#0088cc]/20 text-[#0088cc] hover:bg-[#0088cc] hover:text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 shadow-md" title="Bagikan ke Telegram">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode(current_url()) }}" target="_blank" class="w-9 h-9 rounded-xl bg-[#1877F2]/10 border border-[#1877F2]/20 text-[#1877F2] hover:bg-[#1877F2] hover:text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 shadow-md" title="Bagikan ke Facebook">
                    <i data-lucide="facebook" class="w-4 h-4"></i>
                </a>
                <button onclick="copyToClipboard('{{ current_url() }}')" class="w-9 h-9 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 hover:bg-cyan-500 hover:text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 shadow-md" title="Salin Tautan">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- HERO IMAGE -->
    @php
        $image_path = FCPATH . 'desa/upload/artikel/sedang_' . $post['gambar'];
        $has_image = ($post['gambar'] && is_file($image_path));
        $image_url = $has_image ? AmbilFotoArtikel($post['gambar'], 'sedang') : base_url('assets/images/xosid_default_news.png');
    @endphp
    <div class="relative rounded-[32px] overflow-hidden xosid-glass p-1 border border-white/5 mb-4 shadow-2xl">
        <img src="{{ $image_url }}" alt="{{ $post['judul'] }}" class="w-full h-auto max-h-[500px] object-cover rounded-[28px] hover:scale-[1.01] transition duration-500">
    </div>
    @if ($has_image)
        <span class="block text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-8">
            <i data-lucide="camera" class="inline w-3.5 h-3.5 mr-1 text-indigo-400"></i> Dokumentasi: Pemerintah Desa {{ $desa['nama_desa'] }}
        </span>
    @else
        <span class="block text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-8">
            <i data-lucide="zap" class="inline w-3.5 h-3.5 mr-1 text-cyan-400"></i> Portal Informasi Desa {{ $desa['nama_desa'] }}
        </span>
    @endif

    <!-- ARTICLE CONTENT BODY -->
    <article class="premium-news-body text-slate-800 text-lg leading-relaxed space-y-6 mb-8 font-primary">
        {!! $post['isi'] !!}
    </article>

    <!-- IN-CONTENT AD CARD (Lapak Desa Promotion) -->
    <div class="xosid-glass p-6 rounded-3xl border border-white/10 relative overflow-hidden group shadow-2xl my-10">
        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/5 to-indigo-500/5"></div>
        <div class="absolute -top-20 -right-20 w-60 h-60 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-all duration-700"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4 flex-col sm:flex-row text-center sm:text-left">
                <div class="w-12 h-12 bg-purple-500/20 rounded-2xl flex items-center justify-center border border-purple-500/30 group-hover:rotate-12 transition-transform">
                    <i data-lucide="shopping-bag" class="text-purple-400 w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-[8px] font-black tracking-[0.2em] text-purple-400 uppercase">USAHA LOKAL DESA</span>
                    <h4 class="text-sm font-extrabold text-white mt-0.5 font-title">Lapak Niaga Warga Desa</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5 leading-relaxed font-semibold">Dukung produk UMKM lokal unggulan kami. Belanja murah, langsung dari produsen.</p>
                </div>
            </div>
            <a href="{{ site_url('lapak') }}" class="px-5 py-3 bg-purple-600 hover:bg-purple-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-purple-600/20 active:scale-95 flex items-center gap-1">
                Kunjungi Lapak <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>

    <!-- SUPPORTING IMAGES GALLERY -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 my-10">
        @for ($i = 1; $i <= 3; $i++)
            @if ($post['gambar' . $i])
                <div class="rounded-2xl overflow-hidden xosid-glass p-1 border border-white/5 hover:border-cyan-500/30 transition-all hover:scale-[1.02] duration-300">
                    <img src="{{ AmbilFotoArtikel($post['gambar' . $i], 'sedang') }}" alt="Foto Lampiran" class="w-full h-40 object-cover rounded-xl">
                </div>
            @endif
        @endfor
    </div>

    <!-- DOCUMENT ATTACHMENT BOX -->
    @if ($post['dokumen'])
        <div class="xosid-glass p-5 rounded-2xl border border-white/5 my-8 shadow-lg">
            <div class="flex items-center gap-4 flex-col sm:flex-row text-center sm:text-left">
                <div class="w-12 h-12 bg-red-500/20 border border-red-500/30 rounded-2xl flex items-center justify-center">
                    <i data-lucide="file-text" class="text-red-400 w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider font-title">Unduh Lampiran Resmi</h4>
                    <span class="text-[10px] text-slate-400 font-bold tracking-wide">{{ $post['dokumen'] }}</span>
                </div>
                <a href="{{ ci_route('first.unduh_dokumen_artikel', $post['id']) }}" class="px-5 py-2.5 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/30 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-1.5">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh File
                </a>
            </div>
        </div>
    @endif

    <!-- BOTTOM SHARE BAR -->
    <div class="flex flex-col sm:flex-row justify-between items-center py-6 border-t border-b border-white/5 my-10 gap-4">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2"><i data-lucide="share-2" class="text-cyan-400 w-4 h-4"></i> Bagikan berita ini ke warga lainnya</span>
        <div class="flex gap-2">
            <a href="https://api.whatsapp.com/send?text={{ rawurlencode($post['judul']) }}%20{{ rawurlencode(current_url()) }}" target="_blank" class="px-5 py-2.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-1.5">
                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i> WhatsApp
            </a>
            <a href="https://telegram.me/share/url?url={{ rawurlencode(current_url()) }}&text={{ rawurlencode($post['judul']) }}" target="_blank" class="px-5 py-2.5 bg-[#0088cc]/10 border border-[#0088cc]/20 text-[#0088cc] hover:bg-[#0088cc] hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-1.5">
                <i data-lucide="send" class="w-3.5 h-3.5"></i> Telegram
            </a>
        </div>
    </div>

    <!-- RELATED ARTICLES GRID -->
    @if (isset($related) && count($related) > 0)
        <div class="my-12">
            <h3 class="text-lg font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-5 bg-cyan-500 rounded-full shadow-[0_0_10px_rgba(6,182,212,0.5)]"></span>
                <span>Berita Terkait Lainnya</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($related as $r)
                    <a href="{{ site_url('artikel/' . date('Y/m/d', strtotime($r->tgl_upload)) . '/' . $r->slug) }}" class="group block xosid-glass rounded-3xl border border-white/5 overflow-hidden hover:border-cyan-500/30 hover:scale-[1.02] transition-all duration-300 shadow-xl">
                        @php
                            $r_image_path = FCPATH . 'desa/upload/artikel/sedang_' . $r->gambar;
                            $has_r_image = ($r->gambar && is_file($r_image_path));
                            $r_image_url = $has_r_image ? AmbilFotoArtikel($r->gambar, 'sedang') : base_url('assets/images/xosid_default_news.png');
                        @endphp
                        <div class="w-full h-36 overflow-hidden relative">
                            <img src="{{ $r_image_url }}" alt="{{ $r->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                        </div>
                        <div class="p-5">
                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest block mb-1.5">{{ $r->tgl_upload_local }}</span>
                            <h4 class="text-xs font-bold text-white group-hover:text-cyan-400 transition-colors line-clamp-2 leading-relaxed font-title uppercase tracking-wide">
                                {{ $r->judul }}
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- TOAST ALERT SYSTEM -->
<div id="premium-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] pointer-events-none transform translate-y-10 opacity-0 transition-all duration-350">
    <div class="xosid-glass px-6 py-3 rounded-2xl border border-emerald-500/30 flex items-center gap-2.5 shadow-2xl backdrop-blur-md">
        <div class="w-6 h-6 bg-emerald-500/20 rounded-lg flex items-center justify-center border border-emerald-500/30">
            <i data-lucide="check" class="text-emerald-400 w-3.5 h-3.5"></i>
        </div>
        <span class="text-[11px] font-bold text-white uppercase tracking-wider">Tautan berita berhasil disalin ke papan klip!</span>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            var toast = document.getElementById('premium-toast');
            toast.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(function() {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
            }, 2500);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>

<style>
    /* Premium news typography styling */
    .premium-news-body {
        font-size: 1.1rem;
        line-height: 1.9;
        letter-spacing: 0.01em;
    }
    
    .premium-news-body,
    .premium-news-body p,
    .premium-news-body div,
    .premium-news-body span,
    .premium-news-body li,
    .premium-news-body td,
    .premium-news-body th {
        color: #1e293b;
        line-height: 1.9;
        font-size: 1.05rem;
    }
    
    .premium-news-body p {
        margin-bottom: 1.5rem;
    }

    html.light-mode .premium-news-body,
    html.light-mode .premium-news-body p,
    html.light-mode .premium-news-body div,
    html.light-mode .premium-news-body span,
    html.light-mode .premium-news-body li,
    html.light-mode .premium-news-body td,
    html.light-mode .premium-news-body th {
        color: #0f172a !important; /* Supreme contrast dark slate for light mode */
    }

    html:not(.light-mode) .premium-news-body,
    html:not(.light-mode) .premium-news-body p,
    html:not(.light-mode) .premium-news-body div,
    html:not(.light-mode) .premium-news-body span,
    html:not(.light-mode) .premium-news-body li {
        color: #cbd5e1 !important;
    }

    .premium-news-body strong,
    .premium-news-body b {
        color: #0f172a !important;
        font-weight: 700;
    }

    html:not(.light-mode) .premium-news-body strong,
    html:not(.light-mode) .premium-news-body b {
        color: #ffffff !important;
    }

    .premium-news-body h1,
    .premium-news-body h2,
    .premium-news-body h3,
    .premium-news-body h4,
    .premium-news-body h5,
    .premium-news-body h6 {
        color: #0f172a !important;
        font-weight: 800;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    html:not(.light-mode) .premium-news-body h1,
    html:not(.light-mode) .premium-news-body h2,
    html:not(.light-mode) .premium-news-body h3,
    html:not(.light-mode) .premium-news-body h4,
    html:not(.light-mode) .premium-news-body h5,
    html:not(.light-mode) .premium-news-body h6 {
        color: #ffffff !important;
    }

    .premium-news-body blockquote {
        border-left: 4px solid #2554C7;
        background: #f8fafc;
        padding: 1.25rem 1.75rem;
        border-radius: 0 16px 16px 0;
        margin: 1.75rem 0;
        font-style: italic;
        color: #334155 !important;
    }

    html:not(.light-mode) .premium-news-body blockquote {
        border-left-color: #06b6d4;
        background: rgba(6, 182, 212, 0.05);
        color: #94a3b8 !important;
    }

    .premium-news-body a {
        color: #2554C7 !important;
        text-decoration: underline;
        font-weight: 600;
    }

    html:not(.light-mode) .premium-news-body a {
        color: #22d3ee !important;
    }

    .premium-news-body img {
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.08);
        margin: 2rem auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
</style>
@endsection
