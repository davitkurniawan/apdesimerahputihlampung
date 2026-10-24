# XOSID Update Report: 2606 → 2607
*Generated: 2026-07-01 20:17:19*

## Ringkasan

| Item | Jumlah |
|------|--------|
| File berubah (non-encrypted) | 1221 |
| File berubah di donjo-app (encrypted) |      361 |
| File BARU di 2607 | 67 |
| File DIHAPUS di 2607 | 185 |
| index.php upstream berubah? | ⚠️  YA — PERLU CEK MANUAL |

## Catatan Rilis
Rilis versi 2607.0.0 ini berisi teknis konversi opensid dari CI 3 ke Laravel secara incremental tahap pertama dan perbaikan lainnya yang diminta oleh komunitas SID.


### BUG

1. [#11262](https://github.com/OpenSID/OpenSID/issues/11262) Perbaikan form pengaduan warga menerima konten spam promosi.
2. [#11265](https://github.com/OpenSID/OpenSID/issues/11265) Perbaikan  kode daerah pada halaman formulir DTSEN tidak sesuai.
3. [#11267](https://github.com/OpenSID/OpenSID/issues/11267) Perbaikan cetak peta wilayah desa pada Identitas Desa tidak dapat digunakan.
4. [#11290](https://github.com/OpenSID/OpenSID/issues/11290) Perbaikan page tidak responsive setelah memasukkan kategori lembaga di form tambah lembaga.
5. [#11266](https://github.com/OpenSID/OpenSID/issues/11266) Perbaikan filter dusun dan tahun pada Statistik Akta Kematian tidak diteruskan ke Data Riwayat Mutasi Penduduk.
6. [#11296](https://github.com/OpenSID/OpenSID/issues/11296) Perbaikan error saat membuka menu DTSEN.
7. [#11264](https://github.com/OpenSID/OpenSID/issues/11264) Perbaikan beberapa masalah pada tema.
8. [#11297](https://github.com/OpenSID/OpenSID/issues/11297) Perbaikan logo pada halaman cetak PDF Sub Menu Rekap Catatan Harian yang tampil logo OpenSID.
9. [#11360](https://github.com/OpenSID/OpenSID/issues/11360) Perbaikan hasil unduh XLS Laporan Bulanan agar berisi border.
10. [#11345](https://github.com/OpenSID/OpenSID/issues/11345) Perbaikan pilihan opsi "bertemu" pada buku tamu tidak muncul sesuai yang diisi pada anjungan mandiri.
11. [#11329](https://github.com/OpenSID/OpenSID/issues/11329) Perbaikan template format_import_excel data penduduk untuk pekerjaan id nomor 5 yang masih PNS agar disesuaikan dengan aturan yang baru.
12. [#11311](https://github.com/OpenSID/OpenSID/issues/11311) Perbaikan kolom total saat cetak laporan kelompok rentan yang muncul di setiap halaman.
13. [#11310](https://github.com/OpenSID/OpenSID/issues/11310) Perbaikan tampilan print preview pada data suplemen yang amburadul dan tidak ada garis pada kolomnya.
14. [#11304](https://github.com/OpenSID/OpenSID/issues/11304) Perbaikan pilihan status kehadiran duplikat.
15. [#11303](https://github.com/OpenSID/OpenSID/issues/11303) Perbaikan tombol ekspor ke Excel di menu Stunting.
16. [#11302](https://github.com/OpenSID/OpenSID/issues/11302) Perbaikan kepemilikan Akta Kematian berdasarkan wilayah.
17. [#11372](https://github.com/OpenSID/OpenSID/issues/11372) Perbaikan tautan Facebook pada OpenSID yang masih mengarah ke grup yang lama.
18. [#11377](https://github.com/OpenSID/OpenSID/issues/11377) Perbaikan nama ketua umum pada form pendaftaran kerja sama desa di OpenSID agar sesuai yang terbaru.
19. [#6414](https://github.com/OpenSID/premium/issues/6414) Perbaikan impor template surat tidak bisa digunakan.
20. [#6412](https://github.com/OpenSID/premium/issues/6412) Perbaikan Modul Jabatan yang error 404.
21. [#6409](https://github.com/OpenSID/premium/issues/6409) Perbaikan tampilan halaman template surat yang jadi aneh dan berantakan setelah dikonversi.
22. [#6408](https://github.com/OpenSID/premium/issues/6408) Perbaikan tidak bisa membuat KK baru pada halaman Anggota Keluarga.
23. [#6407](https://github.com/OpenSID/premium/issues/6407) Perbaikan Modul Bagan Akses yang error 404.
24. [#6405](https://github.com/OpenSID/premium/issues/6405) Perbaikan impor template surat dinas tidak bisa digunakan.
25. [#6403](https://github.com/OpenSID/premium/issues/6403) Perbaikan error preview PDF cetak surat dinas.
26. [#6402](https://github.com/OpenSID/premium/issues/6402) Perbaikan gagal periksa permohonan surat.
27. [#6401](https://github.com/OpenSID/premium/issues/6401) Perbaikan gagal tambah pengguna baru layanan mandiri.
28. [#6400](https://github.com/OpenSID/premium/issues/6400) Perbaikan error ketika menjalankan atau mengakses script analisis.
29. [#6373](https://github.com/OpenSID/premium/pull/6373) Penyesuaian lampiran F2.01-a kelahiran anak.


### KEAMANAN

1. [#6354](https://github.com/OpenSID/premium/issues/6354) Perbaikan registrasi Buku Tamu yang menerima input spam dan percobaan SQL Injection.
2. [#6474](https://github.com/OpenSID/premium/issues/6474) Perbaikan kegagalan pemblokiran .htaccess yang mengakibatkan kebocoran kunci enkripsi (APP_KEY).

### TEKNIS

1. [#6353](https://github.com/OpenSID/premium/issues/6353) Penambahan status lisensi dan model distribusi pada repo OpenSID/rilis-premium.
2. [#6388](https://github.com/OpenSID/premium/issues/6388) Pengujian enkripsi OpenSID menggunakan IonCube.
3. [#6441](https://github.com/OpenSID/premium/issues/6441) Perbaikan error migrasi saat menjalankan semua migrasi.
4. [#6439](https://github.com/OpenSID/premium/issues/6439) Perbaikan PHPUnit crash akibat git ignore case-insensitive di Windows.

## ⚠️ File Kritis yang Berubah (Perlu Review)

### index.php upstream
⚠️  YA — PERLU CEK MANUAL

### Migration Baru (Perlu dijalankan di semua tenant DB)
```

```

### File Baru (Only in 2607)
```
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0: EULA.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0: TERMS.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app: Bootstrap
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands: ServeCommand.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events: CallingController.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events: ControllerCalled.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app: Helpers
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http: Controllers
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http: Kernel.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: Authenticate.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: AuthenticateSession.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: CodeIgniterResponseMiddleware.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: EncryptCookies.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: PreventRequestsDuringMaintenance.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: RedirectIfAuthenticated.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: TrimStrings.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: TrustHosts.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: TrustProxies.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: ValidateSignature.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: VerifyCsrfToken.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers: BroadcastServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers: RouteServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Rules: NotSpam.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services: DatabaseConfiguratorService.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services: SpamDetection
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: BantuanFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: DatabaseNotificationFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: DokumenHidupFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: FormatSuratFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: KelompokFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: KelompokMasterFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: KeluargaFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: LogSuratFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: PamongFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: PembangunanFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: PendudukFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: RtmFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: SettingAplikasiFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: SuratKeluarFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: SuratMasukFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: TanahDesaFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: TanahKasDesaFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: WilayahFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations: Migrasi_2026070171.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: bootCodeIgniter.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: cache
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: providers.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: routeCodeIgniter.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config: cors.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config: sanctum.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config: services.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources: css
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources: js
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0: routes
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/backup: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app: private
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/public: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/commons: scripts
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage: debugbar
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: cache
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: sessions
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: testing
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework/views: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage: logs
```

### File Dihapus (Only in 2606)
```
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: .env
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: .user.ini
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: .xosid_protected
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: Dockerfile
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: RELEASE_NOTES.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: XOSID_SAAS_ROADMAP.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: ajax_check_session.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: apache-config.conf
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands: ViewClearCommand.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app: Illuminate
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: ActivitylogServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: CarbonServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: ConsoleServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: DataTablesServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: OneTimePasswordsServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: TelegramNotificationServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: ZipStreamServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Auth: SessionGuard.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services: DataTables
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services: Laravel.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services: QueryDetector.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/css: xosid-core.css
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_avatar.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_bg_desa.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_bg_gunung.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_bg_pantai.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_login_bg.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_logo.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_office.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/lib/print/js: jquery.tools.min.js
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: brute_gallery.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_bumidaya.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config4.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config_id.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_db_lagi.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme_keys.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_tulusrejo.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_config_schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_session.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenant.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenant_cols.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenant_data.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenants.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_wates.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: composer-setup.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: composer.phar
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: config_output.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cookies.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: db_output.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: db_test.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: debug_hook.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: docker-compose.yml
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: download_template.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_ci_router.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_opensid_routes.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_opensid_routes_v3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_routes.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_session.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: gallery_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: gawai_test_raw.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: get_schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: inspect_routes_full.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: mandiri_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: menu_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: mock_license_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: original_index_temp.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: output.html
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: page.html
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: public
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: reflect_mandiri.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: register_my_kiosk.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/dpt: ajax_cetak_bersama.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/home: index.blade.php.bak
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/identitas_desa: view-profil.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/identitas_desa: view-umum.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin: katadesa_ai
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/penduduk: ajax_cetak_bersama.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/penduduk/modal: impor_penduduk.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/setting/info_sistem: log_aktivitas.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/status_desa: analisis_cerdas.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/web/menu: builder.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/web/menu: neural.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/web/widget: neural.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views: xosid
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: scratch
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: setup_netmon.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: simpan_test.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: simpan_test2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes: neural-fundamental
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira: package-lock.json
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: cookies_csrf2.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: cookies_csrf3.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/framework: installed
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: installed
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: invoices
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: uploads
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_call_controller.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_cat.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route4.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route_web.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route_web_v2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route_web_v3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_config.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_csrf.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_custom_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_db.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_db2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_db3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_cli.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_no_param.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_no_status.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_route.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_status_2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_form_load.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_gallery_custom_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_gallery_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_global.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_intercept.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_invoice.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_invoice_schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ip.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_laravel_helper.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_menu_tema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_methods.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_model.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_proxy_tmp.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_reflection.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_reflection_web.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_route.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_run_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_save.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_session_log.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_submit.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_tukar.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: uploads
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: web_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_aggressive_patch.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_ajax_db.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_ajax_wilayah.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_apply_update.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_billing_pdf.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_clear_cache.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_db_sync.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_emergency_autoload.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_fix_all.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_impor_penduduk.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_impor_penduduk.php.bak
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_intelligence_engine.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_member_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_midtrans_gateway.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_midtrans_webhook.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_netmon_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_neural_recovery.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_nuclear_boot.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_pdf_generator.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_pelanggan_mock.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_rebrand_engine.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_recalculate_endpoint.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_registrasi_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_saas_limiter.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_superadmin_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_supradesa_patch.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_totp.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_update_db.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_update_diff.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_update_report_2606.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_uploader.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_widget_tukar.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_wilayah_api.php
```

### File Non-Encrypted yang Berubah
```
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: .env
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/.gitattributes and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/.gitattributes differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/.gitignore and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/.gitignore differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: .user.ini
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: .xosid_protected
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: Dockerfile
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0: EULA.md
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/LICENSE and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/LICENSE differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Database/Migrations/2025_12_19_011545_create_akses_modul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Database/Migrations/2025_12_19_011545_create_akses_modul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Database/Seeders/AnalisisSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Database/Seeders/AnalisisSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Database/Seeders/ModulSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Database/Seeders/ModulSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Database/Seeders/SettingSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Database/Seeders/SettingSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Enums/AnalisisRefStateEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Enums/AnalisisRefStateEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Enums/AnalisisRefSubjekEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Enums/AnalisisRefSubjekEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Enums/AnalisisTipeIndikatorEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Enums/AnalisisTipeIndikatorEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisIndikatorController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisIndikatorController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisKategoriController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisKategoriController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisKlasifikasiController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisKlasifikasiController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisLaporanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisLaporanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisMasterController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisMasterController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisParameterController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisParameterController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisPeriodeController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisPeriodeController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisResponChildController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisResponChildController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisResponController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisResponController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Http/Controllers/AnalisisStatistikJawabanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Http/Controllers/AnalisisStatistikJawabanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Libraries/Analisis.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Libraries/Analisis.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Libraries/Bdt.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Libraries/Bdt.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Libraries/Gform.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Libraries/Gform.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Libraries/Import.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Libraries/Import.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisIndikator.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisIndikator.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisKategori.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisKategori.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisKlasifikasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisKlasifikasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisMaster.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisMaster.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisParameter.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisParameter.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisPeriode.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisPeriode.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisRespon.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisRespon.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisResponBukti.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisResponBukti.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Models/AnalisisResponHasil.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Models/AnalisisResponHasil.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Providers/AnalisisServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Providers/AnalisisServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/Routes/web.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/Routes/web.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Analisis/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Analisis/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Database/Migrations/2026_01_27_001122_create_akses_modul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Database/Migrations/2026_01_27_001122_create_akses_modul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Database/Seeders/AnjunganSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Database/Seeders/AnjunganSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Database/Seeders/MenuSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Database/Seeders/MenuSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Database/Seeders/ModulSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Database/Seeders/ModulSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Database/Seeders/SettingSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Database/Seeders/SettingSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganBaseController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganBaseController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganMenuController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganMenuController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganPengaturanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/BackEnd/AnjunganPengaturanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/AnjunganBerandaController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/AnjunganBerandaController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/AnjunganController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/AnjunganController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/AnjunganSuratController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/AnjunganSuratController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/PendudukGuestController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Http/Controllers/FrontEnd/PendudukGuestController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Models/Anjungan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Models/Anjungan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Models/AnjunganMenu.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Models/AnjunganMenu.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Providers/AnjunganServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Providers/AnjunganServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/Routes/web.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/Routes/web.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Anjungan/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Anjungan/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Migrations/2025_12_19_011545_create_akses_modul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Migrations/2025_12_19_011545_create_akses_modul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Migrations/2025_12_21_080511_create_buku_tamu_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Migrations/2025_12_21_080511_create_buku_tamu_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Migrations/2025_12_22_080511_create_buku_keperluan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Migrations/2025_12_22_080511_create_buku_keperluan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Migrations/2025_12_22_080511_create_buku_pertanyaan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Migrations/2025_12_22_080511_create_buku_pertanyaan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Migrations/2025_12_23_080511_create_buku_kepuasan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Migrations/2025_12_23_080511_create_buku_kepuasan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Seeders/BukuTamuSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Seeders/BukuTamuSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Seeders/ModulSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Seeders/ModulSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Database/Seeders/SettingSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Database/Seeders/SettingSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/AnjunganBaseController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/AnjunganBaseController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/KeperluanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/KeperluanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/KepuasanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/KepuasanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/PertanyaanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/PertanyaanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/TamuController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Controllers/BackEnd/TamuController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Controllers/FrontEnd/BukuTamuController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Controllers/FrontEnd/BukuTamuController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Requests/KeperluanRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Requests/KeperluanRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Requests/PertanyaanRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Requests/PertanyaanRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Http/Requests/TamuRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Http/Requests/TamuRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Models/KeperluanModel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Models/KeperluanModel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Models/KepuasanModel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Models/KepuasanModel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Models/PertanyaanModel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Models/PertanyaanModel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Models/TamuModel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Models/TamuModel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Providers/BukuTamuServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Providers/BukuTamuServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Routes/web.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Routes/web.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/Views/backend/tamu/form.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/Views/backend/tamu/form.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/BukuTamu/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/BukuTamu/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_012431_create_akses_modul_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_012431_create_akses_modul_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015241_create_dtsen_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015241_create_dtsen_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015242_create_dtsen_lampiran_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015242_create_dtsen_lampiran_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015243_create_dtsen_pengaturan_program_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015243_create_dtsen_pengaturan_program_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015244_create_dtsen_anggota_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015244_create_dtsen_anggota_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015245_create_dtsen_ref_lampiran_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_015245_create_dtsen_ref_lampiran_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_095154_update_dtsen_anggota_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_01_19_095154_update_dtsen_anggota_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_04_20_145000_create_dtsen_impor_desil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_04_20_145000_create_dtsen_impor_desil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Migrations/2026_04_29_111530_change_kd_penyakit_kronis_menahun_in_dtsen_anggota_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Migrations/2026_04_29_111530_change_kd_penyakit_kronis_menahun_in_dtsen_anggota_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Seeders/DTSENSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Seeders/DTSENSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Database/Seeders/SettingSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Database/Seeders/SettingSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Enums/DtsenEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Enums/DtsenEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Enums/DtsenSkorEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Enums/DtsenSkorEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Enums/Regsosek2022kEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Enums/Regsosek2022kEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Helpers/dtsen_helper.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Helpers/dtsen_helper.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Http/Controllers/BackEnd/ImporDesilController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Http/Controllers/BackEnd/ImporDesilController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Http/Controllers/BackEnd/LaporanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Http/Controllers/BackEnd/LaporanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Http/Controllers/BackEnd/PendataanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Http/Controllers/BackEnd/PendataanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Models/Dtsen.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Models/Dtsen.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Models/DtsenAnggota.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Models/DtsenAnggota.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Models/DtsenLampiran.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Models/DtsenLampiran.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Models/DtsenPengaturanProgram.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Models/DtsenPengaturanProgram.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Models/ImporDesil.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Models/ImporDesil.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Providers/DTSENServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Providers/DTSENServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Resources/views/backend/laporan/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Resources/views/backend/laporan/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Routes/web.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Routes/web.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Services/DTSENRegsosEk2022k.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Services/DTSENRegsosEk2022k.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/Services/DtsenService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/Services/DtsenService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/DTSEN/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/DTSEN/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_031434_create_akses_modul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_031434_create_akses_modul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045742_create_kehadiran_jam_kerja_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045742_create_kehadiran_jam_kerja_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045747_create_kehadiran_hari_libur_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045747_create_kehadiran_hari_libur_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045748_create_kehadiran_alasan_keluar_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045748_create_kehadiran_alasan_keluar_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045750_create_kehadiran_perangkat_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045750_create_kehadiran_perangkat_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045751_create_kehadiran_pengaduan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_20_045751_create_kehadiran_pengaduan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_28_100000_create_kehadiran_pengajuan_izin_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_28_100000_create_kehadiran_pengajuan_izin_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2025_12_28_120000_create_kehadiran_pengajuan_izin_detail_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2025_12_28_120000_create_kehadiran_pengajuan_izin_detail_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2026_04_13_000001_create_kehadiran_catatan_harian_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2026_04_13_000001_create_kehadiran_catatan_harian_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2026_04_13_000002_create_kehadiran_catatan_harian_foto_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2026_04_13_000002_create_kehadiran_catatan_harian_foto_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Migrations/2026_04_16_022526_add_jarak_kehadiran_to_setting_aplikasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Migrations/2026_04_16_022526_add_jarak_kehadiran_to_setting_aplikasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Seeders/JamKerjaSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Seeders/JamKerjaSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Seeders/KehadiranSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Seeders/KehadiranSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Seeders/ModulSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Seeders/ModulSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Database/Seeders/SettingSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Database/Seeders/SettingSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Enums/JenisIzin.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Enums/JenisIzin.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Enums/StatusApproval.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Enums/StatusApproval.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Enums/StatusCatatan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Enums/StatusCatatan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Exports/RekapCatatanExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Exports/RekapCatatanExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/AlasanKeluarController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/AlasanKeluarController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/ApprovalCatatanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/ApprovalCatatanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/CatatanHarianController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/CatatanHarianController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/HariLiburController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/HariLiburController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/JamKerjaController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/JamKerjaController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/PengaduanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/PengaduanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/PengajuanIzinController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/PengajuanIzinController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/PengajuanIzinPamongController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/PengajuanIzinPamongController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/RekapCatatanController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/RekapCatatanController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/RekapitulasiController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/BackEnd/RekapitulasiController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/FrontEnd/PerangkatController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/FrontEnd/PerangkatController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Controllers/FrontEnd/PublikController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Controllers/FrontEnd/PublikController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Requests/ApprovalCatatanRejectRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Requests/ApprovalCatatanRejectRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Http/Requests/CatatanHarianRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Http/Requests/CatatanHarianRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/AlasanKeluar.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/AlasanKeluar.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/CatatanHarian.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/CatatanHarian.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/CatatanHarianFoto.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/CatatanHarianFoto.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/HariLibur.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/HariLibur.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/JamKerja.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/JamKerja.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/Kehadiran.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/Kehadiran.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/KehadiranPengaduan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/KehadiranPengaduan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/PengajuanIzin.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/PengajuanIzin.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Models/PengajuanIzinDetail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Models/PengajuanIzinDetail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Providers/KehadiranServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Providers/KehadiranServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Routes/web.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Routes/web.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Services/ApprovalCatatanService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Services/ApprovalCatatanService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Services/CatatanHarianService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Services/CatatanHarianService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Services/RekapCatatanService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Services/RekapCatatanService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Traits/HasIndonesianDateFormat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Traits/HasIndonesianDateFormat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Views/backend/approval_catatan/detail.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Views/backend/approval_catatan/detail.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Views/backend/catatan_harian/form.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Views/backend/catatan_harian/form.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Views/backend/pengajuan_izin_pamong/form.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Views/backend/pengajuan_izin_pamong/form.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/Views/backend/rekap_catatan/cetak.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/Views/backend/rekap_catatan/cetak.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Kehadiran/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Kehadiran/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Config/shortcut.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Config/shortcut.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Migrations/2025_12_19_011545_create_akses_modul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Migrations/2025_12_19_011545_create_akses_modul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080511_create_pelapak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080511_create_pelapak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080511_create_produk_kategori_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080511_create_produk_kategori_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080511_create_produk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080511_create_produk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080512_modify_produk_foto_to_longtext.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Migrations/2025_12_22_080512_modify_produk_foto_to_longtext.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Migrations/2025_12_22_130511_modify_notification_buku_tamu_module.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Migrations/2025_12_22_130511_modify_notification_buku_tamu_module.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Seeders/LapakSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Seeders/LapakSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Seeders/ModulSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Seeders/ModulSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Database/Seeders/SettingSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Database/Seeders/SettingSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Http/Controllers/API/LapakController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Http/Controllers/API/LapakController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Http/Controllers/BackEnd/LapakAdminController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Http/Controllers/BackEnd/LapakAdminController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Http/Controllers/BackEnd/LapakKategoriAdminController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Http/Controllers/BackEnd/LapakKategoriAdminController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Http/Controllers/BackEnd/LapakPelapakAdminController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Http/Controllers/BackEnd/LapakPelapakAdminController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Http/Transformers/LapakKategoriTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Http/Transformers/LapakKategoriTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Http/Transformers/LapakProdukTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Http/Transformers/LapakProdukTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Models/Pelapak.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Models/Pelapak.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Models/Produk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Models/Produk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Models/ProdukKategori.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Models/ProdukKategori.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Providers/LapakServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Providers/LapakServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Repositories/LapakKategoriRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Repositories/LapakKategoriRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Repositories/LapakProdukRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Repositories/LapakProdukRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Routes/api.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Routes/api.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/Routes/web.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/Routes/web.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Lapak/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Lapak/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Http/Controllers/PelangganController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Http/Controllers/PelangganController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Http/Controllers/PendaftaranKerjasamaController.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Http/Controllers/PendaftaranKerjasamaController.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Providers/PelangganServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Providers/PelangganServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Routes/web.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Routes/web.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Services/CekService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Services/CekService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Services/PelangganService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Services/PelangganService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Views/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Views/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/Views/pendaftarankerjasama/template.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/Views/pendaftarankerjasama/template.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/Modules/Pelanggan/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/Modules/Pelanggan/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/README.md and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/README.md differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: RELEASE_NOTES.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0: TERMS.md
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/VERSION and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/VERSION differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: XOSID_SAAS_ROADMAP.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: ajax_check_session.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: apache-config.conf
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/GrupAkses/DefaultGrupAkses.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/GrupAkses/DefaultGrupAkses.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/GrupAkses/UpsertGrupAkses.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/GrupAkses/UpsertGrupAkses.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/Modul/ExportModul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/Modul/ExportModul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/Modul/ImportModul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/Modul/ImportModul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/Modul/UpsertModul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/Modul/UpsertModul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/Setting/ExportSetting.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/Setting/ExportSetting.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/Setting/ImportSetting.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/Setting/ImportSetting.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Actions/Theme/ActivateTheme.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Actions/Theme/ActivateTheme.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app: Bootstrap
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Casts/Path.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Casts/Path.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Casts/Sebutan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Casts/Sebutan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Casts/Zoom.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Casts/Zoom.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/AcakDataCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/AcakDataCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/DesaBaruCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/DesaBaruCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/ModuleCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/ModuleCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/BaseModuleMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/BaseModuleMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/CastMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/CastMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/ControllerMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/ControllerMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/EnumMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/EnumMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/HelperMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/HelperMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/MigrationMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/MigrationMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/ModelMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/ModelMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/ModuleMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/ModuleMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/ObserverMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/ObserverMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/RuleMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/RuleMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/ScopeMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/ScopeMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/SeedMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/SeedMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/ServiceMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/ServiceMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/Modules/TraitMakeCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/Modules/TraitMakeCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/PindaiTemaCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/PindaiTemaCommand.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands: ServeCommand.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/SetupCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/SetupCommand.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands/UpdateThemesCommand.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Commands/UpdateThemesCommand.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Commands: ViewClearCommand.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Console/Kernel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Console/Kernel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/AgamaEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/AgamaEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/AktifEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/AktifEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/AsalDanaEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/AsalDanaEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/AsalTanahKasEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/AsalTanahKasEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/AsuransiEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/AsuransiEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/BahasaEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/BahasaEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/BaseEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/BaseEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/BidangBelanjaEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/BidangBelanjaEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/CacatEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/CacatEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/CaraKBEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/CaraKBEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/DerajatSuratEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/DerajatSuratEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/DokumenEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/DokumenEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/Dtks/DtksEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/Dtks/DtksEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/Dtks/Regsosek2022kEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/Dtks/Regsosek2022kEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/FirebaseEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/FirebaseEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/FormatNoRtmEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/FormatNoRtmEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/GolonganDarahEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/GolonganDarahEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/HamilEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/HamilEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/HubunganRTMEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/HubunganRTMEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/ImageExtensionEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/ImageExtensionEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/InventarisMenuEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/InventarisMenuEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/InventarisSubMenuEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/InventarisSubMenuEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/JabatanKelompokEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/JabatanKelompokEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/JawabanKepuasanEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/JawabanKepuasanEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/JenisKelaminEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/JenisKelaminEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/JenisPeraturan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/JenisPeraturan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/KarakterSuratEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/KarakterSuratEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/KategoriDokumenPeraturanEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/KategoriDokumenPeraturanEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/KategoriPublicEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/KategoriPublicEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/KategoriPublik.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/KategoriPublik.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/KategoriPublikEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/KategoriPublikEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/KeluargaSejahteraEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/KeluargaSejahteraEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/KeuanganRefRek1Enum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/KeuanganRefRek1Enum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/ListSasaranEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/ListSasaranEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/OfflineModeEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/OfflineModeEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PekerjaanEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PekerjaanEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PendidikanKKEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PendidikanKKEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PendidikanSedangEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PendidikanSedangEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PendudukBidangEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PendudukBidangEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PendudukKursusEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PendudukKursusEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PeristiwaKeluargaEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PeristiwaKeluargaEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PeristiwaPendudukEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PeristiwaPendudukEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PerubahanDataPiEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PerubahanDataPiEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PeruntukanTanahKasEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PeruntukanTanahKasEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/PindahEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/PindahEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/ReferensiEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/ReferensiEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/SHDKEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/SHDKEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/SakitMenahunEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/SakitMenahunEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/SasaranEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/SasaranEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/SatuanWaktuEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/SatuanWaktuEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/SistemEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/SistemEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/Statistik/StatistikEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/Statistik/StatistikEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/Statistik/StatistikJenisBantuanEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/Statistik/StatistikJenisBantuanEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/Statistik/StatistikKeluargaEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/Statistik/StatistikKeluargaEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/Statistik/StatistikPendudukEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/Statistik/StatistikPendudukEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/Statistik/StatistikRtmEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/Statistik/StatistikRtmEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusDasarEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusDasarEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusDasarKKEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusDasarKKEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusKTPEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusKTPEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusKawinEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusKawinEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusKawinSpesifikEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusKawinSpesifikEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusKehidupanEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusKehidupanEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusPendudukEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusPendudukEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusPengaduanEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusPengaduanEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusRTMEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusRTMEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusRekamEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusRekamEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/StatusSuratKecamatanEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/StatusSuratKecamatanEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/SukuEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/SukuEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/SumberDanaEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/SumberDanaEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/TampilanArtikelEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/TampilanArtikelEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/TipeLinkEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/TipeLinkEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/UmurEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/UmurEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Enums/WargaNegaraEnum.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Enums/WargaNegaraEnum.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/BukuTamu/TamuSubmitted.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/BukuTamu/TamuSubmitted.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events: CallingController.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events: ControllerCalled.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Event.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Event.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/ExampleEvent.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/ExampleEvent.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Kehadiran/PengajuanIzinApprovalChanged.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Kehadiran/PengajuanIzinApprovalChanged.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Kehadiran/PengajuanIzinSubmitted.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Kehadiran/PengajuanIzinSubmitted.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Komentar/KomentarSubmitted.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Komentar/KomentarSubmitted.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Pengaduan/PengaduanSubmitted.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Pengaduan/PengaduanSubmitted.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Pesan/PesanMasukSubmitted.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Pesan/PesanMasukSubmitted.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Pesan/PesanOpenDKReceived.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Pesan/PesanOpenDKReceived.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Surat/PermohonanSuratSubmitted.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Surat/PermohonanSuratSubmitted.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/Surat/PermohonanSuratVerified.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/Surat/PermohonanSuratVerified.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Events/TooManyRequests.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Events/TooManyRequests.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exceptions/Handler.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exceptions/Handler.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/DokumentasiPembangunanOpendkExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/DokumentasiPembangunanOpendkExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/KlasifikasiSuratExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/KlasifikasiSuratExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/PembangunanOpendkExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/PembangunanOpendkExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/PendudukOpendkExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/PendudukOpendkExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/PesertaBantuanOpendkExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/PesertaBantuanOpendkExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/ProgramBantuanOpendkExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/ProgramBantuanOpendkExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/SuratDinasExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/SuratDinasExport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Exports/SuratLayananExport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Exports/SuratLayananExport.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app: Helpers
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http: Controllers
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http: Kernel.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: Authenticate.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: AuthenticateSession.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: CodeIgniterResponseMiddleware.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: EncryptCookies.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: PreventRequestsDuringMaintenance.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: RedirectIfAuthenticated.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Middleware/SecurityHeaders.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware/SecurityHeaders.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: TrimStrings.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: TrustHosts.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: TrustProxies.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: ValidateSignature.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Middleware: VerifyCsrfToken.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Requests/Grup/GrupImportRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Requests/Grup/GrupImportRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Requests/Kelompok/KelompokAnggotaRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Requests/Kelompok/KelompokAnggotaRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Requests/Lokasi/LokasiRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Requests/Lokasi/LokasiRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Requests/Map/MapLokasiRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Requests/Map/MapLokasiRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/AnalisisIndikatorTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/AnalisisIndikatorTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/AnalisisJumlahJawabanTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/AnalisisJumlahJawabanTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/AnalisisMasterTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/AnalisisMasterTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/ArtikelTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/ArtikelTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/BantuanPesertaTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/BantuanPesertaTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/DptTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/DptTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/GaleriTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/GaleriTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/IdmTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/IdmTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InformasiPublikTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InformasiPublikTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InventarisAssetTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InventarisAssetTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InventarisGedungTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InventarisGedungTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InventarisJalanTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InventarisJalanTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InventarisKontruksiTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InventarisKontruksiTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InventarisPeralatanTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InventarisPeralatanTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InventarisTanahTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InventarisTanahTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/InventarisTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/InventarisTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/KategoriProdukHukumTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/KategoriProdukHukumTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/KelompokAnggotaTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/KelompokAnggotaTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/KelompokTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/KelompokTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/LogSuratDinasVerifikasiTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/LogSuratDinasVerifikasiTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/LogSuratVerifikasiTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/LogSuratVerifikasiTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/PembangunanTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/PembangunanTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/PemerintahTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/PemerintahTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/PengaduanTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/PengaduanTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/PetaTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/PetaTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/ProdukHukumTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/ProdukHukumTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/SdgsTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/SdgsTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/StatistikTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/StatistikTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/StuntingTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/StuntingTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/SuplemenTerdataTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/SuplemenTerdataTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/SuplemenTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/SuplemenTransformer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Http/Transformers/WilayahTransformer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Http/Transformers/WilayahTransformer.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app: Illuminate
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Imports/BantuanImports.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Imports/BantuanImports.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Imports/KlasifikasiSuratImports.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Imports/KlasifikasiSuratImports.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Imports/SuratDinasImports.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Imports/SuratDinasImports.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Jobs/Job.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Jobs/Job.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Jobs/TestJob.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Jobs/TestJob.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Acak.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Acak.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/AnalisisImport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/AnalisisImport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/BIP/Bip.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/BIP/Bip.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/BIP/Bip2012.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/BIP/Bip2012.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/BIP/Bip2016.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/BIP/Bip2016.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/BIP/Bip2016Luwutimur.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/BIP/Bip2016Luwutimur.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/BIP/BipEktp.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/BIP/BipEktp.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/BIP/Siak.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/BIP/Siak.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Captcha.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Captcha.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Checker.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Checker.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Database.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Database.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/DateConv.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/DateConv.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Ekspor.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Ekspor.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/FeedParser.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/FeedParser.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/FeedReader.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/FeedReader.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/FlxZipArchive.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/FlxZipArchive.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Import.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Import.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/JobProses.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/JobProses.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Keuangan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Keuangan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/LogFormatter.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/LogFormatter.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/LogViewer.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/LogViewer.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/MyException.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/MyException.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/NoCaptcha.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/NoCaptcha.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/OTP/AbstractManager.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/OTP/AbstractManager.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/OTP/Interface/OtpInterface.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/OTP/Interface/OtpInterface.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/OTP/OtpManager.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/OTP/OtpManager.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/OTP/Repository/OtpEmail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/OTP/Repository/OtpEmail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/OTP/Repository/OtpTelegram.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/OTP/Repository/OtpTelegram.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Paging.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Paging.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Periksa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Periksa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Rekap.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Rekap.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Release.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Release.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Saas.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Saas.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Shortcode.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Shortcode.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/ShortcutModule.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/ShortcutModule.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Sinkronisasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Sinkronisasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Sistem.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Sistem.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/SpreadsheetExcelReader.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/SpreadsheetExcelReader.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Statistik.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Statistik.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Stunting.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Stunting.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/AtasNama.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/AtasNama.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/FakeDataIsian.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/FakeDataIsian.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianAnggotaKeluarga.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianAnggotaKeluarga.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianAritmatika.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianAritmatika.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianForm.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianForm.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianGambar.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianGambar.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianIdentitas.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianIdentitas.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianPasangan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianPasangan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianPenandaTangan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianPenandaTangan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianPenduduk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianPenduduk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianPendudukLuar.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianPendudukLuar.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianPeristiwa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianPeristiwa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE/KodeIsianWilayah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE/KodeIsianWilayah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/TinyMCE.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/TinyMCE.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/Tracker.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/Tracker.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Libraries/UserAgent.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Libraries/UserAgent.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/BukuTamu/SendTamuNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/BukuTamu/SendTamuNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/ExampleListener.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/ExampleListener.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/FailedAdminListener.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/FailedAdminListener.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Kehadiran/SendPengajuanIzinApprovalNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Kehadiran/SendPengajuanIzinApprovalNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Kehadiran/SendPengajuanIzinNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Kehadiran/SendPengajuanIzinNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Komentar/SendKomentarNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Komentar/SendKomentarNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/LockoutAdminListener.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/LockoutAdminListener.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/LoginAdminListener.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/LoginAdminListener.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/LoginPendudukListener.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/LoginPendudukListener.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/LoginPerangkatListener.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/LoginPerangkatListener.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/LogoutAdminListener.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/LogoutAdminListener.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Penduduk/SendEmailVerificationNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Penduduk/SendEmailVerificationNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Penduduk/SendTelegramVerificationNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Penduduk/SendTelegramVerificationNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Pengaduan/SendPengaduanNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Pengaduan/SendPengaduanNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Pesan/SendPesanMasukNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Pesan/SendPesanMasukNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Pesan/SendPesanOpenDKNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Pesan/SendPesanOpenDKNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Surat/SendPermohonanSuratNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Surat/SendPermohonanSuratNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Listeners/Surat/SendPermohonanSuratVerificationNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Listeners/Surat/SendPermohonanSuratVerificationNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Mail/GenericMail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Mail/GenericMail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Mail/MasaAktifAkunMail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Mail/MasaAktifAkunMail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Mail/NewPinMail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Mail/NewPinMail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Mail/OtpMail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Mail/OtpMail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Mail/VerificationSuccessMail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Mail/VerificationSuccessMail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Mail/VerifyMail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Mail/VerifyMail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Activity.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Activity.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Agama.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Agama.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Agenda.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Agenda.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/AliasKodeIsian.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/AliasKodeIsian.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Anak.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Anak.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/AnggotaGrup.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/AnggotaGrup.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Area.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Area.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/ArsipArtikel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/ArsipArtikel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Artikel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Artikel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/ArtikelWilayah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/ArtikelWilayah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Aset.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Aset.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Bahasa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Bahasa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Bantuan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Bantuan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/BantuanPeserta.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/BantuanPeserta.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/BaseModel.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/BaseModel.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Cacat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Cacat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Cdesa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Cdesa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/CdesaMutasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/CdesaMutasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/CdesaPenduduk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/CdesaPenduduk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/CovidPemudik.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/CovidPemudik.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/CovidVaksin.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/CovidVaksin.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/DaftarKontak.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/DaftarKontak.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/DatabaseNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/DatabaseNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/DisposisiSuratmasuk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/DisposisiSuratmasuk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Dokumen.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Dokumen.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/DokumenHidup.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/DokumenHidup.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Dtks.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Dtks.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/DtksAnggota.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/DtksAnggota.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/DtksLampiran.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/DtksLampiran.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/DtksPengaturanProgram.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/DtksPengaturanProgram.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Ekspedisi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Ekspedisi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/FcmToken.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/FcmToken.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/FcmTokenMandiri.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/FcmTokenMandiri.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/FormatSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/FormatSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Galery.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Galery.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Garis.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Garis.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Gawai.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Gawai.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/GisSimbol.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/GisSimbol.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/GolonganDarah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/GolonganDarah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/GrupAkses.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/GrupAkses.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/GrupKontak.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/GrupKontak.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/HubungWarga.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/HubungWarga.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/IbuHamil.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/IbuHamil.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Inbox.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Inbox.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/InventarisAsset.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/InventarisAsset.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/InventarisGedung.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/InventarisGedung.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/InventarisJalan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/InventarisJalan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/InventarisKontruksi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/InventarisKontruksi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/InventarisPeralatan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/InventarisPeralatan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/InventarisTanah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/InventarisTanah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KB.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KB.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KIA.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KIA.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KaderMasyarakat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KaderMasyarakat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Kategori.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Kategori.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KategoriWilayah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KategoriWilayah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KehadiranPamong.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KehadiranPamong.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KelasSosial.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KelasSosial.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Kelompok.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Kelompok.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KelompokAnggota.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KelompokAnggota.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KelompokMaster.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KelompokMaster.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Keluarga.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Keluarga.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KeluargaAktif.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KeluargaAktif.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Keuangan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Keuangan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KeuanganManualRefRek2.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KeuanganManualRefRek2.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KeuanganManualRefRek3.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KeuanganManualRefRek3.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KeuanganTemplate.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KeuanganTemplate.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/KlasifikasiSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/KlasifikasiSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Komentar.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Komentar.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LampiranSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LampiranSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LaporanSinkronisasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LaporanSinkronisasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Line.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Line.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogBackup.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogBackup.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogEkspor.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogEkspor.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogHapusPenduduk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogHapusPenduduk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogKeluarga.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogKeluarga.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogNotifikasiAdmin.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogNotifikasiAdmin.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogNotifikasiMandiri.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogNotifikasiMandiri.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogPenduduk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogPenduduk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogPerubahanPenduduk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogPerubahanPenduduk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogPerubahanSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogPerubahanSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogRestoreDesa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogRestoreDesa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogSinkronisasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogSinkronisasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogSuratDinas.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogSuratDinas.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogTolak.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogTolak.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/LogTte.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/LogTte.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Lokasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Lokasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MasterInventaris.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MasterInventaris.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MediaSosial.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MediaSosial.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Menu.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Menu.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Migrasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Migrasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Modul.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Modul.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MutasiCdesa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MutasiCdesa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MutasiInventarisAsset.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MutasiInventarisAsset.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MutasiInventarisGedung.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MutasiInventarisGedung.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MutasiInventarisJalan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MutasiInventarisJalan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MutasiInventarisPeralatan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MutasiInventarisPeralatan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/MutasiInventarisTanah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/MutasiInventarisTanah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Notifikasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Notifikasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/OneTimePassword.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/OneTimePassword.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/OtpToken.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/OtpToken.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Outbox.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Outbox.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pamong.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pamong.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Paud.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Paud.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pekerjaan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pekerjaan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pembangunan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pembangunan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PembangunanDokumentasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PembangunanDokumentasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pemilihan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pemilihan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pendapat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pendapat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pendidikan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pendidikan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendidikanKK.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendidikanKK.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Penduduk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Penduduk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendudukAsuransi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendudukAsuransi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendudukHidup.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendudukHidup.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendudukHubungan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendudukHubungan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendudukMandiri.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendudukMandiri.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendudukMap.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendudukMap.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendudukSaja.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendudukSaja.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PendudukStatus.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PendudukStatus.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pengaduan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pengaduan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PermohonanSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PermohonanSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Persil.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Persil.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Pesan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Pesan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PesanDetail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PesanDetail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/PesanMandiri.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/PesanMandiri.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Point.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Point.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Polygon.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Polygon.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Posyandu.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Posyandu.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/ProfilDesa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/ProfilDesa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefAsalTanahKas.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefAsalTanahKas.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefDokumen.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefDokumen.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefJabatan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefJabatan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefPendudukBidang.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefPendudukBidang.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefPendudukKursus.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefPendudukKursus.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefPeristiwa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefPeristiwa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefPersilKelas.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefPersilKelas.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefPersilMutasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefPersilMutasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefPeruntukanTanahKas.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefPeruntukanTanahKas.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RefPindah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RefPindah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RentangUmur.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RentangUmur.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Rtm.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Rtm.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/RtmHubungan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/RtmHubungan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SasaranPaud.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SasaranPaud.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SecurityBaseline.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SecurityBaseline.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SecurityReport.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SecurityReport.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SentItem.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SentItem.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Setting.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Setting.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SettingAplikasi.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SettingAplikasi.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Sex.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Sex.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Shortcut.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Shortcut.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Simbol.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Simbol.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SinergiProgram.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SinergiProgram.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Statistics.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Statistics.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/StatistikPengunjung.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/StatistikPengunjung.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/StatusDasar.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/StatusDasar.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/StatusKawin.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/StatusKawin.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/StatusKtp.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/StatusKtp.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Suplemen.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Suplemen.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SuplemenTerdata.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SuplemenTerdata.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SuratDinas.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SuratDinas.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SuratKeluar.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SuratKeluar.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SuratMasuk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SuratMasuk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/SyaratSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/SyaratSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/TanahDesa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/TanahDesa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/TanahKasDesa.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/TanahKasDesa.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/TeksBerjalan.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/TeksBerjalan.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Theme.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Theme.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Urls.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Urls.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/User.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/User.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/UserGrup.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/UserGrup.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/WargaNegara.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/WargaNegara.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Widget.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Widget.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Models/Wilayah.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Models/Wilayah.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Admin/OneTimePasswordNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Admin/OneTimePasswordNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Admin/ResetPasswordNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Admin/ResetPasswordNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Admin/VerifyEmailNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Admin/VerifyEmailNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/BaseNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/BaseNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/BukuTamu/TamuBaru.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/BukuTamu/TamuBaru.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/ExampleNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/ExampleNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Kehadiran/PengajuanIzinApproval.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Kehadiran/PengajuanIzinApproval.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Kehadiran/PengajuanIzinBaru.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Kehadiran/PengajuanIzinBaru.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Komentar/KomentarBaru.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Komentar/KomentarBaru.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Penduduk/PermohonanSuratNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Penduduk/PermohonanSuratNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Penduduk/ResetPasswordNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Penduduk/ResetPasswordNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Penduduk/VerifyNotification.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Penduduk/VerifyNotification.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Pengaduan/PengaduanBaru.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Pengaduan/PengaduanBaru.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Pesan/PesanMasuk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Pesan/PesanMasuk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Pesan/PesanOpenDK.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Pesan/PesanOpenDK.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Surat/PermohonanSuratBaru.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Surat/PermohonanSuratBaru.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Notifications/Surat/PermohonanSuratMasuk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Notifications/Surat/PermohonanSuratMasuk.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Observers/AuthorObserver.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Observers/AuthorObserver.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Observers/ClearCacheObserver.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Observers/ClearCacheObserver.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Observers/ConfigIdObserver.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Observers/ConfigIdObserver.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Observers/UuidObserver.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Observers/UuidObserver.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: ActivitylogServiceProvider.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers/AppServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers/AppServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers/AuthServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers/AuthServiceProvider.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers: BroadcastServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: CarbonServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: ConsoleServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: DataTablesServiceProvider.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers/EventServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers/EventServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers/JsonApiPaginateServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers/JsonApiPaginateServiceProvider.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers/NoCaptchaServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers/NoCaptchaServiceProvider.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: OneTimePasswordsServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers: RouteServiceProvider.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: TelegramNotificationServiceProvider.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers/ViewServiceProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Providers/ViewServiceProvider.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Providers: ZipStreamServiceProvider.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/AnalisisRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/AnalisisRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/ArtikelRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/ArtikelRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/BantuanPesertaRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/BantuanPesertaRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/DptRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/DptRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/GaleriRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/GaleriRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/InformasiPublikRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/InformasiPublikRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/InventarisAssetRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/InventarisAssetRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/InventarisGedungRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/InventarisGedungRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/InventarisJalanRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/InventarisJalanRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/InventarisKontruksiRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/InventarisKontruksiRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/InventarisPeralatanRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/InventarisPeralatanRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/InventarisTanahRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/InventarisTanahRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/KelompokRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/KelompokRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/LaporanPendudukRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/LaporanPendudukRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/LembagaRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/LembagaRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/LogSuratDinasRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/LogSuratDinasRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/LogSuratRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/LogSuratRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/PembangunanRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/PembangunanRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/PemerintahRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/PemerintahRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/PengaduanRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/PengaduanRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/PetaRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/PetaRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/ProdukHukumRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/ProdukHukumRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/SettingAplikasiRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/SettingAplikasiRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/StatistikRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/StatistikRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/StuntingRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/StuntingRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/SuplemenRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/SuplemenRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/SuplemenTerdataRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/SuplemenTerdataRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Repositories/WilayahRepository.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Repositories/WilayahRepository.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Rules/CaptchaRule.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Rules/CaptchaRule.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Rules: NotSpam.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Rules/SecretCodeRule.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Rules/SecretCodeRule.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Rules/SecureCloudUrl.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Rules/SecureCloudUrl.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Rules/Traits/ValidateCloudDomainTrait.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Rules/Traits/ValidateCloudDomainTrait.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Scopes/AccessWilayahScope.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Scopes/AccessWilayahScope.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Scopes/ConfigIdNullScope.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Scopes/ConfigIdNullScope.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Scopes/ConfigIdScope.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Scopes/ConfigIdScope.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Scopes/RemoveRtfScope.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Scopes/RemoveRtfScope.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Scopes/SafeSoftDeletingScope.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Scopes/SafeSoftDeletingScope.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Scopes/WilayahNullScope.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Scopes/WilayahNullScope.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/ArsipFisikSurat.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/ArsipFisikSurat.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Auth/PendudukMandiriProvider.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/Auth/PendudukMandiriProvider.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Auth: SessionGuard.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Auth/Traits/Authorizable.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/Auth/Traits/Authorizable.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Auth/Traits/LoginRequest.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/Auth/Traits/LoginRequest.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/DTKSRegsosEk2022k.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/DTKSRegsosEk2022k.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/DataEkspor.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/DataEkspor.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services: DataTables
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services: DatabaseConfiguratorService.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/DtksService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/DtksService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Feed.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/Feed.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/LaporanInventaris.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/LaporanInventaris.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/LaporanPenduduk.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/LaporanPenduduk.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services: Laravel.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/MasaAktifAkunService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/MasaAktifAkunService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/NotificationService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/NotificationService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/OtpService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/OtpService.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services: QueryDetector.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Security/FileIntegrityDefaultService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/Security/FileIntegrityDefaultService.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Services/Security/HeuristicDetectorDefaultService.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services/Security/HeuristicDetectorDefaultService.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Services: SpamDetection
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/Author.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/Author.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/Collation.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/Collation.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/ConfigId.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/ConfigId.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/ConfigIdNull.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/ConfigIdNull.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/Download.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/Download.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/GenerateRtf.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/GenerateRtf.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/Migration.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/Migration.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/Migrator.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/Migrator.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/ModulTrait.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/ModulTrait.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/ProvidesConvenienceMethods.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/ProvidesConvenienceMethods.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/SafeSoftDeletes.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/SafeSoftDeletes.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/SecureImageSanitizerTrait.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/SecureImageSanitizerTrait.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/ShortcutCache.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/ShortcutCache.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/StatusTrait.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/StatusTrait.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/Upload.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/Upload.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/UploadFotoUser.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/UploadFotoUser.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/Uuid.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/Uuid.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/Traits/WilayahNull.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/Traits/WilayahNull.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: BantuanFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: DatabaseNotificationFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: DokumenHidupFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: FormatSuratFactory.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/factories/Kehadiran/CatatanHarianFactory.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories/Kehadiran/CatatanHarianFactory.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: KelompokFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: KelompokMasterFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: KeluargaFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: LogSuratFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: PamongFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: PembangunanFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: PendudukFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: RtmFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: SettingAplikasiFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: SuratKeluarFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: SuratMasukFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: TanahDesaFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: TanahKasDesaFactory.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/factories: WilayahFactory.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_2026010171.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_2026010171.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_2026020171.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_2026020171.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_2026030171.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_2026030171.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_2026040171.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_2026040171.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_2026050171.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_2026050171.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_2026060171.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_2026060171.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations: Migrasi_2026070171.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_beta.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_beta.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_module.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_module.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_required.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_required.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/Migrasi_rev.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/Migrasi_rev.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_agenda_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_agenda_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_alias_kodeisian_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_alias_kodeisian_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_indikator_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_indikator_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_kategori_indikator_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_kategori_indikator_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_klasifikasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_klasifikasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_master_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_master_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_parameter_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_parameter_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_partisipasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_partisipasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_periode_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_periode_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_ref_state_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_ref_state_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_ref_subjek_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_ref_subjek_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_respon_bukti_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_respon_bukti_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_respon_hasil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_respon_hasil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_respon_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_respon_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_tipe_indikator_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_analisis_tipe_indikator_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_anggota_grup_kontak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_anggota_grup_kontak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_anjungan_menu_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_anjungan_menu_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_anjungan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_anjungan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_area_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_area_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_artikel_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_artikel_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_bulanan_anak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_bulanan_anak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_cdesa_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_cdesa_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_cdesa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_cdesa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_config_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_config_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_covid19_pantau_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_covid19_pantau_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_covid19_pemudik_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_covid19_pemudik_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_covid19_vaksin_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_covid19_vaksin_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_disposisi_surat_masuk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_disposisi_surat_masuk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_dokumen_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_dokumen_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_anggota_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_anggota_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_lampiran_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_lampiran_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_pengaturan_program_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_pengaturan_program_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_ref_lampiran_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_ref_lampiran_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_dtks_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_fcm_token_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_fcm_token_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_fcm_token_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_fcm_token_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_gambar_gallery_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_gambar_gallery_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_garis_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_garis_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_gis_simbol_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_gis_simbol_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_grup_akses_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_grup_akses_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_hubung_warga_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_hubung_warga_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ibu_hamil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ibu_hamil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_inbox_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_inbox_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_asset_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_asset_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_gedung_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_gedung_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_jalan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_jalan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_kontruksi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_kontruksi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_peralatan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_peralatan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_tanah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_inventaris_tanah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kader_pemberdayaan_masyarakat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kader_pemberdayaan_masyarakat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kategori_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kategori_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kelompok_anggota_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kelompok_anggota_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kelompok_master_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kelompok_master_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kelompok_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kelompok_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_manual_ref_rek1_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_manual_ref_rek1_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_manual_ref_rek2_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_manual_ref_rek2_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_manual_ref_rek3_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_manual_ref_rek3_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_template_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_keuangan_template_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kia_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kia_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_klasifikasi_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_klasifikasi_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_komentar_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_komentar_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kontak_grup_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kontak_grup_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_kontak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_kontak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_lampiran_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_lampiran_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_laporan_sinkronisasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_laporan_sinkronisasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_line_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_line_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_activity_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_activity_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_backup_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_backup_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_ekspor_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_ekspor_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_hapus_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_hapus_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_keluarga_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_keluarga_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_notifikasi_admin_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_notifikasi_admin_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_notifikasi_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_notifikasi_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_perubahan_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_perubahan_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_perubahan_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_perubahan_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_restore_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_restore_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_sinkronisasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_sinkronisasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_surat_dinas_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_surat_dinas_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_tolak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_tolak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_log_tte_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_log_tte_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_lokasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_lokasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_media_sosial_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_media_sosial_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_menu_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_menu_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_migrasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_migrasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_cdesa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_cdesa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_asset_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_asset_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_gedung_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_gedung_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_jalan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_jalan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_peralatan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_peralatan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_tanah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_mutasi_inventaris_tanah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_notifikasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_notifikasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_one_time_passwords_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_one_time_passwords_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_otp_token_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_otp_token_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_outbox_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_outbox_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_password_resets_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_password_resets_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pembangunan_ref_dokumentasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pembangunan_ref_dokumentasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pembangunan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pembangunan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pemilihan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pemilihan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pendapat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pendapat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pengaduan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pengaduan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_permohonan_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_permohonan_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_persil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_persil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pesan_detail_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pesan_detail_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pesan_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pesan_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_pesan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_pesan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_point_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_point_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_polygon_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_polygon_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_posyandu_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_posyandu_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_profil_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_profil_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_program_peserta_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_program_peserta_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_program_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_program_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_asal_tanah_kas_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_asal_tanah_kas_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_dokumen_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_dokumen_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_jabatan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_jabatan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_bahasa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_bahasa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_bidang_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_bidang_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_hamil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_hamil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_kursus_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_penduduk_kursus_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_peristiwa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_peristiwa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_persil_kelas_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_persil_kelas_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_persil_mutasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_persil_mutasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_peruntukan_tanah_kas_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_peruntukan_tanah_kas_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_pindah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_pindah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_sinkronisasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_sinkronisasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_status_covid_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_status_covid_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_syarat_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_ref_syarat_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_sasaran_paud_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_sasaran_paud_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_security_baselines_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_security_baselines_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_security_reports_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_security_reports_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_sentitems_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_sentitems_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_setting_aplikasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_setting_aplikasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_setting_modul_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_setting_modul_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_shortcut_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_shortcut_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_sinergi_program_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_sinergi_program_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_statistics_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_statistics_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_suplemen_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_suplemen_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_suplemen_terdata_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_suplemen_terdata_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_surat_dinas_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_surat_dinas_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_surat_keluar_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_surat_keluar_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_surat_masuk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_surat_masuk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_sys_traffic_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_sys_traffic_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tanah_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tanah_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tanah_kas_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tanah_kas_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_teks_berjalan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_teks_berjalan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_theme_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_theme_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_aset_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_aset_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_cacat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_cacat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_cara_kb_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_cara_kb_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_desa_pamong_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_desa_pamong_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_golongan_darah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_golongan_darah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_keluarga_sejahtera_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_keluarga_sejahtera_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_keluarga_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_keluarga_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_agama_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_agama_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_asuransi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_asuransi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_hubungan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_hubungan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_kawin_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_kawin_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_map_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_map_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_pekerjaan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_pekerjaan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_pendidikan_kk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_pendidikan_kk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_pendidikan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_pendidikan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_sex_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_sex_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_status_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_status_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_umur_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_umur_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_warganegara_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_penduduk_warganegara_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_rtm_hubungan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_rtm_hubungan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_rtm_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_rtm_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_sakit_menahun_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_sakit_menahun_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_status_dasar_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_status_dasar_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_status_ktp_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_status_ktp_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_surat_format_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_surat_format_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_wil_clusterdesa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_tweb_wil_clusterdesa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_urls_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_urls_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_user_grup_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_user_grup_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_user_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_user_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080511_create_widget_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080511_create_widget_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080512_create_dokumen_hidup_view.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080512_create_dokumen_hidup_view.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080512_create_keluarga_aktif_view.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080512_create_keluarga_aktif_view.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080512_create_master_inventaris_view.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080512_create_master_inventaris_view.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080512_create_penduduk_hidup_view.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080512_create_penduduk_hidup_view.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080512_create_rekap_mutasi_inventaris_view.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080512_create_rekap_mutasi_inventaris_view.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_agenda_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_agenda_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_alias_kodeisian_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_alias_kodeisian_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_indikator_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_indikator_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_kategori_indikator_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_kategori_indikator_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_klasifikasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_klasifikasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_master_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_master_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_parameter_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_parameter_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_partisipasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_partisipasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_periode_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_periode_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_respon_bukti_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_respon_bukti_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_respon_hasil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_respon_hasil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_respon_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_analisis_respon_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_anggota_grup_kontak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_anggota_grup_kontak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_anjungan_menu_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_anjungan_menu_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_anjungan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_anjungan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_area_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_area_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_artikel_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_artikel_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_bulanan_anak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_bulanan_anak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_cdesa_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_cdesa_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_cdesa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_cdesa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_covid19_pantau_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_covid19_pantau_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_covid19_pemudik_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_covid19_pemudik_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_covid19_vaksin_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_covid19_vaksin_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_disposisi_surat_masuk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_disposisi_surat_masuk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dokumen_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dokumen_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_anggota_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_anggota_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_lampiran_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_lampiran_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_pengaturan_program_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_pengaturan_program_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_ref_lampiran_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_ref_lampiran_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_dtks_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_fcm_token_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_fcm_token_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_fcm_token_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_fcm_token_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_gambar_gallery_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_gambar_gallery_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_garis_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_garis_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_gis_simbol_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_gis_simbol_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_grup_akses_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_grup_akses_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_hubung_warga_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_hubung_warga_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_ibu_hamil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_ibu_hamil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inbox_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inbox_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_asset_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_asset_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_gedung_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_gedung_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_jalan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_jalan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_kontruksi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_kontruksi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_peralatan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_peralatan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_tanah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_inventaris_tanah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kader_pemberdayaan_masyarakat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kader_pemberdayaan_masyarakat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kategori_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kategori_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kelompok_anggota_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kelompok_anggota_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kelompok_master_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kelompok_master_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kelompok_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kelompok_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_keuangan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_keuangan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kia_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kia_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_klasifikasi_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_klasifikasi_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_komentar_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_komentar_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kontak_grup_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kontak_grup_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kontak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_kontak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_lampiran_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_lampiran_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_laporan_sinkronisasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_laporan_sinkronisasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_line_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_line_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_activity_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_activity_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_backup_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_backup_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_hapus_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_hapus_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_keluarga_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_keluarga_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_notifikasi_admin_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_notifikasi_admin_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_notifikasi_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_notifikasi_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_perubahan_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_perubahan_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_perubahan_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_perubahan_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_restore_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_restore_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_sinkronisasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_sinkronisasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_surat_dinas_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_surat_dinas_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_tolak_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_tolak_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_tte_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_log_tte_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_lokasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_lokasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_media_sosial_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_media_sosial_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_menu_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_menu_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_migrasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_migrasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_cdesa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_cdesa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_asset_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_asset_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_gedung_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_gedung_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_jalan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_jalan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_peralatan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_peralatan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_tanah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_mutasi_inventaris_tanah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_notifikasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_notifikasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_one_time_passwords_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_one_time_passwords_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_otp_token_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_otp_token_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_outbox_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_outbox_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pembangunan_ref_dokumentasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pembangunan_ref_dokumentasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pembangunan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pembangunan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pemilihan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pemilihan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pendapat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pendapat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pengaduan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pengaduan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_permohonan_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_permohonan_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_persil_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_persil_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pesan_detail_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pesan_detail_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pesan_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pesan_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pesan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_pesan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_point_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_point_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_polygon_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_polygon_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_posyandu_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_posyandu_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_profil_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_profil_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_program_peserta_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_program_peserta_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_program_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_program_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_ref_jabatan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_ref_jabatan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_ref_syarat_surat_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_ref_syarat_surat_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_sasaran_paud_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_sasaran_paud_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_security_baselines_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_security_baselines_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_security_reports_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_security_reports_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_sentitems_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_sentitems_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_setting_aplikasi_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_setting_aplikasi_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_setting_modul_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_setting_modul_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_shortcut_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_shortcut_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_statistics_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_statistics_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_suplemen_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_suplemen_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_suplemen_terdata_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_suplemen_terdata_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_surat_dinas_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_surat_dinas_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_surat_keluar_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_surat_keluar_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_surat_masuk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_surat_masuk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_sys_traffic_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_sys_traffic_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tanah_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tanah_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tanah_kas_desa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tanah_kas_desa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_teks_berjalan_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_teks_berjalan_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_theme_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_theme_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_desa_pamong_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_desa_pamong_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_keluarga_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_keluarga_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_mandiri_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_mandiri_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_map_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_map_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_umur_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_penduduk_umur_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_rtm_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_rtm_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_surat_format_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_surat_format_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_wil_clusterdesa_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_tweb_wil_clusterdesa_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_urls_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_urls_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_user_grup_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_user_grup_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_user_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_user_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_widget_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2025_12_22_080514_add_foreign_keys_to_widget_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2026_05_20_000001_create_artikel_wilayah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2026_05_20_000001_create_artikel_wilayah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/migrations/install/2026_05_20_000002_create_kategori_wilayah_table.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/migrations/install/2026_05_20_000002_create_kategori_wilayah_table.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/ConfigSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/ConfigSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/DataDinamisSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/DataDinamisSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/DataStatisSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/DataStatisSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/DatabaseSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/DatabaseSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/Demo/PengaduanDemoSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/Demo/PengaduanDemoSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/GisSimbolSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/GisSimbolSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/KategoriSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/KategoriSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/KeuanganTemplateSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/KeuanganTemplateSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/KlasifikasiSuratSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/KlasifikasiSuratSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/MediaSosialSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/MediaSosialSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/NotifikasiSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/NotifikasiSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/RefJabatanSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/RefJabatanSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/RefSyaratSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/RefSyaratSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/RentangUmurSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/RentangUmurSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/ShortcutSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/ShortcutSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/StrukturAwalSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/StrukturAwalSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/Twebaset.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/Twebaset.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/UserGrupSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/UserGrupSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/UserSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/UserSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/app/database/seeders/WidgetSeeder.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/app/database/seeders/WidgetSeeder.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/artisan and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/artisan differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/css: xosid-core.css
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/front/js/mandiri.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/front/js/mandiri.js differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_avatar.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_bg_desa.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_bg_gunung.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_bg_pantai.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_login_bg.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_logo.png
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/images: xosid_office.png
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/apbdes_manual.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/apbdes_manual.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/custom-datetimepicker.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/custom-datetimepicker.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/main-camera.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/main-camera.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/CHANGELOG.md and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/CHANGELOG.md differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/models/dom/model.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/models/dom/model.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/package.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/package.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/accordion/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/accordion/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/advlist/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/advlist/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/anchor/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/anchor/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/autolink/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/autolink/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/autoresize/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/autoresize/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/autosave/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/autosave/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/charmap/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/charmap/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/code/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/code/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/codesample/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/codesample/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/directionality/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/directionality/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/emoticons/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/emoticons/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/fullscreen/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/fullscreen/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/help/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/help/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/importcss/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/importcss/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/insertdatetime/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/insertdatetime/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/laporan_keuangan/plugin.min.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/laporan_keuangan/plugin.min.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/link/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/link/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/lists/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/lists/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/media/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/media/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/media/plugin.min.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/media/plugin.min.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/nonbreaking/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/nonbreaking/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/pagebreak/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/pagebreak/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/preview/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/preview/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/quickbars/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/quickbars/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/save/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/save/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/searchreplace/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/searchreplace/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/table/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/table/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/visualblocks/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/visualblocks/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/visualchars/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/visualchars/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/plugins/wordcount/plugin.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/plugins/wordcount/plugin.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/themes/silver/theme.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/themes/silver/theme.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/tinymce.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/tinymce.js differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/js/tinymce-72/tinymce.min.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/assets/js/tinymce-72/tinymce.min.js differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/assets/lib/print/js: jquery.tools.min.js
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/bootstrap/app.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap/app.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: bootCodeIgniter.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: cache
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: providers.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/bootstrap: routeCodeIgniter.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: brute_gallery.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/catatan_rilis.md and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/catatan_rilis.md differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_bumidaya.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config4.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_config_id.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_db_lagi.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_theme_keys.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cek_tulusrejo.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_config_schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_session.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenant.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenant_cols.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenant_data.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_tenants.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: check_wates.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: composer-setup.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/composer.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/composer.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/composer.lock and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/composer.lock differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: composer.phar
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/activitylog.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/activitylog.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/app.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/app.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/auth.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/auth.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/broadcasting.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/broadcasting.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/cache.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/cache.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config: cors.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/database.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/database.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/datatables.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/datatables.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/filesystems.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/filesystems.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/fractal.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/fractal.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/hashing.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/hashing.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/json-api-paginate.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/json-api-paginate.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/logging.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/logging.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/mail.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/mail.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/notifications.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/notifications.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/one-time-passwords.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/one-time-passwords.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/queue.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/queue.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config: sanctum.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/security.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/security.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config: services.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/session.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/session.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/user_agents.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/user_agents.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/view.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/view.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/config/zipstream.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/config/zipstream.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: config_output.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: cookies.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: db_output.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: db_test.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: debug_hook.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: docker-compose.yml
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/donjo-app: hooks
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: download_template.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_ci_router.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_opensid_routes.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_opensid_routes_v3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_routes.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: dump_session.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: gallery_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: gawai_test_raw.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: get_schema.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/htaccess.apache.txt and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/htaccess.apache.txt differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/index.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/index.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: inspect_routes_full.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: mandiri_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: menu_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: mock_license_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: original_index_temp.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: output.html
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: page.html
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: public
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: reflect_mandiri.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: register_my_kiosk.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources: css
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources: js
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/bumindes/pembangunan/kader/form.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/bumindes/pembangunan/kader/form.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/dpt: ajax_cetak_bersama.blade.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/dpt/dpt_cetak.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/dpt/dpt_cetak.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/home: index.blade.php.bak
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/home/rilis.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/home/rilis.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/identitas_desa: view-profil.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/identitas_desa: view-umum.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin: katadesa_ai
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/kelompok/form.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/kelompok/form.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/laporan/rentan/cetak.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/laporan/rentan/cetak.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/laporan/tabel_bulanan.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/laporan/tabel_bulanan.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/layouts/components/notifikasi.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/layouts/components/notifikasi.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/layouts/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/layouts/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/layouts/partials/header.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/layouts/partials/header.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/layouts/partials/sidebar.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/layouts/partials/sidebar.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/layouts/print_layout.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/layouts/print_layout.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/notifikasi/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/notifikasi/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/opendk/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/opendk/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/pendapat/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/pendapat/index.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/penduduk: ajax_cetak_bersama.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/penduduk/modal: impor_penduduk.blade.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/penduduk_log/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/penduduk_log/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/pengaturan_surat/asset_tinymce.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/pengaturan_surat/asset_tinymce.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/setting/info_sistem/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/setting/info_sistem/index.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/setting/info_sistem: log_aktivitas.blade.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/statistik/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/statistik/index.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/status_desa: analisis_cerdas.blade.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/stunting/pemantauan_anak.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/stunting/pemantauan_anak.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/stunting/pemantauan_ibu_hamil.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/stunting/pemantauan_ibu_hamil.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/stunting/pemantauan_paud.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/stunting/pemantauan_paud.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/suplemen/cetak.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/suplemen/cetak.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/theme/components/form/input-password.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/theme/components/form/input-password.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/theme/components/form/textarea.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/theme/components/form/textarea.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/theme/pengaturan.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/theme/pengaturan.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/web/menu: builder.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/web/menu: neural.blade.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/web/widget: neural.blade.php
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/wilayah/maps_kantor.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/wilayah/maps_kantor.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/admin/wilayah/maps_wilayah.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/admin/wilayah/maps_wilayah.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views/layanan_mandiri/layouts/components/notifikasi.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/resources/views/layanan_mandiri/layouts/components/notifikasi.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/resources/views: xosid
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/UploadHandler.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/UploadHandler.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/UploadHandlerAsli.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/UploadHandlerAsli.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/ajax_calls.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/ajax_calls.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/config/config.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/config/config.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/dialog.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/dialog.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/execute.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/execute.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/force_download.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/force_download.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/include/utils.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/include/utils.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/init.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/init.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/rfm/upload.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/rfm/upload.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0: routes
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: scratch
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: setup_netmon.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: simpan_test.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: simpan_test2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/backup: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app: private
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/public: .gitignore
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/template/impor/format-impor-excel.xlsm and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/template/impor/format-impor-excel.xlsm differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/template/lampiran/f-2.01-a/view.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/template/lampiran/f-2.01-a/view.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/esensi/resources/views/commons/mobile_menu.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/esensi/resources/views/commons/mobile_menu.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/esensi/resources/views/partials/vaksin/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/esensi/resources/views/partials/vaksin/index.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes: neural-fundamental
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/palanta/resources/views/commons/mobile_menu.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/palanta/resources/views/commons/mobile_menu.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/seruit-lite/resources/views/commons/header.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/seruit-lite/resources/views/commons/header.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/seruit-lite/resources/views/template.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/seruit-lite/resources/views/template.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira: .gitignore
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/assets/js/license.js and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/assets/js/license.js differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/assets: vendor
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/config.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/config.json differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira: package-lock.json
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/package.json and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/package.json differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/commons/ai_chat.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/commons/ai_chat.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/commons/meta.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/commons/meta.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/commons/pagination.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/commons/pagination.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/commons: scripts
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/commons/source_css.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/commons/source_css.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/commons/source_js.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/commons/source_js.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/articles.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/articles.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/footer.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/footer.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/galeri/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/galeri/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/header.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/header.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/hero.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/hero.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/hero_klasik.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/hero_klasik.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/lapak/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/lapak/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/location.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/location.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/pengaduan/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/pengaduan/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/partials/peta/index.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/partials/peta/index.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/template.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/template.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/widgets/galeri.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/widgets/galeri.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/widgets/keuangan.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/widgets/keuangan.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/widgets/komentar.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/widgets/komentar.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/widgets/menu_kategori.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/widgets/menu_kategori.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/widgets/peta_lokasi_kantor.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/widgets/peta_lokasi_kantor.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/widgets/peta_wilayah_desa.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/widgets/peta_wilayah_desa.blade.php differ
Files /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/app/themes/wira/resources/views/widgets/sinergi_program.blade.php and /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/themes/wira/resources/views/widgets/sinergi_program.blade.php differ
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/app/vendor: opensid
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: cookies_csrf2.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: cookies_csrf3.txt
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage: debugbar
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: cache
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage/framework: installed
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: sessions
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework: testing
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage/framework/views: .gitignore
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: installed
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: invoices
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/storage: logs
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/storage: uploads
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_call_controller.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_cat.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route4.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route_web.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route_web_v2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ci_route_web_v3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_config.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_csrf.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_custom_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_db.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_db2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_db3.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_cli.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_no_param.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_no_status.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_route.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_dt_status_2.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_form_load.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_gallery_custom_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_gallery_dt.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_global.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_intercept.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_invoice.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_invoice_schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_ip.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_laravel_helper.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_menu_tema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_methods.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_model.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_proxy_tmp.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_reflection.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_reflection_web.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_route.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_run_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_save.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_schema.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_session_log.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_submit.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: test_tukar.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: uploads
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: clue
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: ergebnis
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: evenement
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: fakerphp
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: fidry
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: friendsofphp
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/vendor: graham-campbell
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: hamcrest
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: illuminate
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: intervention
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: justinrainbow
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: localheinz
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: marc-mabe
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: mockery
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: myclabs
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: paragonie
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: phar-io
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/vendor: phpoption
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: phpseclib
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: phpstan
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: phpunit
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/vendor: psy
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: react
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: rector
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: sebastian
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: setasign
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0/vendor: theseer
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/vendor: tinymce
Only in /Users/kampungjagoan/Downloads/rilis-premium-2607.0.0/vendor: vlucas
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: web_datatables.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_aggressive_patch.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_ajax_db.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_ajax_wilayah.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_apply_update.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_billing_pdf.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_clear_cache.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_db_sync.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_emergency_autoload.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_fix_all.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_impor_penduduk.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_impor_penduduk.php.bak
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_intelligence_engine.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_member_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_midtrans_gateway.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_midtrans_webhook.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_netmon_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_neural_recovery.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_nuclear_boot.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_pdf_generator.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_pelanggan_mock.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_rebrand_engine.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_recalculate_endpoint.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_registrasi_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_saas_limiter.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_superadmin_api.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_supradesa_patch.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_totp.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_update_db.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_update_diff.sh
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_update_report_2606.md
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_uploader.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_widget_tukar.php
Only in /Users/kampungjagoan/Downloads/rilis-premium-2606.0.0: xosid_wilayah_api.php
```

## Checklist Update

- [ ] Baca catatan rilis
- [ ] Cek laporan ini
- [ ] Backup XOSID sebelum update: `git add . && git commit -m "backup sebelum update 2607"`
- [ ] Jalankan rsync update: `./xosid_apply_update.sh 2607`
- [ ] Jalankan migration di tenant: `./xosid_run_migrations.sh`
- [ ] Test di staging (bumidaya)
- [ ] Perbarui baseline SQL: `./xosid_refresh_baseline.sh`
- [ ] Commit: `git commit -m "feat: update upstream ke 2607.0.0"`
