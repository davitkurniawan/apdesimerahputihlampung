<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Modul Controller (Clean Unencrypted Replacement)
 * Bypasses legacy ionCube encrypted file and renders Blade views cleanly.
 */
class Modul extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        $this->load->database();
        $this->load->helper('url');
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
                
                // Inject CSRF tokens safely
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
                "[" . date('Y-m-d H:i:s') . "] MODUL RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND);
        }

        if ($output) {
            echo $output;
        } else {
            echo '<div style="color:red;padding:20px;">Gagal memuat tampilan Modul. Silakan cek log sistem.</div>';
        }
    }

    public function index($parent = 0)
    {
        $parent = (int)$parent;
        $data['utama'] = ($parent == 0);
        $data['parent'] = $parent;
        $data['status'] = ['1' => 'Aktif', '2' => 'Tidak Aktif'];
        $data['parentName'] = '';

        if ($parent > 0 && isset($this->db)) {
            $q = $this->db->get_where('setting_modul', ['id' => $parent]);
            if ($q && $row = $q->row_array()) {
                $data['parentName'] = $row['modul'];
            }
        }

        $this->renderBlade('admin.pengaturan.modul.index', $data);
    }

    public function sub_modul($parent = 0)
    {
        $this->index($parent);
    }

    public function lock($id = 0, $val = 0)
    {
        $id = (int)$id;
        $val = (int)$val;
        if ($id > 0 && isset($this->db)) {
            $this->db->where('id', $id)->update('setting_modul', ['aktif' => $val]);
        }
        redirect($_SERVER['HTTP_REFERER'] ?? 'modul');
    }

    public function datatables()
    {
        header('Content-Type: application/json');
        $parent = isset($_GET['parent']) ? (int)$_GET['parent'] : 0;
        $draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
        $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
        $length = isset($_POST['length']) ? (int)$_POST['length'] : 25;
        $search = $_POST['search']['value'] ?? '';

        if (!isset($this->db)) {
            echo json_encode(['draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            exit;
        }

        $builder = $this->db->from('setting_modul')->where('parent', $parent)->where('hidden', 0);
        if (!empty($search)) {
            $this->db->like('modul', $search);
        }

        $total = $this->db->count_all_results('', FALSE);
        $query = $this->db->order_by('urut', 'ASC')->limit($length, $start)->get();
        $data = $query ? $query->result_array() : [];

        $formatted = [];
        $no = $start + 1;

        foreach ($data as $m) {
            $id = $m['id'];
            $aktif = $m['aktif'];

            // Button actions
            $lock_btn = ($aktif == 1)
                ? '<a href="' . site_url("modul/lock/$id/2") . '" class="btn btn-bg bg-navy btn-sm" title="Nonaktifkan Modul"><i class="fa fa-unlock"></i></a>'
                : '<a href="' . site_url("modul/lock/$id/1") . '" class="btn btn-bg bg-red btn-sm" title="Aktifkan Modul"><i class="fa fa-lock"></i></a>';

            $sub_btn = ($parent == 0)
                ? '<a href="' . site_url("modul/sub_modul/$id") . '" class="btn bg-olive btn-sm" title="Sub Modul"><i class="fa fa-list"></i></a>'
                : '';

            $m['DT_RowIndex'] = $no++;
            $m['aksi'] = "<div class='btn-group'>$lock_btn $sub_btn</div>";
            $formatted[] = $m;
        }

        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $formatted
        ]);
        exit;
    }
}