# XOSID SAAS & SUPRADESA STRATEGIC ROADMAP

## 🌐 Domain Architecture
1. **Pusat Pendaftaran & Layanan (Landing Page)**
   - URL: `https://xosid.id`
   - Fungsi: Onboarding desa baru, manajemen langganan, dan portal informasi utama.

2. **Node Desa (Multi-tenant Instances)**
   - Format URL: `https://[nama-desa].xosid.id`
   - Contoh: `bumidaya.xosid.id`, `tulusrejo.xosid.id`
   - Fungsi: Operasional harian desa, pelayanan publik mandiri, dan input data akar rumput.

3. **Pusat Agregasi & Intelijen (Supradesa Hub)**
   - URL: `https://supradesa.id`
   - Fungsi: Konsolidasi data dari seluruh node desa, dashboard eksekutif berjenjang (Kecamatan, Kabupaten, Provinsi, Nasional), dan analisis data makro.

## 🎨 UI/UX Standard: NEURAL-DARK
Semua node dalam ekosistem ini wajib menggunakan standar estetika "Neural-Dark" yang sedang dikembangkan:
- Glassmorphism architecture.
- Neon cyan & indigo accents.
- Real-time interaction hubs.
- AI-driven insights as the primary data presentation layer.

## ⚙️ Core Synchronization
Sinkronisasi data antar jenjang akan dikelola oleh engine `xosid_db_sync.php` yang menghubungkan setiap Node Desa ke Big Data Hub di `supradesa.id`.

---
*Catatan: Dokumen ini adalah panduan suci pengembangan sistem XOSID. Jangan dirubah tanpa koordinasi strategis.*
