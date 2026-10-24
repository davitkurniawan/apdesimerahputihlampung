<?php
$query = 'select `pamong_id`, `pamong_nama`, `jabatan_id`, `ref_jabatan`.`jenis`, `ref_jabatan`.`nama` as `nama_jabatan`, `pamong_nip`, `pamong_niap`, `pamong_ttd`, `pamong_ub`, `pamong_status`, `pamong_nik`, IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nama, tweb_penduduk.nama) AS pamong_nama, IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nik, tweb_penduduk.nik) AS pamong_nik, gelar_depan, gelar_belakang, IF(tweb_desa_pamong.status_pejabat = 1, CONCAT("Desa ", ref_jabatan.nama), ref_jabatan.nama) AS pamong_jabatan from `tweb_desa_pamong` left join `tweb_penduduk` on `tweb_penduduk`.`id` = `tweb_desa_pamong`.`id_pend` left join `ref_jabatan` on `ref_jabatan`.`id` = `tweb_desa_pamong`.`jabatan_id` where `jabatan_id` = ? and `pamong_status` = ? and `tweb_desa_pamong`.`config_id` = ? limit 1';

if (strpos($query, 'tweb_desa_pamong') !== false && strpos($query, 'pamong_id') !== false) {
    echo "First IF passed\n";
    $from_pos = stripos($query, ' from ');
    if ($from_pos !== false) {
        $select_part = substr($query, 0, $from_pos);
        // Check if id_pend is present as a standalone column in SELECT part
        $is_standalone_selected = (
            strpos($select_part, '`id_pend`') !== false ||
            strpos($select_part, 'id_pend,') !== false ||
            strpos($select_part, 'id_pend as') !== false ||
            strpos($select_part, 'tweb_desa_pamong.id_pend,') !== false
        );
        if (!$is_standalone_selected) {
            echo "id_pend is NOT selected as a standalone column. Running preg_replace...\n";
            $new_query = preg_replace('/SELECT\s+(DISTINCT\s+)?(`?)pamong_id\b/i', 'SELECT $1`tweb_desa_pamong`.`id_pend`, $2pamong_id', $query);
            echo "NEW QUERY: " . $new_query . "\n";
        } else {
            echo "id_pend IS selected as a standalone column!\n";
        }
    } else {
        echo "Could not find FROM keyword!\n";
    }
}
