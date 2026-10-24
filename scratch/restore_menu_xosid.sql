-- ============================================================
-- XOSID: Restore Custom Sidebar Menu Structure
-- Based on git history: commit 3a7a2e98 (snapshot before v2606)
-- Reconstructed from: scratch/update_menu_*.php scripts
-- ============================================================

SET @config_id = 1;

-- ============================================================
-- STEP 1: Rename Module ID 2 to 'Kependudukan'
-- ============================================================
UPDATE setting_modul SET modul = 'Kependudukan' WHERE id = 2;

-- ============================================================
-- STEP 2: Create/Update "LAYANAN WARGA" (parent=0, urut=35)
-- ============================================================
-- Check and insert if not exists
INSERT IGNORE INTO setting_modul (id, config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent)
SELECT 547, @config_id, 'Layanan Warga', 'layanan_warga', '#', 1, 'fa-users', 35, 1, 0, 'fa fa-users', 0
WHERE NOT EXISTS (SELECT 1 FROM setting_modul WHERE parent = 0 AND modul = 'Layanan Warga');

-- Get Layanan Warga ID
SET @layanan_warga_id = (SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Layanan Warga' LIMIT 1);

-- Move Kesehatan (ID 206) to Layanan Warga
UPDATE setting_modul SET parent = @layanan_warga_id, urut = 1 WHERE id = 206;

-- Move Bantuan (ID 6) to Layanan Warga
UPDATE setting_modul SET parent = @layanan_warga_id, urut = 2 WHERE id = 6;

-- Move and rename Pengaduan (ID 469) to Pengaduan Warga
UPDATE setting_modul SET parent = @layanan_warga_id, modul = 'Pengaduan Warga', urut = 3 WHERE id = 469;

-- Move and rename Hubung Warga (ID 10) to Kontak Warga
UPDATE setting_modul SET parent = @layanan_warga_id, modul = 'Kontak Warga', urut = 4 WHERE id = 10;

-- Move Layanan Mandiri (ID 14) to Layanan Warga
UPDATE setting_modul SET parent = @layanan_warga_id, urut = 5 WHERE id = 14;

-- Move Buku Tamu (ID 354) to Layanan Warga
UPDATE setting_modul SET parent = @layanan_warga_id, urut = 6 WHERE id = 354;

-- Move and rename XOSID Market (ID 459) to OLShop Warga under Layanan Warga
UPDATE setting_modul SET parent = @layanan_warga_id, modul = 'OLShop Warga', urut = 7 WHERE id = 459;

-- Rename submenus of Layanan Mandiri (ID 14)
UPDATE setting_modul SET modul = 'Pendaftaran Layanan' WHERE id = 387;
UPDATE setting_modul SET modul = 'Pendaftaran Gadget' WHERE id = 483;

-- ============================================================
-- STEP 3: Create/Update "DATA PENDUKUNG" (parent=0, urut=36)
-- ============================================================
INSERT IGNORE INTO setting_modul (id, config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent)
SELECT 548, @config_id, 'Data Pendukung', 'data_pendukung', '#', 1, 'fa-database', 36, 1, 0, 'fa fa-database', 0
WHERE NOT EXISTS (SELECT 1 FROM setting_modul WHERE parent = 0 AND modul = 'Data Pendukung');

-- Get Data Pendukung ID
SET @data_pendukung_id = (SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Data Pendukung' LIMIT 1);

-- Move Pemetaan (ID 9) under Data Pendukung
UPDATE setting_modul SET parent = @data_pendukung_id, urut = 1 WHERE id = 9;

-- Move Pembangunan (ID 442) under Data Pendukung
UPDATE setting_modul SET parent = @data_pendukung_id, urut = 2 WHERE id = 442;

-- Move Pertanahan (ID 7) under Data Pendukung
UPDATE setting_modul SET parent = @data_pendukung_id, urut = 3 WHERE id = 7;

-- Move and rename Analisis (ID 5) to 'Survey dan Analisa' under Data Pendukung
UPDATE setting_modul SET parent = @data_pendukung_id, modul = 'Survey dan Analisa', urut = 4 WHERE id = 5;

-- Move Neural Analytics (ID 3) to 'Data dan Statistik' under Data Pendukung
UPDATE setting_modul SET parent = @data_pendukung_id, modul = 'Data dan Statistik', urut = 5 WHERE id = 3;

-- ============================================================
-- STEP 4: Create/Update "PENGATURAN SISTEM" (parent=0, urut=37)
-- ============================================================
INSERT IGNORE INTO setting_modul (id, config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent)
SELECT 549, @config_id, 'Pengaturan Sistem', 'pengaturan_sistem', '#', 1, 'fa-cogs', 37, 1, 0, 'fa fa-cogs', 0
WHERE NOT EXISTS (SELECT 1 FROM setting_modul WHERE parent = 0 AND modul = 'Pengaturan Sistem');

-- Get Pengaturan Sistem ID
SET @pengaturan_sistem_id = (SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Pengaturan Sistem' LIMIT 1);

-- Move Pengaturan (ID 11) under Pengaturan Sistem
UPDATE setting_modul SET parent = @pengaturan_sistem_id, urut = 1 WHERE id = 11;

-- Move Admin Web (ID 13) under Pengaturan Sistem
UPDATE setting_modul SET parent = @pengaturan_sistem_id, urut = 2 WHERE id = 13;

-- Move Anjungan (ID 312) under Pengaturan Sistem
UPDATE setting_modul SET parent = @pengaturan_sistem_id, urut = 3 WHERE id = 312;

-- ============================================================
-- STEP 5: Reorganize "DIGITAL OFFICE" (ID 4)
-- ============================================================
-- Create Surat Warga sub-group under Digital Office (ID 4)
INSERT IGNORE INTO setting_modul (config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent)
SELECT @config_id, 'Surat Warga', 'surat_warga', '#', 1, 'fa-file-text', 1, 2, 0, 'fa fa-file-text', 4
WHERE NOT EXISTS (SELECT 1 FROM setting_modul WHERE parent = 4 AND modul = 'Surat Warga');

-- Get Surat Warga ID
SET @surat_warga_id = (SELECT id FROM setting_modul WHERE parent = 4 AND modul = 'Surat Warga' LIMIT 1);

-- Move Pengaturan Surat (369), Cetak Surat (370), Arsip Layanan (389), Permohonan Surat (426) to Surat Warga
UPDATE setting_modul SET parent = @surat_warga_id WHERE id IN (369, 370, 389, 426);
-- Also move 425 (Daftar Persyaratan) if exists
UPDATE setting_modul SET parent = @surat_warga_id WHERE id = 425;

-- Move Surat Dinas (ID 493) to Digital Office (parent=4)
UPDATE setting_modul SET parent = 4, urut = 2 WHERE id = 493;

-- Move Buku Administrasi (ID 301) to Digital Office (parent=4)
UPDATE setting_modul SET parent = 4, urut = 3 WHERE id = 301;

-- Move Kehadiran (ID 337) to Digital Office, rename to Digital Presensi
UPDATE setting_modul SET parent = 4, modul = 'Digital Presensi', urut = 4 WHERE id = 337;

-- Move and rename Sekretariat (ID 15) to Kesekretariatan under Digital Office
UPDATE setting_modul SET parent = 4, modul = 'Kesekretariatan', urut = 6 WHERE id = 15;

-- Hide duplicate Alasan Keluar (ID 371)
UPDATE setting_modul SET hidden = 1 WHERE id = 371;

-- ============================================================
-- STEP 6: Update "INTEGRASI DATA" (was Satu Data, ID 352)
-- ============================================================
UPDATE setting_modul SET modul = 'Integrasi Data', urut = 40 WHERE id = 352;

-- Move DTSEN (ID 512) under Integrasi Data
UPDATE setting_modul SET parent = 352, urut = 2 WHERE id = 512;

-- Set DTKS urut = 1
UPDATE setting_modul SET urut = 1 WHERE id = 484;

-- ============================================================
-- VERIFICATION: Show final top-level menu structure
-- ============================================================
SELECT id, modul, parent, urut, hidden 
FROM setting_modul 
WHERE parent = 0 AND hidden = 0 AND aktif = 1
ORDER BY urut ASC;
