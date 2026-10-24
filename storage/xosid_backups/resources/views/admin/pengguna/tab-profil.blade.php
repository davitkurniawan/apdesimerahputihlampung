<div class="tab-pane active" id="profil">
    {!! form_open_multipart($form_action, 'id="validasi"') !!}
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group neural-vertical-group">
                    <label><i data-lucide="user" style="width: 12px; margin-right: 5px;"></i> USERNAME</label>
                    <input class="form-control" type="text" value="{{ ci_auth()->username }}" disabled />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group neural-vertical-group">
                    <label><i data-lucide="mail" style="width: 12px; margin-right: 5px;"></i> ALAMAT EMAIL</label>
                    <div class="input-group">
                        <input class="form-control email" name="email" type="email" value="{{ ci_auth()->email }}" placeholder="contoh@desa.id" />
                        @if (ci_auth()->email_verified_at)
                            <span class="input-group-addon" style="background: rgba(34, 197, 94, 0.1) !important; color: #22c55e !important; border-color: rgba(34, 197, 94, 0.2) !important; font-size: 10px; font-weight: 800;">
                                <i data-lucide="check-circle" style="width: 14px;"></i> VERIFIED
                            </span>
                        @else
                            <span class="input-group-addon" style="background: rgba(244, 63, 94, 0.1) !important; color: #f43f5e !important; border-color: rgba(244, 63, 94, 0.2) !important; font-size: 10px; font-weight: 800; cursor: pointer;" title="Klik untuk verifikasi">
                                <i data-lucide="alert-circle" style="width: 14px;"></i> UNVERIFIED
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group neural-vertical-group">
            <label><i data-lucide="info" style="width: 12px; margin-right: 5px;"></i> NAMA LENGKAP</label>
            <input class="form-control required" type="text" name="nama" value="{{ ci_auth()->nama }}" placeholder="Masukkan nama lengkap" />
        </div>

        <div class="form-group neural-vertical-group">
            <label><i data-lucide="camera" style="width: 12px; margin-right: 5px;"></i> UNGGAH FOTO PROFIL</label>
            <div class="input-group">
                <input type="text" class="form-control" id="file_path" name="foto" placeholder="Pilih file gambar..." readonly>
                <input type="file" class="hidden" id="file" name="foto">
                <span class="input-group-btn">
                    <button type="button" class="btn-xosid btn-xosid-primary" id="file_browser" style="height: 48px; border-radius: 0 10px 10px 0;">
                        <i data-lucide="search" style="width: 14px;"></i> CARI FOTO
                    </button>
                </span>
            </div>
        </div>

        @if (setting('telegram_notifikasi'))
            <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 30px;">
                <div class="form-group neural-vertical-group">
                    <label><i data-lucide="bell" style="width: 12px; margin-right: 5px;"></i> NOTIFIKASI TELEGRAM</label>
                    <div style="display: flex; gap: 15px;">
                        <label class="neural-radio-card" style="flex: 1;">
                            <input type="radio" name="notif_telegram" value="1" {{ selected(ci_auth()->notif_telegram, 1) }} />
                            <div class="radio-content">
                                <i data-lucide="bell" style="width: 16px;"></i>
                                <span>AKTIFKAN</span>
                            </div>
                        </label>
                        <label class="neural-radio-card" style="flex: 1;">
                            <input type="radio" name="notif_telegram" value="0" {{ selected(ci_auth()->notif_telegram, 0) }} />
                            <div class="radio-content">
                                <i data-lucide="bell-off" style="width: 16px;"></i>
                                <span>MATIKAN</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="form-group neural-vertical-group {{ $userData->notif_telegram ? '' : 'hide' }}">
                    <label>USER ID TELEGRAM</label>
                    <input class="form-control bilangan" type="text" id="id_telegram" name="id_telegram" value="{{ ci_auth()->id_telegram }}" {{ jecho($list_setting->firstWhere('key', 'telegram_token')?->value == null, true, 'disabled') }} placeholder="Masukkan ID Telegram Anda" />
                    <small style="color: #64748b; margin-top: 8px; display: block; font-size: 11px;">Dapatkan ID melalui bot telegram <a href="https://t.me/XOSID_Bot" target="_blank" style="color: #06b6d4;">@XOSID_Bot</a></small>
                </div>
            </div>
        @endif
    </div>
    <div class="box-footer" style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 30px; display: flex; justify-content: space-between;">
        <button type="reset" class="btn-xosid btn-xosid-danger"><i class="fa fa-times"></i> BATALKAN</button>
        <button type="submit" class="btn-xosid btn-xosid-primary"><i class="fa fa-check"></i> SIMPAN PERUBAHAN</button>
    </div>
    </form>
</div>
