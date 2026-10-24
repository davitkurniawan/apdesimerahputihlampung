@extends('xosid.portal_layout')

@section('content')
<div class="flex flex-col md:flex-row items-center gap-20">
    <div class="md:w-3/5 space-y-10">
        <div class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-600/10 border border-indigo-500/20 rounded-full">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
            </span>
            <span class="text-[10px] font-bold tracking-widest text-indigo-400 uppercase">Sistem Intelegensi Desa Aktif</span>
        </div>

        <h2 class="text-5xl md:text-7xl font-extrabold leading-[1.1] tracking-tight text-white">
            Wujudkan Desa <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400 uppercase tracking-tighter">Maju & Terkoneksi</span>
        </h2>

        <p class="text-lg text-slate-400 max-w-xl leading-relaxed">
            Selamat datang di portal resmi {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}. Rasakan kemudahan layanan publik berbasis data dan kecerdasan buatan untuk masa depan desa yang lebih cerdas.
        </p>

        <!-- KATADESA AI PROMPT -->
        <div class="ai-command-bar p-2 rounded-2xl flex items-center gap-4 max-w-2xl shadow-2xl bg-white/5 border border-white/10">
            <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="cpu" class="text-cyan-400 w-6 h-6"></i>
            </div>
            <input type="text" placeholder="Tanya apa KATADESA AI hari ini?" class="bg-transparent border-none outline-none text-white w-full font-medium placeholder:text-slate-600">
            <button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-bold text-xs shadow-lg transition-all">TANYA AI</button>
        </div>
    </div>

    <div class="md:w-2/5 flex justify-center">
        <div class="relative">
            <div class="absolute -inset-10 bg-indigo-500/20 blur-[100px] rounded-full"></div>
            <div class="relative w-80 h-80 bg-gradient-to-br from-indigo-600 to-cyan-600 rounded-[40px] shadow-2xl flex items-center justify-center overflow-hidden border border-white/20">
                <i data-lucide="shield" class="text-white/20 absolute -bottom-10 -right-10 w-64 h-64"></i>
                @if(!empty($desa['logo']) && stripos($desa['logo'], 'opensid') === false)
                    <img src="{{ gambar_desa($desa['logo']) }}" class="w-60 h-60 object-contain drop-shadow-2xl z-10" alt="Logo Desa">
                @else
                    <img src="{{ asset('images/xosid_logo.png') }}" class="w-60 h-60 object-contain drop-shadow-2xl z-10" alt="Logo Desa">
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
