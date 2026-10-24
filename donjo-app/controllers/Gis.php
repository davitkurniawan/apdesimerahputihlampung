<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - GIS Controller (Clean Unencrypted Replacement)
 * Fixes broken ionCube legacy file for GIS/Peta Wilayah & clears session filters on /gis/clear.
 */
class Gis extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        $this->load->database();
        $this->load->helper(['url', 'form']);
    }

    private function renderBlade($viewName, $data = [])
    {
        global $app;
        $output = null;

        try {
            if (isset($app) && method_exists($app, 'make')) {
                if (class_exists('\Illuminate\Support\ViewErrorBag')) {
                    $app->make('view')->share('errors', new \Illuminate\Support\ViewErrorBag);
                }
                $app->make('view')->share('ci', $this);
                
                // Inject CSRF tokens
                if (isset($this->security)) {
                    $data['token_name'] = $this->security->get_csrf_token_name();
                    $data['token_value'] = $this->security->get_csrf_hash();
                } else {
                    $data['token_name'] = config_item('csrf_token_name') ?: 'sidcsrf';
                    $data['token_value'] = $_COOKIE[$data['token_name']] ?? '';
                }

                $output = $app->make('view')->make($viewName, $data)->render();
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_exception.log',
                "[" . date('Y-m-d H:i:s') . "] GIS RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND);
        }

        if ($output) {
            echo $output;
        } else {
            echo '<div style="color:red;padding:20px;">Gagal memuat peta GIS. Silakan cek log sistem.</div>';
        }
    }

    public function index($layer = null, $id = null)
    {
        if ($layer === 'clear') {
            $this->clear();
            return;
        }

        $desa = [];
        if (function_exists('identitas')) {
            $desa = identitas();
        } else {
            $q = $this->db->get('config');
            if ($q && $row = $q->row_array()) {
                $desa = $row;
            }
        }

        $list_status_penduduk = [
            '1' => 'Tinggal Tetap',
            '2' => 'Penduduk Tidak Tetap / Pendatang',
            '3' => 'Pindah / Mati'
        ];

        $list_jenis_kelamin = [
            '1' => 'Laki-Laki',
            '2' => 'Perempuan'
        ];

        // Fetch Dusun list
        $list_dusun = [];
        $dusun_q = $this->db->select('dusun')->from('tweb_wil_clusterdesa')->where('dusun !=', '')->group_by('dusun')->get();
        if ($dusun_q) {
            $list_dusun = $dusun_q->result_array();
        }

        $filter = $_SESSION['gis_filter'] ?? '';
        $sex = $_SESSION['gis_sex'] ?? '';
        $dusun = $_SESSION['gis_dusun'] ?? '';
        $rw = $_SESSION['gis_rw'] ?? '';
        $rt = $_SESSION['gis_rt'] ?? '';
        $cari = $_SESSION['gis_cari'] ?? '';
        $lap = $_SESSION['gis_lap'] ?? '';

        $list_rw = [];
        if ($dusun) {
            $rw_q = $this->db->select('rw')->from('tweb_wil_clusterdesa')->where('dusun', $dusun)->where('rw !=', '0')->where('rw !=', '')->group_by('rw')->get();
            if ($rw_q) $list_rw = $rw_q->result_array();
        }

        $list_rt = [];
        if ($dusun && $rw) {
            $rt_q = $this->db->select('rt')->from('tweb_wil_clusterdesa')->where('dusun', $dusun)->where('rw', $rw)->where('rt !=', '0')->where('rt !=', '')->group_by('rt')->get();
            if ($rt_q) $list_rt = $rt_q->result_array();
        }

        $dusun_gis = [];
        $rw_gis = [];
        $rt_gis = [];

        $list_ref = [
            '0' => 'Pendidikan Dalam KK',
            '1' => 'Pekerjaan',
            '2' => 'Status Perkawinan',
            '3' => 'Agama',
            '4' => 'Jenis Kelamin',
            '5' => 'Warganegara',
            '6' => 'Status Penduduk',
            '7' => 'Golongan Darah',
            '8' => 'Penyandang Cacat',
            '9' => 'Penyakit Menahun',
            '10' => 'Akta Kelahiran',
            '11' => 'Kepemilikan KTP',
            '12' => 'Asuransi Kesehatan',
            '13' => 'Hubungan Dalam KK',
            '14' => 'Umur (Kategori)',
            '15' => 'Umur (Rentang)'
        ];

        $list_bantuan = [
            '1' => 'Program Keluarga Harapan (PKH)',
            '2' => 'Bantuan Pangan Non Tunai (BPNT)',
            '3' => 'Bantuan Sosial Tunai (BST)'
        ];

        $data = [
            'desa' => $desa,
            'list_ref' => $list_ref,
            'list_bantuan' => $list_bantuan,
            'lap' => $lap,
            'dusun_gis' => $dusun_gis,
            'rw_gis' => $rw_gis,
            'rt_gis' => $rt_gis,
            'list_status_penduduk' => $list_status_penduduk,
            'list_jenis_kelamin' => $list_jenis_kelamin,
            'list_dusun' => $list_dusun,
            'list_rw' => $list_rw,
            'list_rt' => $list_rt,
            'filter' => $filter,
            'sex' => $sex,
            'dusun' => $dusun,
            'rw' => $rw,
            'rt' => $rt,
            'cari' => $cari,
            'layar_dusun' => 1,
            'layar_rw' => 1,
            'layar_rt' => 1,
            'layar_area' => 1,
            'layar_garis' => 1,
            'layar_lokasi' => 1,
            'layar_bangunan' => 1,
            'layar_persil' => 1,
            'penduduk' => []
        ];

        $this->renderBlade('admin.gis.maps', $data);
    }

    public function clear()
    {
        unset($_SESSION['gis_filter'], $_SESSION['gis_sex'], $_SESSION['gis_dusun'], $_SESSION['gis_rw'], $_SESSION['gis_rt'], $_SESSION['gis_cari'], $_SESSION['gis_lap']);
        redirect('gis');
    }

    public function filter()
    {
        if ($this->input->post()) {
            $_SESSION['gis_filter'] = $this->input->post('filter');
            $_SESSION['gis_sex'] = $this->input->post('sex');
            $_SESSION['gis_dusun'] = $this->input->post('dusun');
            $_SESSION['gis_rw'] = $this->input->post('rw');
            $_SESSION['gis_rt'] = $this->input->post('rt');
            $_SESSION['gis_cari'] = $this->input->post('cari');
        }
        redirect('gis');
    }

    public function ajax_adv_search()
    {
        $this->load->view('admin/gis/ajax_adv_search');
    }
}