@extends('admin.layouts.index')

@section('content')
<style>
    /* TOTAL BLACKOUT - Force global dark theme for this module */
    html, body, .wrapper, .content-wrapper, .content, .main-footer, .main-header {
        background: #060910 !important;
        background-color: #060910 !important;
        border: none !important;
        box-shadow: none !important;
        color: #f1f5f9 !important;
    }

    .neural-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        padding: 30px;
        margin-bottom: 30px;
    }

    .input-xosid {
        background: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        width: 100%;
        outline: none;
        transition: all 0.3s ease;
    }

    .input-xosid:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.2) !important;
    }

    .label-xosid {
        font-family: 'Outfit', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 8px;
        display: block;
    }

    .btn-xosid {
        background: rgba(6, 182, 212, 0.03) !important;
        color: #94a3b8 !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        padding: 12px 24px !important;
        border-radius: 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        letter-spacing: 1px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
    }

    .btn-xosid i {
        color: #06b6d4 !important;
        opacity: 0.8 !important;
    }

    .btn-xosid:hover {
        background: rgba(6, 182, 212, 0.08) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
        color: white !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 0 15px rgba(6, 182, 212, 0.2) !important;
    }

    .btn-xosid-primary {
        background: rgba(6, 182, 212, 0.08) !important;
        border: 1px solid #06b6d4 !important;
        color: white !important;
        box-shadow: 0 8px 20px -10px rgba(6, 182, 212, 0.3) !important;
    }

    .btn-xosid-primary:hover {
        background: rgba(6, 182, 212, 0.15) !important;
        color: white !important;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.4) !important;
        border-color: #06b6d4 !important;
    }

    .health-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .health-sehat { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; }
    .health-cukup_sehat { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .health-kurang_sehat { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; }
    .health-tidak_sehat { background: rgba(120, 113, 108, 0.1); border: 1px solid rgba(120, 113, 108, 0.2); color: #78716c; }

    /* SELECT2 DARK THEME SHIELD */
    .select2-container--default .select2-selection--single {
        background: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        height: 48px !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
        padding-left: 16px !important;
        padding-right: 30px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #64748b transparent transparent transparent !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #64748b transparent !important;
    }
    .select2-dropdown {
        background: #0f172a !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
        overflow: hidden !important;
    }
    .select2-results__option {
        color: #94a3b8 !important;
        padding: 10px 16px !important;
    }
    .select2-results__option--highlighted[aria-selected], .select2-results__option[aria-selected=true] {
        background: #06b6d4 !important;
        color: white !important;
    }
    .select2-search__field {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #64748b !important;
    }
</style>

<div class="content-wrapper-inner" style="padding: 30px;">
    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 10px; color: #06b6d4; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px;">MODUL UTAMA BUMDes</div>
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 28px; color: white; margin: 0; display: flex; align-items: center; gap: 12px;">
                Dashboard & Legalitas
                @if(!empty($profil['status_kesehatan']))
                    <span class="health-badge health-{{ $profil['status_kesehatan'] }}">
                        <i data-lucide="activity" style="width: 12px;"></i> {{ str_replace('_', ' ', $profil['status_kesehatan']) }}
                    </span>
                @endif
            </h2>
        </div>
    </div>

    <!-- METRICS OVERVIEW -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-4">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #06b6d4;">
                    <i data-lucide="award" style="width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Hukum</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: white; margin-top: 2px;">
                        {{ $profil['nomor_badan_hukum'] ?: 'Belum Terdaftar' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #8b5cf6;">
                    <i data-lucide="file-check-2" style="width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">SK Pendirian</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: white; margin-top: 2px;">
                        {{ $profil['sk_pendirian'] ?: 'Belum Diunggah' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                    <i data-lucide="calendar" style="width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Berdiri</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: white; margin-top: 2px;">
                        {{ !empty($profil['tanggal_berdiri']) ? date('d-m-Y', strtotime($profil['tanggal_berdiri'])) : 'Belum Diisi' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NEW METRICS ROW (ORGANS) -->
    <div class="row" style="margin-bottom: 30px;">
        <div class="col-md-4">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #f59e0b;">
                    <i data-lucide="user-check" style="width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Penasihat</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: white; margin-top: 2px;">
                        {{ $profil['nama_penasihat'] ?: 'Belum Diisi' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #ef4444;">
                    <i data-lucide="briefcase" style="width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Direktur Utama</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: white; margin-top: 2px;">
                        {{ $profil['nama_direktur'] ?: 'Belum Diisi' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #06b6d4;">
                    <i data-lucide="store" style="width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Unit Usaha Aktif</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: white; margin-top: 2px;">
                        {{ !empty($profil['unit_usaha']) ? count(array_filter(array_map('trim', explode(',', $profil['unit_usaha'])))) . ' Unit' : '0 Unit' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ site_url('bumdes_profil') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="row">
            <div class="col-md-8">
                <div class="neural-card">
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 18px; color: white; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <i data-lucide="settings" style="width: 20px; color: #06b6d4;"></i> Pengaturan Legalitas BUMDes
                    </h4>
            <div class="row">
                <div class="col-md-6" style="margin-bottom: 20px;">
                    <label class="label-xosid">Nama Resmi BUMDes</label>
                    <input type="text" name="nama_bumdes" required value="{{ $profil['nama_bumdes'] ?? '' }}" placeholder="Contoh: BUMDes Berkarya Mandiri" class="input-xosid">
                </div>
                <div class="col-md-6" style="margin-bottom: 20px;">
                    <label class="label-xosid">Nomor Badan Hukum (Kemenkumham)</label>
                    <input type="text" name="nomor_badan_hukum" value="{{ $profil['nomor_badan_hukum'] ?? '' }}" placeholder="Contoh: AHU-0012345.AH.01.33.Tahun 2026" class="input-xosid">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <label class="label-xosid">Nomor SK Pendirian</label>
                    <input type="text" name="sk_pendirian" value="{{ $profil['sk_pendirian'] ?? '' }}" placeholder="Contoh: 141/05/KPTS/2026" class="input-xosid">
                </div>
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <label class="label-xosid">Tanggal Berdiri</label>
                    <input type="date" name="tanggal_berdiri" value="{{ $profil['tanggal_berdiri'] ?? '' }}" class="input-xosid">
                </div>
                <div class="col-md-4" style="margin-bottom: 20px;">
                    <label class="label-xosid">Tingkat Penilaian Kesehatan</label>
                    <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); padding: 12px 16px; border-radius: 12px; height: 48px; display: flex; align-items: center; justify-content: space-between;">
                        <span class="health-badge health-{{ $health_status }}" style="padding: 4px 10px; font-size: 11px;">
                            <i data-lucide="activity" style="width: 12px;"></i> {{ str_replace('_', ' ', $health_status) }}
                        </span>
                        <span style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #06b6d4; font-size: 14px;">{{ $health_score }}/100 Poin</span>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="col-md-6" style="margin-bottom: 20px;">
                    <label class="label-xosid">Unggah Dokumen AD / ART (PDF)</label>
                    <input type="file" name="ad_art" class="input-xosid" style="padding: 10px;">
                    @if(!empty($profil['ad_art_path']))
                        <div style="margin-top: 8px;">
                            <a href="{{ base_url($profil['ad_art_path']) }}" target="_blank" class="btn-xosid" style="padding: 6px 12px !important; border-radius: 8px !important; font-size: 10px !important;">
                                <i data-lucide="eye" style="width: 12px;"></i> Lihat File AD/ART Saat Ini
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-md-6" style="margin-bottom: 20px;">
                    <label class="label-xosid">Unggah SK Pengurus (PDF)</label>
                    <input type="file" name="sk_pengurus" class="input-xosid" style="padding: 10px;">
                    @if(!empty($profil['sk_pengurus_path']))
                        <div style="margin-top: 8px;">
                            <a href="{{ base_url($profil['sk_pengurus_path']) }}" target="_blank" class="btn-xosid" style="padding: 6px 12px !important; border-radius: 8px !important; font-size: 10px !important;">
                                <i data-lucide="eye" style="width: 12px;"></i> Lihat File SK Pengurus Saat Ini
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- STRUKTUR ORGANISASI BUMDes (PP 11/2021) -->
            <div class="neural-card" style="margin-top: 30px; padding: 30px 30px 20px 30px;">
                <h4 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 18px; color: white; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="users" style="width: 20px; color: #8b5cf6;"></i> Struktur Organisasi BUMDes (PP No. 11/2021)
                </h4>

                <div class="row">
                    <div class="col-md-6" style="margin-bottom: 20px;">
                        <label class="label-xosid">1. Penasihat BUMDes (Ex-Officio Kepala Desa)</label>
                        <select name="nama_penasihat" class="input-xosid select2-penduduk" style="width: 100%;">
                            @if(!empty($profil['penasihat_id']))
                                <option value="{{ $profil['penasihat_id'] }}" selected>{{ $profil['nama_penasihat'] }}</option>
                            @elseif(!empty($profil['nama_penasihat']))
                                <option value="{{ $profil['nama_penasihat'] }}" selected>{{ $profil['nama_penasihat'] }}</option>
                            @endif
                        </select>
                        <small style="color: #64748b; font-size: 11px; margin-top: 4px; display: block;">Menurut aturan, jabatan ini dipegang secara ex-officio oleh Kepala Desa.</small>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 20px;">
                        <label class="label-xosid">2. Pengawas BUMDes (Ketua / Perwakilan)</label>
                        <select name="nama_pengawas" class="input-xosid select2-penduduk" style="width: 100%;">
                            @if(!empty($profil['pengawas_id']))
                                <option value="{{ $profil['pengawas_id'] }}" selected>{{ $profil['nama_pengawas'] }}</option>
                            @elseif(!empty($profil['nama_pengawas']))
                                <option value="{{ $profil['nama_pengawas'] }}" selected>{{ $profil['nama_pengawas'] }}</option>
                            @endif
                        </select>
                        <small style="color: #64748b; font-size: 11px; margin-top: 4px; display: block;">Diangkat oleh Musyawarah Desa untuk mengawasi operasional BUMDes.</small>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <label class="label-xosid" style="color: #06b6d4;">3. Pelaksana Operasional BUMDes</label>
                        <div class="row" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.03); padding: 20px; border-radius: 16px; margin: 0 0 20px 0;">
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <label class="label-xosid" style="font-size: 10px;">Direktur Utama</label>
                                <select name="nama_direktur" class="input-xosid select2-penduduk" style="width: 100%;">
                                    @if(!empty($profil['direktur_id']))
                                        <option value="{{ $profil['direktur_id'] }}" selected>{{ $profil['nama_direktur'] }}</option>
                                    @elseif(!empty($profil['nama_direktur']))
                                        <option value="{{ $profil['nama_direktur'] }}" selected>{{ $profil['nama_direktur'] }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <label class="label-xosid" style="font-size: 10px;">Sekretaris</label>
                                <select name="nama_sekretaris" class="input-xosid select2-penduduk" style="width: 100%;">
                                    @if(!empty($profil['sekretaris_id']))
                                        <option value="{{ $profil['sekretaris_id'] }}" selected>{{ $profil['nama_sekretaris'] }}</option>
                                    @elseif(!empty($profil['nama_sekretaris']))
                                        <option value="{{ $profil['nama_sekretaris'] }}" selected>{{ $profil['nama_sekretaris'] }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <label class="label-xosid" style="font-size: 10px;">Bendahara</label>
                                <select name="nama_bendahara" class="input-xosid select2-penduduk" style="width: 100%;">
                                    @if(!empty($profil['bendahara_id']))
                                        <option value="{{ $profil['bendahara_id'] }}" selected>{{ $profil['nama_bendahara'] }}</option>
                                    @elseif(!empty($profil['nama_bendahara']))
                                        <option value="{{ $profil['nama_bendahara'] }}" selected>{{ $profil['nama_bendahara'] }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- UNIT USAHA BUMDes -->
            <div class="neural-card" style="margin-top: 30px; padding: 30px 30px 20px 30px;">
                <h4 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 18px; color: white; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="store" style="width: 20px; color: #10b981;"></i> Unit Usaha BUMDes
                </h4>

                <div class="row">
                    <div class="col-md-12">
                        <label class="label-xosid">Daftar Unit Usaha Aktif</label>
                        <textarea name="unit_usaha" rows="3" placeholder="Contoh: Unit Air Bersih, Unit Pengolahan Sampah, Unit Desa Wisata, Unit Toko Desa (pisahkan dengan koma)" class="input-xosid" style="resize: vertical;">{{ $profil['unit_usaha'] ?? '' }}</textarea>
                        <small style="color: #64748b; font-size: 11px; margin-top: 6px; display: block;">Tuliskan seluruh unit usaha aktif milik BUMDes, dipisahkan oleh tanda koma (,).</small>
                    </div>
                </div>
            </div>

                </div> <!-- Close Pengaturan Legalitas neural-card -->

                <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; margin-bottom: 30px;">
                    <button type="reset" class="btn-xosid">
                        <i data-lucide="rotate-ccw" style="width: 16px;"></i> Reset
                    </button>
                    <button type="submit" class="btn-xosid btn-xosid-primary">
                        <i data-lucide="save" style="width: 16px;"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>

            <!-- RIGHT COLUMN: HEALTH ASSESSMENT ANALYSIS -->
            <div class="col-md-4">
                <div class="neural-card" style="position: sticky; top: 30px; padding: 30px;">
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 18px; color: white; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <i data-lucide="shield-check" style="width: 20px; color: #06b6d4;"></i> Penilaian Kesehatan BUMDes
                    </h4>

                    <!-- Score Gauge -->
                    <div style="text-align: center; margin-bottom: 30px; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); padding: 25px; border-radius: 20px;">
                        <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Total Skor Kinerja</div>
                        <div style="font-family: 'Outfit', sans-serif; font-size: 48px; font-weight: 900; color: white; line-height: 1; display: flex; align-items: baseline; justify-content: center; gap: 5px;">
                            {{ $health_score }} <span style="font-size: 18px; color: #64748b; font-weight: 500;">/100</span>
                        </div>
                        <div style="margin-top: 15px;">
                            <span class="health-badge health-{{ $health_status }}" style="font-size: 11px; padding: 6px 14px;">
                                <i data-lucide="heart" style="width: 12px; fill: currentColor;"></i> {{ strtoupper(str_replace('_', ' ', $health_status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Assessment Items -->
                    <div class="space-y-4">
                        <!-- Legalitas -->
                        <div style="margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px;">
                                    <i data-lucide="scale" style="width: 16px; color: #06b6d4;"></i> Legalitas & Dokumen
                                </span>
                                <span style="font-size: 11px; color: #06b6d4; font-weight: 700;">{{ $health_breakdown['score_legal'] }}/30 Poin</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 8px; padding-left: 24px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>Nomor Badan Hukum</span>
                                    <i data-lucide="{{ $health_breakdown['has_badan_hukum'] ? 'check-circle-2' : 'x-circle' }}" style="width: 14px; color: {{ $health_breakdown['has_badan_hukum'] ? '#10b981' : '#ef4444' }};"></i>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>SK Pendirian BUMDes</span>
                                    <i data-lucide="{{ $health_breakdown['has_sk_pendirian'] ? 'check-circle-2' : 'x-circle' }}" style="width: 14px; color: {{ $health_breakdown['has_sk_pendirian'] ? '#10b981' : '#ef4444' }};"></i>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>Unggahan AD/ART</span>
                                    <i data-lucide="{{ $health_breakdown['has_ad_art'] ? 'check-circle-2' : 'x-circle' }}" style="width: 14px; color: {{ $health_breakdown['has_ad_art'] ? '#10b981' : '#ef4444' }};"></i>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>Unggahan SK Pengurus</span>
                                    <i data-lucide="{{ $health_breakdown['has_sk_pengurus'] ? 'check-circle-2' : 'x-circle' }}" style="width: 14px; color: {{ $health_breakdown['has_sk_pengurus'] ? '#10b981' : '#ef4444' }};"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Organisasi -->
                        <div style="margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px;">
                                    <i data-lucide="users" style="width: 16px; color: #8b5cf6;"></i> Organisasi & Struktur
                                </span>
                                <span style="font-size: 11px; color: #8b5cf6; font-weight: 700;">{{ $health_breakdown['score_organ'] }}/20 Poin</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 8px; padding-left: 24px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>Kelengkapan 4 Organ Utama</span>
                                    <i data-lucide="{{ $health_breakdown['has_lengkap_organ'] ? 'check-circle-2' : 'x-circle' }}" style="width: 14px; color: {{ $health_breakdown['has_lengkap_organ'] ? '#10b981' : '#ef4444' }};"></i>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>Memiliki Unit Usaha Terdaftar</span>
                                    <i data-lucide="{{ $health_breakdown['has_unit_usaha'] ? 'check-circle-2' : 'x-circle' }}" style="width: 14px; color: {{ $health_breakdown['has_unit_usaha'] ? '#10b981' : '#ef4444' }};"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Usaha -->
                        <div style="margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px;">
                                    <i data-lucide="package" style="width: 16px; color: #10b981;"></i> Produk & Katalog
                                </span>
                                <span style="font-size: 11px; color: #10b981; font-weight: 700;">{{ $health_breakdown['score_produk'] }}/25 Poin</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 8px; padding-left: 24px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>Minimal 5 Produk Aktif (Ada: {{ $health_breakdown['product_count'] }} produk)</span>
                                    <i data-lucide="{{ $health_breakdown['product_count'] >= 5 ? 'check-circle-2' : ($health_breakdown['product_count'] > 0 ? 'alert-circle' : 'x-circle') }}" style="width: 14px; color: {{ $health_breakdown['product_count'] >= 5 ? '#10b981' : ($health_breakdown['product_count'] > 0 ? '#f59e0b' : '#ef4444') }};"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Keuangan -->
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px;">
                                    <i data-lucide="wallet" style="width: 16px; color: #f59e0b;"></i> Keuangan & POS Kasir
                                </span>
                                <span style="font-size: 11px; color: #f59e0b; font-weight: 700;">{{ $health_breakdown['score_keuangan'] }}/25 Poin</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 8px; padding-left: 24px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: #94a3b8;">
                                    <span>Minimal 10 Transaksi POS (Ada: {{ $health_breakdown['trans_count'] }} transaksi)</span>
                                    <i data-lucide="{{ $health_breakdown['trans_count'] >= 10 ? 'check-circle-2' : ($health_breakdown['trans_count'] > 0 ? 'alert-circle' : 'x-circle') }}" style="width: 14px; color: {{ $health_breakdown['trans_count'] >= 10 ? '#10b981' : ($health_breakdown['trans_count'] > 0 ? '#f59e0b' : '#ef4444') }};"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize lucide icons inside blade component
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize Select2 with dynamic remote data & tag support
        if (typeof jQuery !== 'undefined' && jQuery().select2) {
            jQuery('.select2-penduduk').select2({
                tags: true,
                ajax: {
                    url: "{{ site_url('bumdes_profil/apipenduduk') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Ketik NIK / Nama Penduduk...',
                allowClear: true
            });
        }
    });
</script>
@endsection
