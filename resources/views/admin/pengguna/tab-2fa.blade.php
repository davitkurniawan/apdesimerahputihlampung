<div class="tab-pane" id="2fa">
    <div class="neural-info-box" style="margin-bottom: 30px;">
        <div class="info-icon" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4;">
            <i data-lucide="shield-check"></i>
        </div>
        <div class="info-content">
            <h4 style="margin: 0 0 5px; font-weight: 800; color: white; letter-spacing: 1px;">AUTENTIKASI DUA FAKTOR (2FA)</h4>
            <p style="margin: 0; font-size: 13px; color: #94a3b8; line-height: 1.6;">Lindungi akun Anda dengan lapisan keamanan ekstra. Saat aktif, sistem akan memverifikasi identitas Anda melalui metode sekunder setiap kali melakukan login.</p>
        </div>
    </div>

    {!! form_open_multipart(ci_route('pengguna.update_keamanan'), 'id="validasi"') !!}
    <div class="box-body">
        <div class="form-group neural-vertical-group">
            <label><i data-lucide="settings" style="width: 12px; margin-right: 5px;"></i> KONFIGURASI KEAMANAN</label>
            <div style="display: flex; gap: 15px;">
                <label class="neural-radio-card" style="flex: 1;">
                    <input type="radio" name="two_factor_enabled" value="1" @checked($userData['two_factor_enabled'] == '1')>
                    <div class="radio-content">
                        <i data-lucide="lock" style="width: 16px;"></i>
                        <span>AKTIFKAN 2FA</span>
                    </div>
                </label>
                <label class="neural-radio-card" style="flex: 1;">
                    <input type="radio" name="two_factor_enabled" value="0" @checked($userData['two_factor_enabled'] != '1')>
                    <div class="radio-content">
                        <i data-lucide="unlock" style="width: 16px;"></i>
                        <span>NON-AKTIF</span>
                    </div>
                </label>
            </div>
        </div>
    </div>
    <div class="box-footer" style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 30px; display: flex; justify-content: space-between;">
        <button type="reset" class="btn-xosid btn-xosid-danger"><i class="fa fa-times"></i> BATALKAN</button>
        <button type="submit" class="btn-xosid btn-xosid-primary"><i class="fa fa-check"></i> SIMPAN PENGATURAN</button>
    </div>
    {!! form_close() !!}
</div>
