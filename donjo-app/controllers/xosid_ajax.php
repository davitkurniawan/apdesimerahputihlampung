<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Xosid_ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }
    }

    public function wilayah()
    {
        $parent = $this->input->get('parent') ?: 0;
        $level = $this->input->get('level') ?: 'dusun';
        $results = [];
        try {
            $db_real = $this->load->database('default', TRUE);
            
            if ($level == 'dusun') {
                $query = $db_real->where_in('rt', ['0', '-', ''])->where_in('rw', ['0', '-', ''])->get('tweb_wil_clusterdesa');
            } elseif ($level == 'rw') {
                $query = $db_real->where('dusun', $parent)->where_in('rt', ['0', '-', ''])->where_not_in('rw', ['0', '-', ''])->get('tweb_wil_clusterdesa');
            } elseif ($level == 'rt') {
                $query = $db_real->where('dusun', $parent)->where_not_in('rt', ['0', '-', ''])->get('tweb_wil_clusterdesa');
            } else {
                $query = $db_real->where_in('rt', ['0', '-', ''])->where_in('rw', ['0', '-', ''])->get('tweb_wil_clusterdesa');
            }

            if ($query) {
                foreach ($query->result_array() as $row) {
                    $res_rw = 0; $res_rt = 0;
                    if ($level == 'dusun') {
                        $res_rw = $db_real->where(['dusun' => $row['dusun'], 'rw !=' => '0', 'rt' => '0'])->count_all_results('tweb_wil_clusterdesa');
                        $res_rt = $db_real->where(['dusun' => $row['dusun'], 'rt !=' => '0'])->count_all_results('tweb_wil_clusterdesa');
                    }

                    $results[] = [
                        'drag-handle' => '<div style="cursor: move; color: rgba(255,255,255,0.2);"><i data-lucide="grip-vertical" style="width: 16px;"></i></div>',
                        'DT_RowIndex' => count($results) + 1,
                        'aksi' => '<div class="btn-group">
                                    <a href="'.site_url("index.php/wilayah/form_{$level}/{$parent}/".$row['id']).'" class="btn-action-neural" title="Ubah Data"><i class="fa fa-edit"></i></a>
                                    <button onclick="hapusWilayah('.$row['id'].')" class="btn-action-neural" style="color: #f43f5e;" title="Hapus"><i class="fa fa-trash"></i></button>
                                  </div>',
                        $level => $row[$level] ?? '-',
                        'kepala' => $row['id_kepala'] ? 'Pejabat Terdaftar' : 'Belum Ada',
                        'nik_kepala' => '-',
                        'rws_count' => $res_rw,
                        'rts_count' => $res_rt,
                        'keluarga_aktif_count' => 0,
                        'penduduk_count' => 0,
                        'penduduk_pria_count' => 0,
                        'penduduk_wanita_count' => 0,
                        'id' => $row['id']
                    ];
                }
            }
        } catch (\Exception $e) {}

        if (empty($results)) {
            $results[] = [
                'drag-handle' => '<i class="fa fa-bars"></i>', 'DT_RowIndex' => 1, 'aksi' => '-',
                $level => 'Belum ada data wilayah', 'kepala' => '-', 'nik_kepala' => '-',
                'rws_count' => 0, 'rts_count' => 0, 'keluarga_aktif_count' => 0, 'penduduk_count' => 0,
                'penduduk_pria_count' => 0, 'penduduk_wanita_count' => 0, 'id' => 1
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['draw' => intval($this->input->get('draw')), 'recordsTotal' => count($results), 'recordsFiltered' => count($results), 'data' => $results]);
    }
}
