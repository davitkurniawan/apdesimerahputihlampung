@php
    $current_uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

<!-- FLOATING BOTTOM APP NAVIGATION DOCK (Gen Z Clean Light Surface with Auto-Hide on Idle) -->
<style>
    #xosid-mobile-dock {
        transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        will-change: transform, opacity;
    }
    #xosid-mobile-dock.dock-hidden {
        opacity: 0 !important;
        transform: translateY(110px) !important;
        pointer-events: none !important;
    }
</style>

<nav id="xosid-mobile-dock" class="md:hidden fixed bottom-4 left-4 right-4 z-[99999] bg-white/95 backdrop-blur-xl border border-slate-200/90 rounded-3xl py-2.5 px-3 shadow-2xl shadow-slate-900/15 flex items-center justify-around">
    <!-- Home / Beranda -->
    <a href="{{ site_url('/') }}" class="flex flex-col items-center gap-1.5 p-1.5 rounded-xl text-xs transition-all {{ ($current_uri === '/' || $current_uri === '/index.php' || $current_uri === '/index.php/' || strpos($current_uri, 'first') !== false || strpos($current_uri, 'portal') !== false) ? 'text-[#153E7E] font-black' : 'text-slate-500 hover:text-slate-900' }}">
        <i data-lucide="home" class="w-5 h-5"></i>
        <span class="text-[10px] font-bold tracking-tight leading-none">Beranda</span>
    </a>

    <!-- Kabar Desa -->
    <a href="{{ site_url('kabar-desa') }}" class="flex flex-col items-center gap-1.5 p-1.5 rounded-xl text-xs transition-all {{ (strpos($current_uri, 'kabar-desa') !== false || strpos($current_uri, 'kabardesa') !== false || strpos($current_uri, 'artikel') !== false) ? 'text-[#153E7E] font-black' : 'text-slate-500 hover:text-slate-900' }}">
        <i data-lucide="newspaper" class="w-5 h-5"></i>
        <span class="text-[10px] font-bold tracking-tight leading-none">Kabar</span>
    </a>

    <!-- Center Surat Mandiri (Fits 100% Inside White Box) -->
    <a href="{{ site_url('surat-mandiri') }}" class="flex flex-col items-center gap-1 p-1.5 rounded-xl text-xs transition-all">
        <div class="w-9 h-9 rounded-full bg-[#153E7E] text-white shadow-md shadow-[#153E7E]/30 flex items-center justify-center active:scale-95 transition-all">
            <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
        </div>
        <span class="text-[10px] font-black text-[#153E7E] tracking-tight leading-none">Surat</span>
    </a>

    <!-- KataDesa AI -->
    <a href="{{ site_url('katadesa-ai') }}" class="flex flex-col items-center gap-1.5 p-1.5 rounded-xl text-xs transition-all {{ (strpos($current_uri, 'katadesa-ai') !== false) ? 'text-emerald-600 font-black' : 'text-slate-500 hover:text-slate-900' }}">
        <i data-lucide="bot" class="w-5 h-5"></i>
        <span class="text-[10px] font-bold tracking-tight leading-none">AI Desa</span>
    </a>

    <!-- Profil Desa -->
    <a href="{{ site_url('profil') }}" class="flex flex-col items-center gap-1.5 p-1.5 rounded-xl text-xs transition-all {{ (strpos($current_uri, 'profil') !== false) ? 'text-[#153E7E] font-black' : 'text-slate-500 hover:text-slate-900' }}">
        <i data-lucide="info" class="w-5 h-5"></i>
        <span class="text-[10px] font-bold tracking-tight leading-none">Profil</span>
    </a>
</nav>

<script>
    (function() {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }

        const dock = document.getElementById('xosid-mobile-dock');
        if (!dock) return;

        let hideTimer = null;

        function showDock() {
            dock.classList.remove('dock-hidden');
            resetTimer();
        }

        function hideDock() {
            dock.classList.add('dock-hidden');
        }

        function resetTimer() {
            if (hideTimer) clearTimeout(hideTimer);
            hideTimer = setTimeout(function() {
                hideDock();
            }, 3000); // Auto-hide after 3 seconds idle
        }

        // Show dock on touch, scroll, mouse movement, or click
        window.addEventListener('scroll', showDock, { passive: true });
        window.addEventListener('touchstart', showDock, { passive: true });
        window.addEventListener('touchmove', showDock, { passive: true });
        window.addEventListener('mousemove', showDock, { passive: true });
        window.addEventListener('click', showDock, { passive: true });

        // Initial 3s countdown on page load
        resetTimer();
    })();
</script>
