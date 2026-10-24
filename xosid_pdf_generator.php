<?php
// ======================================================================
// XOSID EXECUTIVE PDF GENERATOR
// Uses Spipu\Html2Pdf\Html2Pdf to create high-fidelity, premium PDF reports
// ======================================================================

// Ensure Composer autoloader is loaded for standalone execution on intercepted routes
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Spipu\Html2Pdf\Html2Pdf;

if (!function_exists('xosid_generate_analysis_pdf')) {
    function xosid_generate_analysis_pdf($data) {
        $subdomain = function_exists('xosid_get_tenant_subdomain') ? xosid_get_tenant_subdomain() : 'default';
        if ($subdomain === 'default') {
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $host_parts = explode(':', $host);
            $host_no_port = strtolower($host_parts[0]);
            if (preg_match('/^([a-z0-9-]+)\.(localhost|xosid\.id|127\.0\.0\.1)/', $host_no_port, $m)) {
                if (!in_array($m[1], ['www', 'master', 'api'])) {
                    $subdomain = $m[1];
                }
            }
        }
        $history_dir = __DIR__ . '/desa/upload/analisis_cerdas/' . ($subdomain !== 'default' ? $subdomain . '/' : '') . 'history/';
        if (!is_dir($history_dir)) {
            @mkdir($history_dir, 0755, true);
        }

        // Set name: analisis_desa_YYYYMMDD_HHMMSS.pdf
        $filename = 'analisis_desa_' . date('Ymd_His') . '.pdf';
        $dest_path = $history_dir . $filename;

        // Extract variables safely
        $demo  = $data['demografi'] ?? [];
        $idm   = $data['idm'] ?? [];
        $profil = $data['profil_desa'] ?? [];
        $masalah = $data['masalah'] ?? [];
        $peluang = $data['peluang'] ?? [];
        $pendanaan = $data['pendanaan'] ?? [];
        $skor_desa = $data['skor_desa'] ?? [];

        $nama_desa = !empty($profil['nama_desa']) ? $profil['nama_desa'] : 'Taman Asri';
        $nama_kec  = !empty($profil['nama_kecamatan']) ? $profil['nama_kecamatan'] : 'Purbolinggo';
        $nama_kab  = !empty($profil['nama_kabupaten']) ? $profil['nama_kabupaten'] : 'Kabupaten Lampung Timur';
        $nama_prov = !empty($profil['nama_provinsi']) ? $profil['nama_provinsi'] : 'Lampung';

        $skor_nilai = $idm['skor'] ?? 0;
        $status_label = $idm['status'] ?? 'N/A';
        $tahun = $idm['tahun'] ?? date('Y');

        $dimensi = $idm['dimensi'] ?? [];

        // Dynamic issues counts
        $kritisCount = count(array_filter($masalah, fn($m) => ($m['severity'] ?? '') === 'Kritis'));
        $tinggiCount = count(array_filter($masalah, fn($m) => ($m['severity'] ?? '') === 'Tinggi'));
        $sedangCount = count(array_filter($masalah, fn($m) => ($m['severity'] ?? '') === 'Sedang'));

        // Start HTML content
        // Standard table layout is critical for Html2Pdf compatibility!
        $html = '
        <style>
            page {
                background-color: #ffffff;
                color: #2d3748;
                font-family: Arial, sans-serif;
            }
            .header-table {
                width: 100%;
                border-bottom: 3px double #4a5568;
                padding-bottom: 12px;
                margin-bottom: 20px;
            }
            .logo-cell {
                width: 70px;
                text-align: center;
                vertical-align: middle;
            }
            .title-cell {
                padding-left: 15px;
                vertical-align: middle;
            }
            .title-main {
                font-size: 18px;
                font-weight: bold;
                color: #1a202c;
                text-transform: uppercase;
                margin: 0;
            }
            .title-sub {
                font-size: 11px;
                color: #718096;
                margin-top: 5px;
            }
            .meta-table {
                width: 100%;
                margin-bottom: 20px;
            }
            .meta-box {
                border: 1px solid #e2e8f0;
                background-color: #f7fafc;
                border-radius: 6px;
                padding: 12px;
            }
            .meta-label {
                font-size: 9px;
                color: #718096;
                text-transform: uppercase;
                font-weight: bold;
            }
            .meta-val {
                font-size: 16px;
                font-weight: bold;
                color: #2b6cb0;
            }
            .meta-status {
                font-size: 16px;
                font-weight: bold;
                color: #38a169;
            }
            .section-header {
                font-size: 13px;
                font-weight: bold;
                background-color: #edf2f7;
                border-left: 4px solid #3182ce;
                padding: 6px 10px;
                margin-top: 20px;
                margin-bottom: 10px;
                text-transform: uppercase;
            }
            .data-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 15px;
            }
            .data-table th {
                background-color: #f7fafc;
                border: 1px solid #e2e8f0;
                font-size: 10px;
                font-weight: bold;
                padding: 8px;
                text-align: left;
            }
            .data-table td {
                border: 1px solid #e2e8f0;
                font-size: 10px;
                padding: 8px;
                vertical-align: middle;
            }
            .issue-box {
                border: 1px solid #feb2b2;
                border-left: 4px solid #f56565;
                background-color: #fffaf0;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 4px;
            }
            .issue-title {
                font-size: 11px;
                font-weight: bold;
                color: #c53030;
                margin-bottom: 4px;
            }
            .issue-desc {
                font-size: 9.5px;
                color: #4a5568;
                line-height: 1.3;
            }
            .issue-rekom {
                font-size: 9px;
                color: #2b6cb0;
                margin-top: 6px;
                font-weight: bold;
            }
            .badge-kritis {
                background-color: #fff5f5;
                color: #e53e3e;
                border: 1px solid #fed7d7;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 9px;
                font-weight: bold;
            }
            .badge-tinggi {
                background-color: #fffaf0;
                color: #dd6b20;
                border: 1px solid #ffe3c6;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 9px;
                font-weight: bold;
            }
            .badge-sedang {
                background-color: #ebf8ff;
                color: #3182ce;
                border: 1px solid #bee3f8;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 9px;
                font-weight: bold;
            }
            .footer-notes {
                font-size: 8px;
                color: #a0aec0;
                text-align: center;
                margin-top: 30px;
                border-top: 1px solid #e2e8f0;
                padding-top: 8px;
            }
        </style>

        <page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm">
            <!-- Header -->
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <span style="font-size: 36px;">🏛️</span>
                    </td>
                    <td class="title-cell">
                        <div class="title-main">Laporan Analisis Cerdas Desa</div>
                        <div class="title-sub">
                            Pemerintah Desa ' . htmlspecialchars($nama_desa) . ' | Kec. ' . htmlspecialchars($nama_kec) . ' | Kab. ' . htmlspecialchars($nama_kab) . ' | Prov. ' . htmlspecialchars($nama_prov) . '
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Meta Ringkasan -->
            <table class="meta-table" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 32%; padding-right: 10px;">
                        <div class="meta-box">
                            <div class="meta-label">Skor Indeks Desa ' . $tahun . '</div>
                            <div class="meta-val">' . number_format($skor_nilai, 4) . '</div>
                        </div>
                    </td>
                    <td style="width: 32%; padding-right: 10px;">
                        <div class="meta-box">
                            <div class="meta-label">Status Kemandirian</div>
                            <div class="meta-status">' . htmlspecialchars($status_label) . '</div>
                        </div>
                    </td>
                    <td style="width: 36%;">
                        <div class="meta-box" style="background-color: #ebf8ff;">
                            <div class="meta-label" style="color: #2b6cb0;">Analisis Kerentanan</div>
                            <div style="font-size: 11px; font-weight: bold; color: #2b6cb0; margin-top: 5px;">
                                ' . $kritisCount . ' Kritis &bull; ' . $tinggiCount . ' Tinggi &bull; ' . $sedangCount . ' Sedang
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Dimensi Status Desa -->
            <div class="section-header">Pencapaian 6 Dimensi Indeks Desa (2025)</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Dimensi Indeks Desa</th>
                        <th style="width: 25%; text-align: center;">Skor Model</th>
                        <th style="width: 25%; text-align: center;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>🏥 Layanan Dasar</td>
                        <td style="text-align: center; font-weight: bold;">' . number_format($dimensi['layanan_dasar'] ?? 0, 3) . '</td>
                        <td style="text-align: center;">' . round(($dimensi['layanan_dasar'] ?? 0) * 100) . '%</td>
                    </tr>
                    <tr>
                        <td>🤝 Sosial</td>
                        <td style="text-align: center; font-weight: bold;">' . number_format($dimensi['sosial'] ?? 0, 3) . '</td>
                        <td style="text-align: center;">' . round(($dimensi['sosial'] ?? 0) * 100) . '%</td>
                    </tr>
                    <tr>
                        <td>💼 Ekonomi</td>
                        <td style="text-align: center; font-weight: bold;">' . number_format($dimensi['ekonomi'] ?? 0, 3) . '</td>
                        <td style="text-align: center;">' . round(($dimensi['ekonomi'] ?? 0) * 100) . '%</td>
                    </tr>
                    <tr>
                        <td>🌱 Lingkungan</td>
                        <td style="text-align: center; font-weight: bold;">' . number_format($dimensi['lingkungan'] ?? 0, 3) . '</td>
                        <td style="text-align: center;">' . round(($dimensi['lingkungan'] ?? 0) * 100) . '%</td>
                    </tr>
                    <tr>
                        <td>🚗 Aksesibilitas</td>
                        <td style="text-align: center; font-weight: bold;">' . number_format($dimensi['aksesibilitas'] ?? 0, 3) . '</td>
                        <td style="text-align: center;">' . round(($dimensi['aksesibilitas'] ?? 0) * 100) . '%</td>
                    </tr>
                    <tr>
                        <td>🏛️ Tata Kelola Pemerintahan Desa</td>
                        <td style="text-align: center; font-weight: bold;">' . number_format($dimensi['tata_kelola'] ?? 0, 3) . '</td>
                        <td style="text-align: center;">' . round(($dimensi['tata_kelola'] ?? 0) * 100) . '%</td>
                    </tr>
                </tbody>
            </table>

            <!-- Demografi Kependudukan -->
            <div class="section-header">Snapshot Demografi Kependudukan (Real-time)</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 33%;">Indikator Kependudukan</th>
                        <th style="width: 33%; text-align: right;">Jumlah Jiwa</th>
                        <th style="width: 34%; text-align: left;">Catatan / Rincian</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>👥 Total Penduduk</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($demo['total_penduduk'] ?? 0) . ' Jiwa</td>
                        <td>♂ ' . number_format($demo['laki_laki'] ?? 0) . ' Laki-laki | ♀ ' . number_format($demo['perempuan'] ?? 0) . ' Perempuan</td>
                    </tr>
                    <tr>
                        <td>🏠 Total Kepala Keluarga (KK)</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($demo['total_kk'] ?? 0) . ' KK</td>
                        <td>Rata-rata ' . ($demo['total_kk'] > 0 ? number_format(($demo['total_penduduk'] ?? 0)/$demo['total_kk'], 1) : 0) . ' Jiwa/KK</td>
                    </tr>
                    <tr>
                        <td>💼 Usia Produktif (15-64)</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($demo['usia_produktif'] ?? 0) . ' Jiwa</td>
                        <td>Potensi tenaga kerja aktif desa</td>
                    </tr>
                    <tr>
                        <td>⚠️ Tingkat Pengangguran</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($demo['pengangguran'] ?? 0) . ' Jiwa</td>
                        <td>' . ($demo['tingkat_pengangguran'] ?? 0) . '% dari angkatan kerja aktif</td>
                    </tr>
                    <tr>
                        <td>👶 Balita (0-4 Tahun)</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($demo['balita'] ?? 0) . ' Jiwa</td>
                        <td>Kelompok prioritas pemantauan stunting & gizi</td>
                    </tr>
                    <tr>
                        <td>👴 Kelompok Lansia (65+)</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($demo['lansia'] ?? 0) . ' Jiwa</td>
                        <td>Memerlukan jaminan sosial & perlindungan khusus</td>
                    </tr>
                    <tr>
                        <td>♿ Penyandang Disabilitas</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($demo['disabilitas'] ?? 0) . ' Jiwa</td>
                        <td>Kelompok rentan sasaran DTSEN</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="footer-notes">
                Dihasilkan oleh XO Intelligence Hub (XOSID) pada ' . date('d-m-Y H:i:s') . ' &bull; Halaman 1 dari 2
            </div>
        </page>

        <page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm">
            <!-- Header Halaman 2 -->
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <span style="font-size: 36px;">📋</span>
                    </td>
                    <td class="title-cell">
                        <div class="title-main">Isu Kritis & Rekomendasi Program</div>
                        <div class="title-sub">Pemerintah Desa ' . htmlspecialchars($nama_desa) . ' - Prioritas Pembangunan & Alokasi Dana</div>
                    </td>
                </tr>
            </table>

            <!-- Masalah & Isu Kritis -->
            <div class="section-header">Hasil Sintesis 9 Titik Analisis & Isu Desa</div>
            ';

        if (!empty($masalah)) {
            foreach (array_slice($masalah, 0, 4) as $isu) {
                $severity = $isu['severity'] ?? 'Sedang';
                $badgeClass = 'badge-sedang';
                if ($severity === 'Kritis') $badgeClass = 'badge-kritis';
                elseif ($severity === 'Tinggi') $badgeClass = 'badge-tinggi';

                $html .= '
                <div class="issue-box">
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td style="width: 75%; border: none;">
                                <div class="issue-title">' . htmlspecialchars($isu['judul']) . '</div>
                            </td>
                            <td style="width: 25%; text-align: right; border: none;">
                                <span class="' . $badgeClass . '">' . $severity . '</span>
                            </td>
                        </tr>
                    </table>
                    <div class="issue-desc">' . htmlspecialchars($isu['deskripsi']) . '</div>
                    <div class="issue-rekom">
                        Rekomendasi Utama: ' . htmlspecialchars($isu['rekomendasi'] ?? '') . ' (Est. Biaya: Rp ' . number_format($isu['estimasi_biaya'] ?? 0, 0, ',', '.') . ')
                    </div>
                </div>';
            }
        } else {
            $html .= '<p style="font-size: 10px; color: #718096; italic">Tidak ada isu kritis/tinggi terdeteksi dari database saat ini.</p>';
        }

        // Peluang & Investasi
        $html .= '
            <div class="section-header">Peluang Pengembangan Ekonomi & Investasi</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Sektor Potensi</th>
                        <th style="width: 45%;">Rincian Peluang</th>
                        <th style="width: 25%; text-align: center;">Estimasi Dampak</th>
                    </tr>
                </thead>
                <tbody>';

        if (!empty($peluang)) {
            foreach (array_slice($peluang, 0, 3) as $opp) {
                $html .= '
                <tr>
                    <td style="font-weight: bold;">' . htmlspecialchars($opp['judul']) . '</td>
                    <td>' . htmlspecialchars($opp['deskripsi']) . '</td>
                    <td style="text-align: center; color: #38a169; font-weight: bold;">' . htmlspecialchars($opp['estimasi_dampak'] ?? 'Tinggi') . '</td>
                </tr>';
            }
        } else {
            $html .= '
                <tr>
                    <td colspan="3" style="text-align: center; color: #718096;">Belum ada peluang investasi yang teridentifikasi secara formal.</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <!-- Alokasi Pendanaan -->
            <div class="section-header">Rekomendasi Peta Alokasi Pendanaan Program</div>
            <table class="data-table" style="margin-bottom: 25px;">
                <thead>
                    <tr>
                        <th style="width: 40%;">Sumber Pendanaan</th>
                        <th style="width: 35%; text-align: right;">Rekomendasi Porsi Anggaran</th>
                        <th style="width: 25%; text-align: center;">Status Kelayakan</th>
                    </tr>
                </thead>
                <tbody>';

        if (!empty($pendanaan)) {
            foreach (array_slice($pendanaan, 0, 4) as $fund) {
                $html .= '
                <tr>
                    <td style="font-weight: bold;">' . htmlspecialchars($fund['sumber']) . '</td>
                    <td style="text-align: right;">Rp ' . number_format($fund['porsi'], 0, ',', '.') . ' Jt</td>
                    <td style="text-align: center; color: #3182ce; font-weight: bold;">' . htmlspecialchars($fund['layak'] ?? 'Sangat Layak') . '</td>
                </tr>';
            }
        } else {
            $html .= '
                <tr>
                    <td colspan="3" style="text-align: center; color: #718096;">Peta pendanaan belum tersedia. Selesaikan kalkulasi terlebih dahulu.</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <!-- Footer & Disclaimer -->
            <div style="background-color: #fffaf0; border: 1px solid #feebc8; border-radius: 6px; padding: 12px; font-size: 8px; color: #dd6b20; line-height: 1.4; margin-top: 15px;">
                <strong>DISCLAIMER KEPATUHAN & AKURASI:</strong><br/>
                Laporan ini dibuat secara otomatis oleh XO Intelligence Hub (XOSID) menggunakan model analisis cerdas kependudukan terpadu dan integrasi data Indeks Desa 2025. Seluruh angka, persentase, dan estimasi biaya merupakan hasil simulasi model statistik kependudukan tingkat desa dan <strong>bukan merupakan penetapan status resmi atau keputusan anggaran final</strong> yang dikeluarkan oleh Kementerian Desa PDTT atau Pemerintah Kabupaten setempat. Dokumen ini dirancang khusus sebagai bahan pendukung pengambilan keputusan (Decision Support System) eksekutif bagi Kepala Desa dan perangkat pemerintahan desa.
            </div>

            <div class="footer-notes">
                Dihasilkan oleh XO Intelligence Hub (XOSID) pada ' . date('d-m-Y H:i:s') . ' &bull; Halaman 2 dari 2
            </div>
        </page>
        ';

        try {
            // Instantiate and build PDF
            $html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 15, 15, 15]);
            $html2pdf->writeHTML($html);
            $html2pdf->output($dest_path, 'F');
            return $dest_path;
        } catch (Exception $e) {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_pdf_error.log', "[" . date('Y-m-d H:i:s') . "] PDF Generation failed: " . $e->getMessage() . "\n", FILE_APPEND);
            return null;
        }
    }
}
