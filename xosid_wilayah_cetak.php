<?php
/**
 * XOSID Wilayah Cetak - Standalone Print Page
 * Direct DB bypass for wilayah data printing.
 */

require_once __DIR__ . '/xosid_ajax_db.php';

$pamong_ttd_id     = intval($_GET['pamong_ttd'] ?? $_POST['pamong_ttd'] ?? 0);
$pamong_ketahui_id = intval($_GET['pamong_ketahui'] ?? $_POST['pamong_ketahui'] ?? 0);
$sensor_nik        = intval($_GET['sensor_nik'] ?? 0);
$format            = $_GET['format'] ?? 'html';

if ($format === 'xlsx') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_wilayah_" . date('Y-m-d') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}

// Helper
function db_setting($conn, $key) {
    $k = $conn->real_escape_string($key);
    $r = $conn->query("SELECT value FROM config WHERE name = '$k' LIMIT 1");
    if ($r && $row = $r->fetch_assoc()) return $row['value'];
    return '';
}

function get_pamong($conn, $id) {
    $id = intval($id);
    if (!$id) return ['pamong_nama' => '-', 'pamong_jabatan' => '-', 'pamong_nip' => '', 'pamong_niap' => ''];
    $r = $conn->query("
        SELECT p.pamong_nama, COALESCE(rj.nama, '') AS pamong_jabatan,
               p.pamong_nip, p.pamong_niap
        FROM tweb_desa_pamong p
        LEFT JOIN ref_jabatan rj ON p.jabatan_id = rj.id
        WHERE p.pamong_id = $id AND p.pamong_status = 1
        LIMIT 1
    ");
    if ($r && $row = $r->fetch_assoc()) return $row;
    // Fallback without status filter
    $r2 = $conn->query("SELECT p.pamong_nama, COALESCE(rj.nama, '') AS pamong_jabatan, p.pamong_nip, p.pamong_niap FROM tweb_desa_pamong p LEFT JOIN ref_jabatan rj ON p.jabatan_id = rj.id WHERE p.pamong_id = $id LIMIT 1");
    if ($r2 && $row2 = $r2->fetch_assoc()) return $row2;
    return ['pamong_nama' => '-', 'pamong_jabatan' => '-', 'pamong_nip' => '', 'pamong_niap' => ''];
}

// Get village config using direct columns (xosid master DB structure)
$cfg = [];
$cfg_r = $conn->query("SELECT * FROM config LIMIT 1");
if ($cfg_r && $cfg_row = $cfg_r->fetch_assoc()) {
    $cfg = $cfg_row;
}

$nama_desa     = $cfg['nama_desa'] ?? '-';
$nama_kec      = $cfg['nama_kecamatan'] ?? '-';
$nama_kab      = $cfg['nama_kabupaten'] ?? '-';
$nama_prov     = $cfg['nama_propinsi'] ?? '-';
$alamat_kantor = $cfg['alamat_kantor'] ?? '';
$sebutan_dusun = 'Dusun'; // Default - not in config table
$sebutan_kades = 'Kepala Desa'; // Default
$sebutan_kadus = 'Kepala Dusun'; // Default


$pamong_ttd     = get_pamong($conn, $pamong_ttd_id);
$pamong_ketahui = get_pamong($conn, $pamong_ketahui_id);
$tgl_cetak      = date('d F Y');

// Ambil data dusun
$data = [];
$dq = $conn->query("SELECT dusun, MIN(urut) as min_urut FROM tweb_wil_clusterdesa WHERE dusun != '' AND dusun IS NOT NULL GROUP BY dusun ORDER BY min_urut ASC, dusun ASC");
if ($dq) {
    while ($drow = $dq->fetch_assoc()) {
        $d_name = $drow['dusun'];

        // Get kepala dusun
        $kepala_d = ['nama' => '-', 'nik' => '-'];
        $kd_q = $conn->query("SELECT p.nama, p.nik FROM tweb_wil_clusterdesa wc 
                               JOIN tweb_penduduk p ON wc.id_kepala = p.id 
                               WHERE wc.dusun = '".$conn->real_escape_string($d_name)."' AND wc.id_kepala > 0 
                               AND wc.rt IN ('0','-','') AND wc.rw IN ('0','-','') LIMIT 1");
        if ($kd_q && $kd_r = $kd_q->fetch_assoc()) {
            $kepala_d = ['nama' => $kd_r['nama'], 'nik' => $sensor_nik ? '****' : $kd_r['nik']];
        }

        // RW count
        $rw_q = $conn->query("SELECT COUNT(DISTINCT rw) as cnt FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($d_name)."' AND rw != '0' AND rw != ''");
        $rw_cnt = $rw_q ? intval($rw_q->fetch_assoc()['cnt']) : 0;

        // RT count
        $rt_q = $conn->query("SELECT COUNT(DISTINCT rt) as cnt FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($d_name)."' AND rt NOT IN ('0', '', '-')");
        $rt_cnt = $rt_q ? intval($rt_q->fetch_assoc()['cnt']) : 0;

        // Cluster IDs for this dusun
        $c_q = $conn->query("SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($d_name)."'");
        $cluster_ids = [];
        while ($c_r = $c_q->fetch_assoc()) { $cluster_ids[] = (int)$c_r['id']; }
        $cids = empty($cluster_ids) ? '0' : implode(',', $cluster_ids);

        $fam_q = $conn->query("SELECT COUNT(*) as cnt FROM tweb_keluarga WHERE id_cluster IN ($cids)");
        $kk    = $fam_q ? intval($fam_q->fetch_assoc()['cnt']) : 0;

        $pop_q = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk WHERE id_cluster IN ($cids) AND status_dasar = 1");
        $lp    = $pop_q ? intval($pop_q->fetch_assoc()['cnt']) : 0;

        $l_q   = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk WHERE id_cluster IN ($cids) AND status_dasar = 1 AND sex = 1");
        $l     = $l_q ? intval($l_q->fetch_assoc()['cnt']) : 0;

        $p_q   = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk WHERE id_cluster IN ($cids) AND status_dasar = 1 AND sex = 2");
        $p     = $p_q ? intval($p_q->fetch_assoc()['cnt']) : 0;

        // Get RW data
        $rws = [];
        $rw_list_q = $conn->query("SELECT rw, MIN(urut) as min_urut FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($d_name)."' AND rw NOT IN ('0') AND rt IN ('0','-','') GROUP BY rw ORDER BY min_urut ASC, rw ASC");
        if ($rw_list_q) {
            while ($rw_row = $rw_list_q->fetch_assoc()) {
                $rw_name = $rw_row['rw'];
                if ($rw_name === '') continue;

                // Kepala RW
                $kepala_rw = ['nama' => '-', 'nik' => '-'];
                $krw_q = $conn->query("SELECT p.nama, p.nik FROM tweb_wil_clusterdesa wc 
                                        JOIN tweb_penduduk p ON wc.id_kepala = p.id 
                                        WHERE wc.dusun = '".$conn->real_escape_string($d_name)."' AND wc.rw = '".$conn->real_escape_string($rw_name)."' AND wc.id_kepala > 0 
                                        AND wc.rt IN ('0','-','') LIMIT 1");
                if ($krw_q && $krw_r = $krw_q->fetch_assoc()) {
                    $kepala_rw = ['nama' => $krw_r['nama'], 'nik' => $sensor_nik ? '****' : $krw_r['nik']];
                }

                // RT count in this RW
                $rt_rw_q = $conn->query("SELECT COUNT(DISTINCT rt) as cnt FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($d_name)."' AND rw = '".$conn->real_escape_string($rw_name)."' AND rt NOT IN ('0','-','')");
                $rt_rw_cnt = $rt_rw_q ? intval($rt_rw_q->fetch_assoc()['cnt']) : 0;

                // Cluster IDs for this RW
                $c_rw_q = $conn->query("SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($d_name)."' AND rw = '".$conn->real_escape_string($rw_name)."'");
                $rw_cluster_ids = [];
                while ($c_rw_r = $c_rw_q->fetch_assoc()) { $rw_cluster_ids[] = (int)$c_rw_r['id']; }
                $rw_cids = empty($rw_cluster_ids) ? '0' : implode(',', $rw_cluster_ids);

                $rw_fam_q = $conn->query("SELECT COUNT(*) as cnt FROM tweb_keluarga WHERE id_cluster IN ($rw_cids)");
                $rw_kk    = $rw_fam_q ? intval($rw_fam_q->fetch_assoc()['cnt']) : 0;
                $rw_l_q   = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk WHERE id_cluster IN ($rw_cids) AND status_dasar = 1 AND sex = 1");
                $rw_l     = $rw_l_q ? intval($rw_l_q->fetch_assoc()['cnt']) : 0;
                $rw_p_q   = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk WHERE id_cluster IN ($rw_cids) AND status_dasar = 1 AND sex = 2");
                $rw_p     = $rw_p_q ? intval($rw_p_q->fetch_assoc()['cnt']) : 0;

                // Get RT list
                $rts = [];
                $rt_list_q = $conn->query("SELECT * FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($d_name)."' AND rw = '".$conn->real_escape_string($rw_name)."' AND rt NOT IN ('0','-','') ORDER BY urut ASC, rt ASC");
                if ($rt_list_q) {
                    $seen_rt = [];
                    while ($rt_row = $rt_list_q->fetch_assoc()) {
                        $rt_name = $rt_row['rt'];
                        if (in_array($rt_name, $seen_rt)) continue;
                        $seen_rt[] = $rt_name;

                        $kepala_rt = ['nama' => '-', 'nik' => '-'];
                        if (!empty($rt_row['id_kepala'])) {
                            $krt_q = $conn->query("SELECT nama, nik FROM tweb_penduduk WHERE id = ".(int)$rt_row['id_kepala']." LIMIT 1");
                            if ($krt_q && $krt_r = $krt_q->fetch_assoc()) {
                                $kepala_rt = ['nama' => $krt_r['nama'], 'nik' => $sensor_nik ? '****' : $krt_r['nik']];
                            }
                        }

                        $rt_fam_q = $conn->query("SELECT COUNT(*) as cnt FROM tweb_keluarga WHERE id_cluster = ".(int)$rt_row['id']);
                        $rt_kk    = $rt_fam_q ? intval($rt_fam_q->fetch_assoc()['cnt']) : 0;
                        $rt_l_q   = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk WHERE id_cluster = ".(int)$rt_row['id']." AND status_dasar = 1 AND sex = 1");
                        $rt_l     = $rt_l_q ? intval($rt_l_q->fetch_assoc()['cnt']) : 0;
                        $rt_p_q   = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk WHERE id_cluster = ".(int)$rt_row['id']." AND status_dasar = 1 AND sex = 2");
                        $rt_p     = $rt_p_q ? intval($rt_p_q->fetch_assoc()['cnt']) : 0;

                        $rts[] = ['rt' => $rt_name, 'kepala' => $kepala_rt, 'kk' => $rt_kk, 'l' => $rt_l, 'p' => $rt_p];
                    }
                }

                $rws[] = ['rw' => $rw_name, 'kepala' => $kepala_rw, 'rt_cnt' => $rt_rw_cnt, 'kk' => $rw_kk, 'l' => $rw_l, 'p' => $rw_p, 'rts' => $rts];
            }
        }

        $data[] = [
            'dusun'   => $d_name,
            'kepala'  => $kepala_d,
            'rw_cnt'  => $rw_cnt,
            'rt_cnt'  => $rt_cnt,
            'kk'      => $kk,
            'l'       => $l,
            'p'       => $p,
            'rws'     => $rws
        ];
    }
}

$conn->close();

// Compute totals
$total_rw = array_sum(array_column($data, 'rw_cnt'));
$total_rt = array_sum(array_column($data, 'rt_cnt'));
$total_kk = array_sum(array_column($data, 'kk'));
$total_l  = array_sum(array_column($data, 'l'));
$total_p  = array_sum(array_column($data, 'p'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Wilayah <?= htmlspecialchars($nama_desa) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            color: #000;
            background: white;
            padding: 20mm 15mm;
        }
        .header-doc {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-doc .logo { display: inline-block; vertical-align: middle; margin-right: 15px; }
        .header-doc .logo img { width: 70px; height: 70px; }
        .header-doc .info { display: inline-block; vertical-align: middle; text-align: left; }
        .header-doc h2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-doc h3 { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-doc p { font-size: 10pt; }

        .judul {
            text-align: center;
            margin: 15px 0 10px;
        }
        .judul h3 { font-size: 13pt; font-weight: bold; text-transform: uppercase; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px 6px;
            vertical-align: middle;
        }
        thead th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }
        .row-dusun td { background: #d5e8d4; font-weight: bold; }
        .row-rw td { background: #f5f5f5; }
        .row-rt td { background: #ffffff; }
        tfoot tr td { background: #d5e8d4; font-weight: bold; }

        td.num { text-align: right; }
        td.center { text-align: center; }

        .ttd-area {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .ttd-box {
            text-align: center;
            width: 220px;
        }
        .ttd-box p { font-size: 10pt; margin-bottom: 5px; }
        .ttd-box .ttd-space { height: 55px; border-bottom: 1px solid #000; margin: 5px auto; width: 180px; }
        .ttd-box .ttd-name { font-weight: bold; text-decoration: underline; font-size: 10pt; }
        .ttd-box .ttd-jabatan { font-size: 9pt; }

        .tgl-cetak { font-size: 9pt; color: #555; margin-bottom: 10px; text-align: right; }

        @media print {
            @page {
                size: auto;
                margin: 0mm !important;
            }
            body {
                padding: 20mm 15mm 20mm 15mm !important;
                box-sizing: border-box !important;
            }
            .custom-printable-header {
                position: fixed !important;
                top: 8mm !important;
                left: 15mm !important;
                right: 15mm !important;
                display: flex !important;
                justify-content: space-between !important;
                font-family: 'Outfit', Arial, sans-serif !important;
                font-size: 8pt !important;
                color: #475569 !important;
                border-bottom: 1px solid #cbd5e1 !important;
                padding-bottom: 4px !important;
                z-index: 9999 !important;
            }
            .custom-printable-footer {
                position: fixed !important;
                bottom: 8mm !important;
                left: 15mm !important;
                right: 15mm !important;
                display: flex !important;
                justify-content: space-between !important;
                font-family: 'Outfit', Arial, sans-serif !important;
                font-size: 8pt !important;
                color: #475569 !important;
                border-top: 1px solid #cbd5e1 !important;
                padding-top: 4px !important;
                z-index: 9999 !important;
            }
        }
        @media screen {
            .custom-printable-header, .custom-printable-footer {
                padding: 10px 15px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                margin-bottom: 15px;
                display: flex;
                justify-content: space-between;
                font-family: 'Outfit', Arial, sans-serif;
                font-size: 8pt;
                color: #475569;
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>
<?php
$tenant_paket = 'esensial';
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
    if (strpos($host, 'demo.') === 0 || strpos($host, 'supradesa.') === 0) {
        $tenant_paket = 'enterprise';
    }
}
$is_esensial = ($tenant_paket === 'esensial');
$xosid_archive_title = $is_esensial ? 'XOSID ESENSIAL ARCHIVE' : 'XOSID SUPRADESA ARCHIVE';
?>

<!-- HEADER CETAK DINAMIS -->
<div class="custom-printable-header">
    <span style="font-weight: 600;">Waktu Cetak: <?= date('d/m/Y, h:i A') ?></span>
    <span style="color: #94a3b8; font-weight: 500;">Arsip Resmi Pemerintahan Desa</span>
</div>

<!-- FOOTER CETAK DINAMIS -->
<div class="custom-printable-footer">
    <span style="font-weight: 700; color: #0f172a;">Sumber Dokumen : <?= htmlspecialchars(ucwords($nama_desa)) ?>, <?= htmlspecialchars(ucwords($nama_kec)) ?>, <?= htmlspecialchars(ucwords($nama_kab)) ?></span>
    <span style="font-weight: 600; color: #06b6d4;"><?= $xosid_archive_title ?></span>
</div>

<?php
$logo_url = function_exists('base_url') ? base_url('assets/files/logo/xosid_logo.png') : '/assets/files/logo/xosid_logo.png';
?>

<div class="header-doc">
    <div class="logo">
        <img src="<?= $logo_url ?>" alt="Logo XOSID" style="width: 70px; height: auto; max-height: 70px; object-fit: contain;">
    </div>
    <div class="info">
        <h2>Pemerintah <?= htmlspecialchars(ucwords($nama_desa)) ?></h2>
        <h3><?= htmlspecialchars(ucwords($nama_kec)) ?> &mdash; <?= htmlspecialchars(ucwords($nama_kab)) ?></h3>
        <p>Provinsi <?= htmlspecialchars(ucwords($nama_prov)) ?></p>
        <?php if ($alamat_kantor): ?><p><?= htmlspecialchars($alamat_kantor) ?></p><?php endif; ?>
    </div>
</div>

<div class="judul">
    <h3>Tabel Data Kependudukan Berdasarkan Populasi Per Wilayah</h3>
</div>

<p class="tgl-cetak">Tanggal Cetak: <?= $tgl_cetak ?></p>

<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2"><?= htmlspecialchars(ucwords($sebutan_dusun)) ?></th>
            <th rowspan="2">RW</th>
            <th rowspan="2">RT</th>
            <th colspan="2">NIK &amp; Nama Kepala/Ketua</th>
            <th rowspan="2">Jml RW</th>
            <th rowspan="2">Jml RT</th>
            <th rowspan="2">KK</th>
            <th rowspan="2">L+P</th>
            <th rowspan="2">L</th>
            <th rowspan="2">P</th>
        </tr>
        <tr>
            <th>NIK</th>
            <th>Nama</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($data as $dusun): ?>
            <tr class="row-dusun">
                <td class="center"><?= $no++ ?></td>
                <td><?= htmlspecialchars(strtoupper($dusun['dusun'])) ?></td>
                <td></td>
                <td></td>
                <td><?= htmlspecialchars($dusun['kepala']['nik']) ?></td>
                <td><?= htmlspecialchars($dusun['kepala']['nama']) ?></td>
                <td class="num"><?= $dusun['rw_cnt'] ?></td>
                <td class="num"><?= $dusun['rt_cnt'] ?></td>
                <td class="num"><?= number_format($dusun['kk']) ?></td>
                <td class="num"><?= number_format($dusun['l'] + $dusun['p']) ?></td>
                <td class="num"><?= number_format($dusun['l']) ?></td>
                <td class="num"><?= number_format($dusun['p']) ?></td>
            </tr>
            <?php foreach ($dusun['rws'] as $rw): ?>
                <tr class="row-rw">
                    <td class="center"><?= $no++ ?></td>
                    <td></td>
                    <td><?= htmlspecialchars(strtoupper($rw['rw'])) ?></td>
                    <td></td>
                    <?php if ($rw['rw'] !== '-'): ?>
                        <td><?= htmlspecialchars($rw['kepala']['nik']) ?></td>
                        <td><?= htmlspecialchars($rw['kepala']['nama']) ?></td>
                    <?php else: ?>
                        <td></td><td></td>
                    <?php endif; ?>
                    <td class="num"></td>
                    <td class="num"><?= $rw['rt_cnt'] ?></td>
                    <td class="num"><?= number_format($rw['kk']) ?></td>
                    <td class="num"><?= number_format($rw['l'] + $rw['p']) ?></td>
                    <td class="num"><?= number_format($rw['l']) ?></td>
                    <td class="num"><?= number_format($rw['p']) ?></td>
                </tr>
                <?php foreach ($rw['rts'] as $rt): ?>
                    <tr class="row-rt">
                        <td class="center"><?= $no++ ?></td>
                        <td></td>
                        <td></td>
                        <td><?= htmlspecialchars(strtoupper($rt['rt'])) ?></td>
                        <td><?= htmlspecialchars($rt['kepala']['nik']) ?></td>
                        <td><?= htmlspecialchars($rt['kepala']['nama']) ?></td>
                        <td class="num"></td>
                        <td class="num"></td>
                        <td class="num"><?= number_format($rt['kk']) ?></td>
                        <td class="num"><?= number_format($rt['l'] + $rt['p']) ?></td>
                        <td class="num"><?= number_format($rt['l']) ?></td>
                        <td class="num"><?= number_format($rt['p']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align:right; font-weight:bold;">TOTAL KESELURUHAN</td>
            <td class="num"><?= $total_rw ?></td>
            <td class="num"><?= $total_rt ?></td>
            <td class="num"><?= number_format($total_kk) ?></td>
            <td class="num"><?= number_format($total_l + $total_p) ?></td>
            <td class="num"><?= number_format($total_l) ?></td>
            <td class="num"><?= number_format($total_p) ?></td>
        </tr>
    </tfoot>
</table>

<!-- Tanda Tangan -->
<div class="ttd-area">
    <div class="ttd-box">
        <p>Mengetahui,</p>
        <p><?= htmlspecialchars(strtoupper($pamong_ketahui['pamong_jabatan'] . ' ' . $nama_desa)) ?></p>
        <div class="ttd-space"></div>
        <p class="ttd-name"><?= htmlspecialchars($pamong_ketahui['pamong_nama']) ?></p>
        <p class="ttd-jabatan">
            <?php if ($pamong_ketahui['pamong_nip']): ?>NIP: <?= htmlspecialchars($pamong_ketahui['pamong_nip']) ?><?php endif; ?>
        </p>
    </div>
    <div class="ttd-box">
        <p><?= htmlspecialchars($nama_desa) ?>, <?= $tgl_cetak ?></p>
        <p><?= htmlspecialchars(strtoupper($pamong_ttd['pamong_jabatan'] . ' ' . $nama_desa)) ?></p>
        <div class="ttd-space"></div>
        <p class="ttd-name"><?= htmlspecialchars($pamong_ttd['pamong_nama']) ?></p>
        <p class="ttd-jabatan">
            <?php if ($pamong_ttd['pamong_nip']): ?>NIP: <?= htmlspecialchars($pamong_ttd['pamong_nip']) ?><?php endif; ?>
        </p>
    </div>
</div>

<?php if ($format !== 'xlsx'): ?>
<script>
    // Auto print
    window.onload = function() {
        window.print();
    };
</script>
<?php endif; ?>
</body>
</html>
