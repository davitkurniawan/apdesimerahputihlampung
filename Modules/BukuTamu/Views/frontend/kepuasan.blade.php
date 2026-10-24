@extends('bukutamu::frontend.index')

@push('css')
<style>
    .guest-avatar {
        transform: scaleX(-1);
    }
</style>
@endpush

@section('content')
<div class="glass-card rounded-[32px] p-8">
    
    <!-- Title Section -->
    <div class="flex items-center gap-4 border-b border-white/5 pb-6 mb-6">
        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
            <i data-lucide="smile" class="w-5 h-5"></i>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-white leading-tight">Indeks Kepuasan</h2>
            <p class="text-xs text-gray-400 mt-1">Evaluasi kualitas pelayanan publik kami hari ini</p>
        </div>
    </div>

    <!-- Main Content Body -->
    <div>
        @if (!$ada_pertanyaan)
            <!-- Questionnaire Not Available Callout -->
            <div class="flex flex-col items-center justify-center p-12 text-center border border-white/5 bg-white/5 rounded-2xl gap-4">
                <div class="w-14 h-14 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-400">
                    <i data-lucide="alert-triangle" class="w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Kuisioner Tidak Tersedia</h3>
                    <p class="text-sm text-gray-400 mt-1 leading-relaxed">
                        Maaf, instrumen penilaian kepuasan belum dikonfigurasi oleh administrator.
                    </p>
                </div>
            </div>

        @elseif (count($tamu_hari_ini) > 0)
            <!-- Selection Prompt -->
            <div class="mb-6">
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest block mb-1">Daftar Kehadiran</span>
                <h3 class="text-lg font-bold text-white">Silakan Pilih Nama Anda</h3>
            </div>

            <!-- Guest List Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($tamu_hari_ini as $i => $tamu)
                    <a href="{{ site_url('buku-tamu/kepuasan/' . $tamu->id) }}" class="group block">
                        <div class="glass-card glass-card-hover rounded-2xl p-5 flex flex-col items-center text-center transition-all duration-300">
                            <!-- Avatar -->
                            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-white/10 group-hover:border-cyan-400 group-hover:scale-105 transition-all shadow-lg relative bg-gray-900">
                                @if(!empty($tamu->url_foto))
                                    <img src="{{ $tamu->url_foto }}" class="w-full h-full object-cover guest-avatar" alt="{{ $tamu->nama }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                                        <i data-lucide="user" class="w-8 h-8"></i>
                                    </div>
                                @endif
                            </div>
                            <!-- Name -->
                            <div class="text-sm font-bold text-gray-200 group-hover:text-white mt-4 line-clamp-1 transition-colors">
                                {{ $tamu->nama }}
                            </div>
                            <div class="text-[10px] text-gray-500 mt-1 font-semibold tracking-wider uppercase">
                                {{ $tamu->instansi }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        @else
            <!-- Empty State Guest List -->
            <div class="flex flex-col items-center justify-center p-12 text-center border border-white/5 bg-white/5 rounded-2xl gap-4">
                <div class="w-14 h-14 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Belum Ada Tamu Hari Ini</h3>
                    <p class="text-sm text-gray-400 mt-1 leading-relaxed">
                        Formulir indeks kepuasan hanya dapat diisi oleh pengunjung yang terdaftar hari ini.
                    </p>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    let ada_pertanyaan = `{{ $ada_pertanyaan }}`;
    let url_reload = `{{ site_url('buku-tamu') }}`;

    if (!ada_pertanyaan) {
        setTimeout(function() {
            window.location.href = url_reload;
        }, 2500);
    }

    // Refresh icons
    lucide.createIcons();
</script>
@endpush
