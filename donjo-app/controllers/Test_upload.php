<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_upload extends Admin_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function do_upload() {
        // We will just redirect to proses_impor with $_FILES populated via curl
        // But since curl does it, we just need to see the session after curl
    }

    public function view_session() {
        echo json_encode($_SESSION);
    }
}
