@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

<div class="tab-pane" id="profil">
<div class="neural-form-container">
    <div class="peak-card" style="margin-top: 30px; margin-bottom: 30px; padding: 30px; position: relative; overflow: hidden; background: {{ $is_esensial ? '#ffffff' : 'linear-gradient(135deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6))' }}; border-radius: 24px !important; border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }};">
        <div style="position: absolute; top: -20px; right: -20px; opacity: {{ $is_esensial ? '0.03' : '0.05' }};">
            <i class="fa fa-globe" style="font-size: 150px; color: {{ $is_esensial ? '#153E7E' : 'white' }};"></i>
        </div>
        
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px; position: relative; z-index: 2;">
            <div style="width: 50px; height: 50px; background: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px {{ $is_esensial ? 'rgba(21, 62, 126, 0.3)' : 'rgba(6, 182, 212, 0.4)' }};">
                <i class="fa fa-sitemap" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <h4 style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 900; font-family: 'Outfit', sans-serif; margin: 0; font-size: 18px; letter-spacing: 0.5px;">SUPRADESA ECOSYSTEM INTEGRATION</h4>
                <p style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 12px; margin: 0; font-weight: 600;">Node Identitas Digital Wilayah dalam Jaringan Nasional XOSID</p>
            </div>
        </div>

        <!-- HIERARCHY FLOW -->
        <div style="background: {{ $is_esensial ? '#f8fafc' : 'rgba(0,0,0,0.2)' }}; padding: 30px; border-radius: 20px; border: 1px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.03)' }};">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <!-- DESA -->
                <div style="text-align: center; flex: 1;">
                    <div style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 900; font-size: 11px; font-family: 'Outfit', sans-serif; letter-spacing: 1px;">DESA</div>
                    <div style="width: 12px; height: 12px; background: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; border-radius: 50%; margin: 12px auto; box-shadow: 0 0 15px {{ $is_esensial ? 'rgba(21, 62, 126, 0.4)' : '#06b6d4' }}; position: relative; z-index: 2;"></div>
                    <div style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-size: 11px; font-weight: 800; text-transform: uppercase;">{{ $main['nama_desa'] }}</div>
                </div>

                <div style="height: 2px; flex: 0.8; background: {{ $is_esensial ? '#cbd5e1' : 'linear-gradient(90deg, #06b6d4, rgba(6, 182, 212, 0.2))' }};"></div>

                <!-- KECAMATAN -->
                <div style="text-align: center; flex: 1;">
                    <div style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 900; font-size: 11px; font-family: 'Outfit', sans-serif; letter-spacing: 1px;">KECAMATAN</div>
                    <div style="width: 10px; height: 10px; background: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; border-radius: 50%; margin: 12px auto; opacity: 0.8;"></div>
                    <div style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px; font-weight: 700; text-transform: uppercase;">{{ $main['nama_kecamatan'] }}</div>
                </div>

                <div style="height: 2px; flex: 0.8; background: {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }};"></div>

                <!-- KABUPATEN -->
                <div style="text-align: center; flex: 1;">
                    <div style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 900; font-size: 11px; font-family: 'Outfit', sans-serif; letter-spacing: 1px;">KABUPATEN</div>
                    <div style="width: 10px; height: 10px; background: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; border-radius: 50%; margin: 12px auto; opacity: 0.6;"></div>
                    <div style="color: {{ $is_esensial ? '#475569' : '#64748b' }}; font-size: 11px; font-weight: 700; text-transform: uppercase;">{{ $main['nama_kabupaten'] }}</div>
                </div>

                <div style="height: 2px; flex: 0.8; background: {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }};"></div>

                <!-- PROVINSI -->
                <div style="text-align: center; flex: 1;">
                    <div style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 900; font-size: 11px; font-family: 'Outfit', sans-serif; letter-spacing: 1px;">PROVINSI</div>
                    <div style="width: 10px; height: 10px; background: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; border-radius: 50%; margin: 12px auto; opacity: 0.4;"></div>
                    <div style="color: {{ $is_esensial ? '#475569' : '#475569' }}; font-size: 11px; font-weight: 700; text-transform: uppercase;">{{ $main['nama_propinsi'] }}</div>
                </div>

                <div style="height: 2px; flex: 0.8; background: {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }};"></div>

                <!-- NASIONAL -->
                <div style="text-align: center; flex: 1;">
                    <div style="color: #f59e0b; font-weight: 900; font-size: 11px; font-family: 'Outfit', sans-serif; letter-spacing: 1px;">NASIONAL</div>
                    <div style="width: 10px; height: 10px; background: #f59e0b; border-radius: 50%; margin: 12px auto; box-shadow: 0 0 10px rgba(245, 158, 11, 0.3);"></div>
                    <div style="color: {{ $is_esensial ? '#475569' : '#475569' }}; font-size: 11px; font-weight: 700; text-transform: uppercase;">INDONESIA</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTIONS HEADER -->
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-md-6">
            <div style="border-left: 4px solid #059669; padding-left: 15px; background: {{ $is_esensial ? 'rgba(16, 185, 129, 0.08)' : 'linear-gradient(90deg, rgba(16, 185, 129, 0.05), transparent)' }}; padding-top: 8px; padding-bottom: 8px; border-radius: 0 8px 8px 0;">
                <h4 style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 900; font-family: 'Outfit', sans-serif; margin: 0; display: flex; align-items: center; gap: 10px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa fa-leaf" style="color: #059669;"></i> EKOLOGI & SUMBER DAYA
                </h4>
            </div>
        </div>
        <div class="col-md-6">
            <div style="border-left: 4px solid {{ $is_esensial ? '#153E7E' : '#6366f1' }}; padding-left: 15px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.06)' : 'linear-gradient(90deg, rgba(99, 102, 241, 0.05), transparent)' }}; padding-top: 8px; padding-bottom: 8px; border-radius: 0 8px 8px 0;">
                <h4 style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 900; font-family: 'Outfit', sans-serif; margin: 0; display: flex; align-items: center; gap: 10px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa fa-wifi" style="color: {{ $is_esensial ? '#153E7E' : '#6366f1' }};"></i> INFRASTRUKTUR DIGITAL & STATUS
                </h4>
            </div>
        </div>
    </div>

    <!-- PAIR GRID ROWS FOR PERFECT ALIGNMENT -->
    <!-- BARIS 1: Jenis Tanah & Jenis Jaringan -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="jenis_tanah" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Jenis Tanah</label>
                <input type="text" id="jenis_tanah" name="profil_jenis_tanah" class="form-control" value="{{ $profil_desa['jenis_tanah'] ?? '' }}" placeholder="Contoh: Pertanian, Lempung, dll.">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="jenis_jaringan" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Jenis Jaringan</label>
                <input type="text" id="jenis_jaringan" name="profil_jenis_jaringan" class="form-control" value="{{ $profil_desa['jenis_jaringan'] ?? '' }}" placeholder="Contoh: Fiber Optik, 4G, dll.">
            </div>
        </div>
    </div>

    <!-- BARIS 2: Topografi & Provider Internet -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="topografi" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Topografi</label>
                <input type="text" id="topografi" name="profil_topografi" class="form-control" value="{{ $profil_desa['topografi'] ?? '' }}" placeholder="Contoh: Dataran Rendah, Bukit, dll.">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="provider_internet" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Provider Internet</label>
                <input type="text" id="provider_internet" name="profil_provider_internet" class="form-control" value="{{ $profil_desa['provider_internet'] ?? '' }}" placeholder="Contoh: Telkomsel, Indihome, dll.">
            </div>
        </div>
    </div>

    <!-- BARIS 3: Zona Rawan Bencana & Kecepatan Internet -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="rawan_bencana" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Zona Rawan Bencana</label>
                <input type="text" id="rawan_bencana" name="profil_rawan_bencana" class="form-control" value="{{ $profil_desa['rawan_bencana'] ?? '' }}" placeholder="Contoh: Banjir, Longsor, dll.">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="kecepatan_internet" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Kecepatan Internet</label>
                <input type="text" id="kecepatan_internet" name="profil_kecepatan_internet" class="form-control" value="{{ $profil_desa['kecepatan_internet'] ?? '' }}" placeholder="Contoh: 100 Mbps, dll.">
            </div>
        </div>
    </div>

    <!-- BARIS 4: Potensi SDA & Cakupan Wilayah (Textarea 3 Baris) -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="sumber_daya_alam" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Potensi SDA</label>
                <textarea id="sumber_daya_alam" name="profil_sumber_daya_alam" class="form-control" placeholder="Contoh: Air, Mineral, Hutan, dll." rows="3">{{ $profil_desa['sumber_daya_alam'] ?? '' }}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="cakupan_wilayah" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Cakupan Wilayah</label>
                <textarea id="cakupan_wilayah" name="profil_cakupan_wilayah" class="form-control" placeholder="Deskripsi cakupan..." rows="3">{{ $profil_desa['cakupan_wilayah'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    <!-- BARIS 5: Flora & Fauna & Akses Publik (Textarea 3 Baris) -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="flora_fauna" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Flora & Fauna</label>
                <textarea id="flora_fauna" name="profil_flora_fauna" class="form-control" placeholder="Contoh: Jenis tumbuhan dan hewan..." rows="3">{{ $profil_desa['flora_fauna'] ?? '' }}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="akses_publik" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Akses Publik</label>
                <textarea id="akses_publik" name="profil_akses_publik" class="form-control" placeholder="Deskripsi akses publik..." rows="3">{{ $profil_desa['akses_publik'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    <!-- BARIS 6: Kearifan Lokal (Textarea 3 Baris) & Status Desa (Dropdown) -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="kearifan_lokal" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Kearifan Lokal</label>
                <textarea id="kearifan_lokal" name="profil_kearifan_lokal" class="form-control" placeholder="Contoh: Tradisi, Budaya, dll." rows="3">{{ $profil_desa['kearifan_lokal'] ?? '' }}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label for="status_desa" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">Status Desa</label>
                <select id="status_desa" name="profil_status_desa" class="form-control" style="background: {{ $is_esensial ? '#ffffff' : 'rgba(0,0,0,0.3)' }} !important; color: {{ $is_esensial ? '#0f172a' : 'white' }} !important; border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.1)' }} !important;">
                    <option value="Bukan Adat" {{ ($profil_desa['status_desa'] ?? '') == 'Bukan Adat' ? 'selected' : '' }}>Bukan Adat</option>
                    <option value="Adat" {{ ($profil_desa['status_desa'] ?? '') == 'Adat' ? 'selected' : '' }}>Adat</option>
                </select>
            </div>
        </div>
    </div>

    <!-- STATUS ADAT FIELDS (SHOWS DYNAMICALLY) -->
    <div class="row" id="adat_fields_container" style="margin-top: 30px; display: {{ ($profil_desa['status_desa'] ?? '') == 'Adat' ? 'block' : 'none' }};">
        <div class="col-md-12">
            <div style="background: {{ $is_esensial ? '#f8fafc' : 'linear-gradient(135deg, rgba(99, 102, 241, 0.1), transparent)' }}; border-radius: 20px; border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(99, 102, 241, 0.2)' }}; padding: 25px;">
                <h5 style="color: {{ $is_esensial ? '#153E7E' : '#818cf8' }}; font-weight: 900; font-family: 'Outfit', sans-serif; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 14px;">
                    <i class="fa fa-shield"></i> Atribut Kedaulatan Adat
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group neural-vertical-group">
                            <label for="lembaga_adat" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">LEMBAGA ADAT</label>
                            <input id="lembaga_adat" name="profil_lembaga_adat" class="form-control" type="text" value="{{ $profil_desa['lembaga_adat'] ?? '' }}" placeholder="Nama Lembaga Adat" />
                        </div>
                    </div>
                    
                    @php
                        $struktur_adat_path = LOKASI_DOKUMEN . ($profil_desa['struktur_adat'] ?? '');
                        $struktur_adat_file = FCPATH . $struktur_adat_path;
                        
                        $dokumen_regulasi_path = LOKASI_DOKUMEN . ($profil_desa['dokumen_regulasi_penetapan_kampung_adat'] ?? '');
                        $dokumen_regulasi_file = FCPATH . $dokumen_regulasi_path;
                    @endphp

                    <div class="col-md-6">
                        <div class="form-group neural-vertical-group">
                            <label style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">STRUKTUR ADAT</label>
                            @if (!empty($profil_desa['struktur_adat']) && file_exists($struktur_adat_file))
                                <div style="margin-bottom: 10px;">
                                    <img class="img-responsive img-thumbnail" src="{{ base_url($struktur_adat_path) }}" style="max-height: 120px;">
                                </div>
                            @endif
                            <div class="xosid-file-picker-wrap">
                                <input type="text" class="form-control" id="file_path_struktur" name="profil_struktur_adat_nama" readonly value="{{ $profil_desa['struktur_adat'] ?? '' }}">
                                <input id="file_struktur" type="file" class="hidden" name="profil_struktur_adat" accept=".jpg,.jpeg,.png,.webp" />
                                <button type="button" class="btn browse-file" data-target="file_struktur" style="background: #eff6ff !important; border: 1.5px solid #bfdbfe !important; color: #153E7E !important; font-weight: 700; border-radius: 12px; padding: 0 16px; height: 42px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa fa-search"></i> Browse</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" style="margin-top: 15px;">
                        <div class="form-group neural-vertical-group">
                            <label for="wilayah_adat" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">WILAYAH ADAT</label>
                            <textarea id="wilayah_adat" name="profil_wilayah_adat" class="form-control" placeholder="Deskripsi wilayah adat..." rows="3">{{ $profil_desa['wilayah_adat'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6" style="margin-top: 15px;">
                        <div class="form-group neural-vertical-group">
                            <label for="peraturan_adat" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">PERATURAN ADAT</label>
                            <textarea id="peraturan_adat" name="profil_peraturan_adat" class="form-control" placeholder="Deskripsi peraturan adat..." rows="3">{{ $profil_desa['peraturan_adat'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6" style="margin-top: 15px;">
                        <div class="form-group neural-vertical-group">
                            <label for="regulasi_penetapan_kampung_adat" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">REGULASI PENETAPAN</label>
                            <input id="regulasi_penetapan_kampung_adat" name="profil_regulasi_penetapan_kampung_adat" class="form-control" type="text" value="{{ $profil_desa['regulasi_penetapan_kampung_adat'] ?? '' }}" placeholder="SK Bupati / Regulasi" />
                        </div>
                    </div>

                    <div class="col-md-6" style="margin-top: 15px;">
                        <div class="form-group neural-vertical-group">
                            <label style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">DOKUMEN REGULASI PENETAPAN</label>
                            @if (!empty($profil_desa['dokumen_regulasi_penetapan_kampung_adat']) && file_exists($dokumen_regulasi_file))
                                <div style="margin-bottom: 10px;">
                                    <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 40px; color: #ef4444; cursor: pointer;"
                                    data-title="Berkas {{ $profil_desa['dokumen_regulasi_penetapan_kampung_adat'] }}"
                                    data-url="{{ base_url($dokumen_regulasi_path) }}"></i>
                                </div>
                            @endif
                            <div class="xosid-file-picker-wrap">
                                <input type="text" class="form-control" id="file_path_dokumen" name="profil_dokumen_regulasi_penetapan_kampung_adat_nama" readonly value="{{ $profil_desa['dokumen_regulasi_penetapan_kampung_adat'] ?? '' }}">
                                <input id="file_dokumen" type="file" class="hidden" name="profil_dokumen_regulasi_penetapan_kampung_adat" accept=".pdf" />
                                <button type="button" class="btn browse-file" data-target="file_dokumen" style="background: #eff6ff !important; border: 1.5px solid #bfdbfe !important; color: #153E7E !important; font-weight: 700; border-radius: 12px; padding: 0 16px; height: 42px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa fa-search"></i> Browse</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#status_desa').change(function() {
            if ($(this).val() === 'Adat') {
                $('#adat_fields_container').slideDown();
            } else {
                $('#adat_fields_container').slideUp();
            }
        });

        // Klik tombol browse
        $(document).on('click', '.browse-file', function () {
            const target = $(this).data('target');
            $('#' + target).trigger('click');
        });

        // Klik input text -> buka file picker
        $(document).on('click', 'input[id^="file_path_"]', function () {
            const fileInputId = this.id.replace('file_path_', 'file_');
            $('#' + fileInputId).trigger('click');
        });

        // Saat file dipilih -> tampilkan nama file di input text
        $(document).on('change', 'input[type="file"]', function () {
            const fileName = $(this).val().split('\\').pop();
            const textInputId = this.id.replace('file_', 'file_path_');
            $('#' + textInputId).val(fileName);
        });
    });
</script>
@endpush
