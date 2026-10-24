@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
    $accent_color = '#153E7E';
    
    // Fallback articles if variable not passed
    $feed_articles = $articles ?? collect([]);
@endphp

<!-- GEN Z MOBILE APP-STYLE NEWSFEED (Active on Mobile < 768px) -->
<div class="block md:hidden space-y-6 animate__animated animate__fadeIn">

    <!-- 1. INSTAGRAM-STYLE STORY HIGHLIGHTS BAR -->
    <div class="space-y-2.5">
        <div class="flex items-center justify-between px-1">
            <span class="text-[11px] font-black uppercase tracking-widest text-slate-500">Sorotan & Layanan Cepat</span>
            <span class="text-[10px] font-black text-[#153E7E] flex items-center gap-1.5 bg-[#153E7E]/10 px-2.5 py-0.5 rounded-full border border-[#153E7E]/20">
                <span class="w-1.5 h-1.5 bg-[#153E7E] rounded-full animate-ping"></span> Live Updates
            </span>
        </div>
        
        <div class="flex items-center gap-4 overflow-x-auto no-scrollbar py-2 px-1">
            <!-- 1. Layanan Warga / Surat Mandiri -->
            <a href="{{ site_url('surat-mandiri') }}" class="flex flex-col items-center gap-1.5 shrink-0 group">
                <div class="p-0.5 rounded-full bg-gradient-to-tr from-[#153E7E] via-sky-400 to-blue-600 shadow-md group-active:scale-95 transition-all">
                    <div class="w-14 h-14 rounded-full bg-blue-50 border-2 border-white flex items-center justify-center overflow-hidden relative shadow-sm">
                        <i data-lucide="file-text" class="w-6 h-6 text-[#153E7E] z-10"></i>
                    </div>
                </div>
                <span class="text-[11px] font-black text-slate-800 tracking-tight">Surat Mandiri</span>
            </a>

            <!-- 2. Presensi Digital Pamong -->
            <a href="{{ site_url('kiosk#presensi') }}" class="flex flex-col items-center gap-1.5 shrink-0 group">
                <div class="p-0.5 rounded-full bg-gradient-to-tr from-emerald-500 via-teal-400 to-[#153E7E] shadow-md group-active:scale-95 transition-all">
                    <div class="w-14 h-14 rounded-full bg-emerald-50 border-2 border-white flex items-center justify-center overflow-hidden relative shadow-sm">
                        <i data-lucide="clock" class="w-6 h-6 text-emerald-600 z-10"></i>
                    </div>
                </div>
                <span class="text-[11px] font-black text-slate-800 tracking-tight">Presensi Digital</span>
            </a>

            <!-- 3. Buku Tamu Digital -->
            <a href="{{ site_url('kiosk#buku-tamu') }}" class="flex flex-col items-center gap-1.5 shrink-0 group">
                <div class="p-0.5 rounded-full bg-gradient-to-tr from-amber-500 via-orange-400 to-[#153E7E] shadow-md group-active:scale-95 transition-all">
                    <div class="w-14 h-14 rounded-full bg-amber-50 border-2 border-white flex items-center justify-center overflow-hidden relative shadow-sm">
                        <i data-lucide="book-open" class="w-6 h-6 text-amber-600 z-10"></i>
                    </div>
                </div>
                <span class="text-[11px] font-black text-slate-800 tracking-tight">Buku Tamu</span>
            </a>

            <!-- 4. KataDesa AI -->
            <a href="{{ site_url('katadesa-ai') }}" class="flex flex-col items-center gap-1.5 shrink-0 group">
                <div class="p-0.5 rounded-full bg-gradient-to-tr from-purple-500 via-indigo-500 to-[#153E7E] shadow-md group-active:scale-95 transition-all">
                    <div class="w-14 h-14 rounded-full bg-purple-50 border-2 border-white flex items-center justify-center overflow-hidden relative shadow-sm">
                        <i data-lucide="bot" class="w-6 h-6 text-purple-600 z-10"></i>
                    </div>
                </div>
                <span class="text-[11px] font-black text-slate-800 tracking-tight">KataDesa AI</span>
            </a>

            <!-- 5. APBDes Transparansi -->
            <a href="{{ site_url('opendata') }}" class="flex flex-col items-center gap-1.5 shrink-0 group">
                <div class="p-0.5 rounded-full bg-gradient-to-tr from-cyan-400 via-blue-500 to-[#153E7E] shadow-md group-active:scale-95 transition-all">
                    <div class="w-14 h-14 rounded-full bg-cyan-50 border-2 border-white flex items-center justify-center overflow-hidden relative shadow-sm">
                        <i data-lucide="pie-chart" class="w-6 h-6 text-cyan-600 z-10"></i>
                    </div>
                </div>
                <span class="text-[11px] font-black text-slate-800 tracking-tight">Transparansi</span>
            </a>

            <!-- 6. Pengumuman -->
            <a href="{{ site_url('kabar-desa?kategori=pengumuman') }}" class="flex flex-col items-center gap-1.5 shrink-0 group">
                <div class="p-0.5 rounded-full bg-gradient-to-tr from-rose-500 via-pink-500 to-[#153E7E] shadow-md group-active:scale-95 transition-all">
                    <div class="w-14 h-14 rounded-full bg-rose-50 border-2 border-white flex items-center justify-center overflow-hidden relative shadow-sm">
                        <i data-lucide="bell" class="w-6 h-6 text-rose-600 z-10"></i>
                    </div>
                </div>
                <span class="text-[11px] font-black text-slate-800 tracking-tight">Pengumuman</span>
            </a>
        </div>
    </div>

    <!-- 2. KUMPARAN + THREADS HYBRID NEWSFEED CARDS (LIGHT GEN-Z CRISP THEME) -->
    <div class="space-y-5">
        @if($feed_articles && count($feed_articles) > 0)
            @foreach($feed_articles as $index => $post)
                @php
                    $post_obj = (object)$post;
                    $url = site_url('artikel/' . date('Y/m/d/', strtotime($post_obj->tgl_upload ?? date('Y-m-d'))) . ($post_obj->slug ?? ''));
                    $abstract = strip_tags($post_obj->isi ?? '');
                    $abstract = strlen($abstract) > 115 ? substr($abstract, 0, 115) . '...' : $abstract;
                    
                    $image_path = FCPATH . 'desa/upload/artikel/sedang_' . ($post_obj->gambar ?? '');
                    $image = (!empty($post_obj->gambar) && is_file($image_path)) ? AmbilFotoArtikel($post_obj->gambar, 'sedang') : base_url('assets/images/xosid_default_news.png');
                    
                    $read_time = max(1, ceil(str_word_count(strip_tags($post_obj->isi ?? '')) / 180));
                @endphp

                <!-- Feed Card (Gen Z Crisp High-Contrast Light Surface) -->
                <article class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300">
                    <!-- Card Header (Threads Style Bright Crisp Author Badge) -->
                    <div class="p-4 pb-3 flex items-center justify-between border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white p-1 border-2 border-[#2554C7]/30 shrink-0 shadow-md flex items-center justify-center">
                                @if(!empty($desa['logo']) && stripos($desa['logo'], 'opensid') === false)
                                    <img src="{{ gambar_desa($desa['logo']) }}" class="w-full h-full object-contain" alt="Logo Desa">
                                @else
                                    <img src="{{ base_url('assets/files/logo/xosid_logo.png') }}" class="w-full h-full object-contain" alt="Logo XOSID">
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-black text-slate-900 leading-none">Pemerintah {{ ucwords(setting('sebutan_desa')) }}</span>
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-[#2554C7]"></i>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 font-semibold mt-1">
                                    <span>{{ date('d M Y', strtotime($post_obj->tgl_upload ?? date('Y-m-d'))) }}</span>
                                    <span>•</span>
                                    <span class="text-slate-600 font-bold">{{ $read_time }} mnt baca</span>
                                </div>
                            </div>
                        </div>

                        <span class="px-3 py-1 bg-blue-50 border border-blue-200 text-[#2554C7] text-[10px] font-black rounded-full uppercase tracking-wider">
                            {{ $post_obj->kategori_nama ?? 'Kabar Desa' }}
                        </span>
                    </div>

                    <!-- Media Container (Instagram Style) -->
                    <div class="relative w-full h-56 bg-slate-100 overflow-hidden">
                        <a href="{{ $url }}">
                            <img src="{{ $image }}" alt="{{ $post_obj->judul }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>
                        </a>
                    </div>

                    <!-- Content & Excerpt (Kumparan Style - High Contrast Text) -->
                    <div class="p-5 space-y-2 bg-white">
                        <h3 class="text-lg font-black text-slate-900 leading-snug tracking-tight line-clamp-2">
                            <a href="{{ $url }}" class="hover:text-[#153E7E] transition-colors">{{ $post_obj->judul }}</a>
                        </h3>
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed font-medium">
                            {!! html_entity_decode($abstract) !!}
                        </p>
                    </div>

                    <!-- Action Bar (Threads/IG Style - High Contrast) -->
                    <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/90 flex items-center justify-between text-xs text-slate-600">
                        <div class="flex items-center gap-6">
                            <!-- Like Counter -->
                            <button onclick="xosidLikePost(this)" type="button" class="flex items-center gap-2 text-slate-700 font-extrabold hover:text-rose-600 transition-colors">
                                <i data-lucide="heart" class="w-4 h-4 text-slate-500"></i>
                                <span class="text-xs font-extrabold">{{ ($post_obj->hit ?? 10) + 12 }}</span>
                            </button>

                            <!-- Comment Count -->
                            <a href="{{ $url }}" class="flex items-center gap-2 text-slate-700 font-extrabold hover:text-[#153E7E] transition-colors">
                                <i data-lucide="message-circle" class="w-4 h-4 text-slate-500"></i>
                                <span class="text-xs font-extrabold">Tanggapi</span>
                            </a>

                            <!-- Share Button -->
                            <button onclick="xosidSharePost('{{ $url }}', '{{ addslashes($post_obj->judul) }}')" type="button" class="flex items-center gap-2 text-slate-700 font-extrabold hover:text-[#153E7E] transition-colors">
                                <i data-lucide="share-2" class="w-4 h-4 text-slate-500"></i>
                                <span class="text-xs font-extrabold">Bagikan</span>
                            </button>
                        </div>

                        <!-- Read More Button (Spacious Soft Blue Pill - No Arrow Icon, Smooth Hover) -->
                        <style>
                            .xosid-btn-pill-baca {
                                background-color: #eff6ff !important;
                                color: #2563eb !important;
                                border: 1.5px solid #93c5fd !important;
                                border-radius: 9999px !important;
                                padding: 8px 24px !important;
                                font-family: 'Outfit', sans-serif !important;
                                font-size: 13px !important;
                                font-weight: 800 !important;
                                letter-spacing: 0.3px !important;
                                text-decoration: none !important;
                                display: inline-flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                box-shadow: 0 2px 6px rgba(37, 99, 235, 0.08) !important;
                                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            }
                            .xosid-btn-pill-baca:hover {
                                background-color: #dbeafe !important;
                                color: #1d4ed8 !important;
                                border-color: #60a5fa !important;
                                transform: translateY(-1px) scale(1.03) !important;
                                box-shadow: 0 4px 14px rgba(37, 99, 235, 0.18) !important;
                            }
                        </style>
                        <a href="{{ $url }}" class="xosid-btn-pill-baca">
                            <span>Baca</span>
                        </a>
                    </div>
                </article>
            @endforeach
        @endif
    </div>
</div>

<script>
    function xosidLikePost(btn) {
        const icon = btn.querySelector('svg, i');
        const countSpan = btn.querySelector('span');
        if (icon) {
            icon.classList.toggle('text-rose-600');
            icon.classList.toggle('fill-rose-600');
            icon.classList.toggle('text-slate-500');
        }
        if (countSpan) {
            let cnt = parseInt(countSpan.innerText) || 0;
            countSpan.innerText = cnt + 1;
        }
    }

    function xosidSharePost(url, title) {
        if (navigator.share) {
            navigator.share({ title: title, url: url }).catch(() => {});
        } else {
            const waUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(title + ' ' + url);
            window.open(waUrl, '_blank');
        }
    }
</script>
