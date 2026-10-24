@php
    $custom_menus = [];
    if (class_exists('\App\Models\Menu')) {
        $db_menus = \App\Models\Menu::with('children.children.children')
            ->where('parrent', 0)
            ->where('enabled', 1)
            ->orderBy('urut')
            ->get();
            
        $map_menu = function($item) use (&$map_menu) {
            $link_url = $item->link;
            if (!preg_match('/^(http|https):/i', $link_url)) {
                $link_url = site_url($link_url);
            }
            $childrens = [];
            if ($item->children) {
                foreach ($item->children as $child) {
                    if ($child->enabled) {
                        $childrens[] = $map_menu($child);
                    }
                }
            }
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'parrent' => $item->parrent,
                'link_tipe' => $item->link_tipe,
                'link' => $item->link,
                'link_url' => $link_url,
                'childrens' => $childrens
            ];
        };
        
        foreach ($db_menus as $item) {
            $custom_menus[] = $map_menu($item);
        }
    } else {
        $custom_menus = menu_tema() ?: [];
    }

    @file_put_contents(
        '/var/www/html/storage/logs/xosid_login_debug.log', 
        "[" . date('Y-m-d H:i:s') . "] debug custom_menus count: " . count($custom_menus) . "\n" .
        "content: " . print_r($custom_menus, true) . "\n",
        FILE_APPEND
    );
@endphp
<style>
    .navbar-menu {
        border-radius: 14px;
        margin-top: 15px;
        margin-bottom: 24px !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: 1px solid rgba(255,255,255,0.05);
        background: rgba(15, 23, 42, 0.8) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
    }

    /* KETIKA DI-SCROLL - JADI FLOATING GLASSMORPHISM STICKY */
    .navbar-floating-stick {
        position: fixed !important;
        top: 20px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 90% !important;
        max-width: 1200px !important;
        z-index: 999999 !important;
        background: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(25px) !important;
        -webkit-backdrop-filter: blur(25px) !important;
        border: 1px solid rgba(6, 182, 212, 0.4) !important;
        border-radius: 50px !important;
        box-shadow: 0 30px 60px rgba(0,0,0,0.6) !important;
        animation: slideDownNav 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes slideDownNav {
        from { transform: translate(-50%, -20px); opacity: 0; }
        to { transform: translate(-50%, 0); opacity: 1; }
    }

    .navbar-menu ul {
        list-style: none;
        margin: 0;
        padding: 12px 16px; /* Add padding here since we shrink the links */
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .navbar-menu li {
        position: relative;
    }

    .navbar-menu ul > li > a {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        color: #cbd5e1 !important;
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-radius: 9999px !important; /* Full pill-shape capsule */
        padding: 8px 18px !important;
        display: inline-flex !important;
        align-items: center !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        box-shadow: inset 0 0 6px rgba(255, 255, 255, 0.01) !important;
    }

    .navbar-menu ul > li > a:hover {
        color: #22d3ee !important; /* Cyan active color */
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.25), inset 0 0 8px rgba(6, 182, 212, 0.15) !important;
        text-shadow: 0 0 6px rgba(6, 182, 212, 0.5) !important;
    }

    .navbar-floating-stick ul {
        padding: 8px 16px !important;
    }
    
    .navbar-floating-stick ul > li > a {
        padding: 6px 16px !important; /* Lebih ramping saat melayang */
    }

    /* Default sembunyi */
    .navbar-menu ul ul {
        display: none;
    }

    /* Hover tampilkan anak */
    .navbar-menu li:hover > ul {
        display: block;
    }

    /* Dropdown level 1 */
    .navbar-menu > ul > li > ul {
        top: 100%;
        left: 0;
        margin-top: 5px;
    }

    /* Dropdown level 2 & seterusnya */
    .navbar-menu ul ul {
        top: 0;
        left: 100%;
        min-width: 190px;
    }

    /* Pastikan dropdown selalu di atas */
    .navbar-menu ul ul {
        z-index: 9999;
        background: rgba(15, 23, 42, 0.95) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(6, 182, 212, 0.25) !important;
        border-radius: 16px !important;
        overflow: hidden;
        padding: 8px 0 !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.5) !important;
        display: none;
        flex-direction: column;
    }

    .navbar-menu ul ul li {
        width: 100%;
    }

    .navbar-menu ul ul a {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 600 !important;
        font-size: 12px !important;
        text-transform: none !important;
        letter-spacing: 0.5px !important;
        padding: 10px 20px !important;
        color: #cbd5e1 !important;
        transition: all 0.2s !important;
        display: block !important;
        border-radius: 0 !important;
    }

    .navbar-menu ul ul a:hover {
        background: rgba(6, 182, 212, 0.15) !important;
        color: #06b6d4 !important;
        padding-left: 25px !important; /* Efek geser estetik saat hover */
    }
