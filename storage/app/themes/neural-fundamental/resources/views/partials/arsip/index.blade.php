@extends('theme::layouts.right-sidebar')

@section('content')
    <div class="premium-news-portal animate__animated animate__fadeIn">
        <!-- Breadcrumbs -->
        <nav role="navigation" aria-label="navigation" class="text-xs mb-4 text-slate-400">
            <ol class="flex items-center space-x-2 flex-wrap">
                <li><a href="{{ ci_route('') }}" class="hover:text-cyan-400 transition-colors">Beranda</a></li>
                <li class="before:content-['/'] before:pr-2 before:text-slate-600">
                    <span class="text-slate-500">Pusat Kabar Desa</span>
                </li>
            </ol>
        </nav>

        <!-- TOP ADVERTISEMENT SLOT (Google Adsense / Local Sponsor Billboard) -->
        <div class="premium-ad-slot-horizontal mb-8 group">
            <div class="premium-ad-inner">
                <span class="premium-ad-tag">IKLAN SPONSOR DESA</span>
                <div class="premium-ad-placeholder">
                    <i class="fas fa-bullhorn text-xl mb-1 text-cyan-400/40 group-hover:scale-110 transition duration-300"></i>
                    <p class="premium-ad-title">Pasang Banner Bisnis Anda Di Sini</p>
                    <p class="premium-ad-desc font-title">Promosi premium dilihat oleh seluruh pengunjung portal berita desa. Hubungi Admin Desa.</p>
                    <a href="https://wa.me/6281234567890?text=Saya%20tertarik%20memasang%20iklan%20di%20Website%20Desa%20Bumi%20Daya" target="_blank" class="premium-ad-cta select-none">
                        <i class="fab fa-whatsapp mr-1"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Header -->
        <header class="mb-6">
            <span class="premium-news-badge mb-3">
                <i class="fas fa-newspaper mr-1.5 text-[10px]"></i> Kabar Desa Terintegrasi
            </span>
            <h1 class="premium-news-title font-title">
                Pusat Kabar & Informasi Desa Bumi Daya
            </h1>
            <p class="text-sm text-slate-400 max-w-2xl font-title leading-relaxed">
                Temukan seluruh kumpulan berita, pengumuman resmi, kegiatan pembangunan, dan artikel menarik lainnya dari seluruh jurnalis resmi Desa Bumi Daya.
            </p>
        </header>

        <!-- Datatable Card Grid Container -->
        <div class="premium-news-archive-wrapper mt-8">
            <div class="box-body">
                <div class="table-responsive content">
                    <table id="arsip-artikel" class="w-full">
                        <thead style="display: none !important;">
                            <tr>
                                <th>Artikel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Server-side dynamic visual cards rendered here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- BOTTOM ADVERTISEMENT SLOT -->
        <div class="premium-ad-slot-horizontal mt-10 group">
            <div class="premium-ad-inner">
                <span class="premium-ad-tag">IKLAN SPONSOR DESA</span>
                <div class="premium-ad-placeholder">
                    <i class="fas fa-store text-xl mb-1 text-purple-400/40 group-hover:scale-110 transition duration-300"></i>
                    <p class="premium-ad-title">Sponsori Kumpulan Berita Kami</p>
                    <p class="premium-ad-desc font-title">Dukung media informasi independen desa dengan beriklan. Sangat murah dan berdampak luas.</p>
                    <a href="https://wa.me/6281234567890?text=Saya%20tertarik%20memasang%20iklan%20di%20Website%20Desa%20Bumi%20Daya" target="_blank" class="premium-ad-cta select-none">
                        <i class="fab fa-whatsapp mr-1"></i> Mulai Beriklan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            var arsip = $('#arsip-artikel').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ordering: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-6"f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-8"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari berita dan informasi...",
                    processing: "<div class='text-cyan-400 font-title text-sm'><i class='fas fa-sync fa-spin mr-2'></i> Sinkronisasi Kabar Desa...</div>",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ berita",
                    paginate: {
                        next: "<i class='fas fa-chevron-right'></i>",
                        previous: "<i class='fas fa-chevron-left'></i>"
                    }
                },
                ajax: {
                    url: `{{ ci_route('internal_api.arsip') }}`,
                    method: 'POST',
                    data: function(row) {
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + "tgl_upload",
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total
                        json.recordsFiltered = json.meta.pagination.total
                        return json.data
                    },
                },
                columns: [
                    {
                        data: function(data) {
                            var default_image = `{{ base_url('assets/images/404-image-not-found.jpg') }}`;
                            var image = default_image;
                            if (data.attributes.gambar) {
                                image = `{{ AmbilFotoArtikel('', 'sedang') }}`.replace('sedang_', 'sedang_' + data.attributes.gambar);
                            }
                            
                            var category = (data.attributes.category && data.attributes.category.kategori) ? data.attributes.category.kategori : 'Kabar Desa';
                            
                            // Safe HTML abstract extraction
                            var abstract = '';
                            if (data.attributes.isi) {
                                var doc = new DOMParser().parseFromString(data.attributes.isi, 'text/html');
                                var text = doc.body.textContent || "";
                                abstract = text.substring(0, 140);
                                if (text.length > 140) abstract += '...';
                            }
                            
                            return `
                                <div class="premium-news-card group animate__animated animate__fadeIn">
                                    <div class="premium-news-card-img-wrapper">
                                        <span class="premium-news-card-tag font-title">${category}</span>
                                        <img src="${image}" alt="${data.attributes.judul}" class="premium-news-card-img" onerror="this.onerror=null; this.src='${default_image}';">
                                        <div class="premium-news-card-overlay"></div>
                                    </div>
                                    <div class="premium-news-card-body">
                                        <div>
                                            <a href="${data.attributes.url_slug}" class="premium-news-card-title block font-title">
                                                ${data.attributes.judul}
                                            </a>
                                            <p class="premium-news-card-desc">
                                                ${abstract}
                                            </p>
                                        </div>
                                        <div class="premium-news-card-meta">
                                            <div class="premium-news-card-meta-item">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                <span>${data.attributes.tgl_upload_local}</span>
                                            </div>
                                            <div class="premium-news-card-meta-item">
                                                <i class="far fa-eye mr-1"></i>
                                                <span>Dibaca ${data.attributes.hit} kali</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        },
                        name: "judul"
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });
            
            // Adjust search bar styles dynamically
            $('.dataTables_filter input').addClass('premium-dt-search-input');
        });
    </script>
@endpush
