<div class="mx-4 mt-3 lg:hidden block relative z-40" style="overflow: hidden; max-width: 100%;">
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

        $menu_atas = $custom_menus;
        $total_items = 2;
        if ($menu_atas) {
            foreach ($menu_atas as $menu) {
                $total_items++;
                if (!empty($menu['childrens'])) {
                    foreach ($menu['childrens'] as $child) {
                        $total_items++;
                        if (!empty($child['childrens'])) {
                            foreach ($child['childrens'] as $gchild) {
                                $total_items++;
                                if (!empty($gchild['childrens'])) {
                                    $total_items += count($gchild['childrens']);
                                }
                            }
                        }
                    }
                }
            }
        }
        // Center-align when items are few, scroll-align (start) when items are many to prevent overflow clipping
        $justify_class = $total_items <= 2 ? 'justify-center' : 'justify-start';
    @endphp
    
    <!-- Outer Scroll Container: Handles native scroll and touch physics -->
    <div style="width: 100%; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;">
        <!-- Inner Flex Track: Grows to max-content to prevent wrapping, centers items if fewer than 3 -->
        <div style="display: flex; align-items: center; gap: 8px; padding-top: 6px; padding-bottom: 6px; padding-left: 2px; padding-right: 2px; width: max-content; min-width: 100%; justify-content: {{ $justify_class === 'justify-center' ? 'center' : 'flex-start' }};">
            
            @if ($menu_atas)
                @foreach ($menu_atas as $menu)
                    @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                    @php $menu_link = $has_dropdown ? '#!' : $menu['link_url'] @endphp

                    <!-- LEVEL 1: Cyan Capsule -->
                    <a href="{{ $menu_link }}" 
                       style="white-space: nowrap !important; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; gap: 6px;"
                       class="px-5 py-2 bg-cyan-950/20 hover:bg-cyan-500/10 border border-cyan-500/30 hover:border-cyan-400 text-[11px] font-bold tracking-wider text-cyan-400 hover:text-white rounded-full transition-all duration-300 shadow-sm hover:shadow-[0_0_12px_rgba(6,182,212,0.2)] uppercase leading-none whitespace-nowrap">
                        <span class="relative flex h-1.5 w-1.5 shrink-0" style="flex-shrink: 0;">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-cyan-500"></span>
                        </span>
                        <span>{!! $menu['nama'] !!}</span>
                    </a>

                    @if ($has_dropdown)
                        @foreach ($menu['childrens'] as $childrens)
                            @php $has_dropdown2 = count($childrens['childrens'] ?? []) > 0 @endphp
                            @php $menu_link2 = $has_dropdown2 ? '#!' : $childrens['link_url'] @endphp

                            <!-- LEVEL 2: Indigo/Purple Capsule -->
                            <a href="{{ $menu_link2 }}" 
                               style="white-space: nowrap !important; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; gap: 6px;"
                               class="px-5 py-2 bg-indigo-950/20 hover:bg-indigo-500/10 border border-indigo-500/30 hover:border-indigo-400 text-[11px] font-bold tracking-wider text-indigo-400 hover:text-white rounded-full transition-all duration-300 shadow-sm hover:shadow-[0_0_12px_rgba(99,102,241,0.2)] uppercase leading-none whitespace-nowrap">
                                <span class="relative flex h-1.5 w-1.5 shrink-0" style="flex-shrink: 0;">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-500"></span>
                                </span>
                                <span>{!! $childrens['nama'] !!}</span>
                            </a>

                            @if ($has_dropdown2)
                                @foreach ($childrens['childrens'] as $children)
                                    @php $has_dropdown3 = count($children['childrens'] ?? []) > 0 @endphp
                                    @php $menu_link3 = $has_dropdown3 ? '#!' : $children['link_url'] @endphp

                                    <!-- LEVEL 3: Purple/Pink Capsule -->
                                    <a href="{{ $menu_link3 }}" 
                                       style="white-space: nowrap !important; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; gap: 6px;"
                                       class="px-5 py-2 bg-purple-950/20 hover:bg-purple-500/10 border border-purple-500/30 hover:border-purple-400 text-[11px] font-bold tracking-wider text-purple-400 hover:text-white rounded-full transition-all duration-300 shadow-sm hover:shadow-[0_0_12px_rgba(168,85,247,0.2)] uppercase leading-none whitespace-nowrap">
                                        <span class="relative flex h-1.5 w-1.5 shrink-0" style="flex-shrink: 0;">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-purple-500"></span>
                                        </span>
                                        <span>{!! $children['nama'] !!}</span>
                                    </a>

                                    @if ($has_dropdown3)
                                        @foreach ($children['childrens'] as $ggchild)
                                            <!-- LEVEL 4: Pink Capsule -->
                                            <a href="{{ $ggchild['link_url'] }}" 
                                               style="white-space: nowrap !important; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; gap: 6px;"
                                               class="px-5 py-2 bg-pink-950/20 hover:bg-pink-500/10 border border-pink-500/30 hover:border-pink-400 text-[11px] font-bold tracking-wider text-pink-400 hover:text-white rounded-full transition-all duration-300 shadow-sm hover:shadow-[0_0_12px_rgba(236,72,153,0.2)] uppercase leading-none whitespace-nowrap">
                                                <span class="relative flex h-1.5 w-1.5 shrink-0" style="flex-shrink: 0;">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-pink-500"></span>
                                                </span>
                                                <span>{!! $ggchild['nama'] !!}</span>
                                            </a>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

        </div>
    </div>
</div>
