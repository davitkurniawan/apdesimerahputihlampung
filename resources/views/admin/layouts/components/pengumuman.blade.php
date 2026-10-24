<?php return; // MATIKAN PERMANEN NOTIFIKASI KILAT DARI CONTROLLER ?>
@push('css')
    <style>
        #pengumuman .modal-content {
            background: rgba(15, 23, 42, 0.8) !important;
            backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 24px !important;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }

        #pengumuman .modal-header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(6, 182, 212, 0.1)) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding: 25px !important;
            text-align: center;
        }

        #pengumuman .modal-title {
            color: white !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            letter-spacing: 1px !important;
            text-transform: uppercase;
            font-size: 18px !important;
        }

        #pengumuman .modal-body {
            padding: 35px !important;
            color: #cbd5e1 !important;
            line-height: 1.8 !important;
            font-size: 14px !important;
        }

        #pengumuman .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding: 20px 35px !important;
            background: rgba(255, 255, 255, 0.02) !important;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .xosid-agreement-list {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }

        .xosid-agreement-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .xosid-agreement-icon {
            color: var(--xosid-cyan);
            flex-shrink: 0;
            margin-top: 3px;
        }
    </style>
@endpush

{!! form_open(null, 'id="form-pengumuman"') !!}
<input type="hidden" name="notifikasi" value="1">
<input type="hidden" name="kode" value="{{ $kode }}">
<input type="hidden" id="jenis" value="{{ $jenis }}">

<div class="modal fade" id="pengumuman" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <div style="width: 50px; height: 50px; background: rgba(99, 102, 241, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="shield-check" style="color: var(--xosid-indigo); width: 30px; height: 30px;"></i>
                    </div>
                    <h4 class="modal-title">Neural Compliance Portal</h4>
                </div>
            </div>
            <div class="modal-body">
                <div id="isi">
                    @if($kode == 'persetujuan_penggunaan')
                        <div style="text-align: center; margin-bottom: 30px;">
                            <h2 style="color: white; font-weight: 800; font-size: 24px; margin-bottom: 10px;">Protokol Lisensi XOSID</h2>
                            <p style="color: #94a3b8;">Demi keamanan data dan integritas sistem, mohon tinjau protokol penggunaan berikut:</p>
                        </div>
                        
                        <div class="xosid-agreement-list">
                            <div class="xosid-agreement-item">
                                <i data-lucide="gavel" class="xosid-agreement-icon"></i>
                                <span>Aplikasi ini menggunakan basis XOSID dengan lisensi <strong>GPL V3</strong>. Anda berhak menggunakan, memodifikasi, dan menyebarkan kode ini dengan tetap menghormati hak cipta asli.</span>
                            </div>
                            <div class="xosid-agreement-item">
                                <i data-lucide="shield-alert" class="xosid-agreement-icon"></i>
                                <span>Layanan ini disediakan <strong>"AS-IS"</strong>. XOSID Intelligence menjamin fungsionalitas sistem, namun tanggung jawab keamanan data operasional sepenuhnya ada pada administrator desa.</span>
                            </div>
                            <div class="xosid-agreement-item">
                                <i data-lucide="cpu" class="xosid-agreement-icon"></i>
                                <span>Sistem akan melakukan sinkronisasi data non-sensitif secara berkala untuk keperluan optimasi performa AI dan update intelijen desa tanpa mengambil data kependudukan pribadi.</span>
                            </div>
                        </div>
                        
                        <p style="text-align: center; font-weight: 700; color: white; margin-top: 30px;">Apakah Anda menyetujui protokol intelijen di atas?</p>
                    @else
                        {!! $isi !!}
                    @endif
                </div>
                
                <center>
                    <div id="indikator" class="text-center">
                        <div class="neural-loader"></div>
                    </div>
                </center>
            </div>
            <div class="modal-footer" id="m_footer">
                @if ($jenis == 'pengumuman')
                    <button type="reset" data-dismiss="modal" id="btnSetuju" class="btn-neural">
                        <i class="fa fa-check-circle"></i> Selesai
                    </button>
                @else
                    <button type="reset" data-dismiss="modal" id="btnTidak" class="btn-neural btn-neural-secondary">
                        <i class="fa fa-times-circle"></i> Saya Tolak
                    </button>
                    <button type="submit" id="btnSetuju" class="btn-neural">
                        <i class="fa fa-shield-check"></i> Saya Setuju & Aktifkan
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
</form>

@push('scripts')
    <script>
        $('document').ready(function() {
            $('#pengumuman').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#indikator').hide();
        });

        $('#btnSetuju').on('click', function() {
            var link = `{{ ci_route($aksi_ya) }}`;
            $('#isi').hide();
            $('#m_footer').hide();
            $('#indikator').show();
            $('#btnSetuju').prop('disabled', true);
            $('#btnTidak').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: link,
                data: $('#form-pengumuman').serialize(),
                success: function() {
                    $('#indikator').hide();
                    $('#pengumuman').modal('hide');
                }
            });
            return false;
        });

        // Persetujuan langsung redirect ke aksi_tidak
        $('#btnTidak').on('click', function() {
            var link = "{{ ci_route($aksi_tidak) }}";
            if ($('#jenis').val() == 'persetujuan') location.href = link;

            $('#isi').hide();
            $('#m_footer').hide();
            $('#indikator').show();
            $('#btnSetuju').prop('disabled', true);
            $('#btnTidak').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: link,
                data: $('#form-pengumuman').serialize(),
                success: function() {
                    $('#indikator').hide();
                    $('#pengumuman').modal('hide');
                }
            });
            return false;
        });
    </script>
@endpush