</style>

<nav class="navbar-menu text-white hidden lg:block" role="navigation">
    <ul>
        <!-- Home -->
        <li class="inline-block">
            <a href="{{ site_url('/') }}" class="inline-block" style="background: transparent !important; border: none !important; box-shadow: none !important; padding: 8px 12px !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important;">
                <i class="fa fa-home" style="color: #22d3ee; text-shadow: 0 0 10px rgba(6, 182, 212, 0.8);"></i>
            </a>
        </li>

        <!-- Menu Dinamis -->
        @if ($custom_menus)
            @foreach ($custom_menus as $menu)
                @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                <li class="inline-block relative group">
                    <a href="{{ $has_dropdown ? '#!' : $menu['link_url'] }}"
                       class="p-3 inline-block hover:bg-primary-200">
                        {!! $menu['nama'] !!}
                        @if ($has_dropdown)
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        @endif
                    </a>

                    @if ($has_dropdown)
                        <!-- LEVEL 1 dropdown -->
                        <ul class="absolute bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                            @foreach ($menu['childrens'] as $child)
                                @php $child_has_dropdown = count($child['childrens'] ?? []) > 0 @endphp
                                <li class="relative group">
                                    <a href="{{ $child_has_dropdown ? '#!' : $child['link_url'] }}"
                                       class="flex items-center justify-between py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                        <span class="flex-1">{!! $child['nama'] !!}</span>
                                        @if ($child_has_dropdown)
                                            <i class="fas fa-chevron-right fa-xs ml-2 mr-5 shrink-0"></i>
                                        @endif
                                    </a>

                                    @if ($child_has_dropdown)
                                        <!-- LEVEL 2 dropdown -->
                                        <ul class="absolute top-0 left-full bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                                            @foreach ($child['childrens'] as $grandchild)
                                                @php $grandchild_has_dropdown = count($grandchild['childrens'] ?? []) > 0 @endphp
                                                <li class="relative group">
                                                    <a href="{{ $grandchild_has_dropdown ? '#!' : $grandchild['link_url'] }}"
                                                       class="flex items-center justify-between py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                                        <span class="flex-1">{!! $grandchild['nama'] !!}</span>
                                                        @if ($grandchild_has_dropdown)
                                                            <i class="fas fa-chevron-right fa-xs ml-2 mr-5 shrink-0"></i>
                                                        @endif
                                                    </a>

                                                    @if ($grandchild_has_dropdown)
                                                        <!-- LEVEL 3 dropdown -->
                                                        <ul class="absolute top-0 left-full bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                                                            @foreach ($grandchild['childrens'] as $greatgrandchild)
                                                                <li>
                                                                    <a href="{{ $greatgrandchild['link_url'] }}"
                                                                       class="block py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                                                        {!! $greatgrandchild['nama'] !!}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</nav>

<script>
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function($) {
            var $nav = $('.navbar-menu');
            if ($nav.length) {
                var stickyTop = $nav.offset().top;
                $(window).on('scroll', function() {
                    var scroll = $(window).scrollTop();
                    if (scroll > stickyTop + 120) {
                        if (!$nav.hasClass('navbar-floating-stick')) {
                            $nav.addClass('navbar-floating-stick');
                        }
                    } else {
                        if ($nav.hasClass('navbar-floating-stick')) {
                            $nav.removeClass('navbar-floating-stick');
                        }
                    }
                });
            }
        });
    }
</script>
