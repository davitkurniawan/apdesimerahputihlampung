<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Pengurus Controller
 * High-performance, zero-latency controller for Buku Pemerintah Desa (Pamong & Kepengurusan).
 * Fully compatible with PHP 8.2 & 8.4 with null-safety and instant DataTables execution.
 */
class Pengurus_neural extends CI_Controller
{
    private $db_real = null;
    private $desa = [];
    public $controller = 'pengurus';

    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }

        $this->session = new class {
            public function userdata($key) { return $_SESSION[$key] ?? null; }
            public function set_userdata($key, $val) { $_SESSION[$key] = $val; }
            public function flashdata($key) { return null; }
            public function set_flashdata($key, $val) {}
            public function all_userdata() { return $_SESSION ?? []; }
        };

        $this->db = new class($this) {
            private $ci;
            public function __construct($ci){ $this->ci = $ci; }
            public function select($s){ return $this; }
            public function from($f){ return $this; }
            public function where($k, $v = NULL, $escape = NULL){ return $this; }
            public function insert_id(){ return 0; }
            public function affected_rows(){ return 0; }
            public function order_by($k, $v = 'ASC', $escape = NULL){ return $this; }
            public function limit($l, $o = NULL){ return $this; }
            public function count_all($t){ return 0; }
            public function error(){ return ['code' => 0, 'message' => '']; }
            public function get($t = '', $l = null, $o = null){ return $this; }
            public function row_array(){ return []; }
            public function row(){ return new \stdClass(); }
            public function result_array(){
                try {
                    $real_db = $this->ci->load->database('default', TRUE);
                    $query = $real_db->select('*')->from('setting_modul')->where('aktif', 1)->where('hidden', 0)->order_by('urut', 'ASC')->get();
                    if ($query && $query->num_rows() > 0) return $query->result_array();
                } catch (\Exception $e) {}
                return [];
            }
        };

        try { $this->db_real = $this->load->database('default', TRUE); } catch (\Throwable $e) {}
        try {
            if ($this->db_real) {
                $q = $this->db_real->get('config');
                $this->desa = ($q && $q->num_rows() > 0) ? $q->row_array() : [];
            }
        } catch (\Throwable $e) {}
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
                $app->make('view')->share('desa', $this->desa);
                $output = $app->make('view')->make($viewName, $data)->render();
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_neural_error.log',
                "[" . date('Y-m-d H:i:s') . "] PENGURUS RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n",
                FILE_APPEND);
        }

        if ($output) {
            echo $output;
        } else {
            echo '<div style="color:red;padding:20px;">Error rendering view: ' . htmlspecialchars($viewName) . '</div>';
        }
    }

    public function index()
    {
        $status = [
            '1' => 'Aktif',
            '2' => 'Tidak Aktif',
        ];

        $jabatanKadesSekdes = ['0' => 1, '1' => 2];
        try {
            if ($this->db_real) {
                $q = $this->db_real->query("SELECT id, nama FROM ref_jabatan WHERE LOWER(nama) LIKE '%kepala desa%' OR LOWER(nama) LIKE '%sekretaris%' ORDER BY id ASC LIMIT 2");
                if ($q && $q->num_rows() > 0) {
                    $rows = $q->result_array();
                    $jabatanKadesSekdes = [
                        '0' => $rows[0]['id'] ?? 1,
                        '1' => $rows[1]['id'] ?? 2,
                    ];
                }
            }
        } catch (\Throwable $e) {}

        $data = [
            'main_content'       => 'admin.pengurus.index',
            'selected_nav'       => 'pengurus',
            'controller'         => 'pengurus',
            'status'             => $status,
            'default_status'     => '1',
            'jabatanKadesSekdes' => $jabatanKadesSekdes,
            'subtitle'           => 'Buku Pemerintah Desa',
            'desa'               => $this->desa,
        ];

        $this->renderBlade('admin.bumindes.umum.main', $data);
    }

    public function datatables()
    {
        if (!$this->db_real) {
            echo json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            return;
        }

        $draw      = $this->input->post('draw') ?: 1;
        $start     = $this->input->post('start') ?: 0;
        $length    = $this->input->post('length') ?: 10;
        $search    = $this->input->post('search')['value'] ?? '';
        $status    = $this->input->post('status');
        $kehadiran = $this->input->post('kehadiran');

        try {
            $where = [];
            $params = [];

            if ($status !== null && $status !== '') {
                $where[] = "p.pamong_status = " . (int)$status;
            }

            if ($kehadiran !== null && $kehadiran !== '') {
                $where[] = "p.status_kehadiran = " . (int)$kehadiran;
            }

            if (!empty($search)) {
                $s_esc = $this->db_real->escape_like_str($search);
                $where[] = "(p.pamong_nama LIKE '%{$s_esc}%' OR p.pamong_nik LIKE '%{$s_esc}%' OR p.pamong_nip LIKE '%{$s_esc}%' OR j.nama LIKE '%{$s_esc}%')";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $totQ = $this->db_real->query("SELECT COUNT(*) as total FROM tweb_desa_pamong");
            $recordsTotal = ($totQ && $totQ->num_rows() > 0) ? (int)$totQ->row()->total : 0;

            $countSql = "SELECT COUNT(*) as total FROM tweb_desa_pamong p
                LEFT JOIN ref_jabatan j ON p.jabatan_id = j.id
                LEFT JOIN tweb_penduduk pend ON p.id_pend = pend.id
                {$whereClause}";
            $countQ = $this->db_real->query($countSql);
            $recordsFiltered = ($countQ && $countQ->num_rows() > 0) ? (int)$countQ->row()->total : 0;

            $sql = "SELECT p.*,
                           j.nama as jabatan_nama,
                           pend.nama as pend_nama,
                           pend.nik as pend_nik,
                           pend.sex as pend_sex,
                           pend.tempatlahir as pend_tempatlahir,
                           pend.tanggallahir as pend_tanggallahir,
                           pend.foto as pend_foto,
                           a.nama as agama_nama,
                           pk.nama as pendidikan_nama,
                           g.nama as sex_nama
                    FROM tweb_desa_pamong p
                    LEFT JOIN ref_jabatan j ON p.jabatan_id = j.id
                    LEFT JOIN tweb_penduduk pend ON p.id_pend = pend.id
                    LEFT JOIN tweb_penduduk_agama a ON COALESCE(pend.agama_id, p.pamong_agama) = a.id
                    LEFT JOIN tweb_penduduk_pendidikan_kk pk ON COALESCE(pend.pendidikan_kk_id, p.pamong_pendidikan) = pk.id
                    LEFT JOIN tweb_penduduk_sex g ON COALESCE(pend.sex, p.pamong_sex) = g.id
                    {$whereClause}
                    ORDER BY p.urut ASC, p.pamong_id ASC
                    LIMIT " . (int)$start . ", " . (int)$length;

            $result = $this->db_real->query($sql);
            $data = [];

            if ($result && $result->num_rows() > 0) {
                $idx = $start + 1;
                foreach ($result->result() as $row) {
                    $foto_src = AmbilFoto($row->foto ?: $row->pend_foto, '', $row->pamong_sex ?: $row->pend_sex ?: 1);
                    $id = $row->pamong_id;

                    // 1. Edit Button
                    $edit_btn = '<a href="' . site_url("pengurus/form/{$id}") . '" class="btn btn-sm" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;border-radius:8px;padding:0;transition:all 0.2s;" title="Ubah Data Pamong">
                        <i class="fa fa-pencil" style="font-size:13px;"></i>
                    </a>';

                    // 2. Status Lock/Unlock Button
                    if ((int)$row->pamong_status === 1) {
                        $status_btn = '<a href="' . site_url("pengurus/lock/{$id}") . '" class="btn btn-sm" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;border-radius:8px;padding:0;transition:all 0.2s;" title="Status: Aktif (Klik untuk Nonaktifkan)">
                            <i class="fa fa-unlock" style="font-size:13px;"></i>
                        </a>';
                    } else {
                        $status_btn = '<a href="' . site_url("pengurus/unlock/{$id}") . '" class="btn btn-sm" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#f8fafc;color:#64748b;border:1px solid #cbd5e1;border-radius:8px;padding:0;transition:all 0.2s;" title="Status: Tidak Aktif (Klik untuk Mengaktifkan)">
                            <i class="fa fa-lock" style="font-size:13px;"></i>
                        </a>';
                    }

                    // 3. Tanda Tangan a.n Button (jika bukan kades)
                    $ttd_btn = '';
                    if ((int)$row->jabatan_id !== 1) {
                        if ((int)$row->pamong_ttd === 1) {
                            $ttd_btn = '<a href="' . site_url("pengurus/ttd/{$id}/0") . '" class="btn btn-sm" style="display:inline-flex;align-items:center;justify-content:center;height:32px;padding:0 8px;background:#fef3c7;color:#b45309;border:1px solid #fde68a;border-radius:8px;font-size:11px;font-weight:700;transition:all 0.2s;" title="Hak Penandatanganan: a.n Aktif (Klik untuk Matikan)">
                                a.n
                            </a>';
                        } else {
                            $ttd_btn = '<a href="' . site_url("pengurus/ttd/{$id}/1") . '" class="btn btn-sm" style="display:inline-flex;align-items:center;justify-content:center;height:32px;padding:0 8px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;border-radius:8px;font-size:11px;font-weight:700;transition:all 0.2s;" title="Beri Hak Penandatanganan: a.n">
                                a.n
                            </a>';
                        }
                    }

                    // 4. Delete Button
                    $del_btn = '<a href="' . site_url("pengurus/delete/{$id}") . '" class="btn btn-sm btn-hapus-row" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:8px;padding:0;transition:all 0.2s;" title="Hapus Data Pamong" onclick="return confirm(\'Yakin ingin menghapus pamong ini?\');">
                        <i class="fa fa-trash-o" style="font-size:13px;"></i>
                    </a>';

                    $aksi = '<div class="btn-group" style="display:inline-flex;gap:4px;align-items:center;justify-content:center;">'
                        . $edit_btn
                        . $status_btn
                        . $ttd_btn
                        . $del_btn
                        . '</div>';

                    $identitas = '<div style="font-weight: 700; color: #1e293b; font-size: 13px;">' . htmlspecialchars($row->pamong_nama) . '</div>'
                        . '<div style="font-size: 11px; color: #64748b; margin-top: 2px;">'
                        . 'NIK: <span style="color: #0f172a; font-weight: 600;">' . ($row->pamong_nik ?: $row->pend_nik ?: '-') . '</span>'
                        . ($row->pamong_nip ? ' | NIP: ' . htmlspecialchars($row->pamong_nip) : '')
                        . ($row->pamong_tag_id_card ? ' | RFID: ' . htmlspecialchars($row->pamong_tag_id_card) : '')
                        . '</div>';

                    $tempat = $row->pamong_tempatlahir ?: $row->pend_tempatlahir ?: '-';
                    $tgl = $row->pamong_tgl_lahir ?: $row->pamong_tanggallahir ?: $row->pend_tanggallahir ?: null;
                    $ttl = $tempat . ($tgl ? ', ' . (function_exists('tgl_indo_out') ? tgl_indo_out($tgl) : $tgl) : '');

                    $sex_val = ($row->pamong_sex == 1 || $row->pend_sex == 1) ? 'LAKI-LAKI' : (($row->pamong_sex == 2 || $row->pend_sex == 2) ? 'PEREMPUAN' : ($row->sex_nama ?: '-'));

                    $data[] = [
                        'drag-handle'       => '<i class="fa fa-arrows-v drag-handle" style="cursor: move; color: #94a3b8;"></i>',
                        'ceklist'           => '<input type="checkbox" name="id_cb[]" value="' . $row->pamong_id . '"><input type="hidden" name="pamong_id[]" value="' . $row->pamong_id . '">',
                        'DT_RowIndex'       => $idx++,
                        'aksi'              => $aksi,
                        'foto'              => '<img class="foto-pamong" src="' . $foto_src . '" alt="Foto" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">',
                        'identitas'         => $identitas,
                        'ttl'               => $ttl,
                        'pamong_sex'        => '<span class="badge" style="background: #f1f5f9; color: #334155; font-weight: 700; border: 1px solid #cbd5e1;">' . $sex_val . '</span>',
                        'agama'             => $row->agama_nama ?: ($row->pamong_agama ?: '-'),
                        'pamong_pangkat'    => $row->pamong_pangkat ?: '-',
                        'jabatan'           => ['nama' => $row->jabatan_nama ?: '-'],
                        'pendidikan_kk'     => $row->pendidikan_nama ?: ($row->pamong_pendidikan ?: '-'),
                        'pamong_nosk'       => $row->pamong_nosk ?: '-',
                        'pamong_tglsk'      => $row->pamong_tglsk ? (function_exists('tgl_indo_out') ? tgl_indo_out($row->pamong_tglsk) : $row->pamong_tglsk) : '-',
                        'pamong_nohenti'    => $row->pamong_nohenti ?: '-',
                        'pamong_tglhenti'   => $row->pamong_tglhenti ? (function_exists('tgl_indo_out') ? tgl_indo_out($row->pamong_tglhenti) : $row->pamong_tglhenti) : '-',
                        'pamong_masajab'    => $row->pamong_masajab ?: '-',
                        'pamong_id'         => $row->pamong_id,
                        'jabatan_id'        => $row->jabatan_id,
                    ];
                }
            }

            echo json_encode([
                'draw'            => (int)$draw,
                'recordsTotal'    => (int)$recordsTotal,
                'recordsFiltered' => (int)$recordsFiltered,
                'data'            => $data,
            ]);
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_neural_error.log',
                "[" . date('Y-m-d H:i:s') . "] PENGURUS DT ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['draw' => (int)$draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }

    public function lock($id)
    {
        if ($this->db_real && $id) {
            $this->db_real->query("UPDATE tweb_desa_pamong SET pamong_status = 2 WHERE pamong_id = " . (int)$id);
        }
        redirect('pengurus');
    }

    public function unlock($id)
    {
        if ($this->db_real && $id) {
            $this->db_real->query("UPDATE tweb_desa_pamong SET pamong_status = 1 WHERE pamong_id = " . (int)$id);
        }
        redirect('pengurus');
    }

    public function ttd($id, $val = null)
    {
        if ($this->db_real && $id) {
            if ($val !== null) {
                $new_val = (int)$val;
            } else {
                $q = $this->db_real->query("SELECT pamong_ttd FROM tweb_desa_pamong WHERE pamong_id = " . (int)$id);
                $curr = ($q && $q->num_rows() > 0) ? (int)$q->row()->pamong_ttd : 0;
                $new_val = ($curr === 1) ? 0 : 1;
            }
            $this->db_real->query("UPDATE tweb_desa_pamong SET pamong_ttd = {$new_val} WHERE pamong_id = " . (int)$id);
        }
        redirect('pengurus');
    }

    public function delete($id)
    {
        if ($this->db_real && $id) {
            $this->db_real->query("DELETE FROM tweb_desa_pamong WHERE pamong_id = " . (int)$id);
        }
        redirect('pengurus');
    }

    public function delete_all()
    {
        $ids = $this->input->post('id_cb');
        if ($this->db_real && !empty($ids) && is_array($ids)) {
            $clean_ids = array_map('intval', $ids);
            $id_str = implode(',', $clean_ids);
            $this->db_real->query("DELETE FROM tweb_desa_pamong WHERE pamong_id IN ({$id_str})");
        }
        redirect('pengurus');
    }

    public function tukar()
    {
        $id = $this->input->post('id');
        $target_id = $this->input->post('target_id');
        if ($this->db_real && $id && $target_id) {
            $q1 = $this->db_real->query("SELECT urut FROM tweb_desa_pamong WHERE pamong_id = " . (int)$id);
            $q2 = $this->db_real->query("SELECT urut FROM tweb_desa_pamong WHERE pamong_id = " . (int)$target_id);
            $u1 = ($q1 && $q1->num_rows() > 0) ? (int)$q1->row()->urut : 0;
            $u2 = ($q2 && $q2->num_rows() > 0) ? (int)$q2->row()->urut : 0;
            $this->db_real->query("UPDATE tweb_desa_pamong SET urut = {$u2} WHERE pamong_id = " . (int)$id);
            $this->db_real->query("UPDATE tweb_desa_pamong SET urut = {$u1} WHERE pamong_id = " . (int)$target_id);
            echo json_encode(['status' => true]);
            return;
        }
        echo json_encode(['status' => false]);
    }

    public function form($id = null)
    {
        $pamong = [
            'pamong_id' => '',
            'id_pend' => '',
            'pamong_nama' => '',
            'pamong_nik' => '',
            'pamong_nip' => '',
            'pamong_niap' => '',
            'pamong_tag_id_card' => '',
            'pamong_tempatlahir' => '',
            'pamong_tanggallahir' => '',
            'pamong_sex' => '1',
            'pamong_sex_id' => '1',
            'pamong_agama' => '1',
            'pamong_pendidikan' => '1',
            'pamong_pangkat' => '',
            'pamong_nosk' => '',
            'pamong_tglsk' => '',
            'pamong_nohenti' => '',
            'pamong_tglhenti' => '',
            'pamong_masajab' => '',
            'pamong_status' => '1',
            'status_kehadiran' => '1',
            'jabatan_id' => '',
            'atasan' => '',
            'bagan_tingkat' => '',
            'bagan_offset' => '',
            'bagan_layout' => '',
            'bagan_warna' => '',
            'foto' => '',
            'foto_staff' => '',
            'gelar_depan' => '',
            'gelar_belakang' => '',
            'media_sosial' => [],
        ];

        $aksi = 'Tambah';
        $individu = null;
        if ($id && $this->db_real) {
            $q = $this->db_real->where('pamong_id', (int)$id)->get('tweb_desa_pamong');
            if ($q && $q->num_rows() > 0) {
                $pamong = array_merge($pamong, $q->row_array());
                $pamong['pamong_sex_id'] = (string)($pamong['pamong_sex'] ?: '1');
                $pamong['id_sex'] = (string)($pamong['pamong_sex'] ?: '1');
                $pamong['foto_staff'] = $pamong['foto'];
                if (is_string($pamong['media_sosial']) && !empty($pamong['media_sosial'])) {
                    $pamong['media_sosial'] = json_decode($pamong['media_sosial'], true) ?: [];
                } elseif (!is_array($pamong['media_sosial'])) {
                    $pamong['media_sosial'] = [];
                }
                $aksi = 'Ubah';

                if (!empty($pamong['id_pend'])) {
                    $qPend = $this->db_real->where('id', (int)$pamong['id_pend'])->get('tweb_penduduk');
                    if ($qPend && $qPend->num_rows() > 0) {
                        $individu = $qPend->row_array();
                    }
                }
            }
        }

        $jabatan = [];
        $agama = [];
        $pendidikan = [];
        $atasan = [];

        try {
            if ($this->db_real) {
                $qJ = $this->db_real->order_by('id', 'ASC')->get('ref_jabatan');
                if ($qJ) $jabatan = $qJ->result_array();

                $qA = $this->db_real->get('tweb_penduduk_agama');
                if ($qA) $agama = $qA->result_array();

                $qP = $this->db_real->get('tweb_penduduk_pendidikan_kk');
                if ($qP) $pendidikan = $qP->result_array();

                $qAt = $this->db_real->query("SELECT p.pamong_id as id, p.pamong_nama as nama, COALESCE(j.nama, '-') as jabatan FROM tweb_desa_pamong p LEFT JOIN ref_jabatan j ON p.jabatan_id = j.id WHERE p.pamong_status = 1 ORDER BY p.urut ASC");
                if ($qAt) $atasan = $qAt->result();
            }
        } catch (\Throwable $e) {}

        $media_sosial = [
            ['id' => 'facebook', 'name' => 'Facebook', 'url' => 'https://facebook.com'],
            ['id' => 'twitter', 'name' => 'Twitter/X', 'url' => 'https://x.com'],
            ['id' => 'instagram', 'name' => 'Instagram', 'url' => 'https://instagram.com'],
            ['id' => 'telegram', 'name' => 'Telegram', 'url' => 'https://t.me'],
            ['id' => 'whatsapp', 'name' => 'WhatsApp', 'url' => 'https://wa.me'],
        ];

        $imageInfo = [
            'width' => 130,
            'height' => 150,
        ];

        $data = [
            'pamong'       => $pamong,
            'individu'     => $individu,
            'imageInfo'    => $imageInfo,
            'media_sosial' => $media_sosial,
            'form_action'  => site_url("pengurus/" . ($id ? "update/{$id}" : "insert")),
            'controller'   => 'pengurus',
            'aksi'         => $aksi,
            'action'       => $aksi,
            'jabatan'      => $jabatan,
            'agama'        => $agama,
            'pendidikan'   => $pendidikan,
            'atasan'       => $atasan,
            'sex'          => ['1' => 'LAKI-LAKI', '2' => 'PEREMPUAN'],
            'status'       => ['1' => 'Aktif', '2' => 'Tidak Aktif'],
            'pemerintah'   => 'Staf ' . ucwords(setting('sebutan_pemerintah_desa')),
            'desa'         => $this->desa,
        ];

        $this->renderBlade('admin.pengurus.form', $data);
    }

    public function jabatan()
    {
        $data = [
            'main_content' => 'admin.jabatan.index',
            'selected_nav' => 'pengurus',
            'controller'   => 'pengurus',
            'subtitle'     => 'Daftar Jabatan Pengurus',
            'desa'         => $this->desa,
            'hapus'        => 1,
            'ubah'         => 1,
        ];

        $this->renderBlade('admin.bumindes.umum.main', $data);
    }

    public function jabatan_datatables()
    {
        if (!$this->db_real) {
            echo json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            return;
        }

        $draw   = $this->input->post('draw') ?: 1;
        $start  = $this->input->post('start') ?: 0;
        $length = $this->input->post('length') ?: 10;
        $search = $this->input->post('search')['value'] ?? '';

        try {
            $where = [];
            if (!empty($search)) {
                $s_esc = $this->db_real->escape_like_str($search);
                $where[] = "(nama LIKE '%{$s_esc}%' OR tupoksi LIKE '%{$s_esc}%')";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $totQ = $this->db_real->query("SELECT COUNT(*) as total FROM ref_jabatan");
            $recordsTotal = ($totQ && $totQ->num_rows() > 0) ? (int)$totQ->row()->total : 0;

            $countQ = $this->db_real->query("SELECT COUNT(*) as total FROM ref_jabatan {$whereClause}");
            $recordsFiltered = ($countQ && $countQ->num_rows() > 0) ? (int)$countQ->row()->total : 0;

            $sql = "SELECT * FROM ref_jabatan {$whereClause} ORDER BY id ASC LIMIT " . (int)$start . ", " . (int)$length;
            $result = $this->db_real->query($sql);
            $data = [];

            if ($result && $result->num_rows() > 0) {
                $idx = $start + 1;
                foreach ($result->result() as $row) {
                    $id = $row->id;
                    $jenis = (int)($row->jenis ?? 0);

                    // 1. Edit Button
                    $edit_btn = '<a href="' . site_url("pengurus/jabatanform/{$id}") . '" class="table-action-btn btn-ubah" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#eff6ff !important;color:#2563eb !important;border:1px solid #bfdbfe !important;border-radius:8px !important;text-decoration:none !important;" title="Ubah Data Jabatan">
                        <i class="fa fa-pencil" style="font-size:13px;color:#2563eb;"></i>
                    </a>';

                    // 2. Delete Button (Only for non-kades / non-sekdes)
                    $del_btn = '';
                    if ($jenis === 0) {
                        $del_btn = '<a href="' . site_url("pengurus/jabatandelete/{$id}") . '" class="table-action-btn btn-hapus" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#fef2f2 !important;color:#dc2626 !important;border:1px solid #fecaca !important;border-radius:8px !important;text-decoration:none !important;" title="Hapus Jabatan" onclick="return confirm(\'Yakin ingin menghapus jabatan ini?\');">
                            <i class="fa fa-trash-o" style="font-size:13px;color:#dc2626;"></i>
                        </a>';
                    }

                    $aksi = '<div class="btn-group" style="display:inline-flex;gap:4px;align-items:center;justify-content:center;vertical-align:middle;">'
                        . $edit_btn
                        . $del_btn
                        . '</div>';

                    $ceklist = ($jenis === 0)
                        ? '<input type="checkbox" name="id_cb[]" value="' . $id . '"><input type="hidden" name="id[]" value="' . $id . '">'
                        : '';

                    $data[] = [
                        'ceklist'     => $ceklist,
                        'DT_RowIndex' => $idx++,
                        'aksi'        => $aksi,
                        'nama'        => '<span style="font-weight: 700; color: #1e293b; font-size: 13.5px;">' . htmlspecialchars($row->nama) . '</span>',
                        'tupoksi'     => !empty($row->tupoksi) ? '<div style="font-size: 12px; color: #475569; max-height: 48px; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;">' . strip_tags($row->tupoksi) . '</div>' : '<span style="color: #94a3b8; font-style: italic;">- Tidak ada tupoksi -</span>',
                        'jenis'       => $jenis,
                        'id'          => $id,
                    ];
                }
            }

            echo json_encode([
                'draw'            => (int)$draw,
                'recordsTotal'    => (int)$recordsTotal,
                'recordsFiltered' => (int)$recordsFiltered,
                'data'            => $data,
            ]);
        } catch (\Throwable $e) {
            echo json_encode(['draw' => (int)$draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }

    public function jabatanDatatables()
    {
        return $this->jabatan_datatables();
    }

    public function jabatanform($id = null)
    {
        $jabatan = [
            'id'      => '',
            'nama'    => '',
            'tupoksi' => '',
            'jenis'   => 0,
        ];
        $action = 'Tambah';

        if ($id && $this->db_real) {
            $q = $this->db_real->where('id', (int)$id)->get('ref_jabatan');
            if ($q && $q->num_rows() > 0) {
                $jabatan = $q->row_array();
                $action = 'Ubah';
            }
        }

        $data = [
            'jabatan'      => $jabatan,
            'action'       => $action,
            'form_action'  => site_url("pengurus/" . ($id ? "update_jabatan/{$id}" : "insert_jabatan")),
            'kades_sekdes' => [1, 2],
            'desa'         => $this->desa,
        ];

        $this->renderBlade('admin.jabatan.form', $data);
    }

    public function insert_jabatan()
    {
        $nama = $this->input->post('nama');
        $tupoksi = $this->input->post('tupoksi');
        if ($this->db_real && !empty($nama)) {
            $this->db_real->insert('ref_jabatan', [
                'config_id'  => 1,
                'nama'       => trim($nama),
                'tupoksi'    => $tupoksi,
                'jenis'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
            ]);
        }
        redirect('pengurus/jabatan');
    }

    public function update_jabatan($id)
    {
        $nama = $this->input->post('nama');
        $tupoksi = $this->input->post('tupoksi');
        if ($this->db_real && $id && !empty($nama)) {
            $this->db_real->where('id', (int)$id)->update('ref_jabatan', [
                'nama'       => trim($nama),
                'tupoksi'    => $tupoksi,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1,
            ]);
        }
        redirect('pengurus/jabatan');
    }

    public function insertJabatan()
    {
        return $this->insert_jabatan();
    }

    public function updateJabatan($id)
    {
        return $this->update_jabatan($id);
    }

    public function jabatandelete($id)
    {
        if ($this->db_real && $id) {
            $this->db_real->query("DELETE FROM ref_jabatan WHERE id = " . (int)$id . " AND (jenis = 0 OR jenis IS NULL)");
        }
        redirect('pengurus/jabatan');
    }

    public function jabatandelete_all()
    {
        $ids = $this->input->post('id_cb');
        if ($this->db_real && !empty($ids) && is_array($ids)) {
            $clean_ids = array_map('intval', $ids);
            $id_str = implode(',', $clean_ids);
            $this->db_real->query("DELETE FROM ref_jabatan WHERE id IN ({$id_str}) AND (jenis = 0 OR jenis IS NULL)");
        }
        redirect('pengurus/jabatan');
    }
}

if (!class_exists('Pengurus')) {
    class Pengurus extends Pengurus_neural {}
}
