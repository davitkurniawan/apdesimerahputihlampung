@extends('bukutamu::frontend.index')

@push('css')
<style>
    .capture-box video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transform: scaleX(-1) !important;
    }

    @keyframes scan {
        0%, 100% { top: 2%; }
        50% { top: 96%; }
    }

    .scan-line {
        animation: scan 3s ease-in-out infinite;
    }

    /* Option styling to ensure readability on dropdown popups */
    select option {
        background-color: #0f172a;
        color: #f3f4f6;
    }
</style>
@endpush

@section('content')
<!-- Glassmorphic Card Container -->
<div class="glass-card rounded-[32px] p-8">
    <div class="flex items-center gap-4 border-b border-white/5 pb-6 mb-6">
        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
            <i data-lucide="user-check" class="w-5 h-5"></i>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-white leading-tight">Register Tamu</h2>
            <p class="text-xs text-gray-400 mt-1">Silakan lengkapi formulir kunjungan di bawah ini</p>
        </div>
    </div>

    {!! form_open($aksi, ['id' => 'mainform']) !!}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Form Fields Section -->
        <div class="lg:col-span-8 flex flex-col gap-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Nama -->
                <div class="form-group flex flex-col">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        class="form-control required nama required w-full bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm text-white transition-all placeholder:text-gray-500"
                        maxlength="50"
                        placeholder="Masukkan Nama Lengkap"
                        name="nama"
                        autocomplete="off"
                        required>
                </div>

                <!-- Asal Instansi -->
                <div class="form-group flex flex-col">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Asal Instansi / Lembaga</label>
                    <input
                        type="text"
                        class="form-control required w-full bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm text-white transition-all placeholder:text-gray-500"
                        placeholder="Asal Instansi"
                        name="instansi"
                        maxlength="100"
                        autocomplete="off"
                        required>
                </div>

                <!-- Jenis Kelamin -->
                <div class="form-group flex flex-col">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control form-select required w-full bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm text-white transition-all" required>
                        <option value="" label="Pilih">Pilih Jenis Kelamin</option>
                        @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bertemu -->
                <div class="form-group flex flex-col">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tujuan Menemui</label>
                    <select class="form-control form-select required w-full bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm text-white transition-all" name="id_bidang" required>
                        <option label="Pilih" value="">Pilih Tujuan Temu</option>
                        @foreach ($bertemu as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- No Telp -->
                <div class="form-group flex flex-col">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">No. Telp / WhatsApp</label>
                    <input
                        type="text"
                        class="form-control bilangan telepon required w-full bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm text-white transition-all placeholder:text-gray-500"
                        name="telepon"
                        placeholder="Masukkan No. Telp / HP"
                        maxlength="20"
                        pattern="[0-9]+"
                        autocomplete="off"
                        required>
                </div>

                <!-- Keperluan -->
                <div class="form-group flex flex-col">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Bidang Keperluan</label>
                    <select name="keperluan" class="form-control form-select required w-full bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm text-white transition-all" required>
                        <option label="Pilih" value="">Pilih Keperluan</option>
                        @foreach ($keperluan as $key => $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                        <option value="0">Lainnya</option>
                    </select>
                </div>

            </div>

            <!-- Alamat -->
            <div class="form-group flex flex-col" id="alamat">
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alamat Rumah</label>
                <textarea class="form-control w-full bg-white/5 border border-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm text-white transition-all placeholder:text-gray-500 h-24 resize-none" name="alamat" placeholder="Masukkan Alamat Lengkap" maxlength="500"></textarea>
            </div>

            <!-- Keperluan Lainnya (Dynamic Field) -->
            <div class="form-group flex flex-col" id="lainnya" style="display: none;">
                <label class="text-xs font-semibold text-rose-400 uppercase tracking-wider mb-2">Keperluan Lainnya</label>
                <textarea class="form-control w-full bg-white/5 border border-rose-500/20 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl px-4 py-3 text-sm text-white transition-all placeholder:text-gray-500 h-24 resize-none" name="keperluan_lainnya" placeholder="Jelaskan Keperluan Anda..." maxlength="500"></textarea>
            </div>
        </div>

        <!-- Camera & Submission Actions Section -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            
            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Foto Kunjungan (Verifikasi)</label>
                
                <!-- High-tech Camera Preview Box -->
                <div class="capture-box relative w-full aspect-[4/3] rounded-2xl overflow-hidden bg-black/50 border border-white/10 flex items-center justify-center shadow-inner group">
                    <div class="capture-live w-full h-full z-10"></div>
                    
                    <!-- Neon Scan Line Animation -->
                    <div class="scan-line absolute left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-[0_0_8px_#06b6d4] z-20"></div>
                </div>

                <!-- Camera Trigger Button -->
                <div class="w-full" id="capture">
                    <button type="button" class="w-full py-3.5 bg-cyan-600/10 hover:bg-cyan-600 border border-cyan-500/20 hover:border-cyan-500 text-cyan-400 hover:text-white rounded-xl text-xs font-bold transition-all shadow-[0_4px_12px_rgba(6,182,212,0.15)] flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="aperture" class="w-4 h-4"></i>Ambil Foto (Capture)
                    </button>
                </div>
            </div>

            <!-- Hidden base64 photo input -->
            <input type="hidden" id="foto" name="foto">

            <!-- Submit & Cancel Actions -->
            <div class="flex flex-col gap-3 mt-auto">
                <button class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-cyan-500 hover:from-indigo-600 hover:to-cyan-600 text-white font-bold rounded-xl text-sm transition-all shadow-[0_4px_16px_rgba(99,102,241,0.25)] hover:shadow-[0_6px_24px_rgba(99,102,241,0.35)] hover:-translate-y-0.5 flex items-center justify-center gap-2" type="submit" id="simpan" {{ $kamera ? 'disabled' : '' }}>
                    <i data-lucide="check" class="w-4 h-4"></i>Simpan Kunjungan
                </button>
                <button class="w-full py-3.5 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 text-gray-400 hover:text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2" type="reset">
                    <i data-lucide="x" class="w-4 h-4"></i>Batal / Reset
                </button>
            </div>

        </div>

    </div>
    {!! form_close() !!}
</div>
@endsection

@push('scripts')
@if ($kamera)
<script src="{{ asset('js/webcam.min.js') }}"></script>
<script>
    // Webcam configuration
    Webcam.set({
        image_format: 'jpg',
        jpeg_quality: 100,
        noInterfaceFoundText: 'Kamera tidak terdeteksi / tidak didukung. Mohon periksa kembali dan pastikan website menggunakan protokol SSL (HTTPS).',
    });
    Webcam.attach('.capture-box');

    Webcam.on('error', function(err) {
        if (err == 'NotAllowedError: Permission denied') {
            err = 'Izin kamera ditolak. Pastikan Anda mengaktifkan izin kamera di browser dan menggunakan protokol SSL (HTTPS).';
        }
        Swal.fire({
            icon: 'error',
            title: 'Akses Kamera Gagal',
            text: err,
            background: "#0d1117",
            color: "#fff",
            confirmButtonText: "Tutup",
            customClass: {
                confirmButton: "px-6 py-2 bg-rose-600 text-white rounded-lg text-sm"
            }
        });
    });

    $("#capture").click(function() {
        Webcam.snap(function(data_uri) {
            let height = $('.capture-box').height();
            let width = $('.capture-box').width();
            $("#foto").val(data_uri);
            $('.capture-box').prepend('<img src="' + data_uri + '" style="position: absolute; inset: 0; height: 100%; width: 100%; object-fit: cover; border-radius: 1rem; transform: scaleX(-1); z-index: 30;"/>');
            $('.capture-box').find('video').hide();
            $('.capture-box').find('.scan-line').hide();
            $("#simpan").prop("disabled", false);
        });
        $(this).remove();
    });
</script>
@endif
<script>
    // Dynamic field logic for "Keperluan Lainnya"
    function updateKeperluanLayout() {
        if ($('select[name="keperluan"]').val() === '0') {
            $('#lainnya').show();
            $('textarea[name="keperluan_lainnya"]').prop('required', true);
        } else {
            $('#lainnya').hide();
            $('textarea[name="keperluan_lainnya"]').prop('required', false);
        }
        lucide.createIcons(); // refresh icons just in case
    }

    $('select[name="keperluan"]').on('change', updateKeperluanLayout);
    
    // Refresh icons
    lucide.createIcons();
</script>
@endpush
