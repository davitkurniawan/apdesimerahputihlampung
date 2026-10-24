@php
    $alt_slug = PREMIUM ? 'artikel' : 'first';
@endphp

<section x-data="{ catMenu: false }">
    <button type="button" class="lg:hidden inline-block py-4 px-6 z-10 relative" @click="catMenu = !catMenu">
        <i class="fa fa-list fa-lg"></i>
    </button>

    <div x-show="catMenu" x-on:click="catMenu = false" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 z-30 backdrop-blur-sm"></div>

    <div class="lg:py-3 px-3 lg:block transform transition-transform duration-300 lg:visible z-40 mobile-menu-drawer"
        :class="{ 'bg-white text-gray-700 w-3/4 shadow fixed top-0 left-0 h-screen block inset-0 overflow-y-auto opacity-100 visible': catMenu, 'bg-white lg:bg-transparent fixed lg:relative -translate-x-full h-screen lg:h-auto lg:translate-x-0 opacity-0 lg:opacity-100': !catMenu }"
        x-transitionx-on:click.stop x-trap.noscroll.inert="catMenu"
    >

        <h5 class="text-h5 pt-5 pb-3 px-3 lg:hidden">Menu Kategori</h5>
        <div class="flex lg:flex-row flex-col justify-between items-center relative z-10">
            <ul class="flex flex-wrap items-center gap-1 w-full text-sm">
                @foreach ($menu_kiri as $menu)
                    <li class="inline-block">
                        <a href="{{ site_url("{$alt_slug}/kategori/{$menu['slug']}") }}" class="xosid-category-link block lg:inline-block">
                            {{ $menu['kategori'] }}
                        </a>
                    </li>
                    @if (count($menu['submenu'] ?? []) > 0)
                        @foreach ($menu['submenu'] as $submenu)
                            <li class="inline-block">
                                <a href="{{ site_url("{$alt_slug}/kategori/{$submenu['slug']}") }}" class="xosid-category-link block lg:inline-block">
                                    {{ $submenu['kategori'] }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                @endforeach
            </ul>

            <div class="flex flex-col lg:flex-row gap-3 mt-5 lg:mt-0 flex-wrap lg:justify-end w-full px-3 items-center">
                <!-- Sliding Toggle Switch for XO Neural -->
                <div id="fresh-toggle-btn" onclick="triggerFreshToggle()" class="btn-cyber-cyan text-sm w-full lg:w-auto text-center cursor-pointer select-none flex items-center justify-between gap-3 h-[38px] min-h-[38px]" style="border-radius: 9999px; padding: 5px 12px 5px 18px;">
                    <span class="text-[11px] font-extrabold tracking-wider select-none text-cyan-400">XO NEURAL</span>
                    <!-- Sliding Track -->
                    <div class="w-11 h-6 bg-slate-950/90 border border-cyan-500/20 rounded-full relative flex items-center p-0.5 pointer-events-none">
                        <div id="fresh-toggle-knob" class="w-[18px] h-[18px] rounded-full bg-cyan-400 shadow-[0_0_10px_#06b6d4,0_0_20px_#06b6d4] transition-all duration-300 ease-in-out translate-x-0"></div>
                    </div>
                </div>

                <script>
                    function triggerFreshToggle() {
                        const knob = document.getElementById('fresh-toggle-knob');
                        if (knob) {
                            // Slide to right (Fresh mode)
                            knob.style.transform = 'translateX(20px)';
                            knob.style.backgroundColor = '#a855f7'; // Purple-cyan transition glow
                            knob.style.boxShadow = '0 0 10px #a855f7, 0 0 20px #a855f7';
                            
                            // Redirect after animation finishes
                            setTimeout(() => {
                                window.location.href = "{{ site_url('portal2') }}";
                            }, 350);
                        }
                    }
                </script>
                @if (setting('layanan_mandiri') == 1)
                    <a href="{{ site_url('kiosk') }}" class="btn-cyber-cyan text-sm w-full lg:w-auto text-center">
                        <span class="xosid-btn-dot-cyan"></span>Layanan Warga
                    </a>
                @endif

                <!-- Dark/Light Theme Toggle Button -->
                <button id="xosid-theme-toggle" onclick="toggleXOSIDTheme()" class="btn-cyber-cyan text-sm w-full lg:w-auto text-center cursor-pointer select-none flex items-center justify-center gap-2 h-[38px] min-h-[38px] px-4" style="border-radius: 9999px;">
                    <span id="xosid-theme-icon">🌙</span>
                    <span id="xosid-theme-text" class="text-[11px] font-extrabold tracking-wider">MODE GELAP</span>
                </button>

                <a href="{{ site_url('xoSID-login') }}" class="btn-cyber-pink text-sm w-full lg:w-auto text-center">
                    <span class="xosid-btn-dot-pink"></span>Login SSO
                </a>

                <script type="text/javascript">
                    function updateThemeToggleUI(theme) {
                        const iconEl = document.getElementById('xosid-theme-icon');
                        const textEl = document.getElementById('xosid-theme-text');
                        const btnEl = document.getElementById('xosid-theme-toggle');
                        if (theme === 'light') {
                            if (iconEl) iconEl.innerText = '☀️';
                            if (textEl) textEl.innerText = 'MODE TERANG';
                            if (btnEl) {
                                btnEl.classList.remove('btn-cyber-cyan');
                                btnEl.classList.add('btn-cyber-pink');
                            }
                        } else {
                            if (iconEl) iconEl.innerText = '🌙';
                            if (textEl) textEl.innerText = 'MODE GELAP';
                            if (btnEl) {
                                btnEl.classList.remove('btn-cyber-pink');
                                btnEl.classList.add('btn-cyber-cyan');
                            }
                        }
                    }

                    function toggleXOSIDTheme() {
                        const isLight = document.documentElement.classList.contains('light-mode');
                        const nextTheme = isLight ? 'dark' : 'light';
                        
                        if (nextTheme === 'light') {
                            document.documentElement.classList.add('light-mode');
                        } else {
                            document.documentElement.classList.remove('light-mode');
                        }
                        
                        localStorage.setItem('xosid-theme', nextTheme);
                        updateThemeToggleUI(nextTheme);
                    }

                    // Initialize toggle UI state
                    (function() {
                        const currentTheme = localStorage.getItem('xosid-theme') || 'dark';
                        // Run check as soon as DOM is ready or inline
                        setTimeout(() => {
                            updateThemeToggleUI(currentTheme);
                        }, 50);
                    })();
                </script>
            </div>
        </div>
    </div>

</section>
