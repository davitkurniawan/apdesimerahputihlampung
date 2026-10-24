<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
$_amp_user_name = $_SESSION["amp_user_name"] ?? null;
$_amp_active    = $amp_active_menu ?? "";
?>
<header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm no-print">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

        <!-- Logo & Brand -->
        <a href="/" class="flex items-center gap-2.5 sm:gap-3 shrink-0">
            <img src="/public/images/logo_amp.png" alt="Logo APDesi Merah Putih" class="w-9 h-12 sm:w-11 sm:h-14 object-contain filter drop-shadow">
            <div>
                <span class="text-[10px] sm:text-xs font-bold text-red-700 uppercase tracking-widest block leading-none">Provinsi Lampung</span>
                <h1 class="text-base sm:text-lg font-extrabold text-gray-900 leading-tight">APDESI <span class="text-red-600">MERAH PUTIH</span></h1>
            </div>
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-700">
            <a href="/"        class="<?php echo $_amp_active==="beranda" ? "text-red-700 border-b-2 border-red-700 pb-1" : "hover:text-red-700 transition-colors"; ?>">Beranda</a>
            <a href="/profil"  class="<?php echo $_amp_active==="profil"  ? "text-red-700 border-b-2 border-red-700 pb-1" : "hover:text-red-700 transition-colors"; ?>">Profil Organisasi</a>
            <a href="/peta-potensi" class="<?php echo $_amp_active==="peta" ? "text-red-700 border-b-2 border-red-700 pb-1" : "hover:text-red-700 transition-colors"; ?>">Peta Potensi</a>
            <a href="/berita"  class="<?php echo $_amp_active==="berita"  ? "text-red-700 border-b-2 border-red-700 pb-1" : "hover:text-red-700 transition-colors"; ?>">Berita</a>
            <a href="/agenda"  class="<?php echo $_amp_active==="agenda"  ? "text-red-700 border-b-2 border-red-700 pb-1" : "hover:text-red-700 transition-colors"; ?>">Agenda</a>
            <a href="/galeri"  class="<?php echo $_amp_active==="galeri"  ? "text-red-700 border-b-2 border-red-700 pb-1" : "hover:text-red-700 transition-colors"; ?>">Galeri</a>
            <a href="/portal-layanan" class="<?php echo $_amp_active==="layanan" ? "text-red-700 border-b-2 border-red-700 pb-1" : "hover:text-red-700 transition-colors"; ?>">Portal Layanan</a>
        </nav>

        <!-- CTA Kanan -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <?php if ($_amp_user_name): ?>
                <?php if ($_amp_active !== "dashboard"): ?>
                    <a href="/dashboard" class="hidden sm:flex px-4 py-2.5 bg-red-700 hover:bg-red-800 text-white font-semibold rounded-xl text-sm transition shadow-md items-center gap-2">
                        <span>Dashboard Saya</span>
                    </a>
                <?php endif; ?>
                <a href="/api/amp/logout" class="px-3 sm:px-4 py-2 sm:py-2.5 border border-red-200 text-red-700 hover:bg-red-50 font-semibold rounded-xl text-xs sm:text-sm transition flex items-center gap-1.5">
                    <span>Keluar</span> 🚪
                </a>
            <?php else: ?>
                <a href="/login" class="hidden sm:inline-block px-4 py-2 text-red-700 font-semibold text-sm hover:bg-red-50 rounded-xl transition">Masuk</a>
                <a href="/registrasi" class="hidden sm:inline-block px-5 py-2.5 bg-gradient-to-r from-red-700 to-red-600 hover:from-red-800 hover:to-red-700 text-white font-semibold rounded-xl text-sm transition shadow-md shadow-red-200">Daftar Anggota</a>
            <?php endif; ?>

            <!-- Hamburger Mobile -->
            <button onclick="document.getElementById('amp-mobile-nav').classList.toggle('hidden')"
                    class="md:hidden p-2 rounded-xl hover:bg-gray-100 text-gray-700 transition"
                    aria-label="Buka Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Nav -->
    <div id="amp-mobile-nav" class="hidden md:hidden border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col gap-1 text-sm font-semibold">
            <a href="/"        class="px-3 py-2.5 rounded-xl <?php echo $_amp_active==="beranda" ? "bg-red-50 text-red-700" : "text-gray-700 hover:bg-gray-50"; ?> transition">Beranda</a>
            <a href="/profil"  class="px-3 py-2.5 rounded-xl <?php echo $_amp_active==="profil"  ? "bg-red-50 text-red-700" : "text-gray-700 hover:bg-gray-50"; ?> transition">Profil Organisasi</a>
            <a href="/peta-potensi" class="px-3 py-2.5 rounded-xl <?php echo $_amp_active==="peta" ? "bg-red-50 text-red-700" : "text-gray-700 hover:bg-gray-50"; ?> transition">Peta Potensi</a>
            <a href="/berita"  class="px-3 py-2.5 rounded-xl <?php echo $_amp_active==="berita"  ? "bg-red-50 text-red-700" : "text-gray-700 hover:bg-gray-50"; ?> transition">Berita</a>
            <a href="/agenda"  class="px-3 py-2.5 rounded-xl <?php echo $_amp_active==="agenda"  ? "bg-red-50 text-red-700" : "text-gray-700 hover:bg-gray-50"; ?> transition">Agenda</a>
            <a href="/galeri"  class="px-3 py-2.5 rounded-xl <?php echo $_amp_active==="galeri"  ? "bg-red-50 text-red-700" : "text-gray-700 hover:bg-gray-50"; ?> transition">Galeri</a>
            <a href="/portal-layanan" class="px-3 py-2.5 rounded-xl <?php echo $_amp_active==="layanan" ? "bg-red-50 text-red-700" : "text-gray-700 hover:bg-gray-50"; ?> transition">Portal Layanan</a>
                        <div class="border-t border-gray-100 mt-2 pt-3 flex gap-2">
                <?php if ($_amp_user_name): ?>
                    <?php if ($_amp_active !== "dashboard"): ?>
                        <a href="/dashboard" class="flex-1 py-2.5 bg-red-700 hover:bg-red-800 text-white font-semibold rounded-xl text-center text-sm transition">Dashboard Saya &rarr;</a>
                    <?php endif; ?>
                    <a href="/api/amp/logout" class="flex-1 py-2.5 border border-red-600 text-red-700 font-semibold rounded-xl text-center text-sm transition hover:bg-red-50">Keluar</a>
                <?php else: ?>
                    <a href="/login"      class="flex-1 py-2.5 border border-red-600 text-red-700 font-semibold rounded-xl text-center text-sm transition hover:bg-red-50">Masuk</a>
                    <a href="/registrasi" class="flex-1 py-2.5 bg-red-700 hover:bg-red-800 text-white font-semibold rounded-xl text-center text-sm transition">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
