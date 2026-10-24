@php
    $url = $post->url_slug;
    $abstract = potong_teks(strip_tags($post['isi']), 200);
    $kategori = $post['category']['kategori'] ?? 'Berita';
    
    // Foto default XOSID Neural
    $default_image = base_url('assets/images/404-image-not-found.jpg');
    
    $image_path = LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar'];
    $image = ($post['gambar'] && is_file($image_path)) ? AmbilFotoArtikel($post['gambar'], 'sedang') : $default_image;
@endphp

<article class="xosid-3d-card group">
    <!-- Card Cover Image with Neon tag overlay -->
    <div class="xosid-card-img-wrapper">
        <span class="xosid-category-tag">{{ $kategori }}</span>
        <img src="{{ $image }}" alt="{{ $post['judul'] }}" loading="lazy" class="xosid-card-img group-hover:scale-110 duration-500" onerror="this.onerror=null; this.src='{{ $default_image }}';">
        <div class="xosid-card-img-overlay"></div>
    </div>
    
    <!-- Card Body Content -->
    <div class="xosid-card-body">
        <div class="space-y-2">
            <a href="{{ $url }}" class="xosid-card-title block">
                {{ potong_teks($post['judul'], 65) }}{{ strlen($post['judul']) > 65 ? '...' : '' }}
            </a>
            <p class="text-sm text-slate-400 line-clamp-3 leading-relaxed">
                {!! potong_teks(html_entity_decode($abstract), 110) !!}{{ strlen($abstract) > 110 ? '...' : '' }}
            </p>
        </div>
        
        <!-- Metadata footer with glowing icons -->
        <div class="xosid-card-meta">
            <div class="xosid-meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ tgl_indo($post['tgl_upload']) }}</span>
            </div>
            <div class="xosid-meta-item">
                <i class="fas fa-user"></i>
                <span>{{ potong_teks($post['owner'], 12) }}</span>
            </div>
        </div>
    </div>
</article>
