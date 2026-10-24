<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fas fa-users mr-1"></i>{{ $judul_widget }}
        </h3>
    </div>
    <div class="box-body">
        <div class="owl-carousel pt-2 pb-2">
            @foreach ($aparatur_desa['daftar_perangkat'] as $data)
                <div class="relative space-y-4 text-center">
                    <!-- Glowing Neural Avatar Frame -->
                    <div class="neural-avatar-container">
                        <div class="neural-avatar-frame">
                            <div class="neural-avatar-img-wrapper">
                                @php
                                    $foto = $data['foto'];
                                    if (strpos($foto, 'kuser.png') !== false) {
                                        $foto = base_url('assets/images/xosid_avatar.png');
                                    }
                                @endphp
                                <img src="{{ $foto }}" alt="{{ $data['nama'] }}" class="neural-avatar-img">
                            </div>
                        </div>
                    </div>
                    
                    @if (getWidgetSetting('aparatur_desa', 'overlay') == true)
                        <div class="space-y-1 text-sm text-center z-10">
                            <span class="text-h6 block font-bold text-white tracking-tight">{{ $data['nama'] }}</span>
                            <span class="block text-cyan-400 text-xs font-semibold">{{ $data['jabatan'] }}</span>
                            @if ($data['pamong_niap'])
                                <span class="block text-slate-500 text-[10px]">{{ setting('sebutan_nip_desa') }} : {{ $data['pamong_niap'] }}</span>
                            @endif
                            <div class="pt-2">
                                @if ($tampilkan_status_kehadiran)
                                    @if ($data['kehadiran'] == 1)
                                        @if ($data['status_kehadiran'] == 'hadir')
                                            <span class="btn-cyber-cyan w-auto mx-auto inline-flex py-1 px-3 text-[10px] pointer-events-none">Hadir</span>
                                        @endif
                                        @if ($data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir')
                                            <span class="btn-cyber-pink w-auto mx-auto inline-flex py-1 px-3 text-[10px] pointer-events-none">{{ ucwords($data['status_kehadiran']) }}</span>
                                        @endif
                                        @if ($data['tanggal'] != date('Y-m-d'))
                                            <span class="btn-cyber-pink w-auto mx-auto inline-flex py-1 px-3 text-[10px] pointer-events-none">Absen</span>
                                        @endif
                                    @endif
                                @else
                                    <span class="btn-cyber-gray w-auto mx-auto inline-flex py-1 px-3 text-[10px] pointer-events-none">Hari Libur</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
