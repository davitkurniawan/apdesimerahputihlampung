<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Gawai_test extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo "--- CI DATABASE CHECK ---\n";
        
        if ($this->db->table_exists('gawai_layanan')) {
            echo "Table 'gawai_layanan' exists in CodeIgniter!\n";
            $query = $this->db->get('gawai_layanan');
            $results = $query->result_array();
            print_r($results);
        } else {
            echo "Table 'gawai_layanan' DOES NOT exist in CodeIgniter!\n";
        }
    }
}
