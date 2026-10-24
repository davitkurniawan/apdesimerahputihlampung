@extends('bukutamu::frontend.index')

@section('content')
<div class="glass-card rounded-[32px] p-8">
    
    <!-- Title Section -->
    <div class="flex items-center gap-4 border-b border-white/5 pb-6 mb-6">
        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
            <i data-lucide="heart" class="w-5 h-5"></i>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-white leading-tight">Penilaian Layanan</h2>
            <p class="text-xs text-gray-400 mt-1">Umpan balik Anda sangat berharga bagi kami</p>
        </div>
    </div>

    <!-- Question block -->
    <div class="my-8 text-center py-4 bg-white/5 border border-white/5 rounded-2xl px-6 relative overflow-hidden">
        <div class="absolute w-24 h-24 -top-8 -right-8 bg-indigo-500/5 rounded-full filter blur-xl"></div>
        <div class="absolute w-24 h-24 -bottom-8 -left-8 bg-cyan-500/5 rounded-full filter blur-xl"></div>
        
        <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest block mb-2">Pertanyaan Indeks Kepuasan</span>
        <h3 class="text-xl sm:text-2xl font-extrabold text-white leading-relaxed tracking-wide">
            "{{ $pertanyaan->pertanyaan }}"
        </h3>
    </div>

    <!-- Answer Emojis Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
        @foreach (\App\Enums\JawabanKepuasanEnum::all() as $index => $jawaban)
            @php
                // Dynamic border colors and shadows based on satisfaction index
                // Very Satisfied / Satisfied (green/cyan) vs Less Satisfied / Not Satisfied (amber/rose)
                $hoverGlow = ($index <= 1) 
                    ? 'hover:border-emerald-500/50 hover:shadow-[0_8px_30px_rgb(16,185,129,0.15)] group-hover:text-emerald-400'
                    : 'hover:border-rose-500/50 hover:shadow-[0_8px_30px_rgb(244,63,94,0.15)] group-hover:text-rose-400';
            @endphp
            <a href="{{ site_url('buku-tamu/jawaban/' . $id . '/' . $index . '?pertanyaan=' . $pertanyaan->pertanyaan) }}" class="group block">
                <div class="glass-card rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all duration-300 hover:scale-[1.04] border border-white/5 {{ $hoverGlow }}">
                    
                    <!-- Emoji Image -->
                    <div class="w-16 h-16 mb-4 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                        <img src="{{ asset('images/layanan_mandiri/' . underscore($jawaban, true, true) . '.png') }}" 
                             class="w-full h-full object-contain filter drop-shadow-[0_4px_12px_rgba(0,0,0,0.35)]" 
                             alt="{{ $jawaban }}">
                    </div>
                    
                    <!-- Satisfaction Label -->
                    <div class="text-sm font-bold text-gray-200 group-hover:text-white transition-colors duration-200">
                        {{ $jawaban }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Refresh icons
    lucide.createIcons();
</script>
@endpush
