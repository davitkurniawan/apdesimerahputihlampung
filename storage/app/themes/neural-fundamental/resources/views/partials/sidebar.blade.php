<aside class="space-y-6 sidebar animate__animated animate__fadeInRight">
    <form action="{{ site_url('/') }}" role="form" class="relative group mt-2 mb-6" style="margin-bottom: 1.5rem !important;">
        <!-- AI Glowing Backdrop -->
        <div class="absolute -inset-[1px] bg-gradient-to-r from-cyan-500 via-indigo-500 to-purple-500 blur opacity-40 group-hover:opacity-80 transition duration-500" style="border-radius: 9999px;"></div>
        
        <!-- Search Input Container -->
        <div class="relative flex items-center bg-slate-950/80 backdrop-blur-md border border-white/10 pl-5 pr-3 py-1.5" style="border-radius: 9999px;">
            <!-- AI Icon -->
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-tr from-cyan-500/20 to-purple-500/20 border border-white/5 mr-2 shrink-0">
                <i class="fas fa-robot text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400"></i>
            </div>
            
            <!-- Input Field -->
            <input type="text" name="cari" class="flex-1 text-white placeholder-slate-400 text-[13px] md:text-sm focus:outline-none py-2 w-full border-none focus:ring-0" style="background-color: transparent !important; box-shadow: none !important;" placeholder="Tanya KATADESA AI..." maxlength="100" autocomplete="off">
            
            <!-- Send Button -->
            <button type="submit" class="flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-400 hover:to-purple-500 text-white shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all duration-300 ml-1 shrink-0 border-none">
                <i class="fas fa-arrow-right text-sm"></i>
            </button>
        </div>
    </form>

    <!-- PREMIUM AD WIDGET SLOT (Local Sponsor & Partner Card) -->
    <div class="premium-ad-sidebar-widget group mb-6">
        <div class="premium-ad-sidebar-glow"></div>
        <div class="premium-ad-sidebar-content">
            <span class="premium-ad-sidebar-tag">IKLAN SPONSOR</span>
            <div class="text-center py-4 px-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-500 to-purple-500 flex items-center justify-center mx-auto mb-3 shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                    <i class="fas fa-ad text-white text-lg"></i>
                </div>
                <h4 class="premium-ad-sidebar-title">Sponsori Website Resmi Desa</h4>
                <p class="premium-ad-sidebar-desc font-title">Promosikan toko, warung, produk UMKM, atau jasa Anda di sini untuk jangkauan maksimal seluruh warga desa.</p>
                <a href="https://wa.me/6281234567890?text=Saya%20tertarik%20memasang%20iklan%20di%20Website%20Desa%20Bumi%20Daya" target="_blank" class="premium-ad-sidebar-btn select-none">
                    <i class="fab fa-whatsapp mr-1.5 text-base"></i> Hubungi Pemasaran
                </a>
            </div>
        </div>
    </div>

    <!-- Tampilkan Widget -->
    @if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @php
                $judul_widget = [
                    'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])),
                ];
            @endphp
            <div class="sidebar-widget-neural">
                @includeIf("theme::widgets.{$widget['isi']}", $judul_widget)
            </div>
        @endforeach
    @endif
</aside>
