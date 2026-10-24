<div class="w-full px-6 lg:px-10 mx-auto">
    @includeWhen($transparansi, 'theme::partials.apbdesa', $transparansi)
</div>

@php
    $base_version = function_exists('AmbilVersi') ? AmbilVersi() : '2605.0.0';
    $clean_version = ltrim($base_version, 'v');
    $parts = preg_split('/[\.\-_]/', $clean_version);
    $numbers = [];
    foreach ($parts as $part) {
        if (preg_match('/\d+/', $part, $m)) {
            $numbers[] = $m[0];
        }
    }
    
    $yy = date('y');
    $mm = date('m');
    $release = '1';
    
    if (!empty($numbers)) {
        $first = $numbers[0];
        if (strlen($first) >= 4) {
            $yy = substr($first, 0, 2);
            $mm = substr($first, 2, 2);
            if (isset($numbers[1]) && $numbers[1] !== '0') {
                $release = $numbers[1];
            } elseif (isset($numbers[2]) && $numbers[2] !== '0') {
                $release = $numbers[2];
            }
        } else {
            $yy = str_pad($first, 2, '0', STR_PAD_LEFT);
            if (isset($numbers[1])) {
                $mm = str_pad($numbers[1], 2, '0', STR_PAD_LEFT);
            }
            if (isset($numbers[2]) && $numbers[2] !== '0') {
                $release = $numbers[2];
            }
        }
    }
    $xosid_version = str_pad($mm, 2, '0', STR_PAD_LEFT) . "." . str_pad($yy, 2, '0', STR_PAD_LEFT) . "." . $release . " - Enterprise";
@endphp

@php
    $social_media = [
        'facebook' => [
            'color' => 'bg-blue-600',
            'icon' => 'fa-facebook-f',
        ],
        'twitter' => [
            'color' => 'bg-blue-400',
            'icon' => 'fa-twitter',
        ],
        'instagram' => [
            'color' => 'bg-pink-500',
            'icon' => 'fa-instagram',
        ],
        'telegram' => [
            'color' => 'bg-blue-500',
            'icon' => 'fa-telegram',
        ],
        'whatsapp' => [
            'color' => 'bg-green-500',
            'icon' => 'fa-whatsapp',
        ],
        'youtube' => [
            'color' => 'bg-red-500',
            'icon' => 'fa-youtube',
        ],
    ];
@endphp

@foreach ($sosmed as $social)
    @if ($social['link'])
        @php
            $social_media[strtolower($social['nama'])]['link'] = $social['link'];
        @endphp
    @endif
@endforeach

@include('theme::commons.back_to_top')

<footer class="w-full text-white mt-5" style="background: rgba(15, 23, 42, 0.9) !important; border-top: 1px solid rgba(255,255,255,0.05) !important; backdrop-filter: blur(10px); padding: 15px 25px !important;">
    <div class="w-full flex flex-col lg:flex-row justify-between items-center text-center lg:text-left gap-4">
        
        <!-- Left Side: Text Content -->
        <div class="flex-1 w-full">
            <div class="flex flex-wrap justify-center lg:justify-start items-center gap-y-1 text-[11px] font-semibold text-slate-300 tracking-wide">
                <span>Hak cipta situs &copy; {{ date('Y') }} - {{ $nama_desa }}</span>
                <span class="text-slate-500 mx-2">|</span>
                
                <span>
                    <span class="text-slate-400 font-normal">Platform</span>&nbsp;
                    <a href="https://github.com/xo-sid" target="_blank" rel="noopener noreferrer" class="font-outfit tracking-[1.5px] font-bold text-white hover:text-cyan-400 hover:drop-shadow-[0_0_12px_rgba(6,182,212,0.6)] transition-all">XO<span class="text-cyan-500">SID</span> SUPRA<span class="text-cyan-500">DESA</span></a>
                </span>
                <span class="text-slate-500 mx-2">|</span>
                
                <span>
                    <span class="text-slate-400 font-normal">Dikembangkan Oleh Komunitas</span>&nbsp;
                    <a href="https://kitasedesa.com" target="_blank" rel="noopener noreferrer" class="font-extrabold tracking-[0.5px] text-cyan-500 hover:text-white hover:drop-shadow-[0_0_12px_rgba(255,255,255,0.6)] transition-all">KITASEDESA</a>
                </span>
                <span class="text-slate-500 mx-2">|</span>
                
                <span class="text-slate-400 font-normal">Desa Sambung menyambung Menjadi Indonesia</span>
            </div>
            
            @if (file_exists('mitra'))
                <div class="mt-2 text-[11px] text-slate-400">
                    Hosting didukung
                    <a href="https://my.idcloudhost.com/aff.php?aff=3172" rel="noopener noreferrer" target="_blank">
                        <img src="{{ base_url('/assets/images/Logo-IDcloudhost.png') }}" class="h-3.5 inline-block ml-1 opacity-70 hover:opacity-100 transition-opacity" alt="Logo-IDCloudHost" title="Logo-IDCloudHost">
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Right Side: Version & Social -->
        <div class="flex flex-col lg:flex-row items-center gap-4 shrink-0">
            @if (setting('tte'))
                <div class="hidden lg:block">
                    <img src="{{ asset('assets/images/bsre.png?v', false) }}" alt="Bsre" class="h-8" />
                </div>
            @endif
            
            <ul class="flex space-x-2">
                @foreach ($social_media as $sm)
                    @if ($link = $sm['link'])
                        <li class="inline-block">
                            <a href="{{ $link }}" class="inline-flex items-center justify-center {{ $sm['color'] }} h-7 w-7 rounded-full hover:scale-110 transition-transform">
                                <i class="fab {{ $sm['icon'] }} text-xs"></i>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
            
            <div class="text-slate-500 text-[10px] whitespace-nowrap lg:pl-2 lg:border-l lg:border-slate-700">
                <i class="fa fa-code-fork mr-1"></i> Version : {{ $xosid_version }}
            </div>
        </div>
    </div>
</footer>
