<?php
/**
 * XOSID Wilayah Dialog Handler
 * Standalone dialog for print/download wilayah data.
 * Compatible with xosid master DB structure.
 */

require_once __DIR__ . '/xosid_ajax_db.php';

// Detect tenant package
$tenant_paket = 'esensial';
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
    if (strpos($host, 'demo.') === 0 || strpos($host, 'supradesa.') === 0) {
        $tenant_paket = 'enterprise';
    }
}
$is_esensial = ($tenant_paket === 'esensial');

// Determine action (cetak or unduh)
$action_param  = $_GET['action'] ?? '';
$is_unduh      = ($action_param === 'unduh');
$action_label  = $is_unduh ? 'Unduh' : 'Cetak';
$action_icon   = $is_unduh ? 'fa-download' : 'fa-print';

// Get village config
$cfg = [];
$cfg_r = $conn->query("SELECT * FROM config LIMIT 1");
if ($cfg_r && $cfg_row = $cfg_r->fetch_assoc()) {
    $cfg = $cfg_row;
}
$sebutan_pemerintah = 'Desa'; // Default - config may not have sebutan

// Get pamong list using correct table structure
$pamong_list = [];
$pq = $conn->query("
    SELECT p.pamong_id, p.pamong_nama, COALESCE(rj.nama, '') AS pamong_jabatan,
           p.pamong_nip, p.pamong_niap
    FROM tweb_desa_pamong p
    LEFT JOIN ref_jabatan rj ON p.jabatan_id = rj.id
    WHERE p.pamong_status = 1
    ORDER BY p.urut ASC, rj.nama ASC
    LIMIT 100
");
if ($pq) {
    while ($pr = $pq->fetch_assoc()) {
        $pamong_list[] = $pr;
    }
}

$conn->close();

// POST handler - redirect to print page
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pamong_ttd     = intval($_POST['pamong_ttd'] ?? 0);
    $pamong_ketahui = intval($_POST['pamong_ketahui'] ?? 0);
    $sensor_nik     = isset($_POST['sensor_nik']) ? 1 : 0;
    $format         = $is_unduh ? 'xlsx' : 'html';

    $print_url = "/xosid_wilayah_cetak.php?pamong_ttd={$pamong_ttd}&pamong_ketahui={$pamong_ketahui}&sensor_nik={$sensor_nik}&format={$format}";
    header("Location: {$print_url}");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($action_label) ?> Data Wilayah</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', 'Segoe UI', sans-serif; background: transparent; color: <?= $is_esensial ? '#1e293b' : '#e2e8f0' ?>; }

        .dialog-wrap {
            background: <?= $is_esensial ? '#ffffff' : 'linear-gradient(135deg, rgba(15,23,42,0.98) 0%, rgba(20,30,55,0.98) 100%)' ?>;
            border: none;
            border-radius: 0;
            overflow: hidden;
        }

        .dialog-header {
            background: <?= $is_esensial ? 'rgba(21,62,126,0.04)' : 'rgba(6,182,212,0.08)' ?>;
            border-bottom: <?= $is_esensial ? '1px solid #e2e8f0' : '1px solid rgba(6,182,212,0.2)' ?>;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 0;
        }
        .dialog-header .icon-wrap {
            width: 40px; height: 40px;
            background: <?= $is_esensial ? 'rgba(21,62,126,0.12)' : 'rgba(6,182,212,0.15)' ?>;
            border: <?= $is_esensial ? '1px solid rgba(21,62,126,0.25)' : '1px solid rgba(6,182,212,0.3)' ?>;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: <?= $is_esensial ? '#153E7E' : '#06b6d4' ?>; font-size: 16px;
        }
        .dialog-header h4 {
            font-size: 15px; font-weight: 800; color: <?= $is_esensial ? '#153E7E' : '#ffffff' ?>;
            letter-spacing: 0.5px; margin: 0;
        }
        .dialog-header p {
            font-size: 11px; color: <?= $is_esensial ? '#64748b' : '#64748b' ?>; margin: 2px 0 0;
        }

        .dialog-body { padding: 20px 24px; }

        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 11px; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: <?= $is_esensial ? '#475569' : '#94a3b8' ?>; margin-bottom: 8px;
        }
        .form-select {
            width: 100%; padding: 10px 14px;
            background: <?= $is_esensial ? '#f8fafc' : 'rgba(15,23,42,0.8)' ?>;
            border: <?= $is_esensial ? '1px solid #cbd5e1' : '1px solid rgba(255,255,255,0.1)' ?>;
            border-radius: 10px; color: <?= $is_esensial ? '#0f172a' : '#e2e8f0' ?>;
            font-size: 13px; font-weight: 600; appearance: none;
            cursor: pointer; transition: all 0.2s;
        }
        .form-select:focus {
            outline: none;
            border-color: <?= $is_esensial ? '#153E7E' : 'rgba(6,182,212,0.5)' ?>;
            box-shadow: <?= $is_esensial ? '0 0 0 3px rgba(21,62,126,0.15)' : '0 0 0 3px rgba(6,182,212,0.1)' ?>;
        }
        .form-select option { background: <?= $is_esensial ? '#ffffff' : '#0f172a' ?>; color: <?= $is_esensial ? '#0f172a' : '#e2e8f0' ?>; }

        .sensor-row {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px;
            background: <?= $is_esensial ? 'rgba(245,158,11,0.08)' : 'rgba(245,158,11,0.06)' ?>;
            border: <?= $is_esensial ? '1px solid rgba(245,158,11,0.25)' : '1px solid rgba(245,158,11,0.2)' ?>;
            border-radius: 10px;
            cursor: pointer;
        }
        .sensor-row input[type="checkbox"] {
            width: 16px; height: 16px; cursor: pointer;
            accent-color: #f59e0b;
        }
        .sensor-row span {
            font-size: 12px; color: <?= $is_esensial ? '#334155' : '#94a3b8' ?>; font-weight: 600; cursor: pointer;
        }

        .dialog-footer {
            padding: 14px 24px;
            border-top: <?= $is_esensial ? '1px solid #e2e8f0' : '1px solid rgba(255,255,255,0.05)' ?>;
            display: flex; justify-content: flex-end; gap: 10px;
            background: <?= $is_esensial ? '#f8fafc' : 'rgba(255,255,255,0.01)' ?>;
        }

        .btn-cancel {
            padding: 8px 18px;
            background: <?= $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' ?>;
            border: <?= $is_esensial ? '1px solid #cbd5e1' : '1px solid rgba(255,255,255,0.1)' ?>;
            border-radius: 10px; color: <?= $is_esensial ? '#475569' : '#94a3b8' ?>;
            font-size: 12px; font-weight: 700; cursor: pointer;
            letter-spacing: 0.5px; transition: all 0.2s;
        }
        .btn-cancel:hover { background: <?= $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.1)' ?>; color: <?= $is_esensial ? '#0f172a' : 'white' ?>; }

        .btn-submit {
            padding: 8px 20px;
            background: <?= $is_esensial ? 'linear-gradient(135deg, #153E7E, #1e40af)' : 'linear-gradient(135deg, #06b6d4, #0891b2)' ?>;
            border: none; border-radius: 10px; color: white;
            font-size: 12px; font-weight: 800; cursor: pointer;
            letter-spacing: 1px; text-transform: uppercase;
            box-shadow: <?= $is_esensial ? '0 4px 15px rgba(21,62,126,0.3)' : '0 4px 15px rgba(6,182,212,0.3)' ?>;
            transition: all 0.2s;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: <?= $is_esensial ? '0 6px 20px rgba(21,62,126,0.4)' : '0 6px 20px rgba(6,182,212,0.4)' ?>; }

        .info-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            background: <?= $is_esensial ? 'rgba(21,62,126,0.08)' : 'rgba(6,182,212,0.08)' ?>;
            border: <?= $is_esensial ? '1px solid rgba(21,62,126,0.2)' : '1px solid rgba(6,182,212,0.15)' ?>;
            border-radius: 8px;
            font-size: 11px; font-weight: 600; color: <?= $is_esensial ? '#153E7E' : '#06b6d4' ?>;
            margin-bottom: 16px;
        }

        .no-pamong-msg {
            text-align: center; padding: 20px;
            color: #f59e0b; font-size: 13px;
            background: rgba(245,158,11,0.05);
            border: 1px solid rgba(245,158,11,0.15);
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="dialog-wrap">
    <div class="dialog-header">
        <div class="icon-wrap">
            <i class="fa <?= $action_icon ?>"></i>
        </div>
        <div>
            <h4><?= $action_label ?> Data Wilayah</h4>
            <p>Data Administratif Dusun, RW, dan RT beserta statistik kependudukan</p>
        </div>
    </div>

    <?php if (empty($pamong_list)): ?>
        <div class="dialog-body">
            <div class="no-pamong-msg">
                <i class="fa fa-exclamation-triangle"></i>
                Data pamong/perangkat desa belum tersedia.<br>
                <small>Silakan tambah data pamong terlebih dahulu melalui menu Perangkat Desa.</small>
            </div>
        </div>
        <div class="dialog-footer">
            <button type="button" class="btn-cancel" onclick="closeDialog()">
                <i class="fa fa-times"></i> Tutup
            </button>
        </div>
    <?php else: ?>
    <form method="POST" action="/xosid_wilayah_dialog.php<?= $is_unduh ? '?action=unduh' : '' ?>" target="_blank" onsubmit="return validateDialog();">
        <?php if ($is_unduh): ?>
            <input type="hidden" name="action" value="unduh">
        <?php endif; ?>
        <div class="dialog-body">
            <div class="info-badge">
                <i class="fa fa-info-circle"></i>
                Seluruh wilayah Dusun, RW &amp; RT beserta data kependudukan
            </div>

            <div class="form-group">
                <label class="form-label">Laporan Ditandatangani</label>
                <select name="pamong_ttd" class="form-select" id="sel-pamong-ttd">
                    <option value="">-- Pilih Perangkat <?= htmlspecialchars(ucwords($sebutan_pemerintah)) ?> --</option>
                    <?php foreach ($pamong_list as $p): ?>
                        <option value="<?= (int)$p['pamong_id'] ?>">
                            <?= htmlspecialchars(trim($p['pamong_nama'])) ?>
                            <?php if (!empty(trim($p['pamong_jabatan']))): ?> (<?= htmlspecialchars(trim($p['pamong_jabatan'])) ?>)<?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Laporan Diketahui</label>
                <select name="pamong_ketahui" class="form-select" id="sel-pamong-ketahui">
                    <option value="">-- Pilih Perangkat <?= htmlspecialchars(ucwords($sebutan_pemerintah)) ?> --</option>
                    <?php foreach ($pamong_list as $p): ?>
                        <option value="<?= (int)$p['pamong_id'] ?>">
                            <?= htmlspecialchars(trim($p['pamong_nama'])) ?>
                            <?php if (!empty(trim($p['pamong_jabatan']))): ?> (<?= htmlspecialchars(trim($p['pamong_jabatan'])) ?>)<?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <label class="sensor-row" for="sensor_nik">
                <input type="checkbox" name="sensor_nik" id="sensor_nik" value="1">
                <span>Sensor NIK / No. KK pada dokumen yang dicetak</span>
            </label>
        </div>

        <div class="dialog-footer">
            <button type="button" class="btn-cancel" onclick="closeDialog()">
                <i class="fa fa-times"></i> Batal
            </button>
            <button type="submit" class="btn-submit">
                <i class="fa <?= $action_icon ?>"></i> <?= $action_label ?>
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
function validateDialog() {
    var ttd = document.getElementById('sel-pamong-ttd').value;
    var ketahui = document.getElementById('sel-pamong-ketahui').value;
    if (!ttd) {
        alert('Silakan pilih perangkat yang menandatangani laporan.');
        document.getElementById('sel-pamong-ttd').focus();
        return false;
    }
    if (!ketahui) {
        alert('Silakan pilih perangkat yang mengetahui laporan.');
        document.getElementById('sel-pamong-ketahui').focus();
        return false;
    }
    return true;
}
function closeDialog() {
    try {
        if (window.parent && window.parent.$) {
            window.parent.$('#modalBox').modal('hide');
        } else {
            window.close();
        }
    } catch(e) { window.close(); }
}
</script>
</body>
</html>
