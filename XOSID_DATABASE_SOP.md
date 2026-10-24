# Standar Operasional Prosedur (SOP) Manajemen Database, Blueprint & Kemandirian Wilayah XOSID

SOP ini mengatur arsitektur multi-tenant XOSID, pemetaan database, pemulihan (restore) data dasar Bumi Daya, proteksi telemetri (anti-pantau), serta siklus pembaruan rilis sistem agar tidak merusak kustomisasi UI/UX dan struktur data yang telah dibangun.

---

## 1. Arsitektur & Pemetaan Database

Platform XOSID memisahkan peran database berdasarkan fungsinya:

| Domain / Host | Database Terhubung | Fungsi Utama | Keterangan Data |
|---|---|---|---|
| `master.localhost:8080`<br>`master.xosid.id` | **`xosid`** | Validasi kode rilis terbaru & Sinkronisasi Upstream. | Berisi data dinamis dari live demo (`berputar.opendesa.id` / saat ini Desa Bondo). |
| `demo.localhost:8080`<br>`demo.xosid.id` | **`xosid_tenant_demo`** | **Master Default xoSID (Template)**. Uji coba visual versi Supradesa. | Berisi data asli **Desa Bumi Daya (1801102013)** dengan Kades **Juang Seng** & Tema **Neural**. |
| `localhost:8080`<br>`xosid.id` | **`xosid`** | Landing Page & Portal Registrasi SaaS. | Berisi data pendaftaran, tagihan, dan keanggotaan. |
| `[tenant].localhost:8080`<br>`[tenant].xosid.id` | **`xosid_tenant_[tenant]`** | Website operasional riil masing-masing desa. | Kloning dari Blueprint Bumi Daya yang disuntikkan identitas riil desa tersebut. |

---

## 2. Kemandirian Data Wilayah & Proteksi Telemetri (Anti-Pantau)

Untuk memastikan platform XOSID berjalan mandiri tanpa ketergantungan dan tidak terpantau oleh ekosistem OpenSID/OpenDesa:

### A. Blokir Telemetri di Tingkat Jaringan (Docker)
Kita memetakan seluruh domain pelacakan OpenDesa ke localhost (`127.0.0.1`) di dalam kontainer Docker. Modifikasi pada `docker-compose.yml`:
```yaml
services:
  web:
    ...
    extra_hosts:
      - "pantau.opensid.or.id:127.0.0.1"
      - "pantau.opensid.my.id:127.0.0.1"
      - "layanan.opendesa.id:127.0.0.1"
      - "opendesa.id:127.0.0.1"
```
*Manfaat:* Menjamin 100% tidak ada paket data pelaporan yang keluar dari server, baik dari kode inti CodeIgniter maupun file terenkripsi ionCube.

### B. Blokir Telemetri di Tingkat PHP (Autoload)
Di berkas `xosid_emergency_autoload.php`, fungsi `config_item()` di-override agar seluruh konfigurasi pelacak dinonaktifkan secara paksa:
```php
$_config[0]['server_pantau'] = '';
$_config[0]['tracker_url'] = '';
$_config[0]['enable_track'] = false;
$_config[0]['tracker'] = false;
```
Guzzle Client juga di-mock agar langsung mengembalikan status `200 OK` tiruan untuk setiap permintaan ke server lisensi.

### C. Pangkalan Data Wilayah Mandiri (Netmon DB)
1. **Tabel Lokal:** Kita membuat tabel `xosid_netmon_wilayah` sebagai cache wilayah lokal menggunakan skrip `setup_netmon.php`.
2. **Alur Lookup:** API `/api/wilayah/ambildesa` mencari kode wilayah di tabel lokal `xosid_netmon_wilayah`. Jika tidak ada, ia mengambil data dari pangkalan data wilayah milik XOSID di `https://netmon.xosid.id` dan menyimpannya sebagai cache lokal. Proses ini sepenuhnya bebas dari pantau/telemetri OpenDesa.

---

## 3. Langkah Pemulihan Database Bumi Daya Asli

Jika database `demo.localhost:8080` (`xosid_tenant_demo`) perlu disegarkan, gunakan cadangan asli Anda yang tersimpan di `scratch/xosid_dump.sql`.

### Langkah Pemulihan (Restore):
1. Buat database `xosid_tenant_demo` (jika belum ada) dan impor data:
   ```bash
   docker exec -i xosid_neural_db mysql -uroot -ppassword -e "CREATE DATABASE IF NOT EXISTS xosid_tenant_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
   docker exec -i xosid_neural_db mysql -uroot -ppassword xosid_tenant_demo < scratch/xosid_dump.sql
   ```
2. Pastikan tema default disetel ke **`neural-fundamental`** di database tersebut.

---

## 4. Logika Baru: Konversi Blueprint (Template Default)

Fungsi convert di `/api/superadmin/smartbridge/convert` disesuaikan agar:
1. Membaca data langsung dari database **`xosid_tenant_demo`** (Bumi Daya asli Anda yang ter-update).
2. Mengekspor database tersebut ke berkas SQL blueprint **`xosid_neural_fully_restored_20260512.sql`**.

---

## 5. Alur Kerja Pemeliharaan Bulanan (Siklus Update Rilis)

Setiap awal bulan saat rilis pembaruan GitHub tersedia, ikuti tahapan berikut secara berurutan agar aman:

1. **Update Kode:** Jalankan pembaruan melalui menu *Pembaruan Sistem*.
2. **Migrasi Master:** Akses `master.localhost:8080` untuk menjalankan migrasi database master (`xosid`).
3. **Magic Move:** Sinkronisasi data demo terbaru dari `berputar.opendesa.id` ke `master.localhost:8080` (db `xosid`).
4. **Validasi Master:** Pastikan fitur berjalan normal di `master.localhost:8080` (Desa Bondo).
5. **Migrasi Tenant Demo:** Jalankan migrasi schema ke `xosid_tenant_demo` (Desa Bumi Daya) agar data Bumi Daya asli Anda mendapatkan pembaruan tabel rilis terbaru.
6. **Convert Blueprint:** Jalankan menu *Template dan Uji Coba*. Sistem mengekspor database `xosid_tenant_demo` (Bumi Daya ter-update) menjadi file `xosid_neural_fully_restored_20260512.sql`.
7. **Selesai:** Tenant baru otomatis dibuat dari blueprint Bumi Daya asli yang sudah mutakhir.
