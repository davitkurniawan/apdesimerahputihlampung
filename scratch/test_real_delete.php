<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';
$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

$id = 24; // Mulyono Lestari
echo "Trying a direct, non-transactional deletion of resident ID $id...\n";

try {
    // 1. Let's see if the resident exists
    $stmt = $pdo->prepare("SELECT id, nik, nama FROM tweb_penduduk WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    
    if (!$row) {
        die("Resident ID $id not found in database.\n");
    }
    
    echo "Found resident: NIK={$row['nik']}, Name={$row['nama']}\n";
    
    // 2. Let's check which tables reference this resident right now
    $tables = [
        'analisis_respon' => 'penduduk_id',
        'analisis_respon_bukti' => 'penduduk_id',
        'analisis_respon_hasil' => 'penduduk_id',
        'anggota_grup_kontak' => 'id_penduduk',
        'cdesa_penduduk' => 'id_pend',
        'covid19_pemudik' => 'id_terdata',
        'dokumen' => 'id_pend',
        'dtks_anggota' => 'id_penduduk',
        'kader_pemberdayaan_masyarakat' => 'penduduk_id',
        'kehadiran_pengaduan' => 'id_penduduk',
        'kelompok' => 'id_ketua',
        'kelompok_anggota' => 'id_penduduk',
        'kia' => 'anak_id',
        'kia_ibu' => ['kia', 'ibu_id'],
        'log_keluarga' => 'id_pend',
        'log_penduduk' => 'id_pend',
        'log_perubahan_penduduk' => 'id_pend',
        'log_surat' => 'id_pend',
        'pelapak' => 'id_pend',
        'permohonan_surat' => 'id_pemohon',
        'pesan_mandiri' => 'penduduk_id',
        'program_peserta' => 'kartu_id_pend',
        'suplemen_terdata' => 'penduduk_id',
        'tanah_desa' => 'id_penduduk',
        'tweb_desa_pamong' => 'id_pend',
        'tweb_keluarga' => 'nik_kepala',
        'tweb_penduduk_mandiri' => 'id_pend',
        'tweb_penduduk_map' => 'id',
        'tweb_rtm' => 'nik_kepala',
        'tweb_wil_clusterdesa' => 'id_kepala'
    ];
    
    foreach ($tables as $tkey => $col) {
        $table = $tkey;
        if (is_array($col)) {
            $table = $col[0];
            $col = $col[1];
        }
        try {
            $check = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $col = ?");
            $check->execute([$id]);
            $cnt = $check->fetchColumn();
            if ($cnt > 0) {
                echo "-> Referenced in $table ($col): $cnt rows\n";
            }
        } catch (Exception $ex) {}
    }
    
    // 3. Attempt direct delete on tweb_penduduk to see the immediate foreign key error
    echo "Executing: DELETE FROM tweb_penduduk WHERE id = $id\n";
    $pdo->exec("DELETE FROM tweb_penduduk WHERE id = $id");
    echo "SUCCESSFULLY DELETED WITHOUT EXCEPTION!\n";
} catch (Exception $e) {
    echo "DATABASE ERROR THROWN:\n";
    echo $e->getMessage() . "\n";
}
