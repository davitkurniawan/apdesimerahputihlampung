<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web_widget_tukar extends MY_Controller {
    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('user')) {
            header('HTTP/1.1 403 Forbidden');
            exit('No access');
        }
    }

    public function index() {
        $data = $this->input->post('data');
        if (is_array($data)) {
            foreach ($data as $index => $id) {
                $this->db->where('id', $id);
                $this->db->update('widget', ['urut' => $index + 1]);
            }
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
    }
}
